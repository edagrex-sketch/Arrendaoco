<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('estados_cuenta', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('contrato_id');
            $table->integer('mes');
            $table->integer('anio');
            $table->string('ruta_pdf', 200);
            $table->unsignedInteger('generado_por');

            $table->foreign('contrato_id')->references('id')->on('contratos')->cascadeOnDelete();
            $table->foreign('generado_por')->references('id')->on('usuarios');
            $table->timestamps();

            $table->unique(['contrato_id', 'mes', 'anio']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estados_cuenta');
    }
};
