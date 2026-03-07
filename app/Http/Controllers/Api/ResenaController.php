<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Resena;
use App\Models\Inmueble;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResenaController extends Controller
{
    /**
     * Obtener una reseña específica
     */
    public function show(Resena $resena)
    {
        return response()->json([
            'data' => [
                'id' => $resena->id,
                'usuario_id' => $resena->usuario_id,
                'usuario' => $resena->usuario->nombre,
                'inmueble_id' => $resena->inmueble_id,
                'puntuacion' => $resena->puntuacion,
                'comentario' => $resena->comentario,
                'fecha' => $resena->created_at->diffForHumans(),
            ]
        ]);
    }

    /**
     * Listar todas las reseñas de un inmueble (publico)
     */
    public function index(Inmueble $inmueble)
    {
        $resenas = $inmueble->resenas()->with('usuario')->latest()->paginate(15);
        return response()->json([
            'resenas' => $resenas->map(function($res) {
                return [
                    'id' => $res->id,
                    'usuario' => $res->usuario->nombre,
                    'foto_perfil' => $res->usuario->foto_perfil ? url('storage/' . $res->usuario->foto_perfil) : null,
                    'puntuacion' => $res->puntuacion,
                    'comentario' => $res->comentario,
                    'fecha' => $res->created_at->diffForHumans(),
                    'created_at' => $res->created_at->toDateTimeString(),
                ];
            }),
            'promedio' => $inmueble->resenas->avg('puntuacion') ?? 0,
            'total' => $inmueble->resenas->count(),
        ]);
    }

    /**
     * Crear reseña
     */
    public function store(Request $request, Inmueble $inmueble)
    {
        $data = $request->validate([
            'puntuacion' => 'required|integer|min:1|max:5',
            'comentario' => ['required', 'string', 'max:1000', 'regex:/^[a-zA-Z0-9\s.,?!*\-áéíóúÁÉÍÓÚñÑ]+$/u'],
        ], [
            'comentario.regex' => 'El comentario contiene caracteres no válidos. Solo se permiten letras, números y los símbolos: . , ? ! * -'
        ]);

        $resena = Resena::updateOrCreate(
            [
                'usuario_id' => Auth::id(),
                'inmueble_id' => $inmueble->id,
            ],
            $data
        );

        return response()->json([
            'message' => 'Reseña guardada correctamente',
            'resena' => $resena
        ]);
    }

    /**
     * Actualizar reseña
     */
    public function update(Request $request, Resena $resena)
    {
        if ($resena->usuario_id !== Auth::id()) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $data = $request->validate([
            'puntuacion' => 'required|integer|min:1|max:5',
            'comentario' => ['required', 'string', 'max:1000', 'regex:/^[a-zA-Z0-9\s.,?!*\-áéíóúÁÉÍÓÚñÑ]+$/u'],
        ], [
            'comentario.regex' => 'El comentario contiene caracteres no válidos. Solo se permiten letras, números y los símbolos: . , ? ! * -'
        ]);

        $resena->update($data);

        return response()->json([
            'message' => 'Reseña actualizada',
            'resena' => $resena
        ]);
    }

    /**
     * Eliminar reseña
     */
    public function destroy(Resena $resena)
    {
        if ($resena->usuario_id !== Auth::id() && !Auth::user()->es_admin && !Auth::user()->tieneRol('admin')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $resena->delete();

        return response()->json(['message' => 'Reseña eliminada']);
    }

    /**
     * Eliminar la reseña del usuario actual para un inmueble específico
     */
    public function destroyByInmueble(Inmueble $inmueble)
    {
        $resena = Resena::where('usuario_id', Auth::id())
            ->where('inmueble_id', $inmueble->id)
            ->first();

        if (!$resena) {
            return response()->json(['message' => 'No tienes una reseña en este inmueble'], 404);
        }

        $resena->delete();

        return response()->json(['message' => 'Reseña eliminada correctamente']);
    }
}
