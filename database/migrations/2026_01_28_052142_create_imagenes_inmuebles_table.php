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
        Schema::create('imagenes_inmuebles', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('inmueble_id');
            $table->string('ruta_imagen', 200);
            $table->timestamps();

            $table->foreign('inmueble_id')->references('id')->on('inmuebles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imagenes_inmuebles');
    }
};
