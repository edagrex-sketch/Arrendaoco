<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,      // 1. Roles base
            UsuarioSeeder::class,   // 2. Usuarios con roles
            InmuebleSeeder::class,  // 3. Propiedades para los usuarios
            ContratoSeeder::class,  // 4. Contratos y Pagos entre usuarios e inmuebles
            ResenaSeeder::class,    // 5. Feedback y reseñas
        ]);
    }
}
