<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function ingresos(Request $request)
    {
        $query = Pago::query();

        if ($request->filled('anio')) {
            $query->where('anio', $request->anio);
        }

        if ($request->filled('mes')) {
            $query->where('mes', $request->mes);
        }

        $reporte = $query
            ->select(
                'anio',
                'mes',
                DB::raw('COUNT(*) as total_pagos'),
                DB::raw("SUM(CASE WHEN estatus = 'pagado' THEN monto ELSE 0 END) as total_pagado"),
                DB::raw("SUM(CASE WHEN estatus = 'pendiente' THEN monto ELSE 0 END) as total_pendiente"),
                DB::raw("SUM(CASE WHEN estatus = 'vencido' THEN monto ELSE 0 END) as total_vencido")
            )
            ->groupBy('anio', 'mes')
            ->orderBy('anio')
            ->orderBy('mes')
            ->get();

        return response()->json([
            'filtros' => [
                'anio' => $request->anio,
                'mes' => $request->mes,
            ],
            'reporte' => $reporte
        ]);
    }
}
