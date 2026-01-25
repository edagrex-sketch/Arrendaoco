<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        Usuario::create([
            'nombre' => 'Administrador',
            'email' => 'admin@arrendaoco.com',
            'password' => Hash::make('Admin123!'),
            'telefono' => null,
            'es_admin' => true,
            'estatus' => 'activo',
        ]);

        // Propietario 1
        Usuario::create([
            'nombre' => 'Juan Pérez',
            'email' => 'juan@arrendaoco.com',
            'password' => Hash::make('Password123'),
            'telefono' => '9991234567',
            'es_admin' => false,
            'estatus' => 'activo',
        ]);

        // Propietario 2
        Usuario::create([
            'nombre' => 'María López',
            'email' => 'maria@arrendaoco.com',
            'password' => Hash::make('Password123'),
            'telefono' => '9999876543',
            'es_admin' => false,
            'estatus' => 'activo',
        ]);
    }
}
