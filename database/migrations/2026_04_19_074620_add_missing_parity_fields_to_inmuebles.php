<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('inmuebles', function (Blueprint $table) {
            if (!Schema::hasColumn('inmuebles', 'requiere_deposito')) {
                $table->boolean('requiere_deposito')->default(false);
            }
            if (!Schema::hasColumn('inmuebles', 'tiene_cerradura_propia')) {
                $table->boolean('tiene_cerradura_propia')->default(false);
            }
            if (!Schema::hasColumn('inmuebles', 'cantidad_llaves')) {
                $table->integer('cantidad_llaves')->default(0);
            }
            if (!Schema::hasColumn('inmuebles', 'permite_mascotas')) {
                $table->boolean('permite_mascotas')->default(false);
            }
            if (!Schema::hasColumn('inmuebles', 'incluir_clausulas')) {
                $table->boolean('incluir_clausulas')->default(false);
            }
            if (!Schema::hasColumn('inmuebles', 'clausulas_extra')) {
                $table->text('clausulas_extra')->nullable();
            }
            if (!Schema::hasColumn('inmuebles', 'estado_mobiliario')) {
                $table->string('estado_mobiliario')->default('no amueblada');
            }
            if (!Schema::hasColumn('inmuebles', 'tiene_estacionamiento')) {
                $table->boolean('tiene_estacionamiento')->default(false);
            }
            if (!Schema::hasColumn('inmuebles', 'momento_pago')) {
                $table->string('momento_pago')->default('adelantado');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inmuebles', function (Blueprint $table) {
            $table->dropColumn([
                'requiere_deposito',
                'tiene_cerradura_propia',
                'cantidad_llaves',
                'permite_mascotas',
                'incluir_clausulas',
                'clausulas_extra',
                'estado_mobiliario',
                'tiene_estacionamiento',
                'momento_pago'
            ]);
        });
    }
};
