<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Contrato;
use App\Models\Usuario;
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
        'metros',
        'imagen',
        'latitud',
        'longitud',
        'video_youtube',
        'video_youtube_id',
        'video_thumbnail',
        'video_titulo',
        'video_canal',
        'video_descripcion',
        'video_duracion',
        'video_vistas',
        'video_likes',
        'video_publicado_en',
        'video_actualizado_en',
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
}
