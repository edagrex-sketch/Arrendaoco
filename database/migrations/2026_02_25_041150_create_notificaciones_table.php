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
        Schema::create('notificaciones', function (Blueprint $row) {
            $row->increments('id');
            $row->unsignedInteger('usuario_id');
            $row->string('titulo', 150);
            $row->text('mensaje');
            $row->string('tipo', 50)->default('sistema'); // sistema, resena, pago, renta, etc.
            $row->boolean('leida')->default(false);
            $row->unsignedInteger('referencia_id')->nullable();
            $row->timestamps();

            $row->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
    }
};
