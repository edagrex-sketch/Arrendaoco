@extends('layouts.app')

@section('title', 'Inicio')

@section('content')

{{-- 
    =================================================================
    0. ARQUITECTURA UX/UI: ESTILOS, ANIMACIONES Y LOTTIE
    =================================================================
--}}
{{-- CAMBIO TÉCNICO: Usamos el player estándar para mejor control de tamaño --}}








    {{-- 1. HERO SECTION --}}
    <section class="mb-12 px-4 py-8">
        <div class="w-full max-w-5xl mx-auto rounded-xl bg-card p-6 shadow-lg border border-border">
            <h2 class="mb-6 text-center text-3xl font-semibold text-card-foreground">
                Encuentra tu próximo hogar en Ocosingo
            </h2>
            @auth
                <form action="{{ route('inmuebles.public_search') }}" method="GET"
                    class="flex flex-col gap-4 md:flex-row items-end">
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
                                class="flex h-12 w-full rounded-md border border-input bg-background px-3 py-2 pl-10 text-sm ring-offset-background focus-visible:outline-hidden focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                        </div>
                    </div>

                    <div class="relative w-full md:w-48">
                        <label class="text-sm font-medium mb-1.5 block text-muted-foreground ml-1">Precio</label>
                        <div class="relative">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="absolute left-3 top-1/2 z-10 h-5 w-5 -translate-y-1/2 text-muted-foreground" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <select name="rango_precio"
                                class="flex h-12 w-full appearance-none rounded-md border border-input bg-background px-3 py-2 pl-10 text-sm ring-offset-background focus-visible:outline-hidden focus-visible:ring-リング focus-visible:ring-offset-2">
                                <option value="">Cualquiera</option>
                                <option value="0-2000" {{ request('rango_precio') == '0-2000' ? 'selected' : '' }}>$0 - $2,000
                                </option>
                                <option value="2000-4000" {{ request('rango_precio') == '2000-4000' ? 'selected' : '' }}>$2,000
                                    - $4,000</option>
                                <option value="4000-6000" {{ request('rango_precio') == '4000-6000' ? 'selected' : '' }}>$4,000
                                    - $6,000</option>
                                <option value="6000+" {{ request('rango_precio') == '6000+' ? 'selected' : '' }}>$6,000+</option>
                            </select>
                        </div>
                    </div>
                    <div class="relative w-full md:w-48">
                        <label class="text-sm font-medium mb-1.5 block text-muted-foreground ml-1">Categoría</label>
                        <div class="relative">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="absolute left-3 top-1/2 z-10 h-5 w-5 -translate-y-1/2 text-muted-foreground" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
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
            @else
                <div class="relative group">
                    <div
                        class="absolute inset-0 bg-white/60 backdrop-blur-[2px] z-20 rounded-xl flex items-center justify-center transition-all group-hover:bg-white/40">
                        <a href="{{ route('login') }}"
                            class="bg-[#003049] text-white px-8 py-4 rounded-xl font-black shadow-2xl hover:scale-105 transition-all uppercase tracking-widest text-xs flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            Inicia sesión para buscar
                        </a>
                    </div>
                    <div class="flex flex-col gap-4 md:flex-row items-end opacity-40 grayscale pointer-events-none">
                        <div class="relative flex-1 w-full">
                            <label class="text-sm font-medium mb-1.5 block text-muted-foreground ml-1">Ubicación</label>
                            <div class="h-12 w-full rounded-md border border-input bg-background px-3 py-2 pl-10 text-sm">
                            </div>
                        </div>
                        <div class="relative w-full md:w-48">
                            <label class="text-sm font-medium mb-1.5 block text-muted-foreground ml-1">Precio</label>
                            <div class="h-12 w-full rounded-md border border-input bg-background px-3 py-2 pl-10 text-sm">
                            </div>
                        </div>
                        <div class="relative w-full md:w-48">
                            <label class="text-sm font-medium mb-1.5 block text-muted-foreground ml-1">Categoría</label>
                            <div class="h-12 w-full rounded-md border border-input bg-background px-3 py-2 pl-10 text-sm">
                            </div>
                        </div>
                        <div class="h-12 w-32 bg-primary rounded-md"></div>
                    </div>
                </div>
            @endauth
        </div>
    </section>

    {{-- 2. SECCIÓN DE PROPIEDADES --}}
    <section class="container mx-auto px-4 mb-16">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold text-foreground">Propiedades Disponibles</h2>
            <span class="text-sm font-medium text-muted-foreground bg-secondary/50 px-3 py-1 rounded-full">
                @guest Mostrando primeros resultados (Invitado) @else {{ $inmuebles->total() }} resultados @endguest
            </span>
        </div>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($inmuebles as $index => $inmueble)
                @php $isLocked = auth()->guest() && $index >= 3; @endphp
                <div class="group relative overflow-hidden rounded-xl border border-border bg-card text-card-foreground shadow-sm transition-all hover:shadow-lg hover:-translate-y-1">
                    <div class="relative h-52 w-full overflow-hidden bg-muted">
                        <div class="w-full h-full {{ $isLocked ? 'blur-content' : '' }}">
                            @if ($inmueble->imagen)
                                <img src="{{ $inmueble->imagen }}" alt="{{ $inmueble->titulo }}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                            @else
                                <div class="absolute inset-0 flex flex-col items-center justify-center text-muted-foreground bg-secondary/30">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mb-2 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    <span class="text-xs font-medium">Sin imagen</span>
                                </div>
                            @endif
                        </div>
                        @if($isLocked)
                        <div class="card-lock-overlay">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 lock-icon-hover" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        </div>
                        @endif
                        <div class="absolute top-3 right-3 bg-background/90 backdrop-blur-md px-3 py-1 rounded-full border border-border/50 shadow-sm">
                            <span class="font-bold text-primary">${{ number_format($inmueble->renta_mensual ?? 0) }}</span>
                            <span class="text-xs text-muted-foreground">/mes</span>
                        </div>
                    </div>
                    <div class="p-5">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h3 class="font-semibold text-lg leading-tight line-clamp-1 group-hover:text-primary transition-colors">{{ $inmueble->titulo ?? 'Inmueble' }}</h3>
                                <div class="flex items-center gap-1 mt-1 text-sm text-muted-foreground">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                    <span class="line-clamp-1">{{ $inmueble->direccion ?? 'Ocosingo, Chiapas' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 py-4 border-t border-slate-100 mt-4">
                            <div class="flex items-center gap-1.5 text-slate-500" title="Habitaciones">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="w-5 h-5 opacity-70" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 11v3a2 2 0 002 2h14a2 2 0 002-2v-3"></path><path d="M5 16v2"></path><path d="M19 16v2"></path><path d="M5 11V7a2 2 0 012-2h10a2 2 0 012 2v4"></path><path d="M5 11h14"></path>
                                </svg>
                                <span class="text-base font-bold text-slate-700">{{ $inmueble->habitaciones ?? 2 }} <span class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider ml-0.5">Hab</span></span>
                            </div>
                            <div class="flex items-center gap-1.5 text-slate-500" title="Baños">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="w-5 h-5 opacity-70" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M9 6 6.5 3.5a1.5 1.5 0 0 0-1-.5C4.683 3 4 3.683 4 4.5V17a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-5"/><line x1="10" x2="8" y1="5" y2="7"/><line x1="2" x2="22" y1="12" y2="12"/><line x1="7" x2="7" y1="19" y2="21"/><line x1="17" x2="17" y1="19" y2="21"/>
                                </svg>
                                <span class="text-base font-bold text-slate-700">{{ $inmueble->banos ?? 1 }} <span class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider ml-0.5">Baño</span></span>
                            </div>
                            <div class="flex items-center gap-1.5 text-slate-500" title="Superficie">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="w-5 h-5 opacity-70" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m21 21-6-6m6 6v-4.8m0 4.8h-4.8"/><path d="M3 16.2V21m0 0h4.8M3 21l6-6"/><path d="M21 7.8V3m0 0h-4.8M21 3l-6 6"/><path d="M3 7.8V3m0 0h4.8M3 3l6 6"/>
                                </svg>
                                <span class="text-base font-bold text-slate-700">{{ number_format($inmueble->metros ?? 0, 0) }} <span class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider ml-0.5">M²</span></span>
                            </div>
                        </div>
                        @auth
                            <a href="{{ route('inmuebles.show', $inmueble) }}" class="mt-4 flex w-full items-center justify-center rounded-lg bg-primary/10 px-4 py-2.5 text-sm font-medium text-primary hover:bg-primary hover:text-primary-foreground transition-all duration-300">
                                Ver Detalles
                            </a>
                        @else
                            <button type="button" onclick="window.location.href='{{ route('login') }}'" 
                                class="mt-4 flex w-full items-center justify-center rounded-lg bg-slate-50 border border-dashed border-slate-200 px-4 py-2.5 text-xs font-black text-slate-400 hover:bg-slate-100 transition-all duration-300 cursor-pointer uppercase tracking-widest gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                    <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" />
                                </svg> Sesión para Ver
                            </button>
                        @endauth
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center rounded-2xl border border-dashed border-border bg-card/50">
                    <div class="mx-auto mb-4 h-16 w-16 rounded-full bg-muted flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-foreground">No se encontraron inmuebles</h3>
                    <p class="text-muted-foreground mb-6 max-w-sm mx-auto">Intenta ajustar los filtros de búsqueda o sé el primero en publicar una propiedad.</p>
                </div>
            @endforelse
        </div>
        
        @auth
        <div class="mt-12">{{ $inmuebles->links() }}</div>
        @else
            @if($inmuebles->count() >= 15)
                <div class="mt-12 text-center bg-amber-50 p-6 rounded-lg border border-amber-200">
                    <p class="text-amber-800 font-semibold mb-2">¿Quieres ver más inmuebles?</p>
                    <p class="text-sm text-amber-700 mb-4">Regístrate para desbloquear la paginación completa y todos los filtros.</p>
                    <a href="{{ route('registro') }}" class="inline-block bg-[#5D4037] text-white px-6 py-2 rounded-full text-sm font-bold hover:bg-[#3E2723] transition-colors">Registrarse ahora</a>
                </div>
            @endif
        @endauth
    </section>

    {{-- 3. CTA: Publicar Inmueble --}}
    <section class="mb-12">
        <div class="bg-primary px-6 py-16 rounded-3xl text-center relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-full bg-white/5 pointer-events-none"></div>
            <div class="relative z-10">
                <h2 class="mb-4 text-3xl font-bold text-primary-foreground">¿Tienes una propiedad en Ocosingo?</h2>
                <p class="mb-8 text-lg text-primary-foreground/90 max-w-2xl mx-auto">Únete a ArrendaOco y conecta con inquilinos verificados de la universidad y la ciudad.</p>
                <a href="{{ route('inmuebles.create') }}" 
                   class="inline-flex h-12 items-center justify-center gap-2 rounded-md bg-white px-8 text-sm font-black text-[#003049] shadow-xl hover:scale-105 transition-all uppercase tracking-widest">
                   <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                       <path d="M3.105 2.289a.75.75 0 00-.826.95l1.414 4.925A1.5 1.5 0 005.135 9.25h6.115a.75.75 0 010 1.5H5.135a1.5 1.5 0 00-1.442 1.086l-1.414 4.926a.75.75 0 00.826.95 28.896 28.896 0 0015.293-7.154.75.75 0 000-1.115A28.897 28.897 0 003.105 2.289z" />
                   </svg>
                   Publicar Inmueble Gratis
                </a>
            </div>
        </div>
    </section>

    {{-- Script de Alertas y Animación --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Alertas originales de Laravel
            @if (session('success'))
                Swal.fire({
                    title: '¡Excelente!',
                    text: "{{ session('success') }}",
                    icon: 'success',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#5D4037'
                });
            @endif
        });
    </script>
@endsection