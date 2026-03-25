<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inmuebles', function (Blueprint $table) {
            if (!Schema::hasColumn('inmuebles', 'estado_mobiliario')) {
                $table->enum('estado_mobiliario', ['amueblada', 'semiamueblada', 'no amueblada'])->default('no amueblada')->after('tipo');
            }
            if (!Schema::hasColumn('inmuebles', 'tiene_cerradura_propia')) {
                $table->boolean('tiene_cerradura_propia')->default(false)->after('requiere_deposito');
            }
            if (!Schema::hasColumn('inmuebles', 'cantidad_llaves')) {
                $table->integer('cantidad_llaves')->default(0)->after('tiene_cerradura_propia');
            }
            if (!Schema::hasColumn('inmuebles', 'tiene_estacionamiento')) {
                $table->boolean('tiene_estacionamiento')->default(false)->after('cantidad_llaves');
            }
            if (!Schema::hasColumn('inmuebles', 'momento_pago')) {
                $table->enum('momento_pago', ['adelantado', 'vencido'])->default('adelantado')->after('renta_mensual');
            }
            if (!Schema::hasColumn('inmuebles', 'dias_tolerancia')) {
                $table->integer('dias_tolerancia')->default(0)->after('momento_pago');
            }
            if (!Schema::hasColumn('inmuebles', 'dias_preaviso')) {
                $table->integer('dias_preaviso')->default(30)->after('dias_tolerancia');
            }
        });
    }

    public function down(): void
    {
        Schema::table('inmuebles', function (Blueprint $table) {
            $columns = [
                'estado_mobiliario',
                'tiene_cerradura_propia',
                'tiene_estacionamiento',
                'momento_pago',
                'dias_tolerancia',
                'dias_preaviso'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('inmuebles', $column)) {
                    $table->dropColumn($column);
                }
            }
            // we leave cantidad_llaves if we are not sure it was dropped or not, but let's drop it optionally
            if (Schema::hasColumn('inmuebles', 'cantidad_llaves')) {
                $table->dropColumn('cantidad_llaves');
            }
        });
    }
};
