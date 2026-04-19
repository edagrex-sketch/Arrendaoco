<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contrato;
use App\Models\Pago;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PagoController extends Controller
{
    /**
     * Generar pago mensual del contrato
     */

    public function generar(Request $request, Contrato $contrato)
    {
        // 1️⃣ Validar contrato
        if ($contrato->estatus !== 'activo') {
            abort(422, 'El contrato no está activo');
        }

        // 2️⃣ Validar input
        $data = $request->validate([
            'meses' => 'required|integer|min:1|max:24',
        ]);

        $meses = $data['meses'];
        $fechaInicio = Carbon::parse($contrato->fecha_inicio);

        $pagos = [];

        DB::transaction(function () use ($contrato, $fechaInicio, $meses, &$pagos) {

            for ($i = 0; $i < $meses; $i++) {
                $fechaPago = $fechaInicio->copy()->addMonths($i);

                $pago = Pago::firstOrCreate(
                    [
                        'contrato_id' => $contrato->id,
                        'mes' => $fechaPago->month,
                        'anio' => $fechaPago->year,
                    ],
                    [
                        'monto' => $contrato->renta_mensual,
                        'estatus' => 'pendiente',
                    ]
                );

                $pagos[] = $pago;
            }
        });

        return response()->json([
            'contrato_id' => $contrato->id,
            'pagos_generados' => count($pagos),
            'pagos' => $pagos,
        ], 201);
    }

    /**
     * Pagar renta
     */
    public function pagar(Pago $pago, Request $request)
    {
        // Solo el inquilino puede pagar
        if ($pago->contrato->inquilino_id !== $request->user()->id) {
            abort(403, 'No autorizado para pagar este recibo');
        }

        // Generar sesión de Stripe
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'mxn',
                        'product_data' => [
                            'name' => 'Pago de Renta - Mes ' . $pago->mes . ' (' . $pago->anio . ')',
                            'description' => 'Inmueble: ' . $pago->contrato->inmueble->titulo,
                        ],
                        'unit_amount' => (int) ($pago->monto * 100),
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                // Usamos una ruta de éxito que marque el pago
                'success_url' => url('/api/pagos/' . $pago->id . '/success?session_id={CHECKOUT_SESSION_ID}'),
                'cancel_url' => url('/api/pagos/' . $pago->id . '/cancel'),
            ]);

            return response()->json([
                'url' => $session->url,
                'pago_id' => $pago->id
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error con Stripe: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Endpoint de éxito para marcar el pago
     */
    public function success(Pago $pago)
    {
        $pago->update([
            'estatus' => 'pagado',
            'fecha_pago' => now(),
        ]);

        return view('pago_success'); // Una vista simple que diga "Pago Exitoso"
    }
    public function estadoCuenta(Contrato $contrato, Request $request)
    {
        // Solo propietario o inquilino pueden ver el estado de cuenta
        if (
            $contrato->propietario_id !== $request->user()->id &&
            $contrato->inquilino_id !== $request->user()->id
        ) {
            abort(403, 'No autorizado para ver este contrato');
        }

        $pagos = $contrato->pagos()->orderBy('anio')->orderBy('mes')->get();

        return response()->json([
            'contrato_id' => $contrato->id,
            'inmueble_id' => $contrato->inmueble_id,
            'estatus_contrato' => $contrato->estatus,
            'resumen' => [
                'total_pagos' => $pagos->count(),
                'pagados' => $pagos->where('estatus', 'pagado')->count(),
                'pendientes' => $pagos->where('estatus', 'pendiente')->count(),
                'total_pagado' => $pagos->where('estatus', 'pagado')->sum('monto'),
                'total_pendiente' => $pagos->where('estatus', 'pendiente')->sum('monto'),
            ],
        ]);
    }

    public function pendientes(Request $request)
    {
        $usuario = $request->user();

        $pagos = Pago::with(['contrato.inmueble'])
            ->whereHas('contrato', function ($query) use ($usuario) {
                $query->where('propietario_id', $usuario->id)
                      ->orWhere('inquilino_id', $usuario->id);
            })
            ->whereIn('estatus', ['pendiente', 'vencido'])
            ->orderBy('anio')
            ->orderBy('mes')
            ->get();

        return response()->json($pagos->map(function($p) {
            return [
                'id' => $p->id,
                'contrato_id' => $p->contrato_id,
                'inmueble_titulo' => $p->contrato->inmueble->titulo,
                'monto' => $p->monto,
                'mes' => $p->mes,
                'anio' => $p->anio,
                'estatus' => $p->estatus,
                'fecha_pago' => Carbon::create($p->anio, $p->mes, 1)->toDateString(), // Simplified
            ];
        }));
    }
}
