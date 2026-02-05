@extends('layouts.app')

@section('title', $inmueble->titulo)

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8 lg:py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

            {{-- COLUMNA IZQUIERDA --}}
            <div class="lg:col-span-2 space-y-10">
                <div class="relative group rounded-3xl overflow-hidden shadow-2xl bg-muted aspect-video">
                    @if ($inmueble->imagen)
                        <img src="{{ $inmueble->imagen }}" alt="{{ $inmueble->titulo }}" class="w-full h-full object-cover">
                    @endif
                    <div
                        class="absolute top-5 left-5 bg-white/90 backdrop-blur px-4 py-2 rounded-2xl shadow-lg border border-white/20">
                        <span
                            class="text-[#003049] font-black text-2xl">${{ number_format($inmueble->renta_mensual) }}</span>
                        <span class="text-muted-foreground text-sm font-bold uppercase tracking-widest ml-1">/ mes</span>
                    </div>
                </div>

                <div>
                    <h1 class="text-4xl font-extrabold text-[#003049] mb-3 tracking-tight">{{ $inmueble->titulo }}</h1>
                    <p class="flex items-center text-muted-foreground text-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        </svg>
                        {{ $inmueble->direccion }}, Ocosingo
                    </p>
                </div>

                {{-- Grid de Características MEJORADO --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 py-8 border-y border-slate-100">
                    <div class="flex flex-col items-center">
                        <span
                            class="text-[10px] text-muted-foreground font-bold mb-2 uppercase tracking-widest">Habitaciones</span>
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-primary" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    d="M7 13v-2h10v2h3v6h-2v-2H6v2H4v-6h3zm0-8h10a2 2 0 012 2v4H5V7a2 2 0 012-2zm2 2v2h2V7H9zm4 0v2h2V7h-2z" />
                            </svg>
                            <span class="text-2xl font-black text-[#003049]">{{ $inmueble->habitaciones }}</span>
                        </div>
                    </div>
                    <div class="flex flex-col items-center">
                        <span
                            class="text-[10px] text-muted-foreground font-bold mb-2 uppercase tracking-widest">Baños</span>
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-primary" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    d="M4 11V8a1 1 0 011-1h1V4a2 2 0 012-2h8a2 2 0 012 2v3h1a1 1 0 011 1v3h-1v5a4 4 0 01-4 4H9a4 4 0 01-4-4v-5H4zm11-7H9v3h6V4zM7 11h10v3a2 2 0 01-2 2H9a2 2 0 01-2-2v-3z" />
                            </svg>
                            <span class="text-2xl font-black text-[#003049]">{{ $inmueble->banos }}</span>
                        </div>
                    </div>
                    <div class="flex flex-col items-center">
                        <span class="text-[10px] text-muted-foreground font-bold mb-2 uppercase tracking-widest">Área</span>
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-primary" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                            </svg>
                            <span class="text-2xl font-black text-[#003049]">{{ $inmueble->metros }}m²</span>
                        </div>
                    </div>
                    <div class="flex flex-col items-center text-center">
                        <span
                            class="text-[10px] text-muted-foreground font-bold mb-2 uppercase tracking-widest">Depósito</span>
                        <span
                            class="text-xl font-black text-[#003049] bg-slate-50 px-3 py-1 rounded-lg border border-slate-100">${{ number_format($inmueble->deposito ?? 0) }}</span>
                    </div>
                </div>

                {{-- Radar de Servicios --}}
                <div
                    class="bg-primary/5 rounded-3xl p-8 border border-primary/10 transition-all hover:bg-white hover:shadow-xl hover:shadow-primary/5">
                    <h3 class="text-xl font-bold text-[#003049] mb-6 flex items-center gap-2">
                        📡 Radar de Servicios Cercanos
                        <span
                            class="text-[10px] bg-primary/20 text-primary px-2 py-1 rounded-full font-bold">ACTUALIZADO</span>
                    </h3>
                    <div id="nearby-services" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center gap-3 text-muted-foreground italic text-sm py-4">
                            🔄 Analizando servicios en Ocosingo...
                        </div>
                    </div>
                </div>

                {{-- Mapa --}}
                <div class="space-y-4">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-2">
                        <h3 class="text-xl font-bold text-[#003049]">📍 Mapa de Ubicación</h3>
                        <span
                            class="text-[10px] text-primary font-bold uppercase tracking-widest bg-primary/5 px-2 py-1 rounded-lg">✨
                            Haz clic en el mapa para medir tiempos de llegada</span>
                    </div>

                    <div id="map"
                        class="w-full h-[350px] rounded-3xl border border-border shadow-inner bg-slate-100 z-10"></div>

                    {{-- Div oculto para resultados de distancia --}}
                    <div id="distance-result"
                        class="hidden bg-white p-4 rounded-2xl border border-primary/10 shadow-sm animate-in fade-in slide-in-from-top-2 duration-300">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-black text-primary uppercase tracking-tighter">⏱️ Tiempo estimado
                                desde tu punto:</span>
                            <button onclick="clearUserMarker()"
                                class="text-[10px] text-muted-foreground hover:text-red-500 font-bold uppercase">Eliminar
                                punto</button>
                        </div>
                        <div class="grid grid-cols-2 gap-4 mt-2">
                            <div class="flex items-center gap-3 bg-slate-50 p-3 rounded-xl">
                                <span class="text-xl">🚶</span>
                                <div>
                                    <span
                                        class="block text-[9px] font-bold text-muted-foreground uppercase">Caminando</span>
                                    <span id="walk-time" class="block text-sm font-black text-[#003049]">-- min</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 bg-slate-50 p-3 rounded-xl">
                                <span class="text-xl">🚕</span>
                                <div>
                                    <span class="block text-[9px] font-bold text-muted-foreground uppercase">Taxi</span>
                                    <span id="taxi-time" class="block text-sm font-black text-[#003049]">-- min</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- COLUMNA DERECHA --}}
            <div class="space-y-6">
                <div class="bg-white border border-slate-100 rounded-3xl p-8 shadow-xl space-y-8 sticky top-8">
                    <div class="flex items-center gap-4">
                        <div
                            class="h-16 w-16 bg-primary/10 rounded-full flex items-center justify-center text-primary font-black text-2xl">
                            {{ substr($inmueble->propietario->name ?? 'P', 0, 1) }}
                        </div>
                        <div>
                            <span
                                class="block text-xs font-bold text-muted-foreground uppercase tracking-widest">Dueño</span>
                            <span
                                class="block text-xl font-extrabold text-[#003049]">{{ $inmueble->propietario->name ?? 'Anonimo' }}</span>
                        </div>
                    </div>
                    <a href="mailto:{{ $inmueble->propietario->email }}"
                        class="flex w-full items-center justify-center rounded-2xl bg-[#003049] py-4 text-white font-black shadow-lg uppercase tracking-widest gap-2">
                        ✉️ Contactar vía email
                    </a>
                </div>
            </div>
        </div>
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
