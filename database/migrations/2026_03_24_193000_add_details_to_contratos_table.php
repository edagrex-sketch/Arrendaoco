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
        Schema::table('contratos', function (Blueprint $table) {
            if (!Schema::hasColumn('contratos', 'firma_digital')) {
                $table->longText('firma_digital')->nullable()->after('estatus');
            }
            if (!Schema::hasColumn('contratos', 'plazo')) {
                $table->string('plazo')->nullable()->after('fecha_fin');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contratos', function (Blueprint $table) {
            $table->dropColumn(['firma_digital', 'plazo']);
        });
    }
};
