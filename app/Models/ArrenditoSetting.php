<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArrenditoSetting extends Model
{
    protected $table = 'arrendito_settings';

    protected $fillable = [
        'usuario_id',
        'nombre',
    ];

    /**
     * Relación con el usuario
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
