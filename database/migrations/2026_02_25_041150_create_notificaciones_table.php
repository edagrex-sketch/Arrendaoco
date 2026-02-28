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
            $row->id();
            $row->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $row->string('titulo');
            $row->text('mensaje');
            $row->string('tipo')->default('sistema'); // sistema, resena, pago, renta, etc.
            $row->boolean('leida')->default(false);
            $row->unsignedBigInteger('referencia_id')->nullable();
            $row->timestamps();
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
