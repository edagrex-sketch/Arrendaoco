<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorito;
use App\Models\Inmueble;
use App\Http\Resources\InmuebleResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoritoController extends Controller
{
    /**
     * Listar favoritos del usuario autenticado
     */
    public function index()
    {
        $favoritos = Auth::user()->inmueblesFavoritos()->latest()->paginate(15);
        return InmuebleResource::collection($favoritos);
    }

    /**
     * Alternar favorito (Agregar/Eliminar)
     */
    public function toggle(Inmueble $inmueble)
    {
        $usuario = Auth::user();
        
        $favorito = Favorito::where('usuario_id', $usuario->id)
                            ->where('inmueble_id', $inmueble->id)
                            ->first();

        if ($favorito) {
            $favorito->delete();
            return response()->json([
                'success' => true,
                'message' => 'Eliminado de favoritos',
                'is_favorite' => false
            ]);
        } else {
            Favorito::create([
                'usuario_id' => $usuario->id,
                'inmueble_id' => $inmueble->id
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Agregado a favoritos',
                'is_favorite' => true
            ]);
        }
    }

    /**
     * Actualizar nota de un favorito
     */
    public function update(Request $request, Inmueble $inmueble)
    {
        $request->validate([
            'nota' => 'nullable|string|max:500'
        ]);

        $favorito = Favorito::where('usuario_id', Auth::id())
                            ->where('inmueble_id', $inmueble->id)
                            ->firstOrFail();

        $favorito->update([
            'nota' => $request->nota
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Nota actualizada'
        ]);
    }
}
