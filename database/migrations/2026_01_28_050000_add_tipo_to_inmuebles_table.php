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
        Schema::table('inmuebles', function (Blueprint $table) {
            // Campos nuevos para que coincidan con el diseño
            $table->string('tipo')->after('id')->default('Casa'); // Casa, Depto...
            $table->integer('habitaciones')->nullable()->after('estado'); // Ej: 3
            $table->integer('banos')->nullable()->after('habitaciones'); // Ej: 2
            $table->decimal('metros', 8, 2)->nullable()->after('banos'); // Ej. 120.50
            $table->string('imagen')->nullable()->after('metros'); // Para la foto
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inmuebles', function (Blueprint $table) {
            $table->dropColumn(['tipo', 'habitaciones', 'banos', 'metros', 'imagen']);
        });
    }
};
