<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Inmueble;
use App\Models\Contrato;
use App\Services\NotificationService;
use App\Events\NuevoContratoIniciado;
use App\Events\ContratoStatusChanged;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * ContratoFisicoController
 * ─────────────────────────────────────────────────────────────────
 * Flujo de arrendamiento físico (sin firma digital):
 *
 *   1. Inquilino presiona "Ver Contrato" → SOLO previsualización (NO crea nada en BD)
 *   2. Inquilino presiona "Confirmar y Descargar PDF" → crea el contrato en BD
 *      y descarga el PDF en un único paso atómico.
 *   3. Propietario sube el escaneo firmado → estado 'activo' → inmueble = 'rentado'
 */
class ContratoFisicoController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | 1. Ver Contrato (Inquilino) — SOLO PREVISUALIZACIÓN, sin escribir en BD
    |--------------------------------------------------------------------------
    | Muestra el precontrato al inquilino usando los datos del inmueble.
    | NO escribe nada en la BD. El contrato solo se crea cuando el usuario
    | confirma explícitamente haciendo clic en "Confirmar y Descargar PDF".
    */
    public function verContrato(Inmueble $inmueble): View|RedirectResponse
    {
        // Guardia: el propietario no puede rentar su propia propiedad
        if ($inmueble->propietario_id === Auth::id()) {
            return redirect()->route('inmuebles.show', $inmueble)
                ->with('error', 'No puedes arrendar tu propia propiedad.');
        }

        // Guardia: el inmueble debe estar disponible
        if ($inmueble->estatus !== 'disponible') {
            return redirect()->route('inmuebles.show', $inmueble)
                ->with('error', 'Esta propiedad ya no está disponible.');
        }

        // Guardia: el inquilino no puede tener otro contrato activo
        $tieneRentaActiva = Contrato::where('inquilino_id', Auth::id())
            ->where('estatus', 'activo')
            ->exists();

        if ($tieneRentaActiva) {
            return redirect()->route('inmuebles.show', $inmueble)
                ->with('error', 'Ya tienes una propiedad rentada. Finaliza tu contrato actual antes de rentar otra.');
        }

        $inmueble->load('propietario', 'servicios', 'mascotas', 'zonasComunes');

        // Si ya existe un contrato en proceso (descargó antes y regresó), mostrarlo
        $contratoExistente = Contrato::where('inmueble_id', $inmueble->id)
            ->where('inquilino_id', Auth::id())
            ->whereIn('estatus', ['disponible', 'pdf_descargado'])
            ->latest()
            ->first();

        if ($contratoExistente) {
            $contratoExistente->load('inquilino');
            return view('inmuebles.ver_contrato', [
                'inmueble' => $inmueble,
                'contrato' => $contratoExistente,
                'esPrevia' => false,
            ]);
        }

        // Primera visita: construir contrato provisional EN MEMORIA (sin guardar en BD)
        $duracionMeses = $inmueble->duracion_contrato_meses ?? 12;
        $plazo = $duracionMeses >= 12
            ? floor($duracionMeses / 12) . ' año' . (floor($duracionMeses / 12) > 1 ? 's' : '')
            : $duracionMeses . ' meses';

        $contratoPrevia = new Contrato([
            'inmueble_id'    => $inmueble->id,
            'propietario_id' => $inmueble->propietario_id,
            'inquilino_id'   => Auth::id(),
            'fecha_inicio'   => now()->toDateString(),
            'fecha_fin'      => now()->addMonths($duracionMeses)->toDateString(),
            'plazo'          => $plazo,
            'renta_mensual'  => $inmueble->renta_mensual,
            'deposito'       => $inmueble->deposito ?? $inmueble->renta_mensual,
            'estatus'        => 'disponible',
        ]);
        $contratoPrevia->setRelation('inquilino', Auth::user());
        $contratoPrevia->setRelation('inmueble', $inmueble);

        return view('inmuebles.ver_contrato', [
            'inmueble' => $inmueble,
            'contrato' => $contratoPrevia,
            'esPrevia' => true,  // La vista usa esto para apuntar al endpoint correcto
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | 2. Confirmar y Descargar PDF (Inquilino)
    |--------------------------------------------------------------------------
    | Punto de confirmación explícita. Crea el contrato en BD (si no existe)
    | y devuelve el PDF en una sola acción atómica.
    */
    public function confirmarYDescargar(Inmueble $inmueble): RedirectResponse|\Symfony\Component\HttpFoundation\Response
    {
        if ($inmueble->propietario_id === Auth::id()) {
            return redirect()->route('inmuebles.show', $inmueble)
                ->with('error', 'No puedes arrendar tu propia propiedad.');
        }

        if ($inmueble->estatus !== 'disponible') {
            return redirect()->route('inmuebles.show', $inmueble)
                ->with('error', 'Esta propiedad ya no está disponible.');
        }

        $tieneRentaActiva = Contrato::where('inquilino_id', Auth::id())
            ->where('estatus', 'activo')
            ->exists();

        if ($tieneRentaActiva) {
            return redirect()->route('inmuebles.show', $inmueble)
                ->with('error', 'Ya tienes una propiedad rentada. Finaliza tu contrato actual antes de rentar otra.');
        }

        // Crear o recuperar contrato en transacción atómica
        $contrato = DB::transaction(function () use ($inmueble) {
            $existing = Contrato::where('inmueble_id', $inmueble->id)
                ->where('inquilino_id', Auth::id())
                ->whereIn('estatus', ['pendiente_aprobacion', 'pdf_descargado', 'pendiente', 'disponible'])
                ->lockForUpdate()
                ->latest()
                ->first();

            if ($existing) {
                return $existing;
            }

            $duracionMeses = $inmueble->duracion_contrato_meses ?? 12;
            $plazo = $duracionMeses >= 12
                ? floor($duracionMeses / 12) . ' año' . (floor($duracionMeses / 12) > 1 ? 's' : '')
                : $duracionMeses . ' meses';

            $nuevoContrato = Contrato::create([
                'inmueble_id'       => $inmueble->id,
                'propietario_id'    => $inmueble->propietario_id,
                'inquilino_id'      => Auth::id(),
                'fecha_inicio'      => now()->toDateString(),
                'fecha_fin'         => now()->addMonths($duracionMeses)->toDateString(),
                'plazo'             => $plazo,
                'renta_mensual'     => $inmueble->renta_mensual,
                'deposito'          => $inmueble->deposito ?? $inmueble->renta_mensual,
                'estatus'           => 'pendiente_aprobacion',
            ]);

            // Disparar evento de tiempo real
            event(new NuevoContratoIniciado($nuevoContrato));

            // Notificación persistente para el propietario
            NotificationService::send(
                $inmueble->propietario_id,
                'Nueva solicitud de renta',
                "El usuario " . Auth::user()->nombre . " está interesado en tu inmueble: " . $inmueble->titulo,
                'renta',
                $nuevoContrato->id
            );

            // ==== Crear chat automático y enviar mensaje tipo solicitud ====
            $authId = Auth::id();
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

            $nombreUsuario = Auth::user()->nombre;
            $mensajeTexto = "Hola soy {$nombreUsuario} y me interesa tu inmueble, estoy en espera de informacion para comenzar a rentar ";
            
            $mensaje = $chat->mensajes()->create([
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

        // Si ya tiene session de stripe... no generar de nuevo (en este caso lo pasamos si ya se registró el PI, pero como es en el success que lo registramos, aquí sólo mandamos a pagar) 
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
        try {
            $paymentIntentData = [
                'capture_method' => 'manual',
            ];
            
            if ($inmueble->propietario && $inmueble->propietario->stripe_account_id && $inmueble->propietario->stripe_onboarding_completed) {
                // Transferir fondos automáticamente a la cuenta del propietario una vez que se capturen
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
                            'description' => 'Propiedad: ' . ($inmueble->titulo ?? 'N/A'),
                        ],
                        'unit_amount' => (int) ($inmueble->renta_mensual * 100),
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'payment_intent_data' => $paymentIntentData,
                'success_url' => route('contratos.stripe.reserva.success', ['contrato' => $contrato->id]) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('inmuebles.show', $inmueble->id),
            ]);

            return redirect()->away($session->url);
        } catch (\Exception $e) {
            return back()->with('error', 'Error al procesar el pago con Stripe: ' . $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | 3. Registrar Descarga + Generar PDF — ruta legacy para contratos existentes
    |--------------------------------------------------------------------------
    */
    public function registrarDescarga(Contrato $contrato): RedirectResponse|\Symfony\Component\HttpFoundation\Response
    {
        abort_unless(
            in_array(Auth::id(), [$contrato->inquilino_id, $contrato->propietario_id]),
            403,
            'No tienes permiso para descargar este contrato.'
        );

        DB::transaction(function () use ($contrato) {
            if (is_null($contrato->pdf_descargado_at)) {
                $contrato->update([
                    'pdf_descargado_at' => now(),
                    'estatus'           => 'pdf_descargado',
                ]);
            }
        });

        $contrato->load('inmueble.propietario', 'inquilino');

        $pdf = Pdf::loadView('pdf.contrato', compact('contrato'));

        return $pdf->download('Contrato_ArrendaOco_' . $contrato->id . '.pdf');
    }

    /*
    |--------------------------------------------------------------------------
    | 4. Formulario de Subida de Contrato Firmado (Propietario)
    |--------------------------------------------------------------------------
    */
    public function formSubirFirmado(Contrato $contrato): View|RedirectResponse
    {
        abort_unless($contrato->propietario_id === Auth::id(), 403);

        if ($contrato->estatus === 'activo') {
            return redirect()->route('inmuebles.index')
                ->with('info', 'Este contrato ya está activo. El archivo firmado fue registrado previamente.');
        }

        $contrato->load('inmueble', 'inquilino');

        return view('inmuebles.subir_firmado', compact('contrato'));
    }

    /*
    |--------------------------------------------------------------------------
    | 5. Procesar Subida del Contrato Firmado (Propietario)
    |--------------------------------------------------------------------------
    */
    public function subirFirmado(Request $request, Contrato $contrato): RedirectResponse
    {
        abort_unless($contrato->propietario_id === Auth::id(), 403);

        $request->validate([
            'archivo_firmado' => [
                'required',
                'file',
                'mimes:pdf,jpg,jpeg,png,webp',
                'max:10240',
            ],
        ], [
            'archivo_firmado.required' => 'Debes subir el archivo del contrato firmado.',
            'archivo_firmado.mimes'    => 'El archivo debe ser PDF, JPG, PNG o WebP.',
            'archivo_firmado.max'      => 'El archivo no debe superar los 10 MB.',
        ]);

        DB::transaction(function () use ($request, $contrato) {
            $path = $request->file('archivo_firmado')
                ->store('contratos_firmados', 'public');

            $contrato->update([
                'archivo_firmado'   => $path,
                'archivo_subido_at' => now(),
                'estatus'           => 'activo',
            ]);

            $contrato->inmueble->update(['estatus' => 'rentado']);
            
            // Disparar evento de estatus cambiado para el inquilino
            event(new \App\Events\ContratoStatusChanged($contrato->id, 'activo', $contrato->inquilino_id));

            // Notificación persistente para el inquilino
            NotificationService::send(
                $contrato->inquilino_id,
                '¡Contrato Activo!',
                "Tu contrato para el inmueble " . $contrato->inmueble->titulo . " ha sido activado. ¡Felicidades!",
                'renta',
                $contrato->id
            );
        });

        try {
            $contrato->load('inmueble', 'inquilino');
        } catch (\Exception $e) { /* silencioso */ }

        return redirect()->route('inmuebles.index')
            ->with('success', '✅ ¡Contrato activado! El arrendamiento está ahora vigente y el inmueble fue marcado como rentado.');
    }
}
