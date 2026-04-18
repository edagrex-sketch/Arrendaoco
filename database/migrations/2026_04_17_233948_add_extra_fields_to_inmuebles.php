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
            $table->json('tipos_mascotas')->nullable();
            $table->json('servicios_incluidos')->nullable(); // agua, luz, etc.
            $table->json('pago_servicio')->nullable(); // quien paga que
            $table->string('clabe_interbancaria', 18)->nullable();
            $table->string('banco')->nullable();
            $table->double('largo')->nullable();
            $table->double('ancho')->nullable();
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
