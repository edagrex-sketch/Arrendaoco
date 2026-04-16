<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE contratos MODIFY COLUMN estatus ENUM('activo', 'finalizado', 'cancelado', 'pendiente') DEFAULT 'pendiente'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE contratos MODIFY COLUMN estatus ENUM('activo', 'finalizado', 'cancelado') DEFAULT 'activo'");
    }
};
