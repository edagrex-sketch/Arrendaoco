<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['nombre', 'etiqueta'];

    public function usuarios()
    {
        return $this->belongsToMany(Usuario::class, 'role_usuario', 'role_id', 'usuario_id');
    }
}
