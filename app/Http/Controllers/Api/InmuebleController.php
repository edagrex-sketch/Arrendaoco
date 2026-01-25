<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inmueble;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class InmuebleController extends Controller
{
    use AuthorizesRequests;

    /**
     * Listar inmuebles
     * - Admin: todos
     * - Propietario: solo los suyos
     */
    public function index(Request $request)
    {
        if ($request->user()->es_admin) {
            return Inmueble::all();
        }

        return $request->user()->inmuebles;
    }

    /**
     * Crear inmueble
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'titulo'          => 'required|string|max:255',
            'descripcion'     => 'nullable|string',
            'direccion'       => 'required|string',
            'ciudad'          => 'required|string',
            'estado'          => 'required|string',
            'codigo_postal'   => 'required|string|max:10',
            'renta_mensual'   => 'required|numeric|min:0',
            'deposito'        => 'nullable|numeric|min:0',
        ]);

        $inmueble = Inmueble::create([
            ...$data,
            'propietario_id' => $request->user()->id,
        ]);

        return response()->json($inmueble, 201);
    }

    /**
     * Ver un inmueble específico
     */
    public function show(Inmueble $inmueble)
    {
        $this->authorize('view', $inmueble);

        return $inmueble;
    }

    /**
     * Actualizar inmueble
     */
    public function update(Request $request, Inmueble $inmueble)
    {
        $this->authorize('update', $inmueble);

        $data = $request->validate([
            'titulo'          => 'sometimes|string|max:255',
            'descripcion'     => 'nullable|string',
            'direccion'       => 'sometimes|string',
            'ciudad'          => 'sometimes|string',
            'estado'          => 'sometimes|string',
            'codigo_postal'   => 'sometimes|string|max:10',
            'renta_mensual'   => 'sometimes|numeric|min:0',
            'deposito'        => 'nullable|numeric|min:0',
            'estatus'         => 'in:disponible,rentado,inactivo',
        ]);

        $inmueble->fill($data);
        $inmueble->save();

        return $inmueble;
    }

    /**
     * Eliminar inmueble
     */
    public function destroy(Inmueble $inmueble)
    {
        $this->authorize('delete', $inmueble);

        $inmueble->delete();

        return response()->noContent();
    }
}
