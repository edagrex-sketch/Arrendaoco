@extends('layouts.app')

@section('title', 'Editar Inmueble')

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-12">
        <div class="mb-10">
            <h1 class="text-4xl font-extrabold text-[#003049] tracking-tight">Editar Anuncio</h1>
            <p class="text-muted-foreground mt-2 text-lg">Modifica los detalles de tu publicación.</p>
        </div>

        {{-- Aviso de campos protegidos --}}
        <div class="mb-8 flex items-start gap-4 bg-[#003049]/5 border border-[#003049]/20 rounded-2xl px-6 py-4">
            <div class="mt-0.5 flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#003049]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-bold text-[#003049] mb-0.5">Campos protegidos</p>
                <p class="text-sm text-slate-600 leading-relaxed">Algunos datos estructurales del inmueble (tipo, dirección, superficie y habitaciones) <strong>no se pueden modificar</strong> ya que forman parte del perfil legal de la propiedad y pueden estar vinculados a contratos. Si necesitas corregirlos, contacta al soporte.</p>
            </div>
        </div>

        <form action="{{ route('inmuebles.update', ['inmueble' => $inmueble] + (request()->has('return_to_contrato') ? ['return_to_contrato' => request('return_to_contrato')] : [])) }}" method="POST" enctype="multipart/form-data"
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
                    {{-- Tipo (PROTEGIDO) --}}
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-bold text-[#003049] uppercase tracking-wider">Tipo de Inmueble</label>
                            <span class="inline-flex items-center gap-1 text-xs font-semibold text-[#003049]/70 bg-[#003049]/8 border border-[#003049]/20 rounded-full px-2.5 py-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                                Protegido
                            </span>
                        </div>
                        <div class="w-full px-5 py-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-600 font-medium cursor-not-allowed select-none flex items-center justify-between">
                            <span>{{ $inmueble->tipo }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        </div>
                        <input type="hidden" name="tipo" id="tipo-select" value="{{ $inmueble->tipo }}">
                    </div>

                    {{-- Precio --}}
                    <div>
                        <label class="block text-sm font-bold text-[#003049] uppercase tracking-wider mb-2">Renta Mensual
                            ($)</label>
                        <input type="number" name="precio" id="precio-input" value="{{ $inmueble->renta_mensual }}" required
                            min="{{ $inmueble->tipo === 'Cuarto' ? 300 : 500 }}"
                            oninput="if(this.value < 0) this.value = '';"
                            class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#003049] outline-none">
                    </div>

                    {{-- Depósito --}}
                    <div>
                        <label class="block text-sm font-bold text-[#003049] uppercase tracking-wider mb-2">Depósito
                            ($) <span class="text-xs font-normal text-muted-foreground">(Opcional)</span></label>
                        <input type="number" name="deposito" id="deposito-input" value="{{ $inmueble->deposito }}"
                            min="{{ $inmueble->tipo === 'Cuarto' ? 300 : 500 }}"
                            oninput="if(this.value < 0) this.value = '';"
                            class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#003049] outline-none">
                    </div>
                </div>

                {{-- Dirección y Buscador (PROTEGIDA) --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-sm font-bold text-[#003049] uppercase tracking-wider">Dirección</label>
                        <span class="inline-flex items-center gap-1 text-xs font-semibold text-[#003049]/70 bg-[#003049]/8 border border-[#003049]/20 rounded-full px-2.5 py-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                            Protegido
                        </span>
                    </div>
                    <div class="flex gap-2 mb-4">
                        <div class="flex-1 px-5 py-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-600 font-medium cursor-not-allowed select-none flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            <span>{{ $inmueble->direccion }}</span>
                        </div>
                        <div class="bg-slate-200 text-slate-400 px-6 py-3 rounded-xl font-bold flex items-center gap-2 cursor-not-allowed" title="La dirección está protegida">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                            Protegida
                        </div>
                    </div>
                    <input type="hidden" name="direccion" id="direccion-input" value="{{ $inmueble->direccion }}">
                </div>

                {{-- Selector de Mapa (SOLO LECTURA) --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-sm font-bold text-[#003049] uppercase tracking-wider">Ubicación en el mapa</label>
                        <span class="inline-flex items-center gap-1 text-xs font-semibold text-[#003049]/70 bg-[#003049]/8 border border-[#003049]/20 rounded-full px-2.5 py-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                            Protegido
                        </span>
                    </div>
                    <p class="text-xs text-amber-600 bg-amber-50 border border-amber-200 rounded-lg px-3 py-2 mb-3 font-medium flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        El mapa es de solo lectura. La ubicación del inmueble no puede modificarse.
                    </p>
                    <div id="map-edit" class="w-full h-[300px] rounded-2xl border border-slate-200 shadow-inner z-0 opacity-80 pointer-events-none"></div>
                    <input type="hidden" name="latitud" id="lat-input" value="{{ $inmueble->latitud }}">
                    <input type="hidden" name="longitud" id="longitud-input" value="{{ $inmueble->longitud }}">
                </div>

                {{-- Leaflet y Scripts de Mapa --}}
                <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
                <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                <script>
                    var map, marker;
                    document.addEventListener('DOMContentLoaded', function () {
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

                        marker.on('dragend', function (event) {
                            var position = marker.getLatLng();
                            actualizarCampos(position.lat, position.lng);
                        });

                        map.on('click', function (e) {
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

                {{-- Habitaciones y Metros (PROTEGIDOS) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-bold text-[#003049] uppercase tracking-wider">Habitaciones</label>
                            <span class="inline-flex items-center gap-1 text-xs font-semibold text-[#003049]/70 bg-[#003049]/8 border border-[#003049]/20 rounded-full px-2.5 py-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                                Protegido
                            </span>
                        </div>
                        <div class="w-full px-5 py-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-600 font-medium cursor-not-allowed select-none flex items-center justify-between">
                            <span>{{ $inmueble->habitaciones }} habitación{{ $inmueble->habitaciones != 1 ? 'es' : '' }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        </div>
                        <input type="hidden" name="habitaciones" value="{{ $inmueble->habitaciones }}">
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-bold text-[#003049] uppercase tracking-wider">Metros (m²)</label>
                            <span class="inline-flex items-center gap-1 text-xs font-semibold text-[#003049]/70 bg-[#003049]/8 border border-[#003049]/20 rounded-full px-2.5 py-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                                Protegido
                            </span>
                        </div>
                        <div class="w-full px-5 py-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-600 font-medium cursor-not-allowed select-none flex items-center justify-between">
                            <span>{{ $inmueble->metros }} m²</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        </div>
                        <input type="hidden" name="metros" value="{{ $inmueble->metros }}">
                    </div>
                </div>

                {{-- Baños (PROTEGIDO) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    @php
                        $banoCombo = $inmueble->banos . ',' . $inmueble->medios_banos;
                        $banoLabels = [
                            '0,1' => 'Medio Baño',
                            '1,0' => '1 Baño Completo',
                            '1,1' => '1 Baño Completo y Medio Baño',
                            '2,0' => '2 Baños Completos',
                            '2,1' => '2 Baños Completos y Medio Baño',
                            '3,0' => '3 Baños Completos',
                            '3,1' => '3 Baños Completos y Medio Baño',
                            '4,0' => '4 Baños o más',
                        ];
                        $banoLabel = $banoLabels[$banoCombo] ?? $banoCombo;
                    @endphp
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-bold text-[#003049] uppercase tracking-wider">Baños</label>
                            <span class="inline-flex items-center gap-1 text-xs font-semibold text-[#003049]/70 bg-[#003049]/8 border border-[#003049]/20 rounded-full px-2.5 py-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                                Protegido
                            </span>
                        </div>
                        <div class="w-full px-5 py-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-600 font-medium cursor-not-allowed select-none flex items-center justify-between">
                            <span>{{ $banoLabel }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        </div>
                        <input type="hidden" id="banos-casa-input" name="banos_casa" value="{{ $banoCombo }}">
                    </div>
                </div>

                {{-- Cerradura / Baño compartido (PROTEGIDOS para cuartos) --}}
                <div id="bano-compartido-wrapper" class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-2 mb-6"
                    style="display: {{ $inmueble->tipo === 'Cuarto' ? 'grid' : 'none' }};">
                    <div class="flex items-center sm:-mt-3">
                        <label class="flex items-center gap-2 text-sm font-medium text-slate-400 cursor-not-allowed select-none" title="Campo protegido">
                            <input type="checkbox" name="tiene_cerradura" value="si" {{ $inmueble->tiene_cerradura_propia ? 'checked' : '' }}
                                class="w-4 h-4 rounded border-slate-300 text-[#003049]" disabled>
                            ¿El cuarto tiene cerradura propia? <span class="text-xs text-[#003049]/50">(protegido)</span>
                        </label>
                        {{-- Hidden para preservar valor en el POST --}}
                        @if($inmueble->tiene_cerradura_propia)
                            <input type="hidden" name="tiene_cerradura" value="si">
                        @endif
                    </div>
                    <div class="flex items-center sm:-mt-3">
                        <label class="flex items-center gap-2 text-sm font-medium text-slate-400 cursor-not-allowed select-none" title="Campo protegido">
                            <input type="checkbox" name="bano_compartido" value="1" {{ $inmueble->bano_compartido ? 'checked' : '' }}
                                class="w-4 h-4 rounded border-slate-300 text-[#003049]" disabled>
                            ¿El baño es compartido? <span class="text-xs text-[#003049]/50">(protegido)</span>
                        </label>
                        {{-- Hidden para preservar valor en el POST --}}
                        @if($inmueble->bano_compartido)
                            <input type="hidden" name="bano_compartido" value="1">
                        @endif
                    </div>
                </div>

                {{-- NUEVOS CAMPOS EXTENDIDOS (SIN CONTRATO) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6 pt-6 border-t border-slate-100">
                    <div>
                        <label class="block text-sm font-bold text-[#003049] uppercase tracking-wider mb-2">Mobiliario</label>
                        <select name="estado_mobiliario" required class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#003049] outline-none">
                            <option value="amueblada" {{ $inmueble->estado_mobiliario == 'amueblada' ? 'selected' : '' }}>Amueblada</option>
                            <option value="semiamueblada" {{ $inmueble->estado_mobiliario == 'semiamueblada' ? 'selected' : '' }}>Semiamueblada</option>
                            <option value="no amueblada" {{ $inmueble->estado_mobiliario == 'no amueblada' ? 'selected' : '' }}>No amueblada</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-[#003049] uppercase tracking-wider mb-2">¿Estacionamiento?</label>
                        <select name="tiene_estacionamiento" class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#003049] outline-none">
                            <option value="1" {{ $inmueble->tiene_estacionamiento ? 'selected' : '' }}>Sí</option>
                            <option value="0" {{ !$inmueble->tiene_estacionamiento ? 'selected' : '' }}>No</option>
                        </select>
                    </div>
                </div>

                {{-- Mascotas y Zonas Comunes --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6 pt-6 border-t border-slate-100" x-data="{ 
                    permiteMascotas: '{{ $inmueble->permite_mascotas ? 'si' : 'no' }}',
                    tieneZonasComunes: '{{ $inmueble->zonasComunes->count() > 0 ? 'si' : 'no' }}'
                }">
                    {{-- Mascotas --}}
                    <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100/50">
                        <label class="block text-sm font-bold text-[#003049] uppercase tracking-wider mb-3">¿Permite mascotas?</label>
                        <div class="flex gap-4 mb-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" x-model="permiteMascotas" value="si" name="permite_mascotas" class="text-[#003049] focus:ring-[#003049] h-4 w-4">
                                <span class="text-sm font-medium">Sí</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" x-model="permiteMascotas" value="no" name="permite_mascotas" class="text-[#003049] focus:ring-[#003049] h-4 w-4">
                                <span class="text-sm font-medium">No</span>
                            </label>
                        </div>
                        
                        <div x-show="permiteMascotas === 'si'" class="grid grid-cols-2 gap-3 mt-3 pt-3 border-t border-slate-200">
                            @php
                                $listaMascotas = [
                                    'Perros', 'Gatos', 'Pericos y loros', 'Pájaros de canto', 'Peces',
                                    'Hamsters y ratones', 'Conejos', 'Tortugas', 'Iguanas y lagartijas',
                                    'Serpientes', 'Ranas y ajolotes', 'Hurones', 'Arañas y tarántulas',
                                    'Cuyos', 'Pollos y gallinas', 'Otros'
                                ];
                                $mascotasIds = $inmueble->mascotas->pluck('slug')->toArray();
                            @endphp
                            @foreach($listaMascotas as $mascota)
                                @php $slug = \Str::slug($mascota, '_'); @endphp
                                <label class="flex items-center gap-2 text-xs cursor-pointer bg-white p-2 rounded-lg border hover:border-[#003049] transition-colors">
                                    <input type="checkbox" name="tipos_mascotas[]" value="{{ $slug }}" {{ in_array($slug, $mascotasIds) ? 'checked' : '' }} class="text-[#003049] focus:ring-[#003049] rounded">
                                    <span class="truncate" title="{{ $mascota }}">{{ $mascota }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Zonas Comunes --}}
                    <div id="zonas-comunes-wrapper" class="bg-slate-50 p-5 rounded-2xl border border-slate-100/50" style="display: {{ $inmueble->tipo === 'Cuarto' ? 'block' : 'none' }};">
                        <label class="block text-sm font-bold text-[#003049] uppercase tracking-wider mb-3">¿Acceso a zonas comunes?</label>
                        <div class="flex gap-4 mb-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" x-model="tieneZonasComunes" value="si" name="tiene_zonas_comunes" class="text-[#003049] focus:ring-[#003049] h-4 w-4 gap-2">
                                <span class="text-sm font-medium">Sí</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" x-model="tieneZonasComunes" value="no" name="tiene_zonas_comunes" class="text-[#003049] focus:ring-[#003049] h-4 w-4 gap-2">
                                <span class="text-sm font-medium">No</span>
                            </label>
                        </div>
                        <div x-show="tieneZonasComunes === 'si'" class="grid grid-cols-2 gap-3 mt-3 pt-3 border-t border-slate-200">
                            @php
                                $zonasIds = $inmueble->zonasComunes->pluck('slug')->toArray();
                            @endphp
                            <label class="flex items-center gap-2 text-xs cursor-pointer bg-white p-2 rounded-lg border"><input type="checkbox" name="zonas_comunes[]" value="sala" {{ in_array('sala', $zonasIds) ? 'checked' : '' }} class="rounded text-[#003049] focus:ring-[#003049]"> Sala</label>
                            <label class="flex items-center gap-2 text-xs cursor-pointer bg-white p-2 rounded-lg border"><input type="checkbox" name="zonas_comunes[]" value="cocina" {{ in_array('cocina', $zonasIds) ? 'checked' : '' }} class="rounded text-[#003049] focus:ring-[#003049]"> Cocina</label>
                            <label class="flex items-center gap-2 text-xs cursor-pointer bg-white p-2 rounded-lg border"><input type="checkbox" name="zonas_comunes[]" value="jardin" {{ in_array('jardin', $zonasIds) ? 'checked' : '' }} class="rounded text-[#003049] focus:ring-[#003049]"> Jardín</label>
                            <label class="flex items-center gap-2 text-xs cursor-pointer bg-white p-2 rounded-lg border"><input type="checkbox" name="zonas_comunes[]" value="patio" {{ in_array('patio', $zonasIds) ? 'checked' : '' }} class="rounded text-[#003049] focus:ring-[#003049]"> Patio</label>
                        </div>
                    </div>
                </div>

                {{-- Servicios --}}
                <div class="mt-6 bg-slate-50 p-5 rounded-2xl border border-slate-100/50" x-data="{
                    serviciosSeleccionados: {{ json_encode($inmueble->servicios->pluck('servicio')->toArray()) }}
                }">
                    <label class="block text-sm font-bold text-[#003049] uppercase tracking-wider mb-3">Servicios Disponibles</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                        @php
                            $listaServicios = ['Agua', 'Electricidad', 'Gas', 'Internet', 'TV por Cable'];
                        @endphp
                        @foreach($listaServicios as $servicio)
                        <label class="flex items-center gap-2 text-sm cursor-pointer bg-white p-2 rounded-lg border hover:border-[#003049] transition-colors">
                            <input type="checkbox" x-model="serviciosSeleccionados" value="{{ $servicio }}" name="servicios_incluidos[]" class="text-[#003049] focus:ring-[#003049] rounded">
                            {{ $servicio }}
                        </label>
                        @endforeach
                    </div>

                    <div class="border rounded-xl overflow-hidden mt-4" x-show="serviciosSeleccionados.length > 0" style="display: none;">
                        <div class="bg-white p-3 border-b font-medium text-sm text-center">¿Quién es responsable de pagar?</div>
                        <table class="w-full text-sm bg-white">
                            <thead>
                                <tr>
                                    <th class="p-2 text-left font-normal text-slate-500 border-b">Servicio</th>
                                    <th class="p-2 text-center font-normal text-slate-500 border-b">Inquilino</th>
                                    <th class="p-2 text-center font-normal text-slate-500 border-b">Arrendador</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($listaServicios as $index => $servicio)
                                @php
                                    $pivot = $inmueble->servicios->firstWhere('servicio', $servicio);
                                    $paga = $pivot ? $pivot->paga : 'inquilino';
                                @endphp
                                <tr class="border-t {{ $index % 2 == 0 ? 'bg-slate-50' : 'bg-white' }}" x-show="serviciosSeleccionados.includes('{{ $servicio }}')">
                                    <td class="p-3">{{ $servicio }}</td>
                                    <td class="p-3 text-center">
                                        <input type="radio" name="pago_servicio[{{ \Str::slug($servicio, '_') }}]" value="inquilino" {{ $paga === 'inquilino' ? 'checked' : '' }} class="w-4 h-4 text-[#003049]" x-bind:disabled="!serviciosSeleccionados.includes('{{ $servicio }}')">
                                    </td>
                                    <td class="p-3 text-center">
                                        <input type="radio" name="pago_servicio[{{ \Str::slug($servicio, '_') }}]" value="arrendador" {{ $paga === 'arrendador' ? 'checked' : '' }} class="w-4 h-4 text-[#003049]" x-bind:disabled="!serviciosSeleccionados.includes('{{ $servicio }}')">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Pagos y Tolerancia --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6 pt-6 border-t border-slate-100">
                    <div>
                        <label class="block text-sm font-bold text-[#003049] uppercase tracking-wider mb-2">Momento de Pago</label>
                        <select name="momento_pago" required class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#003049] outline-none">
                            <option value="adelantado" {{ $inmueble->momento_pago == 'adelantado' ? 'selected' : '' }}>Por adelantado</option>
                            <option value="vencido" {{ $inmueble->momento_pago == 'vencido' ? 'selected' : '' }}>A tiempo vencido</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-[#003049] uppercase tracking-wider mb-2">Tolerancia (Días)</label>
                        <input type="number" name="dias_tolerancia" value="{{ $inmueble->dias_tolerancia }}" min="0" max="15" required class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#003049] outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-[#003049] uppercase tracking-wider mb-2">Preaviso Salida (Días)</label>
                        <input type="number" name="dias_preaviso" value="{{ $inmueble->dias_preaviso }}" min="1" max="31" required class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#003049] outline-none">
                    </div>
                </div>

                {{-- Duración del Contrato --}}
                <div class="mt-6 bg-slate-50 p-5 rounded-2xl border border-slate-100">
                    <div class="flex items-center gap-2 mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#003049]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <label class="block text-sm font-bold text-[#003049] uppercase tracking-wider">Duración del Contrato</label>
                    </div>
                    <p class="text-xs text-slate-500 mb-3">Los contratos nuevos usarán esta duración para calcular su fecha de vencimiento.</p>
                    <div class="flex flex-wrap items-center gap-4">
                        <div class="flex items-center gap-2">
                            <input type="number"
                                   name="duracion_contrato_meses"
                                   id="duracion_contrato_meses_edit"
                                   value="{{ $inmueble->duracion_contrato_meses ?? 12 }}"
                                   min="1" max="60" required
                                   class="w-28 px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#003049] font-bold text-slate-800 text-center outline-none transition-all">
                            <span class="text-sm font-medium text-slate-600">meses</span>
                        </div>
                        <div class="flex gap-2">
                            <button type="button" onclick="document.getElementById('duracion_contrato_meses_edit').value=6"
                                    class="px-3 py-1.5 text-xs font-bold bg-white border border-slate-200 rounded-lg hover:border-[#003049] hover:text-[#003049] transition-colors">
                                6 meses
                            </button>
                            <button type="button" onclick="document.getElementById('duracion_contrato_meses_edit').value=12"
                                    class="px-3 py-1.5 text-xs font-bold bg-white border border-slate-200 rounded-lg hover:border-[#003049] hover:text-[#003049] transition-colors">
                                1 año
                            </button>
                            <button type="button" onclick="document.getElementById('duracion_contrato_meses_edit').value=24"
                                    class="px-3 py-1.5 text-xs font-bold bg-white border border-slate-200 rounded-lg hover:border-[#003049] hover:text-[#003049] transition-colors">
                                2 años
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Cláusulas Extra --}}
                <div class="mt-6 pt-6 mb-6 border-t border-slate-100" x-data="{ incluirClausulas: '{{ $inmueble->incluir_clausulas ? 'si' : 'no' }}' }">
                    <label class="block text-sm font-bold text-[#003049] uppercase tracking-wider mb-3">Cláusulas extra</label>
                    <div class="flex gap-4 mb-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" x-model="incluirClausulas" value="si" name="incluir_clausulas" class="text-[#003049] focus:ring-[#003049] h-4 w-4">
                            <span class="text-sm font-medium">Sí</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" x-model="incluirClausulas" value="no" name="incluir_clausulas" class="text-[#003049] focus:ring-[#003049] h-4 w-4">
                            <span class="text-sm font-medium">No</span>
                        </label>
                    </div>
                    
                    <div x-show="incluirClausulas === 'si'" class="mt-3">
                        <textarea name="clausulas_extra" rows="3" placeholder="Ej. El inquilino tendrá acceso a la alberca comunitaria los fines de semana..." oninput="this.value = this.value.replace(/[^a-zA-Z0-9\s.,?!;:/\-áéíóúÁÉÍÓÚñÑüÜ\r\n]/g, '')" class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#003049] outline-none transition-all">{{ $inmueble->clausulas_extra }}</textarea>
                    </div>
                </div>

                {{-- Descripción --}}
                <div>
                    <label class="block text-sm font-bold text-[#003049] uppercase tracking-wider mb-2">Descripción</label>
                    <textarea name="descripcion" rows="5" required
                        oninput="this.value = this.value.replace(/[^a-zA-Z0-9\s.,?!áéíóúÁÉÍÓÚñÑüÜ\r\n]/g, '')"
                        class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#003049] outline-none transition-all">{{ $inmueble->descripcion }}</textarea>
                </div>
            </div>

            <div class="flex gap-4">
                <button type="submit"
                    class="flex-1 bg-[#003049] text-white font-black py-4 rounded-2xl shadow-xl hover:bg-[#003049]/90 transition-all uppercase tracking-widest">
                    Guardar Cambios
                </button>
                <a href="{{ request()->has('return_to_contrato') ? route('contratos.revision', request('return_to_contrato')) : route('inmuebles.index') }}"
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
            const error = document.getElementById('yt-error-edit');
            const iframe = document.getElementById('yt-iframe-edit');

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
            const zonasComunesWrapper = document.getElementById('zonas-comunes-wrapper');

            if (tipo === 'Cuarto') {
                if (banoCompartidoWrapper) banoCompartidoWrapper.style.display = 'grid';
                if (zonasComunesWrapper) zonasComunesWrapper.style.display = 'block';
            } else {
                if (banoCompartidoWrapper) banoCompartidoWrapper.style.display = 'none';
                if (zonasComunesWrapper) zonasComunesWrapper.style.display = 'none';
            }
        }
    </script>
@endpush