<?php
namespace App\Policies;

use App\Models\Inmueble;
use App\Models\Usuario;

class InmueblePolicy
{
    // Admin ve todo / Propietario ve solo los suyos
    public function view(Usuario $user, Inmueble $inmueble): bool
    {
        return $user->es_admin || $inmueble->propietario_id === $user->id;
    }

    public function update(Usuario $user, Inmueble $inmueble): bool
    {
        return $user->es_admin || $inmueble->propietario_id === $user->id;
    }

    public function delete(Usuario $user, Inmueble $inmueble): bool
    {
        return $user->es_admin || $inmueble->propietario_id === $user->id;
    }

    // Crear inmueble: cualquiera autenticado
    public function create(Usuario $user): bool
    {
        return true;
    }
}
