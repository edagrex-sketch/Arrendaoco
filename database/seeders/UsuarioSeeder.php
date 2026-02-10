<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Administrador (Control total)
        $admin = Usuario::create([
            'nombre' => 'Admin ArrendaOco',
            'email' => '',
            'password' => Hash::make('Admin123!'),
            'es_admin' => true,
            'estatus' => 'activo',
        ]);
        $admin->asignarRol('admin');

        // 2. Propietario con Inmuebles (Landlord)
        $propietario = Usuario::create([
            'nombre' => 'Carlos Arrendador',
            'email' => 'propietario@test.com',
            'password' => Hash::make('Password123'),
            'es_admin' => false,
            'estatus' => 'activo',
        ]);
        $propietario->asignarRol('propietario');

        // 3. Inquilino con Contrato Activo (Tenant)
        $inquilino = Usuario::create([
            'nombre' => 'Ana Inquilina',
            'email' => 'inquilino@test.com',
            'password' => Hash::make('Password123'),
            'es_admin' => false,
            'estatus' => 'activo',
        ]);
        $inquilino->asignarRol('inquilino');

        // 4. Usuario Nuevo (Sin roles extra, para probar el botón de "¡Quiero Publicar!")
        $nuevo = Usuario::create([
            'nombre' => 'Usuario Nuevo',
            'email' => 'nuevo@test.com',
            'password' => Hash::make(''),
            'es_admin' => false,
            'estatus' => 'activo',
        ]);
        $nuevo->asignarRol('inquilino'); // Por defecto todos son inquilinos/usuarios base

        // 5. Usuario Adicional (Cualquier otro rol)
        $otro = Usuario::create([
            'nombre' => 'Pedro Garcia',
            'email' => 'pedro@test.com',
            'password' => Hash::make('Password123'),
            'es_admin' => false,
            'estatus' => 'activo',
        ]);
        $otro->asignarRol('propietario');
    }
}
