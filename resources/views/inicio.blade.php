@extends('layouts.app')

@section('title', 'Inicio')

@section('content')

    {{-- 
       1. HERO SECTION & BUSCADOR 
    --}}
    <section class="mb-12 py-4" x-data="{ 
        filtersExpanded: false, 
        isDesktop: window.innerWidth >= 768 
    }" @resize.window="isDesktop = window.innerWidth >= 768; if(isDesktop) filtersExpanded = false">
        <div class="w-full max-w-5xl mx-auto rounded-3xl bg-white p-5 sm:p-10 shadow-[0_20px_50px_rgba(0,48,73,0.05)] border border-gray-100">
            <h2 class="mb-8 text-center text-2xl sm:text-4xl font-black text-[#003049] tracking-tight">
                Encuentra tu próximo hogar en Ocosingo
            </h2>

            {{-- Botón de Búsqueda (Trigger para Móvil) --}}
            <button @click="filtersExpanded = !filtersExpanded" 
                class="flex md:hidden w-full items-center justify-between bg-brand-dark text-white px-6 py-4 rounded-2xl font-bold shadow-lg shadow-brand-dark/20 transition-all active:scale-[0.98] mb-4"
                x-show="!filtersExpanded && !isDesktop" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95">
                <div class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <span>Buscar propiedades...</span>
                </div>
                <svg :class="{'rotate-180': filtersExpanded}" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <form id="form-busqueda" action="{{ route('inmuebles.public_search') }}" method="GET"
                class="flex flex-col gap-4 md:flex-row items-end"
                x-show="filtersExpanded || isDesktop"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 -translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0">
                
                {{-- Contenedor de Campos (Para poder animarlos juntos) --}}
                <div class="grid grid-cols-1 md:flex md:flex-row gap-4 w-full items-end">
                    
                    {{-- Input: Ubicación --}}
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
                    <div class="relative w-full md:w-44">
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

                    {{-- Select: Precio --}}
                    <div class="relative w-full md:w-44">
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

                    <div class="flex gap-3 w-full md:w-auto">
                        {{-- Botón: Ocultar (Solo Móvil) --}}
                        <button type="button" @click="filtersExpanded = false"
                            class="flex md:hidden items-center justify-center w-12 h-12 rounded-xl bg-slate-100 text-brand-dark hover:bg-slate-200 transition-all border border-slate-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7" />
                            </svg>
                        </button>

                        <button type="submit"
                            class="btn-primary flex-1 md:flex-none md:w-auto px-8 h-12 flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <span>Buscar</span>
                        </button>
                    </div>
                </div>
            </form>
            @guest
                <div class="mt-4 text-center">
                    <p class="text-xs text-muted-foreground">¿Quieres usar los filtros avanzados? 
                        <a href="{{ route('login') }}" class="font-bold text-brand-dark hover:underline transition-all">Inicia Sesión</a>
                    </p>
                </div>
            @endguest
        </div>
    </section>

    {{-- 
        2. MAPA DE EXPLORACIÓN
    --}}
    <section class="container mx-auto px-4 mb-16" x-data="mapExploration({{ session('login_success') ? 'true' : 'false' }})">
        <div class="relative w-fit">
            {{-- Burbuja Flotante y Roco (Solo tras Login) --}}
            <div x-show="showHint"
                 x-transition:enter="transition ease-out duration-700 transform"
                 x-transition:enter-start="opacity-0 translate-y-8 scale-50"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-500 transform"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 scale-75"
                 class="absolute z-20 flex items-end gap-2 pointer-events-none"
                 style="bottom: 100%; left: 0; padding-bottom: 8px; display: none;">
                 
                 <!-- Roco Mascota (Izquierda) -->
                 <div class="w-16 h-16 origin-bottom transform scale-x-[-1]">
                     <lottie-player src="https://assets4.lottiefiles.com/packages/lf20_syqnfe7c.json"
                         background="transparent" speed="1" loop autoplay renderer="svg" style="width: 100%; height: 100%;">
                     </lottie-player>
                 </div>

                 <!-- Burbuja de Texto -->
                 <div class="bg-[#FDF0D5] px-5 py-3 rounded-2xl shadow-xl relative animate-[bounce_2s_infinite]">
                     <span class="text-[#003049] font-black text-sm tracking-wide">Prueba el mapa</span>
                     <!-- Triangulito de la burbuja -->
                     <div class="absolute -bottom-2 left-6 w-4 h-4 bg-[#FDF0D5] rotate-45 transform"></div>
                 </div>
            </div>

            <div class="flex items-center mb-6 cursor-pointer group w-fit" @click="toggleMap">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl bg-brand-dark flex items-center justify-center text-white shadow-lg transition-transform duration-300 group-hover:scale-105">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /></svg>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-brand-dark tracking-tight transition-colors duration-300 group-hover:text-brand-light">Explora el Mapa</h2>
                    <div class="flex items-center gap-2">
                        <p class="text-slate-400 text-xs font-bold uppercase tracking-widest hidden md:block transition-colors duration-300 group-hover:text-slate-500">Encuentra disponibilidad cerca de ti con vista satelital</p>
                        
                        <svg :class="{'rotate-180': showMap}" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-brand-dark transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
            </div>
            </div>
        </div>
        
        
        <div x-show="showMap" x-transition.opacity.duration.300ms style="display: none;" class="w-full relative z-0">
            <div id="map-inicio" class="w-full h-[500px] rounded-[2.5rem] border border-slate-100 shadow-2xl relative z-0"></div>
        </div>
    </section>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('mapExploration', (shouldShowHint = false) => ({
                map: null,
                userMarker: null,
                userCircle: null,
                showMap: false,
                showHint: false,
                toggleMap() {
                    this.showMap = !this.showMap;
                    if (this.showMap) {
                        this.showHint = false;
                        // Forzar el redibujado repetidamente durante la transición para evitar Leaflet render glitch
                        let count = 0;
                        let interval = setInterval(() => {
                            if (this.map) {
                                this.map.invalidateSize();
                            }
                            if (++count > 10) clearInterval(interval); // 500ms total
                        }, 50);
                    }
                },
                init() {
                    if (shouldShowHint) {
                        setTimeout(() => {
                            this.showHint = true;
                            setTimeout(() => {
                                this.showHint = false;
                            }, 12000);
                        }, 1500);
                    }

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
    <section class="container mx-auto px-4 mb-20 -mt-8" id="resultados-busqueda">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-black text-brand-dark">Propiedades Disponibles</h2>
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

                        <div class="flex items-center justify-between py-4 border-t border-slate-100 mb-6">
                            <div class="flex items-center gap-4">
                                <div class="flex items-center gap-1.5 text-slate-500" title="Habitaciones">
                                    <svg class="w-4 h-4 text-[#003049]/60" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M22 13V19C22 19.5523 21.5523 20 21 20H3C2.44772 20 2 19.5523 2 19V13C2 11.3431 3.34315 10 5 10H19C20.6569 10 22 11.3431 22 13ZM19 12H5C4.44772 12 4 12.4477 4 13V15H20V13C20 12.4477 19.5523 12 19 12ZM20 6H4V9H20V6Z" />
                                    </svg>
                                    <span class="text-base font-bold text-slate-700">{{ $inmueble->habitaciones }} <span class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider ml-0.5">Hab</span></span>
                                </div>
                                <div class="flex items-center gap-1.5 text-slate-500" title="Baños">
                                    <svg class="w-4 h-4 text-[#003049]/60" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M19 11C19.5523 11 20 11.4477 20 12V14C20 15.6569 18.6569 17 17 17H7C5.34315 17 4 15.6569 4 14V12C4 11.4477 4.44772 11 5 11H19ZM16 4H8V10H16V4ZM18 18H6V20H18V18Z" />
                                    </svg>
                                    <span class="text-base font-bold text-slate-700">{{ $inmueble->banos }} <span class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider ml-0.5">Baño</span></span>
                                </div>
                                <div class="flex items-center gap-1.5 text-slate-500" title="Superficie">
                                    <svg class="w-4 h-4 text-[#003049]/60" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M3 7V5C3 3.89543 3.89543 3 5 3H7M17 3H19C20.1046 3 21 3.89543 21 5V7M21 17V19C21 20.1046 20.1046 21 19 21H17M7 21H5C3.89543 21 3 20.1046 3 19V17M9 9H15V15H9V9Z" />
                                    </svg>
                                    <span class="text-base font-bold text-slate-700">{{ number_format($inmueble->metros ?? 0, 0) }} <span class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider ml-0.5">M²</span></span>
                                </div>
                            </div>
                            @auth
                                @if(Auth::id() === $inmueble->propietario_id)
                                    <div class="flex-shrink-0 ml-2" title="Tu propiedad">
                                        <div class="h-10 w-10 flex items-center justify-center rounded-full border-[3px] border-[#E63946] bg-white shadow-sm overflow-hidden group/owner group-hover:scale-105 transition-transform p-1">
                                            @if(Auth::user()->foto_perfil)
                                                <img src="{{ str_starts_with(Auth::user()->foto_perfil, 'http') ? Auth::user()->foto_perfil : asset('storage/' . Auth::user()->foto_perfil) }}" class="w-full h-full object-cover rounded-full">
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full text-orange-500" viewBox="0 0 24 24" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.437.695A18.683 18.683 0 0112 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 01-.437-.695z" clip-rule="evenodd" />
                                                </svg>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endauth
                        </div>

                        @auth
                            <a href="{{ route('inmuebles.show', $inmueble) }}"
                                class="flex w-full py-4 items-center justify-center rounded-2xl bg-slate-100 text-sm font-black text-[#003049] transition-all hover:bg-slate-200 uppercase tracking-widest">
                                Ver Detalles
                            </a>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('form-busqueda');
            const resultados = document.getElementById('resultados-busqueda');
            let timeout = null;

            if (!form || !resultados) return;

            function realizarBusqueda(url) {
                resultados.style.opacity = '0.5';
                resultados.style.pointerEvents = 'none';

                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    // Reemplazar el contenedor interior si la vista enviada no contiene el outer wrapper
                    // Pero la vista de public_search retorna partials.list_inicio
                    // list_inicio no incluye un wrapper <section>
                    resultados.innerHTML = html;
                })
                .catch(error => console.error('Error en la búsqueda:', error))
                .finally(() => {
                    resultados.style.opacity = '1';
                    resultados.style.pointerEvents = 'auto';
                });
            }

            function triggerBusqueda() {
                const url = new URL(form.action);
                const formData = new FormData(form);
                const params = new URLSearchParams();
                
                for(let [key, value] of Object.entries(Object.fromEntries(formData))) {
                    if (value) {
                         params.append(key, value);
                    }
                }

                url.search = params.toString();
                realizarBusqueda(url.href);
            }

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                triggerBusqueda();
            });

            form.querySelectorAll('input, select').forEach(input => {
                input.addEventListener(input.tagName === 'INPUT' ? 'input' : 'change', function() {
                    clearTimeout(timeout);
                    timeout = setTimeout(triggerBusqueda, 400);
                });
            });

            // Interceptar clics en la paginación dentro de #resultados-busqueda
            resultados.addEventListener('click', function(e) {
                const link = e.target.closest('nav[role="navigation"] a, .pagination a');
                if (link && link.href) {
                    e.preventDefault();
                    realizarBusqueda(link.href);
                    // Opcional: scrollear hacia los resultados
                    const offsetTop = resultados.getBoundingClientRect().top + window.scrollY - 100;
                    window.scrollTo({ top: offsetTop, behavior: 'smooth' });
                }
            });
            // TIEMPO REAL: Escuchar nuevas publicaciones
            if (typeof window.Echo !== 'undefined') {
                window.Echo.channel('admin-updates')
                    .listen('.nuevo-inmueble', (e) => {
                        console.log('Nueva publicación detectada:', e);
                        
                        // 1. Notificación premium
                        if (window.Swal) {
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 6000,
                                timerProgressBar: true
                            });

                            Toast.fire({
                                icon: 'info',
                                title: '¡Nueva propiedad disponible!',
                                text: e.inmueble.titulo
                            });
                        }

                        // 2. Refrescar la lista automáticamente
                        triggerBusqueda();
                    });
            }
        });
    </script>
@endsection
