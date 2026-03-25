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

                {{-- Dirección y Buscador --}}
                <div>
                    <label class="block text-sm font-bold text-[#003049] uppercase tracking-wider mb-2">Dirección</label>
                    <div class="flex gap-2 mb-4">
                        <input type="text" name="direccion" id="direccion-input" value="{{ $inmueble->direccion }}" required
                            class="flex-1 px-5 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#003049] outline-none">
                        <button type="button" onclick="buscarDireccion()"
                            class="bg-[#003049] text-white px-6 py-3 rounded-xl hover:bg-[#003049]/90 transition-all font-bold flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                <path fill-rule="evenodd"
                                    d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z"
                                    clip-rule="evenodd" />
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

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label
                            class="block text-sm font-bold text-[#003049] uppercase tracking-wider mb-2">Habitaciones</label>
                        <input type="number" name="habitaciones" value="{{ $inmueble->habitaciones }}" required min="0"
                            oninput="if(this.value < 0) this.value = '';"
                            class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#003049] outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-[#003049] uppercase tracking-wider mb-2">Metros
                            (m²)</label>
                        <input type="number" name="metros" value="{{ $inmueble->metros }}" required min="0"
                            oninput="if(this.value < 0) this.value = '';"
                            class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#003049] outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label class="block text-sm font-bold text-[#003049] uppercase tracking-wider mb-2">Baños</label>
                        @php $banoCombo = $inmueble->banos . ',' . $inmueble->medios_banos; @endphp
                        <select id="banos-casa-input" name="banos_casa" required
                            class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#003049] outline-none">
                            <option value="0,1" {{ $banoCombo === '0,1' ? 'selected' : '' }}>Medio Baño</option>
                            <option value="1,0" {{ $banoCombo === '1,0' ? 'selected' : '' }}>1 Baño Completo</option>
                            <option value="1,1" {{ $banoCombo === '1,1' ? 'selected' : '' }}>1 Baño Completo y Medio Baño
                            </option>
                            <option value="2,0" {{ $banoCombo === '2,0' ? 'selected' : '' }}>2 Baños Completos</option>
                            <option value="2,1" {{ $banoCombo === '2,1' ? 'selected' : '' }}>2 Baños Completos y Medio Baño
                            </option>
                            <option value="3,0" {{ $banoCombo === '3,0' ? 'selected' : '' }}>3 Baños Completos</option>
                            <option value="3,1" {{ $banoCombo === '3,1' ? 'selected' : '' }}>3 Baños Completos y Medio Baño
                            </option>
                            <option value="4,0" {{ $banoCombo === '4,0' ? 'selected' : '' }}>4 Baños o más</option>
                        </select>
                    </div>
                </div>

                <div id="bano-compartido-wrapper" class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-2 mb-6"
                    style="display: {{ $inmueble->tipo === 'Cuarto' ? 'grid' : 'none' }};">
                    <div class="flex items-center sm:-mt-3">
                        <label class="flex items-center gap-2 text-sm font-medium text-slate-500 cursor-pointer">
                            <input type="checkbox" name="tiene_cerradura" value="si" {{ $inmueble->tiene_cerradura_propia ? 'checked' : '' }}
                                class="w-4 h-4 rounded border-slate-300 text-[#003049] focus:ring-[#003049]">
                            ¿El cuarto tiene cerradura propia?
                        </label>
                    </div>
                    <div class="flex items-center sm:-mt-3">
                        <label class="flex items-center gap-2 text-sm font-medium text-slate-500 cursor-pointer">
                            <input type="checkbox" name="bano_compartido" value="1" {{ $inmueble->bano_compartido ? 'checked' : '' }}
                                class="w-4 h-4 rounded border-slate-300 text-[#003049] focus:ring-[#003049]">
                            ¿El baño es compartido?
                        </label>
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