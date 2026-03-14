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
