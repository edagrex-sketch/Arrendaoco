<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('estados_cuenta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contrato_id')->constrained('contratos')->cascadeOnDelete();
            $table->integer('mes');
            $table->integer('anio');
            $table->string('ruta_pdf');
            $table->foreignId('generado_por')->constrained('usuarios');
            $table->timestamps();

            $table->unique(['contrato_id', 'mes', 'anio']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estados_cuenta');
    }
};
