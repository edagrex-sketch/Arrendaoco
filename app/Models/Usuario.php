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

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_usuario', 'usuario_id', 'role_id');
    }

    public function tieneRol($rolNombre)
    {
        return $this->roles()->where('nombre', $rolNombre)->exists();
    }

    public function asignarRol($rolNombre)
    {
        $rol = Role::where('nombre', $rolNombre)->first();
        if ($rol) {
            $this->roles()->syncWithoutDetaching([$rol->id]);
        }
    }

    public function eliminarRol($rolNombre)
    {
        $rol = Role::where('nombre', $rolNombre)->first();
        if ($rol) {
            $this->roles()->detach($rol->id);
        }
    }

    public function resenas()
    {
        return $this->hasMany(Resena::class, 'usuario_id');
    }

    public function favoritos()
    {
        return $this->hasMany(Favorito::class, 'usuario_id');
    }

    public function inmueblesFavoritos()
    {
        return $this->belongsToMany(Inmueble::class, 'favoritos', 'usuario_id', 'inmueble_id')
                    ->withPivot('nota')
                    ->withTimestamps();
    }
}
