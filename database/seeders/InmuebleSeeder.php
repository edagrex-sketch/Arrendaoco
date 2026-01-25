<?php

namespace Database\Seeders;

use App\Models\Inmueble;
use App\Models\Usuario;
use Illuminate\Database\Seeder;

class InmuebleSeeder extends Seeder
{
    public function run(): void
    {
        $juan = Usuario::where('email', 'juan@arrendaoco.com')->first();
        $maria = Usuario::where('email', 'maria@arrendaoco.com')->first();

        Inmueble::create([
            'propietario_id' => $juan->id,
            'titulo' => 'Departamento Centro',
            'descripcion' => 'Departamento amueblado en el centro',
            'direccion' => 'Calle 10 #123',
            'ciudad' => 'Mérida',
            'estado' => 'Yucatán',
            'codigo_postal' => '97000',
            'renta_mensual' => 8500,
            'deposito' => 8500,
            'estatus' => 'disponible',
        ]);

        Inmueble::create([
            'propietario_id' => $juan->id,
            'titulo' => 'Casa Norte',
            'descripcion' => 'Casa con patio amplio',
            'direccion' => 'Av. 60 #456',
            'ciudad' => 'Mérida',
            'estado' => 'Yucatán',
            'codigo_postal' => '97110',
            'renta_mensual' => 12000,
            'deposito' => 12000,
            'estatus' => 'rentado',
        ]);

        Inmueble::create([
            'propietario_id' => $maria->id,
            'titulo' => 'Departamento Playa',
            'descripcion' => 'Vista al mar',
            'direccion' => 'Malecón #89',
            'ciudad' => 'Progreso',
            'estado' => 'Yucatán',
            'codigo_postal' => '97320',
            'renta_mensual' => 15000,
            'deposito' => 15000,
            'estatus' => 'disponible',
        ]);
    }
}
