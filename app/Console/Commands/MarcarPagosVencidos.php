<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pago;
use Carbon\Carbon;

class MarcarPagosVencidos extends Command
{
    protected $signature = 'pagos:marcar-vencidos';

    protected $description = 'Marca como vencidos los pagos pendientes cuyo mes ya pasó';

    public function handle()
    {
        $hoy = Carbon::now();

        $pagos = Pago::where('estatus', 'pendiente')
            ->where(function ($q) use ($hoy) {
                $q->where('anio', '<', $hoy->year)
                  ->orWhere(function ($q2) use ($hoy) {
                      $q2->where('anio', $hoy->year)
                         ->where('mes', '<', $hoy->month);
                  });
            })
            ->get();

        foreach ($pagos as $pago) {
            $pago->update([
                'estatus' => 'vencido',
            ]);
        }

        $this->info("Pagos vencidos actualizados: " . $pagos->count());
    }
}
