<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\ContratoController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    // OJO: si en tu tabla usuarios el campo no es "email" sino "correo",
    // cambia 'email' por 'correo' aquí y en tu form.
    if (!Auth::attempt($credentials)) {
        abort(401, 'Credenciales incorrectas');
    }

    $request->session()->regenerate();

    return response()->json([
        'message' => 'Login correcto'
    ]);
});

// PDF (para navegador con sesión)
Route::middleware('auth')->get(
    '/contratos/{contrato}/estado-cuenta/pdf',
    [ContratoController::class, 'estadoCuentaPdf']
);
Route::middleware('auth')->get(
    '/contratos/{contrato}/estados-cuenta',
    [ContratoController::class, 'listarEstadosCuenta']
);
Route::middleware('auth')->get(
    '/estados-cuenta/{estadoCuenta}/descargar',
    [ContratoController::class, 'descargarEstadoCuenta']
);

