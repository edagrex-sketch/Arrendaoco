<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Api\ContratoController;
use App\Mail\SolicitudRentaMail;
use App\Mail\RentaRespondidaMail;
use Illuminate\Support\Facades\Mail;
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
    // Fase 2 — Nuevo flujo físico: «Ver Contrato» (reemplaza el wizard contrato-pago)
    Route::get('/inmuebles/{inmueble}/ver-contrato',
        [\App\Http\Controllers\ContratoFisicoController::class, 'verContrato']
    )->name('contratos.ver');
    Route::get('/contratos/{contrato}/descargar-registrar',
        [\App\Http\Controllers\ContratoFisicoController::class, 'registrarDescarga']
    )->name('contratos.descargar-registrar');
    Route::get('/contratos/{contrato}/subir-firmado',
        [\App\Http\Controllers\ContratoFisicoController::class, 'formSubirFirmado']
    )->name('contratos.subir-firmado');
    Route::post('/contratos/{contrato}/subir-firmado',
        [\App\Http\Controllers\ContratoFisicoController::class, 'subirFirmado']
    )->name('contratos.subir-firmado.post');

    // Rutas de Inmuebles
    Route::get('/mis-propiedades', [InmuebleController::class, 'index'])->name('inmuebles.index');
    Route::get('/mis-rentas', [InmuebleController::class, 'misRentas'])->name('inmuebles.mis_rentas');

    // PDF Downloads
    Route::get('/contratos/{contrato}/descargar', [InmuebleController::class, 'descargarContratoPdf'])->name('contratos.descargar');
    Route::get('/pagos/{pago}/descargar', [InmuebleController::class, 'descargarComprobantePdf'])->name('pagos.descargar_recibo');

    // ============================================================
    // Flujo de Aprobación / Rechazo del Propietario
    // ============================================================
    Route::get('/contratos/{contrato}/revision', function (\App\Models\Contrato $contrato) {
        // Solo el propietario puede revisar
        if ($contrato->propietario_id !== Auth::id() && !Auth::user()->es_admin && !Auth::user()->tieneRol('admin')) {
            abort(403, 'No tienes permiso para revisar este contrato.');
        }
        if ($contrato->estatus !== 'pendiente_aprobacion') {
            return redirect()->route('inmuebles.index')->with('info', 'Esta solicitud ya fue procesada.');
        }
        $contrato->load('inmueble.propietario', 'inquilino');
        return view('inmuebles.revision_contrato', compact('contrato'));
    })->name('contratos.revision');

    Route::post('/contratos/{contrato}/aprobar', function (\Illuminate\Http\Request $request, \App\Models\Contrato $contrato) {
        // LEGADO: ruta de aprobación del flujo anterior (pendiente_aprobacion)
        // Para contratos nuevos del flujo físico, usar ContratoFisicoController@subirFirmado
        if ($contrato->propietario_id !== Auth::id()) abort(403);
        if (!in_array($contrato->estatus, ['pendiente_aprobacion', 'pdf_descargado'])) {
            return redirect()->route('inmuebles.index')->with('info', 'Esta solicitud ya fue procesada.');
        }

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            // 1. Activar el contrato (sin firma digital)
            $contrato->update(['estatus' => 'activo']);

            // 2. Marcar el inmueble como rentado
            $contrato->inmueble->update(['estatus' => 'rentado']);

            // 3. Crear evento de calendario
            \App\Models\Evento::create([
                'usuario_id'  => $contrato->inquilino_id,
                'renta_id'    => $contrato->id,
                'titulo'      => 'Renta Aprobada: ' . $contrato->inmueble->titulo,
                'descripcion' => '¡Tu renta fue aprobada! Ya puedes coordinar la entrega de llaves.',
                'fecha'       => now(),
            ]);

            // 4. Notificar al inquilino (opcional)
            try {
                $contrato->load('inmueble', 'inquilino');
                Mail::to(optional($contrato->inquilino)->email)->send(new RentaRespondidaMail($contrato, 'aprobada'));
            } catch (\Exception $e) {}

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('inmuebles.index')->with('success', '¡Contrato activado! El inmueble ha sido marcado como rentado.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Error al aprobar: ' . $e->getMessage());
        }
    })->name('contratos.aprobar');

    Route::post('/contratos/{contrato}/rechazar', function (\App\Models\Contrato $contrato) {
        if ($contrato->propietario_id !== Auth::id()) abort(403);
        if ($contrato->estatus !== 'pendiente_aprobacion') {
            return redirect()->route('inmuebles.index')->with('info', 'Esta solicitud ya fue procesada.');
        }

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            // 1. Marcar contrato como rechazado
            $contrato->update(['estatus' => 'rechazado']);

            // 2. El inmueble ya sigue disponible (nunca se marcó rentado)
            // 3. Eliminar el pago pendiente (liberar fondos congelados)
            \App\Models\Pago::where('contrato_id', $contrato->id)
                ->where('estatus', 'pendiente')
                ->delete();

            // 4. Notificar al inquilino
            try {
                $contrato->load('inmueble', 'inquilino');
                Mail::to(optional($contrato->inquilino)->email)->send(new RentaRespondidaMail($contrato, 'rechazada'));
            } catch (\Exception $e) {}

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('inmuebles.index')->with('success', 'Solicitud rechazada. Los fondos del inquilino serán liberados.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Error al rechazar: ' . $e->getMessage());
        }
    })->name('contratos.rechazar');
    
    // Ruta solo para pruebas: cancelar renta y desvincular
    Route::delete('/mis-rentas/cancelar/{contrato}', function (\Illuminate\Http\Request $request, \App\Models\Contrato $contrato) {
        if ($contrato->inquilino_id === \Illuminate\Support\Facades\Auth::id()) {
            $inmueble = $contrato->inmueble;
            if ($inmueble) {
                $inmueble->update(['estatus' => 'disponible']);
                
                // Buscar todos los contratos de este usuario para este inmueble (por si hay duplicados de prueba)
                $contratosIds = \App\Models\Contrato::where('inquilino_id', \Illuminate\Support\Facades\Auth::id())
                                ->where('inmueble_id', $inmueble->id)
                                ->pluck('id');
                
                // Borrar pagos y eventos asociados a estos contratos para limpiar el UI
                \App\Models\Pago::whereIn('contrato_id', $contratosIds)->delete();
                \App\Models\Evento::whereIn('renta_id', $contratosIds)->delete();
                
                // Borrar los contratos
                \App\Models\Contrato::whereIn('id', $contratosIds)->delete();
            } else {
                $contrato->delete();
            }
        }
        return redirect()->route('inmuebles.mis_rentas')->with('success', 'Renta cancelada y desvinculada exitosamente.');
    })->name('rentas.cancelar');

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


// ─── Rutas de Pago (flujo mensual vigente) ────────────────────────────────
// NOTA: El checkout inicial (wizard + hold) fue eliminado en la Fase 0.
// Los contratos ahora se inician desde ContratoFisicoController@verContrato.
Route::middleware('auth')->prefix('pagos')->group(function () {

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
