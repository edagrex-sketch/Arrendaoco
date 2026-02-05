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

        // Contexto de inmuebles
        $inmuebles = Inmueble::where('estatus', 'disponible')->limit(5)->get(['titulo', 'renta_mensual', 'direccion']);
        $contexto = "";
        foreach ($inmuebles as $i) {
            $contexto .= "🏠 <b>{$i->titulo}</b><br>💰 \${$i->renta_mensual}<br>📍 {$i->direccion}<br><br>";
        }

        $prompt = "Eres ROCO, el entusiasta asistente Beagle de Arrendaoco en Ocosingo, Chiapas. 
        Tu misión es ser amigable, servicial y experto en rentas.
        
        INFORMACIÓN DE CONTACTO:
        Si alguien quiere contactar al administrador o tiene dudas legales, indícales que escriban al correo electrónico: <b>arrendaoco@gmail.com</b>. Por el momento no contamos con número telefónico.

        INMUEBLES DISPONIBLES ACTUALMENTE:
        {$contexto}

        REGLAS DE RESPUESTA:
        1. Responde SIEMPRE en HTML usando <b> para resaltar nombres o montos y <br> para separar ideas.
        2. Sé breve (máximo 2 párrafos).
        3. Usa emojis de perro (🐶, 🦴, 🏠) de forma natural.
        4. Si preguntan por inmuebles, usa la lista de arriba. Si no hay inmuebles en la lista, diles que 'estás olfateando nuevas oportunidades' y que vuelvan pronto.

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
