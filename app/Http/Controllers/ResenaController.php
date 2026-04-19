<?php

namespace App\Http\Controllers;

use App\Models\Inmueble;
use App\Models\Resena;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ResenaController extends Controller
{
    /**
     * Muestra todas las reseñas para moderación (Admin).
     */
    public function index(Request $request)
    {
        if (!Auth::user()->es_admin && !Auth::user()->tieneRol('admin')) {
            abort(403);
        }

        $query = Resena::with(['usuario', 'inmueble']);
        $this->applyFilters($query, $request);

        $resenas = $query->latest()->paginate(10)->withQueryString();
        return view('admin.resenas.index', compact('resenas'));
    }

    public function reporte(Request $request)
    {
        if (!Auth::user()->es_admin && !Auth::user()->tieneRol('admin')) {
            abort(403);
        }

        $query = Resena::with(['usuario', 'inmueble']);
        $this->applyFilters($query, $request);

        $resenas = $query->latest()->get();
        $pdf = Pdf::loadView('admin.resenas.reporte', compact('resenas'));
        return $pdf->download('reporte_resenas.pdf');
    }

    private function applyFilters($query, Request $request)
    {
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('comentario', 'like', "%$search%")
                    ->orWhereHas('usuario', function ($sq) use ($search) {
                        $sq->where('nombre', 'like', "%$search%");
                    })
                    ->orWhereHas('inmueble', function ($sq) use ($search) {
                        $sq->where('titulo', 'like', "%$search%");
                    });
            });
        }

        if ($request->filled('puntuacion')) {
            $query->where('puntuacion', $request->puntuacion);
        }

        if ($request->filled('desde')) {
            $query->whereDate('created_at', '>=', $request->desde);
        }

        if ($request->filled('hasta')) {
            $query->whereDate('created_at', '<=', $request->hasta);
        }
    }

    /**
     * Almacena una nueva reseña para un inmueble.
     */
    public function store(Request $request, Inmueble $inmueble)
    {
        $request->validate([
            'puntuacion' => 'required|integer|min:1|max:5',
            'comentario' => ['required', 'string', 'max:1000', 'regex:/^[a-zA-Z0-9\s.,?!*\-áéíóúÁÉÍÓÚñÑ]+$/u'],
        ], [
            'comentario.regex' => 'El comentario contiene caracteres no permitidos. Solo puedes usar letras, números y signos básicos: . , ? ! * -'
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

        $resena = Resena::create([
            'usuario_id' => Auth::id(),
            'inmueble_id' => $inmueble->id,
            'puntuacion' => $request->puntuacion,
            'comentario' => $request->comentario,
        ]);

        // Notificar al propietario
        \App\Services\NotificationService::send(
            $inmueble->propietario_id,
            'Nueva reseña recibida',
            Auth::user()->nombre . " calificó tu propiedad con " . $request->puntuacion . " estrellas.",
            'sistema',
            $inmueble->id
        );

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
            'comentario' => ['required', 'string', 'max:1000', 'regex:/^[a-zA-Z0-9\s.,?!*\-áéíóúÁÉÍÓÚñÑ]+$/u'],
        ], [
            'comentario.regex' => 'El comentario contiene caracteres no permitidos. Solo puedes usar letras, números y signos básicos: . , ? ! * -'
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
