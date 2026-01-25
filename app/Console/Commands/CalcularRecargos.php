<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pago;
use Carbon\Carbon;

class CalcularRecargos extends Command
{
    protected $signature = 'pagos:calcular-recargos';
    protected $description = 'Calcula recargos automáticos en pagos vencidos';

    public function handle()
    {
        $hoy = Carbon::today();

        $pagos = Pago::where('estatus', 'pendiente')->get();

        foreach ($pagos as $pago) {

            $fechaVencimiento = Carbon::create(
                $pago->anio,
                $pago->mes,
                5
            );

            if ($hoy->greaterThan($fechaVencimiento)) {

                $diasAtraso = $fechaVencimiento->diffInDays($hoy);

                $recargo = ($pago->monto * 0.02 / 30) * $diasAtraso;

                $pago->update([
                    'estatus' => 'vencido',
                    'dias_atraso' => $diasAtraso,
                    'recargo' => round($recargo, 2),
                    'total_con_recargo' => round($pago->monto + $recargo, 2),
                ]);
            }
        }

        $this->info('Recargos calculados correctamente');
    }
}
