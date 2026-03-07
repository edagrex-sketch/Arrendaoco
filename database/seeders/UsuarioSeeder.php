<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar tablas relacionadas
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('role_usuario')->truncate();
        Usuario::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. Administrador (1)
        $admin = Usuario::create([
            'nombre' => 'Admin ArrendaOco',
            'email' => 'admin@arrendaoco.com',
            'password' => Hash::make('admin123'),
            'es_admin' => true,
            'estatus' => 'activo',
        ]);
        $admin->asignarRol('admin');

        // 2. Propietarios (3)
        $nombresPropietarios = ['Juan Carlos Pérez', 'María Elena García', 'Roberto Hernández'];
        foreach ($nombresPropietarios as $i => $nombre) {
            $user = Usuario::create([
                'nombre' => $nombre,
                'email' => "propietario".($i+1)."@arrendaoco.com",
                'password' => Hash::make('password123'),
                'es_admin' => false,
                'estatus' => 'activo',
            ]);
            $user->asignarRol('propietario');
        }

        // 3. Inquilinos (3)
        $nombresInquilinos = ['Ana Lucía Velasco', 'Fernando Ruiz', 'Guadalupe Jiménez'];
        foreach ($nombresInquilinos as $i => $nombre) {
            $user = Usuario::create([
                'nombre' => $nombre,
                'email' => "inquilino".($i+1)."@arrendaoco.com",
                'password' => Hash::make('password123'),
                'es_admin' => false,
                'estatus' => 'activo',
            ]);
            $user->asignarRol('inquilino');
        }

        // 4. Inquilino + Propietario (2)
        $nombresDuo = ['Carlos Méndez', 'Patricia Solis'];
        foreach ($nombresDuo as $i => $nombre) {
            $user = Usuario::create([
                'nombre' => $nombre,
                'email' => "dual".($i+1)."@arrendaoco.com",
                'password' => Hash::make('password123'),
                'es_admin' => false,
                'estatus' => 'activo',
            ]);
            $user->asignarRol('inquilino');
            $user->asignarRol('propietario');
        }
    }
}
