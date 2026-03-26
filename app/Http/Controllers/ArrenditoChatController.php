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
     */
    private const GEMINI_MODEL = 'gemini-2.0-flash';

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
                        'temperature' => 0.7,
                        'maxOutputTokens' => 1024,
                    ],
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $resText = $data['candidates'][0]['content']['parts'][0]['text'] ?? '¡Guau! 🐾 No pude procesar eso, intenta de nuevo.';
                return response()->json(['success' => true, 'response' => $resText]);
            }

            return response()->json(['success' => false, 'response' => '¡Guau! 🐾 Tuve un problemita al procesar tu mensaje.']);

        } catch (\Exception $e) {
            Log::error("ROCO Chat - Excepción: " . $e->getMessage());
            return response()->json(['success' => false, 'response' => '¡Guau! 🐾 Error inesperado del sistema.']);
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
        return "Eres ROCO, el amigable asistente Beagle de Arrendaoco en Ocosingo, Chiapas.
- Responde SIEMPRE en HTML ligero usando <b> y <br>.
- Si el usuario pregunta por un inmueble específico (se te da en contexto), usa esos detalles.
- Sé breve y usa emojis perrunos (🐶, 🦴, 🐾).
- Si mencionas una propiedad, pon el botón de 'Ver Detalles' usando la BOTÓN_URL que te di:
  <a href='BOTÓN_URL' style='display:inline-block; margin-top:10px; padding:8px 15px; background:#003049; color:white; border-radius:10px; text-decoration:none; font-weight:bold; font-size:12px;'>🏠 Ver Detalles</a>";
    }
}
