<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inmueble;
use Illuminate\Support\Facades\DB; // Necesario para transacciones
use Illuminate\Support\Facades\Storage;

use Barryvdh\DomPDF\Facade\Pdf;

class InmuebleController extends Controller
{
    public function index()
    {
        if (auth()->user()->es_admin || auth()->user()->tieneRol('admin')) {
            $inmuebles = Inmueble::with('propietario')->get();
            return view('admin.inmuebles.index', compact('inmuebles'));
        } else {
            $inmuebles = Inmueble::where('propietario_id', auth()->id())->get();
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

    public function home()
    {
        $inmuebles = Inmueble::where('estatus', 'disponible')->latest()->paginate(9);
        
        $favoritosIds = [];
        if (auth()->check()) {
            $favoritosIds = auth()->user()->favoritos()->pluck('inmueble_id')->toArray();
        }

        return view('inicio', compact('inmuebles', 'favoritosIds'));
    }

    public function publicSearch(Request $request)
    {
        $query = Inmueble::where('estatus', 'disponible');

        // Filtro por ubicación (título, dirección o ciudad)
        if ($request->filled('ubicacion')) {
            $query->where(function($q) use ($request) {
                $q->where('titulo', 'like', '%' . $request->ubicacion . '%')
                  ->orWhere('direccion', 'like', '%' . $request->ubicacion . '%')
                  ->orWhere('ciudad', 'like', '%' . $request->ubicacion . '%');
            });
        }

        // Solo permitir filtros avanzados si el usuario está autenticado
        if (auth()->check()) {
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
        }

        $inmuebles = $query->paginate(12);

        $favoritosIds = [];
        if (auth()->check()) {
            $favoritosIds = auth()->user()->favoritos()->pluck('inmueble_id')->toArray();
        }

        return view('inmuebles.public_index', compact('inmuebles', 'favoritosIds'));
    }

    public function create()
    {
        return view('inmuebles.create');
    }

    public function show(Inmueble $inmueble)
    {
        $imagenes = DB::table('imagenes_inmuebles')->where('inmueble_id', $inmueble->id)->get();
        return view('inmuebles.show', compact('inmueble', 'imagenes'));
    }

    public function edit(Inmueble $inmueble)
    {
        if ($inmueble->propietario_id !== auth()->id() && !auth()->user()->es_admin && !auth()->user()->tieneRol('admin')) {
            abort(403);
        }

        $imagenes = DB::table('imagenes_inmuebles')->where('inmueble_id', $inmueble->id)->get();
        return view('inmuebles.edit', compact('inmueble', 'imagenes'));
    }

    public function store(Request $request)
    {
        $rules = [
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|string',
            'precio' => 'required|numeric',
            'habitaciones' => 'required|integer',
            'banos' => 'required|integer',
            'metros' => 'required|numeric',
            'descripcion' => 'required|string',
            'direccion' => 'required|string',
            'imagenes' => 'required|array|min:1|max:10',
            'imagenes.*' => 'image|max:10240',
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
            $inmueble->habitaciones = $request->habitaciones;
            $inmueble->banos = $request->banos;
            $inmueble->metros = $request->metros;
            $inmueble->propietario_id = auth()->id();
            $inmueble->ciudad = 'Ocosingo';
            $inmueble->estado = 'Chiapas';
            $inmueble->codigo_postal = '29950';

            $primeraImagen = $request->file('imagenes')[0];
            $pathPortada = $primeraImagen->store('inmuebles', 'public');
            $inmueble->imagen = '/storage/' . $pathPortada;

            $inmueble->save();

            foreach ($request->file('imagenes') as $foto) {
                $path = $foto->store('inmuebles', 'public');
                DB::table('imagenes_inmuebles')->insert([
                    'inmueble_id' => $inmueble->id,
                    'ruta_imagen' => '/storage/' . $path,
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

        $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|string',
            'precio' => 'required|numeric',
            'descripcion' => 'required|string',
            'direccion' => 'required|string',
        ]);

        $inmueble->update([
            'titulo' => $request->nombre,
            'tipo' => $request->tipo,
            'renta_mensual' => $request->precio,
            'descripcion' => $request->descripcion,
            'direccion' => $request->direccion,
            'habitaciones' => $request->habitaciones,
            'banos' => $request->banos,
            'metros' => $request->metros,
        ]);

        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $foto) {
                $path = $foto->store('inmuebles', 'public');
                DB::table('imagenes_inmuebles')->insert([
                    'inmueble_id' => $inmueble->id,
                    'ruta_imagen' => '/storage/' . $path,
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

        $inmueble->delete();
        return redirect()->route('inmuebles.index')->with('success', 'Propiedad eliminada correctamente.');
    }
}