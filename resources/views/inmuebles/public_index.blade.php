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
                @if (request('ubicacion') || request('categoria') || request('rango_precio'))
                    Resultados para tus filtros seleccionados.
                @else
                    Mostrando todas las propiedades disponibles en Ocosingo.
                @endif
            </p>

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

    <section class="max-w-7xl mx-auto px-4 mb-20 -mt-8">
        @if ($inmuebles->isEmpty())
            <div class="bg-white rounded-[3rem] p-20 text-center border border-slate-100 shadow-xl shadow-[#003049]/5 max-w-4xl mx-auto">
                <div class="bg-slate-50 w-24 h-24 rounded-3xl flex items-center justify-center mx-auto mb-8 text-[#003049] shadow-inner">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 opacity-40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <h3 class="text-3xl font-black text-[#003049] mb-4">No se encontraron resultados</h3>
                <p class="text-slate-500 text-lg mb-10 max-w-md mx-auto">Intenta ajustar tus filtros de ubicación, precio o categoría para encontrar la propiedad ideal.</p>
                <a href="{{ route('inmuebles.public_search') }}"
                    class="inline-flex items-center justify-center px-10 py-4 bg-[#003049] text-white font-black rounded-full hover:scale-105 transition-all shadow-xl shadow-[#003049]/20 group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 group-hover:rotate-180 transition-transform duration-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                    </svg>
                    Restablecer búsqueda
                </a>
            </div>
        @else
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6 border-b border-slate-100 pb-8">
                <div class="space-y-2">
                    <span class="text-[10px] font-black text-[#64748B] uppercase tracking-[0.3em]">Resultados de búsqueda</span>
                    <h2 class="text-4xl font-black text-[#003049] tracking-tight">
                        Estos son tus resultados
                    </h2>
                </div>
                <div class="bg-white px-6 py-2 rounded-full border border-slate-100 shadow-sm flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                    <span class="text-sm font-black text-[#003049]">
                        {{ $inmuebles->total() }} <span class="text-slate-400 font-bold uppercase text-[10px] ml-1">Propiedades</span>
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($inmuebles as $inmueble)
                    <div class="group bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md hover:-translate-y-1">
                        {{-- Imagen --}}
                        <div class="relative h-56 overflow-hidden">
                            @if ($inmueble->imagen)
                                <img src="{{ str_starts_with($inmueble->imagen, 'http') ? $inmueble->imagen : (str_contains($inmueble->imagen, 'storage/') ? asset($inmueble->imagen) : asset('storage/' . $inmueble->imagen)) }}" alt="{{ $inmueble->titulo }}"
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                            @else
                                <div class="w-full h-full bg-slate-50 flex items-center justify-center text-slate-300">
                                    <span class="text-xs font-bold uppercase tracking-widest">Sin imagen</span>
                                </div>
                            @endif

                            {{-- Badge de Precio --}}
                            <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm px-3 py-1.5 rounded-full shadow-sm border border-slate-100">
                                <span class="font-bold text-[#003049]">${{ number_format($inmueble->renta_mensual ?? 0) }}</span>
                                <span class="text-[10px] text-slate-500">/ mes</span>
                            </div>

                            {{-- Botón Favorito --}}
                            @auth
                                @unless(Auth::user()->tieneRol('admin') || Auth::user()->es_admin)
                                <div class="absolute top-4 left-4 z-10" x-data="{ 
                                    isFavorited: {{ in_array($inmueble->id, $favoritosIds ?? []) ? 'true' : 'false' }},
                                    loading: false,
                                    toggle() {
                                        if (this.loading) return;
                                        this.loading = true;
                                        fetch('{{ route('favoritos.toggle', $inmueble) }}', {
                                            method: 'POST',
                                            headers: {
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                'Accept': 'application/json',
                                                'X-Requested-With': 'XMLHttpRequest'
                                            }
                                        })
                                        .then(res => res.json())
                                        .then(data => {
                                            if(data.success) {
                                                this.isFavorited = data.agregado;
                                                
                                                // Toast de confirmación premium
                                                 const Toast = Swal.mixin({
                                                     toast: true,
                                                     position: 'top-end',
                                                     showConfirmButton: false,
                                                     timer: 1500
                                                 });

                                                 Toast.fire({
                                                     icon: 'success',
                                                     title: data.agregado ? 'Agregado' : 'Eliminado'
                                                 });
                                            }
                                        })
                                        .finally(() => this.loading = false);
                                    }
                                }">
                                    <button @click.prevent="toggle()" 
                                        class="h-10 w-10 flex items-center justify-center rounded-full bg-white/90 backdrop-blur-md shadow-lg transition-all hover:scale-110 active:scale-95 group/fav"
                                        :class="isFavorited ? 'text-red-500' : 'text-slate-400'">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 transition-all duration-300" 
                                             :class="isFavorited ? 'fill-current' : 'fill-none'" 
                                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                    </button>
                                </div>
                                @endunless
                            @endauth
                        </div>

                        {{-- Contenido --}}
                        <div class="p-6">
                            <h3 class="font-bold text-lg text-[#003049] line-clamp-1 mb-1">
                                {{ $inmueble->titulo }}</h3>
                            <p class="text-sm text-slate-400 flex items-center gap-1.5 mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{ $inmueble->direccion }}
                            </p>

                            <div class="flex items-center justify-between py-4 border-t border-slate-100 mb-6">
                                <div class="flex items-center gap-4">
                                    <div class="flex items-center gap-1.5 text-slate-500" title="Habitaciones">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="w-5 h-5 opacity-70" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M3 11v3a2 2 0 002 2h14a2 2 0 002-2v-3"></path><path d="M5 16v2"></path><path d="M19 16v2"></path><path d="M5 11V7a2 2 0 012-2h10a2 2 0 012 2v4"></path><path d="M5 11h14"></path>
                                        </svg>
                                        <span class="text-base font-bold text-slate-700">{{ $inmueble->habitaciones }} <span class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider ml-0.5">Hab</span></span>
                                    </div>
                                    <div class="flex items-center gap-1.5 text-slate-500" title="Baños">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="w-5 h-5 opacity-70" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M9 6 6.5 3.5a1.5 1.5 0 0 0-1-.5C4.683 3 4 3.683 4 4.5V17a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-5"/><line x1="10" x2="8" y1="5" y2="7"/><line x1="2" x2="22" y1="12" y2="12"/><line x1="7" x2="7" y1="19" y2="21"/><line x1="17" x2="17" y1="19" y2="21"/>
                                        </svg>
                                        <span class="text-base font-bold text-slate-700">{{ $inmueble->banos }} <span class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider ml-0.5">Baño</span></span>
                                    </div>
                                    <div class="flex items-center gap-1.5 text-slate-500" title="Superficie">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="w-5 h-5 opacity-70" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="m21 21-6-6m6 6v-4.8m0 4.8h-4.8"/><path d="M3 16.2V21m0 0h4.8M3 21l6-6"/><path d="M21 7.8V3m0 0h-4.8M21 3l-6 6"/><path d="M3 7.8V3m0 0h4.8M3 3l6 6"/>
                                        </svg>
                                        <span class="text-base font-bold text-slate-700">{{ number_format($inmueble->metros ?? 0, 0) }} <span class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider ml-0.5">M²</span></span>
                                    </div>
                                </div>
                                @auth
                                    @if(Auth::id() === $inmueble->propietario_id)
                                        <div class="flex-shrink-0 ml-2" title="Tu propiedad">
                                            <div class="h-10 w-10 flex items-center justify-center rounded-full border-[3px] border-[#E63946] bg-white shadow-sm overflow-hidden group/owner group-hover:scale-105 transition-transform p-1">
                                                @if(Auth::user()->foto_perfil)
                                                    <img src="{{ str_starts_with(Auth::user()->foto_perfil, 'http') ? Auth::user()->foto_perfil : asset('storage/' . Auth::user()->foto_perfil) }}" class="w-full h-full object-cover rounded-full">
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full text-orange-500" viewBox="0 0 24 24" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.437.695A18.683 18.683 0 0112 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 01-.437-.695z" clip-rule="evenodd" />
                                                    </svg>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                @endauth
                            </div>

                            <a href="{{ route('inmuebles.show', $inmueble) }}"
                                class="flex w-full py-4 items-center justify-center rounded-2xl bg-slate-100 text-sm font-black text-[#003049] transition-all hover:bg-slate-200 uppercase tracking-widest">
                                Ver Detalles
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-12 flex justify-center">
                {{ $inmuebles->withQueryString()->links() }}
            </div>
        @endif
        </div>
        </div>
    @endsection
