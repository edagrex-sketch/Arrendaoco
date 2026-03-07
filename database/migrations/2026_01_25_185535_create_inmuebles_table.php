<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inmuebles', function (Blueprint $table) {
            $table->increments('id');

            // Relación con propietario (usuarios)
            $table->unsignedInteger('propietario_id');
            $table->foreign('propietario_id')->references('id')->on('usuarios')->cascadeOnDelete();

            // Datos del inmueble
            $table->string('titulo', 150);
            $table->text('descripcion')->nullable();
            $table->string('direccion', 200);
            $table->string('ciudad', 100);
            $table->string('estado', 100);
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
