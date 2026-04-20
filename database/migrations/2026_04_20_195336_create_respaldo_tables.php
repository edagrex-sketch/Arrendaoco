<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('configuracion_respaldos', function (Blueprint $table) {
            $table->id();
            $table->boolean('automatico')->default(false);
            $table->integer('frecuencia')->default(1); // En días: 1, 3, 5, 7, 15
            $table->timestamp('ultimo_respaldo_auto')->nullable();
            $table->timestamps();
        });

        Schema::create('respaldos_logs', function (Blueprint $table) {
            $table->id();
            $table->string('tipo')->default('manual'); // manual, automatico
            $table->string('nombre_archivo')->nullable();
            $table->string('ruta')->nullable();
            $table->string('tamano')->nullable();
            $table->string('estatus')->default('exitoso'); // exitoso, fallido
            $table->text('error')->nullable();
            $table->timestamps();
        });

        // Insertar configuración inicial
        DB::table('configuracion_respaldos')->insert([
            'automatico' => false,
            'frecuencia' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configuracion_respaldos');
        Schema::dropIfExists('respaldos_logs');
    }
};
