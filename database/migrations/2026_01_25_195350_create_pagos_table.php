<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('contrato_id')
                ->constrained('contratos')
                ->cascadeOnDelete();

            $table->unsignedTinyInteger('mes');   // 1–12
            $table->unsignedSmallInteger('anio'); // 2026, 2027, etc.

            $table->decimal('monto', 10, 2);

            $table->enum('estatus', [
                'pendiente',
                'pagado',
                'vencido'
            ])->default('pendiente');

            $table->timestamp('fecha_pago')->nullable();

            $table->timestamps();

            // 🔒 Evita pagos duplicados por mes
            $table->unique(['contrato_id', 'mes', 'anio']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
