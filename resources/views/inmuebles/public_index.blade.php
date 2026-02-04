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
                @if(request('ubicacion') || request('categoria') || request('rango_precio'))
                    Resultados para tus filtros seleccionados.
                @else
                    Mostrando todas las propiedades disponibles en Ocosingo.
                @endif
            </p>

            {{-- Formulario funcional que envía los filtros al controlador --}}
            <form action="{{ route('inmuebles.public_search') }}" method="GET" class="flex flex-col gap-4 md:flex-row items-end">
                
                {{-- Input: Ubicación (SIEMPRE DISPONIBLE) --}}
                <div class="relative flex-1 w-full">
                    <label class="text-sm font-medium mb-1.5 block text-muted-foreground ml-1">Ubicación</label>
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <input 
                            type="text" 
                            name="ubicacion" 
                            value="{{ request('ubicacion') }}"
                            placeholder="Ej: Centro, Las Margaritas..."
                            class="flex h-12 w-full rounded-md border border-input bg-background px-3 py-2 pl-10 text-sm ring-offset-background focus-visible:outline-hidden focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
                        >
                    </div>
                </div>

                @auth
                    {{-- Select: Rango de Precio --}}
                    <div class="relative w-full md:w-48">
                        <label class="text-sm font-medium mb-1.5 block text-muted-foreground ml-1">Precio</label>
                        <div class="relative">
                            <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 z-10 h-5 w-5 -translate-y-1/2 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <select 
                                name="rango_precio" 
                                class="flex h-12 w-full appearance-none rounded-md border border-input bg-background px-3 py-2 pl-10 text-sm ring-offset-background focus-visible:outline-hidden focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
                            >
                                <option value="">Cualquiera</option>
                                <option value="0-2000" {{ request('rango_precio') == '0-2000' ? 'selected' : '' }}>$0 - $2,000</option>
                                <option value="2000-4000" {{ request('rango_precio') == '2000-4000' ? 'selected' : '' }}>$2,000 - $4,000</option>
                                <option value="4000-6000" {{ request('rango_precio') == '4000-6000' ? 'selected' : '' }}>$4,000 - $6,000</option>
                                <option value="6000+" {{ request('rango_precio') == '6000+' ? 'selected' : '' }}>$6,000+</option>
                            </select>
                        </div>
                    </div>

                    {{-- Select: Categoría --}}
                    <div class="relative w-full md:w-48">
                        <label class="text-sm font-medium mb-1.5 block text-muted-foreground ml-1">Categoría</label>
                        <div class="relative">
                            <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 z-10 h-5 w-5 -translate-y-1/2 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <select 
                                name="categoria" 
                                class="flex h-12 w-full appearance-none rounded-md border border-input bg-background px-3 py-2 pl-10 text-sm ring-offset-background focus-visible:outline-hidden focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
                            >
                                <option value="">Todas</option>
                                <option value="casa" {{ request('categoria') == 'casa' ? 'selected' : '' }}>Casa</option>
                                <option value="departamento" {{ request('categoria') == 'departamento' ? 'selected' : '' }}>Departamento</option>
                                <option value="cuarto" {{ request('categoria') == 'cuarto' ? 'selected' : '' }}>Cuarto</option>
                            </select>
                        </div>
                    </div>
                @else
                    {{-- ESTADO BLOQUEADO PARA INVITADOS --}}
                    <div class="relative w-full md:w-96 flex gap-2">
                        <div class="w-1/2">
                            <label class="text-sm font-medium mb-1.5 block text-muted-foreground ml-1">Precio</label>
                            <div onclick="mostrarAlertaRegistro()" class="flex h-12 w-full items-center rounded-md border border-dashed border-[#5D4037]/30 bg-[#5D4037]/5 px-3 text-sm text-muted-foreground input-locked cursor-pointer transition hover:bg-[#5D4037]/10">
                                <span>Bloqueado</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 lock-badge" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                            </div>
                        </div>
                        <div class="w-1/2">
                            <label class="text-sm font-medium mb-1.5 block text-muted-foreground ml-1">Categoría</label>
                            <div onclick="mostrarAlertaRegistro()" class="flex h-12 w-full items-center rounded-md border border-dashed border-[#5D4037]/30 bg-[#5D4037]/5 px-3 text-sm text-muted-foreground input-locked cursor-pointer transition hover:bg-[#5D4037]/10">
                                <span>Bloqueado</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 lock-badge" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                            </div>
                        </div>
                    </div>
                @endauth

                {{-- Botón Buscar --}}
                <button type="submit" class="inline-flex h-12 items-center justify-center rounded-md bg-primary px-8 text-sm font-medium text-primary-foreground ring-offset-background transition-colors hover:bg-primary/90 focus-visible:outline-hidden focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 gap-2 w-full md:w-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
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

    <section class="container mx-auto px-4 mb-16">

    @if ($inmuebles->isEmpty())
        <div class="bg-white rounded-3xl p-20 text-center border-2 border-dashed border-slate-200">
            <div class="bg-[#FDF0D5] w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6 text-[#003049]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-[#003049] mb-2">No se encontraron resultados</h3>
            <p class="text-muted-foreground text-lg mb-8">Intenta ajustar tus filtros de búsqueda para encontrar lo que buscas.</p>
            <a href="{{ route('inmuebles.public_search') }}" class="text-[#C1121F] font-bold hover:underline flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                </svg>
                Seguir buscando
            </a>
        </div>
    @else
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold text-foreground">
                Resultados de búsqueda
            </h2>
            <span class="text-sm font-medium text-muted-foreground bg-secondary/50 px-3 py-1 rounded-full">
                {{ $inmuebles->total() }} resultados
            </span>
        </div>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($inmuebles as $inmueble)
                <div class="group relative overflow-hidden rounded-xl border border-border bg-card text-card-foreground shadow-sm transition-all hover:shadow-lg hover:-translate-y-1">
                    {{-- Imagen --}}
                    <div class="relative h-52 w-full overflow-hidden bg-muted">
                        @if ($inmueble->imagen)
                            <img src="{{ $inmueble->imagen }}" alt="{{ $inmueble->titulo }}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                        @else
                            <div class="absolute inset-0 flex flex-col items-center justify-center text-muted-foreground bg-secondary/30">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mb-2 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="text-xs font-medium">Sin imagen</span>
                            </div>
                        @endif
                        
                        {{-- Badge de Precio --}}
                        <div class="absolute top-3 right-3 bg-background/90 backdrop-blur-md px-3 py-1 rounded-full border border-border/50 shadow-sm">
                            <span class="font-bold text-primary">${{ number_format($inmueble->renta_mensual ?? 0) }}</span>
                            <span class="text-xs text-muted-foreground">/mes</span>
                        </div>
                    </div>

                    {{-- Contenido Tarjeta --}}
                    <div class="p-5">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h3 class="font-semibold text-lg leading-tight line-clamp-1 group-hover:text-primary transition-colors">
                                    {{ $inmueble->titulo ?? 'Inmueble' }}
                                </h3>
                                <div class="flex items-center gap-1 mt-1 text-sm text-muted-foreground">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span class="line-clamp-1">{{ $inmueble->direccion ?? 'Ocosingo, Chiapas' }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Características --}}
                        <div class="flex gap-4 mt-4 text-sm text-muted-foreground border-t border-border pt-4">
                            <div class="flex items-center gap-1.5" title="Habitaciones">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 21v-3a2 2 0 012-2h8a2 2 0 012 2v3" />
                                </svg>
                                <span>{{ $inmueble->habitaciones ?? 2 }}</span>
                            </div>
                            <div class="flex items-center gap-1.5" title="Baños">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064" />
                                </svg>
                                <span>{{ $inmueble->banos ?? 1 }}</span>
                            </div>
                            <div class="flex items-center gap-1.5 ml-auto">
                                <span class="bg-secondary px-2 py-0.5 rounded text-xs font-medium text-secondary-foreground">
                                    {{ ucfirst($inmueble->tipo ?? 'Casa') }}
                                </span>
                            </div>
                        </div>

                        {{-- Botón Ver Detalles --}}
                        @auth
                            <a href="{{ route('inmuebles.show', $inmueble) }}"
                                class="mt-4 flex w-full items-center justify-center rounded-lg bg-primary/10 px-4 py-2.5 text-sm font-medium text-primary hover:bg-primary hover:text-primary-foreground transition-all duration-300">
                                Ver Detalles
                            </a>
                        @else
                            <button type="button" onclick="mostrarAlertaRegistro()"
                                class="mt-4 flex w-full items-center justify-center rounded-lg bg-gray-100 px-4 py-2.5 text-sm font-medium text-gray-500 hover:bg-gray-200 transition-all duration-300 cursor-pointer">
                                Ver Detalles 🔒
                            </button>
                        @endauth
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-12">
            {{ $inmuebles->withQueryString()->links() }}
        </div>
    @endif
    </section>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function mostrarAlertaRegistro() {
            Swal.fire({
                title: '¡Contenido Exclusivo!',
                text: "Regístrate o inicia sesión para ver más detalles y usar los filtros.",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#003049',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Iniciar Sesión',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('login') }}";
                }
            })
        }
    </script>
@endsection
