<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\InmuebleController;
use App\Http\Controllers\Api\ContratoController;
use App\Http\Controllers\Api\NotificacionController;
use App\Http\Controllers\Api\PagoController;
use App\Http\Controllers\Api\ReporteController;
use App\Http\Controllers\Api\FavoritoController;
use App\Http\Controllers\Api\ArrenditoController;
use App\Http\Controllers\Api\ResenaController;
use App\Http\Controllers\Api\PerfilController;
use App\Http\Controllers\Api\EventoController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\StripeConnectController;

/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/
// Auth
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/google-login', [\App\Http\Controllers\Auth\SocialAuthController::class, 'handleApiGoogleLogin']);

// Inmuebles Públicos
Route::get('/inmuebles/public-list', [InmuebleController::class, 'publicList']);
Route::get('/inmuebles/public-detail/{inmueble}', [InmuebleController::class, 'show']);

// Rutas públicas de retorno de Stripe para la App Móvil
Route::get('/contratos/{contrato}/success', [ContratoController::class, 'successReserva']);
Route::get('/contratos/{contrato}/cancel', function() { return "Reserva cancelada"; });
Route::get('/pagos/{pago}/success', [PagoController::class, 'success']);
Route::get('/pagos/{pago}/cancel', function() { return "Pago cancelado"; });

// Reseñas Públicas
Route::get('/resenas/{resena}', [ResenaController::class, 'show']);
Route::get('/inmuebles/{inmueble}/resenas', [ResenaController::class, 'index']);

// Arrendito Chat (IA)
Route::post('/arrendito/chat', [ArrenditoController::class, 'chat']);

/*
|--------------------------------------------------------------------------
| Rutas protegidas
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // Auth & Perfil
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', function () {
        return new \App\Http\Resources\UserResource(auth()->user()->load('roles'));
    });

    Route::post('/perfil/actualizar', [PerfilController::class, 'update']);
    Route::post('/perfil/solicitar-propietario', [PerfilController::class, 'publicar']);

    // Favoritos
    Route::get('/favoritos', [FavoritoController::class, 'index']);
    Route::post('/favoritos/{inmueble}/toggle', [FavoritoController::class, 'toggle']);
    Route::put('/favoritos/{inmueble}', [FavoritoController::class, 'update']);

    // Reseñas (Auth)
    Route::post('/inmuebles/{inmueble}/resenas', [ResenaController::class, 'store']);
    Route::put('/inmuebles/{inmueble}/resenas', [ResenaController::class, 'store']); // Nuevo: Permitir PUT en la misma ruta
    Route::delete('/inmuebles/{inmueble}/resenas', [ResenaController::class, 'destroyByInmueble']); // Nuevo: Borrar mi reseña de este inmueble
    Route::put('/resenas/{resena}', [ResenaController::class, 'update']);
    Route::delete('/resenas/{resena}', [ResenaController::class, 'destroy']);

    // Notificaciones
    Route::get('/notificaciones', [NotificacionController::class, 'index']);
    Route::get('/notificaciones/unread-count', [NotificacionController::class, 'unreadCount']);
    Route::post('/notificaciones/mark-all-read', [NotificacionController::class, 'markAllAsRead']);
    Route::put('/notificaciones/{notificacion}', [NotificacionController::class, 'update']);
    Route::delete('/notificaciones/{notificacion}', [NotificacionController::class, 'destroy']);

    // Inmuebles (Gestión Propietario/Admin)
    Route::get('/inmuebles/{inmueble}/ver-contrato', [ContratoController::class, 'verContrato']);
    Route::post('/inmuebles/{inmueble}/rentar', [ContratoController::class, 'rentar']);
    Route::apiResource('inmuebles', InmuebleController::class)->names('api.inmuebles');

    // Contratos
    Route::get('/contratos', [ContratoController::class, 'index']);
    Route::get('/contratos/{contrato}', [ContratoController::class, 'show']);
    Route::put('/contratos/{contrato}', [ContratoController::class, 'update']);
    Route::post('/contratos/{contrato}/subir-firmado', [ContratoController::class, 'subirFirmado']);
    Route::post('/contratos/{contrato}/renovar', [ContratoController::class, 'renovar']);
    Route::post('/contratos/{contrato}/cancelar', [ContratoController::class, 'cancelar']);

    // Estado de cuenta
    Route::get('/contratos/{contrato}/descargar-pdf', [ContratoController::class, 'descargarContratoPdf']);
    Route::get('/contratos/{contrato}/estado-cuenta', [ContratoController::class, 'estadoCuenta']);

    // Pagos
    Route::get('/pagos/pendientes', [PagoController::class, 'pendientes']);
    Route::post('/contratos/{contrato}/pagos/generar', [PagoController::class, 'generar']);
    Route::post('/pagos/{pago}/pagar', [PagoController::class, 'pagar']);

    // Reportes
    Route::get('/reportes/ingresos', [ReporteController::class, 'ingresos']);

    // Calendario / Eventos
    Route::get('/calendario', [EventoController::class, 'index']);
    Route::post('/calendario', [EventoController::class, 'store']);
    Route::put('/calendario/{evento}', [EventoController::class, 'update']);
    Route::delete('/calendario/{evento}', [EventoController::class, 'destroy']);
    Route::get('/contratos/{contrato}/eventos', [EventoController::class, 'byRenta']);

    // Excel
    Route::get('/contratos/{contrato}/estado-cuenta/excel', [ContratoController::class, 'exportarEstadoCuentaExcel']);

    // Chats
    Route::get('/chats', [ChatController::class, 'index']);
    Route::get('/chats/{chat}/mensajes', [ChatController::class, 'messages']);
    Route::post('/chats/{chat}/enviar', [ChatController::class, 'sendMessage']);
    Route::post('/chats/iniciar/{otroUsuarioId}/{inmuebleId?}', [ChatController::class, 'startChat']);
    Route::post('/fcm-token', [ChatController::class, 'updateFcmToken']);

    // Stripe Connect (Propietarios)
    Route::get('/stripe/onboarding-link', [StripeConnectController::class, 'getOnboardingLink']);
    Route::get('/stripe/check-status', [StripeConnectController::class, 'checkStatus']);
});
