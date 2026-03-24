<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Api\ContratoController;
use App\Http\Controllers\InmuebleController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\ArrenditoController;
use App\Http\Controllers\ResenaController;
use App\Http\Controllers\FavoritoController;
use App\Http\Controllers\ArrenditoChatController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Auth\SocialAuthController;

// Social Login Routes
Route::get('/auth/{provider}', [SocialAuthController::class, 'redirectToProvider'])->name('social.login');
Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'handleProviderCallback'])->name('social.callback');

// Ruta /test-gemini ELIMINADA por seguridad (exponía API Key públicamente)


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
        'foto_perfil' => 'nullable|image|max:2048',
    ], [
        'nombre.required' => 'El nombre es obligatorio.',
        'email.required' => 'El correo electrónico es obligatorio.',
        'email.email' => 'Ingresa un correo válido.',
        'email.unique' => 'Este correo ya está registrado.',
        'password.required' => 'La contraseña es obligatoria.',
        'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        'password.confirmed' => 'Las contraseñas no coinciden.',
        'foto_perfil.image' => 'El archivo debe ser una imagen.',
        'foto_perfil.max' => 'La imagen no debe pesar más de 2MB.',
    ]);

    $path = null;
    if ($request->hasFile('foto_perfil')) {
        $path = $request->file('foto_perfil')->store('perfil', 'public');
        \App\Support\MediaUrl::ensurePublicStorageCopy($path);
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

Route::get('/terminos', function () {
    return view('terminos');
})->name('terminos');

Route::get('/privacidad', function () {
    return view('privacidad');
})->name('privacidad');

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('inicio');
    }
    $inmuebles = \App\Models\Inmueble::where('estatus', 'disponible')->latest()->paginate(15);
    return view('welcome', compact('inmuebles'));
})->name('welcome');

Route::get('/storage/{path}', function (string $path) {
    $normalizedPath = ltrim(str_replace('\\', '/', $path), '/');

    abort_if($normalizedPath === '' || str_contains($normalizedPath, '..'), 404);

    $legacyPath = public_path('storage/' . $normalizedPath);
    if (is_file($legacyPath)) {
        return response()->file($legacyPath);
    }

    $disk = Storage::disk('public');
    abort_unless($disk->exists($normalizedPath), 404);

    return response()->file($disk->path($normalizedPath));
})->where('path', '.*')->name('storage.public');



// Mostrar formulario de login
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Procesar login
Route::post('/login', function (Request $request) {

    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ], [
        'email.required' => 'El correo electrónico es obligatorio.',
        'email.email' => 'Ingresa un correo electrónico válido.',
        'password.required' => 'La contraseña es obligatoria.',
    ]);

    if (Auth::attempt($credentials)) {
        if (Auth::user()->estatus !== 'activo') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return back()->withErrors(['email' => 'Tu cuenta ha sido desactivada. Contacta al administrador para más información.'])->onlyInput('email');
        }

        $request->session()->regenerate();
        $request->session()->flash('login_success', true);

        return redirect()->route('inicio');
    }

    return back()
        ->withErrors(['email' => 'Credenciales incorrectas'])
        ->onlyInput('email');
});

// Logout
Route::match(['get', 'post'], '/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('welcome')->with('logged_out', true);
})->name('logout');


