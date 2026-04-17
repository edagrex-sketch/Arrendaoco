@extends('layouts.app')

@section('title', $inmueble->titulo)

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8 lg:py-12" x-data="{ 
                        active: 0, 
                        showFullscreen: false,
                        images: [
                            '{{ $inmueble->imagen_url }}',
                            @foreach($imagenes as $img)
                                @if($img->ruta_imagen !== $inmueble->imagen)
                                    '{{ $img->ruta_imagen_url }}',
                                @endif
                            @endforeach
                        ],
                        next() { this.active = (this.active + 1) % this.images.length; },
                        prev() { this.active = (this.active - 1 + this.images.length) % this.images.length; },
                        init() {
                            setInterval(() => { if(!this.showFullscreen) this.next(); }, 5000);
                        }
                     }" @keydown.right.window="if(showFullscreen) next()" @keydown.left.window="if(showFullscreen) prev()"
        @keydown.escape.window="showFullscreen = false"> <!-- Start of new grid layout -->
        {{-- MODAL FULLSCREEN 3.0 (FUERA DEL CARRUSEL PARA EVITAR CLIPPING) --}}
        <div x-show="showFullscreen" x-transition:enter="transition opacity duration-300"
            x-transition:leave="transition opacity duration-200"
            class="fixed inset-0 z-[99999] bg-black/95 backdrop-blur-2xl flex items-center justify-center overflow-hidden"
            style="display: none;" x-cloak>

            {{-- Botón Cerrar Ultra-Premium --}}
            <button @click="showFullscreen = false"
                class="absolute top-8 right-8 p-3 text-white/50 hover:text-white hover:bg-white/10 rounded-full transition-all z-[100001] group">
                <svg class="h-8 w-8 transition-transform group-hover:rotate-90" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            {{-- Navegación Principal --}}
            <div class="relative w-full h-full flex flex-col items-center justify-center select-none p-4 md:p-12">
                {{-- Flechas Laterales Flotantes --}}
                <button @click.stop="prev()"
                    class="absolute left-6 md:left-12 p-5 text-white/40 hover:text-white transition-all bg-white/5 hover:bg-white/10 backdrop-blur-md rounded-full z-[100001]">
                    <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <img :src="images[active]"
                    class="max-w-full max-h-[75vh] object-contain shadow-[0_0_80px_rgba(0,0,0,0.5)] rounded-2xl border border-white/10"
                    x-transition:enter="transition transform duration-500" x-transition:enter-start="scale-95 opacity-0"
                    x-transition:enter-end="scale-100 opacity-100">

                <button @click.stop="next()"
                    class="absolute right-6 md:right-12 p-5 text-white/40 hover:text-white transition-all bg-white/5 hover:bg-white/10 backdrop-blur-md rounded-full z-[100001]">
                    <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19l7-7-7-7" />
                    </svg>
                </button>

                {{-- Tira de Miniaturas Estilizada --}}
                <div
                    class="absolute bottom-10 left-1/2 -translate-x-1/2 w-full max-w-7xl flex gap-4 px-8 py-5 bg-white/5 backdrop-blur-2xl rounded-[2.5rem] border border-white/10 overflow-x-auto no-scrollbar scroll-smooth">
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

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-4">
            {{-- Left Box: Image Gallery --}}
            <div class="lg:col-span-5 bg-white rounded-3xl p-4 shadow-sm border border-slate-200 flex flex-col">
                <div class="relative group w-full flex-1 min-h-[450px] lg:min-h-[600px] rounded-2xl grid gap-2 overflow-hidden"
                    :class="images.length >= 4 ? 'grid-cols-2 grid-rows-4' : (images.length >= 2 ? 'grid-cols-2 grid-rows-3' : 'grid-cols-2 grid-rows-2')">

                    {{-- Grid 5 posiciones --}}
                    <template x-for="i in Math.min(images.length, 5)" :key="i">
                        <div class="bg-slate-100 rounded-xl overflow-hidden relative cursor-pointer group/item"
                            :class="i === 1 ? 'col-span-2 row-span-2' : ((images.length === 2 && i === 2) || (images.length === 4 && i === 4) ? 'col-span-2 row-span-1' : 'col-span-1 row-span-1')"
                            @click="showFullscreen = true; active = (active + i - 1) % images.length">

                            {{-- Imagen actual en la posición rotativa --}}
                            <img :src="images[(active + i - 1) % images.length]"
                                class="absolute inset-0 w-full h-full object-cover group-hover/item:scale-105 transition-transform duration-500">
                        </div>
                    </template>

                    {{-- Controles del carrusel --}}
                    <button @click.stop="prev()"
                        class="absolute left-3 top-1/2 -translate-y-1/2 z-20 h-10 w-10 flex items-center justify-center rounded-full bg-white/80 text-[#003049] opacity-0 group-hover:opacity-100 transition-all hover:bg-white shadow-lg backdrop-blur-sm">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <button @click.stop="next()"
                        class="absolute right-3 top-1/2 -translate-y-1/2 z-20 h-10 w-10 flex items-center justify-center rounded-full bg-white/80 text-[#003049] opacity-0 group-hover:opacity-100 transition-all hover:bg-white shadow-lg backdrop-blur-sm">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19l7-7-7-7" />
                        </svg>
                    </button>

                    {{-- Indicador de fotos adicionales --}}
                    <template x-if="images.length > 5">
                        <div
                            class="absolute bottom-3 right-3 bg-black/70 backdrop-blur-md text-white px-3 py-1.5 rounded-xl font-bold text-[10px] uppercase tracking-wider pointer-events-none z-20 shadow-lg">
                            +<span x-text="images.length - 5"></span> fotos
                        </div>
                    </template>
                </div>
            </div>

            {{-- Right Box: Info --}}
            <div
                class="lg:col-span-7 bg-white rounded-3xl p-6 lg:p-8 shadow-sm border border-slate-200 flex flex-col justify-between">
                <div>
                    <h1 class="text-3xl lg:text-4xl font-semibold text-slate-800 mb-6">{{ $inmueble->titulo }}</h1>

                    <div class="mb-8">
                        <h3 class="text-xl font-bold text-slate-700 mb-3 underline decoration-brand-light/30 decoration-4 underline-offset-8">Descripción</h3>
                        <p class="text-sm md:text-base font-medium text-slate-600 leading-relaxed text-justify">
                            {{ $inmueble->descripcion ?? 'Sin descripción proporcionada.' }}
                        </p>
                    </div>

                    <div class="mb-10">
                        <h3 class="text-xl font-bold text-slate-700 mb-3 underline decoration-brand-light/30 decoration-4 underline-offset-8">Dirección</h3>
                        <p class="text-sm md:text-base font-medium text-slate-600 leading-relaxed">{{ $inmueble->direccion }}</p>
                    </div>
                    {{-- Nuevo Diseño: Gallery Dashboard --}}
                    <div class="mt-8 mb-12">
                        {{-- BLOQUE 1: Estadísticas de Impacto (Upper Header) --}}
                        <div class="grid grid-cols-3 gap-0 mb-12 rounded-[2rem] border border-slate-100 overflow-hidden shadow-sm">
                            <div class="p-8 bg-white flex flex-col items-center justify-center text-center border-r border-slate-100 hover:bg-slate-50/50 transition-colors">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Habitaciones</span>
                                <div class="flex items-center gap-3">
                                    <svg class="w-8 h-8 text-[#003049]" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M22 13V19C22 19.5523 21.5523 20 21 20H3C2.44772 20 2 19.5523 2 19V13C2 11.3431 3.34315 10 5 10H19C20.6569 10 22 11.3431 22 13ZM19 12H5C4.44772 12 4 12.4477 4 13V15H20V13C20 12.4477 19.5523 12 19 12ZM20 6H4V9H20V6Z" />
                                    </svg>
                                    <span class="text-3xl font-black text-[#003049]">{{ $inmueble->habitaciones }}</span>
                                </div>
                                <span class="text-[11px] font-bold text-slate-500 mt-2">Dormitorios</span>
                            </div>
                            <div class="p-8 bg-white flex flex-col items-center justify-center text-center border-r border-slate-100 hover:bg-slate-50/50 transition-colors">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Servicios Sanitarios</span>
                                <div class="flex items-center gap-3">
                                    <svg class="w-8 h-8 text-[#003049]" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M19 11C19.5523 11 20 11.4477 20 12V14C20 15.6569 18.6569 17 17 17H7C5.34315 17 4 15.6569 4 14V12C4 11.4477 4.44772 11 5 11H19ZM16 4H8V10H16V4ZM18 18H6V20H18V18Z" />
                                    </svg>
                                    <span class="text-3xl font-black text-[#003049]">{{ $inmueble->banos + ($inmueble->medios_banos * 0.5) }}</span>
                                </div>
                                <span class="text-[11px] font-bold text-slate-500 mt-2">Baños Totales</span>
                            </div>
                            <div class="p-8 bg-white flex flex-col items-center justify-center text-center hover:bg-slate-50/50 transition-colors">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Dimensión Total</span>
                                <div class="flex items-center gap-3">
                                    <svg class="w-7 h-7 text-[#003049]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path d="M3 7V5C3 3.89543 3.89543 3 5 3H7M17 3H19C20.1046 3 21 3.89543 21 5V7M21 17V19C21 20.1046 20.1046 21 19 21H17M7 21H5C3.89543 21 3 20.1046 3 19V17M9 9H15V15H9V9Z" />
                                    </svg>
                                    <span class="text-3xl font-black text-[#003049]">{{ number_format($inmueble->metros, 0) }}</span>
                                </div>
                                <span class="text-[11px] font-bold text-slate-500 mt-2">Metros Cuadrados</span>
                            </div>
                        </div>

                        {{-- BLOQUE 2: Cuadrícula de Detalles Operativos --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10 px-4 mb-12">
                            {{-- Mobiliario --}}
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 shrink-0 rounded-2xl bg-[#003049]/5 flex items-center justify-center text-[#003049]">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path d="M4 18V20M20 18V20M19 18V9C19 7.89543 18.1046 7 17 7H7C5.89543 7 5 7.89543 5 9V18M4 18H20M9 11V14M15 11V14" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Mobiliario</h4>
                                    <p class="text-base font-bold text-slate-700 capitalize">{{ $inmueble->estado_mobiliario ?? 'No especificado' }}</p>
                                </div>
                            </div>

                            {{-- Estacionamiento --}}
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 shrink-0 rounded-2xl bg-[#003049]/5 flex items-center justify-center text-[#003049]">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path d="M7 11.5V14.5M10 11.5V14.5M14 11.5V14.5M17 11.5V14.5M5 18H19M5 7H19C20.1046 7 21 7.89543 21 9V18H3V9C3 7.89543 3.89543 7 5 7Z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Estacionamiento</h4>
                                    <p class="text-base font-bold text-slate-700">{{ $inmueble->tiene_estacionamiento ? 'Cochera Incluida' : 'No disponible' }}</p>
                                </div>
                            </div>

                            {{-- Renta --}}
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 shrink-0 rounded-2xl bg-[#003049]/5 flex items-center justify-center text-[#003049]">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path d="M17 9V7C17 4.23858 14.7614 2 12 2C9.23858 2 7 4.23858 7 7V9M5 9H19C20.1046 9 21 9.89543 21 11V19C21 20.1046 20.1046 21 19 21H5C3.89543 21 3 20.1046 3 19V11C3 9.89543 3.89543 9 5 9ZM12 14V16" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Esquema Renta</h4>
                                    <p class="text-base font-bold text-slate-700 capitalize">Pago {{ $inmueble->momento_pago ?? 'Adelantado' }}</p>
                                </div>
                            </div>

                            {{-- Mascotas --}}
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 shrink-0 rounded-2xl bg-[#003049]/5 flex items-center justify-center text-[#003049]">
                                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 14c-2.209 0-4 1.791-4 4s1.791 4 4 4 4-1.791 4-4-1.791-4-4-4zm-5-3c-1.105 0-2 .895-2 2s.895 2 2 2 2-.895 2-2-.895-2-2-2zm10 0c-1.105 0-2 .895-2 2s.895 2 2 2 2-.895 2-2-.895-2-2-2zm-5-3c-1.105 0-2 .895-2 2s.895 2 2 2 2-.895 2-2-.895-2-2-2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Mascotas</h4>
                                    <p class="text-base font-bold text-slate-700">{{ $inmueble->permite_mascotas ? 'Permitidas' : 'No permitidas' }}</p>
                                </div>
                            </div>

                            {{-- Tolerancia --}}
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 shrink-0 rounded-2xl bg-[#003049]/5 flex items-center justify-center text-[#003049]">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Días de Tolerancia</h4>
                                    <p class="text-base font-bold text-slate-700">{{ $inmueble->dias_tolerancia ?? 0 }} Días hábiles</p>
                                </div>
                            </div>

                            {{-- Preaviso --}}
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 shrink-0 rounded-2xl bg-[#003049]/5 flex items-center justify-center text-[#003049]">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Días de Preaviso</h4>
                                    <p class="text-base font-bold text-slate-700">{{ $inmueble->dias_preaviso ?? 30 }} Días naturales</p>
                                </div>
                            </div>
                        </div>

                        {{-- BLOQUE 3: Tags Detallados (Si existen) --}}
                        @if(($inmueble->permite_mascotas && $inmueble->mascotas->isNotEmpty()) || ($inmueble->tipo === 'Cuarto' && $inmueble->zonasComunes->isNotEmpty()))
                        <div class="bg-slate-50 rounded-[1.5rem] p-8 mt-10 border border-slate-100/50 flex flex-col gap-6">
                            @if($inmueble->permite_mascotas && $inmueble->mascotas->isNotEmpty())
                            <div class="flex items-center gap-6">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest shrink-0 w-32">Especies OK</span>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($inmueble->mascotas as $m)
                                        <span class="bg-white px-3 py-1.5 rounded-lg border border-slate-200 text-[#003049] text-[10px] font-black uppercase tracking-wider">{{ $m->nombre }}</span>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            @if($inmueble->tipo === 'Cuarto' && $inmueble->zonasComunes->isNotEmpty())
                            <div class="flex items-center gap-6">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest shrink-0 w-32">Áreas Comunes</span>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($inmueble->zonasComunes as $z)
                                        <span class="bg-white px-3 py-1.5 rounded-lg border border-slate-200 text-[#003049] text-[10px] font-black uppercase tracking-wider">{{ $z->nombre }}</span>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>




                    {{-- Matriz de Servicios Relacional --}}
                    @if($inmueble->servicios && $inmueble->servicios->isNotEmpty())
                    <div class="mb-8 pt-8 border-t border-slate-100">
                        <h4 class="text-xl font-bold text-slate-700 mb-5 block">
                            Servicios del Inmueble
                        </h4>
                        <ul class="space-y-4 grid md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-2">
                            @foreach($inmueble->servicios as $pivot)
                                @php
                                    $pago = $pivot->paga;
                                    $isArrendador = $pago === 'arrendador';
                                    $texto = $isArrendador ? 'Incluido' : 'Extra';
                                    $color = $isArrendador ? 'text-[#003049] bg-slate-100 border-slate-200/60' : 'text-slate-500 bg-white border-slate-200';
                                @endphp
                                <li class="flex items-center justify-between text-sm font-bold text-slate-600 py-2 border-b border-slate-100 last:border-0 md:last:border-b-0 break-inside-avoid">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-2 shrink-0 {{ $isArrendador ? 'text-slate-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $isArrendador ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12' }}" />
                                        </svg>
                                        {{ $pivot->servicio }}
                                    </div>
                                    <span class="text-[10px] {{ $color }} border px-2 py-1 rounded shadow-sm font-black uppercase tracking-widest">{{ $texto }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    {{-- Cláusulas Extra --}}
                    @if($inmueble->incluir_clausulas && !empty($inmueble->clausulas_extra))
                    <div class="mb-8">
                        <h4 class="text-sm font-bold text-slate-700 mb-3 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                            Cláusulas Importantes del Propietario
                        </h4>
                        <div class="p-5 bg-amber-50/50 rounded-2xl border border-amber-100/60 text-[11px] text-amber-900/80 leading-relaxed font-bold whitespace-pre-line shadow-inner">
                            {{ $inmueble->clausulas_extra }}
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Bottom Action Row --}}
                <div class="flex items-center justify-between mt-auto pt-6 border-t border-slate-100">
                    <div class="text-2xl font-bold text-slate-800">
                        ${{ number_format($inmueble->renta_mensual, 2) }} <span
                            class="text-sm font-bold ml-1 uppercase text-slate-500">MXN</span>
                    </div>

                    <div class="flex items-center gap-4">
                        @if(Auth::id() === $inmueble->propietario_id)
                            @if($inmueble->estatus === 'rentado')
                                <span
                                    class="bg-[#003049] text-white px-6 py-3 rounded-xl text-sm font-black shadow-md uppercase tracking-widest border border-[#003049]">
                                    Rentado
                                </span>
                            @else
                                <a href="{{ route('inmuebles.edit', $inmueble) }}"
                                    class="bg-[#729CB2] text-white px-6 py-3 rounded-xl text-sm font-bold shadow-md hover:bg-[#5C869C] transition-colors border border-[#5C869C]">
                                    Editar Propiedad
                                </a>
                            @endif
                        @else
                            <a href="{{ route('chats.start', ['otroUsuarioId' => $inmueble->propietario_id, 'inmuebleId' => $inmueble->id]) }}"
                                class="bg-white text-[#729CB2] px-4 sm:px-6 py-3 rounded-xl text-sm font-bold shadow-sm hover:bg-slate-50 transition-colors border border-[#729CB2] flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                Mensaje
                            </a>
                            @if($inmueble->estatus === 'disponible')
                                <a href="{{ route('contratos.ver', $inmueble) }}"
                                   id="btn-ver-contrato"
                                   class="bg-[#003049] text-white px-6 sm:px-10 py-3 rounded-xl text-sm font-black shadow-xl shadow-[#003049]/20 hover:bg-[#002233] hover:-translate-y-0.5 transition-all border border-[#002233] flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Ver Contrato
                                </a>
                            @else
                                <span
                                    class="bg-gray-300 text-gray-500 px-6 sm:px-10 py-3 rounded-xl text-sm font-black shadow-sm border border-gray-400 cursor-not-allowed uppercase tracking-widest">
                                    Rentado
                                </span>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            {{-- ROCO AI CONTEXTUAL WIDGET --}}
            <div class="lg:col-span-12 mt-6">
                <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-[2.5rem] border border-orange-100 p-8 shadow-sm flex flex-col md:flex-row items-center gap-8 relative overflow-hidden group">
                    {{-- Decoración de huellas --}}
                    <div class="absolute -right-10 -bottom-10 text-orange-200/20 rotate-12 group-hover:scale-110 transition-transform duration-700 pointer-events-none">
                        <svg class="h-40 w-40" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2zm9 7c0 1.1-.9 2-2 2s-2-.9-2-2 .9-2 2-2 2 .9 2 2zM5 9c0 1.1-.9 2-2 2S1 10.1 1 9s.9-2 2-2 2 .9 2 2zm7 11c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2zm7-4c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2zm-14 0c0 1.1-.9 2-2 2s-2-.9-2-2 .9-2 2-2 2 .9 2 2z"/>
                        </svg>
                    </div>

                    <div class="h-24 w-24 rounded-3xl bg-white shadow-xl shadow-orange-200/50 flex items-center justify-center p-4 border border-orange-100 shrink-0 transform group-hover:rotate-6 transition-all">
                        <img src="{{ asset('logo1.png') }}" class="w-full h-auto" alt="ROCO">
                    </div>

                    <div class="flex-1 text-center md:text-left">
                        <h3 class="text-2xl font-black text-orange-900 tracking-tight mb-2">¿Tienes dudas sobre esta propiedad?</h3>
                        <p class="text-orange-700/70 font-bold text-sm max-w-xl">Pregúntale a ROCO. Él conoce todos los detalles de <b>{{ $inmueble->titulo }}</b> y te ayudará a decidirte.</p>
                    </div>

                    <button onclick="window.openRocoWithContext({{ $inmueble->id }}, '¡Guau! Roco, cuéntame sobre esta casa: {{ $inmueble->titulo }}')" 
                        class="bg-orange-600 text-white px-10 py-5 rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-orange-700 transition-all shadow-xl shadow-orange-600/30 hover:-translate-y-1 active:scale-95 whitespace-nowrap z-10">
                        Consultar a Roco
                    </button>
                </div>
            </div>
        </div>

        {{-- Contenedor fondo blanco para lo demás, similar a la card de Reseñas --}}
        <div class="bg-white rounded-3xl p-6 lg:p-8 shadow-sm border border-slate-200">

            {{-- Tiempos de Traslado (Calculados) --}}
            <div class="mt-8 bg-slate-50 rounded-[2rem] p-5 lg:p-6 border border-slate-100 shadow-inner" x-data="{
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
                <div class="flex items-center gap-4 mb-6">
                    <div class="h-12 w-12 rounded-xl bg-[#003049] flex items-center justify-center text-white shadow-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-[#003049] tracking-tight">Ubicación Estratégica</h3>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Tiempos estimados desde
                            esta propiedad</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <template x-for="punto in puntos" :key="punto.nombre">
                        <div
                            class="bg-white p-4 rounded-xl shadow-sm border border-slate-50 flex flex-col justify-between group hover:shadow-md transition-all">
                            <div>
                                <h4 class="font-black text-[#003049] text-xs group-hover:text-primary transition-colors"
                                    x-text="punto.nombre"></h4>
                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest"
                                    x-text="`${getDistancia(punto.lat, punto.lng)} km`"></p>
                            </div>
                            <div class="mt-4 flex items-center justify-between gap-1 border-t border-slate-50 pt-3">
                                <div class="flex flex-col items-center">
                                    <span class="text-[7px] font-black text-slate-300 uppercase">A pie</span>
                                    <span class="text-[10px] font-black text-[#003049]"
                                        x-text="`${Math.ceil(getDistancia(punto.lat, punto.lng) * 12)} min`"></span>
                                </div>
                                <div class="flex flex-col items-center">
                                    <span class="text-[7px] font-black text-orange-300 uppercase">Bici</span>
                                    <span class="text-[10px] font-black text-orange-600"
                                        x-text="`${Math.ceil(getDistancia(punto.lat, punto.lng) * 4)} min`"></span>
                                </div>
                                <div class="flex flex-col items-center">
                                    <span class="text-[7px] font-black text-[#003049]/30 uppercase">Taxi</span>
                                    <span class="text-[10px] font-black text-[#003049]"
                                        x-text="`${Math.ceil(getDistancia(punto.lat, punto.lng) * 2) + 2} min`"></span>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>


            {{-- Leaflet y Scripts de Mapa --}}
            <div x-data="{ showMap: false }" class="mt-12">
                <div class="flex items-center mb-6 cursor-pointer group w-fit" @click="showMap = !showMap; if(showMap) setTimeout(() => { window.dispatchEvent(new Event('resize')); }, 300)">
                    <div class="flex items-center gap-3 sm:gap-4">
                        <div class="h-12 w-12 rounded-xl bg-[#003049] flex items-center justify-center text-white shadow-xl shrink-0 transition-transform duration-300 group-hover:scale-105">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-[#003049] tracking-tight transition-colors duration-300 group-hover:text-[#669BBC]">Explora el Mapa</h3>
                            <div class="flex items-center gap-2">
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5 transition-colors duration-300 group-hover:text-slate-500 line-clamp-1 sm:line-clamp-none">Encuentra disponibilidad cerca de ti con vista satelital</p>
                                
                                <svg :class="{'rotate-180': showMap}" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#003049] transition-transform duration-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div x-show="showMap" x-transition x-cloak>
                    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
                    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                    <div id="map-show"
                        class="w-full h-[300px] rounded-3xl border border-slate-100 shadow-inner mt-6 z-0 relative"></div>
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
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

                            // Escuchar el resize para redibujar Leaflet
                            window.addEventListener('resize', function () {
                                if (document.getElementById('map-show').offsetHeight > 0) {
                                    map.invalidateSize();
                                }
                            });
                            // Forzar redibujo inicial
                            setTimeout(() => { map.invalidateSize(); }, 500);
                        });
                    </script>
                </div>
            </div>

            {{-- Video Tour Virtual (Solo si existe) --}}
            @if($inmueble->video_youtube_id)
                <div class="mt-8 pt-6 border-t border-slate-100/80" id="video-tour-section">
                    {{-- Header --}}
                    <div class="flex items-center justify-between mb-8 flex-wrap gap-4">
                        <div class="flex items-center gap-4">
                            <div
                                class="h-14 w-14 rounded-2xl bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center text-white shadow-xl">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M19.59 6.69a4.83 4.83 0 01-3.77-2.75 12.61 12.61 0 00-7.38 0A4.83 4.83 0 014.67 6.69 46.55 46.55 0 004 12a46.55 46.55 0 00.67 5.31 4.83 4.83 0 003.77 2.75 12.61 12.61 0 007.38 0 4.83 4.83 0 003.77-2.75A46.55 46.55 0 0020 12a46.55 46.55 0 00-.41-5.31zM10 15V9l5 3z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-black text-[#003049] tracking-tight">Video Tour Virtual</h3>
                                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Recorre la propiedad desde
                                    donde estés</p>
                            </div>
                        </div>
                        @if($inmueble->video_canal)
                            <span
                                class="inline-flex items-center gap-2 bg-red-50 text-red-700 text-xs font-bold px-4 py-2 rounded-xl border border-red-100">
                                <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M19.59 6.69a4.83 4.83 0 01-3.77-2.75 12.61 12.61 0 00-7.38 0A4.83 4.83 0 014.67 6.69 46.55 46.55 0 004 12a46.55 46.55 0 00.67 5.31 4.83 4.83 0 003.77 2.75 12.61 12.61 0 007.38 0 4.83 4.83 0 003.77-2.75A46.55 46.55 0 0020 12a46.55 46.55 0 00-.41-5.31zM10 15V9l5 3z" />
                                </svg>
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
                                    <p class="text-lg font-black text-[#003049]">
                                        {{ \Carbon\Carbon::parse($inmueble->video_publicado_en)->format('M Y') }}
                                    </p>
                                    <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider mt-1">Publicado</p>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Título del video --}}
                    @if($inmueble->video_titulo)
                        <p class="text-base font-bold text-slate-700 mb-4 flex items-start gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-500 mt-0.5 shrink-0" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    d="M19.59 6.69a4.83 4.83 0 01-3.77-2.75 12.61 12.61 0 00-7.38 0A4.83 4.83 0 014.67 6.69 46.55 46.55 0 004 12a46.55 46.55 0 00.67 5.31 4.83 4.83 0 003.77 2.75 12.61 12.61 0 007.38 0 4.83 4.83 0 003.77-2.75A46.55 46.55 0 0020 12a46.55 46.55 0 00-.41-5.31zM10 15V9l5 3z" />
                            </svg>
                            {{ $inmueble->video_titulo }}
                        </p>
                    @endif

                    {{-- iframe responsivo --}}
                    <div class="relative w-full rounded-[2rem] overflow-hidden shadow-2xl border border-slate-100"
                        style="padding-top: 56.25%;">
                        <iframe src="https://www.youtube.com/embed/{{ $inmueble->video_youtube_id }}?rel=0&modestbranding=1"
                            class="absolute inset-0 w-full h-full" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen title="{{ $inmueble->video_titulo ?? 'Video Tour - ' . $inmueble->titulo }}">
                        </iframe>
                    </div>

                    {{-- Descripción del video (si viene de API) --}}
                    @if($inmueble->video_descripcion)
                        <div class="mt-6 bg-slate-50 rounded-2xl p-5 border border-slate-100">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Descripción del video</p>
                            <p class="text-sm text-slate-600 leading-relaxed whitespace-pre-line">{{ $inmueble->video_descripcion }}
                            </p>
                        </div>
                    @endif

                    {{-- Acciones --}}
                    <div class="mt-5 flex items-center flex-wrap gap-3">
                        <a href="{{ $inmueble->video_youtube }}" target="_blank" rel="noopener"
                            class="inline-flex items-center gap-2 text-xs font-bold text-red-600 hover:text-red-700 transition-colors bg-red-50 hover:bg-red-100 px-4 py-2 rounded-xl border border-red-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M19.59 6.69a4.83 4.83 0 01-3.77-2.75 12.61 12.61 0 00-7.38 0A4.83 4.83 0 014.67 6.69 46.55 46.55 0 004 12a46.55 46.55 0 00.67 5.31 4.83 4.83 0 003.77 2.75 12.61 12.61 0 007.38 0 4.83 4.83 0 003.77-2.75A46.55 46.55 0 0020 12a46.55 46.55 0 00-.41-5.31zM10 15V9l5 3z" />
                            </svg>
                            Ver en YouTube
                        </a>

                        @if($inmueble->video_actualizado_en)
                            <span class="text-xs text-slate-400 flex items-center gap-1">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Stats actualizados {{ \Carbon\Carbon::parse($inmueble->video_actualizado_en)->diffForHumans() }}
                            </span>
                        @endif

                        {{-- Botón refresh (solo para el propietario o admin) --}}
                        @if(auth()->check() && (auth()->id() === $inmueble->propietario_id || auth()->user()->es_admin))
                            <button id="btn-refresh-video" onclick="refreshVideoStats({{ $inmueble->id }})"
                                class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-[#003049] transition-all bg-slate-100 hover:bg-slate-200 px-4 py-2 rounded-xl border border-slate-200 ml-auto">
                                <svg id="refresh-icon" class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Actualizar estadísticas
                            </button>
                        @endif
                    </div>
                </div>
            @endif



            {{-- Finanzas y Pagos (solo para el propietario del inmueble) --}}
            @if(Auth::check() && Auth::id() === $inmueble->propietario_id && !empty($inmueble->contratos) && $inmueble->contratos->isNotEmpty())
            @php
                $contratoActivo = $inmueble->contratos->firstWhere('estatus', 'activo') ?? $inmueble->contratos->first();
            @endphp
            @if($contratoActivo)
            <div class="mt-8 pt-8 border-t border-slate-100/80">
                <div class="flex items-center gap-3 mb-8">
                    <div class="h-8 w-1 bg-[#C1121F] rounded-full"></div>
                    <h2 class="text-3xl font-black text-[#003049] tracking-tight">Finanzas y Pagos</h2>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <!-- Vista de Próximo Pago -->
                    <div class="bg-white rounded-[2rem] p-6 shadow-md border border-gray-100 flex flex-col justify-center gap-4">
                        <h3 class="font-bold text-slate-400 uppercase tracking-widest text-[10px]">Próximo Cobro Esperado</h3>
                        @php
                            $diaPago = \Carbon\Carbon::parse($contratoActivo->fecha_inicio)->day;
                            $vence = now()->setDay($diaPago);
                            if (now()->day >= $diaPago) $vence->addMonth();
                        @endphp
                        <div class="flex flex-wrap items-center justify-between gap-y-5 gap-x-6 mt-4">
                            <div class="flex items-center gap-4 flex-1 min-w-[240px]">
                                <div class="h-14 w-14 rounded-2xl bg-[#FDF0D5] flex items-center justify-center text-xl shadow-inner shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-6 h-6 text-[#003049]">
                                        <path fill-rule="evenodd" d="M9.293 2.293a1 1 0 011.414 0l7 7A1 1 0 0117 11h-1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-3a1 1 0 00-1-1H9a1 1 0 00-1 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-6H3a1 1 0 01-.707-1.707l7-7z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xl font-black text-[#003049] truncate" title="Renta de {{ $vence->translatedFormat('F') }}">Renta de {{ $vence->translatedFormat('F') }}</p>
                                    <p class="text-[11px] font-bold text-gray-400 mt-1 truncate">Inquilino: {{ $contratoActivo->inquilino->nombre ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="shrink-0 text-left sm:text-right bg-slate-50/50 sm:bg-transparent p-3 sm:p-0 rounded-xl w-full sm:w-auto border border-slate-100 sm:border-none">
                                <p class="text-3xl font-black text-[#003049]">${{ number_format($contratoActivo->renta_mensual, 2) }} <span class="text-xs text-gray-400 font-bold">MXN</span></p>
                                <p class="text-[10px] font-bold text-gray-400 mt-1">Vence: {{ $vence->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Resumen del Contrato -->
                     <div class="bg-[#FDF0D5]/40 rounded-[2rem] p-6 shadow-sm border border-[#FDF0D5] flex flex-col justify-center gap-4">
                        <h3 class="font-bold text-slate-500 uppercase tracking-widest text-[10px]">Detalles del Contrato Vigente</h3>
                        <div class="grid grid-cols-2 gap-4 h-full items-center text-center">
                            <div class="bg-white p-3 rounded-xl shadow-sm border border-gray-100">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Inicio</p>
                                <p class="text-lg font-black text-[#003049] mt-1">{{ \Carbon\Carbon::parse($contratoActivo->fecha_inicio)->format('d/m/Y') }}</p>
                            </div>
                            <div class="bg-white p-3 rounded-xl shadow-sm border border-gray-100">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Fin</p>
                                <p class="text-lg font-black text-[#003049] mt-1">{{ $contratoActivo->fecha_fin ? \Carbon\Carbon::parse($contratoActivo->fecha_fin)->format('d/m/Y') : 'Indefinido' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Historial (Demostrativo / Simple) -->
                <div class="bg-white rounded-[2rem] shadow-md border border-gray-100 overflow-hidden overflow-x-auto w-full mb-10">
                    <table class="w-full text-left min-w-[600px]">
                        <thead>
                            <tr class="bg-[#FDF0D5]/30 border-b border-gray-100">
                                <th class="px-6 py-4 text-[10px] font-black text-[#669BBC] uppercase tracking-widest">Concepto</th>
                                <th class="px-6 py-4 text-[10px] font-black text-[#669BBC] uppercase tracking-widest">Fecha de Pago</th>
                                <th class="px-6 py-4 text-[10px] font-black text-[#669BBC] uppercase tracking-widest">Monto</th>
                                <th class="px-6 py-4 text-[10px] font-black text-[#669BBC] uppercase tracking-widest text-right">Estatus</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="h-8 w-8 rounded-lg bg-[#669BBC]/10 flex items-center justify-center shrink-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-[#669BBC]">
                                                <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-bold text-[#003049] text-sm whitespace-nowrap">Depósito y 1er Mes</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-sm font-medium text-gray-500">{{ \Carbon\Carbon::parse($contratoActivo->fecha_inicio)->format('d/m/Y') }}</td>
                                <td class="px-6 py-5 text-sm font-black text-[#003049]">${{ number_format($contratoActivo->renta_mensual + ($contratoActivo->deposito ?? 0), 2) }}</td>
                                <td class="px-6 py-5 text-right">
                                    <span class="px-3 py-1 bg-[#669BBC]/20 text-[#003049] text-[8px] font-black uppercase tracking-widest rounded-full">Recibido</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
            @endif



            {{-- Sección de Reseñas --}}
            <div class="mt-8 pt-8 border-t border-slate-100/80">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
                    <div>
                        <h2 class="text-3xl font-black text-[#003049] tracking-tight">Experiencias y Reseñas</h2>
                        <p class="text-slate-500 font-medium mt-1">Lo que otros dicen sobre esta propiedad.</p>
                    </div>
                    <div
                        class="flex items-center gap-3 bg-amber-50 px-5 py-3 rounded-2xl border border-amber-100 self-start md:self-auto">
                        <span
                            class="text-2xl font-black text-amber-600">{{ number_format($inmueble->resenas->avg('puntuacion') ?? 0, 1) }}</span>
                        <div class="flex text-amber-400">
                            @for($i = 1; $i <= 5; $i++)
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 {{ $i <= ($inmueble->resenas->avg('puntuacion') ?? 0) ? '' : 'opacity-30' }}"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endfor
                        </div>
                        <span
                            class="text-xs font-bold text-amber-700/60 uppercase tracking-widest pl-2 border-l border-amber-200">{{ $inmueble->resenas->count() }}
                            Opiniones</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($inmueble->resenas as $resena)
                        <div
                            class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-4 mb-6">
                                <div
                                    class="h-12 w-12 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-500 font-black text-xl overflow-hidden shadow-inner">
                                    @if($resena->usuario->foto_perfil)
                                        <img src="{{ str_starts_with($resena->usuario->foto_perfil, 'http') ? $resena->usuario->foto_perfil : asset('storage/' . $resena->usuario->foto_perfil) }}"
                                            class="h-full w-full object-cover">
                                    @else
                                        {{ substr($resena->usuario->nombre, 0, 1) }}
                                    @endif
                                </div>
                                <div>
                                    <span class="block font-black text-[#003049] leading-tight">
                                        {{ $resena->usuario->nombre }}
                                        @if(Auth::id() === $resena->usuario_id)
                                            <span
                                                class="ml-2 text-[8px] bg-[#003049] text-white px-2 py-0.5 rounded-full uppercase tracking-tighter">Tú</span>
                                        @endif
                                    </span>
                                    <div class="flex text-amber-400 mt-0.5 scale-75 origin-left">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5 {{ $i <= $resena->puntuacion ? '' : 'opacity-30' }}"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        @endfor
                                    </div>
                                </div>
                                <span
                                    class="ml-auto text-[10px] font-bold text-slate-300 uppercase tracking-widest">{{ $resena->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="space-y-4">
                                <p class="text-slate-600 leading-relaxed italic text-sm">"{{ $resena->comentario }}"</p>

                                @auth
                                    @php
                                        $esAutor = Auth::id() === $resena->usuario_id;
                                        $esAdmin = Auth::user()->es_admin || Auth::user()->tieneRol('admin');
                                    @endphp

                                    @if($esAutor || $esAdmin)
                                        <div class="flex gap-6 pt-6 mt-2 border-t border-slate-50"
                                            x-data="{ editing: false, comentario: '{{ $resena->comentario }}', puntuacion: {{ $resena->puntuacion }} }">

                                            @if($esAutor)
                                                {{-- Botón Editar (Solo Autor) --}}
                                                <button @click="editing = !editing"
                                                    class="flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-[0.15em] hover:text-[#003049] transition-all group/edit">
                                                    <div
                                                        class="h-8 w-8 rounded-lg bg-slate-50 flex items-center justify-center group-hover/edit:bg-[#003049]/5 transition-colors">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </div>
                                                    <span x-text="editing ? 'Cerrar' : 'Editar'"></span>
                                                </button>
                                            @endif

                                            {{-- Botón Eliminar (Autor o Admin) --}}
                                            <form id="delete-resena-{{ $resena->id }}" action="{{ route('resenas.destroy', $resena) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" onclick="confirmDeleteResena({{ $resena->id }})"
                                                    class="flex items-center gap-2 text-[10px] font-black text-red-200/80 uppercase tracking-[0.15em] hover:text-red-500 transition-all group/del">
                                                    <div
                                                        class="h-8 w-8 rounded-lg bg-red-50/30 flex items-center justify-center group-hover/del:bg-red-50 transition-colors">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </div>
                                                    Eliminar
                                                </button>
                                            </form>

                                            @if($esAutor)
                                                {{-- Modal de Edición (Solo para el Autor) --}}
                                                <div x-show="editing" x-transition
                                                    class="fixed inset-0 bg-[#003049]/40 backdrop-blur-sm z-[2000] flex items-center justify-center p-4"
                                                    x-cloak>
                                                    <div class="bg-white w-full max-w-lg rounded-[2.5rem] p-8 shadow-2xl"
                                                        @click.away="editing = false">
                                                        <h4 class="text-xl font-black text-[#003049] mb-6">Actualizar Reseña</h4>
                                                        <form action="{{ route('resenas.update', $resena) }}" method="POST"
                                                            class="space-y-6">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="flex flex-col gap-2">
                                                                <label
                                                                    class="text-[10px] font-black text-[#003049] uppercase tracking-widest">Nueva
                                                                    Calificación</label>
                                                                <div class="flex gap-1">
                                                                    <template x-for="i in 5">
                                                                        <button type="button" @click="puntuacion = i"
                                                                            class="transition-transform hover:scale-125">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8"
                                                                                :class="puntuacion >= i ? 'text-amber-400' : 'text-slate-200'"
                                                                                viewBox="0 0 20 20" fill="currentColor">
                                                                                <path
                                                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                                            </svg>
                                                                        </button>
                                                                    </template>
                                                                    <input type="hidden" name="puntuacion" :value="puntuacion">
                                                                </div>
                                                            </div>
                                                            <textarea name="comentario" x-model="comentario" rows="4"
                                                                class="w-full rounded-2xl border-slate-200 bg-slate-50 p-4 text-sm focus:ring-2 focus:ring-[#003049] outline-none"></textarea>
                                                            <div class="flex gap-4">
                                                                <button type="submit"
                                                                    class="flex-1 bg-[#003049] text-white font-black py-4 rounded-xl text-xs uppercase tracking-widest">Guardar</button>
                                                                <button type="button" @click="editing = false"
                                                                    class="flex-1 bg-slate-100 text-slate-500 font-black py-4 rounded-xl text-xs uppercase tracking-widest">Cerrar</button>
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
                        <div
                            class="col-span-full py-16 flex flex-col items-center justify-center text-center bg-slate-50/50 rounded-[3rem] border-2 border-dashed border-slate-200">
                            <div
                                class="h-20 w-20 bg-white rounded-3xl flex items-center justify-center text-slate-200 mb-4 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-slate-400">Aún no hay reseñas</h3>
                            <p class="text-slate-400 text-sm max-w-xs mt-2 font-medium">Sé el primero en rentar esta propiedad y
                                compartir tu experiencia.</p>
                        </div>
                    @endforelse
                </div>

                {{-- Formulario de Nueva Reseña --}}
                @auth
                    @if(Auth::id() !== $inmueble->propietario_id)
                        <div class="mt-8 mb-8 bg-[#003049]/5 rounded-[2rem] p-5 lg:p-6 border border-[#003049]/10"
                            x-data="{ showForm: false }">
                            <div class="flex flex-col items-center justify-center text-center gap-4"
                                :class="showForm ? 'mb-8' : 'mb-2'">
                                <h3
                                    class="text-lg md:text-xl font-black text-[#003049] flex flex-col sm:flex-row items-center gap-3">
                                    <span
                                        class="flex h-10 w-10 items-center justify-center bg-[#003049] text-white rounded-xl shadow-lg shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                            class="w-5 h-5">
                                            <path
                                                d="M21.731 2.269a2.625 2.625 0 00-3.712 0l-1.157 1.158 3.71 3.71 1.159-1.157a2.625 2.625 0 000-3.711z" />
                                            <path
                                                d="M16.273 5.337l-3.71-3.71L3.926 10.263a4.5 4.5 0 00-1.077 1.637l-1.42 4.259a.75.75 0 00.95.95l4.259-1.42a4.5 4.5 0 001.637-1.077l8.636-8.636z" />
                                        </svg>
                                    </span>
                                    Cuéntanos tu experiencia
                                </h3>
                                <button @click="showForm = !showForm"
                                    class="flex items-center justify-center gap-2 px-6 py-2.5 bg-white text-[#003049] font-bold text-xs rounded-xl hover:bg-slate-50 transition border border-slate-200 shadow-sm whitespace-nowrap">
                                    <span x-text="showForm ? 'Contraer' : 'Comentar'"></span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform duration-300"
                                        :class="showForm ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>

                            <div x-show="showForm" x-transition x-cloak class="pt-2">
                                <form action="{{ route('resenas.store', $inmueble) }}" method="POST" class="space-y-5">
                                    @csrf
                                    <div class="flex flex-col gap-3">
                                        <label class="text-[10px] font-black text-[#003049] uppercase tracking-[0.2em]">Tu
                                            Calificación</label>
                                        <div class="flex gap-2" x-data="{ rating: 0, hover: 0 }">
                                            @for($i = 1; $i <= 5; $i++)
                                                <button type="button" @click="rating = {{ $i }}" @mouseenter="hover = {{ $i }}"
                                                    @mouseleave="hover = 0"
                                                    class="transition-all duration-300 transform hover:scale-125 focus:outline-none">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8"
                                                        :class="(hover || rating) >= {{ $i }} ? 'text-amber-400' : 'text-slate-200'"
                                                        viewBox="0 0 20 20" fill="currentColor">
                                                        <path
                                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                    </svg>
                                                </button>
                                            @endfor
                                            <input type="hidden" name="puntuacion" :value="rating" required>
                                        </div>
                                        @error('puntuacion')
                                            <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="space-y-2">
                                        <label
                                            class="text-[10px] font-black text-[#003049] uppercase tracking-[0.2em]">Comentario</label>
                                        <textarea name="comentario" rows="3" required
                                            placeholder="¿Qué te pareció la propiedad y la atención del dueño?"
                                            pattern="^[a-zA-Z0-9\s.,?!*\-áéíóúÁÉÍÓÚñÑ]*$"
                                            title="Solo se permiten letras, números y los siguientes signos: . , ? ! * -"
                                            oninput="this.value = this.value.replace(/[^a-zA-Z0-9\s.,?!*\-áéíóúÁÉÍÓÚñÑ]/g, '')"
                                            class="w-full rounded-2xl border-slate-200 bg-white/50 backdrop-blur-sm p-4 text-sm focus:ring-4 focus:ring-[#003049]/10 focus:border-[#003049] transition-all outline-none text-slate-700"></textarea>
                                        @error('comentario')
                                            <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <button type="submit"
                                        class="w-full md:w-auto px-8 py-3 bg-[#003049] text-white font-black rounded-xl shadow-lg shadow-[#003049]/20 hover:-translate-y-0.5 hover:brightness-110 transition-all uppercase tracking-widest text-[10px]">
                                        Publicar Reseña
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="mt-8 mb-8 bg-amber-50 rounded-3xl p-6 text-center border border-amber-100">
                            <p class="text-amber-700 font-bold mb-1 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                    class="w-4 h-4 inline mr-2 text-amber-500">
                                    <path fill-rule="evenodd"
                                        d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z"
                                        clip-rule="evenodd" />
                                </svg> Estás viendo tu propia publicación
                            </p>
                            <p class="text-amber-600 text-xs">Los dueños no pueden calificar sus propias propiedades.</p>
                        </div>
                    @endif
                @else
                    <div class="mt-8 mb-8 bg-slate-100/50 rounded-3xl p-6 text-center border-2 border-dashed border-slate-200">
                        <p class="text-[#003049] font-bold mb-4">¿Quieres compartir tu experiencia?</p>
                        <a href="{{ route('login') }}"
                            class="inline-block px-8 py-3 bg-[#003049] text-white font-black rounded-2xl shadow-lg hover:-translate-y-1 transition-all uppercase tracking-widest text-xs">
                            Inicia sesión para calificar
                        </a>
                    </div>
                @endauth
            </div>


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
                        const btn = document.getElementById('btn-refresh-video');
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
                                    title: 'Estadísticas actualizadas',
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
        {{-- ==== SECCIÓN PARA PROPIETARIOS: HISTORIAL DE PAGOS ==== --}}
        @if(auth()->check() && auth()->id() === $inmueble->propietario_id && isset($inmueble->contratos) && $inmueble->contratos->isNotEmpty())
            <div class="mt-12 bg-white rounded-3xl p-6 lg:p-8 shadow-sm border border-slate-200">
                <div class="flex items-center gap-3 mb-8">
                    <div class="h-8 w-1 bg-[#669BBC] rounded-full"></div>
                    <h2 class="text-3xl font-black text-[#003049] tracking-tight">Historial de Inquilinos y Pagos</h2>
                </div>

                <div class="bg-white rounded-[2.5rem] shadow-xl shadow-gray-200/40 border border-gray-100 overflow-hidden overflow-x-auto">
                    <table class="w-full text-left min-w-[700px]">
                        <thead>
                            <tr class="bg-[#FDF0D5]/30 border-b border-gray-100">
                                <th class="px-8 py-5 text-xs font-black text-[#669BBC] uppercase tracking-widest">Inquilino</th>
                                <th class="px-8 py-5 text-xs font-black text-[#669BBC] uppercase tracking-widest">Contrato / Concepto</th>
                                <th class="px-8 py-5 text-xs font-black text-[#669BBC] uppercase tracking-widest">Fecha Ingreso</th>
                                <th class="px-8 py-5 text-xs font-black text-[#669BBC] uppercase tracking-widest">Monto Mensual</th>
                                <th class="px-8 py-5 text-xs font-black text-[#669BBC] uppercase tracking-widest text-right">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($inmueble->contratos as $contrato)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-4">
                                            <div class="h-10 w-10 rounded-xl bg-[#669BBC]/10 flex items-center justify-center text-lg shrink-0 font-bold text-[#669BBC]">
                                                {{ substr($contrato->inquilino->nombre ?? 'N/A', 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-[#003049] whitespace-nowrap">{{ $contrato->inquilino->nombre ?? 'Usuario Eliminado' }}</p>
                                                <p class="text-xs text-gray-400">{{ $contrato->inquilino->email ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <p class="font-bold text-[#003049] whitespace-nowrap">Renta + Depósito Inicial</p>
                                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Depósito: ${{ number_format($contrato->deposito ?? 0, 2) }}</p>
                                    </td>
                                    <td class="px-8 py-6 text-sm font-medium text-gray-600 whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($contrato->fecha_inicio)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-8 py-6 text-lg font-black text-[#003049] whitespace-nowrap">
                                        ${{ number_format($contrato->renta_mensual, 2) }}
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <div class="flex items-center justify-end gap-3">
                                            @if($contrato->estatus === 'activo')
                                                <span class="px-3 py-1 bg-[#669BBC]/20 text-[#003049] text-[10px] font-black uppercase tracking-widest rounded-full">Activo</span>
                                            @else
                                                <span class="px-3 py-1 bg-red-100 text-red-700 text-[10px] font-black uppercase tracking-widest rounded-full">{{ ucfirst($contrato->estatus) }}</span>
                                            @endif
                                            <button class="p-2 hover:bg-[#669BBC]/10 rounded-lg text-[#669BBC] transition-colors" title="Detalles del Contrato">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
        {{-- ==== FIN SECCIÓN PARA PROPIETARIOS ==== --}}

    </div> <!-- ESTE ES EL CORRESPONDIENTE AL max-w-4xl mx-auto DE LINEA 6 PERO CUIDADO SI FALTABA DIV -->
@endsection
