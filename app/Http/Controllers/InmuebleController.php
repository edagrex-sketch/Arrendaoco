<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Inmueble;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use Barryvdh\DomPDF\Facade\Pdf;

class InmuebleController extends Controller
{
    /**
     * Extrae el ID de YouTube de cualquier formato de URL.
     * Soporta: youtu.be/ID, watch?v=ID, /embed/ID, /shorts/ID, /v/ID
     */
    private function extractYouTubeId(?string $url): ?string
    {
        if (!$url) return null;

        $patterns = [
            '/youtu\.be\/([a-zA-Z0-9_-]{11})/',
            '/youtube\.com\/watch\?(?:.*&)?v=([a-zA-Z0-9_-]{11})/',
            '/youtube\.com\/embed\/([a-zA-Z0-9_-]{11})/',
            '/youtube\.com\/shorts\/([a-zA-Z0-9_-]{11})/',
            '/youtube\.com\/v\/([a-zA-Z0-9_-]{11})/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }

    /**
     * Convierte duración ISO 8601 (PT3M45S) a formato legible (3:45)
     */
    private function formatDuration(string $iso): string
    {
        preg_match('/PT(?:(\d+)H)?(?:(\d+)M)?(?:(\d+)S)?/', $iso, $m);
        $h = (int)($m[1] ?? 0);
        $m = (int)($m[2] ?? 0);
        $s = (int)($m[3] ?? 0);

        if ($h > 0) {
            return sprintf('%d:%02d:%02d', $h, $m, $s);
        }
        return sprintf('%d:%02d', $m, $s);
    }

    /**
     * Obtiene metadata completa del video usando YouTube Data API v3.
     * Si no hay API Key configurada, hace fallback a oEmbed (gratis).
     *
     * Devuelve:
     *  titulo, canal, descripcion, duracion, vistas, likes, publicado_en, thumbnail, raw_duracion
     */
    private function getYouTubeMeta(string $videoId): ?array
    {
        $apiKey = config('services.youtube.api_key');

        // ── Con API Key: YouTube Data API v3 (completo) ──────────────────────
        if ($apiKey) {
            try {
                $response = Http::timeout(8)->get('https://www.googleapis.com/youtube/v3/videos', [
                    'id'   => $videoId,
                    'key'  => $apiKey,
                    'part' => 'snippet,contentDetails,statistics',
                ]);

                if ($response->successful()) {
                    $items = $response->json('items');

                    if (!empty($items)) {
                        $item       = $items[0];
                        $snippet    = $item['snippet']          ?? [];
                        $details    = $item['contentDetails']   ?? [];
                        $stats      = $item['statistics']       ?? [];
                        $rawDuration = $details['duration'] ?? 'PT0S';

                        // Elegir la mejor thumbnail disponible
                        $thumbs    = $snippet['thumbnails'] ?? [];
                        $thumbnail = $thumbs['maxres']['url']
                            ?? $thumbs['high']['url']
                            ?? $thumbs['medium']['url']
                            ?? "https://img.youtube.com/vi/{$videoId}/hqdefault.jpg";

                        return [
                            'titulo'         => $snippet['title']                ?? null,
                            'canal'          => $snippet['channelTitle']         ?? null,
                            'descripcion'    => mb_substr($snippet['description'] ?? '', 0, 500),
                            'duracion'       => $this->formatDuration($rawDuration),
                            'raw_duracion'   => $rawDuration,
                            'vistas'         => (int)($stats['viewCount']        ?? 0),
                            'likes'          => (int)($stats['likeCount']        ?? 0),
                            'publicado_en'   => $snippet['publishedAt']          ?? null,
                            'thumbnail'      => $thumbnail,
                        ];
                    }
                }
            } catch (\Exception $e) {
                \Log::warning("YouTube API v3 falló para {$videoId}: " . $e->getMessage());
            }
        }

        // ── Fallback: oEmbed gratuito (sin API Key) ───────────────────────────
        try {
            $url      = "https://www.youtube.com/oembed?url=https://www.youtube.com/watch?v={$videoId}&format=json";
            $response = Http::timeout(5)->get($url);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'titulo'       => $data['title']          ?? null,
                    'canal'        => $data['author_name']    ?? null,
                    'descripcion'  => null,
                    'duracion'     => null,
                    'raw_duracion' => null,
                    'vistas'       => null,
                    'likes'        => null,
                    'publicado_en' => null,
                    'thumbnail'    => "https://img.youtube.com/vi/{$videoId}/hqdefault.jpg",
                ];
            }
        } catch (\Exception $e) {
            // falla silenciosa
        }

        return null;
    }

