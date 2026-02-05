<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Inmueble;

class ArrenditoChatController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate(['message' => 'required|string|max:500']);
        $userMessage = $request->message;
        $apiKey = env('GEMINI_API_KEY');

        // Inteligencia de búsqueda básica (Detección de UTS)
        $query = Inmueble::where('estatus', 'disponible');
        
        $isUTS = str_contains(strtolower($userMessage), 'uts') || str_contains(strtolower($userMessage), 'universidad');
        
        if ($isUTS) {
            $query->where(function($q) {
                $q->where('direccion', 'LIKE', '%UTS%')
                  ->orWhere('direccion', 'LIKE', '%Universidad%')
                  ->orWhere('titulo', 'LIKE', '%UTS%')
                  ->orWhere('titulo', 'LIKE', '%Universidad%');
            });
        }

        $inmuebles = $query->limit(5)->get(['id', 'titulo', 'renta_mensual', 'direccion']);
        
        // Si no encontró nada específico, traer los últimos 5 generales
        if ($inmuebles->isEmpty()) {
            $inmuebles = Inmueble::where('estatus', 'disponible')->latest()->limit(5)->get(['id', 'titulo', 'renta_mensual', 'direccion']);
        }

        $contexto = "";
        foreach ($inmuebles as $i) {
            $url = route('inmuebles.show', $i->id);
            $contexto .= "🏠 <b>{$i->titulo}</b><br>💰 \${$i->renta_mensual}<br>📍 {$i->direccion}<br>🔗 BOTÓN_URL: {$url}<br><br>";
        }

        $prompt = "Eres ROCO, el entusiasta asistente Beagle de Arrendaoco en Ocosingo, Chiapas. 
        Tu misión es ser amigable, servicial y experto en rentas.
        
        CONTEXTO GEOGRÁFICO DE OCOSINGO:
        - La UTS (Universidad Tecnológica de la Selva) es el punto más importante para estudiantes. Está a las afueras, por la zona de la carretera a Altamirano. No la confundas con la UNICACH o la Normal.
        - El Centro es donde está el parque y el mercado.
        
        INFORMACIÓN DE CONTACTO:
        Si alguien quiere contactar al administrador, indícales que escriban a: <b>arrendaoco@gmail.com</b>.

        INMUEBLES QUE 'OLFATEASTE' PARA ESTA PREGUNTA:
        {$contexto}

        REGLAS CRÍTICAS DE RESPUESTA:
        1. Responde SIEMPRE en HTML usando <b> para resaltar nombres o montos y <br> para separar ideas.
        2. Si el usuario pregunta por la UTS, enfócate en las propiedades que digan 'UTS' o 'Universidad'. Si en la lista no hay ninguna cerca de la UTS, sé honesto y dile que 'estás siguiendo el rastro' pero que de momento no tienes nada ahí.
        3. PROHIBIDO: No menciones nunca IDs o códigos técnicos.
        4. BOTONES: Si mencionas una propiedad, DEBES poner el botón de 'Ver Detalles' usando la BOTÓN_URL que te di arriba:
           <a href='BOTÓN_URL' style='display:inline-block; margin-top:10px; padding:8px 15px; background:#003049; color:white; border-radius:10px; text-decoration:none; font-weight:bold; font-size:12px;'>🏠 Ver Detalles de la Casa</a>
        5. Sé breve y usa emojis de perro (🐶, 🦴, 🏠).

        Usuario dice: {$userMessage}";

        try {
            // Usando v1beta y gemini-2.0-flash que está confirmado en la lista de modelos de esta API Key
            $response = Http::withoutVerifying()
                ->timeout(30)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$apiKey}", [
                    'contents' => [
                        ['parts' => [['text' => $prompt]]]
                    ]
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $resText = $data['candidates'][0]['content']['parts'][0]['text'] ?? '¡Guau! 🐾';
                return response()->json(['success' => true, 'response' => $resText]);
            }

            Log::error("Gemini API Error: " . $response->body());
            return response()->json([
                'success' => false,
                'response' => '¡Guau! 🐾 Mi conexión falló. Por favor revisa la API KEY o intenta más tarde.'
            ]);

        } catch (\Exception $e) {
            Log::error("Chat Exception: " . $e->getMessage());
            return response()->json(['success' => false, 'response' => '¡Guau! 🐾 Error del sistema.']);
        }
    }
}
