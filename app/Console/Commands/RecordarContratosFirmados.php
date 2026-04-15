<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\Contrato;

/**
 * Artisan Command: contratos:recordar-firmado
 * ─────────────────────────────────────────────
 * Envía recordatorios por correo a los propietarios que tienen
 * contratos en estado 'pdf_descargado' sin haber subido el
 * escaneo firmado después de N días configurables.
 *
 * Uso:
 *   php artisan contratos:recordar-firmado
 *   php artisan contratos:recordar-firmado --dias=3
 *
 * Programar en Kernel.php (o en routes/console.php):
 *   Schedule::command('contratos:recordar-firmado')->dailyAt('09:00');
 */
class RecordarContratosFirmados extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'contratos:recordar-firmado
                            {--dias=2 : Días de espera antes de enviar el primer recordatorio}';

    /**
     * The console command description.
     */
    protected $description = 'Envía recordatorios a propietarios con contratos pdf_descargado pendientes de subir firmados';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $dias = (int) $this->option('dias');

        $this->info("🔎 Buscando contratos con PDF descargado hace más de {$dias} días...");

        // Buscar contratos en estado pdf_descargado cuyo PDF fue descargado
        // hace más de $dias días y que aún no tienen archivo firmado.
        $contratos = Contrato::where('estatus', 'pdf_descargado')
            ->whereNull('archivo_firmado')
            ->whereNotNull('pdf_descargado_at')
            ->where('pdf_descargado_at', '<=', now()->subDays($dias))
            ->with(['propietario', 'inquilino', 'inmueble'])
            ->get();

        if ($contratos->isEmpty()) {
            $this->info('✅ No hay contratos pendientes. Todo al día.');
            return Command::SUCCESS;
        }

        $this->info("📋 Se encontraron {$contratos->count()} contrato(s) pendientes.");

        $enviados  = 0;
        $fallidos  = 0;

        $this->withProgressBar($contratos, function (Contrato $contrato) use (&$enviados, &$fallidos) {
            try {
                $propietario = $contrato->propietario;

                if (!$propietario || !$propietario->email) {
                    $fallidos++;
                    return;
                }

                $enlaceSubir = route('contratos.subir-firmado', $contrato->id);
                $diasEsperando = (int) now()->diffInDays($contrato->pdf_descargado_at);
                $nombreInquilino = optional($contrato->inquilino)->nombre ?? 'El inquilino';
                $tituloInmueble  = optional($contrato->inmueble)->titulo  ?? 'su propiedad';

                Mail::send(
                    'emails.recordatorio_contrato_firmado',
                    compact('contrato', 'propietario', 'enlaceSubir', 'diasEsperando', 'nombreInquilino', 'tituloInmueble'),
                    function ($message) use ($propietario) {
                        $message->to($propietario->email, $propietario->nombre)
                                ->subject('⚠️ ArrendaOco — Tienes un contrato pendiente de formalizar');
                    }
                );

                $enviados++;
            } catch (\Exception $e) {
                $fallidos++;
                $this->newLine();
                $this->warn("  ⚠ Error al enviar al propietario #{$contrato->propietario_id}: {$e->getMessage()}");
            }
        });

        $this->newLine(2);
        $this->table(
            ['Métrica', 'Cantidad'],
            [
                ['Contratos encontrados', $contratos->count()],
                ['Recordatorios enviados', $enviados],
                ['Fallos / sin email',    $fallidos],
            ]
        );

        return Command::SUCCESS;
    }
}
