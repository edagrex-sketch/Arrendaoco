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
        Schema::table('mensajes', function (Blueprint $table) {
            if (!Schema::hasColumn('mensajes', 'tipo')) {
                $table->string('tipo')->default('texto')->after('contenido');
            }
            if (!Schema::hasColumn('mensajes', 'metadata')) {
                $table->json('metadata')->nullable()->after('tipo');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mensajes', function (Blueprint $table) {
            $table->dropColumn(['tipo', 'metadata']);
        });
    }
};
