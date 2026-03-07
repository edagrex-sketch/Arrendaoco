<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contratos', function (Blueprint $table) {
            $table->increments('id');

            // Relaciones principales
            $table->unsignedInteger('inmueble_id');
            $table->unsignedInteger('propietario_id');
            $table->unsignedInteger('inquilino_id');

            $table->foreign('inmueble_id')->references('id')->on('inmuebles')->cascadeOnDelete();
            $table->foreign('propietario_id')->references('id')->on('usuarios')->cascadeOnDelete();
            $table->foreign('inquilino_id')->references('id')->on('usuarios')->cascadeOnDelete();

            // Información contractual
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();

            $table->decimal('renta_mensual', 10, 2);
            $table->decimal('deposito', 10, 2)->nullable();

            // Estado del contrato
            $table->enum('estatus', [
                'activo',
                'finalizado',
                'cancelado'
            ])->default('activo');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contratos');
    }
};
