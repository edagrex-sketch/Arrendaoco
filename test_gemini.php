<?php

use Illuminate\Support\Facades\Http;

$apiKey = config('services.gemini.api_key', env('GEMINI_API_KEY'));
$model = config('services.gemini.model', 'gemini-2.5-flash');

echo "=== Test de API Gemini para ROCO ===\n";
echo "Modelo: {$model}\n";
echo "API Key: " . ($apiKey ? 'Sí (configurada)' : 'No (FALTA)') . "\n\n";

if (!$apiKey) {
    echo "ERROR: No se encontró GEMINI_API_KEY en el archivo .env\n";
    echo "Agrega: GEMINI_API_KEY=tu_llave_aquí\n";
    exit(1);
}

try {
    echo "Enviando solicitud a Gemini {$model}...\n";

    $response = Http::withoutVerifying()
        ->timeout(15)
        ->withHeaders(['Content-Type' => 'application/json'])
        ->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}", [
            'systemInstruction' => [
                'parts' => [['text' => 'Eres ROCO, un perrito Beagle asistente. Responde en una sola línea corta.']]
            ],
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [['text' => 'Di hola en español como un perrito amigable']]
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.8,
                'maxOutputTokens' => 100,
            ]
        ]);

    echo "Status Code: " . $response->status() . "\n";

    if ($response->successful()) {
        echo "✅ ¡ÉXITO! La API funciona correctamente con {$model}\n";
        $data = $response->json();

        if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
            echo "Respuesta de ROCO: " . $data['candidates'][0]['content']['parts'][0]['text'] . "\n";
        }

        // Mostrar tokens usados si disponible
        if (isset($data['usageMetadata'])) {
            $usage = $data['usageMetadata'];
            echo "\n📊 Uso de tokens:\n";
            echo "  - Prompt: " . ($usage['promptTokenCount'] ?? 'N/A') . "\n";
            echo "  - Respuesta: " . ($usage['candidatesTokenCount'] ?? 'N/A') . "\n";
            echo "  - Total: " . ($usage['totalTokenCount'] ?? 'N/A') . "\n";
        }
    } else {
        echo "❌ ERROR: La API devolvió un error\n";
        echo "Código: " . $response->status() . "\n";
        echo "Respuesta: " . $response->body() . "\n";

        $errorData = $response->json();
        if (isset($errorData['error']['message'])) {
            echo "\nMensaje: " . $errorData['error']['message'] . "\n";
        }
    }
} catch (\Exception $e) {
    echo "❌ EXCEPCIÓN: " . $e->getMessage() . "\n";
}
