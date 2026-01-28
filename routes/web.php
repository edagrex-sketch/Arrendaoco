<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\ContratoController;

/*
|--------------------------------------------------------------------------
| Rutas públicas
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Login (WEB con sesión)
|--------------------------------------------------------------------------
*/

// Mostrar formulario de login
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Procesar login
Route::post('/login', function (Request $request) {

    $credentials = $request->validate([
        'email'    => 'required|email',
        'password' => 'required|string',
    ]);

    if (!Auth::attempt($credentials)) {
        return back()
            ->withErrors(['email' => 'Credenciales incorrectas'])
            ->onlyInput('email');
    }

    // 🔐 Regenerar sesión (CLAVE para evitar 419)
    $request->session()->regenerate();

    return redirect()->route('inicio');
});

// Logout
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');
})->name('logout');

/*
|--------------------------------------------------------------------------
| App protegida (dashboard)
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\InmuebleController; // Importar el controlador

Route::middleware('auth')->group(function () {

    Route::get('/inicio', function () {
        return view('inicio');
    })->name('inicio');

    Route::get('/inmuebles/{inmueble}', function (\App\Models\Inmueble $inmueble) {
        return view('inmuebles.show', compact('inmueble'));
    })->name('inmuebles.show');
    
    // Rutas de Inmuebles
    Route::get('/publicar', function () {
        return view('inmuebles.create');
    })->name('inmuebles.create');

    // PDFs con sesión
    Route::get(
        '/contratos/{contrato}/estado-cuenta/pdf',
        [ContratoController::class, 'estadoCuentaPdf']
    );

    Route::get(
        '/contratos/{contrato}/estados-cuenta',
        [ContratoController::class, 'listarEstadosCuenta']
    );

    Route::get(
        '/estados-cuenta/{estadoCuenta}/descargar',
        [ContratoController::class, 'descargarEstadoCuenta']
    );
});

// 🔓 RUTA DE PRUEBA (FUERA DE AUTH)
// Esto es para ver si llegamos al controlador sin que nos pida login
Route::post('/publicar', [InmuebleController::class, 'store'])->name('inmuebles.guardar');
