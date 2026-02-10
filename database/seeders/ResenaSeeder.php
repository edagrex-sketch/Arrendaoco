<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Resena;
use App\Models\Inmueble;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;

class ResenaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('resenas')->truncate();

        $inquilinos = Usuario::whereHas('roles', function($q) {
            $q->where('nombre', 'inquilino');
        })->get();

        $inmuebles = Inmueble::all();

        $comentarios = [
            '¡Excelente ubicación! El barrio es muy tranquilo y seguro.',
            'La casa está en perfectas condiciones, muy recomendada.',
            'El trato con el propietario fue muy cordial y profesional.',
            'Lugar muy iluminado y espacioso, superó mis expectativas.',
            'Todo muy limpio y bien mantenido. Los servicios funcionan perfecto.',
            'Ideal para estudiantes, muy cerca de transporte y comercios.',
            'Me encantó la terraza, tiene una vista increíble de Ocosingo.',
            'Un lugar acogedor y con todo lo necesario para vivir cómodo.',
            'El precio es muy justo por la calidad de la propiedad.',
            'Sin duda volvería a rentar aquí, una experiencia de 10.',
            'Muy satisfecho con la estancia, todo de acuerdo a las fotos.',
            'El internet vuela y la zona es muy céntrica.',
        ];

        foreach ($inmuebles as $inmueble) {
            // Candidatos que NO son el dueño de esta propiedad
            $candidatos = $inquilinos->reject(function($u) use ($inmueble) {
                return $u->id === $inmueble->propietario_id;
            });

            if ($candidatos->isEmpty()) continue;

            // 2 reseñas por inmueble
            for ($i = 0; $i < 2; $i++) {
                $inquilino = $candidatos->random();
                Resena::create([
                    'usuario_id' => $inquilino->id,
                    'inmueble_id' => $inmueble->id,
                    'puntuacion' => rand(4, 5),
                    'comentario' => $comentarios[array_rand($comentarios)],
                ]);
            }
        }
    }
}
