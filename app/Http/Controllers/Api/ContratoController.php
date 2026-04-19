<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contrato;
use App\Models\Inmueble;
use App\Models\EstadoCuenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\EstadoCuentaMail;
use Carbon\Carbon;

class ContratoController extends Controller
{
    use AuthorizesRequests;

    /**
     * Listar contratos del usuario autenticado (como propietario o inquilino)
     */
    public function index(Request $request)
    {
        $usuario = $request->user();
        
        $contratos = Contrato::with(['inmueble', 'propietario', 'inquilino'])
            ->where('propietario_id', $usuario->id)
            ->orWhere('inquilino_id', $usuario->id)
            ->latest()
            ->get();

        return response()->json([
            'data' => $contratos->map(function($c) {
                return $this->serializeContrato($c);
            })
        ]);
    }

    protected function serializeContrato($c) {
        return [
            'id' => $c->id,
            'inmueble_id' => $c->inmueble_id,
            'inmueble_titulo' => $c->inmueble->titulo,
            'rutas_imagen' => \App\Support\MediaUrl::fromStoragePath($c->inmueble->imagen),
            'arrendador_id' => $c->propietario_id,
            'arrendador_nombre' => $c->propietario->nombre,
            'inquilino_id' => $c->inquilino_id,
            'inquilino_nombre' => $c->inquilino->nombre,
            'fecha_inicio' => $c->fecha_inicio,
            'fecha_fin' => $c->fecha_fin,
            'monto_mensual' => $c->renta_mensual,
            'deposito' => $c->deposito,
            'dia_pago' => 5, // Default day
            'estado' => ($c->estatus === 'activo' || $c->estatus === 'activa') ? 'activa' : $c->estatus,
        ];
    }

