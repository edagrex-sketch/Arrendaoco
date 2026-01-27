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

    /* ======================================================
     *  CREAR CONTRATO (RENTAR INMUEBLE)
     * ====================================================== */
    public function rentar(Request $request, Inmueble $inmueble)
    {
        $data = $request->validate([
            'inquilino_id'  => 'required|exists:usuarios,id',
            'fecha_inicio'  => 'required|date',
            'fecha_fin'     => 'nullable|date|after:fecha_inicio',
            'renta_mensual' => 'required|numeric|min:0',
            'deposito'      => 'nullable|numeric|min:0',
        ]);

        if ($inmueble->propietario_id !== $request->user()->id) {
            abort(403, 'Solo el propietario puede rentar este inmueble');
        }

        if ($inmueble->estatus !== 'disponible') {
            abort(422, 'El inmueble no está disponible');
        }

        if ($data['inquilino_id'] == $request->user()->id) {
            abort(422, 'El propietario no puede ser el inquilino');
        }

        $contrato = DB::transaction(function () use ($data, $inmueble, $request) {

            $contrato = Contrato::create([
                'inmueble_id'    => $inmueble->id,
                'propietario_id' => $request->user()->id,
                'inquilino_id'   => $data['inquilino_id'],
                'fecha_inicio'   => $data['fecha_inicio'],
                'fecha_fin'      => $data['fecha_fin'],
                'renta_mensual'  => $data['renta_mensual'],
                'deposito'       => $data['deposito'],
                'estatus'        => 'activo',
            ]);

            $inmueble->update(['estatus' => 'rentado']);

            return $contrato;
        });

        return response()->json($contrato, 201);
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
}
