@extends('layouts.app')

@section('title', 'Resultados de Búsqueda')

@section('content')
    <style>
        /* Estilos para campos bloqueados (Invitados) */
        .input-locked {
            background-color: #f3f4f6;
            cursor: not-allowed;
            opacity: 0.7;
            position: relative;
        }

        .lock-badge {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #5D4037;
        }
    </style>

    {{-- 
       1. HERO SECTION & BUSCADOR INTELIGENTE (Consistente con Inicio)
    --}}
    <section class="mb-12 px-4 py-8">
        <div class="w-full max-w-5xl mx-auto rounded-xl bg-card p-6 shadow-lg border border-border">

            <h2 class="mb-6 text-center text-3xl font-semibold text-card-foreground">
                Propiedades Encontradas
            </h2>
            <p class="text-center text-muted-foreground mb-6">
                @if (request('ubicacion') || request('categoria') || request('rango_precio'))
                    Resultados para tus filtros seleccionados.
                @else
                    Mostrando todas las propiedades disponibles en Ocosingo.
                @endif
            </p>

            {{-- Formulario funcional que envía los filtros al controlador --}}
            <form action="{{ route('inmuebles.public_search') }}" method="GET"
                class="flex flex-col gap-4 md:flex-row items-end">

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
                            class="flex h-12 w-full rounded-md border border-input bg-background px-3 py-2 pl-10 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-hidden focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                    </div>
                </div>

                {{-- Select: Rango de Precio --}}
                <div class="relative w-full md:w-48">
                    <label class="text-sm font-medium mb-1.5 block text-muted-foreground ml-1">Precio</label>
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="absolute left-3 top-1/2 z-10 h-5 w-5 -translate-y-1/2 text-muted-foreground"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <select name="rango_precio"
                            class="flex h-12 w-full appearance-none rounded-md border border-input bg-background px-3 py-2 pl-10 text-sm ring-offset-background focus-visible:outline-hidden focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                            <option value="">Cualquiera</option>
                            <option value="0-2000" {{ request('rango_precio') == '0-2000' ? 'selected' : '' }}>$0 - $2,000
                            </option>
                            <option value="2000-4000" {{ request('rango_precio') == '2000-4000' ? 'selected' : '' }}>$2,000
                                - $4,000</option>
                            <option value="4000-6000" {{ request('rango_precio') == '4000-6000' ? 'selected' : '' }}>$4,000
                                - $6,000</option>
                            <option value="6000+" {{ request('rango_precio') == '6000+' ? 'selected' : '' }}>$6,000+
                            </option>
                        </select>
                    </div>
                </div>

                {{-- Select: Categoría --}}
                <div class="relative w-full md:w-48">
                    <label class="text-sm font-medium mb-1.5 block text-muted-foreground ml-1">Categoría</label>
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="absolute left-3 top-1/2 z-10 h-5 w-5 -translate-y-1/2 text-muted-foreground"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <select name="categoria"
                            class="flex h-12 w-full appearance-none rounded-md border border-input bg-background px-3 py-2 pl-10 text-sm ring-offset-background focus-visible:outline-hidden focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                            <option value="">Todas</option>
                            <option value="casa" {{ request('categoria') == 'casa' ? 'selected' : '' }}>Casa</option>
                            <option value="departamento" {{ request('categoria') == 'departamento' ? 'selected' : '' }}>
                                Departamento</option>
                            <option value="cuarto" {{ request('categoria') == 'cuarto' ? 'selected' : '' }}>Cuarto</option>
                        </select>
                    </div>
                </div>

                {{-- Botón Buscar --}}
                <button type="submit"
                    class="inline-flex h-12 items-center justify-center rounded-md bg-primary px-8 text-sm font-medium text-primary-foreground ring-offset-background transition-colors hover:bg-primary/90 focus-visible:outline-hidden focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 gap-2 w-full md:w-auto">
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
                    <p class="text-sm text-muted-foreground">
                        ¿Quieres usar los filtros avanzados?
                        <a href="{{ route('login') }}" class="text-[#5D4037] font-bold hover:underline">Inicia Sesión</a>
                    </p>
                </div>
            @endguest
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 mb-20">
        @if ($inmuebles->isEmpty())
            <div class="bg-white rounded-[3rem] p-20 text-center border border-slate-100 shadow-xl shadow-[#003049]/5 max-w-4xl mx-auto">
                <div class="bg-slate-50 w-24 h-24 rounded-3xl flex items-center justify-center mx-auto mb-8 text-[#003049] shadow-inner">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 opacity-40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <h3 class="text-3xl font-black text-[#003049] mb-4">No se encontraron resultados</h3>
                <p class="text-slate-500 text-lg mb-10 max-w-md mx-auto">Intenta ajustar tus filtros de ubicación, precio o categoría para encontrar la propiedad ideal.</p>
                <a href="{{ route('inmuebles.public_search') }}"
                    class="inline-flex items-center justify-center px-10 py-4 bg-[#003049] text-white font-black rounded-full hover:scale-105 transition-all shadow-xl shadow-[#003049]/20 group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 group-hover:rotate-180 transition-transform duration-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                    </svg>
                    Restablecer búsqueda
                </a>
            </div>
        @else
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6 border-b border-slate-100 pb-8">
                <div class="space-y-2">
                    <span class="text-[10px] font-black text-[#64748B] uppercase tracking-[0.3em]">Resultados de búsqueda</span>
                    <h2 class="text-4xl font-black text-[#003049] tracking-tight">
                        Estos son tus resultados
                    </h2>
                </div>
                <div class="bg-white px-6 py-2 rounded-full border border-slate-100 shadow-sm flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                    <span class="text-sm font-black text-[#003049]">
                        {{ $inmuebles->total() }} <span class="text-slate-400 font-bold uppercase text-[10px] ml-1">Propiedades</span>
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($inmuebles as $inmueble)
                    <div class="group bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md hover:-translate-y-1">
                        {{-- Imagen --}}
                        <div class="relative h-56 overflow-hidden">
                            @if ($inmueble->imagen)
                                <img src="{{ $inmueble->imagen_url }}" alt="{{ $inmueble->titulo }}"
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
                                            }
                                        })
                                        .finally(() => this.loading = false);
                                    }
                                }">
                                    <button @click.prevent="toggle()" 
                                        class="h-9 w-9 flex items-center justify-center rounded-full bg-white/20 backdrop-blur-md border border-white/30 text-white transition-all hover:bg-white hover:text-red-500"
                                        :class="isFavorited ? 'bg-white !text-red-500 shadow-sm' : ''">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" :fill="isFavorited ? 'currentColor' : 'none'" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
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
                                <div class="flex items-center gap-1.5 text-slate-600">
                                    <svg class="w-4 h-4 text-[#003049]/60" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M22 13V19C22 19.5523 21.5523 20 21 20H3C2.44772 20 2 19.5523 2 19V13C2 11.3431 3.34315 10 5 10H19C20.6569 10 22 11.3431 22 13ZM19 12H5C4.44772 12 4 12.4477 4 13V15H20V13C20 12.4477 19.5523 12 19 12ZM20 6H4V9H20V6Z" />
                                    </svg>
                                    <span class="text-sm font-bold">{{ $inmueble->habitaciones }} <span class="text-[10px] uppercase font-medium text-slate-400">Hab</span></span>
                                </div>
                                <div class="flex items-center gap-1.5 text-slate-600">
                                    <svg class="w-4 h-4 text-[#003049]/60" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M19 11C19.5523 11 20 11.4477 20 12V14C20 15.6569 18.6569 17 17 17H7C5.34315 17 4 15.6569 4 14V12C4 11.4477 4.44772 11 5 11H19ZM16 4H8V10H16V4ZM18 18H6V20H18V18Z" />
                                    </svg>
                                    <span class="text-sm font-bold">{{ $inmueble->banos }} <span class="text-[10px] uppercase font-medium text-slate-400">Baño</span></span>
                                </div>
                                <div class="flex items-center gap-1.5 text-slate-600">
                                    <svg class="w-4 h-4 text-[#003049]/60" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M3 7V5C3 3.89543 3.89543 3 5 3H7M17 3H19C20.1046 3 21 3.89543 21 5V7M21 17V19C21 20.1046 20.1046 21 19 21H17M7 21H5C3.89543 21 3 20.1046 3 19V17M9 9H15V15H9V9Z" />
                                    </svg>
                                    <span class="text-sm font-bold">{{ number_format($inmueble->metros, 0) }} <span class="text-[10px] uppercase font-medium text-slate-400">m²</span></span>
                                </div>
                            </div>

                            <a href="{{ route('inmuebles.show', $inmueble) }}"
                                class="flex w-full py-3 items-center justify-center rounded-xl bg-slate-100 text-sm font-bold text-[#003049] transition-all hover:bg-slate-200">
                                Ver Detalles
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-12 flex justify-center">
                {{ $inmuebles->withQueryString()->links() }}
            </div>
        @endif
        </div>
        </div>
    @endsection
