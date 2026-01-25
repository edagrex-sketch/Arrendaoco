<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\InmuebleController;
use App\Http\Controllers\Api\ContratoController;

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
Route::post('/inmuebles/{inmueble}/rentar', [ContratoController::class, 'rentar']);
Route::post('/contratos/{contrato}/renovar', [ContratoController::class, 'renovar']);
Route::post('/contratos/{contrato}/cancelar', [ContratoController::class, 'cancelar']);