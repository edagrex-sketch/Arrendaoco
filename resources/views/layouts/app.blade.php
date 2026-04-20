<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ArrendaOco')</title>

    {{-- Importamos la fuente Instrument Sans (o Geist como en el diseño original) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&display=swap"
        rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    {{-- VITE --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" href="{{ asset('logo1.png') }}" type="image/x-icon">
    {{-- Lottie Player - Cargado en el head para evitar errores de renderizado --}}
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>


</head>

<body class="bg-background text-foreground font-sans antialiased">

    <div class="flex flex-col justify-start items-stretch">
        <!-- Barra de Navegación -->
        @php
            $novedadRenta = false;
            if (Auth::check()) {
                $novedadRenta = \App\Models\Contrato::where('inquilino_id', Auth::id())
                    ->where('estatus', 'activo')
                    ->where('updated_at', '>', now()->subHours(24))
                    ->exists() && !session()->has('renta_visto_' . Auth::id());
            }
        @endphp
        <nav class="bg-brand-dark border-b border-brand-dark sticky top-0 z-50 shadow-lg"
            x-data="{ mobileMenuOpen: false }">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center">
                <!-- 1. Logo y Nombre -->
                <div class="flex-shrink-0">
                    <a href="{{ Auth::check() ? route('inicio') : route('welcome') }}"
                        class="flex items-center gap-3 hover:opacity-90 transition-opacity">
                        <img src="{{ asset('logo1.png') }}" alt="Logo ArrendaOco" class="h-10 w-auto object-contain">
                        <span class="text-xl font-black text-white tracking-tight">
                            ArrendaOco
                        </span>
                    </a>
                </div>

                <!-- 2. Menú Central (Enlaces) - Solo Escritorio -->
                <div class="hidden lg:flex items-center gap-8 ml-10">
                    <a href="{{ Auth::check() ? route('inicio') : route('welcome') }}"
                        class="text-sm font-medium text-white hover:text-brand-light transition-colors border-b-2 border-transparent hover:border-brand-light py-1">
                        Inicio
                    </a>
                    @auth
                        @unless(Auth::user()->tieneRol('admin') || Auth::user()->es_admin)
                            <a href="{{ route('inmuebles.mis_rentas') }}"
                                class="text-sm font-medium text-white hover:text-[#669BBC] transition-colors border-b-2 border-transparent hover:border-[#669BBC] py-1 relative">
                                Mi renta
                                @if($novedadRenta)
                                    <span class="absolute -top-1 -right-2 w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                                @endif
                            </a>
                        @endunless
                    @endauth
                    <a href="{{ route('nosotros') }}"
                        class="text-sm font-medium text-white hover:text-[#669BBC] transition-colors border-b-2 border-transparent hover:border-[#669BBC] py-1">
                        Nosotros
                    </a>
                </div>

                <!-- 3. Acciones de Usuario / Hamburguesa (Empujado a la derecha) -->
                <div class="ml-auto flex items-center gap-2">
                    {{-- Content (Desktop Only) --}}
                    <div class="hidden lg:flex items-center gap-4">
                        @auth
                            @if (Auth::user()->tieneRol('admin') || Auth::user()->es_admin)
                                <a href="{{ route('admin.dashboard') }}"
                                    class="flex items-center gap-2 text-sm font-bold text-white bg-[#669BBC]/20 hover:bg-[#669BBC]/30 border border-[#669BBC]/50 rounded-lg px-4 py-1.5 transition-all">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                    </svg>
                                    Panel Admin
                                </a>
                            @endif

                            @if (Auth::user()->tieneRol('propietario'))
                                <a href="{{ route('inmuebles.create') }}" class="btn-danger px-5 py-2 text-sm">
                                    Publicar
                                </a>
                            @endif

                            <!-- Contenedor de Usuario y Notificaciones -->
                            <div class="hidden sm:flex items-center gap-3">
                                
                                <!-- 1. Centro de Notificaciones -->
                                <div x-data="{ openNotifications: false }" class="relative">
                                    <button @click="openNotifications = !openNotifications; if(openNotifications) fetchNotifications()" 
                                        class="relative p-2 text-white hover:text-brand-light transition-colors focus:outline-none group flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                        </svg>
                                        <span id="notification-badge" class="absolute -top-1 -right-1 w-5 h-5 bg-[#C1121F] text-white text-[10px] font-black flex items-center justify-center rounded-full border-2 border-brand-dark shadow-lg hidden">0</span>
                                    </button>

                                    <!-- Dropdown de Notificaciones -->
                                    <div x-show="openNotifications" @click.away="openNotifications = false"
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                        x-transition:leave="transition ease-in duration-150"
                                        class="absolute right-0 mt-3 w-80 sm:w-96 bg-white rounded-2xl shadow-2xl border border-gray-100 z-50 overflow-hidden" x-cloak>
                                        
                                        <div class="px-5 py-4 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                                            <h3 class="text-xs font-black text-brand-dark uppercase tracking-wider">Notificaciones</h3>
                                            <button @click="markAllAsRead()" class="text-[10px] font-bold text-blue-600 hover:text-blue-800 uppercase tracking-tighter">Limpiar todas</button>
                                        </div>

                                        <div id="notifications-container" class="max-h-[400px] overflow-y-auto">
                                            <div class="py-12 flex flex-col items-center justify-center space-y-3">
                                                <div class="w-6 h-6 border-2 border-brand-light border-t-transparent rounded-full animate-spin"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- 2. Perfil de Usuario -->
                                <div x-data="{ openProfile: false }" class="relative">
                                    <button @click="openProfile = !openProfile" @click.away="openProfile = false"
                                        class="flex items-center gap-2 text-sm font-bold text-white hover:text-brand-light transition-colors focus:outline-none">
                                        @if (Auth::user()->foto_perfil)
                                            <img src="{{ str_starts_with(Auth::user()->foto_perfil, 'http') ? Auth::user()->foto_perfil : asset('storage/' . Auth::user()->foto_perfil) }}"
                                                alt="Perfil" class="h-8 w-8 rounded-full object-cover border-2 border-white/20 flex-shrink-0">
                                        @else
                                            <div class="h-8 w-8 rounded-full bg-white/10 flex items-center justify-center text-xs border-2 border-white/20 flex-shrink-0">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-white">
                                                    <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.437.695A18.683 18.683 0 0112 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 01-.437-.695z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        @endif
                                        <span class="hidden xl:inline truncate max-w-[120px]">{{ Auth::user()->nombre }}</span>
                                        <span class="xl:hidden truncate max-w-[80px]">{{ explode(' ', Auth::user()->nombre)[0] }}</span>
                                        <svg :class="{'rotate-180': openProfile}" class="h-4 w-4 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>

                                    <!-- Dropdown de Perfil -->
                                    <div x-show="openProfile"
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                        x-transition:leave="transition ease-in duration-150"
                                        class="absolute right-0 mt-3 w-64 bg-white rounded-2xl shadow-2xl border border-gray-100 z-50 overflow-hidden" x-cloak>
                                        <div class="px-5 py-4 border-b border-gray-50 bg-gray-50/50">
                                            <p class="text-xs font-black text-brand-dark uppercase tracking-widest">{{ Auth::user()->roles->first()->nombre ?? 'Usuario' }}</p>
                                            <p class="text-sm font-medium text-gray-500 truncate">{{ Auth::user()->email }}</p>
                                        </div>
                                        <div class="py-2">
                                            <a href="{{ route('perfil.index') }}"
                                                class="flex items-center gap-3 px-5 py-3 text-[15px] font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 opacity-40">
                                                    <path fill-rule="evenodd" d="M18.685 19.097A9.723 9.723 0 0021.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 003.065 7.097A9.716 9.716 0 0012 21.75a9.716 9.716 0 006.685-2.653zm-12.54-1.285A7.486 7.486 0 0112 15a7.486 7.486 0 015.855 2.812A8.224 8.224 0 0112 20.25a8.224 8.224 0 01-5.855-2.438zM15.75 9a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" clip-rule="evenodd" />
                                                </svg>
                                                Mi Perfil
                                            </a>
                                            
                                            <!-- Opción para Arrendadores/Propietarios solamente -->
                                            @if (Auth::user()->tieneRol('propietario'))
                                            <a href="{{ route('inmuebles.index') }}"
                                                class="flex items-center gap-3 px-5 py-3 text-[15px] font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 opacity-40">
                                                    <path d="M11.47 3.84a.75.75 0 011.06 0l8.69 8.69a.75.75 0 101.06-1.06l-8.69-8.69a2.25 2.25 0 00-3.182 0l-8.69 8.69a.75.75 0 001.06 1.06l8.69-8.69z" />
                                                    <path d="M12 5.432l8.159 8.159c.03.03.06.058.091.086v6.198c0 1.035-.84 1.875-1.875 1.875H15a.75.75 0 01-.75-.75v-4.5a.75.75 0 00-.75-.75h-3a.75.75 0 00-.75.75V21a.75.75 0 01-.75.75H5.751a1.875 1.875 0 01-1.875-1.875v-6.198a2.29 2.29 0 00.091-.086L12 5.432z" />
                                                </svg>
                                                Mis Propiedades
                                            </a>
                                            @endif

                                            <div class="h-px bg-gray-50 my-2"></div>
                                            
                                            <form id="logout-form" method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <button type="button" onclick="confirmLogout()"
                                                    class="w-full flex items-center gap-3 px-5 py-3 text-[15px] text-red-600 hover:bg-red-50 font-medium transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 flex-shrink-0">
                                                        <path fill-rule="evenodd" d="M7.5 3.75A1.5 1.5 0 006 5.25v13.5a1.5 1.5 0 001.5 1.5h6a1.5 1.5 0 001.5-1.5V15a.75.75 0 011.5 0v3.75a3 3 0 01-3 3h-6a3 3 0 01-3-3V5.25a3 3 0 013-3h6a3 3 0 013 3V9a.75.75 0 01-1.5 0V5.25a1.5 1.5 0 00-1.5-1.5h-6zm10.72 4.72a.75.75 0 011.06 0l3 3a.75.75 0 010 1.06l-3 3a.75.75 0 11-1.06-1.06l1.72-1.72H9a.75.75 0 010-1.5h10.94l-1.72-1.72a.75.75 0 010-1.06z" clip-rule="evenodd" />
                                                    </svg>
                                                    Cerrar Sesión
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}"
                                class="text-xs sm:text-sm font-bold text-white hover:text-brand-light transition-all px-2 sm:px-4 py-2 border-b-2 border-transparent hover:border-brand-light">
                                Iniciar Sesión
                            </a>
                            <a href="{{ route('registro') }}"
                                class="bg-white text-brand-dark px-4 sm:px-6 py-2.5 rounded-xl text-xs sm:text-sm font-black shadow-lg hover:shadow-xl hover:scale-105 active:scale-95 transition-all">
                                Registrarse
                            </a>
                        @endauth
                    </div>

                    <!-- Botón Hamburguesa (Solo Móvil) -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen"
                        class="lg:hidden p-2 text-white hover:bg-white/10 rounded-xl transition-colors">
                        <svg x-show="!mobileMenuOpen" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16m-7 6h7" />
                        </svg>
                        <svg x-show="mobileMenuOpen" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Menú Lateral (Slide-over) Premium -->
            <div x-show="mobileMenuOpen" 
                class="fixed inset-0 z-[100] lg:hidden" 
                x-description="Mobile menu, show/hide based on mobile menu state." 
                x-cloak>
                
                <!-- Fondo oscurecido con desenfoque -->
                <div x-show="mobileMenuOpen" 
                    x-transition:enter="transition-opacity ease-linear duration-300" 
                    x-transition:enter-start="opacity-0" 
                    x-transition:enter-end="opacity-100" 
                    x-transition:leave="transition-opacity ease-linear duration-300" 
                    x-transition:leave-start="opacity-100" 
                    x-transition:leave-end="opacity-0" 
                    @click="mobileMenuOpen = false"
                    class="fixed inset-0 bg-brand-dark/40 backdrop-blur-sm"></div>

                <!-- Contenido del Slide-over -->
                <div x-show="mobileMenuOpen" 
                    x-transition:enter="transition ease-in-out duration-300 transform" 
                    x-transition:enter-start="translate-x-full" 
                    x-transition:enter-end="translate-x-0" 
                    x-transition:leave="transition ease-in-out duration-300 transform" 
                    x-transition:leave-start="translate-x-0" 
                    x-transition:leave-end="translate-x-full" 
                    class="fixed inset-y-0 right-0 max-w-xs w-full bg-white shadow-2xl flex flex-col overflow-y-auto">
                    
                    <!-- Botón Cerrar y Header -->
                    <div class="px-6 py-6 flex items-center justify-between border-b border-gray-100">
                        <span class="text-xl font-black text-brand-dark">ArrendaOco</span>
                        <button @click="mobileMenuOpen = false" class="p-2 rounded-xl bg-gray-50 text-gray-400 hover:text-brand-dark transition-colors">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

                    <!-- Sección de Usuario (Datos Enriquecidos) -->
                    @auth
                    <div class="px-6 py-8 bg-[#f8f9fa]">
                        <div class="flex items-center gap-4 mb-6">
                            @if (Auth::user()->foto_perfil)
                                <img src="{{ str_starts_with(Auth::user()->foto_perfil, 'http') ? Auth::user()->foto_perfil : asset('storage/' . Auth::user()->foto_perfil) }}"
                                    class="h-16 w-16 rounded-2xl object-cover border-4 border-white shadow-md">
                            @else
                                <div class="h-16 w-16 rounded-2xl bg-brand-dark flex items-center justify-center font-black text-white text-xl shadow-md uppercase">
                                    {{ substr(Auth::user()->nombre, 0, 1) }}
                                </div>
                            @endif
                            <div>
                                <h3 class="font-black text-brand-dark leading-tight">{{ Auth::user()->nombre }}</h3>
                                <p class="text-xs font-bold text-brand-light uppercase tracking-wider">{{ Auth::user()->roles->first()->nombre ?? 'Usuario' }}</p>
                            </div>
                        </div>

                        <!-- Fichas de Datos que faltaban -->
                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-lg bg-white flex items-center justify-center text-brand-dark shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0v1m-4 0a2 2 0 014 0v1" /></svg>
                                </div>
                                <div>
                                    <p class="text-[10px] uppercase font-bold text-gray-400">ID de Cliente</p>
                                    <p class="text-xs font-black text-brand-dark">#{{ str_pad(Auth::id(), 5, '0', STR_PAD_LEFT) }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-lg bg-white flex items-center justify-center text-brand-dark shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                </div>
                                <div class="overflow-hidden">
                                    <p class="text-[10px] uppercase font-bold text-gray-400">Correo Electrónico</p>
                                    <p class="text-xs font-bold text-brand-dark truncate">{{ Auth::user()->email }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endauth

                    <!-- Enlaces de Navegación -->
                    <nav class="flex-1 px-6 py-6 space-y-2">
                        <a href="{{ Auth::check() ? route('inicio') : route('welcome') }}" class="flex items-center gap-4 px-4 py-3 rounded-xl hover:bg-gray-50 transition-colors text-gray-600 font-bold">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            Inicio
                        </a>

                        @auth
                            <a href="{{ route('chats.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-xl hover:bg-gray-50 transition-colors text-gray-600 font-bold">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                                Mensajes
                            </a>
                            <a href="{{ route('favoritos.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-xl hover:bg-gray-50 transition-colors text-gray-600 font-bold">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                Mis Favoritos
                            </a>
                            @unless(Auth::user()->tieneRol('admin') || Auth::user()->es_admin)
                                <a href="{{ route('inmuebles.mis_rentas') }}" class="flex items-center gap-4 px-4 py-3 rounded-xl hover:bg-gray-50 transition-colors text-gray-600 font-bold">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                    Mi renta
                                </a>
                            @endunless
                            <a href="{{ route('perfil.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-xl hover:bg-gray-50 transition-colors text-gray-600 font-bold">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                Mi Perfil
                            </a>
                        @else
                            <div class="pt-4 space-y-4">
                                <a href="{{ route('login') }}" class="block w-full py-4 bg-brand-dark text-white rounded-2xl text-center font-black shadow-lg">Iniciar Sesión</a>
                                <a href="{{ route('registro') }}" class="block w-full py-4 border-2 border-brand-dark text-brand-dark rounded-2xl text-center font-black">Registrarse</a>
                            </div>
                        @endauth
                    </nav>

                    <!-- Logout Button -->
                    @auth
                    <div class="px-6 py-8 border-t border-gray-100">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full flex items-center justify-center gap-3 py-4 bg-red-50 text-red-600 font-black rounded-2xl hover:bg-red-100 transition-colors">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                Cerrar Sesión
                            </button>
                        </form>
                    </div>
                    @endauth
                </div>
            </div>
        </nav>
        <!-- Contenido Principal: Posición fija arriba para evitar huecos al hacer scroll -->
        <main class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-0 sm:py-4">
            @yield('content')
        </main>

        <!-- Footer simplificado -->
        <footer class="bg-brand-dark text-white pt-16 pb-8 border-t border-white/5 mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-12">
                    <div>
                        <div class="flex items-center gap-3 mb-6">
                            <img src="{{ asset('logo1.png') }}" class="h-8 w-auto grayscale brightness-200">
                            <span class="text-xl font-black">ArrendaOco</span>
                        </div>
                        <p class="text-gray-400 text-sm leading-relaxed">
                            La plataforma líder en Ocosingo para la gestión de rentas y propiedades urbanas.
                        </p>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold uppercase tracking-widest mb-6 text-brand-light">Contacto</h4>
                        <ul class="space-y-4 text-sm text-gray-400 font-medium">
                            <li class="flex items-center gap-3">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Ocosingo, Chiapas.
                            </li>
                            <li class="flex items-center gap-3">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                contacto@arrendaoco.com
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold uppercase tracking-widest mb-6 text-brand-light">Síguenos</h4>
                        <div class="flex gap-4">
                            <a href="#" class="h-10 w-10 bg-white/5 rounded-xl flex items-center justify-center hover:bg-brand-light hover:text-brand-dark transition-all">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </a>
                            <a href="#" class="h-10 w-10 bg-white/5 rounded-xl flex items-center justify-center hover:bg-brand-light hover:text-brand-dark transition-all">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16.36a4.198 4.198 0 110-8.396 4.198 4.198 0 010 8.396zm5.346-9.395a1.108 1.108 0 100-2.215 1.108 1.108 0 000 2.215z"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="pt-8 border-t border-white/5 text-center">
                    <p class="text-xs text-gray-500">© 2026 ArrendaOco. Todos los derechos reservados.</p>
                </div>
            </div>
        </footer>
    </div>

    <!-- Scripts de confirmación -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmLogout() {
            Swal.fire({
                title: '¿Cerrar sesión?',
                text: "Tendrás que ingresar de nuevo para ver tus propiedades.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#003049',
                cancelButtonColor: '#C1121F',
                confirmButtonText: 'Sí, salir',
                cancelButtonText: 'Cancelar',
                background: '#fff',
                borderRadius: '20px'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            })
        }

        function fetchNotifications() {
            const container = document.getElementById('notifications-container');
            const badge = document.getElementById('notification-badge');

            fetch('/notificaciones', {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                container.innerHTML = '';
                if (data.length === 0) {
                    container.innerHTML = '<div class="py-12 text-center text-gray-400 text-xs font-bold uppercase tracking-widest">Sin notificaciones</div>';
                    badge.classList.add('hidden');
                } else {
                    data.forEach(n => {
                        const item = document.createElement('div');
                        item.className = `px-5 py-4 border-b border-gray-50 hover:bg-gray-50 transition-colors ${!n.read_at ? 'bg-blue-50/30' : ''}`;
                        item.innerHTML = `
                            <p class="text-[13px] font-bold text-brand-dark mb-0.5">${n.titulo}</p>
                            <p class="text-xs text-gray-500 leading-relaxed">${n.mensaje}</p>
                        `;
                        container.appendChild(item);
                    });
                }
            });
        }

        function updateNotificationBadge() {
            const badge = document.getElementById('notification-badge');
            // Corregido: unread-count con guion medio para coincidir con la ruta de Laravel
            fetch('/notificaciones/unread-count')
            .then(res => {
                if(!res.ok) throw new Error('Not found');
                return res.json();
            })
            .then(count => {
                if(count > 0) {
                    badge.innerText = count;
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
            })
            .catch(err => console.log('Badge sync wait...')); // Silenciar 404 temporales si el usuario no tiene sesion
        }

        function markAllAsRead() {
            fetch('/notificaciones/mark-all-read', {
                method: 'POST',
                headers: { 
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).then(() => {
                fetchNotifications();
                updateNotificationBadge();
            });
        }

        // Cargar conteo inicial
        updateNotificationBadge();
    </script>
    @auth
    <script>
        // Actualizar cada 30 segundos
        setInterval(updateNotificationBadge, 30000);
    </script>
    @endauth
    @auth
    <x-arrendito />
    @endauth
    <!-- Firebase CDN Integration - Robust fall-back for production -->
    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
        import { getFirestore, collection, query, orderBy, onSnapshot, where } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-firestore.js";

        const firebaseConfig = {
            apiKey: "AIzaSyC3_c0n242ffdr2s4vF9H9xEVgs8WD83k4",
            authDomain: "arrendaoco-fad79.firebaseapp.com",
            projectId: "arrendaoco-fad79",
            storageBucket: "arrendaoco-fad79.firebasestorage.app",
            messagingSenderId: "32992727938",
            appId: "1:32992727938:web:22344c7c04f5087d9e359b"
        };

        const app = initializeApp(firebaseConfig);
        const db = getFirestore(app);

        // Definir funciones Globales para el Chat
        window.FirebaseChat = {
            getChatId: function(uid1, uid2) {
                const ids = [uid1.toString(), uid2.toString()].sort();
                return ids.join('_');
            },
            listenToAllChats: function(userId, callback) {
                const q1 = query(collection(db, "chats"), where("usuario_1", "==", userId.toString()));
                const q2 = query(collection(db, "chats"), where("usuario_2", "==", userId.toString()));
                
                const unsub1 = onSnapshot(q1, (snapshot) => {
                    snapshot.docChanges().forEach((change) => {
                        if (change.type === "modified") callback(change.doc.data(), change.doc.id);
                    });
                });
                const unsub2 = onSnapshot(q2, (snapshot) => {
                    snapshot.docChanges().forEach((change) => {
                        if (change.type === "modified") callback(change.doc.data(), change.doc.id);
                    });
                });
                return () => { unsub1(); unsub2(); };
            }
        };
        console.log('🔥 Firebase cargado vía CDN exitosamente');
    </script>
</body>

</html>