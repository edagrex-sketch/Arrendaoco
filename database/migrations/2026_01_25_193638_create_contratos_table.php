<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contratos', function (Blueprint $table) {
            $table->id();

            // Relaciones principales
            $table->foreignId('inmueble_id')
                  ->constrained('inmuebles')
                  ->cascadeOnDelete();

            $table->foreignId('propietario_id')
                  ->constrained('usuarios')
                  ->cascadeOnDelete();

            $table->foreignId('inquilino_id')
                  ->constrained('usuarios')
                  ->cascadeOnDelete();

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
