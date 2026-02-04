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

        {{-- 🌟 SECCIÓN DE RESEÑAS --}}
        <div class="mt-12 mb-16">
            <h2 class="text-3xl font-bold text-[#003049] mb-8 flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-500" fill="currentColor"
                    viewBox="0 0 24 24">
                    <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                </svg>
                Reseñas de la Comunidad
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Columna de Resumen y Formulario --}}
                <div class="md:col-span-1 space-y-6">
                    {{-- Tarjeta de Resumen --}}
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-border">
                        <div class="text-center">
                            @php
                                $promedio = $inmueble->resenas->avg('puntuacion') ?? 0;
                                $total = $inmueble->resenas->count();
                            @endphp
                            <div class="text-5xl font-black text-[#003049] mb-1">{{ number_format($promedio, 1) }}</div>
                            <div class="flex justify-center mb-2">
                                @for ($i = 1; $i <= 5; $i++)
                                    <svg class="w-5 h-5 {{ $i <= round($promedio) ? 'text-yellow-400' : 'text-slate-200' }}"
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                @endfor
                            </div>
                            <p class="text-sm text-muted-foreground">{{ $total }} reseñas publicadas</p>
                        </div>
                    </div>

                    {{-- Formulario para Nueva Reseña --}}
                    @auth
                        @php
                            $yaReseno = $inmueble->resenas->where('usuario_id', auth()->id())->first();
                            $esDuenio = auth()->id() === $inmueble->propietario_id;
                        @endphp

                        @if (!$esDuenio && !$yaReseno)
                            <div class="bg-[#F8FAFC] rounded-2xl p-6 border border-dashed border-slate-300">
                                <h3 class="font-bold text-[#003049] mb-4">Escribe tu opinión</h3>
                                <form action="{{ route('resenas.store', $inmueble) }}" method="POST"
                                    x-data="{ rating: 0, hover: 0 }">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-slate-700 mb-2">Calificación</label>
                                        <div class="flex gap-1">
                                            <input type="hidden" name="puntuacion" :value="rating">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <button type="button" @click="rating = {{ $i }}"
                                                    @mouseover="hover = {{ $i }}" @mouseleave="hover = 0"
                                                    class="focus:outline-none transition-transform hover:scale-125">
                                                    <svg class="w-8 h-8"
                                                        :class="(hover || rating) >= {{ $i }} ? 'text-yellow-400' :
                                                            'text-slate-300'"
                                                        fill="currentColor" viewBox="0 0 20 20">
                                                        <path
                                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                    </svg>
                                                </button>
                                            @endfor
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <textarea name="comentario" rows="3" required
                                            class="w-full rounded-xl border-slate-200 focus:ring-[#003049] focus:border-[#003049] text-sm"
                                            placeholder="¿Qué te pareció este lugar?"></textarea>
                                    </div>
                                    <button type="submit"
                                        class="w-full bg-[#003049] text-white font-bold py-3 rounded-xl hover:bg-[#003049]/90 transition-all text-sm">
                                        Publicar Comentario
                                    </button>
                                </form>
                            </div>
                        @elseif($yaReseno)
                            <div
                                class="bg-blue-50 text-blue-800 rounded-2xl p-4 text-sm flex items-start gap-3 border border-blue-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mt-0.5 shrink-0" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Ya has calificado esta propiedad. Puedes editar o eliminar tu reseña en la lista.</span>
                            </div>
                        @endif
                    @else
                        <div class="bg-slate-50 rounded-2xl p-6 text-center border border-slate-200">
                            <p class="text-sm text-slate-600 mb-4">Inicia sesión para compartir tu experiencia.</p>
                            <a href="{{ route('login') }}"
                                class="inline-block text-[#003049] font-bold text-sm hover:underline">Iniciar Sesión &rarr;</a>
                        </div>
                    @endauth
                </div>

                {{-- Columna de Listado de Reseñas --}}
                <div class="md:col-span-2 space-y-4">
                    @forelse ($inmueble->resenas as $resena)
                        <div class="bg-card rounded-2xl p-6 shadow-sm border border-border group relative overflow-hidden"
                            x-data="{ editing: false }">

                            {{-- Vista Normal --}}
                            <div x-show="!editing" class="transition-all">
                                <div class="flex justify-between items-start mb-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="h-10 w-10 rounded-full bg-[#669BBC] flex items-center justify-center text-white font-bold">
                                            {{ strtoupper(substr($resena->usuario->nombre, 0, 1)) }}
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-[#003049] text-sm">{{ $resena->usuario->nombre }}
                                            </h4>
                                            <p
                                                class="text-[10px] uppercase tracking-widest text-muted-foreground font-bold">
                                                {{ $resena->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex gap-0.5">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 {{ $i <= $resena->puntuacion ? 'text-yellow-400' : 'text-slate-200' }}"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        @endfor
                                    </div>
                                </div>
                                <p class="text-slate-600 leading-relaxed italic">"{{ $resena->comentario }}"</p>

                                {{-- Acciones (Editar/Eliminar) --}}
                                @auth
                                    @if (auth()->id() === $resena->usuario_id || auth()->user()->es_admin || auth()->user()->tieneRol('admin'))
                                        <div class="mt-4 flex gap-3 pt-4 border-t border-slate-50">
                                            @if (auth()->id() === $resena->usuario_id)
                                                <button @click="editing = true"
                                                    class="flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-[#003049] font-bold text-[10px] uppercase tracking-wider rounded-lg border border-blue-100 hover:bg-[#003049] hover:text-white transition-all duration-300">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                    </svg>
                                                    Editar
                                                </button>
                                            @endif

                                            @php $confirmId = 'resena-' . $resena->id; @endphp
                                            <form id="delete-form-{{ $confirmId }}"
                                                action="{{ route('resenas.destroy', $resena) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" onclick="confirmDelete('{{ $confirmId }}')"
                                                    class="flex items-center gap-1.5 px-3 py-1.5 bg-red-50 text-red-600 font-bold text-[10px] uppercase tracking-wider rounded-lg border border-red-100 hover:bg-red-600 hover:text-white transition-all duration-300">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                @endauth
                            </div>

                            {{-- Vista Edición --}}
                            @auth
                                @if (auth()->id() === $resena->usuario_id)
                                    <div x-show="editing" x-cloak class="transition-all">
                                        <form action="{{ route('resenas.update', $resena) }}" method="POST"
                                            x-data="{ currentRating: {{ $resena->puntuacion }}, hoverRating: 0 }">
                                            @csrf
                                            @method('PUT')
                                            <div class="mb-4">
                                                <div class="flex gap-1 mb-2">
                                                    <input type="hidden" name="puntuacion" :value="currentRating">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <button type="button" @click="currentRating = {{ $i }}"
                                                            @mouseover="hoverRating = {{ $i }}"
                                                            @mouseleave="hoverRating = 0" class="focus:outline-none">
                                                            <svg class="w-6 h-6"
                                                                :class="(hoverRating || currentRating) >= {{ $i }} ?
                                                                    'text-yellow-400' : 'text-slate-300'"
                                                                fill="currentColor" viewBox="0 0 20 20">
                                                                <path
                                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                            </svg>
                                                        </button>
                                                    @endfor
                                                </div>
                                                <textarea name="comentario" rows="2" required class="w-full rounded-xl border-slate-200 text-sm p-3">{{ $resena->comentario }}</textarea>
                                            </div>
                                            <div class="flex gap-3">
                                                <button type="submit"
                                                    class="bg-[#003049] text-white px-4 py-2 rounded-lg text-xs font-bold">Guardar</button>
                                                <button type="button" @click="editing = false"
                                                    class="bg-slate-100 text-slate-600 px-4 py-2 rounded-lg text-xs font-bold">Cancelar</button>
                                            </div>
                                        </form>
                                    </div>
                                @endif
                            @endauth

                        </div>
                    @empty
                        <div class="text-center py-12 bg-slate-50 rounded-2xl border border-dashed border-slate-300">
                            <div class="text-slate-400 mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                </svg>
                            </div>
                            <p class="text-slate-500 font-medium">Aún no hay reseñas. ¡Sé el primero en opinar!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

@endsection
