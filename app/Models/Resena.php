<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resena extends Model
{
    use HasFactory;

    protected $table = 'resenas';

    protected $fillable = [
        'usuario_id',
        'inmueble_id',
        'puntuacion',
        'comentario',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function inmueble()
    {
        return $this->belongsTo(Inmueble::class, 'inmueble_id');
    }
}
