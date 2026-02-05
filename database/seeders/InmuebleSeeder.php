<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inmueble;
use App\Models\Usuario;

class InmuebleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Usuario::where('email', 'admin@arrendaoco.com')->first();
        $propietario = Usuario::where('email', 'propietario@test.com')->first();
        $otroPropietario = Usuario::where('email', 'pedro@test.com')->first();

        if (!$propietario) $propietario = Usuario::first();
        if (!$otroPropietario) $otroPropietario = Usuario::first();
        if (!$admin) $admin = Usuario::first();

        // Propiedades de Carlos Arrendador (Landlord Principal)
        Inmueble::create([
            'titulo' => 'Departamento Céntrico Ocosingo',
            'direccion' => 'Calle Central Norte #10',
            'ciudad' => 'Ocosingo',
            'estado' => 'Chiapas',
            'codigo_postal' => '29950',
            'descripcion' => 'Excelente departamento con todos los servicios incluidos cerca del parque central.',
            'renta_mensual' => 3500,
            'deposito' => 2000,
            'habitaciones' => 2,
            'banos' => 1,
            'metros' => 65,
            'tipo' => 'Departamento',
            'latitud' => 16.906852,
            'longitud' => -92.094123,
            'imagen' => null,
            'propietario_id' => $propietario->id,
            'estatus' => 'disponible',
        ]);

        Inmueble::create([
            'titulo' => 'Casa Amplia San Jose',
            'direccion' => 'Barrio San Jose, Av. Las Palmitas',
            'ciudad' => 'Ocosingo',
            'estado' => 'Chiapas',
            'codigo_postal' => '29950',
            'descripcion' => 'Casa familiar con amplio patio y estacionamiento para 2 vehículos.',
            'renta_mensual' => 6000,
            'deposito' => 6000,
            'habitaciones' => 3,
            'banos' => 2,
            'metros' => 120,
            'tipo' => 'Casa',
            'latitud' => 16.903012,
            'longitud' => -92.088056,
            'imagen' => null,
            'propietario_id' => $propietario->id,
            'estatus' => 'disponible',
        ]);

        // Propiedades de Pedro Garcia
        Inmueble::create([
            'titulo' => 'Cuarto Económico Estudiantes',
            'direccion' => 'Av. Universidad Progresiva',
            'ciudad' => 'Ocosingo',
            'estado' => 'Chiapas',
            'codigo_postal' => '29950',
            'descripcion' => 'Cuarto individual con baño compartido e internet de alta velocidad.',
            'renta_mensual' => 1500,
            'deposito' => 500,
            'habitaciones' => 1,
            'banos' => 1,
            'metros' => 15,
            'tipo' => 'Cuarto',
            'latitud' => 16.892045,
            'longitud' => -92.110012,
            'imagen' => null,
            'propietario_id' => $otroPropietario->id,
            'estatus' => 'disponible',
        ]);

        // Propiedades del Admin (Para tener volumen) - Usarán las coords aleatorias del Factory
        Inmueble::factory()->count(10)->create([
            'propietario_id' => $admin->id,
        ]);

        $this->command->info('Inmuebles diversificados con ubicaciones reales en Ocosingo creados.');
    }
}
