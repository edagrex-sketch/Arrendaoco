<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContratoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar para evitar errores de llaves foraneas
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('pagos')->truncate();
        DB::table('contratos')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        // No creamos contratos automáticos por ahora para mantener 
        // las 28 publicaciones limpias y disponibles según el requerimiento.
    }
}
