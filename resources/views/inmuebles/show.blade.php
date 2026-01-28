@extends('layouts.app')

@section('title', $inmueble->nombre)

@section('content')

    <div class="max-w-4xl mx-auto">

        {{-- Botón Volver --}}
        <a href="{{ route('inicio') }}"
            class="inline-flex items-center gap-2 text-muted-foreground hover:text-foreground mb-6 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Volver al inicio
        </a>

        {{-- Tarjeta Principal --}}
        <div class="bg-card rounded-2xl shadow-sm border border-border overflow-hidden">

            {{-- Imagen Grande (Placeholder) --}}
            <div class="h-64 sm:h-80 bg-slate-200 relative flex items-center justify-center">
                @if ($inmueble->imagen)
                    <img src="{{ $inmueble->imagen }}" alt="{{ $inmueble->nombre }}" class="w-full h-full object-cover">
                @else
                    <span class="text-slate-400 text-lg font-medium">📷 Sin fotografía disponible</span>
                @endif

                {{-- Badge de Precio Flotante --}}
                <div
                    class="absolute bottom-4 right-4 bg-white/90 backdrop-blur text-primary px-4 py-2 rounded-lg shadow-lg font-bold text-xl border border-white/50">
                    ${{ number_format($inmueble->precio) }} <span
                        class="text-sm font-normal text-muted-foreground">/mes</span>
                </div>
            </div>

            {{-- Contenido --}}
            <div class="p-6 sm:p-8">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h1 class="text-3xl font-bold text-foreground mb-2">{{ $inmueble->nombre }}</h1>
                        <p class="flex items-center text-muted-foreground">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-primary" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ $inmueble->direccion ?? 'Ocosingo, Chiapas' }}
                        </p>
                    </div>
                    <span
                        class="bg-primary/10 text-primary border border-primary/20 px-3 py-1 rounded-full text-sm font-semibold capitalize">
                        {{ $inmueble->tipo ?? 'Inmueble' }}
                    </span>
                </div>

                {{-- Estadísticas Rápidas --}}
                <div class="grid grid-cols-3 gap-4 py-6 border-y border-border mb-6">
                    <div class="text-center">
                        <span class="block text-2xl font-bold text-foreground">
                            {{ $inmueble->habitaciones ?? '-' }}
                        </span>
                        <span class="text-xs text-muted-foreground uppercase tracking-wider">Habitaciones</span>
                    </div>
                    <div class="text-center border-l border-border">
                        <span class="block text-2xl font-bold text-foreground">
                            {{ $inmueble->banos ?? '-' }}
                        </span>
                        <span class="text-xs text-muted-foreground uppercase tracking-wider">Baños</span>
                    </div>
                    <div class="text-center border-l border-border">
                        <span class="block text-2xl font-bold text-foreground">
                            {{ $inmueble->metros ?? '-' }}
                        </span>
                        <span class="text-xs text-muted-foreground uppercase tracking-wider">m²</span>
                    </div>
                </div>

                {{-- Descripción --}}
                <div class="mb-8">
                    <h3 class="text-lg font-semibold mb-2">Descripción</h3>
                    <p class="text-muted-foreground leading-relaxed">
                        {{ $inmueble->descripcion ?? 'El propietario no ha añadido una descripción detallada para este inmueble.' }}
                    </p>
                </div>

                {{-- Botón de Acción --}}
                <div class="flex flex-col sm:flex-row gap-4 mt-8">
                    <button
                        class="flex-1 bg-primary text-primary-foreground font-bold py-3 px-6 rounded-xl hover:bg-primary/90 transition-all shadow-lg shadow-primary/20 hover:shadow-primary/40 flex justify-center items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a1 1 0 100-2 1 1 0 000 2z"
                                clip-rule="evenodd" />
                        </svg>
                        ¡Me interesa! Iniciar Renta
                    </button>

                    <button
                        class="flex-1 sm:flex-none border border-border bg-transparent text-foreground font-semibold py-3 px-6 rounded-xl hover:bg-slate-50 transition-colors">
                        Contactar Propietario
                    </button>
                </div>

            </div>
        </div>
    </div>

@endsection
