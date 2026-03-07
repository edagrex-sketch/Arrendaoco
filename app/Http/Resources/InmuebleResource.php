<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InmuebleResource extends JsonResource
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
            'titulo' => $this->titulo,
            'descripcion' => $this->descripcion,
            'direccion' => $this->direccion,
            'ciudad' => $this->ciudad,
            'estado' => $this->estado,
            'codigo_postal' => $this->codigo_postal,
            'renta_mensual' => $this->renta_mensual,
            'deposito' => $this->deposito,
            'estatus' => $this->estatus,
            'tipo' => $this->tipo,
            'habitaciones' => $this->habitaciones,
            'banos' => $this->banos,
            'metros' => $this->metros,
            'imagen_portada' => $this->imagen ? url($this->imagen) : null,
            'latitud' => $this->latitud,
            'longitud' => $this->longitud,
            'propietario' => [
                'id' => $this->propietario->id,
                'nombre' => $this->propietario->nombre,
                'email' => $this->propietario->email,
                'foto_perfil' => $this->propietario->foto_perfil ? url('storage/' . $this->propietario->foto_perfil) : null,
            ],
            'imagenes' => $this->imagenes->map(function($img) {
                return [
                    'id' => $img->id,
                    'url' => url($img->ruta_imagen),
                ];
            }),
            'resenas' => $this->resenas->map(function($res) {
                return [
                    'id' => $res->id,
                    'usuario' => $res->usuario->nombre,
                    'puntuacion' => $res->puntuacion,
                    'comentario' => $res->comentario,
                    'fecha' => $res->created_at->diffForHumans(),
                ];
            }),
            'promedio_calificacion' => $this->resenas->avg('puntuacion') ?? 0,
            'total_resenas' => $this->resenas->count(),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
