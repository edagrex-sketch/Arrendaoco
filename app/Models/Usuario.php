<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Inmueble;
use App\Models\Contrato;

class Usuario extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'email',
        'password',
        'telefono',
        'es_admin',
        'estatus',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    public function inmuebles()
    {
        return $this->hasMany(Inmueble::class, 'propietario_id');
    }

    public function contratosComoPropietario()
    {
        return $this->hasMany(Contrato::class, 'propietario_id');
    }

    public function contratosComoInquilino()
    {
        return $this->hasMany(Contrato::class, 'inquilino_id');
    }
}
