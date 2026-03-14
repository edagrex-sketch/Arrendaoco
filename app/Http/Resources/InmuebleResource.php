<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Support\MediaUrl;

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
            'medios_banos' => $this->medios_banos ?? 0,
            'bano_compartido' => (bool)($this->bano_compartido ?? false),
            'metros' => $this->metros,
            'imagen_portada' => MediaUrl::fromStoragePath($this->imagen),
            'latitud' => $this->latitud,
            'longitud' => $this->longitud,
            'propietario' => [
                'id' => $this->propietario->id,
                'nombre' => $this->propietario->nombre,
                'email' => $this->propietario->email,
                'foto_perfil' => MediaUrl::fromStoragePath($this->propietario->foto_perfil),
            ],
            'imagenes' => $this->imagenes->map(function ($img) {
                return [
                    'id' => $img->id,
                    'url' => MediaUrl::fromStoragePath($img->ruta_imagen),
                ];
            }),
            'resenas' => $this->resenas->map(function ($res) {
                return [
                    'id' => $res->id,
                    'usuario_id' => $res->usuario_id,
                    'usuario' => $res->usuario->nombre,
                    'foto_perfil' => MediaUrl::fromStoragePath($res->usuario->foto_perfil),
                    'puntuacion' => $res->puntuacion,
                    'comentario' => $res->comentario,
                    'fecha' => $res->created_at->diffForHumans(),
                    'created_at' => $res->created_at->toDateTimeString(),
                ];
            }),
            'promedio_calificacion' => $this->resenas->avg('puntuacion') ?? 0,
            'total_resenas' => $this->resenas->count(),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
