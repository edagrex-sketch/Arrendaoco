<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Fase 1 — ArrendaOco Flujo Físico
 * Agrega campos de seguimiento del contrato físico y actualiza el ENUM de estatus.
 * Los contratos existentes quedan marcados como 'cancelado' para limpiar el estado.
 */
return new class extends Migration
{
    public function up(): void
    {
        // ─── 1. Nuevos campos de seguimiento físico ──────────────────────────
        Schema::table('contratos', function (Blueprint $table) {
            if (!Schema::hasColumn('contratos', 'archivo_firmado')) {
                // Ruta del escaneo/foto del contrato firmado físicamente (subido por el propietario)
                $table->string('archivo_firmado')
                      ->nullable()
                      ->after('deposito')
                      ->comment('Ruta en storage del contrato firmado escaneado');
            }

            if (!Schema::hasColumn('contratos', 'pdf_descargado_at')) {
                // Primera vez que se descargó el PDF (para activar recordatorios)
                $table->timestamp('pdf_descargado_at')
                      ->nullable()
                      ->after('archivo_firmado')
                      ->comment('Timestamp de la primera descarga del PDF');
            }

            if (!Schema::hasColumn('contratos', 'archivo_subido_at')) {
                // Cuando el propietario sube la evidencia física escaneada
                $table->timestamp('archivo_subido_at')
                      ->nullable()
                      ->after('pdf_descargado_at')
                      ->comment('Timestamp de cuando el propietario subió el archivo firmado');
            }
        });

        // ─── 2. Cancelar todos los contratos existentes (proyecto en desarrollo) ──
        // Esto limpia el estado sin TRUNCATE para respetar FK constraints
        DB::table('contratos')->update(['estatus' => 'cancelado']);

        // ─── 3. Actualizar el ENUM con los nuevos estados del flujo físico ──────
        // Orden de vida de un contrato:
        //   disponible → pdf_descargado → activo → finalizado
        //                                        ↘ cancelado | rechazado
        DB::statement("ALTER TABLE contratos MODIFY COLUMN estatus ENUM(
            'disponible',           -- Contrato listo, el inquilino puede verlo y descargarlo
            'pdf_descargado',       -- Al menos una parte descargó el PDF, esperando firma física
            'activo',               -- Propietario subió el escaneo firmado → arrendamiento vigente
            'finalizado',           -- Contrato terminado correctamente al vencimiento
            'cancelado',            -- Cancelado por cualquiera de las partes
            'rechazado',            -- Propietario rechazó explícitamente al inquilino
            'pendiente_aprobacion', -- LEGADO: contratos del flujo anterior (no se usará para nuevos)
            'pendiente',            -- LEGADO: compatibilidad con registros viejos
            'borrador'              -- Reservado para uso futuro (pre-publicación)
        ) DEFAULT 'disponible'");
    }

    public function down(): void
    {
        // Revertir el ENUM al estado original
        DB::statement("ALTER TABLE contratos MODIFY COLUMN estatus ENUM(
            'pendiente_aprobacion',
            'pendiente',
            'activo',
            'finalizado',
            'cancelado',
            'rechazado'
        ) DEFAULT 'pendiente_aprobacion'");

        Schema::table('contratos', function (Blueprint $table) {
            foreach (['archivo_firmado', 'pdf_descargado_at', 'archivo_subido_at'] as $col) {
                if (Schema::hasColumn('contratos', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