    /**
     * Construye el array de datos a guardar en la BD a partir de la metadata.
     */
    private function buildVideoData(string $videoUrl, string $videoId, ?array $meta): array
    {
        return [
            'video_youtube'       => $videoUrl,
            'video_youtube_id'    => $videoId,
            'video_thumbnail'     => $meta['thumbnail']      ?? null,
            'video_titulo'        => $meta['titulo']         ?? null,
            'video_canal'         => $meta['canal']          ?? null,
            'video_descripcion'   => $meta['descripcion']    ?? null,
            'video_duracion'      => $meta['duracion']       ?? null,
            'video_vistas'        => $meta['vistas']         ?? null,
            'video_likes'         => $meta['likes']          ?? null,
            'video_publicado_en'  => isset($meta['publicado_en'])
                ? \Carbon\Carbon::parse($meta['publicado_en'])->toDateTimeString()
                : null,
            'video_actualizado_en'=> now()->toDateTimeString(),
        ];
    }

    /**
     * Refresca los datos del video de YouTube para un inmueble existente (llamada AJAX).
     */
    public function refreshVideo(Inmueble $inmueble)
    {
        if ($inmueble->propietario_id !== auth()->id() && !auth()->user()->es_admin) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        if (!$inmueble->video_youtube_id) {
            return response()->json(['error' => 'No hay video asociado'], 422);
        }

        $meta = $this->getYouTubeMeta($inmueble->video_youtube_id);

        if (!$meta) {
            return response()->json(['error' => 'No se pudo obtener información del video'], 422);
        }

        $inmueble->update($this->buildVideoData(
            $inmueble->video_youtube,
            $inmueble->video_youtube_id,
            $meta
        ));

        return response()->json([
            'success' => true,
            'data'    => [
                'titulo'    => $inmueble->video_titulo,
                'canal'     => $inmueble->video_canal,
                'vistas'    => number_format($inmueble->video_vistas),
                'likes'     => number_format($inmueble->video_likes),
                'duracion'  => $inmueble->video_duracion,
            ]
        ]);
    }

