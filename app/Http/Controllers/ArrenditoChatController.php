<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Inmueble;

class ArrenditoChatController extends Controller
{
    /**
     * Modelo de Gemini a utilizar (Fallback si no está en config).
     */
    private const DEFAULT_GEMINI_MODEL = 'gemini-1.5-flash';

    /**
     * Endpoint base de la API de Gemini.
     */
    private const GEMINI_BASE_URL = 'https://generativelanguage.googleapis.com/v1beta/models/';

    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500',
            'inmueble_id' => 'nullable|exists:inmuebles,id'
        ]);
        
        $userMessage = $request->message;
        $apiKey = config('services.gemini.api_key', env('GEMINI_API_KEY'));

        if (empty($apiKey)) {
            Log::error('ROCO Chat: GEMINI_API_KEY no está configurada.');
            return response()->json([
                'success' => false,
                'response' => '¡Guau! 🐾 Mi cerebro perruno necesita una llave API para funcionar. Contacta al administrador.'
            ]);
        }

        $contexto = "";
        
        if ($request->inmueble_id) {
            $inmueble = Inmueble::find($request->inmueble_id, ['*']);
            $contexto = "EL USUARIO ESTÁ PREGUNTANDO SOBRE ESTA PROPIEDAD ESPECÍFICA:\n" .
                        "Título: {$inmueble->titulo}\n" .
                        "Descripción: {$inmueble->descripcion}\n" .
                        "Precio: \${$inmueble->renta_mensual}\n" .
                        "Ubicación: {$inmueble->direccion}\n" .
                        "Características: {$inmueble->habitaciones} hab, {$inmueble->banos} baños, {$inmueble->metros} m2\n" .
                        "Estatus: {$inmueble->estatus}\n\n";
        } else {
            $inmuebles = $this->buscarInmueblesInteligente($userMessage);
            $contexto = "INMUEBLES QUE 'OLFATEASTE' PARA ESTA PREGUNTA:\n" . $this->construirContextoInmuebles($inmuebles) . "\n\n";
        }

        $systemInstruction = $this->getSystemInstruction();
        
        // Detección para móvil: si la ruta empieza por /api o pide JSON explícitamente
        $esMovil = $request->is('api/*');
        if ($esMovil) {
            $systemInstruction .= "\n\nREGLA CRÍTICA PARA MÓVIL: Estás respondiendo a la APP MÓVIL. NO USES ETIQUETAS HTML (<b>, <br>, <a>, etc.). Usa texto plano, saltos de línea normales (\n) y viñetas simples (-). Las negritas de markdown (**) están permitidas, pero evita el HTML.";
        }

        $userContent = "{$contexto}Usuario dice: {$userMessage}";
        $model = config('services.gemini.model', self::DEFAULT_GEMINI_MODEL);

        try {
            $url = self::GEMINI_BASE_URL . $model . ":generateContent?key={$apiKey}";

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
                        'temperature' => 0.7,
                        'maxOutputTokens' => 1024,
                    ],
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $resText = $data['candidates'][0]['content']['parts'][0]['text'] ?? '¡Guau! 🐾 No pude procesar eso, intenta de nuevo.';
                
                // Si es móvil, limpiar HTML por seguridad
                if ($esMovil) {
                    $resText = strip_tags($resText);
                }

                // Retornar ambos para compatibilidad con Web (response) y Móvil (reply)
                return response()->json([
                    'success' => true, 
                    'response' => $resText,
                    'reply' => $resText
                ]);
            }

            $status = $response->status();
            $body = $response->body();
            Log::error("ROCO Chat - Error API Gemini [$status]: $body");

            return response()->json([
                'success' => false, 
                'response' => '¡Guau! 🐾 Mi cerebro perruno se distrajo con una mariposa. Intenta de nuevo en un momento.',
                'error_detail' => "API Error $status"
            ]);

        } catch (\Exception $e) {
            Log::error("ROCO Chat - Excepción: " . $e->getMessage());
            return response()->json([
                'success' => false, 
                'response' => '¡Guau! 🐾 Error inesperado en mi caseta digital.'
            ]);
        }
    }

    private function buscarInmueblesInteligente(string $mensaje): \Illuminate\Database\Eloquent\Collection
    {
        $mensaje = strtolower($mensaje);
        $query = Inmueble::where('estatus', 'disponible');

        // UTS
        if (str_contains($mensaje, 'uts') || str_contains($mensaje, 'universidad')) {
            $query->where('direccion', 'LIKE', '%UTS%', 'or')->orWhere('direccion', 'LIKE', '%Universidad%', 'or');
        }

        // Centro
        if (str_contains($mensaje, 'centro')) {
            $query->where('direccion', 'LIKE', '%Centro%');
        }

        // Precio
        if (preg_match('/(\d[\d,]*)\s*(?:pesos|mxn|\$)/i', $mensaje, $matches)) {
            $precio = (int) str_replace(',', '', $matches[1]);
            $query->where('renta_mensual', '<=', $precio * 1.2);
        }

        $inmuebles = $query->limit(5)->get(['id', 'titulo', 'renta_mensual', 'direccion', 'tipo']);

        if ($inmuebles->isEmpty()) {
            $inmuebles = Inmueble::where('estatus', 'disponible')->latest()->limit(5)->get(['id', 'titulo', 'renta_mensual', 'direccion', 'tipo']);
        }

        return $inmuebles;
    }

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

    private function getSystemInstruction(): string
    {
        return "Eres ROCO (Rent, Organize, Care, Ocosingo), el amigable, leal y experto perro Beagle asistente de ArrendaOco. Tu misión principal es ser el GUÍA PACIENTE para personas que no saben usar aplicaciones de tecnología o de rentas.
        
        TU FILOSOFÍA:
        - Eres extremadamente paciente, amable y sencillo.
        - Evitas palabras técnicas. Si tienes que usarlas, explícalas (ej: 'El depósito es un dinero de seguridad que se guarda por si algo se rompe').
        - Tratas a los usuarios con mucha calidez, como a un buen amigo.

        TU MÉTODO DE ENSEÑANZA (PASO A PASO):
        Si alguien no sabe qué hacer, explícales este camino simple:
        1. 🔍 **BUSCAR:** Diles que usen la lupa o hablen contigo para encontrar una casa bonita.
        2. 💬 **PREGUNTAR:** Cuando vean algo que les guste, diles que busquen el botón 'Preguntar' para hablar con el dueño.
        3. 📋 **RENTAR:** Si el dueño acepta, se hace un trato digital (contrato) que ellos pueden ver en su lista de 'Mis Rentas'.
        4. 💰 **PAGAR:** Explícales que cada mes la app les avisará y ellos deben pagarle al dueño directamente para que él los marque como 'Al corriente'.

        FUNCIONES CLAVE EXPLICADAS PARA NOVATOS:
        - **Favoritos (Corazón ❤️):** Es como poner una marca en las fotos que más te gustan para no perderlas.
        - **Mis Rentas:** Es la carpeta donde guardas tus tratos actuales y tus recibos de pago.
        - **Perfil:** Es donde están tus datos y tus mensajes con los dueños de las casas.

        REGLAS DE ORO:
        - Siempre termina preguntando: '¿Te gustaría que te explique cómo hacer algo de esto?' o '¿En qué más te puedo orientar?'.
        - Si el usuario parece confundido, ofrécele el correo de soporte: arrendaoco@gmail.com.
        - NUNCA uses códigos ni lenguajes extraños. Solo texto claro.
        - Si mencionas una propiedad específica recuperada de tu búsqueda, añade un botón llamativo usando esta plantilla EXACTAMENTE:
          <a href='BOTÓN_URL' style='display:inline-block; margin-top:10px; padding:10px 18px; background:linear-gradient(135deg, #1F3A5F 0%, #2E5E8C 100%); color:white; border-radius:30px; text-decoration:none; font-weight:800; font-size:12px; box-shadow:0 4px 10px rgba(31,58,95,0.3);'>🏠 VER DETALLES</a>";
    }
}
