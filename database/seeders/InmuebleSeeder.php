<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Inmueble;

class InmuebleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 7 Departamentos
        Inmueble::factory()->count(7)->create([
            'tipo' => 'Departamento',
        ]);

        // 7 Cuartos
        Inmueble::factory()->count(7)->create([
            'tipo' => 'Cuarto',
        ]);

        // 6 Casas
        Inmueble::factory()->count(6)->create([
            'tipo' => 'Casa',
        ]);
        
        // Mensaje de confirmación en consola (opcional)
        $this->command->info('Se han creado 20 inmuebles de prueba con éxito.');
    }
}
