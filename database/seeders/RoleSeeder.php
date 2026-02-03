<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Role::create(['nombre' => 'admin', 'etiqueta' => 'Administrador']);
        \App\Models\Role::create(['nombre' => 'propietario', 'etiqueta' => 'Propietario']);
        \App\Models\Role::create(['nombre' => 'inquilino', 'etiqueta' => 'Inquilino']);
    }
}
