<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EventoController extends Controller
{
    use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;

    public function index(Request $request)
    {
        $eventos = \App\Models\Evento::where('usuario_id', $request->user()->id)
            ->orderBy('fecha', 'asc')
            ->get();
        return response()->json($eventos);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'titulo'      => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha'       => 'required|date',
            'renta_id'    => 'nullable|exists:contratos,id',
        ]);

        $evento = \App\Models\Evento::create([
            ...$data,
            'usuario_id' => $request->user()->id,
        ]);

        return response()->json($evento, 201);
    }

    public function byRenta(Request $request, $rentaId)
    {
        $eventos = \App\Models\Evento::where('renta_id', $rentaId)
            ->orderBy('fecha', 'asc')
            ->get();
        return response()->json($eventos);
    }

    public function update(Request $request, \App\Models\Evento $evento)
    {
        if ($evento->usuario_id !== $request->user()->id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $data = $request->validate([
            'titulo'      => 'sometimes|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha'       => 'sometimes|date',
        ]);

        $evento->update($data);
        return response()->json($evento);
    }

    public function destroy(Request $request, \App\Models\Evento $evento)
    {
        if ($evento->usuario_id !== $request->user()->id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $evento->delete();
        return response()->json(['success' => true]);
    }
}
