<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inmueble;
use Illuminate\Http\Request;

class InmuebleController extends Controller
{
    // Listar inmuebles del usuario autenticado
    public function index(Request $request)
    {
        return $request->user()->inmuebles;
    }

    // Crear inmueble
    public function store(Request $request)
    {
        $data = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'direccion' => 'required|string',
            'ciudad' => 'required|string',
            'estado' => 'required|string',
            'codigo_postal' => 'required|string|max:10',
            'renta_mensual' => 'required|numeric|min:0',
            'deposito' => 'nullable|numeric|min:0',
        ]);

        $inmueble = Inmueble::create([
            ...$data,
            'propietario_id' => $request->user()->id,
        ]);

        return response()->json($inmueble, 201);
    }

    // Ver un inmueble específico
    public function show(Inmueble $inmueble)
    {
        $this->authorizeInmueble($inmueble);

        return $inmueble;
    }

    // Actualizar inmueble
    public function update(Request $request, Inmueble $inmueble)
    {
        $this->authorizeInmueble($inmueble);

        $data = $request->validate([
            'titulo' => 'sometimes|string|max:255',
            'descripcion' => 'nullable|string',
            'direccion' => 'sometimes|string',
            'ciudad' => 'sometimes|string',
            'estado' => 'sometimes|string',
            'codigo_postal' => 'sometimes|string|max:10',
            'renta_mensual' => 'sometimes|numeric|min:0',
            'deposito' => 'nullable|numeric|min:0',
            'estatus' => 'in:disponible,rentado,inactivo',
        ]);

        $inmueble->update($data);

        return $inmueble;
    }

    // Eliminar inmueble
    public function destroy(Inmueble $inmueble)
    {
        $this->authorizeInmueble($inmueble);

        $inmueble->delete();

        return response()->noContent();
    }

    // 🔒 Seguridad: solo el propietario
    private function authorizeInmueble(Inmueble $inmueble)
    {
        if ($inmueble->propietario_id !== auth()->id()) {
            abort(403, 'No autorizado');
        }
    }
}
