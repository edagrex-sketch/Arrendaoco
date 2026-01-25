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

        if ($pago->estatus === 'pagado') {
            abort(422, 'Este pago ya fue liquidado');
        }

        $pago->update([
            'estatus' => 'pagado',
            'fecha_pago' => now(),
        ]);

        return response()->json($pago);
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
            'pagos' => $pagos
        ]);
    }
}
