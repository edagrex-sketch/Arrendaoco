<?php

use Illuminate\Support\Facades\Storage;

it('serves files from the public storage disk through the storage route', function () {
    Storage::disk('public')->put('inmuebles/test-route.txt', 'imagen disponible');

    $response = $this->get('/storage/inmuebles/test-route.txt');

    $response->assertOk();
    expect($response->streamedContent())->toContain('imagen disponible');
});
