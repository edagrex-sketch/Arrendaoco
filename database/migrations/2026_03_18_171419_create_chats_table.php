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
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('usuario_1');
            $table->unsignedInteger('usuario_2');
            $table->unsignedInteger('inmueble_id')->nullable();
            
            $table->string('last_message')->nullable();
            $table->timestamp('last_message_at')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->foreign('usuario_1')->references('id')->on('usuarios')->onDelete('cascade');
            $table->foreign('usuario_2')->references('id')->on('usuarios')->onDelete('cascade');
            $table->foreign('inmueble_id')->references('id')->on('inmuebles')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};
