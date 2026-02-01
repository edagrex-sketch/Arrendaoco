<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\ContratoController;

// 1. Mostrar formulario
Route::get('/registro', function () {
    return view('auth.register');
})->name('registro');
// 2. Guardar usuario
Route::post('/registro', function (\Illuminate\Http\Request $request) {
    // Validar datos
    $datos = $request->validate([
        'nombre'   => 'required|string|max:255',
        'email'    => 'required|email|unique:usuarios,email',
        'password' => 'required|string|min:8|confirmed',
    ]);
    // Crear usuario en base de datos
    $usuario = \App\Models\Usuario::create([
        'nombre'   => $datos['nombre'],
        'email'    => $datos['email'],
        'password' => \Illuminate\Support\Facades\Hash::make($datos['password']),
        'estatus'  => 'activo',
    ]);
    // Iniciar sesión y redirigir
    \Illuminate\Support\Facades\Auth::login($usuario);
    return redirect()->route('inicio');
})->name('registro.post');
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

// 1. Mostrar formulario de "Olvidé mi contraseña"
Route::get('/olvide-password', function () {
    return view('auth.forgot-password');
})->name('password.request');
// 2. Enviar correo con el enlace
Route::post('/olvide-password', function (\Illuminate\Http\Request $request) {
    $request->validate(['email' => 'required|email']);
    $status = \Illuminate\Support\Facades\Password::sendResetLink(
        $request->only('email')
    );
    if ($status === \Illuminate\Support\Facades\Password::RESET_LINK_SENT) {
        return back()->with('status', __($status));
    }
    return back()->withErrors(['email' => __($status)]);
})->name('password.email');
// 3. Mostrar formulario de "Restablecer contraseña" (al hacer clic en el email)
Route::get('/reset-password/{token}', function ($token) {
    return view('auth.reset-password', ['token' => $token]);
})->name('password.reset');
// 4. Procesar el cambio de contraseña
Route::post('/reset-password', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);
    $status = \Illuminate\Support\Facades\Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill([
                'password' => \Illuminate\Support\Facades\Hash::make($password)
            ])->setRememberToken(\Illuminate\Support\Str::random(60));
            $user->save();
            \Illuminate\Support\Facades\Event::dispatch(new \Illuminate\Auth\Events\PasswordReset($user));
        }
    );
    if ($status === \Illuminate\Support\Facades\Password::PASSWORD_RESET) {
        return redirect()->route('login')->with('status', __($status));
    }
    return back()
        ->withInput($request->only('email'))
        ->withErrors(['email' => __($status)]);
})->name('password.update');

// 🔓 RUTA DE PRUEBA (FUERA DE AUTH)
// Esto es para ver si llegamos al controlador sin que nos pida login
Route::post('/publicar', [InmuebleController::class, 'store'])->name('inmuebles.guardar');
