<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contrato;
use App\Models\Inmueble;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Exports\EstadoCuentaExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ContratoController extends Controller
{
    use AuthorizesRequests;

    /**
     * Rentar un inmueble (crear contrato)
     */
    public function rentar(Request $request, Inmueble $inmueble)
    {
        // 1️⃣ Validar datos de entrada
        $data = $request->validate([
            'inquilino_id'   => 'required|exists:usuarios,id',
            'fecha_inicio'   => 'required|date',
            'fecha_fin'      => 'nullable|date|after:fecha_inicio',
            'renta_mensual'  => 'required|numeric|min:0',
            'deposito'       => 'nullable|numeric|min:0',
        ]);

        // 2️⃣ Validar que el usuario sea el propietario
        if ($inmueble->propietario_id !== $request->user()->id) {
            abort(403, 'Solo el propietario puede rentar este inmueble');
        }

        // 3️⃣ Validar que el inmueble esté disponible
        if ($inmueble->estatus !== 'disponible') {
            abort(422, 'El inmueble no está disponible para renta');
        }

        // 4️⃣ Evitar que el propietario sea el inquilino
        if ($data['inquilino_id'] == $request->user()->id) {
            abort(422, 'El propietario no puede ser el inquilino');
        }

        // 5️⃣ Transacción: contrato + cambio de estado
        $contrato = DB::transaction(function () use ($data, $inmueble, $request) {

            $contrato = Contrato::create([
                'inmueble_id'    => $inmueble->id,
                'propietario_id' => $request->user()->id,
                'inquilino_id'   => $data['inquilino_id'],
                'fecha_inicio'   => $data['fecha_inicio'],
                'fecha_fin'      => $data['fecha_fin'] ?? null,
                'renta_mensual'  => $data['renta_mensual'],
                'deposito'       => $data['deposito'] ?? null,
                'estatus'        => 'activo',
            ]);

            $inmueble->update([
                'estatus' => 'rentado'
            ]);

            return $contrato;
        });

        return response()->json($contrato, 201);
    }

    /**
     * Estado de cuenta del contrato
     */
    public function estadoCuenta(Contrato $contrato, Request $request)
    {
        // 🔐 Seguridad: solo propietario o inquilino
        if (
            $contrato->propietario_id !== $request->user()->id &&
            $contrato->inquilino_id !== $request->user()->id
        ) {
            abort(403, 'No autorizado para ver este contrato');
        }

        $pagos = $contrato->pagos()
            ->orderBy('anio')
            ->orderBy('mes')
            ->get();

        return response()->json([
            'contrato_id' => $contrato->id,
            'inmueble_id' => $contrato->inmueble_id,
            'estatus_contrato' => $contrato->estatus,

            'resumen' => [
                'total_pagos' => $pagos->count(),

                'pagados' => $pagos->where('estatus', 'pagado')->count(),
                'pendientes' => $pagos->where('estatus', 'pendiente')->count(),
                'vencidos' => $pagos->where('estatus', 'vencido')->count(),

                'total_pagado' => $pagos->where('estatus', 'pagado')->sum('monto'),

                'total_pendiente' => $pagos
                    ->whereIn('estatus', ['pendiente', 'vencido'])
                    ->sum('monto'),

                'total_recargos' => $pagos->sum('recargo'),

                'total_a_pagar' => $pagos
                    ->whereIn('estatus', ['pendiente', 'vencido'])
                    ->sum('total_con_recargo'),
            ],

            'pagos' => $pagos->map(function ($pago) {
                return [
                    'id' => $pago->id,
                    'mes' => $pago->mes,
                    'anio' => $pago->anio,
                    'estatus' => $pago->estatus,

                    'monto_base' => $pago->monto,
                    'recargo' => $pago->recargo,
                    'total' => $pago->estatus === 'pagado'
                        ? $pago->monto
                        : $pago->total_con_recargo,

                    'dias_atraso' => $pago->dias_atraso,
                    'fecha_pago' => $pago->fecha_pago,
                ];
            })
        ]);
    }


    /**
     * Renovar contrato
     * ❌ Bloqueado si existen pagos vencidos
     */
    public function renovar(Request $request, Contrato $contrato)
    {
        // 🔐 Solo el propietario puede renovar
        if ($contrato->propietario_id !== $request->user()->id) {
            abort(403, 'No autorizado para renovar este contrato');
        }

        // ❌ Bloquear si hay pagos vencidos
        $tieneVencidos = $contrato->pagos()
            ->where('estatus', 'vencido')
            ->exists();

        if ($tieneVencidos) {
            abort(422, 'No se puede renovar el contrato: existen pagos vencidos');
        }

        // ✅ Validar nueva fecha
        $data = $request->validate([
            'fecha_fin' => 'required|date|after:fecha_inicio',
        ]);

        $contrato->update([
            'fecha_fin' => $data['fecha_fin'],
        ]);

        return response()->json([
            'message' => 'Contrato renovado correctamente',
            'contrato' => $contrato
        ]);
    }

    public function exportarEstadoCuentaExcel(Contrato $contrato, Request $request)
    {
        // 🔐 Seguridad
        if (
            $contrato->propietario_id !== $request->user()->id &&
            $contrato->inquilino_id !== $request->user()->id
        ) {
            abort(403, 'No autorizado');
        }

        return Excel::download(
            new EstadoCuentaExport($contrato),
            'estado_cuenta_contrato_' . $contrato->id . '.xlsx'
        );
    }
public function exportarEstadoCuentaPdf(Contrato $contrato, Request $request)
{
    // 🔐 Seguridad
    if (
        $contrato->propietario_id !== $request->user()->id &&
        $contrato->inquilino_id !== $request->user()->id
    ) {
        abort(403, 'No autorizado');
    }

    $pdf = Pdf::loadView('pdf.estado_cuenta', [
        'contrato' => $contrato
    ]);

    return $pdf->download('estado_cuenta_contrato_'.$contrato->id.'.pdf');
}

}
