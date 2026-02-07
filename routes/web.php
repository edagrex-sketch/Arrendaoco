<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Api\ContratoController;
use App\Http\Controllers\InmuebleController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\ArrenditoController;
use App\Http\Controllers\ResenaController;
use App\Http\Controllers\FavoritoController;
use App\Http\Controllers\ArrenditoChatController;

// Test Gemini API
Route::get('/test-gemini', function () {
    $apiKey = env('GEMINI_API_KEY');
    
    if (!$apiKey) {
        return response()->json(['error' => 'No API key found']);
    }
    
    try {
        $response = Http::timeout(10)->withHeaders([
            'Content-Type' => 'application/json',
        ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key={$apiKey}", [
            'contents' => [
                [
                    'parts' => [
                        ['text' => 'Say hello in one word']
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


// 1. Mostrar formulario
Route::get('/registro', function () {
    return view('auth.register');
})->name('registro');
// 2. Guardar usuario
Route::post('/registro', function (\Illuminate\Http\Request $request) {
    // Validar datos
    $datos = $request->validate([
        'nombre' => 'required|string|max:255',
        'email' => 'required|email|unique:usuarios,email',
        'password' => 'required|string|min:8|confirmed',
        'foto_perfil' => 'nullable|image|max:2048', // Validación para la imagen
    ]);

    $path = null;
    if ($request->hasFile('foto_perfil')) {
        $path = $request->file('foto_perfil')->store('perfil', 'public');
    }

    // Crear usuario en base de datos
    $usuario = \App\Models\Usuario::create([
        'nombre' => $datos['nombre'],
        'email' => $datos['email'],
        'password' => \Illuminate\Support\Facades\Hash::make($datos['password']),
        'estatus' => 'activo',
        'foto_perfil' => $path,
    ]);

    // Asignar rol de inquilino por defecto
    $usuario->asignarRol('inquilino');
    // Iniciar sesión y redirigir
    // Iniciar sesión y redirigir
    \Illuminate\Support\Facades\Auth::login($usuario);
    request()->session()->flash('login_success', true);
    return redirect()->route('inicio');
})->name('registro.post');

Route::get('/nosotros', function () {
    return view('nosotros');
})->name('nosotros');

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('inicio');
    }
    $inmuebles = \App\Models\Inmueble::where('estatus', 'disponible')->latest()->paginate(15);
    return view('welcome', compact('inmuebles'));
})->name('welcome');

Route::get('/buscar', [InmuebleController::class, 'publicSearch'])->name('inmuebles.public_search');


// Mostrar formulario de login
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Procesar login
Route::post('/login', function (Request $request) {

    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    if (!Auth::attempt($credentials)) {
        return back()
            ->withErrors(['email' => 'Credenciales incorrectas'])
            ->onlyInput('email');
    }

    $request->session()->regenerate();
    $request->session()->flash('login_success', true);

    return redirect()->route('inicio');
});

// Logout
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');
})->name('logout');


Route::get('/inmuebles/{inmueble}', [InmuebleController::class, 'show'])->name('inmuebles.show');

Route::middleware('auth')->group(function () {
    Route::get('/inicio', [InmuebleController::class, 'home'])->name('inicio');

    // Rutas de Inmuebles
    Route::get('/mis-propiedades', [InmuebleController::class, 'index'])->name('inmuebles.index');
    Route::get('/publicar', [InmuebleController::class, 'create'])->name('inmuebles.create');
    Route::post('/publicar', [InmuebleController::class, 'store'])->name('inmuebles.guardar');
    Route::get('/inmuebles/{inmueble}/editar', [InmuebleController::class, 'edit'])->name('inmuebles.edit');
    Route::put('/inmuebles/{inmueble}', [InmuebleController::class, 'update'])->name('inmuebles.update');
    Route::delete('/inmuebles/{inmueble}', [InmuebleController::class, 'destroy'])->name('inmuebles.destroy');

    // Perfil
    Route::get('/perfil', [PerfilController::class, 'index'])->name('perfil.index');
    Route::put('/perfil', [PerfilController::class, 'update'])->name('perfil.update');
    Route::post('/perfil/publicar', [PerfilController::class, 'publicar'])->name('perfil.publicar');

    // Admin Usuarios
    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        Route::get('usuarios/reporte', [UsuarioController::class, 'reporte'])->name('usuarios.reporte');
        Route::resource('usuarios', UsuarioController::class);
        Route::get('inmuebles/reporte', [InmuebleController::class, 'reporte'])->name('inmuebles.reporte');
        Route::get('resenas', [ResenaController::class, 'index'])->name('resenas.index');
    });

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

    // Arrendito Mascot Routes
    Route::post('/arrendito/actualizar', [ArrenditoController::class, 'updateName'])->name('arrendito.update');
    Route::get('/arrendito/nombre', [ArrenditoController::class, 'getName'])->name('arrendito.name');
});

// Chat route must be public for visitors
Route::post('/arrendito/chat', [ArrenditoChatController::class, 'chat'])->name('arrendito.chat');

Route::middleware('auth')->group(function () {
    // Reseñas Routes
    Route::post('/inmuebles/{inmueble}/resenas', [ResenaController::class, 'store'])->name('resenas.store');
    Route::put('/resenas/{resena}', [ResenaController::class, 'update'])->name('resenas.update');
    Route::delete('/resenas/{resena}', [ResenaController::class, 'destroy'])->name('resenas.destroy');

    // Favoritos Routes
    Route::get('/favoritos', [FavoritoController::class, 'index'])->name('favoritos.index');
    Route::post('/favoritos/{inmueble}/toggle', [FavoritoController::class, 'toggle'])->name('favoritos.toggle');
    Route::put('/favoritos/{inmueble}', [FavoritoController::class, 'update'])->name('favoritos.update');
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

