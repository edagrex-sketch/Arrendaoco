<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\InmuebleController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', function () {
        return auth()->user();
    });
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('inmuebles', InmuebleController::class);
});