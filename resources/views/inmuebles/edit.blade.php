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
                        <select name="tipo"
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
                        <input type="number" name="precio" value="{{ $inmueble->renta_mensual }}" required
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
                        {{-- 
                        <button type="button" onclick="buscarDireccion()"
                            class="bg-[#003049] text-white px-6 py-3 rounded-xl hover:bg-[#003049]/90 transition-all font-bold flex items-center gap-2">
                            🔍 Buscar en mapa
                        </button>
                        --}}
                    </div>
                </div>

                {{-- 🗺️ Selector de Mapa (SUSPENDIDO) --}}
                {{-- 
                <div>
                    <label class="block text-sm font-bold text-[#003049] uppercase tracking-wider mb-2">Ubicación en el
                        mapa</label>
                    <p class="text-xs text-muted-foreground mb-3 font-medium">Puedes mover el marcador manualmente si la
                        búsqueda no fue exacta.</p>
                    <div id="map-edit" class="w-full h-[350px] rounded-2xl border border-slate-100 shadow-inner z-0"></div>
                    <input type="hidden" name="latitud" id="lat-input" value="{{ $inmueble->latitud }}">
                    <input type="hidden" name="longitud" id="longitud-input" value="{{ $inmueble->longitud }}">
                </div>
                --}}

                {{-- Leaflet y Scripts de Mapa (SUSPENDIDO) --}}
                {{-- 
                <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
                <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                <script>
                    var map, marker;
                    document.addEventListener('DOMContentLoaded', function() {
                        var initialLat = {{ $inmueble->latitud ?? 16.9068 }};
                        var initialLng = {{ $inmueble->longitud ?? -92.0941 }};

                        map = L.map('map-edit').setView([initialLat, initialLng], 15);

                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '&copy; OpenStreetMap contributors'
                        }).addTo(map);

                        marker = L.marker([initialLat, initialLng]).addTo(map);

                        map.on('click', function(e) {
                            actualizarPin(e.latlng.lat, e.latlng.lng);
                        });

                        setTimeout(() => {
                            map.invalidateSize();
                        }, 500);
                    });

                    function actualizarPin(lat, lng, zoom = null) {
                        marker.setLatLng([lat, lng]);
                        document.getElementById('lat-input').value = lat;
                        document.getElementById('longitud-input').value = lng;
                        if (zoom) map.setView([lat, lng], zoom);
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
                                alert("No encontramos la ubicación exacta. Prueba moviendo el marcador manualmente en el mapa. 🐶");
                            }
                        } catch (error) {
                            console.error("Error:", error);
                        } finally {
                            btn.innerHTML = originalText;
                            btn.disabled = false;
                        }
                    }
                </script>
                --}}

                <div class="grid grid-cols-3 gap-6">
                    <div>
                        <label
                            class="block text-sm font-bold text-[#003049] uppercase tracking-wider mb-2">Habitaciones</label>
                        <input type="number" name="habitaciones" value="{{ $inmueble->habitaciones }}"
                            class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#003049] outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-[#003049] uppercase tracking-wider mb-2">Baños</label>
                        <input type="number" name="banos" value="{{ $inmueble->banos }}"
                            class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#003049] outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-[#003049] uppercase tracking-wider mb-2">Metros
                            (m²)</label>
                        <input type="number" name="metros" value="{{ $inmueble->metros }}"
                            class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#003049] outline-none">
                    </div>
                </div>

                {{-- Descripción --}}
                <div>
                    <label class="block text-sm font-bold text-[#003049] uppercase tracking-wider mb-2">Descripción</label>
                    <textarea name="descripcion" rows="5" required
                        class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#003049] outline-none transition-all">{{ $inmueble->descripcion }}</textarea>
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
