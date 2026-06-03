<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Agregar columna firma_propietario
        Schema::table('contratos', function (Blueprint $table) {
            if (!Schema::hasColumn('contratos', 'firma_propietario')) {
                $table->longText('firma_propietario')->nullable()->after('firma_digital');
            }
        });

        // Ampliar el ENUM de estatus para incluir 'pendiente_aprobacion' y 'rechazado'
        try {
            DB::statement("ALTER TABLE contratos MODIFY COLUMN estatus ENUM(
                'pendiente_aprobacion',
                'pendiente',
                'activo',
                'finalizado',
                'cancelado',
                'rechazado'
            ) DEFAULT 'pendiente_aprobacion'");
        } catch (\Throwable $e) {
        }
    }

    public function down(): void
    {
        Schema::table('contratos', function (Blueprint $table) {
            $table->dropColumn('firma_propietario');
        });

        try {
            DB::statement("ALTER TABLE contratos MODIFY COLUMN estatus ENUM('activo', 'finalizado', 'cancelado', 'pendiente') DEFAULT 'pendiente'");
        } catch (\Throwable $e) {
        }
    }
};
