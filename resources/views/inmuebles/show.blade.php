@extends('layouts.app')

@section('title', $inmueble->titulo)

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

            {{-- 🖼️ CARUSEL DE IMÁGENES --}}
            <div x-data="{
                active: 0,
                images: {{ $imagenes->count() > 0 ? $imagenes->pluck('ruta_imagen') : json_encode([$inmueble->imagen ?? '/placeholder.jpg']) }},
                next() { this.active = (this.active + 1) % this.images.length },
                prev() { this.active = (this.active - 1 + this.images.length) % this.images.length }
            }" class="relative bg-slate-200 group w-full"
                style="height: 600px; min-height: 400px;">

                {{-- Imágenes --}}
                <template x-for="(img, index) in images" :key="index">
                    <div x-show="active === index" x-transition:enter="transition ease-out duration-500"
                        x-transition:enter-start="opacity-0 scale-105" x-transition:enter-end="opacity-100 scale-100"
                        class="absolute inset-0">
                        <img :src="img" class="w-full h-full object-cover">
                    </div>
                </template>

                {{-- Overlay de Gradiente --}}
                <div class="absolute inset-0 bg-linear-to-t from-black/40 to-transparent"></div>

                {{-- Controles (Solo si hay más de una imagen) --}}
                <template x-if="images.length > 1">
                    <div>
                        <button @click="prev()"
                            class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/20 hover:bg-white/40 backdrop-blur-md text-white p-2 rounded-full transition-all opacity-0 group-hover:opacity-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <button @click="next()"
                            class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/20 hover:bg-white/40 backdrop-blur-md text-white p-2 rounded-full transition-all opacity-0 group-hover:opacity-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>

                        {{-- Indicadores --}}
                        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2">
                            <template x-for="(img, index) in images" :key="index">
                                <div @click="active = index" :class="active === index ? 'w-6 bg-white' : 'w-2 bg-white/50'"
                                    class="h-2 rounded-full cursor-pointer transition-all"></div>
                            </template>
                        </div>
                    </div>
                </template>

                {{-- Badge de Precio Flotante --}}
                <div
                    class="absolute bottom-4 right-4 bg-white/95 backdrop-blur text-[#003049] px-6 py-2 rounded-xl shadow-xl font-extrabold text-2xl border border-white/50">
                    ${{ number_format($inmueble->renta_mensual) }} <span
                        class="text-sm font-medium text-muted-foreground">/mes</span>
                </div>
            </div>

            {{-- Contenido --}}
            <div class="p-6 sm:p-10">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h1 class="text-4xl font-extrabold text-[#003049] mb-3 tracking-tight">{{ $inmueble->titulo }}</h1>
                        <p class="flex items-center text-muted-foreground text-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ $inmueble->direccion }}
                        </p>
                    </div>
                    <span
                        class="bg-[#FDF0D5] text-[#003049] border border-[#003049]/10 px-5 py-2 rounded-full text-sm font-bold uppercase tracking-wide">
                        {{ $inmueble->tipo }}
                    </span>
                </div>

                {{-- Estadísticas Rápidas --}}
                <div class="grid grid-cols-3 gap-8 py-8 border-y border-slate-100 mb-8">
                    <div class="text-center group">
                        <span
                            class="block text-3xl font-black text-[#003049] mb-1 group-hover:scale-110 transition-transform">
                            {{ $inmueble->habitaciones }}
                        </span>
                        <span class="text-xs text-muted-foreground uppercase font-bold tracking-widest">Habitaciones</span>
                    </div>
                    <div class="text-center border-x border-slate-100 group">
                        <span
                            class="block text-3xl font-black text-[#003049] mb-1 group-hover:scale-110 transition-transform">
                            {{ $inmueble->banos }}
                        </span>
                        <span class="text-xs text-muted-foreground uppercase font-bold tracking-widest">Baños</span>
                    </div>
                    <div class="text-center group">
                        <span
                            class="block text-3xl font-black text-[#003049] mb-1 group-hover:scale-110 transition-transform">
                            {{ $inmueble->metros }}
                        </span>
                        <span class="text-xs text-muted-foreground uppercase font-bold tracking-widest">m² Totales</span>
                    </div>
                </div>

                {{-- Descripción --}}
                <div class="mb-10">
                    <h3 class="text-xl font-bold text-[#003049] mb-4">Descripción de la propiedad</h3>
                    <p class="text-muted-foreground leading-relaxed text-lg">
                        {{ $inmueble->descripcion }}
                    </p>
                </div>

                {{-- 🛠️ BOTONES DE ACCIÓN INTELIGENTES --}}
                <div class="flex flex-col sm:flex-row gap-4 mt-8">
                    @auth
                        @if (auth()->id() === $inmueble->propietario_id)
                            {{-- VISTA PARA EL DUEÑO --}}
                            <a href="{{ route('inmuebles.edit', $inmueble) }}"
                                class="flex-1 bg-[#003049] text-white font-bold py-4 px-8 rounded-2xl hover:bg-[#003049]/90 transition-all shadow-xl shadow-blue-500/10 flex justify-center items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Editar mi anuncio
                            </a>

                            <a href="{{ route('inmuebles.index') }}"
                                class="flex-1 border-2 border-slate-100 bg-white text-[#003049] font-bold py-4 px-8 rounded-2xl hover:bg-slate-50 transition-colors text-center">
                                Gestionar mis propiedades
                            </a>
                        @else
                            {{-- VISTA PARA UN INTERESADO --}}
                            <button
                                class="flex-1 bg-[#C1121F] text-white font-extrabold py-4 px-8 rounded-2xl hover:bg-[#780000] transition-all shadow-xl shadow-red-500/20 flex justify-center items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                                ¡Me interesa! Iniciar Renta
                            </button>
                            <button
                                class="flex-1 border-2 border-[#003049] text-[#003049] font-bold py-4 px-8 rounded-2xl hover:bg-[#003049] hover:text-white transition-all">
                                Contactar Propietario
                            </button>
                        @endif
                    @else
                        {{-- VISITANTE --}}
                        <a href="{{ route('login') }}"
                            class="flex-1 bg-[#003049] text-white font-bold py-4 px-8 rounded-2xl hover:bg-[#003049]/90 transition-all text-center">
                            Inicia sesión para contactar al dueño
                        </a>
                    @endauth
                </div>

            </div>
        </div>
    </div>

@endsection
