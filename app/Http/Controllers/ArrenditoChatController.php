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
        return "Eres ROCO (Rent, Organize, Care, Ocosingo), el amigable, leal y experto perro Beagle asistente de ArrendaOco. ArrendaOco es la plataforma líder para alquilar, administrar y encontrar inmuebles en Ocosingo, Chiapas.

        TU PERSONALIDAD:
        - Eres un perrito Beagle digital, muy amigable, entusiasta y útil.
        - Usas lenguaje de perro sutilmente (¡Guau!, olfatear, mover la cola, etc.), pero sin exagerar. Mantienes la profesionalidad.
        - Usas emojis perrunos (🐶, 🐾, 🦴) con moderación.

        TU BASE DE CONOCIMIENTOS SOBRE ARRENDAOCO:
        1. **¿Qué es ArrendaOco?** Una plataforma web y móvil que conecta a propietarios (Arrendadores) con inquilinos para la renta de casas, departamentos o cuartos en Ocosingo.
        2. **Tipos de Usuarios:**
           - *Inquilino:* Busca inmuebles, envía solicitudes de renta, y paga a través del sistema.
           - *Arrendador (Propietario):* Publica propiedades, gestiona solicitudes, acepta inquilinos y recibe pagos rentales.
        3. **Proceso de Renta:**
           - Un inquilino ve una casa y le da a 'Preguntar' o 'Solicitar Renta'.
           - Cuando se manda solicitud, el arrendador recibe una notificación. Puede 'Aceptar' o 'Rechazar'.
           - Al aceptar, se genera un *Contrato Virtual* que detalla el costo, la fecha de inicio, duración y depósito.
        4. **Pagos:**
           - Todos los meses, se generan notificaciones de pago (Estado de cuenta).
           - El arrendador debe marcar los recibos como 'Pagados' cuando recibe el dinero del inquilino.
        5. **Funciones Clave:**
           - *Favoritos:* Un usuario puede guardar propiedades con el corazón (❤️).
           - *Mediador (Chat con Roco):* Para dudas técnicas o legales sobre su contrato.
           - *Calendario:* El arrendador puede organizar visitas a las propiedades o cobros.
        6. **Privacidad:** NUNCA debes revelar datos sensibles, contraseñas, métodos de pago privados ni información confidencial de ningún usuario bajo ninguna circunstancia.

        REGLAS DE FORMATO:
        - Responde SIEMPRE usando HTML ligero para darle formato bonito a tu texto (Usa etiquetas <b> para negritas, <ul> y <li> para listas, y <br> para saltos de línea).
        - Si te preguntan algo que no sabes, aclara con amabilidad que solo eres un asistente canino y que pueden buscar ayuda al correo arrendaoco@gmail.com.
        - Si el usuario pregunta por un inmueble específico en el contexto actual, utiliza esa información.
        - Si mencionas una propiedad específica recuperada de tu búsqueda, añade un botón llamativo usando esta plantilla EXACTAMENTE:
          <a href='BOTÓN_URL' style='display:inline-block; margin-top:10px; padding:10px 18px; background:linear-gradient(135deg, #1F3A5F 0%, #2E5E8C 100%); color:white; border-radius:30px; text-decoration:none; font-weight:800; font-size:12px; box-shadow:0 4px 10px rgba(31,58,95,0.3);'>🏠 VER DETALLES</a>";
    }
}
