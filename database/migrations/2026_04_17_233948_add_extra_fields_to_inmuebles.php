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
            if (!Schema::hasColumn('inmuebles', 'tipos_mascotas')) {
                $table->json('tipos_mascotas')->nullable();
            }
            if (!Schema::hasColumn('inmuebles', 'servicios_incluidos')) {
                $table->json('servicios_incluidos')->nullable(); // agua, luz, etc.
            }
            if (!Schema::hasColumn('inmuebles', 'pago_servicio')) {
                $table->json('pago_servicio')->nullable(); // quien paga que
            }
            if (!Schema::hasColumn('inmuebles', 'clabe_interbancaria')) {
                $table->string('clabe_interbancaria', 18)->nullable();
            }
            if (!Schema::hasColumn('inmuebles', 'banco')) {
                $table->string('banco')->nullable();
            }
            if (!Schema::hasColumn('inmuebles', 'largo')) {
                $table->double('largo')->nullable();
            }
            if (!Schema::hasColumn('inmuebles', 'ancho')) {
                $table->double('ancho')->nullable();
            }
            $table->integer('dias_tolerancia')->default(3)->change(); // ensure matches web
            $table->integer('dias_preaviso')->default(30)->change(); // ensure matches web
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inmuebles', function (Blueprint $table) {
            $table->dropColumn([
                'tipos_mascotas',
                'servicios_incluidos',
                'pago_servicio',
                'clabe_interbancaria',
                'banco',
                'largo',
                'ancho'
            ]);
        });
    }
};
