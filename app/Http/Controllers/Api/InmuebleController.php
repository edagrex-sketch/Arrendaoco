<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inmueble;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\Http\Resources\InmuebleResource;
use Illuminate\Support\Facades\Storage;
use App\Models\ImagenInmueble;
use App\Support\MediaUrl;

class InmuebleController extends Controller
{
    use AuthorizesRequests;

    /**
     * Listar inmuebles disponibles (Publico para la App)
     */
    public function publicList(Request $request)
    {
        $query = Inmueble::with(['propietario', 'imagenes', 'resenas.usuario'])
            ->where('estatus', 'disponible');

        // Excluir mis propios inmuebles si estoy autenticado (para no rentarme a mi mismo)
        if ($request->user('sanctum')) {
            $query->where('propietario_id', '!=', $request->user('sanctum')->id);
        }

        if ($request->has('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->has('ciudad')) {
            $query->where('ciudad', 'like', '%' . $request->ciudad . '%');
        }

        return InmuebleResource::collection($query->latest()->paginate(10));
    }

    /**
     * Listar inmuebles (Admin/Propietario)
     */
    public function index(Request $request)
    {
        $query = Inmueble::with(['propietario', 'imagenes', 'resenas.usuario']);

        if (!$request->user()->es_admin && !$request->user()->tieneRol('admin')) {
            $query->where('propietario_id', $request->user()->id);
        }

        return InmuebleResource::collection($query->latest()->paginate(15));
    }

    /**
     * Crear inmueble
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'titulo'          => 'required|string|max:255',
            'descripcion'     => 'required|string',
            'direccion'       => 'required|string',
            'tipo'            => 'required|string',
            'renta_mensual'   => 'required|numeric|min:0',
            'deposito'        => 'nullable|numeric|min:0',
            'habitaciones'    => 'required|integer|min:0',
            'banos'           => 'required|numeric|min:0',
            'medios_banos'    => 'nullable|integer|min:0',
            'bano_compartido' => 'nullable|boolean',
            'metros'          => 'required|numeric|min:0',
            'latitud'         => 'nullable|numeric',
            'longitud'        => 'nullable|numeric',
            'imagenes'        => 'nullable|array',
            'imagenes.*'      => 'image|max:5120',
            'registrado_desde'    => 'nullable|string',
            'plataforma_metadata' => 'nullable|array',
            'estatus'         => 'nullable|string|in:disponible,inactivo,rentado',
            // Nuevos campos de paridad
            'tiene_estacionamiento' => 'nullable|boolean',
            'permite_mascotas'      => 'nullable|boolean',
            'tipos_mascotas'        => 'nullable|array',
            'estado_mobiliario'     => 'nullable|string',
            'servicios_incluidos'   => 'nullable|array',
            'pago_servicio'         => 'nullable|array',
            'momento_pago'          => 'nullable|string',
            'dias_tolerancia'       => 'nullable|integer',
            'dias_preaviso'         => 'nullable|integer',
            'duracion_contrato_meses' => 'nullable|integer',
            'clabe_interbancaria'    => 'nullable|string|max:18',
            'banco'                 => 'nullable|string',
            'incluir_clausulas'     => 'nullable|boolean',
            'clausulas_extra'       => 'nullable|string',
        ]);


        // Limpiar datos numéricos/booleanos que podrían venir como strings vacíos desde el móvil
        $cleanData = collect($data)->map(function ($value, $key) {
            if ($value === '' || $value === 'null') return null;
            return $value;
        })->toArray();

        $inmueble = Inmueble::create([
            ...collect($cleanData)->except(['imagenes', 'estatus', 'tipos_mascotas', 'servicios_incluidos', 'pago_servicio'])->toArray(),
            'propietario_id' => $request->user()->id,
            'ciudad'         => 'Ocosingo',
            'estado'         => 'Chiapas',
            'codigo_postal'  => '29950',
            'estatus'        => $data['estatus'] ?? 'disponible',
            'tipos_mascotas' => $request->input('tipos_mascotas'),
            'servicios_incluidos' => $request->input('servicios_incluidos'),
            'pago_servicio' => $request->input('pago_servicio'),
            'registrado_desde' => $data['registrado_desde'] ?? ($request->header('User-Agent') ? 'mobile' : 'web'),
        ]);


        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $index => $file) {
                $path = $file->store('inmuebles', 'public');
                MediaUrl::ensurePublicStorageCopy($path);
                $ruta = 'storage/' . $path;
                
                if ($index === 0) {
                    $inmueble->update(['imagen' => $ruta]);
                }

                ImagenInmueble::create([
                    'inmueble_id' => $inmueble->id,
                    'ruta_imagen' => $ruta
                ]);
            }
        }

        return new InmuebleResource($inmueble->load(['propietario', 'imagenes']));
    }

    /**
     * Ver un inmueble específico
     */
    public function show(Inmueble $inmueble)
    {
        // No autorizamos para que sea publico el detalle en la app si se desea
        return new InmuebleResource($inmueble->load(['propietario', 'imagenes', 'resenas.usuario', 'servicios']));
    }

    /**
     * Actualizar inmueble
     */
    public function update(Request $request, Inmueble $inmueble)
    {
        $this->authorize('update', $inmueble);

        $data = $request->validate([
            'titulo'          => 'sometimes|string|max:255',
            'descripcion'     => 'sometimes|string',
            'direccion'       => 'sometimes|string',
            'renta_mensual'   => 'sometimes|numeric|min:0',
            'deposito'        => 'nullable|numeric|min:0',
            'estatus'         => 'in:disponible,rentado,inactivo',
            'habitaciones'    => 'sometimes|integer|min:0',
            'banos'           => 'sometimes|integer|min:0',
            'medios_banos'    => 'nullable|integer|min:0',
            'bano_compartido' => 'nullable|boolean',
            'metros'          => 'sometimes|numeric|min:0',
            'imagenes'        => 'nullable|array',
            'imagenes.*'      => 'image|max:5120',
            'plataforma_metadata' => 'nullable|array',
            'tiene_estacionamiento' => 'nullable|boolean',
            'permite_mascotas'      => 'nullable|boolean',
            'estado_mobiliario'     => 'nullable|string',
            'momento_pago'          => 'nullable|string',
            'clabe_interbancaria'    => 'nullable|string',
        ]);


        $inmueble->update(collect($data)->except('imagenes')->toArray());


        if ($request->hasFile('imagenes')) {
            // Opcional: Eliminar imagenes anteriores or keep them
            // Por ahora agregamos nuevas
            foreach ($request->file('imagenes') as $index => $file) {
                $path = $file->store('inmuebles', 'public');
                MediaUrl::ensurePublicStorageCopy($path);
                $ruta = 'storage/' . $path;
                
                if ($index === 0 && !$inmueble->imagen) {
                    $inmueble->update(['imagen' => $ruta]);
                }

                ImagenInmueble::create([
                    'inmueble_id' => $inmueble->id,
                    'ruta_imagen' => $ruta
                ]);
            }
        }

        return new InmuebleResource($inmueble->load(['propietario', 'imagenes']));
    }

    /**
     * Eliminar inmueble
     */
    public function destroy(Inmueble $inmueble)
    {
        $this->authorize('delete', $inmueble);
        $inmueble->delete();
        return response()->json(['message' => 'Inmueble eliminado']);
    }
}


