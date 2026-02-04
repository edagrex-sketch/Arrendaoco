<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Resena;
use App\Models\Inmueble;
use App\Models\Usuario;

class ResenaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $inquilino = Usuario::where('email', 'inquilino@test.com')->first();
        $inmuebles = Inmueble::where('estatus', 'disponible')->take(3)->get();

        if ($inquilino) {
            foreach ($inmuebles as $inmueble) {
                Resena::create([
                    'usuario_id' => $inquilino->id,
                    'inmueble_id' => $inmueble->id,
                    'puntuacion' => rand(4, 5),
                    'comentario' => 'Excelente lugar, muy cómodo y bien ubicado en Ocosingo.',
                ]);
            }
        }

        $this->command->info('Reseñas de prueba generadas.');
    }
}
