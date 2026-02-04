<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contrato;
use App\Models\Inmueble;
use App\Models\Usuario;
use App\Models\Pago;
use Carbon\Carbon;

class ContratoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $propietario = Usuario::where('email', 'propietario@test.com')->first();
        $inquilino = Usuario::where('email', 'inquilino@test.com')->first();
        $inmueble = Inmueble::where('propietario_id', $propietario->id)->where('estatus', 'rentado')->first();

        if ($propietario && $inquilino && $inmueble) {
            // 1. Crear un contrato activo
            $contrato = Contrato::create([
                'inmueble_id' => $inmueble->id,
                'propietario_id' => $propietario->id,
                'inquilino_id' => $inquilino->id,
                'fecha_inicio' => Carbon::now()->startOfMonth()->subMonths(2),
                'fecha_fin' => Carbon::now()->addMonths(10),
                'renta_mensual' => $inmueble->renta_mensual,
                'deposito' => $inmueble->deposito,
                'estatus' => 'activo',
            ]);

            // 2. Crear pagos para este contrato
            // Pago de hace 2 meses (Pagado)
            Pago::create([
                'contrato_id' => $contrato->id,
                'mes' => Carbon::now()->subMonths(2)->month,
                'anio' => Carbon::now()->subMonths(2)->year,
                'monto' => $contrato->renta_mensual,
                'estatus' => 'pagado',
                'fecha_pago' => Carbon::now()->subMonths(2)->startOfMonth()->addDays(2),
                'dias_atraso' => 0,
                'recargo' => 0,
                'total_con_recargo' => $contrato->renta_mensual,
            ]);

            // Pago del mes pasado (Pagado)
            Pago::create([
                'contrato_id' => $contrato->id,
                'mes' => Carbon::now()->subMonth()->month,
                'anio' => Carbon::now()->subMonth()->year,
                'monto' => $contrato->renta_mensual,
                'estatus' => 'pagado',
                'fecha_pago' => Carbon::now()->subMonth()->startOfMonth()->addDays(1),
                'dias_atraso' => 0,
                'recargo' => 0,
                'total_con_recargo' => $contrato->renta_mensual,
            ]);

            // Pago de este mes (Pendiente)
            Pago::create([
                'contrato_id' => $contrato->id,
                'mes' => Carbon::now()->month,
                'anio' => Carbon::now()->year,
                'monto' => $contrato->renta_mensual,
                'estatus' => 'pendiente',
                'fecha_pago' => null,
                'dias_atraso' => 0,
                'recargo' => 0,
                'total_con_recargo' => $contrato->renta_mensual,
            ]);
        }

        $this->command->info('Contratos y Pagos de prueba generados.');
    }
}
