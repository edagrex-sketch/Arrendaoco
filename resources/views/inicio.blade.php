@extends('layouts.app')

@section('title', 'Inicio')

@section('content')

    {{-- 
       1. HERO SECTION & BUSCADOR 
    --}}
    <section class="mb-12 px-4 py-8">
        <div class="w-full max-w-5xl mx-auto rounded-xl bg-card p-6 shadow-lg border border-border">
            <h2 class="mb-6 text-center text-3xl font-semibold text-card-foreground">
                Encuentra tu próximo hogar en Ocosingo
            </h2>
            <form action="{{ route('inmuebles.public_search') }}" method="GET"
                class="flex flex-col gap-4 lg:flex-row items-end">
                <div class="relative flex-1 w-full">
                    <label class="text-sm font-medium mb-1.5 block text-muted-foreground ml-1">Ubicación</label>
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-muted-foreground" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <input type="text" name="ubicacion" value="{{ request('ubicacion') }}"
                            placeholder="Ej: Centro, Las Margaritas..."
                            class="flex h-12 w-full rounded-md border border-input bg-background px-3 py-2 pl-10 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary">
                    </div>
                </div>

                {{-- Select: Categoría --}}
                <div class="relative w-full lg:w-44">
                    <label class="text-sm font-medium mb-1.5 block text-muted-foreground ml-1">Categoría</label>
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-muted-foreground" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <select name="categoria"
                            class="flex h-12 w-full appearance-none rounded-md border border-input bg-background px-3 py-2 pl-10 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary shadow-sm">
                            <option value="">Todas</option>
                            <option value="Casa" {{ request('categoria') == 'Casa' ? 'selected' : '' }}>Casa</option>
                            <option value="Departamento" {{ request('categoria') == 'Departamento' ? 'selected' : '' }}>Departamento</option>
                            <option value="Cuarto" {{ request('categoria') == 'Cuarto' ? 'selected' : '' }}>Cuarto</option>
                        </select>
                    </div>
                </div>

                <div class="relative w-full lg:w-44">
                    <label class="text-sm font-medium mb-1.5 block text-muted-foreground ml-1">Precio</label>
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-muted-foreground" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <select name="rango_precio"
                            class="flex h-12 w-full appearance-none rounded-md border border-input bg-background px-3 py-2 pl-10 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary shadow-sm">
                            <option value="">Cualquiera</option>
                            <option value="0-2000" {{ request('rango_precio') == '0-2000' ? 'selected' : '' }}>$0 - $2,000</option>
                            <option value="2000-4000" {{ request('rango_precio') == '2000-4000' ? 'selected' : '' }}>$2,000 - $4,000</option>
                            <option value="4000-6000" {{ request('rango_precio') == '4000-6000' ? 'selected' : '' }}>$4,000 - $6,000</option>
                            <option value="6000+" {{ request('rango_precio') == '6000+' ? 'selected' : '' }}>$6,000+</option>
                        </select>
                    </div>
                </div>
                <button type="submit"
                    class="inline-flex h-12 items-center justify-center rounded-md bg-[#003049] px-8 text-sm font-semibold text-white transition-all hover:bg-[#003049]/90 hover:scale-[1.02] shadow-md gap-2 w-full lg:w-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Buscar
                </button>
            </form>
            @guest
                <div class="mt-4 text-center">
                    <p class="text-xs text-muted-foreground">¿Quieres usar los filtros avanzados? 
                        <a href="{{ route('login') }}" class="font-bold text-[#003049] hover:underline transition-all">Inicia Sesión</a>
                    </p>
                </div>
            @endguest
        </div>
    </section>

    {{-- 
        2. MAPA DE EXPLORACIÓN
    --}}
    <section class="container mx-auto px-4 mb-16" x-data="mapExploration()">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl bg-[#003049] flex items-center justify-center text-white shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /></svg>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-[#003049] tracking-tight">Explora el Mapa</h2>
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-widest hidden md:block">Encuentra disponibilidad cerca de ti con vista satelital</p>
                </div>
            </div>
            
            <button @click="toggleMap" class="text-sm font-bold bg-[#FDF0D5] text-[#003049] px-4 py-2 rounded-xl hover:bg-[#FDF0D5]/80 transition-all flex items-center gap-2 shadow-sm whitespace-nowrap">
                <span x-text="showMap ? 'Contraer Mapa' : 'Mostrar Mapa'"></span>
                <svg x-show="!showMap" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                <svg x-show="showMap" style="display: none;" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
            </button>
        </div>
        
        <div x-show="showMap" x-transition.opacity.duration.300ms style="display: none;" class="w-full relative z-0">
            <div id="map-inicio" class="w-full h-[500px] rounded-[2.5rem] border border-slate-100 shadow-2xl relative z-0"></div>
        </div>
    </section>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('mapExploration', () => ({
                map: null,
                userMarker: null,
                userCircle: null,
                showMap: false,
                toggleMap() {
                    this.showMap = !this.showMap;
                    if (this.showMap) {
                        setTimeout(() => {
                            if (this.map) {
                                this.map.invalidateSize();
                            }
                        }, 100);
                    }
                },
                init() {
                    setTimeout(() => {
                        // Capas Base
                        const osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            maxZoom: 19,
                            attribution: '&copy; OpenStreetMap contributors'
                        });

                        const satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                            attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
                        });

                        // Inicializar mapa
                        this.map = L.map('map-inicio', {
                            center: [16.9068, -92.0941],
                            zoom: 15,
                            layers: [osm],
                            scrollWheelZoom: false // <--- Deshabilitado por defecto
                        });

                        // Habilitar el zoom con la rueda al hacer clic, deshabilitar al quitar el mouse
                        this.map.on('click', () => { this.map.scrollWheelZoom.enable(); });
                        this.map.on('mouseout', () => { this.map.scrollWheelZoom.disable(); });

                        const baseMaps = { 'Callejero': osm, 'Satélite': satellite };
                        L.control.layers(baseMaps, null, { collapsed: false, position: 'topright' }).addTo(this.map);
                        L.control.scale({ imperial: false, position: 'bottomleft' }).addTo(this.map);

                        // Botón de geolocalización
                        const locateControl = L.Control.extend({
                            options: { position: 'topleft' },
                            onAdd: function(map) {
                                const container = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-control-custom');
                                container.style.backgroundColor = 'white';
                                container.style.width = '34px';
                                container.style.height = '34px';
                                container.style.display = 'flex';
                                container.style.alignItems = 'center';
                                container.style.justifyContent = 'center';
                                container.style.cursor = 'pointer';
                                container.innerHTML = `<svg xmlns='http://www.w3.org/2000/svg' style='width:20px; height:20px; color:#003049' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z' /><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M15 11a3 3 0 11-6 0 3 3 0 016 0z' /></svg>`;
                                container.onclick = function() {
                                    if (!window.isSecureContext && window.location.hostname !== 'localhost' && window.location.hostname !== '127.0.0.1') {
                                        Swal.fire({
                                            icon: 'warning',
                                            title: 'Conexión no segura',
                                            text: 'Para usar el GPS, el navegador exige HTTPS.',
                                            footer: '<span class=\"text-xs text-slate-400\">Prueba usando localhost o instala un certificado SSL.</span>'
                                        });
                                        return;
                                    }
                                    map.locate({setView: true, maxZoom: 16});
                                };
                                return container;
                            }
                        });
                        this.map.addControl(new locateControl());

                        this.map.on('locationfound', (e) => {
                            if (this.userMarker) this.map.removeLayer(this.userMarker);
                            if (this.userCircle) this.map.removeLayer(this.userCircle);
                            this.userMarker = L.marker(e.latlng).addTo(this.map).bindPopup('Estás aquí').openPopup();
                            this.userCircle = L.circle(e.latlng, e.accuracy / 2).addTo(this.map);
                        });

                        this.map.on('locationerror', (e) => {
                            let msg = 'No pudimos encontrar tu ubicación. Por favor, asegúrate de activar el GPS.';
                            if (e.message.includes('Only secure origins are allowed')) {
                                msg = 'El GPS está bloqueado por falta de HTTPS.';
                            }
                            Swal.fire({ icon: 'error', title: 'Error de GPS', text: msg });
                        });

                        // Marcadores dinámicos
                        @foreach($inmueblesMapa as $in)
                            @if($in->latitud && $in->longitud)
                                L.marker([{{ $in->latitud }}, {{ $in->longitud }}])
                                    .addTo(this.map)
                                    .bindPopup(`
                                        <div class='w-48 overflow-hidden font-sans'>
                                            <div class='relative h-24 mb-2'>
                                                <img src='{{ str_starts_with($in->imagen, 'http') ? $in->imagen : (str_contains($in->imagen, 'storage/') ? asset($in->imagen) : asset('storage/' . $in->imagen)) }}' class='w-full h-full object-cover rounded-md'>
                                                <span class='absolute top-1 right-1 bg-[#003049] text-white text-[8px] px-1.5 py-0.5 rounded-full font-bold'>${{ number_format($in->renta_mensual) }}</span>
                                            </div>
                                            <h4 class='font-black text-[#003049] text-xs line-clamp-1'>{{ $in->titulo }}</h4>
                                            <p class='text-[10px] text-slate-500 mb-2'>{{ $in->categoria }} en Ocosingo</p>
                                            <a href='{{ route('inmuebles.show', $in) }}' class='block w-full py-1.5 bg-[#003049] hover:bg-[#669BBC] text-white text-[10px] font-bold rounded text-center'>Ver propiedad</a>
                                        </div>
                                    `);
                            @endif
                        @endforeach
                    }, 500);
                }
            }));
        });
    </script>

    {{-- 3. SECCIÓN DE RESULTADOS --}}
    <section class="container mx-auto px-4 mb-20 -mt-8">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold text-[#003049]">Propiedades Disponibles</h2>
            <div class="flex items-center gap-4">
                <span class="text-xs font-bold text-slate-400 bg-slate-50 px-3 py-1.5 rounded-full border border-slate-100">
                    {{ $inmuebles->total() }} resultados
                    @guest <span>(Vista Invitado)</span> @endguest
                </span>
            </div>
        </div>

        {{-- Grid de Tarjetas --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-12">
            @forelse ($inmuebles as $inmueble)
                <div class="group bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md hover:-translate-y-1">
                    {{-- Imagen --}}
                    <div class="relative h-56 overflow-hidden">
                        @if ($inmueble->imagen)
                            <img src="{{ str_starts_with($inmueble->imagen, 'http') ? $inmueble->imagen : (str_contains($inmueble->imagen, 'storage/') ? asset($inmueble->imagen) : asset('storage/' . $inmueble->imagen)) }}" alt="{{ $inmueble->titulo }}"
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        @else
                            <div class="w-full h-full bg-slate-50 flex items-center justify-center text-slate-300">
                                <span class="text-xs font-bold uppercase tracking-widest">Sin imagen</span>
                            </div>
                        @endif

                        {{-- Badge de Precio --}}
                        <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm px-3 py-1.5 rounded-full shadow-sm border border-slate-100">
                            <span class="font-bold text-[#003049]">${{ number_format($inmueble->renta_mensual ?? 0) }}</span>
                            <span class="text-[10px] text-slate-500">/ mes</span>
                        </div>

                        {{-- Botón Favorito --}}
                        @auth
                            @unless(Auth::user()->tieneRol('admin') || Auth::user()->es_admin)
                            <div class="absolute top-4 left-4 z-10" x-data="{ 
                                isFavorited: {{ in_array($inmueble->id, $favoritosIds) ? 'true' : 'false' }},
                                loading: false,
                                toggle() {
                                    if (this.loading) return;
                                    this.loading = true;
                                    fetch('{{ route('favoritos.toggle', $inmueble) }}', {
                                        method: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                            'Accept': 'application/json',
                                            'X-Requested-With': 'XMLHttpRequest'
                                        }
                                    })
                                    .then(res => res.json())
                                    .then(data => {
                                        if(data.success) {
                                            this.isFavorited = data.agregado;
                                            
                                            // Toast de confirmación premium
                                             const Toast = Swal.mixin({
                                                 toast: true,
                                                 position: 'top-end',
                                                 showConfirmButton: false,
                                                 timer: 1500
                                             });

                                             Toast.fire({
                                                 icon: 'success',
                                                 title: data.agregado ? 'Agregado' : 'Eliminado'
                                             });
                                        }
                                    })
                                    .finally(() => this.loading = false);
                                }
                            }">
                                <button @click.prevent="toggle()" 
                                    class="h-10 w-10 flex items-center justify-center rounded-full bg-white/90 backdrop-blur-md shadow-lg transition-all hover:scale-110 active:scale-95 group/fav"
                                    :class="isFavorited ? 'text-red-500' : 'text-slate-400'">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 transition-all duration-300" 
                                         :class="isFavorited ? 'fill-current' : 'fill-none'" 
                                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                </button>
                            </div>
                            @endunless
                        @endauth
                    </div>

                    {{-- Contenido --}}
                    <div class="p-6">
                        <h3 class="font-bold text-lg text-[#003049] line-clamp-1 mb-1">
                            {{ $inmueble->titulo }}</h3>
                        <p class="text-sm text-slate-400 flex items-center gap-1.5 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ $inmueble->direccion }}
                        </p>

                        <div class="flex items-center gap-4 py-4 border-t border-slate-100 mb-6">
                            <div class="flex items-center gap-1.5 text-slate-500" title="Habitaciones">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="w-5 h-5 opacity-70" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 11v3a2 2 0 002 2h14a2 2 0 002-2v-3"></path><path d="M5 16v2"></path><path d="M19 16v2"></path><path d="M5 11V7a2 2 0 012-2h10a2 2 0 012 2v4"></path><path d="M5 11h14"></path>
                                </svg>
                                <span class="text-base font-bold text-slate-700">{{ $inmueble->habitaciones }} <span class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider ml-0.5">Hab</span></span>
                            </div>
                            <div class="flex items-center gap-1.5 text-slate-500" title="Baños">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="w-5 h-5 opacity-70" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M9 6 6.5 3.5a1.5 1.5 0 0 0-1-.5C4.683 3 4 3.683 4 4.5V17a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-5"/><line x1="10" x2="8" y1="5" y2="7"/><line x1="2" x2="22" y1="12" y2="12"/><line x1="7" x2="7" y1="19" y2="21"/><line x1="17" x2="17" y1="19" y2="21"/>
                                </svg>
                                <span class="text-base font-bold text-slate-700">{{ $inmueble->banos }} <span class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider ml-0.5">Baño</span></span>
                            </div>
                            <div class="flex items-center gap-1.5 text-slate-500" title="Superficie">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="w-5 h-5 opacity-70" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m21 21-6-6m6 6v-4.8m0 4.8h-4.8"/><path d="M3 16.2V21m0 0h4.8M3 21l6-6"/><path d="M21 7.8V3m0 0h-4.8M21 3l-6 6"/><path d="M3 7.8V3m0 0h4.8M3 3l6 6"/>
                                </svg>
                                <span class="text-base font-bold text-slate-700">{{ number_format($inmueble->metros ?? 0, 0) }} <span class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider ml-0.5">M²</span></span>
                            </div>
                        </div>

                        @auth
                            @if(Auth::id() === $inmueble->propietario_id)
                                <a href="{{ route('inmuebles.show', $inmueble) }}"
                                    class="flex w-full py-4 items-center justify-center rounded-2xl bg-gradient-to-br from-[#003049] to-[#004e7a] text-sm font-black text-white transition-all hover:-translate-y-1 shadow-lg shadow-[#003049]/20 uppercase tracking-widest">
                                    Gestionar Propiedad
                                </a>
                            @else
                                <a href="{{ route('inmuebles.show', $inmueble) }}"
                                    class="flex w-full py-4 items-center justify-center rounded-2xl bg-slate-100 text-sm font-black text-[#003049] transition-all hover:bg-slate-200 uppercase tracking-widest">
                                    Ver Detalles
                                </a>
                            @endif
                        @else
                            <button onclick="window.location.href='{{ route('login') }}'"
                                class="flex w-full py-4 items-center justify-center rounded-2xl bg-slate-50 border-2 border-dashed border-slate-200 text-xs font-black text-slate-400 transition-all hover:bg-slate-100 uppercase tracking-widest gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                    <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" />
                                </svg> Inicia Sesión para Ver
                            </button>
                        @endauth
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center text-slate-400 font-medium uppercase tracking-widest opacity-50">No hay propiedades disponibles.</div>
            @endforelse
        {{-- Paginación --}}
        <div class="mt-16 px-4 flex justify-center">
            {{ $inmuebles->links() }}
        </div>
    </section>

@endsection
