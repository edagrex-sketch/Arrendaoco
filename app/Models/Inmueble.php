<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Contrato;
use App\Models\Usuario;
use App\Support\MediaUrl;

class Inmueble extends Model
{
    use HasFactory;

    protected $table = 'inmuebles';

    protected $fillable = [
        'propietario_id',
        'titulo',
        'descripcion',
        'direccion',
        'ciudad',
        'estado',
        'codigo_postal',
        'renta_mensual',
        'deposito',
        'estatus',
        'tipo',
        'habitaciones',
        'banos',
        'medios_banos',
        'bano_compartido',
        'metros',
        'imagen',
        'latitud',
        'longitud',
        'latitud',
        'longitud',
        'contrato_documento',
        'requiere_deposito',
        'tiene_cerradura_propia',
        'tiene_cerradura', // Retained for backwards compatibility
        'cantidad_llaves',
        'tiene_estacionamiento',
        'estado_mobiliario',
        'momento_pago',
        'dias_tolerancia',
        'dias_preaviso',
        'duracion_contrato_meses', // Duración del contrato definida por el propietario
        'permite_mascotas',
        'tipos_mascotas',
        'servicios_incluidos',
        'pago_servicio',
        'incluir_clausulas',
        'clausulas_extra',
        'clabe_interbancaria',
        'banco',
        'registrado_desde',
        'plataforma_metadata',
        'largo',
        'ancho',
    ];


    protected $casts = [
        'tipos_mascotas'          => 'array',
        'servicios_incluidos'     => 'array',
        'pago_servicio'           => 'array',
        'requiere_deposito'       => 'boolean',
        'tiene_cerradura_propia'  => 'boolean',
        'tiene_estacionamiento'   => 'boolean',
        'permite_mascotas'        => 'boolean',
        'incluir_clausulas'       => 'boolean',
        'duracion_contrato_meses' => 'integer',
        'plataforma_metadata'     => 'array',
    ];


    public function propietario()
    {
        return $this->belongsTo(Usuario::class, 'propietario_id');
    }
    public function contratos()
    {
        return $this->hasMany(Contrato::class);
    }

    public function resenas()
    {
        return $this->hasMany(Resena::class)->orderBy('created_at', 'desc');
    }

    public function favoritos()
    {
        return $this->hasMany(Favorito::class, 'inmueble_id');
    }

    public function imagenes()
    {
        return $this->hasMany(ImagenInmueble::class, 'inmueble_id');
    }

    public function getImagenUrlAttribute(): ?string
    {
        return MediaUrl::fromStoragePath($this->attributes['imagen'] ?? null);
    }

    public function mascotas()
    {
        return $this->belongsToMany(Mascota::class, 'inmueble_mascota');
    }

    public function mobiliarios()
    {
        return $this->belongsToMany(Mobiliario::class, 'inmueble_mobiliario');
    }

    public function zonasComunes()
    {
        return $this->belongsToMany(ZonaComun::class, 'inmueble_zona_comun');
    }

    public function servicios()
    {
        return $this->hasMany(InmuebleServicio::class, 'inmueble_id');
    }

    /**
     * Une los servicios de la tabla relacional (web) con la columna JSON (móvil)
     */
    public function mergeServiciosParaApp()
    {
        $mapa = is_array($this->pago_servicio) ? $this->pago_servicio : [];

        // Si hay servicios en la tabla relacional, los agregamos si no están ya
        if ($this->servicios) {
            foreach ($this->servicios as $s) {
                if (!isset($mapa[$s->servicio])) {
                    $mapa[$s->servicio] = ($s->paga === 'arrendador') ? 'Arrendador' : 'Inquilino';
                }
            }
        }

        return (object)$mapa;
    }
}