    public function show(Contrato $contrato, Request $request)
    {
        if ($contrato->propietario_id !== $request->user()->id && $contrato->inquilino_id !== $request->user()->id) {
            abort(403);
        }
        $contrato->load(['inmueble', 'propietario', 'inquilino']);
        return response()->json([
            'data' => $this->serializeContrato($contrato)
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | VER PRECONTRATO (Previsualización)
    |--------------------------------------------------------------------------
    */
    public function verContrato(Inmueble $inmueble, Request $request)
    {
        $usuario = $request->user();

        if ($inmueble->propietario_id === $usuario->id) {
            return response()->json(['message' => 'No puedes arrendar tu propia propiedad.'], 403);
        }

        if ($inmueble->estatus !== 'disponible') {
            return response()->json(['message' => 'Esta propiedad ya no está disponible.'], 422);
        }

        $inmueble->load('propietario');
        
        $duracionMeses = $inmueble->duracion_contrato_meses ?? 12;
        $plazo = $duracionMeses >= 12
            ? floor($duracionMeses / 12) . ' año' . (floor($duracionMeses / 12) > 1 ? 's' : '')
            : $duracionMeses . ' meses';

        return response()->json([
            'inmueble' => [
                'id' => $inmueble->id,
                'titulo' => $inmueble->titulo,
                'propietario_nombre' => $inmueble->propietario->nombre,
                'renta_mensual' => $inmueble->renta_mensual,
                'deposito' => $inmueble->deposito ?? $inmueble->renta_mensual,
                'duracion' => $plazo,
                'direccion' => $inmueble->direccion,
                'area' => $inmueble->area ?? '0.00',
                'habitaciones' => $inmueble->habitaciones ?? 1,
                'banos' => $inmueble->banos ?? 1,
                'compartida' => $inmueble->compartida ? 'El inmueble comparte acceso principal.' : 'El inmueble cuenta con acceso independiente.',
                'estacionamiento' => ($inmueble->estacionamiento > 0) ? "El alquiler incluye {$inmueble->estacionamiento} cajón(es) de estacionamiento." : "El alquiler NO incluye un cajón de estacionamiento asignado.",
                'mobiliario' => $inmueble->amueblado ? 'Amueblada' : 'Sin amueblar',
                'servicios_inquilino' => $inmueble->servicios_incluidos ?? 'No especificado',
                'mascotas_permitidas' => $inmueble->acepta_mascotas ? 'EL Arrendador autoriza explícitamente la tenencia de mascotas.' : 'No se permite la tenencia de mascotas.',
                'mascotas_detalles' => $inmueble->detalles_mascotas ?? 'Perros/Gatos',
                'clausulas_adicionales' => $inmueble->clausulas_adicionales ?? 'No se estipularon cláusulas especiales adicionales por parte del Arrendador para este contrato.',
            ],
            'inquilino' => [
                'id' => $usuario->id,
                'nombre' => $usuario->nombre,
            ],
            'fecha_actual' => now()->format('d \d\e F \d\e\l Y'),
            'fecha_inicio' => now()->format('d \d\e F \d\e\l Y'),
            'fecha_fin' => now()->addMonths($duracionMeses)->format('d \d\e F \d\e\l Y'),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | CONFIRMAR RENTA (CREAR CONTRATO + STRIPE HOLD)
    |--------------------------------------------------------------------------
    */
    public function rentar(Request $request, Inmueble $inmueble)
    {
        $usuario = $request->user();

        if ($inmueble->propietario_id === $usuario->id) {
            return response()->json(['message' => 'No puedes arrendar tu propia propiedad.'], 403);
        }

        if ($inmueble->estatus !== 'disponible') {
            return response()->json(['message' => 'Esta propiedad ya no está disponible.'], 422);
        }

        // Crear el contrato en estatus pendiente de aprobación
        $contrato = DB::transaction(function () use ($inmueble, $usuario) {
            $duracionMeses = $inmueble->duracion_contrato_meses ?? 12;
            $plazo = $duracionMeses >= 12
                ? floor($duracionMeses / 12) . ' año' . (floor($duracionMeses / 12) > 1 ? 's' : '')
                : $duracionMeses . ' meses';

            $nuevoContrato = Contrato::create([
                'inmueble_id'       => $inmueble->id,
                'propietario_id'    => $inmueble->propietario_id,
                'inquilino_id'      => $usuario->id,
                'fecha_inicio'      => now()->toDateString(),
                'fecha_fin'         => now()->addMonths($duracionMeses)->toDateString(),
                'plazo'             => $plazo,
                'renta_mensual'     => $inmueble->renta_mensual,
                'deposito'          => $inmueble->deposito ?? $inmueble->renta_mensual,
                'estatus'           => 'pendiente_aprobacion',
            ]);

            // ==== Crear chat automático y enviar mensaje tipo solicitud ====
            $authId = $usuario->id;
            $id1 = min($authId, $inmueble->propietario_id);
            $id2 = max($authId, $inmueble->propietario_id);

            $chat = \App\Models\Chat::firstOrCreate(
                [
                    'usuario_1'   => $id1,
                    'usuario_2'   => $id2,
                    'inmueble_id' => $inmueble->id
                ],
                [
                    'activo'          => true,
                    'last_message_at' => now()
                ]
            );

            $mensajeTexto = "Hola, soy {$usuario->nombre} y me interesa tu inmueble, acabo de solicitar la renta y estoy en espera de tu aprobación.";
            
            $chat->mensajes()->create([
                'sender_id' => $authId,
                'contenido' => $mensajeTexto,
                'tipo'      => 'solicitud_renta'
            ]);

            $chat->update([
                'last_message'    => $mensajeTexto,
                'last_message_at' => now()
            ]);

            return $nuevoContrato;
        });

        // Generar sesión de Stripe para el móvil (Checkout URL)
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
        try {
            $paymentIntentData = [
                'capture_method' => 'manual', // HOLD DE FONDOS
            ];
            
            if ($inmueble->propietario && $inmueble->propietario->stripe_account_id && $inmueble->propietario->stripe_onboarding_completed) {
                $paymentIntentData['transfer_data'] = [
                    'destination' => $inmueble->propietario->stripe_account_id
                ];
            }

            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'mxn',
                        'product_data' => [
                            'name' => 'Validación de Fondos y Reserva de Renta',
                            'description' => 'Propiedad: ' . $inmueble->titulo,
                        ],
                        'unit_amount' => (int) ($inmueble->renta_mensual * 100),
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'payment_intent_data' => $paymentIntentData,
                'success_url' => url('/api/contratos/' . $contrato->id . '/success?session_id={CHECKOUT_SESSION_ID}'),
                'cancel_url' => url('/api/contratos/' . $contrato->id . '/cancel'),
            ]);

            return response()->json([
                'success' => true,
                'contrato_id' => $contrato->id,
                'stripe_url' => $session->url // El móvil abrirá esto para el pago
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error con Stripe: ' . $e->getMessage()], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SUBIR CONTRATO FIRMADO (PROPIETARIO)
    |--------------------------------------------------------------------------
    */
    public function subirFirmado(Request $request, Contrato $contrato)
    {
        $usuario = $request->user();
        if ($contrato->propietario_id !== $usuario->id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $request->validate([
            'archivo' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        DB::transaction(function () use ($request, $contrato) {
            $path = $request->file('archivo')->store('contratos_firmados', 'public');

            $contrato->update([
                'archivo_firmado'   => $path,
                'archivo_subido_at' => now(),
                'estatus'           => 'activo',
            ]);

            $contrato->inmueble->update(['estatus' => 'rentado']);
        });

        return response()->json(['success' => true, 'message' => 'Contrato activado exitosamente.']);
    }

    /* ======================================================
     *  ESTADO DE CUENTA (JSON)
     * ====================================================== */
    public function estadoCuenta(Contrato $contrato, Request $request)
    {
        if (
            $contrato->propietario_id !== $request->user()->id &&
            $contrato->inquilino_id !== $request->user()->id
        ) {
            abort(403, 'No autorizado');
        }

        $pagos = $contrato->pagos()
            ->orderBy('anio')
            ->orderBy('mes')
            ->get();

        return response()->json([
            'contrato_id' => $contrato->id,
            'resumen' => [
                'total_pagado'    => $pagos->where('estatus', 'pagado')->sum('monto'),
                'total_pendiente' => $pagos->whereIn('estatus', ['pendiente', 'vencido'])->sum('monto'),
                'vencidos'        => $pagos->where('estatus', 'vencido')->count(),
            ],
            'pagos' => $pagos
        ]);
    }

    /* ======================================================
     *  ESTADO DE CUENTA PDF + HISTÓRICO + CORREO
     * ====================================================== */
    public function estadoCuentaPdf(Contrato $contrato, Request $request)
    {
        if (
            $contrato->propietario_id !== $request->user()->id &&
            $contrato->inquilino_id !== $request->user()->id
        ) {
            abort(403, 'No autorizado');
        }

        // Cargar relaciones
        $contrato->load(['pagos', 'inmueble', 'inquilino', 'propietario']);

        $pagos = $contrato->pagos()
            ->orderBy('anio')
            ->orderBy('mes')
            ->get();

        $resumen = [
            'total_pagado'    => $pagos->where('estatus', 'pagado')->sum('monto'),
            'total_pendiente' => $pagos->whereIn('estatus', ['pendiente', 'vencido'])->sum('monto'),
            'vencidos'        => $pagos->where('estatus', 'vencido')->count(),
        ];

        $fecha = Carbon::now();

        $ruta = "estados_cuenta/contrato_{$contrato->id}";
        $nombre = "estado_cuenta_{$fecha->year}_{$fecha->month}.pdf";
        $pathRelativo = "{$ruta}/{$nombre}";
        $pathAbsoluto = storage_path("app/{$pathRelativo}");

        // Crear carpeta si no existe (CRÍTICO)
        if (!Storage::disk('local')->exists($ruta)) {
            Storage::disk('local')->makeDirectory($ruta);
        }

        // Generar PDF
        $pdf = Pdf::loadView('pdf.estado_cuenta', [
            'contrato' => $contrato,
            'pagos'    => $pagos,
            'resumen'  => $resumen,
        ])->setPaper('letter');

        // GUARDADO DEFINITIVO (WINDOWS SAFE)
        file_put_contents($pathAbsoluto, $pdf->output());

        // Guardar / actualizar histórico
        $estadoCuenta = EstadoCuenta::updateOrCreate(
            [
                'contrato_id' => $contrato->id,
                'mes'  => $fecha->month,
                'anio' => $fecha->year,
            ],
            [
                'ruta_pdf'     => $pathRelativo,
                'generado_por' => $request->user()->id,
            ]
        );

        // Enviar correo al inquilino
        Mail::to($contrato->inquilino->email)
            ->send(new EstadoCuentaMail($estadoCuenta));

        return $pdf->download($nombre);
    }

    /* ======================================================
     *  LISTAR ESTADOS DE CUENTA HISTÓRICOS
     * ====================================================== */
    public function listarEstadosCuenta(Contrato $contrato, Request $request)
    {
        if (
            $contrato->propietario_id !== $request->user()->id &&
            $contrato->inquilino_id !== $request->user()->id
        ) {
            abort(403, 'No autorizado');
        }

        return response()->json(
            $contrato->estadosCuenta()
                ->orderByDesc('anio')
                ->orderByDesc('mes')
                ->get()
        );
    }

    /* ======================================================
     *  DESCARGAR ESTADO DE CUENTA
     * ====================================================== */
    public function descargarEstadoCuenta(EstadoCuenta $estadoCuenta, Request $request)
    {
        $contrato = $estadoCuenta->contrato;

        if (
            $contrato->propietario_id !== $request->user()->id &&
            $contrato->inquilino_id !== $request->user()->id
        ) {
            abort(403, 'No autorizado');
        }

        if (!Storage::disk('local')->exists($estadoCuenta->ruta_pdf)) {
            abort(404, 'Archivo no encontrado');
        }

        return Storage::disk('local')->download(
            $estadoCuenta->ruta_pdf,
            basename($estadoCuenta->ruta_pdf)
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DESCARGAR CONTRATO PDF (INQUILINO/ARRENDADOR)
    |--------------------------------------------------------------------------
    */
    public function descargarContratoPdf(Contrato $contrato)
    {
        abort_unless(
            in_array(auth()->id(), [$contrato->inquilino_id, $contrato->propietario_id]),
            403
        );

        $contrato->load('inmueble.propietario', 'inquilino');
        $pdf = Pdf::loadView('pdf.contrato', compact('contrato'));

        return $pdf->download('Contrato_ArrendaOco_' . $contrato->id . '.pdf');
    }

    public function update(Request $request, Contrato $contrato)
    {
        $usuario = $request->user();
        if ($contrato->propietario_id !== $usuario->id && $contrato->inquilino_id !== $usuario->id) {
            abort(403);
        }

        $data = $request->validate([
            'estado' => 'required|string'
        ]);

        $nuevoEstado = $data['estado'];
        // Normalización para la DB
        if (in_array($nuevoEstado, ['activa', 'activo'])) $nuevoEstado = 'activo';
        if (in_array($nuevoEstado, ['finalizada', 'cancelada', 'finalizado'])) $nuevoEstado = 'finalizado';
        if (in_array($nuevoEstado, ['rechazada', 'rechazado'])) $nuevoEstado = 'rechazado';

        $contrato->update(['estatus' => $nuevoEstado]);

        if (in_array($nuevoEstado, ['finalizado', 'rechazado'])) {
            $contrato->inmueble->update(['estatus' => 'disponible']);
        }

        return response()->json(['success' => true, 'data' => $contrato]);
    }

    public function cancelar(Contrato $contrato, Request $request)
    {
        return $this->update($request->merge(['estado' => 'finalizado']), $contrato);
    }

    public function renovar(Contrato $contrato, Request $request)
    {
        return $this->update($request->merge(['estado' => 'activo']), $contrato);
    }
}