Route::middleware('auth')->group(function () {
    Route::get('/inicio', [InmuebleController::class, 'home'])->name('inicio');
    Route::get('/buscar', [InmuebleController::class, 'publicSearch'])->name('inmuebles.public_search');
    Route::get('/inmuebles/{inmueble}', [InmuebleController::class, 'show'])->name('inmuebles.show');
    Route::get('/inmuebles/{inmueble}/rentar', [InmuebleController::class, 'rentar'])->name('inmuebles.rentar');

    // Rutas de Inmuebles
    Route::get('/mis-propiedades', [InmuebleController::class, 'index'])->name('inmuebles.index');
    Route::get('/mis-rentas', [InmuebleController::class, 'misRentas'])->name('inmuebles.mis_rentas');
    Route::post('/rentas/{contrato}/cancelar', [InmuebleController::class, 'cancelarRenta'])->name('rentas.cancelar');
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

    // Chat Nativo Routes
    Route::get('/chats', [ChatController::class, 'index'])->name('chats.index');
    Route::get('/chats/{chat}', [ChatController::class, 'show'])->name('chats.show');
    Route::post('/chats/{chat}/mensaje', [ChatController::class, 'sendMessage'])->name('chats.message.send');
    Route::get('/chats/iniciar/{otroUsuarioId}/{inmuebleId?}', [ChatController::class, 'startChat'])->name('chats.start');
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


// Rutas de Pago (Protegidas con Auth)
Route::middleware('auth')->prefix('pagos')->group(function () {
    Route::post('/checkout/{inmueble}', function (\Illuminate\Http\Request $request, \App\Models\Inmueble $inmueble) {
        $metodo = $request->input('metodo_pago', 'card');
        $montoTotal = $inmueble->renta_mensual + ($inmueble->deposito ?? 0);

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            // Determine payment methods based on selection
            $paymentMethodTypes = $metodo === 'oxxo' ? ['oxxo'] : ['card'];

            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => $paymentMethodTypes,
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'mxn',
                        'product_data' => [
                            'name' => 'Renta Inicial: ' . $inmueble->titulo,
                            'description' => 'Pago de 1er mes y depósito.',
                        ],
                        'unit_amount' => (int) ($montoTotal * 100), // Stripes uses cents
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('pagos.test.success.process', ['inmueble' => $inmueble->id]) . '?session_id={CHECKOUT_SESSION_ID}&metodo_pago=' . $metodo,
                'cancel_url' => route('inmuebles.rentar', $inmueble->id),
            ]);

            return redirect()->away($session->url);
        } catch (\Exception $e) {
            return back()->with('error', 'Error al procesar el pago con Stripe: ' . $e->getMessage());
        }
    })->name('pagos.stripe.checkout');

    Route::match(['get', 'post'], '/success/{inmueble}', function (\Illuminate\Http\Request $request, \App\Models\Inmueble $inmueble) {
        // Prevent double processing if already rented by them (or anyone)
        if ($inmueble->estatus === 'rentado' && \App\Models\Contrato::where('inmueble_id', $inmueble->id)->exists()) {
            // If we just got redirected from Stripe, but contract is already there, just show success
            return view('pagos.success', ['inmueble' => $inmueble]);
        }

        // Validate via Stripe Session if needed
        if ($request->has('session_id')) {
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            $session = \Stripe\Checkout\Session::retrieve($request->session_id);
            if ($session->payment_status !== 'paid' && $session->payment_status !== 'unpaid') {
                return redirect()->route('inicio')->with('error', 'El pago no fue completado.');
            }
        }

        $inmueble->update(['estatus' => 'rentado']);

        $contrato = \App\Models\Contrato::create([
            'inmueble_id' => $inmueble->id,
            'propietario_id' => $inmueble->propietario_id,
            'inquilino_id' => Auth::id(),
            'fecha_inicio' => now(),
            'renta_mensual' => $inmueble->renta_mensual,
            'deposito' => $inmueble->deposito,
            'estatus' => 'activo'
        ]);

        // Crear evento de calendario para el inquilino
        \App\Models\Evento::create([
            'usuario_id' => Auth::id(),
            'renta_id' => $contrato->id,
            'titulo' => 'Inicio de Renta: ' . $inmueble->titulo,
            'descripcion' => 'Tu renta del inmueble "' . $inmueble->titulo . '" ha comenzado.',
            'fecha' => now()
        ]);

        // Crear evento de calendario para el propietario
        \App\Models\Evento::create([
            'usuario_id' => $inmueble->propietario_id,
            'renta_id' => $contrato->id,
            'titulo' => 'Inmueble Rentado: ' . $inmueble->titulo,
            'descripcion' => 'Tu propiedad ha sido rentada por ' . Auth::user()->nombre . '.',
            'fecha' => now()
        ]);

        $metodo = $request->input('metodo_pago', 'card');

        // Create an initial Pago record to reflect the transaction
        \App\Models\Pago::create([
            'contrato_id' => $contrato->id,
            'mes' => now()->month,
            'anio' => now()->year,
            'monto' => $contrato->renta_mensual + ($contrato->deposito ?? 0),
            // For oxxo it might be pending, but we'll mark it pending if OXXO, or Pagado if card
            'estatus' => $metodo === 'oxxo' ? 'pendiente' : 'pagado',
            'fecha_pago' => now(),
            'dias_atraso' => 0,
            'recargo' => 0,
            'total_con_recargo' => $contrato->renta_mensual + ($contrato->deposito ?? 0),
            'concepto' => 'Depósito y 1er Mes',
        ]);

        if ($metodo === 'oxxo') {
            $referencia = implode(' - ', str_split(rand(100000000000000, 999999999999999), 4));
            return view('pagos.oxxo', ['inmueble' => $inmueble, 'referencia' => $referencia, 'contrato' => $contrato]);
        }

        return view('pagos.success', ['inmueble' => $inmueble]);
    })->name('pagos.test.success.process');

    Route::post('/pagar-mensualidad/{contrato}', function (\Illuminate\Http\Request $request, \App\Models\Contrato $contrato) {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card', 'oxxo'], // Providing both to allow selection
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'mxn',
                        'product_data' => [
                            'name' => 'Renta Mensual',
                            'description' => 'Propiedad: ' . ($contrato->inmueble->titulo ?? 'N/A'),
                        ],
                        'unit_amount' => (int) ($contrato->renta_mensual * 100),
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('pagos.stripe.mensualidad.success', ['contrato' => $contrato->id]) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('inmuebles.mis_rentas'),
            ]);

            return redirect()->away($session->url);
        } catch (\Exception $e) {
            return back()->with('error', 'Error al procesar el pago con Stripe: ' . $e->getMessage());
        }
    })->name('pagos.stripe.mensualidad');

    Route::match(['get', 'post'], '/mensualidad/success/{contrato}', function (\Illuminate\Http\Request $request, \App\Models\Contrato $contrato) {
        if ($request->has('session_id')) {
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            $session = \Stripe\Checkout\Session::retrieve($request->session_id);
            if ($session->payment_status !== 'paid' && $session->payment_status !== 'unpaid') {
                return redirect()->route('inmuebles.mis_rentas')->with('error', 'El pago mensual no fue completado.');
            }
        }

        // To avoid duplicate payments, check if a payment for this month/year already exists recently (last few minutes or status pending)
        // Here we just record it assuming Stripe is source of truth
        \App\Models\Pago::create([
            'contrato_id' => $contrato->id,
            'mes' => now()->month,
            'anio' => now()->year,
            'monto' => $contrato->renta_mensual,
            'estatus' => 'pagado',
            'fecha_pago' => now(),
            'dias_atraso' => 0,
            'recargo' => 0,
            'total_con_recargo' => $contrato->renta_mensual,
            'concepto' => 'Mensualidad ' . now()->month . '/' . now()->year,
        ]);

        return redirect()->route('inmuebles.mis_rentas')->with('success', 'Pago de mensualidad registrado exitosamente.');
    })->name('pagos.stripe.mensualidad.success');
});
