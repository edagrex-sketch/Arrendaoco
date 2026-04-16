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
        DB::statement("ALTER TABLE contratos MODIFY COLUMN estatus ENUM(
            'pendiente_aprobacion',
            'pendiente',
            'activo',
            'finalizado',
            'cancelado',
            'rechazado'
        ) DEFAULT 'pendiente_aprobacion'");
    }

    public function down(): void
    {
        Schema::table('contratos', function (Blueprint $table) {
            $table->dropColumn('firma_propietario');
        });

        DB::statement("ALTER TABLE contratos MODIFY COLUMN estatus ENUM('activo', 'finalizado', 'cancelado', 'pendiente') DEFAULT 'pendiente'");
    }
};
