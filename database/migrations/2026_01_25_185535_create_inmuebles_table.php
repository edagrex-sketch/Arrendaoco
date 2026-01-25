<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inmuebles', function (Blueprint $table) {
            $table->id();

            // Relación con propietario (usuarios)
            $table->foreignId('propietario_id')
                  ->constrained('usuarios')
                  ->cascadeOnDelete();

            // Datos del inmueble
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->string('direccion');
            $table->string('ciudad');
            $table->string('estado');
            $table->string('codigo_postal', 10);

            // Información financiera
            $table->decimal('renta_mensual', 10, 2);
            $table->decimal('deposito', 10, 2)->nullable();

            // Estado del inmueble
            $table->enum('estatus', ['disponible', 'rentado', 'inactivo'])
                  ->default('disponible');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inmuebles');
    }
};