    public function index(Request $request)
    {
        $search = $request->search;

        if (auth()->user()->es_admin || auth()->user()->tieneRol('admin')) {
            $query = Inmueble::with('propietario');

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('titulo', 'like', "%$search%")
                      ->orWhere('direccion', 'like', "%$search%")
                      ->orWhere('tipo', 'like', "%$search%")
                      ->orWhereHas('propietario', function($sq) use ($search) {
                          $sq->where('nombre', 'like', "%$search%")
                             ->orWhere('email', 'like', "%$search%");
                      });
                });
            }

            $inmuebles = $query->paginate(10)->withQueryString();
            return view('admin.inmuebles.index', compact('inmuebles'));
        } else {
            $query = Inmueble::where('propietario_id', auth()->id());
            
            if ($search) {
                $query->where(function($q) use ($search) {
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
            $query->where(function($q) use ($request) {
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

        return view('inmuebles.public_index', compact('inmuebles', 'favoritosIds'));
    }

    public function create()
    {
        return view('inmuebles.create');
    }

    public function show(Inmueble $inmueble)
    {
        $inmueble->load(['propietario', 'resenas.usuario']);
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
            'nombre'        => 'required|string|max:255',
            'tipo'          => 'required|string',
            'precio'        => [
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
            'deposito'      => [
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
            'habitaciones'      => 'required|integer|min:0',
            'banos_casa'        => 'required|string',
            'bano_compartido'   => 'nullable|boolean',
            'metros'            => 'required|numeric|min:0',
            'descripcion'   => ['required', 'string', 'regex:/^[a-zA-Z0-9\s.,?!áéíóúÁÉÍÓÚñÑüÜ\r\n]*$/'],
            'direccion'     => 'required|string',
            'imagenes'      => 'required|array|min:1|max:10',
            'imagenes.*'    => 'image|max:10240',
            'latitud'       => 'nullable|numeric',
            'longitud'      => 'nullable|numeric',
            'video_youtube' => 'nullable|url|max:255',
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
            $inmueble->banos = isset($parts[0]) ? (int)$parts[0] : 0;
            $inmueble->medios_banos = isset($parts[1]) ? (int)$parts[1] : 0;
            $inmueble->bano_compartido = $request->tipo === 'Cuarto' ? ($request->has('bano_compartido') ? true : false) : false;

            $inmueble->metros = $request->metros;
            $inmueble->latitud = $request->latitud;
            $inmueble->longitud = $request->longitud;
            $inmueble->propietario_id = auth()->id();

            // 🎬 Procesamiento de video de YouTube (API v3 completa)
            $videoUrl = $request->input('video_youtube');
            $videoId  = $this->extractYouTubeId($videoUrl);
            if ($videoId) {
                $meta = $this->getYouTubeMeta($videoId);
                foreach ($this->buildVideoData($videoUrl, $videoId, $meta) as $col => $val) {
                    $inmueble->$col = $val;
                }
            }
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
            'nombre'        => 'required|string|max:255',
            'tipo'          => 'required|string',
            'precio'        => [
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
            'deposito'      => [
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
            'habitaciones'      => 'required|integer|min:0',
            'banos_casa'        => 'required|string',
            'bano_compartido'   => 'nullable|boolean',
            'metros'            => 'required|numeric|min:0',
            'descripcion'   => ['required', 'string', 'regex:/^[a-zA-Z0-9\s.,?!áéíóúÁÉÍÓÚñÑüÜ\r\n]*$/'],
            'direccion'     => 'required|string',
            'video_youtube' => 'nullable|url|max:255',
        ]);

        // 🎬 Procesamiento de video de YouTube en edición
        $videoUrl = $request->input('video_youtube');
        $videoId  = $this->extractYouTubeId($videoUrl);
        $videoData = [
            'video_youtube'    => null,
            'video_youtube_id' => null,
            'video_thumbnail'  => null,
            'video_titulo'     => null,
        ];
        if ($videoId) {
            $meta = $this->getYouTubeMeta($videoId);
            $videoData = [
                'video_youtube'    => $videoUrl,
                'video_youtube_id' => $videoId,
                'video_thumbnail'  => $meta['thumbnail'] ?? null,
                'video_titulo'     => $meta['titulo'] ?? null,
            ];
        }

        $parts = explode(',', $request->banos_casa);
        $banos = isset($parts[0]) ? (int)$parts[0] : 0;
        $medios_banos = isset($parts[1]) ? (int)$parts[1] : 0;
        $bano_compartido = $request->tipo === 'Cuarto' ? ($request->has('bano_compartido') ? true : false) : false;

        $inmueble->update(array_merge([
            'titulo'       => $request->nombre,
            'tipo'         => $request->tipo,
            'renta_mensual'=> $request->precio,
            'deposito'     => $request->deposito,
            'descripcion'  => $request->descripcion,
            'direccion'    => $request->direccion,
            'habitaciones' => $request->habitaciones,
            'banos'        => $banos,
            'medios_banos' => $medios_banos,
            'bano_compartido' => $bano_compartido,
            'metros'       => $request->metros,
            'latitud'      => $request->latitud,
            'longitud'     => $request->longitud,
        ], $videoData));

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