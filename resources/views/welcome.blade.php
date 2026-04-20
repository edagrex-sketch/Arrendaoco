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
    <section class="mb-12 py-4" x-data="{ 
        filtersExpanded: false, 
        isDesktop: window.innerWidth >= 768 
    }" @resize.window="isDesktop = window.innerWidth >= 768; if(isDesktop) filtersExpanded = false">
        <div class="w-full max-w-5xl mx-auto rounded-3xl bg-white p-5 sm:p-10 shadow-[0_20px_50px_rgba(0,48,73,0.05)] border border-gray-100">
            <h2 class="mb-8 text-center text-2xl sm:text-4xl font-black text-[#003049] tracking-tight">
                Encuentra tu próximo hogar en Ocosingo
            </h2>

            {{-- Botón de Búsqueda (Trigger para Móvil) --}}
            <button @click="filtersExpanded = !filtersExpanded" 
                class="flex md:hidden w-full items-center justify-between bg-brand-dark text-white px-6 py-4 rounded-2xl font-bold shadow-lg shadow-brand-dark/20 transition-all active:scale-[0.98] mb-4"
                x-show="!filtersExpanded && !isDesktop" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95">
                <div class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <span>Buscar propiedades...</span>
                </div>
                <svg :class="{'rotate-180': filtersExpanded}" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <form id="form-busqueda" action="{{ route('inmuebles.public_search') }}" method="GET"
                    class="flex flex-col gap-4 md:flex-row items-end"
                    x-show="filtersExpanded || isDesktop"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 -translate-y-4"
                    x-transition:enter-end="opacity-100 translate-y-0">

                    {{-- Contenedor de Campos --}}
                    <div class="grid grid-cols-1 md:flex md:flex-row gap-4 w-full items-end">
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
                                    class="flex h-12 w-full rounded-md border border-input bg-background px-3 py-2 pl-10 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary shadow-sm">
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
                                    class="flex h-12 w-full appearance-none rounded-md border border-input bg-background px-3 py-2 pl-10 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary shadow-sm">
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
                                    class="flex h-12 w-full appearance-none rounded-md border border-input bg-background px-3 py-2 pl-10 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary shadow-sm">
                                    <option value="">Todas</option>
                                    <option value="casa" {{ request('categoria') == 'casa' ? 'selected' : '' }}>Casa</option>
                                    <option value="departamento" {{ request('categoria') == 'departamento' ? 'selected' : '' }}>
                                        Departamento</option>
                                    <option value="cuarto" {{ request('categoria') == 'cuarto' ? 'selected' : '' }}>Cuarto</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex gap-3 w-full md:w-auto">
                            {{-- Botón: Ocultar (Solo Móvil) --}}
                            <button type="button" @click="filtersExpanded = false"
                                class="flex md:hidden items-center justify-center w-12 h-12 rounded-xl bg-slate-100 text-brand-dark hover:bg-slate-200 transition-all border border-slate-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7" />
                                </svg>
                            </button>

                            <button type="submit"
                                class="btn-primary flex-1 md:flex-none md:w-auto px-8 h-12 flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Buscar
                            </button>
                        </div>
                    </div>
                </form>
        </div>
    </section>

    {{-- 2. MAPA DE EXPLORACIÓN (Invitados) --}}
    <section class="container mx-auto px-4 mb-16" x-data="{ 
        showHint: false,
        init() {
            setTimeout(() => {
                this.showHint = true;
                setTimeout(() => {
                    this.showHint = false;
                }, 12000);
            }, 1500);
        }
    }">
        <div class="relative w-fit">
            {{-- Burbuja Flotante y Roco --}}
            <div x-show="showHint"
                 x-transition:enter="transition ease-out duration-700 transform"
                 x-transition:enter-start="opacity-0 translate-y-8 scale-50"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-500 transform"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 scale-75"
                 class="absolute z-20 flex items-end gap-2 pointer-events-none"
                 style="bottom: 100%; left: 0; padding-bottom: 8px; display: none;">
                 
                 <!-- Roco Mascota (Izquierda) -->
                 <div class="w-16 h-16 origin-bottom transform scale-x-[-1]">
                     <lottie-player src="https://assets4.lottiefiles.com/packages/lf20_syqnfe7c.json"
                         background="transparent" speed="1" loop autoplay renderer="svg" style="width: 100%; height: 100%;">
                     </lottie-player>
                 </div>

                 <!-- Burbuja de Texto -->
                 <div class="bg-[#FDF0D5] px-5 py-3 rounded-2xl shadow-xl relative animate-[bounce_2s_infinite]">
                     <span class="text-[#003049] font-black text-sm tracking-wide">Puedes buscar en el mapa</span>
                     <!-- Triangulito de la burbuja -->
                     <div class="absolute -bottom-2 left-6 w-4 h-4 bg-[#FDF0D5] rotate-45 transform"></div>
                 </div>
            </div>

            <div class="flex items-center mb-6 cursor-pointer group w-fit" onclick="Swal.fire({
                icon: 'info',
                title: '¡Inicia sesión!',
                text: 'Para buscar y explorar propiedades directamente en el mapa interactivo, necesitas acceder a tu cuenta.',
                confirmButtonText: 'Iniciar Sesión',
                confirmButtonColor: '#003049',
                showCancelButton: true,
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if(result.isConfirmed) {
                    window.location.href = '{{ route('login') }}';
                }
            })">
                <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl bg-brand-dark flex items-center justify-center text-white shadow-lg transition-transform duration-300 group-hover:scale-105" style="background-color: #003049;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /></svg>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-brand-dark tracking-tight transition-colors duration-300 group-hover:text-brand-light" style="color: #003049;">Explora el Mapa</h2>
                    <div class="flex items-center gap-2">
                        <p class="text-slate-400 text-xs font-bold uppercase tracking-widest hidden md:block transition-colors duration-300 group-hover:text-slate-500">Encuentra disponibilidad cerca de ti con vista satelital</p>
                        
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-brand-dark transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3" style="color: #003049;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 3. SECCIÓN DE PROPIEDADES --}}
    <section class="container mx-auto px-4 mb-16 -mt-8" id="resultados-busqueda">
        <div class="flex items-center justify-between mb-8 border-b border-slate-100 pb-4">
            <h2 class="text-2xl font-black text-brand-dark">Propiedades Disponibles</h2>
            <span class="text-xs font-bold text-slate-400 bg-slate-50 px-3 py-1.5 rounded-full border border-slate-100">
                {{ $inmuebles->total() }} resultados
                {{-- @guest <span>(Vista Invitado)</span> @endguest --}}
            </span>
        </div>

        <div id="grid-inmuebles" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse ($inmuebles as $inmueble)
                @include('inmuebles.partials.card', ['inmueble' => $inmueble])
            @empty
                <div id="no-inmuebles" class="col-span-full py-16 text-center text-slate-400 font-medium uppercase tracking-widest opacity-50">No hay propiedades disponibles.</div>
            @endforelse
        </div>
        
        <div class="mt-12">{{ $inmuebles->links() }}</div>
    </section>

    {{-- 3. CTA: Publicar Inmueble --}}
    <section class="mb-12 px-2 sm:px-0">
        <div class="bg-[#003049] px-5 py-12 sm:px-10 sm:py-20 rounded-[40px] text-center relative overflow-hidden shadow-2xl shadow-[#003049]/20">
            <div class="absolute top-0 left-0 w-full h-full bg-white/5 pointer-events-none"></div>
            <div class="relative z-10">
                <h2 class="mb-4 text-2xl sm:text-4xl font-black text-white leading-tight">¿Tienes una propiedad en Ocosingo?</h2>
                <p class="mb-8 text-sm sm:text-lg text-white/80 max-w-2xl mx-auto font-medium">Únete a ArrendaOco y conecta con inquilinos verificados de la universidad y la ciudad.</p>
                <a href="{{ route('inmuebles.create') }}" 
                   class="inline-flex h-14 items-center justify-center gap-3 rounded-2xl bg-[#669BBC] px-8 text-sm font-black text-white shadow-xl hover:bg-[#669BBC]/90 hover:scale-105 transition-all uppercase tracking-widest">
                   <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                       <path d="M3.105 2.289a.75.75 0 00-.826.95l1.414 4.925A1.5 1.5 0 005.135 9.25h6.115a.75.75 0 010 1.5H5.135a1.5 1.5 0 00-1.442 1.086l-1.414 4.926a.75.75 0 00.826.95 28.896 28.896 0 0015.293-7.154.75.75 0 000-1.115A28.897 28.897 0 003.105 2.289z" />
                   </svg>
                   Publicar Inmueble Gratis
                </a>
            </div>
        </div>
    </section>

    {{-- Script de Alertas y Tiempo Real --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // TIEMPO REAL: NUEVOS INMUEBLES
            if (typeof window.Echo !== 'undefined') {
                window.Echo.channel('public-updates')
                    .listen('.nuevo-inmueble', (e) => {
                        console.log('🆕 Nueva propiedad detectada:', e);
                        
                        const noInmuebles = document.getElementById('no-inmuebles');
                        if (noInmuebles) noInmuebles.remove();

                        fetch(`/inmuebles/card-render/${e.inmueble.id}`, {
                            headers: { 'X-Requested-With': 'XMLHttpRequest' }
                        })
                        .then(res => res.text())
                        .then(html => {
                            const grid = document.getElementById('grid-inmuebles');
                            if (grid) {
                                const tempDiv = document.createElement('div');
                                tempDiv.innerHTML = html.trim();
                                const newCard = tempDiv.firstChild;
                                
                                newCard.classList.add('animate-pulse', 'border-[#003049]', 'border-2');
                                grid.insertBefore(newCard, grid.firstChild);

                                Swal.fire({
                                    title: '¡Nueva Casa!',
                                    text: `Se acaba de publicar: ${e.inmueble.titulo}`,
                                    icon: 'info',
                                    toast: true,
                                    position: 'bottom-start',
                                    showConfirmButton: false,
                                    timer: 6000,
                                    timerProgressBar: true
                                });

                                setTimeout(() => {
                                    newCard.classList.remove('animate-pulse', 'border-[#003049]', 'border-2');
                                }, 10000);
                            }
                        });
                    });
            }

            // Alertas originales de Laravel
            const form = document.getElementById('form-busqueda');
            const resultados = document.getElementById('resultados-busqueda');
            let timeout = null;

            if (form && resultados) {
                function realizarBusqueda(url) {
                    resultados.style.opacity = '0.5';
                    resultados.style.pointerEvents = 'none';

                    fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'text/html'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        resultados.innerHTML = html;
                    })
                    .catch(error => console.error('Error en la búsqueda:', error))
                    .finally(() => {
                        resultados.style.opacity = '1';
                        resultados.style.pointerEvents = 'auto';
                    });
                }

                function triggerBusqueda() {
                    const url = new URL(form.action);
                    const formData = new FormData(form);
                    const params = new URLSearchParams();
                    
                    for(let [key, value] of Object.entries(Object.fromEntries(formData))) {
                        if (value) {
                             params.append(key, value);
                        }
                    }

                    url.search = params.toString();
                    realizarBusqueda(url.href);
                }

                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    triggerBusqueda();
                });

                form.querySelectorAll('input, select').forEach(input => {
                    input.addEventListener(input.tagName === 'INPUT' ? 'input' : 'change', function() {
                        clearTimeout(timeout);
                        timeout = setTimeout(triggerBusqueda, 400);
                    });
                });

                // Interceptar clics en la paginación dentro de #resultados-busqueda
                resultados.addEventListener('click', function(e) {
                    const link = e.target.closest('nav[role="navigation"] a, .pagination a');
                    if (link && link.href) {
                        e.preventDefault();
                        realizarBusqueda(link.href);
                        
                        // Scrollear hacia los resultados suavemente
                        const offsetTop = resultados.getBoundingClientRect().top + window.scrollY - 100;
                        window.scrollTo({ top: offsetTop, behavior: 'smooth' });
                    }
                });
            }
        });
    </script>
@endsection
