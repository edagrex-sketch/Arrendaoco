<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('contratos')) {
            return;
        }
        try {
            DB::statement("ALTER TABLE contratos MODIFY COLUMN estatus ENUM('activo', 'finalizado', 'cancelado', 'pendiente') DEFAULT 'pendiente'");
        } catch (\Throwable $e) {
            // La migración 2026_03_25_200000 y 2026_04_15_000002 ya actualizan este ENUM
        }
    }

    public function down(): void
    {
        try {
            DB::statement("ALTER TABLE contratos MODIFY COLUMN estatus ENUM('activo', 'finalizado', 'cancelado') DEFAULT 'activo'");
        } catch (\Throwable $e) {
        }
    }
};
