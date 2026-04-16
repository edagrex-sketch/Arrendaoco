<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Fase 1 — ArrendaOco Flujo Físico
 * Agrega la duración del contrato al inmueble.
 * El propietario define esto al publicar/editar su propiedad.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inmuebles', function (Blueprint $table) {
            if (!Schema::hasColumn('inmuebles', 'duracion_contrato_meses')) {
                // Duración numérica — el propietario elige cuántos meses/años
                $table->unsignedSmallInteger('duracion_contrato_meses')
                      ->default(12)
                      ->after('dias_preaviso')
                      ->comment('Duración del contrato definida por el propietario (en meses)');
            }
        });
    }

    public function down(): void
    {
        Schema::table('inmuebles', function (Blueprint $table) {
            if (Schema::hasColumn('inmuebles', 'duracion_contrato_meses')) {
                $table->dropColumn('duracion_contrato_meses');
            }
        });
    }
};
