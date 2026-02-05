Route::get('/test-gemini', function () {
    $apiKey = env('GEMINI_API_KEY');
    
    if (!$apiKey) {
        return response()->json(['error' => 'No API key found']);
    }
    
    try {
        $response = Http::timeout(10)->withHeaders([
            'Content-Type' => 'application/json',
        ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}", [
            'contents' => [
                [
                    'parts' => [
                        ['text' => 'Say hello']
                    ]
                ]
            ]
        ]);

        return response()->json([
            'status' => $response->status(),
            'successful' => $response->successful(),
            'body' => $response->json()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage()
        ]);
    }
});
