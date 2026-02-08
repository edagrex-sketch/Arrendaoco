@extends('layouts.app')

@section('title', $inmueble->titulo)

@section('content')
    <div class="max-w-5xl mx-auto px-4 py-8 lg:py-12">
        <div class="bg-white rounded-[3rem] shadow-2xl shadow-[#003049]/10 border border-slate-100 overflow-hidden">
            {{-- Imagen Principal --}}
            <div class="relative group aspect-[21/9] overflow-hidden bg-muted">
                @if ($inmueble->imagen)
                    <img src="{{ $inmueble->imagen }}" alt="{{ $inmueble->titulo }}"
                        class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105">
                @endif
                <div class="absolute top-8 left-8 bg-white/95 backdrop-blur-md px-6 py-3 rounded-2xl shadow-2xl border border-white/20">
                    <span class="text-[#003049] font-black text-3xl">${{ number_format($inmueble->renta_mensual) }}</span>
                    <span class="text-muted-foreground text-sm font-bold uppercase tracking-widest ml-1">/ mes</span>
                </div>
            </div>

            <div class="p-8 lg:p-12 space-y-12">
                {{-- Sección Superior: Título y Ubicación --}}
                <div class="max-w-3xl">
                    <h1 class="text-4xl lg:text-5xl font-extrabold text-[#003049] mb-4 tracking-tight leading-tight">
                        {{ $inmueble->titulo }}</h1>
                    <p class="flex items-center text-muted-foreground text-lg">
                        <svg xmlns="http://www.w3.org/2000/center" class="h-7 w-7 mr-3 text-primary" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        {{ $inmueble->direccion }}, Ocosingo
                    </p>
                </div>

                {{-- Bloque Central: Características + Dueño --}}
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-start">
                    {{-- Lado Izquierdo: Características --}}
                    <div class="lg:col-span-8 grid grid-cols-2 gap-x-12 gap-y-16">
                        {{-- Habitaciones --}}
                        <div class="flex flex-col items-start">
                            <span class="text-[11px] text-[#4F6D7A] font-black uppercase tracking-[0.25em] mb-4">Habitaciones</span>
                            <div class="flex items-center gap-4">
                                <div class="h-14 w-14 bg-primary/5 rounded-2xl flex items-center justify-center text-primary shadow-inner">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <span class="text-2xl font-black text-[#003049]">{{ $inmueble->habitaciones }} <span class="text-sm text-muted-foreground font-bold ml-1">Hab</span></span>
                            </div>
                        </div>
                        {{-- Baños --}}
                        <div class="flex flex-col items-start">
                            <span class="text-[11px] text-[#4F6D7A] font-black uppercase tracking-[0.25em] mb-4">Baños</span>
                            <div class="flex items-center gap-4">
                                <div class="h-14 w-14 bg-primary/5 rounded-2xl flex items-center justify-center text-primary shadow-inner">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12h18v3a4 4 0 01-4 4H7a4 4 0 01-4-4v-3zM3 12h18M21 12v-1a2 2 0 00-2-2h-3M7 12V7a3 3 0 013-3h2M12 2v4M14 3l-2 2M10 3l2 2M6 19v2M18 19v2" />
                                    </svg>
                                </div>
                                <span class="text-2xl font-black text-[#003049]">{{ $inmueble->banos }} <span class="text-sm text-muted-foreground font-bold ml-1">Baños</span></span>
                            </div>
                        </div>
                        {{-- Área --}}
                        <div class="flex flex-col items-start">
                            <span class="text-[11px] text-[#4F6D7A] font-black uppercase tracking-[0.25em] mb-4">Área</span>
                            <div class="flex items-center gap-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-[#003049]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                                </svg>
                                <span class="text-4xl font-black text-[#003049] leading-none">{{ number_format($inmueble->metros, 0) }}m²</span>
                            </div>
                        </div>
                        {{-- Depósito --}}
                        <div class="flex flex-col items-start">
                            <span class="text-[11px] text-[#4F6D7A] font-black uppercase tracking-[0.25em] mb-4">Depósito</span>
                            <div class="bg-[#F4F7F9] px-10 py-4 rounded-3xl border border-[#E5EDF2] shadow-sm">
                                <span class="text-3xl font-black text-[#003049] tracking-tight">${{ number_format($inmueble->deposito ?? 0) }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Lado Derecho: Información del Dueño --}}
                    <div class="lg:col-span-4 bg-slate-50/50 rounded-[2.5rem] p-8 border border-slate-100/80 shadow-inner flex flex-col justify-between h-full">
                        <div class="space-y-6">
                            <span class="text-[10px] font-black text-[#4F6D7A] uppercase tracking-[0.25em]">Publicado por</span>
                            <div class="flex items-center gap-4">
                                <div class="h-20 w-16 bg-white rounded-3xl flex items-center justify-center text-[#475569] font-black text-3xl shadow-xl border border-white/50 uppercase">
                                    {{ substr($inmueble->propietario->nombre ?? 'P', 0, 1) }}
                                </div>
                                <div class="space-y-1">
                                    <span class="block font-black text-[#003049] text-xl tracking-tight leading-none">{{ $inmueble->propietario->nombre ?? 'Anonimo' }}</span>
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-bold text-[#64748B] uppercase tracking-wider">Dueño</span>
                                        <span class="text-[10px] font-bold text-[#64748B] uppercase tracking-wider">Verificado</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8">
                            <a href="mailto:{{ $inmueble->propietario->email }}"
                                class="flex w-full items-center justify-center rounded-full bg-[#003049] py-5 text-white font-black shadow-2xl shadow-[#003049]/30 transition-all duration-500 hover:-translate-y-1.5 group/btn gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white transition-transform group-hover/btn:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <span class="uppercase tracking-[0.2em] text-sm leading-none">Contactar</span>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Radar de Servicios --}}
                <div class="space-y-10 pt-8 border-t border-slate-100/80">
                    <div class="flex items-center justify-between">
                        <h3 class="text-3xl font-extrabold text-[#003049] flex items-center gap-4">
                            <span class="text-3xl">📡</span> Radar de Servicios Cercanos
                        </h3>
                        <span class="text-xs bg-primary/10 text-primary px-4 py-1.5 rounded-full font-black tracking-widest uppercase">Escaneo en tiempo real</span>
                    </div>
                    <div id="nearby-services" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="flex items-center gap-4 text-muted-foreground italic text-sm py-6 bg-slate-50/50 rounded-3xl border border-dashed border-slate-200 px-8">
                            <div class="h-5 w-5 border-2 border-primary border-t-transparent rounded-full animate-spin"></div>
                            Analizando entorno de la propiedad...
                        </div>
                    </div>
                </div>

                {{-- Ubicación Exacta --}}
                <div class="space-y-10 pt-8 border-t border-slate-100/80">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <h3 class="text-3xl font-extrabold text-[#003049] flex items-center gap-4">
                            <span class="text-3xl text-red-500">📍</span> Ubicación en Mapa
                        </h3>
                        <div class="bg-primary/5 border border-primary/10 px-6 py-2.5 rounded-2xl flex items-center gap-3">
                            <span class="animate-pulse text-lg">💡</span>
                            <span class="text-xs text-primary font-black uppercase tracking-widest">Haz clic en cualquier punto para medir tiempos de llegada</span>
                        </div>
                    </div>

                    <div class="relative group/map rounded-[3rem] overflow-hidden ring-1 ring-slate-200 shadow-inner">
                        <div id="map" class="w-full h-[550px] bg-slate-100 z-10"></div>

                        {{-- Resultados de distancia flotantes --}}
                        <div id="distance-result"
                            class="hidden absolute bottom-10 right-10 w-96 bg-white/95 backdrop-blur-2xl p-8 rounded-[2.5rem] border border-[#003049]/10 shadow-[0_32px_64px_-16px_rgba(0,48,73,0.3)] z-[1000] animate-in fade-in zoom-in-95 duration-500">
                            <div class="flex items-center justify-between mb-8">
                                <span class="text-[11px] font-black text-[#4F6D7A] uppercase tracking-[0.25em]">⏱️ Análisis de Tiempo</span>
                                <button onclick="clearUserMarker()" class="p-2 rounded-xl hover:bg-red-50 text-red-400 transition-all hover:rotate-90">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <div class="space-y-4">
                                <div class="flex items-center gap-6 bg-[#F4F7F9] p-5 rounded-3xl border border-[#E5EDF2] transition-transform hover:scale-[1.02]">
                                    <div class="h-14 w-14 bg-white rounded-2xl shadow-sm flex items-center justify-center text-3xl">🚶</div>
                                    <div>
                                        <span class="block text-[10px] font-black text-[#4F6D7A] uppercase tracking-widest mb-1">Caminando</span>
                                        <span id="walk-time" class="block text-2xl font-black text-[#003049]">-- min</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-6 bg-[#F4F7F9] p-5 rounded-3xl border border-[#E5EDF2] transition-transform hover:scale-[1.02]">
                                    <div class="h-14 w-14 bg-white rounded-2xl shadow-sm flex items-center justify-center text-3xl">🚕</div>
                                    <div>
                                        <span class="block text-[10px] font-black text-[#4F6D7A] uppercase tracking-widest mb-1">En Taxi / Auto</span>
                                        <span id="taxi-time" class="block text-2xl font-black text-[#003049]">-- min</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>        </div>
    </div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        var userMarker;
        var map;

        function clearUserMarker() {
            if (userMarker && map) {
                map.removeLayer(userMarker);
                userMarker = null;
                document.getElementById('distance-result').classList.add('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            var lat = {{ $inmueble->latitud ?? 16.9068 }};
            var lng = {{ $inmueble->longitud ?? -92.0941 }};

            map = L.map('map').setView([lat, lng], 16);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
            L.marker([lat, lng]).addTo(map).bindPopup("<b>{{ $inmueble->titulo }}</b>");

            // Lógica de cálculo de distancias
            var userIcon = L.divIcon({
                html: '📍',
                className: 'user-marker-icon',
                iconSize: [30, 30],
                iconAnchor: [15, 30]
            });

            map.on('click', function(e) {
                if (userMarker) {
                    map.removeLayer(userMarker);
                }

                userMarker = L.marker(e.latlng, {
                        icon: userIcon,
                        draggable: true
                    }).addTo(map)
                    .bindPopup("<b>📍 Tu Referencia</b>").openPopup();

                updateDistance(e.latlng);

                userMarker.on('dragend', function(event) {
                    updateDistance(event.target.getLatLng());
                });
            });

            function updateDistance(userLoc) {
                var propLoc = L.latLng(lat, lng);
                var distance = userLoc.distanceTo(propLoc);

                var walkTime = Math.round(distance / 70);
                var taxiTime = Math.round(distance / 250) + 2;

                document.getElementById('distance-result').classList.remove('hidden');
                document.getElementById('walk-time').innerText = walkTime + ' min';
                document.getElementById('taxi-time').innerText = taxiTime + ' min';
            }

            async function scanEnvironment() {
                const query =
                    `[out:json];(node["amenity"~"hospital|pharmacy|clinic|doctors|school|university|kindergarten|taxi|bus_station|bus_stop|market|supermarket|bank|atm"](around:1000,${lat},${lng}););out body;`;
                const url = `https://overpass-api.de/api/interpreter?data=${encodeURIComponent(query)}`;

                try {
                    const response = await fetch(url);
                    const data = await response.json();
                    const container = document.getElementById('nearby-services');
                    container.innerHTML = '';

                    const categories = {
                        'hospital': {
                            icon: '🏥',
                            label: 'Centro de Salud'
                        },
                        'pharmacy': {
                            icon: '💊',
                            label: 'Farmacia'
                        },
                        'clinic': {
                            icon: '🏥',
                            label: 'Clínica'
                        },
                        'school': {
                            icon: '🎓',
                            label: 'Escuela'
                        },
                        'university': {
                            icon: '🎓',
                            label: 'Universidad'
                        },
                        'taxi': {
                            icon: '🚕',
                            label: 'Sitio de Taxis'
                        },
                        'bus_station': {
                            icon: '🚌',
                            label: 'Terminal de Autobuses'
                        },
                        'bus_stop': {
                            icon: '🚐',
                            label: 'Parada de Colectivo'
                        },
                        'supermarket': {
                            icon: '🛒',
                            label: 'Tienda/Super'
                        },
                        'bank': {
                            icon: '🏦',
                            label: 'Banco'
                        },
                        'atm': {
                            icon: '🏧',
                            label: 'Cajero Automático'
                        }
                    };

                    if (data.elements.length === 0) {
                        container.innerHTML =
                            '<p class="col-span-full text-muted-foreground text-sm italic">No se encontraron servicios registrados cerca de esta ubicación en Ocosingo.</p>';
                        return;
                    }

                    data.elements.slice(0, 8).forEach(el => {
                        const type = el.tags.amenity;
                        const info = categories[type] || {
                            icon: '📍',
                            label: 'Servicio'
                        };
                        let name = el.tags.name;

                        if (!name || name.toLowerCase() === type.toLowerCase()) {
                            name = info.label;
                        }

                        const div = document.createElement('div');
                        div.className =
                            "flex items-center gap-3 p-4 bg-white rounded-2xl border border-primary/5 shadow-sm";
                        div.innerHTML = `
                            <span class="text-2xl">${info.icon}</span>
                            <div>
                                <span class="block text-[9px] font-black text-primary uppercase tracking-widest">${info.label}</span>
                                <span class="block text-sm text-[#003049] font-bold leading-tight">${name}</span>
                            </div>
                        `;
                        container.appendChild(div);
                    });
                } catch (e) {
                    container.innerHTML = '<p class="text-xs text-red-100">Error al escanear mapa.</p>';
                }
            }
            scanEnvironment();
        });
    </script>
    <x-arrendito />
@endsection
