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
        for ($i = 1; $i <= 3; $i++) {
            $user = Usuario::create([
                'nombre' => "Propietario Real $i",
                'email' => "prop$i@arrendaoco.com",
                'password' => Hash::make('password123'),
                'es_admin' => false,
                'estatus' => 'activo',
            ]);
            $user->asignarRol('propietario');
        }

        // 3. Inquilinos (3)
        for ($i = 1; $i <= 3; $i++) {
            $user = Usuario::create([
                'nombre' => "Inquilino Real $i",
                'email' => "inq$i@arrendaoco.com",
                'password' => Hash::make('password123'),
                'es_admin' => false,
                'estatus' => 'activo',
            ]);
            $user->asignarRol('inquilino');
        }

        // 4. Inquilino + Propietario (2)
        for ($i = 1; $i <= 2; $i++) {
            $user = Usuario::create([
                'nombre' => "Doble Rol $i",
                'email' => "dual$i@arrendaoco.com",
                'password' => Hash::make('password123'),
                'es_admin' => false,
                'estatus' => 'activo',
            ]);
            $user->asignarRol('inquilino');
            $user->asignarRol('propietario');
        }
    }
}
