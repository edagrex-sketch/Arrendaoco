<?php

namespace App\Http\Controllers;

use App\Models\Inmueble;
use App\Models\Resena;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResenaController extends Controller
{
    /**
     * Muestra todas las reseñas para moderación (Admin).
     */
    public function index()
    {
        if (!Auth::user()->es_admin && !Auth::user()->tieneRol('admin')) {
            abort(403);
        }

        $resenas = Resena::with(['usuario', 'inmueble'])->latest()->paginate(20);
        return view('admin.resenas.index', compact('resenas'));
    }

    /**
     * Almacena una nueva reseña para un inmueble.
     */
    public function store(Request $request, Inmueble $inmueble)
    {
        $request->validate([
            'puntuacion' => 'required|integer|min:1|max:5',
            'comentario' => 'required|string|max:1000',
        ]);

        // Evitar que el usuario califique su propio inmueble
        if ($inmueble->propietario_id === Auth::id()) {
            return back()->with('error', 'No puedes calificar tu propia propiedad.');
        }

        // Opcional: Evitar múltiples reseñas del mismo usuario en el mismo inmueble
        $resenaExistente = Resena::where('usuario_id', Auth::id())
                                ->where('inmueble_id', $inmueble->id)
                                ->first();
        
        if ($resenaExistente) {
            return back()->with('error', 'Ya has calificado esta propiedad.');
        }

        Resena::create([
            'usuario_id' => Auth::id(),
            'inmueble_id' => $inmueble->id,
            'puntuacion' => $request->puntuacion,
            'comentario' => $request->comentario,
        ]);

        return back()->with('success', 'Tu reseña ha sido enviada con éxito.');
    }

    /**
     * Actualiza una reseña existente.
     */
    public function update(Request $request, Resena $resena)
    {
        if ($resena->usuario_id !== Auth::id()) {
            abort(403, 'No tienes permiso para editar esta reseña.');
        }

        $request->validate([
            'puntuacion' => 'required|integer|min:1|max:5',
            'comentario' => 'required|string|max:1000',
        ]);

        $resena->update([
            'puntuacion' => $request->puntuacion,
            'comentario' => $request->comentario,
        ]);

        return back()->with('success', 'Reseña actualizada correctamente.');
    }

    /**
     * Elimina una reseña.
     */
    public function destroy(Resena $resena)
    {
        $user = Auth::user();
        
        // El autor o el admin pueden eliminar
        if ($resena->usuario_id === $user->id || $user->es_admin || $user->tieneRol('admin')) {
            $resena->delete();
            return back()->with('success', 'Reseña eliminada correctamente.');
        }

        abort(403, 'No tienes permiso para eliminar esta reseña.');
    }
}
