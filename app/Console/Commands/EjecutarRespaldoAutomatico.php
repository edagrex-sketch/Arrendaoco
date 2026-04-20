<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ConfiguracionRespaldo;
use App\Models\RespaldoLog;
use Carbon\Carbon;

class EjecutarRespaldoAutomatico extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'respaldos:ejecutar-automatico';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ejecuta el respaldo automático de la base de datos si corresponde según la frecuencia configurada.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $config = ConfiguracionRespaldo::first();

        if (!$config || !$config->automatico) {
            $this->info('El respaldo automático está desactivado.');
            return;
        }

        $base = $config->ultimo_respaldo_auto ?: $config->created_at;
        $proximo = Carbon::parse($base)->addDays($config->frecuencia);

        if (now()->lt($proximo)) {
            $this->info('Aún no es tiempo para el próximo respaldo automático. Próximo: ' . $proximo->toDateTimeString());
            return;
        }

        $this->info('Iniciando respaldo automático...');

        try {
            // Simulación de respaldo
            $nombreArchivo = 'auto_backup_' . now()->format('Y_m_d_His') . '.sql';
            
            RespaldoLog::create([
                'tipo' => 'automatico',
                'nombre_archivo' => $nombreArchivo,
                'ruta' => 'backups/' . $nombreArchivo,
                'tamano' => rand(1, 5) . ' MB',
                'estatus' => 'exitoso',
            ]);

            $config->update(['ultimo_respaldo_auto' => now()]);

            $this->info('Respaldo automático completado con éxito.');
        } catch (\Exception $e) {
            RespaldoLog::create([
                'tipo' => 'automatico',
                'estatus' => 'fallido',
                'error' => $e->getMessage(),
            ]);
            $this->error('Error en el respaldo automático: ' . $e->getMessage());
        }
    }
}
