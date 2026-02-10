<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inmueble;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;

class InmuebleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('imagenes_inmuebles')->truncate();
        Inmueble::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $propietarios = Usuario::whereHas('roles', function($q) {
            $q->where('nombre', 'propietario');
        })->get();

        $inmueblesData = [
            ['titulo' => 'Casa Familiar en San Sebastián', 'tipo' => 'Casa', 'precio' => 4500, 'habitaciones' => 3, 'banos' => 2, 'metros' => 120, 'barrio' => 'San Sebastián'],
            ['titulo' => 'Departamento Moderno en el Centro', 'tipo' => 'Departamento', 'precio' => 3200, 'habitaciones' => 2, 'banos' => 1, 'metros' => 65, 'barrio' => 'Centro'],
            ['titulo' => 'Cuarto Estudiantil cerca de la UNICH', 'tipo' => 'Cuarto', 'precio' => 1200, 'habitaciones' => 1, 'banos' => 1, 'metros' => 15, 'barrio' => 'San Juan'],
            ['titulo' => 'Loft Ejecutivo en San Jose', 'tipo' => 'Departamento', 'precio' => 5500, 'habitaciones' => 1, 'banos' => 1, 'metros' => 45, 'barrio' => 'San Jose'],
            ['titulo' => 'Casa con Jardín en Barrio Nuevo', 'tipo' => 'Casa', 'precio' => 6000, 'habitaciones' => 4, 'banos' => 3, 'metros' => 180, 'barrio' => 'Barrio Nuevo'],
            ['titulo' => 'Residencia Amplia en La Piedad', 'tipo' => 'Casa', 'precio' => 7500, 'habitaciones' => 5, 'banos' => 3, 'metros' => 220, 'barrio' => 'La Piedad'],
            ['titulo' => 'Departamento Amueblado en Los Pinos', 'tipo' => 'Departamento', 'precio' => 4000, 'habitaciones' => 2, 'banos' => 1, 'metros' => 70, 'barrio' => 'Los Pinos'],
            ['titulo' => 'Casa Duplex en Guadalupe', 'tipo' => 'Casa', 'precio' => 3800, 'habitaciones' => 3, 'banos' => 2, 'metros' => 95, 'barrio' => 'Guadalupe'],
            ['titulo' => 'Cuarto con Baño Propio en San Juan', 'tipo' => 'Cuarto', 'precio' => 1500, 'habitaciones' => 1, 'banos' => 1, 'metros' => 20, 'barrio' => 'San Juan'],
            ['titulo' => 'Casa Estilo Colonial en el Centro', 'tipo' => 'Casa', 'precio' => 8500, 'habitaciones' => 4, 'banos' => 3, 'metros' => 250, 'barrio' => 'Centro'],
            ['titulo' => 'Departamento con Vista en San Sebastián', 'tipo' => 'Departamento', 'precio' => 3500, 'habitaciones' => 2, 'banos' => 1, 'metros' => 60, 'barrio' => 'San Sebastián'],
            ['titulo' => 'Cuarto Económico en San Jose', 'tipo' => 'Cuarto', 'precio' => 1000, 'habitaciones' => 1, 'banos' => 1, 'metros' => 12, 'barrio' => 'San Jose'],
            ['titulo' => 'Casa Grande para Oficinas en el Centro', 'tipo' => 'Casa', 'precio' => 12000, 'habitaciones' => 6, 'banos' => 4, 'metros' => 350, 'barrio' => 'Centro'],
            ['titulo' => 'Departamento para Parejas en Barrio Nuevo', 'tipo' => 'Departamento', 'precio' => 2800, 'habitaciones' => 1, 'banos' => 1, 'metros' => 40, 'barrio' => 'Barrio Nuevo'],
            ['titulo' => 'Loft Minimalista en Los Pinos', 'tipo' => 'Departamento', 'precio' => 4800, 'habitaciones' => 1, 'banos' => 1, 'metros' => 50, 'barrio' => 'Los Pinos'],
            ['titulo' => 'Casa Tradicional en Guadalupe', 'tipo' => 'Casa', 'precio' => 3500, 'habitaciones' => 3, 'banos' => 1, 'metros' => 100, 'barrio' => 'Guadalupe'],
            ['titulo' => 'Cuarto Amueblado en La Piedad', 'tipo' => 'Cuarto', 'precio' => 1800, 'habitaciones' => 1, 'banos' => 1, 'metros' => 18, 'barrio' => 'La Piedad'],
            ['titulo' => 'Residencia de Lujo en San Jose', 'tipo' => 'Casa', 'precio' => 15000, 'habitaciones' => 6, 'banos' => 5, 'metros' => 400, 'barrio' => 'San Jose'],
            ['titulo' => 'Departamento Céntrico Av. Central', 'tipo' => 'Departamento', 'precio' => 3900, 'habitaciones' => 2, 'banos' => 1, 'metros' => 75, 'barrio' => 'Centro'],
            ['titulo' => 'Casa con Cochera en San Juan', 'tipo' => 'Casa', 'precio' => 5200, 'habitaciones' => 3, 'banos' => 2, 'metros' => 130, 'barrio' => 'San Juan'],
            ['titulo' => 'Cuarto Compartido en San Sebastián', 'tipo' => 'Cuarto', 'precio' => 900, 'habitaciones' => 1, 'banos' => 1, 'metros' => 25, 'barrio' => 'San Sebastián'],
            ['titulo' => 'Loft Industrial en Barrio Nuevo', 'tipo' => 'Departamento', 'precio' => 6200, 'habitaciones' => 1, 'banos' => 1, 'metros' => 55, 'barrio' => 'Barrio Nuevo'],
            ['titulo' => 'Casa Rural en las afueras', 'tipo' => 'Casa', 'precio' => 2500, 'habitaciones' => 2, 'banos' => 1, 'metros' => 150, 'barrio' => 'Afueras'],
            ['titulo' => 'Departamento Duplex en Los Pinos', 'tipo' => 'Departamento', 'precio' => 5800, 'habitaciones' => 3, 'banos' => 2, 'metros' => 110, 'barrio' => 'Los Pinos'],
            ['titulo' => 'Cuarto con Internet en San Jose', 'tipo' => 'Cuarto', 'precio' => 1600, 'habitaciones' => 1, 'banos' => 1, 'metros' => 16, 'barrio' => 'San Jose'],
            ['titulo' => 'Casa con Terraza en Guadalupe', 'tipo' => 'Casa', 'precio' => 4900, 'habitaciones' => 3, 'banos' => 2, 'metros' => 115, 'barrio' => 'Guadalupe'],
            ['titulo' => 'Departamento Studio en el Centro', 'tipo' => 'Departamento', 'precio' => 3100, 'habitaciones' => 1, 'banos' => 1, 'metros' => 35, 'barrio' => 'Centro'],
            ['titulo' => 'Casa Familiar en San Juan', 'tipo' => 'Casa', 'precio' => 4200, 'habitaciones' => 3, 'banos' => 2, 'metros' => 105, 'barrio' => 'San Juan'],
        ];

        $stockImages = [
            'https://images.unsplash.com/photo-1568605114967-8130f3a36994?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1570129477492-45c003edd2be?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1554995207-c18c20360a59?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1480074568708-e7b720bb3f09?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1518780664697-55e3ad937233?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1493809842364-78817add7ffb?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&w=800&q=80',
        ];

        foreach ($inmueblesData as $index => $data) {
            $propietario = $propietarios->random();
            
            $inmueble = Inmueble::create([
                'titulo' => $data['titulo'],
                'descripcion' => "Hermosa propiedad tipo {$data['tipo']} ubicada en el barrio {$data['barrio']} de Ocosingo. Cuenta con todos los servicios básicos, excelente iluminación y espacios bien distribuidos. Ideal para quienes buscan comodidad y seguridad.",
                'direccion' => "Calle Principal, Barrio {$data['barrio']}",
                'ciudad' => 'Ocosingo',
                'estado' => 'Chiapas',
                'codigo_postal' => '29950',
                'renta_mensual' => $data['precio'],
                'deposito' => $data['precio'],
                'estatus' => 'disponible',
                'tipo' => $data['tipo'],
                'habitaciones' => $data['habitaciones'],
                'banos' => $data['banos'],
                'metros' => $data['metros'],
                'propietario_id' => $propietario->id,
                'imagen' => $stockImages[array_rand($stockImages)],
            ]);

            // Generar 5 imágenes adicionales
            for ($i = 0; $i < 5; $i++) {
                DB::table('imagenes_inmuebles')->insert([
                    'inmueble_id' => $inmueble->id,
                    'ruta_imagen' => $stockImages[($index + $i) % count($stockImages)],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
