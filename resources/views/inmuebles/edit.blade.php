@extends('layouts.app')

@section('title', 'Editar Inmueble')

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-12">
        <div class="mb-10">
            <h1 class="text-4xl font-extrabold text-[#003049] tracking-tight">Editar Anuncio</h1>
            <p class="text-muted-foreground mt-2 text-lg">Modifica los detalles de tu publicación.</p>
        </div>

        <form action="{{ route('inmuebles.update', $inmueble) }}" method="POST" enctype="multipart/form-data"
            class="space-y-8">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100 space-y-6">
                {{-- Título --}}
                <div>
                    <label class="block text-sm font-bold text-[#003049] uppercase tracking-wider mb-2">Título del
                        Anuncio</label>
                    <input type="text" name="nombre" value="{{ $inmueble->titulo }}" required
                        class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#003049] focus:border-transparent outline-none transition-all">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Tipo --}}
                    <div>
                        <label class="block text-sm font-bold text-[#003049] uppercase tracking-wider mb-2">Tipo de
                            Inmueble</label>
                        <select name="tipo" id="tipo-select" onchange="updateMinVal()"
                            class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#003049] outline-none">
                            <option value="Casa" {{ $inmueble->tipo == 'Casa' ? 'selected' : '' }}>Casa</option>
                            <option value="Departamento" {{ $inmueble->tipo == 'Departamento' ? 'selected' : '' }}>
                                Departamento</option>
                            <option value="Cuarto" {{ $inmueble->tipo == 'Cuarto' ? 'selected' : '' }}>Cuarto</option>
                        </select>
                    </div>

                    {{-- Precio --}}
                    <div>
                        <label class="block text-sm font-bold text-[#003049] uppercase tracking-wider mb-2">Renta Mensual
                            ($)</label>
                        <input type="number" name="precio" id="precio-input" value="{{ $inmueble->renta_mensual }}" required
                            min="{{ $inmueble->tipo === 'Cuarto' ? 300 : 500 }}" oninput="if(this.value < 0) this.value = '';"
                            class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#003049] outline-none">
                    </div>

                    {{-- Depósito --}}
                    <div>
                        <label class="block text-sm font-bold text-[#003049] uppercase tracking-wider mb-2">Depósito
                            ($) <span class="text-xs font-normal text-muted-foreground">(Opcional)</span></label>
                        <input type="number" name="deposito" id="deposito-input" value="{{ $inmueble->deposito }}"
                            min="{{ $inmueble->tipo === 'Cuarto' ? 300 : 500 }}" oninput="if(this.value < 0) this.value = '';"
                            class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#003049] outline-none">
                    </div>
                </div>

                {{-- Dirección y Buscador --}}
                <div>
                    <label class="block text-sm font-bold text-[#003049] uppercase tracking-wider mb-2">Dirección</label>
                    <div class="flex gap-2 mb-4">
                        <input type="text" name="direccion" id="direccion-input" value="{{ $inmueble->direccion }}"
                            required
                            class="flex-1 px-5 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#003049] outline-none">
                        <button type="button" onclick="buscarDireccion()"
                            class="bg-[#003049] text-white px-6 py-3 rounded-xl hover:bg-[#003049]/90 transition-all font-bold flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                            </svg> Buscar en mapa
                        </button>
                    </div>
                </div>

                {{-- Selector de Mapa --}}
                <div>
                    <label class="block text-sm font-bold text-[#003049] uppercase tracking-wider mb-2">Ubicación en el
                        mapa</label>
                    <p class="text-xs text-muted-foreground mb-3 font-medium">Puedes mover el marcador manualmente si la
                        búsqueda no fue exacta.</p>
                    <div id="map-edit" class="w-full h-[350px] rounded-2xl border border-slate-100 shadow-inner z-0"></div>
                    <input type="hidden" name="latitud" id="lat-input" value="{{ $inmueble->latitud }}">
                    <input type="hidden" name="longitud" id="longitud-input" value="{{ $inmueble->longitud }}">
                </div>

                {{-- Leaflet y Scripts de Mapa --}}
                <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
                <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                <script>
                    var map, marker;
                    document.addEventListener('DOMContentLoaded', function() {
                        var initialLat = {{ $inmueble->latitud ?? 16.9068 }};
                        var initialLng = {{ $inmueble->longitud ?? -92.0941 }};

                        const osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            maxZoom: 19,
                            attribution: '&copy; OpenStreetMap contributors'
                        });

                        const satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                            attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
                        });

                        map = L.map('map-edit', {
                            center: [initialLat, initialLng],
                            zoom: 15,
                            layers: [osm]
                        });

                        const baseMaps = {
                            'Callejero': osm,
                            'Satélite': satellite
                        };
                        L.control.layers(baseMaps, null, { collapsed: false }).addTo(map);
                        L.control.scale({ imperial: false }).addTo(map);

                        marker = L.marker([initialLat, initialLng], {
                            draggable: true
                        }).addTo(map);

                        marker.on('dragend', function(event) {
                            var position = marker.getLatLng();
                            actualizarCampos(position.lat, position.lng);
                        });

                        map.on('click', function(e) {
                            actualizarCampos(e.latlng.lat, e.latlng.lng, true);
                        });

                        setTimeout(() => {
                            map.invalidateSize();
                        }, 500);
                    });

                    function actualizarCampos(lat, lng, zoom = false) {
                        marker.setLatLng([lat, lng]);
                        document.getElementById('lat-input').value = lat;
                        document.getElementById('longitud-input').value = lng;
                        if (zoom) map.setView([lat, lng], map.getZoom());
                    }

                    async function buscarDireccion() {
                        const direccionRaw = document.getElementById('direccion-input').value;
                        if (!direccionRaw) return;

                        const query = encodeURIComponent(direccionRaw + ", Ocosingo, Chiapas, México");
                        const btn = event.currentTarget;
                        const originalText = btn.innerHTML;

                        btn.innerHTML = "Buscando...";
                        btn.disabled = true;

                        try {
                            const response = await fetch(
                                `https://nominatim.openstreetmap.org/search?format=json&q=${query}&limit=1`);
                            const data = await response.json();

                            if (data && data.length > 0) {
                                const lat = parseFloat(data[0].lat);
                                const lon = parseFloat(data[0].lon);
                                actualizarPin(lat, lon, 17);
                            } else {
                                alert("No encontramos la ubicación exacta. Prueba moviendo el marcador manualmente en el mapa.");
                            }
                        } catch (error) {
                            console.error("Error:", error);
                        } finally {
                            btn.innerHTML = originalText;
                            btn.disabled = false;
                        }
                    }
                </script>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label
                            class="block text-sm font-bold text-[#003049] uppercase tracking-wider mb-2">Habitaciones</label>
                        <input type="number" name="habitaciones" value="{{ $inmueble->habitaciones }}" required
                            min="0" oninput="if(this.value < 0) this.value = '';"
                            class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#003049] outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-[#003049] uppercase tracking-wider mb-2">Metros
                            (m²)</label>
                        <input type="number" name="metros" value="{{ $inmueble->metros }}" required
                            min="0" oninput="if(this.value < 0) this.value = '';"
                            class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#003049] outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label class="block text-sm font-bold text-[#003049] uppercase tracking-wider mb-2">Baños</label>
                        @php $banoCombo = $inmueble->banos . ',' . $inmueble->medios_banos; @endphp
                        <select id="banos-casa-input" name="banos_casa" required class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#003049] outline-none">
                            <option value="0,1" {{ $banoCombo === '0,1' ? 'selected' : '' }}>Medio Baño</option>
                            <option value="1,0" {{ $banoCombo === '1,0' ? 'selected' : '' }}>1 Baño Completo</option>
                            <option value="1,1" {{ $banoCombo === '1,1' ? 'selected' : '' }}>1 Baño Completo y Medio Baño</option>
                            <option value="2,0" {{ $banoCombo === '2,0' ? 'selected' : '' }}>2 Baños Completos</option>
                            <option value="2,1" {{ $banoCombo === '2,1' ? 'selected' : '' }}>2 Baños Completos y Medio Baño</option>
                            <option value="3,0" {{ $banoCombo === '3,0' ? 'selected' : '' }}>3 Baños Completos</option>
                            <option value="3,1" {{ $banoCombo === '3,1' ? 'selected' : '' }}>3 Baños Completos y Medio Baño</option>
                            <option value="4,0" {{ $banoCombo === '4,0' ? 'selected' : '' }}>4 Baños o más</option>
                        </select>
                    </div>
                </div>

                <div id="bano-compartido-wrapper" class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-2 mb-6" style="display: {{ $inmueble->tipo === 'Cuarto' ? 'grid' : 'none' }};">
                    <div class="hidden md:block"></div>
                    <div class="flex items-center sm:-mt-3">
                        <label class="flex items-center gap-2 text-sm font-medium text-slate-500 cursor-pointer">
                            <input type="checkbox" name="bano_compartido" value="1" {{ $inmueble->bano_compartido ? 'checked' : '' }}
                                class="w-4 h-4 rounded border-slate-300 text-[#003049] focus:ring-[#003049]">
                            ¿El baño es compartido?
                        </label>
                    </div>
                </div>

                {{-- Descripción --}}
                <div>
                    <label class="block text-sm font-bold text-[#003049] uppercase tracking-wider mb-2">Descripción</label>
                    <textarea name="descripcion" rows="5" required
                        oninput="this.value = this.value.replace(/[^a-zA-Z0-9\s.,?!áéíóúÁÉÍÓÚñÑüÜ\r\n]/g, '')"
                        class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#003049] outline-none transition-all">{{ $inmueble->descripcion }}</textarea>
                </div>

                {{-- Video Tour de YouTube --}}
                <div class="bg-red-50/40 border border-red-100 rounded-2xl p-6">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="text-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-6 h-6 text-red-500">
                                <path d="M3.25 4A2.25 2.25 0 001 6.25v7.5A2.25 2.25 0 003.25 16h7.5A2.25 2.25 0 0013 13.75v-7.5A2.25 2.25 0 0010.75 4h-7.5zM19 4.75a.75.75 0 00-1.28-.53l-3 3a.75.75 0 00-.22.53v4.5c0 .199.079.39.22.53l3 3a.75.75 0 001.28-.53V4.75z" />
                            </svg>
                        </span>
                        <label class="text-sm font-bold text-slate-700">Video Tour de YouTube
                            <span class="text-xs text-muted-foreground font-normal">(Opcional)</span></label>
                    </div>
                    <p class="text-xs text-slate-500 mb-3">Pega el enlace de YouTube del recorrido de tu propiedad. Funciona con cualquier formato: youtube.com/watch?v=..., youtu.be/..., Shorts.</p>
                    <input type="url" name="video_youtube" id="yt-url-input-edit"
                        value="{{ $inmueble->video_youtube }}"
                        placeholder="https://www.youtube.com/watch?v=..."
                        oninput="previewYoutubeEdit(this.value)"
                        class="w-full rounded-lg border bg-white border-slate-200 py-3 px-4 focus:ring-2 focus:ring-red-400/30 focus:border-red-400 transition-all text-sm">

                    @if($inmueble->video_youtube_id)
                        <p class="text-xs text-green-600 font-bold mt-2 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 inline mr-1">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                            </svg> Video actual: <span class="font-normal text-slate-500 ml-1">{{ $inmueble->video_titulo }}</span>
                        </p>
                    @endif

                    {{-- Preview en vivo --}}
                    <div id="yt-preview-edit" class="mt-4 {{ $inmueble->video_youtube_id ? '' : 'hidden' }}">
                        <div class="relative w-full rounded-xl overflow-hidden shadow-md" style="padding-top: 56.25%;">
                            <iframe id="yt-iframe-edit"
                                src="{{ $inmueble->video_youtube_id ? 'https://www.youtube.com/embed/' . $inmueble->video_youtube_id : '' }}"
                                class="absolute inset-0 w-full h-full"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen>
                            </iframe>
                        </div>
                        <p class="text-xs text-green-600 font-bold mt-2 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 inline mr-1">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                            </svg> Video válido &mdash; se mostrará en tu anuncio
                        </p>
                    </div>
                    <div id="yt-error-edit" class="mt-3 hidden">
                        <p class="text-xs text-red-500 font-bold flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 inline mr-1">
                                <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                            </svg> No pudimos detectar un ID de YouTube válido en ese enlace.
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex gap-4">
                <button type="submit"
                    class="flex-1 bg-[#003049] text-white font-black py-4 rounded-2xl shadow-xl hover:bg-[#003049]/90 transition-all uppercase tracking-widest">
                    Guardar Cambios
                </button>
                <a href="{{ route('inmuebles.index') }}"
                    class="flex-1 bg-white text-muted-foreground font-bold py-4 rounded-2xl border border-slate-200 hover:bg-slate-50 transition-all text-center uppercase tracking-widest">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    function extractYouTubeIdEdit(url) {
        const patterns = [
            /youtu\.be\/([a-zA-Z0-9_-]{11})/,
            /youtube\.com\/watch\?v=([a-zA-Z0-9_-]{11})/,
            /youtube\.com\/embed\/([a-zA-Z0-9_-]{11})/,
            /youtube\.com\/shorts\/([a-zA-Z0-9_-]{11})/,
            /youtube\.com\/v\/([a-zA-Z0-9_-]{11})/,
        ];
        for (const pattern of patterns) {
            const match = url.match(pattern);
            if (match) return match[1];
        }
        return null;
    }

    function previewYoutubeEdit(url) {
        const preview = document.getElementById('yt-preview-edit');
        const error   = document.getElementById('yt-error-edit');
        const iframe  = document.getElementById('yt-iframe-edit');

        if (!url) {
            preview.classList.add('hidden');
            error.classList.add('hidden');
            return;
        }

        const id = extractYouTubeIdEdit(url);
        if (id) {
            iframe.src = `https://www.youtube.com/embed/${id}`;
            preview.classList.remove('hidden');
            error.classList.add('hidden');
        } else {
            preview.classList.add('hidden');
            error.classList.remove('hidden');
        }
    }

    function updateMinVal() {
        const tipo = document.getElementById('tipo-select').value;
        const minVal = tipo === 'Cuarto' ? 300 : 500;
        document.getElementById('precio-input').min = minVal;
        document.getElementById('deposito-input').min = minVal;
        const banoCompartidoWrapper = document.getElementById('bano-compartido-wrapper');
        
        if (tipo === 'Cuarto') {
            if (banoCompartidoWrapper) banoCompartidoWrapper.style.display = 'grid';
        } else {
            if (banoCompartidoWrapper) banoCompartidoWrapper.style.display = 'none';
        }
    }
</script>
@endpush
