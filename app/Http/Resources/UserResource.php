<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'email' => $this->email,
            'foto_perfil' => $this->foto_perfil ? (str_starts_with($this->foto_perfil, 'http') ? $this->foto_perfil : url('storage/' . $this->foto_perfil)) : null,
            'roles' => $this->roles->pluck('nombre'),
            'es_admin' => $this->es_admin,
            'estatus' => $this->estatus,
            'stripe_onboarding_completed' => (bool)($this->stripe_onboarding_completed ?? false),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
