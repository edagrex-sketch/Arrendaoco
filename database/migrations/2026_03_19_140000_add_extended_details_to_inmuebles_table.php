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
            $table->boolean('requiere_deposito')->default(false)->after('deposito');
            $table->boolean('tiene_cerradura')->default(false)->after('requiere_deposito');
            $table->integer('cantidad_llaves')->default(0)->after('tiene_cerradura');
            $table->boolean('permite_mascotas')->default(false)->after('cantidad_llaves');
            $table->json('tipos_mascotas')->nullable()->after('permite_mascotas');
            $table->json('servicios_incluidos')->nullable()->after('tipos_mascotas');
            $table->json('pago_servicio')->nullable()->after('servicios_incluidos');
            $table->boolean('incluir_clausulas')->default(false)->after('pago_servicio');
            $table->text('clausulas_extra')->nullable()->after('incluir_clausulas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inmuebles', function (Blueprint $table) {
            $table->dropColumn([
                'requiere_deposito',
                'tiene_cerradura',
                'cantidad_llaves',
                'permite_mascotas',
                'tipos_mascotas',
                'servicios_incluidos',
                'pago_servicio',
                'incluir_clausulas',
                'clausulas_extra'
            ]);
        });
    }
};
