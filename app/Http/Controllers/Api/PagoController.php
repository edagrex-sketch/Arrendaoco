<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contrato;
use App\Models\Pago;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PagoController extends Controller
{
    /**
     * Generar pago mensual del contrato
     */
    public function generar(Contrato $contrato)
    {
        if ($contrato->estatus !== 'activo') {
            abort(422, 'El contrato no está activo');
        }

        $fecha = Carbon::now();

        $pago = Pago::firstOrCreate(
            [
                'contrato_id' => $contrato->id,
                'mes' => $fecha->month,
                'anio' => $fecha->year,
            ],
            [
                'monto' => $contrato->renta_mensual,
                'estatus' => 'pendiente',
            ]
        );

        return response()->json($pago);
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
}
