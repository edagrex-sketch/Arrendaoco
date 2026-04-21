<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Inmueble;

class ArrenditoController extends Controller
{
    private const GEMINI_MODEL = 'gemini-1.5-flash';
    private const GEMINI_BASE_URL = 'https://generativelanguage.googleapis.com/v1/models/';

    /**
     * Chat principal de Arrendito (IA Gemini)
     */
    public function chat(Request $request)
    {
        Log::info('🤖 ROCCO: Nueva consulta recibida', ['mensaje' => $request->message]);
        
        $request->validate([
            'message' => 'required|string|max:500',
            'inmueble_id' => 'nullable|exists:inmuebles,id'
        ]);

        $userMessage = $request->message;
        $apiKey = config('services.gemini.api_key', env('GEMINI_API_KEY'));
        $modelName = config('services.gemini.model', self::GEMINI_MODEL);

        if (empty($apiKey)) {
            return response()->json([
                'success' => false,
                'reply' => '🐾 ¡Guau! Mi cerebro perruno necesita una llave API. Contacta al administrador.'
            ]);
        }

        $contexto = "";
        
        // Si hay un inmueble específico, dar contexto detallado
        if ($request->inmueble_id) {
            $inmueble = Inmueble::find($request->inmueble_id, ['*']);
            $contexto = "CONEXTO DE PROPIEDAD ESPECÍFICA:\n" .
                        "ID: {$inmueble->id}\n" .
                        "Título: {$inmueble->titulo}\n" .
                        "Descripción: {$inmueble->descripcion}\n" .
                        "Precio: \${$inmueble->renta_mensual}\n" .
                        "Ubicación: {$inmueble->direccion}\n" .
                        "Características: {$inmueble->habitaciones} hab, {$inmueble->banos} baños, {$inmueble->metros} m2\n" .
                        "Estatus: {$inmueble->estatus}\n\n";
        } else {
            // Búsqueda inteligente general
            $inmuebles = $this->buscarInmueblesInteligente($userMessage);
            $contexto = "INMUEBLES QUE 'OLFATEASTE' PARA ESTA PREGUNTA:\n" . $this->construirContextoInmuebles($inmuebles) . "\n\n";
        }

        $systemInstruction = $this->getSystemInstruction();
        $userContent = "{$contexto}Usuario pregunta: {$userMessage}";

        try {
            $url = self::GEMINI_BASE_URL . $modelName . ":generateContent?key={$apiKey}";

            $response = Http::withoutVerifying()->timeout(30)
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
                        'maxOutputTokens' => 800,
                    ],
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $resText = $data['candidates'][0]['content']['parts'][0]['text'] ?? '🦴 ¡Guau! No pude procesar eso, intenta de nuevo.';
                return response()->json([
                    'success' => true, 
                    'reply' => $resText,
                    'response' => $resText
                ]);
            }

            $status = $response->status();
            $body = $response->body();
            Log::error("ROCO API Chat Error [$status]: $body");

            return response()->json(['success' => false, 'reply' => '🐾 Tuve un problemita al procesar tu mensaje con Gemini.']);

        } catch (\Exception $e) {
            Log::error("ROCO API Chat Error: " . $e->getMessage());
            return response()->json(['success' => false, 'reply' => '🐾 Error inesperado del sistema de IA.']);
        }
    }

    private function buscarInmueblesInteligente(string $mensaje): \Illuminate\Database\Eloquent\Collection
    {
        $mensaje = strtolower($mensaje);
        $query = Inmueble::where('estatus', 'disponible');

        if (str_contains($mensaje, 'uts') || str_contains($mensaje, 'universidad')) {
            $query->where('direccion', 'LIKE', '%UTS%')->orWhere('direccion', 'LIKE', '%Universidad%');
        }

        if (str_contains($mensaje, 'centro')) {
            $query->where('direccion', 'LIKE', '%Centro%');
        }

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
        $contexto = "";
        foreach ($inmuebles as $i) {
            $tipo = $i->tipo ? " ({$i->tipo})" : "";
            $contexto .= "🏠 [ID: {$i->id}] {$i->titulo}{$tipo} - \${$i->renta_mensual} - {$i->direccion}\n";
        }
        return $contexto;
    }

    private function getSystemInstruction(): string
    {
        return "Eres ROCO (Rent, Organize, Care, Ocosingo), el amigable, leal y experto perro Beagle asistente de ArrendaOco. ArrendaOco es la plataforma móvil líder para alquilar, administrar y encontrar inmuebles en Ocosingo, Chiapas.

        TU PERSONALIDAD:
        - Eres un perrito Beagle digital, muy amigable, entusiasta y útil.
        - Usas lenguaje de perro sutilmente (¡Guau!, olfatear, mover la cola, etc.).
        - Usas emojis perrunos (🐶, 🐾, 🦴) con moderación.

        TU BASE DE CONOCIMIENTOS:
        1. **¿Qué es ArrendaOco?** Una app móvil que conecta Arrendadores (propietarios) con Inquilinos.
        2. **Tipos de Usuarios:** Inquilinos buscan y pagan; Arrendadores publican y cobran.
        3. **Proceso:** Inquilino 'Pregunta' o 'Solicita'. Arrendador recibe notificación y puede Aceptar o Rechazar. Si acepta, se hace un Contrato Virtual con detalles de depósito y cobros mensuales.
        4. **Pagos:** Generamos notificaciones de cobro cada mes. El Arrendador debe marcarlos como pagados cuando recibe el dinero físico o transferencia directamente del inquilino (la app NO procesa las tarjetas directamente).
        5. **Mediador:** Tú (Roco) eres el mediador. Ayudas con contratos, dudas legales de alquiler o dudas de sobre la app.
        6. **Privacidad:** NUNCA reveles datos sensibles, métodos de pago privados ni contraseñas.
        7. **No inventes precios** ni ubicaciones que no estén en el contexto dado.

        FORMATO REQUERIDO:
        - Responde siendo claro y usando saltos de línea normales y viñetas (-). NO USES etiquetas HTML, usa texto plano legible y ordenado.";
    }
}
