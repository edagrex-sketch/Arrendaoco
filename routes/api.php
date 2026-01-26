<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\InmuebleController;
use App\Http\Controllers\Api\ContratoController;
use App\Http\Controllers\Api\PagoController;
use App\Http\Controllers\Api\ReporteController;
/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/

Route::post('/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| Rutas protegidas
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', function () {
        return auth()->Usuario();
    });

    // Inmuebles
    Route::apiResource('inmuebles', InmuebleController::class);

    // Contratos
    Route::post('/inmuebles/{inmueble}/rentar', [ContratoController::class, 'rentar']);
    Route::post('/contratos/{contrato}/renovar', [ContratoController::class, 'renovar']);
    Route::post('/contratos/{contrato}/cancelar', [ContratoController::class, 'cancelar']);

    // Pagos
    Route::post('/contratos/{contrato}/pagos/generar', [PagoController::class, 'generar']);
    Route::post('/pagos/{pago}/pagar', [PagoController::class, 'pagar']);

    Route::middleware('auth:sanctum')->get(
        '/contratos/{contrato}/estado-cuenta',
        [PagoController::class, 'estadoCuenta']
    );
    Route::get('/contratos/{contrato}/estado-cuenta', [ContratoController::class, 'estadoCuenta']);

    Route::get('/reportes/ingresos', [ReporteController::class, 'ingresos']);

    Route::get(
        '/contratos/{contrato}/estado-cuenta/excel',
        [ContratoController::class, 'exportarEstadoCuentaExcel']
    )->middleware('auth:sanctum');

    Route::get(
        '/contratos/{contrato}/estado-cuenta/pdf',
        [ContratoController::class, 'exportarEstadoCuentaPdf']
    )->middleware('auth:sanctum');
});
