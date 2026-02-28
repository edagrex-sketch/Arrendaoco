<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImagenInmueble extends Model
{
    use HasFactory;

    protected $table = 'imagenes_inmuebles';

    protected $fillable = [
        'inmueble_id',
        'ruta_imagen',
    ];

    public function inmueble()
    {
        return $this->belongsTo(Inmueble::class);
    }
}
