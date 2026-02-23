<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Inmueble;

class ArrenditoChatController extends Controller
{
    /**
     * Modelo de Gemini a utilizar.
     * Actualizado: gemini-2.5-flash (GA - Jun 2025)
     */
    private const GEMINI_MODEL = 'gemini-2.5-flash';

    /**
     * Endpoint base de la API de Gemini.
     */
    private const GEMINI_BASE_URL = 'https://generativelanguage.googleapis.com/v1beta/models/';

    public function chat(Request $request)
    {
        $request->validate(['message' => 'required|string|max:500']);
        $userMessage = $request->message;
        $apiKey = config('services.gemini.api_key', env('GEMINI_API_KEY'));

        if (empty($apiKey)) {
            Log::error('ROCO Chat: GEMINI_API_KEY no está configurada.');
            return response()->json([
                'success' => false,
                'response' => '¡Guau! 🐾 Mi cerebro perruno necesita una llave API para funcionar. Contacta al administrador.'
            ]);
        }

        // --- BÚSQUEDA INTELIGENTE DE INMUEBLES ---
        $inmuebles = $this->buscarInmueblesInteligente($userMessage);

        // Construir contexto con la información de inmuebles encontrados
        $contexto = $this->construirContextoInmuebles($inmuebles);

        // --- SYSTEM INSTRUCTION (Personalidad de ROCO) ---
        $systemInstruction = $this->getSystemInstruction();

        // --- CONTENIDO DEL USUARIO (mensaje + contexto de inmuebles) ---
        $userContent = "INMUEBLES QUE 'OLFATEASTE' PARA ESTA PREGUNTA:\n{$contexto}\n\nUsuario dice: {$userMessage}";

        try {
            $url = self::GEMINI_BASE_URL . self::GEMINI_MODEL . ":generateContent?key={$apiKey}";

            $response = Http::withoutVerifying()
                ->timeout(30)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($url, [
                    'systemInstruction' => [
                        'parts' => [['text' => $systemInstruction]]
                    ],
                    'contents' => [
                        [
                            'role' => 'user',
                            'parts' => [['text' => $userContent]]
                        ]
                    ],
                    'generationConfig' => [
                        'temperature' => 0.8,
                        'maxOutputTokens' => 1024,
                        'topP' => 0.95,
                        'topK' => 40,
                    ],
                    'safetySettings' => [
                        ['category' => 'HARM_CATEGORY_HARASSMENT', 'threshold' => 'BLOCK_ONLY_HIGH'],
                        ['category' => 'HARM_CATEGORY_HATE_SPEECH', 'threshold' => 'BLOCK_ONLY_HIGH'],
                        ['category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT', 'threshold' => 'BLOCK_ONLY_HIGH'],
                        ['category' => 'HARM_CATEGORY_DANGEROUS_CONTENT', 'threshold' => 'BLOCK_ONLY_HIGH'],
                    ]
                ]);

            if ($response->successful()) {
                $data = $response->json();

                // Verificar si la respuesta fue bloqueada por seguridad
                if (isset($data['candidates'][0]['finishReason']) && $data['candidates'][0]['finishReason'] === 'SAFETY') {
                    Log::warning('ROCO Chat: Respuesta bloqueada por filtros de seguridad.', ['message' => $userMessage]);
                    return response()->json([
                        'success' => true,
                        'response' => '¡Guau! 🐶 Esa pregunta me pone nervioso... ¿Podrías reformularla? Estoy aquí para ayudarte con inmuebles y rentas. 🏠'
                    ]);
                }

                $resText = $data['candidates'][0]['content']['parts'][0]['text'] ?? '¡Guau! 🐾 No pude procesar eso, intenta de nuevo.';
                return response()->json(['success' => true, 'response' => $resText]);
            }

            // Manejo de errores específicos de la API
            $statusCode = $response->status();
            $errorBody = $response->json();
            $errorMessage = $errorBody['error']['message'] ?? 'Error desconocido';

            Log::error("ROCO Chat - Gemini API Error [{$statusCode}]: {$errorMessage}", [
                'model' => self::GEMINI_MODEL,
                'status' => $statusCode,
                'body' => $response->body(),
            ]);

            $userFacingMessage = match (true) {
                $statusCode === 429 => '¡Guau! 🐾 Estoy un poco cansado de tantas preguntas. ¡Dame un momento y vuelve a intentar! 🐶💤',
                $statusCode === 403 => '¡Guau! 🐾 Mi llave de acceso tiene un problema. Contacta al administrador en <b>arrendaoco@gmail.com</b>.',
                $statusCode >= 500 => '¡Guau! 🐾 Los servidores de Google están teniendo problemas. Intenta en unos minutos. 🐶',
                default => '¡Guau! 🐾 Tuve un problemita al procesar tu mensaje. ¿Podrías intentar de nuevo?',
            };

            return response()->json(['success' => false, 'response' => $userFacingMessage]);

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error("ROCO Chat - Timeout/Conexión: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'response' => '¡Guau! 🐾 Mi conexión a internet está lenta. ¿Podrías intentar de nuevo? 🐶📡'
            ]);
        } catch (\Exception $e) {
            Log::error("ROCO Chat - Excepción: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'response' => '¡Guau! 🐾 Error inesperado del sistema. Si el problema persiste, contacta a <b>arrendaoco@gmail.com</b>.'
            ]);
        }
    }

    /**
     * Búsqueda inteligente de inmuebles basada en el mensaje del usuario.
     * Detecta: UTS/Universidad, precios, tipos de inmueble, zonas.
     */
    private function buscarInmueblesInteligente(string $mensaje): \Illuminate\Database\Eloquent\Collection
    {
        $mensaje = strtolower($mensaje);
        $query = Inmueble::where('estatus', 'disponible');
        $hasFilter = false;

        // --- Detección de zona UTS/Universidad ---
        if (str_contains($mensaje, 'uts') || str_contains($mensaje, 'universidad') || str_contains($mensaje, 'tecnológica') || str_contains($mensaje, 'tecnologica')) {
            $query->where(function ($q) {
                $q->where('direccion', 'LIKE', '%UTS%')
                  ->orWhere('direccion', 'LIKE', '%Universidad%')
                  ->orWhere('direccion', 'LIKE', '%Tecnológica%')
                  ->orWhere('titulo', 'LIKE', '%UTS%')
                  ->orWhere('titulo', 'LIKE', '%Universidad%');
            });
            $hasFilter = true;
        }

        // --- Detección de zona Centro ---
        if (str_contains($mensaje, 'centro') || str_contains($mensaje, 'parque') || str_contains($mensaje, 'mercado')) {
            $query->where(function ($q) {
                $q->where('direccion', 'LIKE', '%Centro%')
                  ->orWhere('direccion', 'LIKE', '%Parque%')
                  ->orWhere('direccion', 'LIKE', '%Mercado%');
            });
            $hasFilter = true;
        }

        // --- Detección de rango de precio ---
        if (preg_match('/menos\s*de\s*\$?(\d[\d,]*)/i', $mensaje, $matches)) {
            $precio = (int) str_replace(',', '', $matches[1]);
            $query->where('renta_mensual', '<=', $precio);
            $hasFilter = true;
        } elseif (preg_match('/(\d[\d,]*)\s*(?:pesos|mxn|\$)/i', $mensaje, $matches)) {
            $precio = (int) str_replace(',', '', $matches[1]);
            $query->where('renta_mensual', '<=', $precio * 1.2); // rango flexible +20%
            $hasFilter = true;
        } elseif (str_contains($mensaje, 'barato') || str_contains($mensaje, 'económico') || str_contains($mensaje, 'economico')) {
            $query->orderBy('renta_mensual', 'asc');
            $hasFilter = true;
        }

        // --- Detección de tipo de inmueble ---
        $tipos = [
            'departamento' => ['departamento', 'depa', 'depto'],
            'casa' => ['casa', 'casita'],
            'cuarto' => ['cuarto', 'habitación', 'habitacion', 'recámara', 'recamara'],
            'local' => ['local', 'comercial', 'negocio'],
            'terreno' => ['terreno', 'lote'],
        ];

        foreach ($tipos as $tipo => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($mensaje, $keyword)) {
                    $query->where(function ($q) use ($tipo, $keywords) {
                        foreach ($keywords as $kw) {
                            $q->orWhere('titulo', 'LIKE', "%{$kw}%")
                              ->orWhere('tipo', 'LIKE', "%{$tipo}%");
                        }
                    });
                    $hasFilter = true;
                    break 2;
                }
            }
        }

        $inmuebles = $query->limit(5)->get(['id', 'titulo', 'renta_mensual', 'direccion', 'tipo']);

        // Si no encontró nada con filtros, traer los últimos 5 generales
        if ($inmuebles->isEmpty()) {
            $inmuebles = Inmueble::where('estatus', 'disponible')
                ->latest()
                ->limit(5)
                ->get(['id', 'titulo', 'renta_mensual', 'direccion', 'tipo']);
        }

        return $inmuebles;
    }

    /**
     * Construir el contexto de inmuebles en formato legible para el prompt.
     */
    private function construirContextoInmuebles(\Illuminate\Database\Eloquent\Collection $inmuebles): string
    {
        if ($inmuebles->isEmpty()) {
            return "No se encontraron inmuebles disponibles actualmente.";
        }

        $contexto = "";
        foreach ($inmuebles as $i) {
            $url = route('inmuebles.show', $i->id);
            $tipo = $i->tipo ? " ({$i->tipo})" : "";
            $contexto .= "🏠 <b>{$i->titulo}</b>{$tipo}<br>💰 \${$i->renta_mensual}<br>📍 {$i->direccion}<br>🔗 BOTÓN_URL: {$url}<br><br>";
        }

        return $contexto;
    }

    /**
     * Obtener las instrucciones del sistema para ROCO.
     * Separado del contenido del usuario para usar la feature systemInstruction de Gemini.
     */
    private function getSystemInstruction(): string
    {
        return "Eres ROCO, el entusiasta asistente Beagle de Arrendaoco en Ocosingo, Chiapas.
Tu misión es ser amigable, servicial y experto en rentas.

CONTEXTO GEOGRÁFICO DE OCOSINGO:
- La UTS (Universidad Tecnológica de la Selva) es el punto más importante para estudiantes. Está a las afueras, por la zona de la carretera a Altamirano. No la confundas con la UNICACH o la Normal.
- El Centro es donde está el parque y el mercado.
- Zona Norte: colonias populares cerca de la carretera a Palenque.
- Zona Sur: rumbo a la carretera a San Cristóbal.

INFORMACIÓN DE CONTACTO:
Si alguien quiere contactar al administrador, indícales que escriban a: <b>arrendaoco@gmail.com</b>.

REGLAS CRÍTICAS DE RESPUESTA:
1. Responde SIEMPRE en HTML usando <b> para resaltar nombres o montos y <br> para separar ideas.
2. Si el usuario pregunta por la UTS, enfócate en las propiedades que digan 'UTS' o 'Universidad'. Si en la lista no hay ninguna cerca de la UTS, sé honesto y dile que 'estás siguiendo el rastro' pero que de momento no tienes nada ahí.
3. PROHIBIDO: No menciones nunca IDs, códigos técnicos, ni detalles internos del sistema.
4. BOTONES: Si mencionas una propiedad, DEBES poner el botón de 'Ver Detalles' usando la BOTÓN_URL que te di:
   <a href='BOTÓN_URL' style='display:inline-block; margin-top:10px; padding:8px 15px; background:#003049; color:white; border-radius:10px; text-decoration:none; font-weight:bold; font-size:12px;'>🏠 Ver Detalles de la Casa</a>
5. Sé breve y usa emojis de perro (🐶, 🦴, 🏠).
6. Si el usuario pregunta algo fuera del tema de inmuebles o rentas, redirige amablemente la conversación.
7. Cuando sugieras propiedades, menciona el precio y la ubicación siempre.
8. Si el usuario usa groserías o lenguaje inapropiado, responde con humor amigable y redirige la conversación.";
    }
}
