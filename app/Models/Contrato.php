<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Inmueble;
use App\Models\Usuario;
use App\Models\Pago;

class Contrato extends Model
{
    use HasFactory;

    protected $table = 'contratos';

    protected $fillable = [
        'inmueble_id',
        'propietario_id',
        'inquilino_id',
        'fecha_inicio',
        'fecha_fin',
        'plazo',
        'renta_mensual',
        'deposito',
        'estatus',
        'stripe_payment_intent_id',
        // ── Flujo físico (firma digital eliminada) ──────────────────
        'archivo_firmado',      // Escaneo del contrato firmado (subido por propietario)
        'pdf_descargado_at',    // Timestamp de primer descarga del PDF
        'archivo_subido_at',    // Timestamp de cuando se subió el escaneo
        // ── LEGADO (sin usar en nuevos contratos) ────────────────────
        'firma_digital',        // Base64 — solo en registros históricos
        'firma_propietario',    // Base64 — solo en registros históricos
    ];

    /**
     * Cast automático de timestamps y booleanos.
     */
    protected $casts = [
        'fecha_inicio'       => 'date',
        'fecha_fin'          => 'date',
        'pdf_descargado_at'  => 'datetime',
        'archivo_subido_at'  => 'datetime',
        'renta_mensual'      => 'decimal:2',
        'deposito'           => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    /** Propiedad que se arrienda en este contrato */
    public function inmueble(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Inmueble::class);
    }

    /** Usuario arrendador (propietario del inmueble en este contrato) */
    public function propietario(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'propietario_id');
    }

    /** Usuario arrendatario (inquilino) */
    public function inquilino(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'inquilino_id');
    }

    /** Pagos registrados en este contrato */
    public function pagos(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Pago::class);
    }

    /** Estados de cuenta mensuales */
    public function estadosCuenta(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(EstadoCuenta::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers / Accessors
    |--------------------------------------------------------------------------
    */

    /**
     * Indica si el PDF ya fue descargado por alguna parte.
     */
    public function getPdfDescargadoAttribute(): bool
    {
        return !is_null($this->pdf_descargado_at);
    }

    /**
     * Indica si el propietario ya subió el escaneo del contrato firmado.
     */
    public function getArchivoSubidoAttribute(): bool
    {
        return !is_null($this->archivo_subido_at);
    }

    /**
     * Indica si el contrato está en un estado considerado "activo/vigente".
     */
    public function estaVigente(): bool
    {
        return $this->estatus === 'activo';
    }

    /**
     * Indica si el contrato está pendiente de que el propietario suba la evidencia.
     * (PDF descargado pero aún no se subió el firmado)
     */
    public function pendienteDeEvidencia(): bool
    {
        return $this->estatus === 'pdf_descargado'
            && !is_null($this->pdf_descargado_at)
            && is_null($this->archivo_subido_at);
    }
}
