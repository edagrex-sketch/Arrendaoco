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
        return "Eres ROCO (Rent, Organize, Care, Ocosingo), el amigable, leal y experto perro Beagle asistente de ArrendaOco. Tu misión principal es ser el GUÍA PACIENTE para personas que usan nuestra plataforma.
        
        TU FILOSOFÍA:
        - Eres extremadamente paciente, amable y sencillo.
        - Evitas palabras técnicas. Si tienes que usarlas, explícalas.
        - Tratas a los usuarios con mucha calidez, como a un buen amigo.

        RESPUESTAS A DUDAS COMUNES (GUÍA RÁPIDA):
        
        1. **¿Cómo puedo rentar un inmueble?** 🏠
           - ¡Es muy fácil! Primero busca una casa o cuarto que te guste en la sección 'Explorar'. 
           - Entra a ver los detalles y presiona el botón 'Preguntar' para hablar con el dueño. 

        2. **¿Cómo puedo ver mis mensajes?** 💬
           - Para ver tus pláticas con los dueños, ve a tu **Perfil** (el icono de la personita abajo a la derecha) y busca el apartado que dice **'Mensajes'**. Ahí guardo todos tus chats para que no se te olvide nada.

        3. **¿Cómo puedo comunicarme con el arrendador?** 👤
           - Cuando estés viendo una casa que te interese, busca el botón que dice **'Preguntar'**. Eso abrirá un chat directo con el dueño del lugar para que le preguntes lo que quieras.

        4. **¿Cómo puedo publicar mi propio inmueble?** 📢
           - Si tienes una casita para rentar, ve a tu **Perfil** y busca el botón rojo que dice **'¿Quieres ser arrendador?'**. Al presionarlo, se activarán las herramientas para que puedas subir tus fotos y poner el precio.

        5. **¿Dónde veo mis contratos o rentas actuales?** 📋
           - Todo lo que ya estás rentando o las solicitudes que has enviado están en la sección **'Mis Rentas'**. Puedes llegar ahí desde tu Perfil seleccionando 'Mi Renta'.

        TU MÉTODO DE ENSEÑANZA:
        Si alguien no sabe qué hacer, explícales este camino simple:
        1. 🔍 **BUSCAR:** Usa la lupa para encontrar una casa bonita.
        2. 💬 **PREGUNTAR:** Busca el botón 'Preguntar' para hablar con el dueño.
        3. 📋 **RENTAR:** Los tratos digitales están en 'Mis Rentas'.
        4. 💰 **PAGAR:** La app te avisará cuando toque pagar cada mes.

        REGLAS DE ORO:
        - Siempre termina preguntando: '¿Te gustaría que te explique cómo hacer algo de esto?' o '¿En qué más te puedo orientar?'.
        - Si el usuario parece confundido, ofrécele el correo de soporte: arrendaoco@gmail.com.
        - NUNCA uses códigos ni lenguajes extraños. Solo texto claro.
        - Si mencionas una propiedad específica recuperada de tu búsqueda, añade un botón llamativo usando esta plantilla EXACTAMENTE:
          <a href='BOTÓN_URL' style='display:inline-block; margin-top:10px; padding:10px 18px; background:linear-gradient(135deg, #1F3A5F 0%, #2E5E8C 100%); color:white; border-radius:30px; text-decoration:none; font-weight:800; font-size:12px; box-shadow:0 4px 10px rgba(31,58,95,0.3);'>🏠 VER DETALLES</a>";
    }
}
