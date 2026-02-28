<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    protected $table = 'calendario';

    protected $fillable = [
        'usuario_id',
        'renta_id',
        'titulo',
        'descripcion',
        'fecha',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function contrato()
    {
        return $this->belongsTo(Contrato::class, 'renta_id');
    }
}
