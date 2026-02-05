<?php

use Illuminate\Support\Facades\Http;

$apiKey = env('GEMINI_API_KEY');

echo "Testing Gemini API...\n";
echo "API Key exists: " . ($apiKey ? 'Yes' : 'No') . "\n\n";

if (!$apiKey) {
    echo "ERROR: No API key found in .env file\n";
    exit(1);
}

try {
    $response = Http::timeout(10)->withHeaders([
        'Content-Type' => 'application/json',
    ])->post("https://generativelanguage.googleapis.com/v1/models/gemini-1.5-flash:generateContent?key={$apiKey}", [
        'contents' => [
            [
                'parts' => [
                    ['text' => 'Say hello in one word']
                ]
            ]
        ]
    ]);

    echo "Status Code: " . $response->status() . "\n";
    
    if ($response->successful()) {
        echo "SUCCESS! API is working\n";
        $data = $response->json();
        if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
            echo "Response: " . $data['candidates'][0]['content']['parts'][0]['text'] . "\n";
        }
    } else {
        echo "ERROR: API returned error\n";
        echo "Response: " . $response->body() . "\n";
    }
} catch (\Exception $e) {
    echo "EXCEPTION: " . $e->getMessage() . "\n";
}
