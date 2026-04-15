<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\Inmueble;
use App\Models\Contrato;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * ContratoFisicoController
 * ─────────────────────────────────────────────────────────────────
 * Gestiona el nuevo flujo de arrendamiento sin firma digital:
 *
 *   1. Inquilino presiona "Ver Contrato" en show.blade.php
 *   2. Se crea (o recupera) un Contrato en estado 'disponible'
 *   3. Inquilino descarga el PDF → timestamp guardado → estado 'pdf_descargado'
 *   4. Propietario sube el escaneo firmado → estado 'activo' → inmueble = 'rentado'
 */
class ContratoFisicoController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | 1. Ver Contrato (Inquilino)
    |--------------------------------------------------------------------------
    | Muestra el precontrato al inquilino. Si no existe un contrato 'disponible'
    | para este inmueble y este inquilino, lo crea en ese estado.
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

        // Buscar contrato existente en estado 'disponible' para este inquilino+inmueble
        $contrato = Contrato::where('inmueble_id', $inmueble->id)
            ->where('inquilino_id', Auth::id())
            ->whereIn('estatus', ['disponible', 'pdf_descargado'])
            ->latest()
            ->first();

        // Si no existe, crear un borrador en estado 'disponible'
        if (!$contrato) {
            // Calcular fecha_fin a partir de la duración definida por el propietario
            $duracionMeses = $inmueble->duracion_contrato_meses ?? 12;
            $fechaInicio   = now()->toDateString();
            $fechaFin      = now()->addMonths($duracionMeses)->toDateString();
            $plazo         = $duracionMeses >= 12
                ? floor($duracionMeses / 12) . ' año' . (floor($duracionMeses / 12) > 1 ? 's' : '')
                : $duracionMeses . ' meses';

            $contrato = Contrato::create([
                'inmueble_id'    => $inmueble->id,
                'propietario_id' => $inmueble->propietario_id,
                'inquilino_id'   => Auth::id(),
                'fecha_inicio'   => $fechaInicio,
                'fecha_fin'      => $fechaFin,
                'plazo'          => $plazo,
                'renta_mensual'  => $inmueble->renta_mensual,
                'deposito'       => $inmueble->deposito ?? $inmueble->renta_mensual,
                'estatus'        => 'disponible',
            ]);
        }

        $contrato->load('inquilino');

        return view('inmuebles.ver_contrato', compact('inmueble', 'contrato'));
    }

    /*
    |--------------------------------------------------------------------------
    | 2. Registrar Descarga + Generar PDF (Inquilino o Propietario)
    |--------------------------------------------------------------------------
    | Guarda el timestamp de la primera descarga y devuelve el PDF.
    */
    public function registrarDescarga(Contrato $contrato): RedirectResponse|\Symfony\Component\HttpFoundation\Response
    {
        // Solo las partes involucradas pueden descargar
        abort_unless(
            in_array(Auth::id(), [$contrato->inquilino_id, $contrato->propietario_id]),
            403,
            'No tienes permiso para descargar este contrato.'
        );

        DB::transaction(function () use ($contrato) {
            // Solo registrar el timestamp la primera vez
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
    | 3. Formulario de Subida de Contrato Firmado (Propietario)
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
    | 4. Procesar Subida del Contrato Firmado (Propietario)
    |--------------------------------------------------------------------------
    | Activa el contrato y marca el inmueble como rentado.
    */
    public function subirFirmado(Request $request, Contrato $contrato): RedirectResponse
    {
        abort_unless($contrato->propietario_id === Auth::id(), 403);

        $request->validate([
            'archivo_firmado' => [
                'required',
                'file',
                'mimes:pdf,jpg,jpeg,png,webp',
                'max:10240', // 10 MB máximo
            ],
        ], [
            'archivo_firmado.required' => 'Debes subir el archivo del contrato firmado.',
            'archivo_firmado.mimes'    => 'El archivo debe ser PDF, JPG, PNG o WebP.',
            'archivo_firmado.max'      => 'El archivo no debe superar los 10 MB.',
        ]);

        DB::transaction(function () use ($request, $contrato) {
            // Guardar el archivo en disco
            $path = $request->file('archivo_firmado')
                ->store('contratos_firmados', 'public');

            // Activar el contrato
            $contrato->update([
                'archivo_firmado'  => $path,
                'archivo_subido_at' => now(),
                'estatus'           => 'activo',
            ]);

            // Marcar el inmueble como rentado
            $contrato->inmueble->update(['estatus' => 'rentado']);
        });

        // Notificar al inquilino (silencioso si falla)
        try {
            $contrato->load('inmueble', 'inquilino');
            // Mail::to(optional($contrato->inquilino)->email)->send(new ContratoActivadoMail($contrato));
        } catch (\Exception $e) { /* silencioso */ }

        return redirect()->route('inmuebles.index')
            ->with('success', '✅ ¡Contrato activado! El arrendamiento está ahora vigente y el inmueble fue marcado como rentado.');
    }
}
