<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    ];

    public function propietario()
    {
        return $this->belongsTo(Usuario::class, 'propietario_id');
    }
}
