@extends('layouts.app')

@section('title', $inmueble->titulo)

@section('content')
    <div class="max-w-5xl mx-auto px-4 py-8 lg:py-12"
         x-data="{ 
            active: 0, 
            showFullscreen: false,
            images: [
                '{{ $inmueble->imagen }}',
                @foreach($imagenes as $img)
                    @if($img->ruta_imagen !== $inmueble->imagen)
                        '{{ $img->ruta_imagen }}',
                    @endif
                @endforeach
            ],
            next() { this.active = (this.active + 1) % this.images.length; },
            prev() { this.active = (this.active - 1 + this.images.length) % this.images.length; },
            init() {
                setInterval(() => { if(!this.showFullscreen) this.next(); }, 5000);
            }
         }"
         @keydown.right.window="if(showFullscreen) next()"
         @keydown.left.window="if(showFullscreen) prev()"
         @keydown.escape.window="showFullscreen = false">
        
        <div class="bg-white rounded-[3rem] shadow-2xl shadow-[#003049]/10 border border-slate-100 overflow-hidden">
            {{-- Imagen Principal con Carrusel --}}
            <div class="relative group aspect-[21/9] overflow-hidden bg-slate-200">
                
                {{-- Imágenes del Carrusel --}}
                <template x-for="(img, index) in images" :key="index">
                    <img :src="img" 
                         x-show="active === index"
                         x-transition:enter="transition ease-out duration-1000"
                         x-transition:enter-start="opacity-0 scale-105"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="absolute inset-0 w-full h-full object-cover cursor-pointer"
                         @click="showFullscreen = true">
                </template>
                
                {{-- Overlay y Gradiente --}}
                <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-black/20 pointer-events-none"></div>

                {{-- Controles del Carrusel Principal --}}
                <button @click="prev()" class="absolute left-4 top-1/2 -track-y-1/2 z-20 h-10 w-10 flex items-center justify-center rounded-full bg-white/10 backdrop-blur-md text-white border border-white/20 opacity-0 group-hover:opacity-100 transition-all hover:bg-white/30">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                </button>
                <button @click="next()" class="absolute right-4 top-1/2 -track-y-1/2 z-20 h-10 w-10 flex items-center justify-center rounded-full bg-white/10 backdrop-blur-md text-white border border-white/20 opacity-0 group-hover:opacity-100 transition-all hover:bg-white/30">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19l7-7-7-7" /></svg>
                </button>

                {{-- Badge de Precio --}}
                <div class="absolute top-6 left-6 bg-white/90 backdrop-blur-md px-5 py-2 rounded-2xl shadow-xl z-20 border border-white/20">
                    <span class="text-[#003049] font-black text-2xl">${{ number_format($inmueble->renta_mensual) }}</span>
                    <span class="text-xs text-muted-foreground font-bold ml-1 uppercase">/ mes</span>
                </div>

                {{-- Botón Expandir --}}
                <button @click="showFullscreen = true" class="absolute bottom-6 left-6 z-20 h-10 w-10 flex items-center justify-center rounded-xl bg-white/10 backdrop-blur-md text-white border border-white/20 hover:bg-white/30 transition-all">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4" /></svg>
                </button>

                {{-- Indicadores --}}
                <div class="absolute bottom-6 right-6 flex gap-1.5 z-20">
                    <template x-for="(img, index) in images" :key="index">
                        <div class="h-1 rounded-full transition-all duration-500" :class="active === index ? 'w-6 bg-white' : 'w-2 bg-white/30'"></div>
                    </template>
                </div>
            </div>

        {{-- MODAL FULLSCREEN 3.0 (FUERA DEL CARRUSEL PARA EVITAR CLIPPING) --}}
        <div x-show="showFullscreen" 
             x-transition:enter="transition opacity duration-300"
             x-transition:leave="transition opacity duration-200"
             class="fixed inset-0 z-[99999] bg-black/95 backdrop-blur-2xl flex items-center justify-center overflow-hidden" 
             style="display: none;"
             x-cloak>
            
            {{-- Botón Cerrar Ultra-Premium --}}
            <button @click="showFullscreen = false" class="absolute top-8 right-8 p-3 text-white/50 hover:text-white hover:bg-white/10 rounded-full transition-all z-[100001] group">
                <svg class="h-8 w-8 transition-transform group-hover:rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>

            {{-- Navegación Principal --}}
            <div class="relative w-full h-full flex flex-col items-center justify-center select-none p-4 md:p-12">
                {{-- Flechas Laterales Flotantes --}}
                <button @click.stop="prev()" class="absolute left-6 md:left-12 p-5 text-white/40 hover:text-white transition-all bg-white/5 hover:bg-white/10 backdrop-blur-md rounded-full z-[100001]">
                    <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                </button>
                
                <img :src="images[active]" 
                     class="max-w-full max-h-[75vh] object-contain shadow-[0_0_80px_rgba(0,0,0,0.5)] rounded-2xl border border-white/10"
                     x-transition:enter="transition transform duration-500"
                     x-transition:enter-start="scale-95 opacity-0"
                     x-transition:enter-end="scale-100 opacity-100">
                
                <button @click.stop="next()" class="absolute right-6 md:right-12 p-5 text-white/40 hover:text-white transition-all bg-white/5 hover:bg-white/10 backdrop-blur-md rounded-full z-[100001]">
                    <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19l7-7-7-7" /></svg>
                </button>

                {{-- Tira de Miniaturas Estilizada --}}
                <div class="absolute bottom-10 left-1/2 -translate-x-1/2 w-full max-w-4xl flex gap-4 px-8 py-5 bg-white/5 backdrop-blur-2xl rounded-[2.5rem] border border-white/10 overflow-x-auto no-scrollbar scroll-smooth">
                    <template x-for="(img, index) in images" :key="index">
                        <button @click="active = index" 
                                class="h-20 w-32 flex-shrink-0 rounded-2xl overflow-hidden border-2 transition-all duration-300"
                                :class="active === index ? 'border-white scale-110 shadow-2xl z-10' : 'border-transparent opacity-40 hover:opacity-100'">
                            <img :src="img" class="h-full w-full object-cover">
                        </button>
                    </template>
                </div>
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
                        {{-- Renta Mensual --}}
                        <div class="flex flex-col items-start">
                            <span class="text-[11px] text-[#4F6D7A] font-black uppercase tracking-[0.25em] mb-4">Renta Mensual</span>
                            <div class="bg-[#F4F7F9] px-10 py-4 rounded-3xl border border-[#E5EDF2] shadow-sm">
                                <span class="text-3xl font-black text-[#003049] tracking-tight">${{ number_format($inmueble->renta_mensual ?? 0) }}</span>
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

                        <div class="mt-8 space-y-4">
                            @if(Auth::id() === $inmueble->propietario_id)
                                {{-- Botones de Gestión para el Dueño --}}
                                <a href="{{ route('inmuebles.edit', $inmueble) }}"
                                    class="flex w-full items-center justify-center rounded-2xl bg-gradient-to-r from-[#003049] to-[#004e7a] py-4 text-white font-bold shadow-xl shadow-[#003049]/20 transition-all duration-300 hover:-translate-y-1 hover:brightness-110 active:scale-95 group/btn gap-3">
                                    <div class="h-10 w-10 rounded-xl bg-white/10 flex items-center justify-center transition-transform group-hover/btn:scale-110">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </div>
                                    <span class="uppercase tracking-[0.15em] text-xs leading-none">Editar Propiedad</span>
                                </a>

                                <form id="delete-form-{{ $inmueble->id }}" action="{{ route('inmuebles.destroy', $inmueble) }}" method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <button type="button" onclick="confirmDelete({{ $inmueble->id }})"
                                    class="flex w-full items-center justify-center rounded-2xl bg-white border-2 border-red-50 py-4 text-red-600 font-bold shadow-lg shadow-red-500/5 transition-all duration-300 hover:bg-red-50 hover:border-red-100/50 active:scale-95 group/del gap-3">
                                    <div class="h-10 w-10 rounded-xl bg-red-50 flex items-center justify-center transition-colors group-hover/del:bg-red-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </div>
                                    <span class="uppercase tracking-[0.15em] text-xs leading-none">Eliminar Publicación</span>
                                </button>
                            @else
                                @auth
                                    {{-- Botón de Contacto para Visitantes --}}
                                    <a href="mailto:{{ $inmueble->propietario->email }}"
                                        class="flex w-full items-center justify-center rounded-2xl bg-[#003049] py-5 text-white font-black shadow-2xl shadow-[#003049]/30 transition-all duration-500 hover:-translate-y-1.5 group/btn gap-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white transition-transform group-hover/btn:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        <span class="uppercase tracking-[0.2em] text-sm leading-none">Contactar</span>
                                    </a>
                                @else
                                    <div class="bg-[#003049]/5 border-2 border-dashed border-[#003049]/20 p-6 rounded-3xl text-center">
                                        <p class="text-[#003049] font-bold text-sm mb-3">Para contactar al dueño debes iniciar sesión</p>
                                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-xs font-black text-white bg-[#003049] px-6 py-3 rounded-xl hover:scale-105 transition-all uppercase tracking-widest">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                                <path fill-rule="evenodd" d="M8 7a5 5 0 113.61 4.804l-1.903 1.903A1 1 0 019 14H8v1a1 1 0 01-1 1H6v1a1 1 0 01-1 1H3a1 1 0 01-1-1v-2a1 1 0 01.293-.707L8.196 8.39A5.002 5.002 0 018 7zm5-3a.75.75 0 000 1.5A1.5 1.5 0 0114.5 7 .75.75 0 0016 7a3 3 0 00-3-3z" clip-rule="evenodd" />
                                            </svg> Iniciar Sesión para Contactar
                                        </a>
                                    </div>
                                @endauth
                            @endif
                        </div>
                    </div>
                </div>



                {{-- Sección de Reseñas --}}
                <div class="mt-16 pt-12 border-t border-slate-100/80">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
                        <div>
                            <h2 class="text-3xl font-black text-[#003049] tracking-tight">Experiencias y Reseñas</h2>
                            <p class="text-slate-500 font-medium mt-1">Lo que otros dicen sobre esta propiedad.</p>
                        </div>
                        <div class="flex items-center gap-3 bg-amber-50 px-5 py-3 rounded-2xl border border-amber-100 self-start md:self-auto">
                            <span class="text-2xl font-black text-amber-600">{{ number_format($inmueble->resenas->avg('puntuacion') ?? 0, 1) }}</span>
                            <div class="flex text-amber-400">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 {{ $i <= ($inmueble->resenas->avg('puntuacion') ?? 0) ? '' : 'opacity-30' }}" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                @endfor
                            </div>
                            <span class="text-xs font-bold text-amber-700/60 uppercase tracking-widest pl-2 border-l border-amber-200">{{ $inmueble->resenas->count() }} Opiniones</span>
                        </div>
                    </div>

                    {{-- Formulario de Nueva Reseña --}}
                    @auth
                        @if(Auth::id() !== $inmueble->propietario_id)
                            <div class="mb-12 bg-[#003049]/5 rounded-[2.5rem] p-8 md:p-10 border border-[#003049]/10">
                                <h3 class="text-xl font-black text-[#003049] mb-6 flex items-center gap-3">
                                    <span class="flex h-10 w-10 items-center justify-center bg-[#003049] text-white rounded-xl shadow-lg">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                            <path d="M21.731 2.269a2.625 2.625 0 00-3.712 0l-1.157 1.158 3.71 3.71 1.159-1.157a2.625 2.625 0 000-3.711z" />
                                            <path d="M16.273 5.337l-3.71-3.71L3.926 10.263a4.5 4.5 0 00-1.077 1.637l-1.42 4.259a.75.75 0 00.95.95l4.259-1.42a4.5 4.5 0 001.637-1.077l8.636-8.636z" />
                                        </svg>
                                    </span>
                                    Cuéntanos tu experiencia
                                </h3>
                                <form action="{{ route('resenas.store', $inmueble) }}" method="POST" class="space-y-6">
                                    @csrf
                                    <div class="flex flex-col gap-4">
                                        <label class="text-xs font-black text-[#003049] uppercase tracking-[0.2em]">Tu Calificación</label>
                                        <div class="flex gap-2" x-data="{ rating: 0, hover: 0 }">
                                            @for($i = 1; $i <= 5; $i++)
                                                <button type="button" 
                                                    @click="rating = {{ $i }}" 
                                                    @mouseenter="hover = {{ $i }}" 
                                                    @mouseleave="hover = 0"
                                                    class="transition-all duration-300 transform hover:scale-125 focus:outline-none">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" :class="(hover || rating) >= {{ $i }} ? 'text-amber-400' : 'text-slate-200'" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                    </svg>
                                                </button>
                                            @endfor
                                            <input type="hidden" name="puntuacion" :value="rating" required>
                                        </div>
                                    </div>

                                    <div class="space-y-3">
                                        <label class="text-xs font-black text-[#003049] uppercase tracking-[0.2em]">Comentario</label>
                                        <textarea name="comentario" rows="4" required
                                            placeholder="¿Qué te pareció la propiedad y la atención del dueño?"
                                            class="w-full rounded-[1.5rem] border-slate-200 bg-white/50 backdrop-blur-sm p-5 focus:ring-4 focus:ring-[#003049]/10 focus:border-[#003049] transition-all outline-none text-slate-700"></textarea>
                                    </div>

                                    <button type="submit" 
                                        class="w-full md:w-auto px-10 py-4 bg-[#003049] text-white font-black rounded-2xl shadow-xl shadow-[#003049]/20 hover:-translate-y-1 hover:brightness-110 transition-all uppercase tracking-widest text-xs">
                                        Publicar Reseña
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="mb-12 bg-amber-50 rounded-[2.5rem] p-8 text-center border border-amber-100">
                                <p class="text-amber-700 font-bold mb-1 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 inline mr-2 text-amber-500">
                                        <path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd" />
                                    </svg> Estás viendo tu propia publicación
                                </p>
                                <p class="text-amber-600 text-xs">Los dueños no pueden calificar sus propias propiedades.</p>
                            </div>
                        @endif
                    @else
                        <div class="mb-12 bg-slate-100/50 rounded-[2.5rem] p-8 text-center border-2 border-dashed border-slate-200">
                            <p class="text-[#003049] font-bold mb-4">¿Quieres compartir tu experiencia?</p>
                            <a href="{{ route('login') }}" class="inline-block px-8 py-3 bg-[#003049] text-white font-black rounded-2xl shadow-lg hover:-translate-y-1 transition-all uppercase tracking-widest text-xs">
                                Inicia sesión para calificar
                            </a>
                        </div>
                    @endauth

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @forelse($inmueble->resenas as $resena)
                            <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                                <div class="flex items-center gap-4 mb-6">
                                    <div class="h-12 w-12 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-500 font-black text-xl overflow-hidden shadow-inner">
                                        @if($resena->usuario->foto_perfil)
                                            <img src="{{ str_starts_with($resena->usuario->foto_perfil, 'http') ? $resena->usuario->foto_perfil : asset('storage/' . $resena->usuario->foto_perfil) }}" class="h-full w-full object-cover">
                                        @else
                                            {{ substr($resena->usuario->nombre, 0, 1) }}
                                        @endif
                                    </div>
                                    <div>
                                        <span class="block font-black text-[#003049] leading-tight">
                                            {{ $resena->usuario->nombre }}
                                            @if(Auth::id() === $resena->usuario_id)
                                                <span class="ml-2 text-[8px] bg-[#003049] text-white px-2 py-0.5 rounded-full uppercase tracking-tighter">Tú</span>
                                            @endif
                                        </span>
                                        <div class="flex text-amber-400 mt-0.5 scale-75 origin-left">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 {{ $i <= $resena->puntuacion ? '' : 'opacity-30' }}" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            @endfor
                                        </div>
                                    </div>
                                    <span class="ml-auto text-[10px] font-bold text-slate-300 uppercase tracking-widest">{{ $resena->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="space-y-4">
                                    <p class="text-slate-600 leading-relaxed italic text-sm">"{{ $resena->comentario }}"</p>
                                    
                                    @auth
                                        @php
                                            $esAutor = Auth::id() === $resena->usuario_id;
                                            $esAdmin = Auth::user()->es_admin || Auth::user()->tieneRol('admin');
                                        @endphp
                                        
                                        @if($esAutor || $esAdmin)
                                            <div class="flex gap-6 pt-6 mt-2 border-t border-slate-50" x-data="{ editing: false, comentario: '{{ $resena->comentario }}', puntuacion: {{ $resena->puntuacion }} }">
                                                
                                                @if($esAutor)
                                                    {{-- Botón Editar (Solo Autor) --}}
                                                    <button @click="editing = !editing" class="flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-[0.15em] hover:text-[#003049] transition-all group/edit">
                                                        <div class="h-8 w-8 rounded-lg bg-slate-50 flex items-center justify-center group-hover/edit:bg-[#003049]/5 transition-colors">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                            </svg>
                                                        </div>
                                                        <span x-text="editing ? 'Cerrar' : 'Editar'"></span>
                                                    </button>
                                                @endif
                                                
                                                {{-- Botón Eliminar (Autor o Admin) --}}
                                                <form id="delete-resena-{{ $resena->id }}" action="{{ route('resenas.destroy', $resena) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" onclick="confirmDeleteResena({{ $resena->id }})" 
                                                        class="flex items-center gap-2 text-[10px] font-black text-red-200/80 uppercase tracking-[0.15em] hover:text-red-500 transition-all group/del">
                                                        <div class="h-8 w-8 rounded-lg bg-red-50/30 flex items-center justify-center group-hover/del:bg-red-50 transition-colors">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </div>
                                                        Eliminar
                                                    </button>
                                                </form>

                                                @if($esAutor)
                                                    {{-- Modal de Edición (Solo para el Autor) --}}
                                                    <div x-show="editing" x-transition class="fixed inset-0 bg-[#003049]/40 backdrop-blur-sm z-[2000] flex items-center justify-center p-4" x-cloak>
                                                        <div class="bg-white w-full max-w-lg rounded-[2.5rem] p-8 shadow-2xl" @click.away="editing = false">
                                                            <h4 class="text-xl font-black text-[#003049] mb-6">Actualizar Reseña</h4>
                                                            <form action="{{ route('resenas.update', $resena) }}" method="POST" class="space-y-6">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="flex flex-col gap-2">
                                                                    <label class="text-[10px] font-black text-[#003049] uppercase tracking-widest">Nueva Calificación</label>
                                                                    <div class="flex gap-1">
                                                                        <template x-for="i in 5">
                                                                            <button type="button" @click="puntuacion = i" class="transition-transform hover:scale-125">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" :class="puntuacion >= i ? 'text-amber-400' : 'text-slate-200'" viewBox="0 0 20 20" fill="currentColor">
                                                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                                                </svg>
                                                                            </button>
                                                                        </template>
                                                                        <input type="hidden" name="puntuacion" :value="puntuacion">
                                                                    </div>
                                                                </div>
                                                                <textarea name="comentario" x-model="comentario" rows="4" class="w-full rounded-2xl border-slate-200 bg-slate-50 p-4 text-sm focus:ring-2 focus:ring-[#003049] outline-none"></textarea>
                                                                <div class="flex gap-4">
                                                                    <button type="submit" class="flex-1 bg-[#003049] text-white font-black py-4 rounded-xl text-xs uppercase tracking-widest">Guardar</button>
                                                                    <button type="button" @click="editing = false" class="flex-1 bg-slate-100 text-slate-500 font-black py-4 rounded-xl text-xs uppercase tracking-widest">Cerrar</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    @endauth
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full py-16 flex flex-col items-center justify-center text-center bg-slate-50/50 rounded-[3rem] border-2 border-dashed border-slate-200">
                                <div class="h-20 w-20 bg-white rounded-3xl flex items-center justify-center text-slate-200 mb-4 shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-slate-400">Aún no hay reseñas</h3>
                                <p class="text-slate-400 text-sm max-w-xs mt-2 font-medium">Sé el primero en rentar esta propiedad y compartir tu experiencia.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Video Tour Virtual (Solo si existe) --}}
                @if($inmueble->video_youtube_id)
                <div class="mt-16 pt-12 border-t border-slate-100/80" id="video-tour-section">
                    {{-- Header --}}
                    <div class="flex items-center justify-between mb-8 flex-wrap gap-4">
                        <div class="flex items-center gap-4">
                            <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center text-white shadow-xl">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19.59 6.69a4.83 4.83 0 01-3.77-2.75 12.61 12.61 0 00-7.38 0A4.83 4.83 0 014.67 6.69 46.55 46.55 0 004 12a46.55 46.55 0 00.67 5.31 4.83 4.83 0 003.77 2.75 12.61 12.61 0 007.38 0 4.83 4.83 0 003.77-2.75A46.55 46.55 0 0020 12a46.55 46.55 0 00-.41-5.31zM10 15V9l5 3z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-black text-[#003049] tracking-tight">Video Tour Virtual</h3>
                                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Recorre la propiedad desde donde estés</p>
                            </div>
                        </div>
                        @if($inmueble->video_canal)
                            <span class="inline-flex items-center gap-2 bg-red-50 text-red-700 text-xs font-bold px-4 py-2 rounded-xl border border-red-100">
                                <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-2.75 12.61 12.61 0 00-7.38 0A4.83 4.83 0 014.67 6.69 46.55 46.55 0 004 12a46.55 46.55 0 00.67 5.31 4.83 4.83 0 003.77 2.75 12.61 12.61 0 007.38 0 4.83 4.83 0 003.77-2.75A46.55 46.55 0 0020 12a46.55 46.55 0 00-.41-5.31zM10 15V9l5 3z"/></svg>
                                {{ $inmueble->video_canal }}
                            </span>
                        @endif
                    </div>

                    {{-- Stats Row de la API v3 --}}
                    @if($inmueble->video_vistas || $inmueble->video_likes || $inmueble->video_duracion || $inmueble->video_publicado_en)
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
                        @if($inmueble->video_vistas)
                        <div class="bg-slate-50 rounded-2xl p-4 text-center border border-slate-100">
                            <p class="text-2xl font-black text-[#003049]">{{ number_format($inmueble->video_vistas) }}</p>
                            <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider mt-1">Visualizaciones</p>
                        </div>
                        @endif
                        @if($inmueble->video_likes)
                        <div class="bg-slate-50 rounded-2xl p-4 text-center border border-slate-100">
                            <p class="text-2xl font-black text-[#003049]">{{ number_format($inmueble->video_likes) }}</p>
                            <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider mt-1">Me gusta</p>
                        </div>
                        @endif
                        @if($inmueble->video_duracion)
                        <div class="bg-slate-50 rounded-2xl p-4 text-center border border-slate-100">
                            <p class="text-2xl font-black text-[#003049]">{{ $inmueble->video_duracion }}</p>
                            <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider mt-1">Duración</p>
                        </div>
                        @endif
                        @if($inmueble->video_publicado_en)
                        <div class="bg-slate-50 rounded-2xl p-4 text-center border border-slate-100">
                            <p class="text-lg font-black text-[#003049]">{{ \Carbon\Carbon::parse($inmueble->video_publicado_en)->format('M Y') }}</p>
                            <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider mt-1">Publicado</p>
                        </div>
                        @endif
                    </div>
                    @endif

                    {{-- Título del video --}}
                    @if($inmueble->video_titulo)
                        <p class="text-base font-bold text-slate-700 mb-4 flex items-start gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-500 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-2.75 12.61 12.61 0 00-7.38 0A4.83 4.83 0 014.67 6.69 46.55 46.55 0 004 12a46.55 46.55 0 00.67 5.31 4.83 4.83 0 003.77 2.75 12.61 12.61 0 007.38 0 4.83 4.83 0 003.77-2.75A46.55 46.55 0 0020 12a46.55 46.55 0 00-.41-5.31zM10 15V9l5 3z"/></svg>
                            {{ $inmueble->video_titulo }}
                        </p>
                    @endif

                    {{-- iframe responsivo --}}
                    <div class="relative w-full rounded-[2rem] overflow-hidden shadow-2xl border border-slate-100"
                         style="padding-top: 56.25%;">
                        <iframe
                            src="https://www.youtube.com/embed/{{ $inmueble->video_youtube_id }}?rel=0&modestbranding=1"
                            class="absolute inset-0 w-full h-full"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen
                            title="{{ $inmueble->video_titulo ?? 'Video Tour - ' . $inmueble->titulo }}">
                        </iframe>
                    </div>

                    {{-- Descripción del video (si viene de API) --}}
                    @if($inmueble->video_descripcion)
                    <div class="mt-6 bg-slate-50 rounded-2xl p-5 border border-slate-100">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Descripción del video</p>
                        <p class="text-sm text-slate-600 leading-relaxed whitespace-pre-line">{{ $inmueble->video_descripcion }}</p>
                    </div>
                    @endif

                    {{-- Acciones --}}
                    <div class="mt-5 flex items-center flex-wrap gap-3">
                        <a href="{{ $inmueble->video_youtube }}" target="_blank" rel="noopener"
                           class="inline-flex items-center gap-2 text-xs font-bold text-red-600 hover:text-red-700 transition-colors bg-red-50 hover:bg-red-100 px-4 py-2 rounded-xl border border-red-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-2.75 12.61 12.61 0 00-7.38 0A4.83 4.83 0 014.67 6.69 46.55 46.55 0 004 12a46.55 46.55 0 00.67 5.31 4.83 4.83 0 003.77 2.75 12.61 12.61 0 007.38 0 4.83 4.83 0 003.77-2.75A46.55 46.55 0 0020 12a46.55 46.55 0 00-.41-5.31zM10 15V9l5 3z"/></svg>
                            Ver en YouTube
                        </a>

                        @if($inmueble->video_actualizado_en)
                        <span class="text-xs text-slate-400 flex items-center gap-1">
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Stats actualizados {{ \Carbon\Carbon::parse($inmueble->video_actualizado_en)->diffForHumans() }}
                        </span>
                        @endif

                        {{-- Botón refresh (solo para el propietario o admin) --}}
                        @if(auth()->check() && (auth()->id() === $inmueble->propietario_id || auth()->user()->es_admin))
                        <button id="btn-refresh-video"
                            onclick="refreshVideoStats({{ $inmueble->id }})"
                            class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-[#003049] transition-all bg-slate-100 hover:bg-slate-200 px-4 py-2 rounded-xl border border-slate-200 ml-auto">
                            <svg id="refresh-icon" class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            Actualizar estadísticas
                        </button>
                        @endif
                    </div>
                </div>
                @endif


                {{-- Tiempos de Traslado (Calculados) --}}
                <div class="mt-16 bg-slate-50 rounded-[3rem] p-10 border border-slate-100 shadow-inner" x-data="{
                    lat1: {{ $inmueble->latitud ?? 16.9068 }},
                    lng1: {{ $inmueble->longitud ?? -92.0941 }},
                    puntos: [
                        { nombre: 'Parque Central', lat: 16.9068, lng: -92.0941 },
                        { nombre: 'UT de la Selva (UTS)', lat: 16.9188, lng: -92.1032 },
                        { nombre: 'Terminal OCC', lat: 16.9042, lng: -92.0995 }
                    ],
                    getDistancia(lat2, lng2) {
                        const R = 6371; // Radio de la Tierra en km
                        const dLat = (lat2 - this.lat1) * Math.PI / 180;
                        const dLng = (lng2 - this.lng1) * Math.PI / 180;
                        const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                                  Math.cos(this.lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                                  Math.sin(dLng / 2) * Math.sin(dLng / 2);
                        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                        return (R * c).toFixed(2);
                    }
                }">
                    <div class="flex items-center gap-4 mb-10">
                        <div class="h-14 w-14 rounded-2xl bg-[#003049] flex items-center justify-center text-white shadow-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /></svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-black text-[#003049] tracking-tight">Ubicación Estratégica</h3>
                            <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Tiempos estimados desde esta propiedad en Ocosingo</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <template x-for="punto in puntos" :key="punto.nombre">
                            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-50 flex flex-col justify-between group hover:shadow-md transition-all">
                                <div>
                                    <h4 class="font-black text-[#003049] text-sm group-hover:text-primary transition-colors" x-text="punto.nombre"></h4>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1" x-text="`${getDistancia(punto.lat, punto.lng)} km`"></p>
                                </div>
                                <div class="mt-6 flex items-center justify-between gap-2 border-t border-slate-50 pt-4">
                                    <div class="flex flex-col items-center">
                                        <span class="text-[8px] font-black text-slate-300 uppercase">A pie</span>
                                        <span class="text-xs font-black text-[#003049]" x-text="`${Math.ceil(getDistancia(punto.lat, punto.lng) * 12)} min`"></span>
                                    </div>
                                    <div class="flex flex-col items-center">
                                        <span class="text-[8px] font-black text-orange-300 uppercase">Bici</span>
                                        <span class="text-xs font-black text-orange-600" x-text="`${Math.ceil(getDistancia(punto.lat, punto.lng) * 4)} min`"></span>
                                    </div>
                                    <div class="flex flex-col items-center">
                                        <span class="text-[8px] font-black text-[#003049]/30 uppercase">Taxi</span>
                                        <span class="text-xs font-black text-[#003049]" x-text="`${Math.ceil(getDistancia(punto.lat, punto.lng) * 2) + 2} min`"></span>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Leaflet y Scripts de Mapa --}}
                <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
                <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                <div id="map-show" class="w-full h-[400px] rounded-[2.5rem] border border-slate-100 shadow-inner mt-12 z-0"></div>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const lat = {{ $inmueble->latitud ?? 16.9068 }};
                        const lng = {{ $inmueble->longitud ?? -92.0941 }};
                        
                        const osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            maxZoom: 19,
                            attribution: '&copy; OpenStreetMap contributors'
                        });

                        const satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                            attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
                        });

                        const map = L.map('map-show', {
                            center: [lat, lng],
                            zoom: 16,
                            layers: [osm]
                        });

                        const baseMaps = {
                            'Callejero': osm,
                            'Satélite': satellite
                        };
                        L.control.layers(baseMaps, null, { collapsed: false }).addTo(map);
                        L.control.scale({ imperial: false }).addTo(map);

                        L.marker([lat, lng]).addTo(map)
                            .bindPopup(`
                                <div class='font-sans p-1'>
                                    <h4 class='font-black text-[#003049] text-sm'>{{ $inmueble->titulo }}</h4>
                                    <p class='text-[10px] text-slate-500'>{{ $inmueble->direccion }}</p>
                                </div>
                            `)
                            .openPopup();

                        // Forzar redibujo por problemas de Alpine / Tabs
                        setTimeout(() => { map.invalidateSize(); }, 500);
                    });
                </script>
                <script>
                    function confirmDeleteResena(id) {
                        Swal.fire({
                            title: '¿Eliminar reseña?',
                            text: "Esta acción no se puede deshacer.",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#003049',
                            cancelButtonColor: '#ff4444',
                            confirmButtonText: 'Sí, eliminar',
                            cancelButtonText: 'Cancelar',
                            borderRadius: '1.5rem',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                document.getElementById('delete-resena-' + id).submit();
                            }
                        })
                    }
                </script>

    {{-- Script de actualización de estadísticas de YouTube --}}
    @if($inmueble->video_youtube_id && auth()->check() && (auth()->id() === $inmueble->propietario_id || auth()->user()->es_admin))
    <script>
        async function refreshVideoStats(inmuebleId) {
            const btn  = document.getElementById('btn-refresh-video');
            const icon = document.getElementById('refresh-icon');

            // Spinner
            btn.disabled = true;
            icon.style.animation = 'spin 1s linear infinite';
            icon.style.transformOrigin = 'center';

            try {
                const response = await fetch(`/inmuebles/${inmuebleId}/refresh-video`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    }
                });

                const data = await response.json();

                if (data.success) {
                    Swal.fire({
                        title:  'Estadísticas actualizadas',
                        html: `
                            <div style="text-align:left; font-size:14px; line-height:2;">
                                <p><b>Título:</b> ${data.data.titulo ?? '—'}</p>
                                <p><b>Canal:</b> ${data.data.canal ?? '—'}</p>
                                <p><b>Vistas:</b> ${data.data.vistas ?? '—'}</p>
                                <p><b>Likes:</b> ${data.data.likes ?? '—'}</p>
                                <p><b>Duración:</b> ${data.data.duracion ?? '—'}</p>
                            </div>`,
                        icon: 'success',
                        confirmButtonColor: '#003049',
                        confirmButtonText: 'Ver cambios',
                        timer: 5000,
                        showConfirmButton: true,
                    }).then(() => location.reload());
                } else {
                    Swal.fire({
                        title: 'No se pudo actualizar',
                        text: data.error ?? 'Intenta de nuevo más tarde.',
                        icon: 'warning',
                        confirmButtonColor: '#003049',
                    });
                }
            } catch (err) {
                Swal.fire({
                    title: 'Error de conexión',
                    text: 'No se pudo conectar con el servidor.',
                    icon: 'error',
                    confirmButtonColor: '#003049',
                });
            } finally {
                btn.disabled = false;
                icon.style.animation = '';
            }
        }

        // Añadir keyframe de spin si no existe
        if (!document.getElementById('yt-spin-style')) {
            const s = document.createElement('style');
            s.id = 'yt-spin-style';
            s.textContent = '@keyframes spin { to { transform: rotate(360deg); } }';
            document.head.appendChild(s);
        }
    </script>
    @endif
@endsection

