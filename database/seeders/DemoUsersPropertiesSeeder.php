<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;
use App\Models\Inmueble;
use App\Models\ImagenInmueble;
use App\Models\Mascota;
use App\Models\Mobiliario;
use App\Models\ZonaComun;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoUsersPropertiesSeeder extends Seeder
{
    public function run()
    {
        $emails = [
            'hannigutilob@gmail.com',
            'renteriafatima2006@gmail.com',
            'estradaperezneyser@gmail.com',
            'ed.ag.rex@gmail.com'
        ];

        $users = [];
        foreach ($emails as $email) {
            $user = Usuario::updateOrCreate(
                ['email' => $email],
                [
                    'nombre' => explode('@', $email)[0],
                    'password' => Hash::make('password123'),
                    'estatus' => 'activo',
                ]
            );
            $users[] = $user;
            
            // Asegurar que tengan el rol de propietario si existe el sistema de roles
            $user->asignarRol('propietario');
        }

        $barrios = ['San Jose', 'Guadalupe', 'Centro', 'La Esperanza', 'Los Pinos', 'Linda Vista'];
        $tipos = ['Casa', 'Departamento', 'Cuarto'];
        
        // Imágenes realistas de Unsplash (Arquitectura e Interiores)
        $poolImagenes = [
            'https://images.unsplash.com/photo-1568605114967-8130f3a36994', // Casa moderna
            'https://images.unsplash.com/photo-1570129477492-45c003edd2be', // Casa clásica
            'https://images.unsplash.com/photo-1512917774080-9991f1c4c750', // Mansión
            'https://images.unsplash.com/photo-1600585154340-be6199f7a096', // Cocina
            'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9', // Casa blanca
            'https://images.unsplash.com/photo-1600607687940-4e7a6a3536a9', // Sala
            'https://images.unsplash.com/photo-1560448204-603b3fc33ddc', // Loft
            'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688', // Interior de apto
            'https://images.unsplash.com/photo-1484154218962-a197022b5858', // Cocina moderna
            'https://images.unsplash.com/photo-1493663284031-b7e3aefcae8e', // Sala minimalista
            'https://images.unsplash.com/photo-1536376074432-bf121770b440', // Terraza
            'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267', // Cuarto moderno
            'https://images.unsplash.com/photo-1513694203232-719a280e022f', // Baño
            'https://images.unsplash.com/photo-1505691938895-1758d7eaa511', // Recámara
            'https://images.unsplash.com/photo-1554995207-c18c203602cb', // Pasillo
            'https://images.unsplash.com/photo-1582268611958-ebfd161ef9cf', // Patio
            'https://images.unsplash.com/photo-1560184897-6a4a15993a46', // Fachada moderna
            'https://images.unsplash.com/photo-1560185127-6ed189bf02f4', // Sala comedor
            'https://images.unsplash.com/photo-1560185007-c5ca9d2c014d', // Baño elegante
            'https://images.unsplash.com/photo-1560185008-b033106af5c3', // Closet
            'https://images.unsplash.com/photo-1600566753190-17f0bbc242e0', // Comedor
            'https://images.unsplash.com/photo-1600566752355-35792bedcfea', // Vista exterior
            'https://images.unsplash.com/photo-1600210492486-724fe5c67fb0', // Escaleras
            'https://images.unsplash.com/photo-1600573472591-ee6b68d14c68', // Piscina
            'https://images.unsplash.com/photo-1600047509807-ba8f99d2cdde', // Balcón
        ];

        foreach ($users as $user) {
            for ($i = 1; $i <= 4; $i++) {
                $tipo = $tipos[array_rand($tipos)];
                $barrio = $barrios[array_rand($barrios)];
                $renta = ($tipo == 'Cuarto') ? rand(1500, 3000) : (($tipo == 'Departamento') ? rand(3500, 7000) : rand(8000, 15000));
                
                $largo = rand(5, 20);
                $ancho = rand(4, 15);
                $metros = $largo * $ancho;

                $inmueble = Inmueble::create([
                    'propietario_id' => $user->id,
                    'titulo' => "{$tipo} acogedor en Barrio {$barrio} - Unidad {$i}",
                    'descripcion' => "Excelente oportunidad de renta. Este {$tipo} cuenta con una ubicación privilegiada en el corazón de Barrio {$barrio}. Ideal para quienes buscan comodidad, seguridad y un ambiente tranquilo. La propiedad está en perfectas condiciones y lista para ser habitada inmediatamente. Cerca de comercios, transporte y escuelas de Ocosingo.",
                    'direccion' => "Calle " . Str::random(5) . " #" . rand(1, 200) . ", Barrio {$barrio}",
                    'ciudad' => 'Ocosingo',
                    'estado' => 'Chiapas',
                    'codigo_postal' => '29950',
                    'renta_mensual' => $renta,
                    'deposito' => $renta, // Generalmente un mes de renta
                    'estatus' => 'disponible',
                    'tipo' => $tipo,
                    'habitaciones' => rand(1, 4),
                    'banos' => rand(1, 2),
                    'medios_banos' => rand(0, 1),
                    'metros' => $metros,
                    'latitud' => 16.90 + (rand(-500, 500) / 10000),
                    'longitud' => -92.09 + (rand(-500, 500) / 10000),
                    'requiere_deposito' => true,
                    'tiene_cerradura_propia' => true,
                    'cantidad_llaves' => rand(1, 3),
                    'tiene_estacionamiento' => (bool)rand(0, 1),
                    'estado_mobiliario' => array_rand(array_flip(['amueblada', 'semiamueblada', 'no amueblada'])),
                    'momento_pago' => 'adelantado',
                    'dias_tolerancia' => 3,
                    'dias_preaviso' => 30,
                    'duracion_contrato_meses' => 12,
                    'permite_mascotas' => (bool)rand(0, 1),
                    'incluir_clausulas' => true,
                    'clausulas_extra' => "1. No ruidos molestos después de las 11 PM.\n2. El mantenimiento de áreas comunes está incluido.\n3. Prohibido subarrendar la propiedad.",
                    'banco' => 'BBVA',
                    'clabe_interbancaria' => '012' . rand(100000000000000, 999999999999999),
                    'registrado_desde' => 'seeder'
                ]);

                // Asignar 5 imágenes aleatorias pero únicas por inmueble si es posible
                $indices = array_rand($poolImagenes, 5);
                $primera = true;
                foreach ($indices as $idx) {
                    $imgUrl = $poolImagenes[$idx] . "?auto=format&fit=crop&w=800&q=80";
                    
                    ImagenInmueble::create([
                        'inmueble_id' => $inmueble->id,
                        'ruta_imagen' => $imgUrl
                    ]);

                    if ($primera) {
                        $inmueble->update(['imagen' => $imgUrl]);
                        $primera = false;
                    }
                }

                // Relaciones adicionales
                if ($inmueble->permite_mascotas) {
                    $mascotas = Mascota::inRandomOrder()->take(rand(1, 3))->pluck('id');
                    $inmueble->mascotas()->attach($mascotas);
                }

                $mobiliarios = Mobiliario::inRandomOrder()->take(rand(2, 5))->pluck('id');
                $inmueble->mobiliarios()->attach($mobiliarios);

                if ($tipo == 'Cuarto') {
                    $zonas = ZonaComun::inRandomOrder()->take(rand(1, 4))->pluck('id');
                    $inmueble->zonasComunes()->attach($zonas);
                }

                // Servicios
                $serviciosDisponibles = ['Agua', 'Luz', 'Internet', 'Gas', 'TV por Cable'];
                foreach ($serviciosDisponibles as $srv) {
                    $inmueble->servicios()->create([
                        'servicio' => $srv,
                        'paga' => rand(0, 1) ? 'arrendador' : 'inquilino'
                    ]);
                }
            }
        }
    }
}
