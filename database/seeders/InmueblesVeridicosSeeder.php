<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Usuario;

class InmueblesVeridicosSeeder extends Seeder
{
    public function run()
    {
        $admin = Usuario::where('email', 'admin@arrendaoco.com')->first();
        if (!$admin) {
            $admin = Usuario::first();
        }
        $adminId = $admin ? $admin->id : 1;

        $inmuebles = [
            [
                'titulo' => 'Residencia Moderna en Barrio Central',
                'direccion' => 'Calle Central Norte #45, Edificio Los Pinos',
                'ciudad' => 'Ocosingo',
                'estado' => 'Chiapas',
                'codigo_postal' => '29950',
                'descripcion' => 'Hermosa casa con acabados de lujo, amplia cocina integral, roof garden y estacionamiento para 2 autos. Excelente iluminación natural y seguridad 24/7.',
                'renta_mensual' => 8500,
                'deposito' => 8500,
                'habitaciones' => 3,
                'banos' => 2,
                'metros' => 180,
                'tipo' => 'Casa',
                'latitud' => 16.908581,
                'longitud' => -92.094592,
                'imagen' => '/storage/inmuebles/casa_moderna.png',
                'propietario_id' => $adminId,
                'estatus' => 'disponible',
            ],
            [
                'titulo' => 'Departamento para Estudiantes UTC',
                'direccion' => 'Av. Universidad #12, Frente a la UTC',
                'ciudad' => 'Ocosingo',
                'estado' => 'Chiapas',
                'codigo_postal' => '29950',
                'descripcion' => 'Departamento cómodo y funcional, ideal para estudiantes. Incluye servicios de agua e internet. A solo unos pasos del campus universitario.',
                'renta_mensual' => 3500,
                'deposito' => 2000,
                'habitaciones' => 1,
                'banos' => 1,
                'metros' => 45,
                'tipo' => 'Departamento',
                'latitud' => 16.892015,
                'longitud' => -92.110052,
                'imagen' => '/storage/inmuebles/depto_utc.png',
                'propietario_id' => $adminId,
                'estatus' => 'disponible',
            ],
            [
                'titulo' => 'Cuarto para Estudiantes cerca del Tec',
                'direccion' => 'Calle Reforma #45, Barrio Guadalupe',
                'ciudad' => 'Ocosingo',
                'estado' => 'Chiapas',
                'codigo_postal' => '29950',
                'descripcion' => 'Habitación privada amueblada, incluye cama individual, closet y escritorio. Servicios de agua, luz e internet incluidos.',
                'renta_mensual' => 1800,
                'deposito' => 1000,
                'habitaciones' => 1,
                'banos' => 1,
                'metros' => 15,
                'tipo' => 'Cuarto',
                'latitud' => 16.906813,
                'longitud' => -92.094056,
                'imagen' => '/storage/inmuebles/cuarto_estudiante.png',
                'propietario_id' => $adminId,
                'estatus' => 'disponible',
            ],
            [
                'titulo' => 'Casa Familiar con Amplio Jardín',
                'direccion' => 'Fraccionamiento La Esperanza, Calle Los Olivos #102',
                'ciudad' => 'Ocosingo',
                'estado' => 'Chiapas',
                'codigo_postal' => '29950',
                'descripcion' => 'Propiedad espaciosa con jardín perimetral, área de asador, 4 recámaras y estudio independiente. Zona muy tranquila y segura.',
                'renta_mensual' => 15000,
                'deposito' => 15000,
                'habitaciones' => 4,
                'banos' => 3,
                'metros' => 320,
                'tipo' => 'Casa',
                'latitud' => 16.915024,
                'longitud' => -92.105012,
                'imagen' => '/storage/inmuebles/casa_familiar.png',
                'propietario_id' => $adminId,
                'estatus' => 'disponible',
            ],
            [
                'titulo' => 'Loft Amueblado Estilo Industrial',
                'direccion' => 'Calle 2da Oriente Sur #88, Barrio San Jose',
                'ciudad' => 'Ocosingo',
                'estado' => 'Chiapas',
                'codigo_postal' => '29950',
                'descripcion' => 'Loft totalmente equipado con muebles modernos, SMART TV, refrigerador y microondas. Todo incluido en un ambiente contemporáneo.',
                'renta_mensual' => 5500,
                'deposito' => 5500,
                'habitaciones' => 1,
                'banos' => 1,
                'metros' => 55,
                'tipo' => 'Departamento',
                'latitud' => 16.903045,
                'longitud' => -92.088012,
                'imagen' => '/storage/inmuebles/estudio.png',
                'propietario_id' => $adminId,
                'estatus' => 'disponible',
            ],
            [
                'titulo' => 'Quinta Campestre con Vista a la Sierra',
                'direccion' => 'Carretera Ocosingo - Palenque KM 2.5, Rancho Alegre',
                'ciudad' => 'Ocosingo',
                'estado' => 'Chiapas',
                'codigo_postal' => '29950',
                'descripcion' => 'Escapa del ruido de la ciudad en esta hermosa quinta. Cuenta con árboles frutales, corredor amplio con hamacas y clima inmejorable.',
                'renta_mensual' => 6000,
                'deposito' => 6000,
                'habitaciones' => 2,
                'banos' => 2,
                'metros' => 800,
                'tipo' => 'Casa',
                'latitud' => 16.920056,
                'longitud' => -92.080034,
                'imagen' => '/storage/inmuebles/casa_rural.png',
                'propietario_id' => $adminId,
                'estatus' => 'disponible',
            ],
        ];

        foreach ($inmuebles as $inmueble) {
            DB::table('inmuebles')->insert(array_merge($inmueble, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
