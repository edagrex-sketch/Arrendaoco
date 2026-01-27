<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Contrato;
use App\Http\Controllers\Api\ContratoController;
use Illuminate\Http\Request;

class GenerarEstadosCuentaMensuales extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'estados-cuenta:generar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera y envía los estados de cuenta mensuales de contratos activos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generando estados de cuenta mensuales...');

        $contratos = Contrato::where('estatus', 'activo')->get();

        foreach ($contratos as $contrato) {
            try {
                // Simular request vacío (necesario para el controlador)
                $request = Request::create('/', 'GET');
                $request->setUserResolver(function () use ($contrato) {
                    return $contrato->propietario; // se usa el propietario como contexto
                });

                app(ContratoController::class)
                    ->estadoCuentaPdf($contrato, $request);

                $this->info("✔ Estado de cuenta generado para contrato {$contrato->id}");
            } catch (\Throwable $e) {
                $this->error("✖ Error en contrato {$contrato->id}: {$e->getMessage()}");
            }
        }

        $this->info('Proceso finalizado.');
    }
}
