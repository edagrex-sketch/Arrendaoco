<?php

namespace App\Http\Controllers;

use App\Models\Favorito;
use App\Models\Inmueble;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoritoController extends Controller
{
    /**
     * Muestra la lista de favoritos del usuario (RF-29: Consulta)
     */
    public function index()
    {
        $favoritos = Auth::user()->inmueblesFavoritos()->latest()->paginate(12);
        return view('favoritos.index', compact('favoritos'));
    }

    /**
     * Alterna un inmueble como favorito (RF-26: Alta / RF-27: Baja)
     */
    public function toggle(Inmueble $inmueble)
    {
        $usuario = Auth::user();
        
        $existe = Favorito::where('usuario_id', $usuario->id)
                          ->where('inmueble_id', $inmueble->id)
                          ->first();

        if ($existe) {
            $existe->delete();
            $mensaje = 'Eliminado de favoritos';
            $agregado = false;
        } else {
            Favorito::create([
                'usuario_id' => $usuario->id,
                'inmueble_id' => $inmueble->id
            ]);
            $mensaje = 'Agregado a favoritos';
            $agregado = true;
        }

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'mensaje' => $mensaje,
                'agregado' => $agregado
            ]);
        }

        return back();
    }

    /**
     * Actualiza la nota de un favorito (RF-28: Edición)
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

        return back()->with('success', 'Nota actualizada correctamente');
    }
}
