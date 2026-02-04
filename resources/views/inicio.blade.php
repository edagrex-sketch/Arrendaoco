@extends('layouts.app')

@section('title', 'Inicio')

@section('content')

    {{-- 
       1. HERO SECTION & BUSCADOR INTELIGENTE 
       (Integración del diseño "New Code" adaptado a Blade)
    --}}
    <section class="mb-12 px-4 py-8">
        <div class="w-full max-w-5xl mx-auto rounded-xl bg-card p-6 shadow-lg border border-border">

            <h2 class="mb-6 text-center text-3xl font-semibold text-card-foreground">
                Encuentra tu próximo hogar en Ocosingo
            </h2>

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
        </div>
    </section>

    {{-- 
        2. SECCIÓN DE PROPIEDADES 
        (Lógica original de Laravel + Estilo de tarjetas pulido)
    --}}
    <section class="container mx-auto px-4 mb-16">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold text-foreground">
                Propiedades Disponibles
            </h2>
            <span class="text-sm font-medium text-muted-foreground bg-secondary/50 px-3 py-1 rounded-full">
                {{ \App\Models\Inmueble::count() }} resultados
            </span>
        </div>

        {{-- Grid de Tarjetas --}}
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">

            @forelse ($inmuebles as $inmueble)
                {{-- Tarjeta de Propiedad --}}
                <div
                    class="group relative overflow-hidden rounded-xl border border-border bg-card text-card-foreground shadow-sm transition-all hover:shadow-lg hover:-translate-y-1">

                    {{-- Imagen --}}
                    <div class="relative h-52 w-full overflow-hidden bg-muted">
                        @if ($inmueble->imagen)
                            <img src="{{ $inmueble->imagen }}" alt="{{ $inmueble->titulo }}"
                                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                        @else
                            <div
                                class="absolute inset-0 flex flex-col items-center justify-center text-muted-foreground bg-secondary/30">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mb-2 opacity-50" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="text-xs font-medium">Sin imagen</span>
                            </div>
                        @endif

                        {{-- Badge de Precio --}}
                        <div
                            class="absolute top-3 right-3 bg-background/90 backdrop-blur-md px-3 py-1 rounded-full border border-border/50 shadow-sm">
                            <span class="font-bold text-primary">${{ number_format($inmueble->renta_mensual ?? 0) }}</span>
                            <span class="text-xs text-muted-foreground">/mes</span>
                        </div>
                    </div>

                    {{-- Contenido Tarjeta --}}
                    <div class="p-5">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h3
                                    class="font-semibold text-lg leading-tight line-clamp-1 group-hover:text-primary transition-colors">
                                    {{ $inmueble->titulo ?? 'Inmueble' }}
                                </h3>
                                <div class="flex items-center gap-1 mt-1 text-sm text-muted-foreground">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span class="line-clamp-1">{{ $inmueble->direccion ?? 'Ocosingo, Chiapas' }}</span>
                                </div>
                                @php $promedio = $inmueble->resenas->avg('puntuacion') ?? 0; @endphp
                                @if ($promedio > 0)
                                    <div class="flex items-center gap-1 mt-1">
                                        <svg class="w-3.5 h-3.5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <span
                                            class="text-xs font-bold text-slate-700">{{ number_format($promedio, 1) }}</span>
                                        <span
                                            class="text-[10px] text-muted-foreground">({{ $inmueble->resenas->count() }})</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Características --}}
                        <div class="flex gap-4 mt-4 text-sm text-muted-foreground border-t border-border pt-4">
                            <div class="flex items-center gap-1.5" title="Habitaciones">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 12h.01M12 12h.01M19 12h.01M6 21v-3a2 2 0 012-2h8a2 2 0 012 2v3" />
                                </svg>
                                <span>{{ $inmueble->habitaciones ?? 2 }}</span>
                            </div>
                            <div class="flex items-center gap-1.5" title="Baños">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064" />
                                </svg>
                                <span>{{ $inmueble->banos ?? 1 }}</span>
                            </div>
                            <div class="flex items-center gap-1.5 ml-auto">
                                <span
                                    class="bg-secondary px-2 py-0.5 rounded text-xs font-medium text-secondary-foreground">
                                    {{ ucfirst($inmueble->tipo ?? 'Casa') }}
                                </span>
                            </div>
                        </div>

                        {{-- Botón Ver Detalles --}}
                        <a href="{{ route('inmuebles.show', $inmueble) }}"
                            class="mt-4 flex w-full items-center justify-center rounded-lg bg-primary/10 px-4 py-2.5 text-sm font-medium text-primary hover:bg-primary hover:text-primary-foreground transition-all duration-300">
                            Ver Detalles
                        </a>
                    </div>
                </div>
            @empty
                {{-- ESTADO VACÍO --}}
                <div class="col-span-full py-16 text-center rounded-2xl border border-dashed border-border bg-card/50">
                    <div class="mx-auto mb-4 h-16 w-16 rounded-full bg-muted flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-muted-foreground" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-foreground">No se encontraron inmuebles</h3>
                    <p class="text-muted-foreground mb-6 max-w-sm mx-auto">
                        Intenta ajustar los filtros de búsqueda o sé el primero en publicar una propiedad.
                    </p>
                    <a href="{{ route('inmuebles.create') }}"
                        class="inline-flex h-10 items-center justify-center rounded-md bg-primary px-8 text-sm font-medium text-primary-foreground shadow transition-colors hover:bg-primary/90">
                        Publicar Propiedad
                    </a>
                </div>
            @endforelse
        </div>

        {{-- Paginación --}}
        <div class="mt-12">
            {{ $inmuebles->links() }}
        </div>
    </section>

    {{-- 3. CTA: Publicar Inmueble (Manteniendo el original que funciona bien) --}}
    <section class="mb-12">
        <div class="bg-primary px-6 py-16 rounded-3xl text-center relative overflow-hidden">
            {{-- Efecto de fondo sutil --}}
            <div class="absolute top-0 left-0 w-full h-full bg-white/5 pointer-events-none"></div>

            <div class="relative z-10">
                <h2 class="mb-4 text-3xl font-bold text-primary-foreground">
                    ¿Tienes una propiedad en Ocosingo?
                </h2>
                <p class="mb-8 text-lg text-primary-foreground/90 max-w-2xl mx-auto">
                    Únete a ArrendaOco y conecta con inquilinos verificados de la universidad y la ciudad.
                </p>
                <a href="{{ route('inmuebles.create') }}"
                    class="inline-flex h-12 items-center justify-center rounded-md bg-background px-8 text-sm font-medium text-primary shadow transition-colors hover:bg-background/90">
                    Publicar Inmueble Gratis
                </a>
            </div>
        </div>
    </section>

    {{-- Script de Alertas (Original) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                Swal.fire({
                    title: '¡Excelente!',
                    text: "{{ session('success') }}",
                    icon: 'success',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: 'hsl(var(--primary))' // Usando variable CSS si existe, o un color hex
                });
            @endif
        });
    </script>

@endsection
