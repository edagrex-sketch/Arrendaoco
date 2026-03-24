<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Inmueble;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Support\MediaUrl;

use Barryvdh\DomPDF\Facade\Pdf;

class InmuebleController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        if (auth()->user()->es_admin || auth()->user()->tieneRol('admin')) {
            $query = Inmueble::with('propietario');

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('titulo', 'like', "%$search%")
                        ->orWhere('direccion', 'like', "%$search%")
                        ->orWhere('tipo', 'like', "%$search%")
                        ->orWhereHas('propietario', function ($sq) use ($search) {
                            $sq->where('nombre', 'like', "%$search%")
                                ->orWhere('email', 'like', "%$search%");
                        });
                });
            }

            $inmuebles = $query->paginate(10)->withQueryString();
            return view('admin.inmuebles.index', compact('inmuebles'));
        } else {
            $query = Inmueble::with('contratos.inquilino')->where('propietario_id', auth()->id());

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('titulo', 'like', "%$search%")
                        ->orWhere('direccion', 'like', "%$search%")
                        ->orWhere('tipo', 'like', "%$search%");
                });
            }

            $inmuebles = $query->paginate(10)->withQueryString();
            return view('inmuebles.index', compact('inmuebles'));
        }
    }

    public function reporte()
    {
        if (!auth()->user()->es_admin && !auth()->user()->tieneRol('admin')) {
            abort(403);
        }

        $inmuebles = Inmueble::with('propietario')->get();
        $pdf = Pdf::loadView('admin.inmuebles.reporte', compact('inmuebles'));
        return $pdf->download('reporte_inmuebles.pdf');
    }

    public function misRentas()
    {
        $userId = auth()->id();
        $contratos = \App\Models\Contrato::with(['inmueble.propietario', 'inquilino', 'pagos'])
            ->where('inquilino_id', $userId)
            ->orWhere('propietario_id', $userId)
            ->latest()
            ->get();
        return view('inmuebles.mis_rentas', compact('contratos'));
    }

    public function cancelarRenta(\App\Models\Contrato $contrato)
    {
        if ($contrato->inquilino_id !== auth()->id() && $contrato->propietario_id !== auth()->id() && !auth()->user()->es_admin && !auth()->user()->tieneRol('admin')) {
            abort(403, 'No tienes permiso para cancelar esta renta.');
        }

        if ($contrato->estatus !== 'activo') {
            return back()->with('error', 'Esta renta ya no está activa.');
        }

        try {
            DB::beginTransaction();
            $contrato->estatus = 'cancelado';
            
            // Set end date to now if it isn't set, otherwise maybe it already has one.
            if (!$contrato->fecha_fin) {
                // If the user wants to keep a history of what the intended end date was, they might not zero it out. But typically cancelling happens now.
                $contrato->fecha_fin = now();
            }
            $contrato->save();

            if ($contrato->inmueble) {
                $contrato->inmueble->estatus = 'disponible';
                $contrato->inmueble->save();
            }
            DB::commit();

            return back()->with('success', 'Renta (contrato) cancelada exitosamente y la propiedad está disponible de nuevo.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Ocurrió un error al cancelar la renta: ' . $e->getMessage());
        }
    }

    //cargar las ultimas 9 casas disponibles
    public function home()
    {
        $inmuebles = Inmueble::where('estatus', 'disponible')->latest()->paginate(9);
        $inmueblesMapa = Inmueble::where('estatus', 'disponible')->get();

        $favoritosIds = [];
        if (auth()->check()) {
            $favoritosIds = auth()->user()->favoritos()->pluck('inmueble_id')->toArray();
        }

        return view('inicio', compact('inmuebles', 'inmueblesMapa', 'favoritosIds'));
    }
    // buscador publico
    public function publicSearch(Request $request)
    {
        $query = Inmueble::where('estatus', 'disponible');

        // Filtro por ubicación (título, dirección o ciudad)
        if ($request->filled('ubicacion')) {
            $query->where(function ($q) use ($request) {
                $q->where('titulo', 'like', '%' . $request->ubicacion . '%')
                    ->orWhere('direccion', 'like', '%' . $request->ubicacion . '%')
                    ->orWhere('ciudad', 'like', '%' . $request->ubicacion . '%');
            });
        }

        // Filtro por categoría (tipo: casa, departamento, cuarto)
        if ($request->filled('categoria')) {
            $query->where('tipo', $request->categoria);
        }

        // Filtro por rango de precio
        if ($request->filled('rango_precio')) {
            switch ($request->rango_precio) {
                case '0-2000':
                    $query->whereBetween('renta_mensual', [0, 2000]);
                    break;
                case '2000-4000':
                    $query->whereBetween('renta_mensual', [2000, 4000]);
                    break;
                case '4000-6000':
                    $query->whereBetween('renta_mensual', [4000, 6000]);
                    break;
                case '6000+':
                    $query->where('renta_mensual', '>=', 6000);
                    break;
            }
        }

        $inmuebles = $query->paginate(12);

        $favoritosIds = [];
        if (auth()->check()) {
            $favoritosIds = auth()->user()->favoritos()->pluck('inmueble_id')->toArray();
        }

        if ($request->ajax() || $request->wantsJson()) {
            return view('inmuebles.partials.list_inicio', compact('inmuebles', 'favoritosIds'))->render();
        }

        return view('inmuebles.public_index', compact('inmuebles', 'favoritosIds'));
    }

    public function create()
    {
        return view('inmuebles.create');
    }

    public function show(Inmueble $inmueble)
    {
        $inmueble->load(['propietario', 'resenas.usuario', 'contratos.inquilino']);
        $imagenes = $inmueble->imagenes()->get();
        return view('inmuebles.show', compact('inmueble', 'imagenes'));
    }

    public function rentar(Inmueble $inmueble)
    {
        if ($inmueble->propietario_id === auth()->id()) {
            return redirect()->route('inmuebles.show', $inmueble)->with('error', 'No puedes rentar tu propia propiedad.');
        }

        if ($inmueble->estatus !== 'disponible') {
            return redirect()->route('inmuebles.show', $inmueble)->with('error', 'Esta propiedad ya no está disponible para rentar.');
        }

        $rentaActiva = \App\Models\Contrato::where('inquilino_id', auth()->id())
            ->where('estatus', 'activo')
            ->exists();

        if ($rentaActiva) {
            return redirect()->route('inmuebles.show', $inmueble)
                ->with('error', 'Ya tienes una propiedad rentada actualmente. No puedes rentar otra hasta finalizar tu contrato actual.');
        }

        $inmueble->load('propietario');
        return view('inmuebles.rentar', compact('inmueble'));
    }

    public function edit(Inmueble $inmueble)
    {
        if ($inmueble->propietario_id !== auth()->id() && !auth()->user()->es_admin && !auth()->user()->tieneRol('admin')) {
            abort(403);
        }

        if ($inmueble->estatus === 'rentado' || \App\Models\Contrato::where('inmueble_id', $inmueble->id)->where('estatus', 'activo')->exists()) {
            return redirect()->route('inmuebles.index')->with('error', 'No puedes editar un inmueble que ya está rentado.');
        }

        $imagenes = $inmueble->imagenes()->get();
        return view('inmuebles.edit', compact('inmueble', 'imagenes'));
    }

    public function store(Request $request)
    {
        $rules = [
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|string',
            'precio' => [
                'required',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->tipo === 'Cuarto' && $value < 300) {
                        $fail('La renta mínima para cuartos es de $300.');
                    }
                    if (in_array($request->tipo, ['Departamento', 'Casa']) && $value < 500) {
                        $fail('La renta mínima para este tipo de propiedad es de $500.');
                    }
                },
            ],
            'deposito' => [
                'nullable',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->tipo === 'Cuarto' && $value < 300) {
                        $fail('El depósito mínimo para cuartos es de $300.');
                    }
                    if (in_array($request->tipo, ['Departamento', 'Casa']) && $value < 500) {
                        $fail('El depósito mínimo para este tipo de propiedad es de $500.');
                    }
                },
            ],
            'habitaciones' => 'required|integer|min:0',
            'banos_casa' => 'required|string',
            'bano_compartido' => 'nullable|boolean',
            'metros' => 'required|numeric|min:0',
            'descripcion' => ['required', 'string', 'regex:/^[a-zA-Z0-9\s.,?!áéíóúÁÉÍÓÚñÑüÜ\r\n]*$/'],
            'direccion' => 'required|string',
            'imagenes' => 'required|array|min:1|max:10',
            'imagenes.*' => 'image|max:10240',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
            'contrato_documento' => 'nullable|file|mimes:pdf|max:5120',
        ];

        $request->validate($rules);

        try {
            DB::beginTransaction();

            $inmueble = new Inmueble();
            $inmueble->titulo = $request->nombre;
            $inmueble->descripcion = $request->descripcion;
            $inmueble->direccion = $request->direccion;
            $inmueble->tipo = $request->tipo;
            $inmueble->renta_mensual = $request->precio;
            $inmueble->deposito = $request->deposito;
            $inmueble->habitaciones = $request->habitaciones;

            $parts = explode(',', $request->banos_casa);
            $inmueble->banos = isset($parts[0]) ? (int) $parts[0] : 0;
            $inmueble->medios_banos = isset($parts[1]) ? (int) $parts[1] : 0;
            $inmueble->bano_compartido = $request->tipo === 'Cuarto' ? ($request->has('bano_compartido') ? true : false) : false;

            $inmueble->metros = $request->metros;
            $inmueble->latitud = $request->latitud;
            $inmueble->longitud = $request->longitud;
            $inmueble->propietario_id = auth()->id();

            $inmueble->ciudad = 'Ocosingo';
            $inmueble->estado = 'Chiapas';
            $inmueble->codigo_postal = '29950';

            $primeraImagen = $request->file('imagenes')[0];
            $pathPortada = $primeraImagen->store('inmuebles', 'public');
            MediaUrl::ensurePublicStorageCopy($pathPortada);
            $inmueble->imagen = $pathPortada;

            if ($request->hasFile('contrato_documento')) {
                $pathContrato = $request->file('contrato_documento')->store('contratos', 'public');
                $inmueble->contrato_documento = $pathContrato;
            }

            $inmueble->save();

            foreach ($request->file('imagenes') as $foto) {
                $path = $foto->store('inmuebles', 'public');
                MediaUrl::ensurePublicStorageCopy($path);
                DB::table('imagenes_inmuebles')->insert([
                    'inmueble_id' => $inmueble->id,
                    'ruta_imagen' => $path,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();
            return redirect()->route('inmuebles.index')->with('success', '¡Propiedad publicada correctamente!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Inmueble $inmueble)
    {
        if ($inmueble->propietario_id !== auth()->id() && !auth()->user()->es_admin && !auth()->user()->tieneRol('admin')) {
            abort(403);
        }

        if ($inmueble->estatus === 'rentado' || \App\Models\Contrato::where('inmueble_id', $inmueble->id)->where('estatus', 'activo')->exists()) {
            return redirect()->route('inmuebles.index')->with('error', 'No puedes editar un inmueble que ya está rentado.');
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|string',
            'precio' => [
                'required',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->tipo === 'Cuarto' && $value < 300) {
                        $fail('La renta mínima para cuartos es de $300.');
                    }
                    if (in_array($request->tipo, ['Departamento', 'Casa']) && $value < 500) {
                        $fail('La renta mínima para este tipo de propiedad es de $500.');
                    }
                },
            ],
            'deposito' => [
                'nullable',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->tipo === 'Cuarto' && $value < 300) {
                        $fail('El depósito mínimo para cuartos es de $300.');
                    }
                    if (in_array($request->tipo, ['Departamento', 'Casa']) && $value < 500) {
                        $fail('El depósito mínimo para este tipo de propiedad es de $500.');
                    }
                },
            ],
            'habitaciones' => 'required|integer|min:0',
            'banos_casa' => 'required|string',
            'bano_compartido' => 'nullable|boolean',
            'metros' => 'required|numeric|min:0',
            'direccion' => 'required|string',
            'contrato_documento' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        $parts = explode(',', $request->banos_casa);
        $banos = isset($parts[0]) ? (int) $parts[0] : 0;
        $medios_banos = isset($parts[1]) ? (int) $parts[1] : 0;
        $bano_compartido = $request->tipo === 'Cuarto' ? ($request->has('bano_compartido') ? true : false) : false;

        $inmueble->update([
            'titulo' => $request->nombre,
            'tipo' => $request->tipo,
            'renta_mensual' => $request->precio,
            'deposito' => $request->deposito,
            'descripcion' => $request->descripcion,
            'direccion' => $request->direccion,
            'habitaciones' => $request->habitaciones,
            'banos' => $banos,
            'medios_banos' => $medios_banos,
            'bano_compartido' => $bano_compartido,
            'metros' => $request->metros,
            'latitud' => $request->latitud,
            'longitud' => $request->longitud,
        ]);

        if ($request->hasFile('contrato_documento')) {
            $pathContrato = $request->file('contrato_documento')->store('contratos', 'public');
            $inmueble->update(['contrato_documento' => $pathContrato]);
        }

        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $foto) {
                $path = $foto->store('inmuebles', 'public');
                MediaUrl::ensurePublicStorageCopy($path);
                DB::table('imagenes_inmuebles')->insert([
                    'inmueble_id' => $inmueble->id,
                    'ruta_imagen' => $path,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return redirect()->route('inmuebles.index')->with('success', 'Propiedad actualizada con éxito.');
    }

    public function destroy(Inmueble $inmueble)
    {
        if ($inmueble->propietario_id !== auth()->id() && !auth()->user()->es_admin && !auth()->user()->tieneRol('admin')) {
            abort(403);
        }

        if ($inmueble->estatus === 'rentado' || \App\Models\Contrato::where('inmueble_id', $inmueble->id)->where('estatus', 'activo')->exists()) {
            return redirect()->route('inmuebles.index')->with('error', 'No puedes eliminar un inmueble que ya está rentado.');
        }

        $inmueble->delete();
        return redirect()->route('inmuebles.index')->with('success', 'Propiedad eliminada correctamente.');
    }
}


