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
        $search  = $request->search;
        $estatus = $request->estatus; // 'disponible' | 'rentado' | 'proceso' | null (todos)

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
            $estatus = $request->get('estatus', 'proceso'); // Default to 'proceso'
            if ($estatus === 'todos') {
                $estatus = null;
            }

            $query = Inmueble::with(['contratos' => function ($q) {
                $q->with('inquilino')->whereIn('estatus', ['pendiente_aprobacion', 'activo'])->latest();
            }])->where('propietario_id', auth()->id());

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('titulo', 'like', "%$search%")
                        ->orWhere('direccion', 'like', "%$search%")
                        ->orWhere('tipo', 'like', "%$search%");
                });
            }

            // Filtro por estatus
            if ($estatus === 'proceso') {
                // Inmuebles con contrato pendiente de aprobación
                $query->whereHas('contratos', fn($q) => $q->where('estatus', 'pendiente_aprobacion'));
            } elseif ($estatus === 'disponible') {
                $query->where('estatus', 'disponible')
                      ->whereDoesntHave('contratos', fn($q) => $q->where('estatus', 'pendiente_aprobacion'));
            } elseif ($estatus === 'rentado') {
                $query->where('estatus', 'rentado');
            }

            // Ordenamiento por defecto: proceso_renta > disponible > rentado
            // Usamos CASE en SQL para prioridad
            $query->orderByRaw("
                CASE
                    WHEN EXISTS (
                        SELECT 1 FROM contratos
                        WHERE contratos.inmueble_id = inmuebles.id
                          AND contratos.estatus = 'pendiente_aprobacion'
                    ) THEN 0
                    WHEN inmuebles.estatus = 'disponible' THEN 1
                    ELSE 2
                END ASC
            ")->orderBy('created_at', 'desc');

            $inmuebles = $query->paginate(12)->withQueryString();

            // Contadores para los filtros de tabs
            $base = Inmueble::where('propietario_id', auth()->id());
            $cuentas = [
                'total'     => (clone $base)->count(),
                'proceso'   => (clone $base)->whereHas('contratos', fn($q) => $q->where('estatus', 'pendiente_aprobacion'))->count(),
                'disponible'=> (clone $base)->where('estatus', 'disponible')
                                            ->whereDoesntHave('contratos', fn($q) => $q->where('estatus', 'pendiente_aprobacion'))->count(),
                'rentado'   => (clone $base)->where('estatus', 'rentado')->count(),
            ];

            return view('inmuebles.index', compact('inmuebles', 'estatus', 'cuentas'));
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

        // Contratos activos/pendientes — excluimos 'rechazado' y 'cancelado' del listado
        $contratos = \App\Models\Contrato::with('inmueble.propietario')
            ->where('inquilino_id', $userId)
            ->whereNotIn('estatus', ['rechazado', 'cancelado'])
            ->latest()
            ->get();

        // Detectar si hay contrato rechazado que el inquilino aún no ha visto
        // Usamos session para mostrarlo solo una vez
        $contratoRechazado = null;
        if (!session()->has('rechazo_visto_' . $userId)) {
            $contratoRechazado = \App\Models\Contrato::with('inmueble')
                ->where('inquilino_id', $userId)
                ->where('estatus', 'rechazado')
                ->latest()
                ->first();

            if ($contratoRechazado) {
                // Marcamos como visto para no mostrarlo de nuevo
                session()->put('rechazo_visto_' . $userId, true);
            }
        }

        $pagosPendientes = \App\Models\Pago::with('contrato.inmueble')
                            ->whereIn('contrato_id', $contratos->pluck('id'))
                            ->where('estatus', 'pendiente')
                            ->orderBy('anio', 'asc')
                            ->orderBy('mes', 'asc')
                            ->get();

        $historialPagos = \App\Models\Pago::with('contrato.inmueble')
                            ->whereIn('contrato_id', $contratos->pluck('id'))
                            ->where('estatus', 'pagado')
                            ->orderBy('fecha_pago', 'desc')
                            ->get();

        // Marco como visto para quitar el punto rojo de notificación
        session()->put('renta_visto_' . auth()->id(), true);

        return view('inmuebles.mis_rentas', compact('contratos', 'pagosPendientes', 'historialPagos', 'contratoRechazado'));
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
            $inmueble->deposito = $request->deposito ?: 0;
            $inmueble->habitaciones = $request->habitaciones;

            $parts = explode(',', $request->banos_casa);
            $inmueble->banos = isset($parts[0]) ? (int) $parts[0] : 0;
            $inmueble->medios_banos = isset($parts[1]) ? (int) $parts[1] : 0;
            $inmueble->bano_compartido = $request->tipo === 'Cuarto' ? ($request->has('bano_compartido') ? true : false) : false;

            $inmueble->metros = $request->metros;
            $inmueble->latitud = $request->latitud;
            $inmueble->longitud = $request->longitud;
            
            // Nuevos campos extendidos
            $inmueble->requiere_deposito = $request->requiere_deposito === 'si';
            $inmueble->tiene_cerradura_propia = $request->tiene_cerradura === 'si'; // Fallback if old name comes through
            $inmueble->cantidad_llaves = $request->cantidad_llaves ?? 0;
            $inmueble->permite_mascotas = $request->permite_mascotas === 'si';
            $inmueble->incluir_clausulas = $request->incluir_clausulas === 'si';
            $inmueble->clausulas_extra = $request->clausulas_extra ?? '';

            $inmueble->estado_mobiliario = $request->estado_mobiliario ?? 'no amueblada';
            $inmueble->tiene_estacionamiento = $request->tiene_estacionamiento == 1;
            $inmueble->momento_pago = $request->momento_pago ?? 'adelantado';
            $inmueble->dias_tolerancia = $request->dias_tolerancia ?? 0;
            $inmueble->dias_preaviso = $request->dias_preaviso ?? 30;

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

            // Sincronizar Zonas Comunes
            if ($request->tiene_zonas_comunes === 'si' && $request->has('zonas_comunes')) {
                $zonasIds = \App\Models\ZonaComun::whereIn('slug', $request->zonas_comunes)->pluck('id');
                $inmueble->zonasComunes()->sync($zonasIds);
            }

            // Sincronizar Mascotas
            if ($request->permite_mascotas === 'si' && $request->has('tipos_mascotas')) {
                $mascotasIds = \App\Models\Mascota::whereIn('slug', $request->tipos_mascotas)->pluck('id');
                $inmueble->mascotas()->sync($mascotasIds);
            }

            // Sincronizar Servicios
            if ($request->has('servicios_incluidos')) {
                foreach ($request->servicios_incluidos as $serv) {
                    $slug = \Illuminate\Support\Str::slug($serv, '_');
                    $pago = $request->pago_servicio[$slug] ?? 'inquilino';
                    \App\Models\InmuebleServicio::create([
                        'inmueble_id' => $inmueble->id,
                        'servicio' => $serv,
                        'paga' => $pago
                    ]);
                }
            }

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
            'deposito' => $request->deposito ?: 0,
            'descripcion' => $request->descripcion,
            'direccion' => $request->direccion,
            'habitaciones' => $request->habitaciones,
            'banos' => $banos,
            'medios_banos' => $medios_banos,
            'bano_compartido' => $bano_compartido,
            'metros' => $request->metros,
            'latitud' => $request->latitud,
            'longitud' => $request->longitud,
            
            // Nuevos campos extendidos
            'requiere_deposito' => $request->requiere_deposito === 'si',
            'tiene_cerradura_propia' => $request->has('tiene_cerradura') ? $request->tiene_cerradura === 'si' : false,
            'cantidad_llaves' => $request->cantidad_llaves ?? 0,
            'permite_mascotas' => $request->permite_mascotas === 'si',
            'incluir_clausulas' => $request->incluir_clausulas === 'si',
            'clausulas_extra' => $request->clausulas_extra ?? '',
            'estado_mobiliario' => $request->estado_mobiliario ?? 'no amueblada',
            'tiene_estacionamiento' => $request->tiene_estacionamiento == 1,
            'momento_pago' => $request->momento_pago ?? 'adelantado',
            'dias_tolerancia' => $request->dias_tolerancia ?? 0,
            'dias_preaviso' => $request->dias_preaviso ?? 30,
        ]);

        // Sincronizar Zonas Comunes
        if ($request->has('tiene_zonas_comunes')) {
            if ($request->tiene_zonas_comunes === 'si' && $request->has('zonas_comunes')) {
                $zonasIds = \App\Models\ZonaComun::whereIn('slug', $request->zonas_comunes)->pluck('id');
                $inmueble->zonasComunes()->sync($zonasIds);
            } else {
                $inmueble->zonasComunes()->detach();
            }
        }

        // Sincronizar Mascotas
        if ($request->has('permite_mascotas')) {
            if ($request->permite_mascotas === 'si' && $request->has('tipos_mascotas')) {
                $mascotasIds = \App\Models\Mascota::whereIn('slug', $request->tipos_mascotas)->pluck('id');
                $inmueble->mascotas()->sync($mascotasIds);
            } else {
                $inmueble->mascotas()->detach();
            }
        }

        // Sincronizar Servicios
        if ($request->has('servicios_incluidos')) {
            $inmueble->servicios()->delete(); // Limpiar viejos
            foreach ($request->servicios_incluidos as $serv) {
                $slug = \Illuminate\Support\Str::slug($serv, '_');
                $pago = isset($request->pago_servicio) && isset($request->pago_servicio[$slug]) ? $request->pago_servicio[$slug] : 'inquilino';
                \App\Models\InmuebleServicio::create([
                    'inmueble_id' => $inmueble->id,
                    'servicio' => $serv,
                    'paga' => $pago
                ]);
            }
        } else {
            $inmueble->servicios()->delete();
        }

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

    public function descargarContratoPdf(\App\Models\Contrato $contrato)
    {
        if ($contrato->inquilino_id !== auth()->id() && $contrato->propietario_id !== auth()->id() && !auth()->user()->es_admin && !auth()->user()->tieneRol('admin')) {
            abort(403);
        }
        $contrato->load('inmueble.propietario', 'inquilino');
        $pdf = Pdf::loadView('pdf.contrato', compact('contrato'));
        return $pdf->download('Contrato_Arrendo_' . $contrato->id . '.pdf');
    }

    public function descargarComprobantePdf(\App\Models\Pago $pago)
    {
        $pago->load('contrato.inmueble.propietario', 'contrato.inquilino');
        if ($pago->contrato->inquilino_id !== auth()->id() && $pago->contrato->propietario_id !== auth()->id() && !auth()->user()->es_admin && !auth()->user()->tieneRol('admin')) {
            abort(403);
        }
        $pdf = Pdf::loadView('pdf.comprobante', compact('pago'));
        return $pdf->download('Recibo_Pago_' . $pago->id . '.pdf');
    }
}


