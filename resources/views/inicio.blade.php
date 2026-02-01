@extends('layouts.app')

@section('title', 'Inicio')

@section('content')

    {{-- 1. Hero Section con Buscador --}}
    <section class="bg-primary/5 px-4 py-12 mb-12 rounded-3xl">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-4xl font-bold mb-6 text-foreground">
                Encuentra tu próximo hogar en Ocosingo
            </h1>

            {{-- Barra de búsqueda simulada (Visualmente idéntica) --}}
            <div class="relative max-w-2xl mx-auto">
                <input type="text" placeholder="Buscar por zona, precio o tipo..."
                    class="w-full h-14 pl-12 pr-4 rounded-full border border-input bg-card shadow-sm focus:ring-2 focus:ring-ring focus:border-transparent outline-hidden transition-all">
                {{-- Icono de lupa --}}
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-muted-foreground" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>
    </section>

    {{-- 2. Sección de Propiedades --}}
    <section class="container mx-auto px-4 mb-16">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold text-foreground">
                Propiedades Disponibles
            </h2>
            <span class="text-muted-foreground">
                {{ \App\Models\Inmueble::count() }} resultados
            </span>
        </div>

        {{-- Grid de Tarjetas --}}
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">

            @forelse (\App\Models\Inmueble::all() as $inmueble)
                {{-- Tarjeta de Propiedad (Estilo v0) --}}
                <div
                    class="group overflow-hidden rounded-xl border border-border bg-card text-card-foreground shadow-sm hover:shadow-md transition-all">

                    {{-- Imagen (Placeholder por ahora si no hay imagen real) --}}
                    <div class="relative h-48 w-full overflow-hidden bg-gray-200">
                        <!-- Aquí iría la imagen real -->
                        @if ($inmueble->imagen)
                            <img src="{{ $inmueble->imagen }}" alt="Foto" class="w-full h-full object-cover">
                        @else
                            <div class="absolute inset-0 flex items-center justify-center text-gray-400">
                                <span class="text-xs">Foto Inmueble</span>
                            </div>
                        @endif
                    </div>

                    {{-- Contenido Tarjeta --}}
                    <div class="p-5">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h3 class="font-semibold text-lg line-clamp-1 group-hover:text-primary transition-colors">
                                    {{ $inmueble->titulo ?? 'Inmueble sin nombre' }}
                                </h3>
                                <p class="text-sm text-muted-foreground line-clamp-1">
                                    {{ $inmueble->direccion ?? 'Ubicación no especificada' }}
                                </p>
                            </div>
                            <span
                                class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-hidden focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-primary text-primary-foreground hover:bg-primary/80">
                                {{ $inmueble->tipo ?? 'Casa' }}
                            </span>
                        </div>

                        <div class="flex items-baseline gap-1 mt-4">
                            <span class="text-2xl font-bold text-primary">
                                ${{ number_format($inmueble->renta_mensual ?? 0) }}
                            </span>
                            <span class="text-xs text-muted-foreground">/mes</span>
                        </div>

                        {{-- Footer Tarjeta: Habitaciones/Baños (Datos Mock por ahora si no existen en BD) --}}
                        <div class="flex gap-4 mt-4 text-sm text-muted-foreground border-t border-border pt-4">
                            <div class="flex items-center gap-1">
                                <span>🛏️ {{ $inmueble->habitaciones ?? 2 }} Hab</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <span>🚿 {{ $inmueble->banos ?? 1 }} Baños</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <span>📏 {{ $inmueble->metros ?? 50 }} m²</span>
                            </div>
                        </div>

                        {{-- Botón Ver Detalles --}}
                        <a href="{{ route('inmuebles.show', $inmueble) }}"
                            class="mt-4 block w-full rounded-lg bg-secondary px-4 py-2 text-center text-sm font-medium text-secondary-foreground hover:bg-secondary/80 transition-colors">
                            Ver Detalles
                        </a>
                    </div>
                </div>
            @empty
                {{-- ESTADO VACÍO: Se muestra si no hay inmuebles --}}
                <div class="col-span-full text-center py-12 px-4 rounded-2xl border border-dashed border-border bg-card/50">
                    <div class="bg-primary/5 rounded-full h-20 w-20 flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-primary" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-foreground mb-2">Aún no hay propiedades disponibles</h3>
                    <p class="text-muted-foreground mb-6 max-w-md mx-auto">
                        Actualmente no tenemos inmuebles registrados en la plataforma. ¡Puedes ser el primero en publicar el
                        tuyo!
                    </p>
                    <a href="{{ route('inmuebles.create') }}"
                        class="inline-flex items-center justify-center rounded-lg bg-primary px-6 py-2.5 text-sm font-semibold text-primary-foreground shadow hover:bg-primary/90 transition-colors">
                        Publicar Propiedad Ahora
                    </a>
                </div>
            @endforelse

        </div>
    </section>

    {{-- 3. CTA: Publicar Inmueble --}}
    <section class="bg-primary px-4 py-16 rounded-3xl text-center mb-12">
        <h2 class="mb-4 text-3xl font-bold text-primary-foreground">
            ¿Tienes una propiedad para rentar?
        </h2>
        <p class="mb-8 text-lg text-primary-foreground/80 max-w-2xl mx-auto">
            Publica tu inmueble y encuentra inquilinos confiables con historial verificado en todo Ocosingo.
        </p>
        <a href="{{ route('inmuebles.create') }}"
            class="rounded-lg bg-card px-8 py-3 font-semibold text-card-foreground transition-colors hover:bg-white/90">
            Publicar Inmueble
        </a>
    </section>

    {{-- Script de Alertas --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                Swal.fire({
                    title: '¡Felicidades!',
                    text: "{{ session('success') }}",
                    icon: 'success',
                    confirmButtonText: '¡Genial!',
                    confirmButtonColor: '#16a34a'
                });
            @endif
        });
    </script>

@endsection
