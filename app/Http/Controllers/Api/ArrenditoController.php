<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Inmueble;

class ArrenditoController extends Controller
{
    private const GEMINI_MODEL = 'gemini-2.5-flash';
    private const GEMINI_BASE_URL = 'https://generativelanguage.googleapis.com/v1beta/models/';

    /**
     * Chat principal de Arrendito (IA Gemini)
     */
    public function chat(Request $request)
    {
        $request->validate(['message' => 'required|string|max:500']);
        $userMessage = $request->message;
        $apiKey = config('services.gemini.api_key', env('GEMINI_API_KEY'));

        if (empty($apiKey)) {
            return response()->json([
                'success' => false,
                'response' => '🐾 ¡Guau! Mi cerebro perruno necesita una llave API. Contacta al administrador.'
            ]);
        }

        // Búsqueda inteligente de inmuebles
        $inmuebles = $this->buscarInmueblesInteligente($userMessage);
        $contexto = $this->construirContextoInmuebles($inmuebles);

        // System Instruction (Personalidad de Arrendito/ROCO)
        $systemInstruction = $this->getSystemInstruction();

        // Contenido del usuario (mensaje + contexto de inmuebles)
        $userContent = "INMUEBLES QUE 'OLFATEASTE' PARA ESTA PREGUNTA:\n{$contexto}\n\nUsuario dice: {$userMessage}";

        try {
            $url = self::GEMINI_BASE_URL . self::GEMINI_MODEL . ":generateContent?key={$apiKey}";

            $response = Http::timeout(30)
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
                    ],
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $resText = $data['candidates'][0]['content']['parts'][0]['text'] ?? '🦴 ¡Guau! No pude procesar eso, intenta de nuevo.';
                return response()->json(['success' => true, 'response' => $resText]);
            }

            return response()->json(['success' => false, 'response' => '🐾 Tuve un problemita al procesar tu mensaje.']);

        } catch (\Exception $e) {
            Log::error("ROCO API Chat Error: " . $e->getMessage());
            return response()->json(['success' => false, 'response' => '🐾 Error inesperado del sistema.']);
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
            // Nota: El prompt maneja el enlace para la App si es necesario.
            $tipo = $i->tipo ? " ({$i->tipo})" : "";
            $contexto .= "🏠 {$i->titulo}{$tipo} - \${$i->renta_mensual} - Ubicado en {$i->direccion}\n";
        }
        return $contexto;
    }

    private function getSystemInstruction(): string
    {
        return "Eres ROCO, el amigable asistente Beagle de Arrendaoco en Ocosingo, Chiapas.
            Si el usuario pregunta por inmuebles, usa la información que te damos. 
            Responde de forma breve, perruna (🦴, 🐾, 🐶) y usa HTML ligero si es necesario.";
    }
}
