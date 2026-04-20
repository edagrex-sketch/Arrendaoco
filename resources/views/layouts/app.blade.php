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

    <div class="min-h-screen flex flex-col">

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

                                    <!-- Dropdown de Perfil (El Slider) -->
                                    <div x-show="openProfile" x-transition:enter="transition ease-out duration-100"
                                        x-transition:enter-start="transform opacity-0 scale-95"
                                        x-transition:enter-end="transform opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-75"
                                        x-transition:leave-start="transform opacity-100 scale-100"
                                        x-transition:leave-end="transform opacity-0 scale-95"
                                        class="absolute right-0 mt-3 w-64 bg-white rounded-xl shadow-xl border border-gray-100 py-2 z-50 overflow-hidden" x-cloak>
                                        
                                        <div class="px-5 py-3 border-b border-gray-100">
                                            <p class="text-xs text-gray-500 font-medium">Conectado como</p>
                                            <p class="text-sm font-bold text-gray-900 truncate">{{ Auth::user()->email }}</p>
                                        </div>

                                    @unless(Auth::user()->tieneRol('admin') || Auth::user()->es_admin)
                                        <a href="{{ route('favoritos.index') }}"
                                            class="flex items-center gap-3 px-5 py-3 text-[15px] text-gray-700 hover:bg-gray-50 hover:text-brand-dark transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-red-400 flex-shrink-0">
                                                <path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z" />
                                            </svg>
                                            Mis Favoritos
                                        </a>
                                        @php $unreadCount = Auth::user()->unreadMessagesCount(); @endphp
                                        <a href="{{ route('chats.index') }}"
                                            class="flex items-center gap-3 px-5 py-3 text-[15px] text-gray-700 hover:bg-gray-50 hover:text-brand-dark transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-indigo-400 flex-shrink-0">
                                                <path fill-rule="evenodd" d="M4.804 21.644A6.707 6.707 0 006 21.75a6.721 6.721 0 003.583-1.029c.774.182 1.584.279 2.417.279 5.322 0 9.75-3.97 9.75-8.5S17.322 4 12 4s-9.75 3.97-9.75 8.5c0 2.012.738 3.87 1.988 5.345-.36 1.058-.926 2.024-1.67 2.856a.75.75 0 00.596 1.238 8.236 8.236 0 001.64-.205z" clip-rule="evenodd" />
                                            </svg>
                                            Mensajes
                                            @if($unreadCount > 0)
                                                <span class="bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full min-w-[18px] text-center ml-auto">
                                                    {{ $unreadCount }}
                                                </span>
                                            @endif
                                        </a>
                                    @endunless

                                    @if(Auth::user()->tieneRol('propietario'))
                                        <a href="{{ route('inmuebles.index') }}"
                                            class="flex items-center gap-3 px-5 py-3 text-[15px] text-gray-700 hover:bg-gray-50 hover:text-brand-dark transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-emerald-400 flex-shrink-0">
                                                <path d="M11.47 3.84a.75.75 0 011.06 0l8.69 8.69a.75.75 0 101.06-1.06l-8.689-8.69a2.25 2.25 0 00-3.182 0l-8.69 8.69a.75.75 0 001.061 1.06l8.69-8.69z" />
                                                <path d="M12 5.432l8.159 8.159c.03.03.06.058.091.086v6.198c0 1.035-.84 1.875-1.875 1.875H15a.75.75 0 01-.75-.75v-4.5a.75.75 0 00-.75-.75h-3a.75.75 0 00-.75.75V21a.75.75 0 01-.75.75H5.625a1.875 1.875 0 01-1.875-1.875v-6.198a2.29 2.29 0 00.091-.086L12 5.432z" />
                                            </svg>
                                            Mis Propiedades
                                        </a>

                                        @if(!Auth::user()->stripe_onboarding_completed)
                                            <a href="{{ route('stripe.connect.onboard') }}"
                                                class="flex items-center gap-3 px-5 py-3 text-[15px] text-[#003049] hover:bg-[#FDF0D5] transition-colors bg-[#FDF0D5]/50 border-y border-[#003049]/10 font-bold">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-[#003049] flex-shrink-0">
                                                    <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12.75 6a.75.75 0 00-1.5 0v.816a3.836 3.836 0 00-1.72.756c-.712.566-1.112 1.35-1.112 2.178 0 .829.4 1.612 1.113 2.178.502.4 1.102.647 1.719.756v2.978a2.536 2.536 0 01-.921-.421l-.879-.66a.75.75 0 00-.9 1.2l.879.66c.533.4 1.169.645 1.821.75V18a.75.75 0 001.5 0v-.81a4.124 4.124 0 001.821-.749c.745-.559 1.179-1.344 1.179-2.191 0-.847-.434-1.632-1.179-2.191a4.122 4.122 0 00-1.821-.75V8.354c.29.082.559.213.786.393l.415.33a.75.75 0 00.933-1.175l-.415-.33a3.836 3.836 0 00-1.719-.755V6z" clip-rule="evenodd" />
                                                </svg>
                                                Configurar Cobros
                                            </a>
                                        @endif
                                    @endif

                                    <a href="{{ route('perfil.index') }}"
                                        class="flex items-center gap-3 px-5 py-3 text-[15px] text-gray-700 hover:bg-gray-50 hover:text-brand-dark transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-gray-400 flex-shrink-0">
                                            <path fill-rule="evenodd" d="M11.078 2.25c-.917 0-1.699.663-1.85 1.567L9.05 4.889c-.02.12-.115.26-.297.348a7.493 7.493 0 00-.986.57c-.166.115-.334.126-.45.083L6.3 5.508a1.875 1.875 0 00-2.282.819l-.922 1.597a1.875 1.875 0 00.432 2.385l.84.692c.095.078.17.229.154.43a7.598 7.598 0 000 1.139c.015.2-.059.352-.153.43l-.841.692a1.875 1.875 0 00-.432 2.385l.922 1.597a1.875 1.875 0 002.282.818l1.019-.382c.115-.043.283-.031.45.082.312.214.641.405.985.57.182.088.277.228.297.35l.178 1.071c.151.904.933 1.567 1.85 1.567h1.844c.916 0 1.699-.663 1.85-1.567l.178-1.072c.02-.12.114-.26.297-.349.344-.165.673-.356.985-.57.167-.114.335-.125.45-.082l1.02.382a1.875 1.875 0 002.28-.819l.923-1.597a1.875 1.875 0 00-.432-2.385l-.84-.692c-.095-.078-.17-.229-.154-.43a7.614 7.614 0 000-1.139c-.016-.2.059-.352.153-.43l.84-.692c.708-.582.891-1.59.433-2.385l-.922-1.597a1.875 1.875 0 00-2.282-.818l-1.02.382c-.114.043-.282.031-.449-.083a7.49 7.49 0 00-.985-.57c-.183-.087-.277-.227-.297-.348l-.179-1.072a1.875 1.875 0 00-1.85-1.567h-1.843zM12 15.75a3.75 3.75 0 100-7.5 3.75 3.75 0 000 7.5z" clip-rule="evenodd" />
                                        </svg>
                                        Editar Perfil
                                    </a>

                                    <div class="border-t border-gray-100 mt-1 pt-1">
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
                                </div> {{-- Fin de Perfil (Línea 129) --}}
                            </div> {{-- Fin de sm:flex (Línea 95) --}}
                        @else
                            <!-- Invitado: Login + Registro -->
                            <a href="{{ route('login') }}"
                                class="text-sm font-medium text-white hover:text-[#669BBC] transition-colors">
                                Iniciar Sesión
                            </a>

                            <a href="{{ route('registro') }}"
                                class="btn-secondary px-5 py-2 text-sm">
                                Registrarse
                            </a>
                        @endauth
                    </div> {{-- Fin de Desktop Auth Menu --}}
                </div> {{-- Fin de Botones (Auth) --}}

                    {{-- Botón Hamburguesa: SOLO visible en móvil (oculto en lg+) --}}
                    <button
                        @click="mobileMenuOpen = !mobileMenuOpen"
                        id="nav-hamburger-btn"
                        class="flex lg:hidden items-center justify-center w-11 h-11 rounded-xl bg-white/10 hover:bg-white/20 border border-white/20 text-white transition-all focus:outline-none"
                        aria-label="Menú"
                    >
                        {{-- Ícono hamburguesa --}}
                        <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        {{-- Ícono X --}}
                        <svg x-show="mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div> {{-- Fin de h-16 --}}

            <!-- Menú Móvil Lateral (Premium Overlay) -->
            <div x-show="mobileMenuOpen" 
                class="fixed inset-0 z-[60] lg:hidden" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                x-cloak>
                
                <!-- Fondo oscuro desenfocado -->
                <div class="absolute inset-0 bg-brand-dark/40 backdrop-blur-md" @click="mobileMenuOpen = false"></div>

                <!-- Panel Lateral -->
                <div class="absolute inset-y-0 right-0 w-full max-w-[320px] bg-white shadow-2xl flex flex-col"
                    x-show="mobileMenuOpen"
                    x-transition:enter="transition ease-out duration-300 transform"
                    x-transition:enter-start="translate-x-full"
                    x-transition:enter-end="translate-x-0"
                    x-transition:leave="transition ease-in duration-200 transform"
                    x-transition:leave-start="translate-x-0"
                    x-transition:leave-end="translate-x-full">
                    
                    <div class="flex-1 overflow-y-auto custom-scrollbar">
                        {{-- Perfil del Usuario en el Menú --}}
                        <div class="p-8 bg-brand-dark overflow-hidden relative">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16"></div>
                            @auth
                                <div class="flex flex-col items-center text-center relative z-10">
                                    <div class="relative mb-4">
                                        @if (Auth::user()->foto_perfil)
                                            <img src="{{ str_starts_with(Auth::user()->foto_perfil, 'http') ? Auth::user()->foto_perfil : asset('storage/' . Auth::user()->foto_perfil) }}"
                                                alt="Perfil" class="h-20 w-20 rounded-3xl border-4 border-white/10 object-cover shadow-2xl">
                                        @else
                                            <div class="h-20 w-20 rounded-3xl bg-white/10 flex items-center justify-center text-3xl font-black text-white border-4 border-white/10">
                                                {{ substr(Auth::user()->nombre, 0, 1) }}
                                            </div>
                                        @endif
                                        <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-green-500 border-4 border-brand-dark rounded-full"></div>
                                    </div>
                                    <h3 class="text-xl font-black text-white truncate w-full px-4">{{ Auth::user()->nombre }}</h3>
                                    <p class="text-brand-light/60 text-xs font-bold uppercase tracking-widest mt-1">{{ Auth::user()->email }}</p>
                                </div>
                            @else
                                <div class="text-center relative z-10 pt-4">
                                    <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-white/10">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    </div>
                                    <h3 class="text-white font-black text-lg">Bienvenido</h3>
                                    <p class="text-brand-light/60 text-xs font-medium">Inicia sesión para una mejor experiencia</p>
                                </div>
                            @endauth
                        </div>

                        <div class="p-6 space-y-2">
                            <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4 px-4">Navegación</h4>
                            
                            <a href="{{ Auth::check() ? route('inicio') : route('welcome') }}"
                                class="flex items-center gap-4 p-4 rounded-2xl text-gray-700 hover:bg-slate-50 transition-all group">
                                <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center transition-colors group-hover:bg-brand-dark group-hover:text-white">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                </div>
                                <span class="font-bold">Explorar Inmuebles</span>
                            </a>

                            @auth
                            <a href="{{ route('perfil.index') }}"
                                class="flex items-center gap-4 p-4 rounded-2xl text-gray-700 hover:bg-slate-50 transition-all group">
                                <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center transition-colors group-hover:bg-brand-dark group-hover:text-white">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                                <span class="font-bold">Mi Cuenta</span>
                            </a>

                            <a href="{{ route('favoritos.index') }}"
                                class="flex items-center gap-4 p-4 rounded-2xl text-gray-700 hover:bg-slate-50 transition-all group">
                                <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center transition-colors group-hover:bg-brand-dark group-hover:text-white">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                </div>
                                <span class="font-bold">Mis Favoritos</span>
                            </a>

                            <a href="{{ route('chats.index') }}"
                                class="flex items-center gap-4 p-4 rounded-2xl text-gray-700 hover:bg-slate-50 transition-all group relative">
                                <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center transition-colors group-hover:bg-brand-dark group-hover:text-white">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.827-1.233L3 20l1.326-3.945C3.394 14.742 3 13.446 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                </div>
                                <span class="font-bold">Mis Mensajes</span>
                                @if($unreadCount > 0)
                                    <span class="absolute right-6 bg-red-500 text-white text-[10px] font-black px-2 py-1 rounded-full">{{ $unreadCount }}</span>
                                @endif
                            </a>

                            @if(Auth::user()->tieneRol('propietario'))
                                <a href="{{ route('inmuebles.index') }}"
                                    class="flex items-center gap-4 p-4 rounded-2xl text-gray-700 hover:bg-slate-50 transition-all group">
                                    <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center transition-colors group-hover:bg-brand-dark group-hover:text-white">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5"/></svg>
                                    </div>
                                    <span class="font-bold">Mis Propiedades</span>
                                </a>
                            @endif
                            @endauth

                            <a href="{{ route('nosotros') }}"
                                class="flex items-center gap-4 p-4 rounded-2xl text-gray-700 hover:bg-slate-50 transition-all group">
                                <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center transition-colors group-hover:bg-brand-dark group-hover:text-white">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <span class="font-bold">Nosotros</span>
                            </a>
                        </div>
                    </div>

                    <div class="p-6 border-t border-gray-100 bg-gray-50/50">
                        @auth
                            <button type="button" @click="confirmLogout()" 
                                class="w-full h-14 flex items-center justify-center gap-3 rounded-2xl bg-red-50 text-red-600 font-black hover:bg-red-100 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Cerrar Sesión
                            </button>
                        @else
                            <div class="grid grid-cols-1 gap-3">
                                <a href="{{ route('login') }}" class="w-full h-14 flex items-center justify-center font-bold text-brand-dark border-2 border-brand-dark rounded-2xl hover:bg-brand-dark hover:text-white transition-all">Iniciar Sesión</a>
                                <a href="{{ route('registro') }}" class="w-full h-14 flex items-center justify-center font-black bg-brand-dark text-white rounded-2xl shadow-xl shadow-brand-dark/20">Crear Cuenta</a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>
        <!-- Contenido Principal -->
        <main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @yield('content')
        </main>

        <!-- Footer -->
        <!-- Footer Premium -->
        <!-- Footer Premium (3 Columnas) -->
        <footer class="bg-brand-dark text-white pt-16 pb-8 border-t-4 border-brand-light">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- CAMBIO AQUÍ: lg:grid-cols-3 para 3 columnas iguales -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-12">

                    <!-- Columna 1: Marca -->
                    <div class="space-y-4">
                        <a href="{{ Auth::check() ? route('inicio') : route('welcome') }}"
                            class="flex items-center gap-2 group">
                            <img src="{{ asset('logo1.png') }}" alt="Logo ArrendaOco"
                                class="h-12 w-auto object-contain bg-white/5 rounded-lg p-1">
                            <span class="text-2xl font-bold tracking-tight text-white">
                                ArrendaOco
                            </span>
                        </a>
                        <p class="text-gray-300 text-sm leading-relaxed">
                            La plataforma líder en Ocosingo para encontrar tu próximo hogar.
                        </p>
                    </div>

                    <!-- Columna 2: Contacto (Centrado para balancear visualmente si se desea, o quitar 'md:text-center' y 'items-center' para alineación izquierda) -->
                    <div class="flex flex-col md:items-center">
                        <!-- Añadido flex y items-center para centrar el bloque visualmente -->
                        <div class="text-left"> <!-- Contenedor interno para mantener alineación del texto -->
                            <h3 class="text-lg font-bold mb-6 text-[#FDF0D5]">Contacto</h3>
                            <ul class="space-y-4 text-sm text-gray-300">
                                <li class="flex items-start gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#669BBC] mt-0.5"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span>Ocosingo, Chiapas.<br></span>
                                </li>
                                <li class="flex items-center gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#669BBC]" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    <a href="mailto:tu_correo@ejemplo.com" class="hover:text-white transition-colors">
                                        arrendaoco@gmail.com
                                    </a>


                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Columna 3: Redes Sociales (Alineado a la derecha en escritorio para balance) -->
                    <div class="flex flex-col md:items-end">
                        <div class="text-left md:text-right">
                            <h3 class="text-lg font-bold mb-6 text-[#FDF0D5]">Síguenos</h3>
                            <div class="flex gap-4 md:justify-end">
                                <a href="https://www.facebook.com/people/ArrendaOco/61587302949402/" target="_blank"
                                    class="bg-white/10 hover:bg-[#669BBC] p-3 rounded-lg transition-all duration-300 hover:-translate-y-1">
                                    <span class="sr-only">Facebook</span>
                                    <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" />
                                    </svg>
                                </a>
                                <a href="https://www.instagram.com/aoco.05/" target="_blank"
                                    class="bg-white/10 hover:bg-[#669BBC] p-3 rounded-lg transition-all duration-300 hover:-translate-y-1">
                                    <span class="sr-only">Instagram</span>
                                    <!-- Nuevo Icono: Estilo minimalista (Outline) -->
                                    <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                                        <path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"></path>
                                        <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Barra Copyright (sin cambios) -->
                <div
                    class="border-t border-white/10 pt-8 mt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-xs text-gray-400">
                        &copy; {{ date('Y') }} <span class="text-white font-medium">ArrendaOco</span>. Todos los
                        derechos reservados.
                    </p>
                    <div class="flex gap-6 text-xs text-gray-400">
                        <a href="{{ route('terminos') }}" class="hover:text-white transition-colors">Términos de
                            servicio</a>
                        <a href="{{ route('privacidad') }}" class="hover:text-white transition-colors">Política de
                            privacidad</a>
                    </div>
                </div>
            </div>
        </footer>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if (session('success'))
            Swal.fire({
                title: '¡Éxito!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonColor: '#003049',
                borderRadius: '1.5rem',
            });
        @endif

        @if (session('error'))
            Swal.fire({
                title: '¡Error!',
                text: "{{ session('error') }}",
                icon: 'error',
                confirmButtonColor: '#C1121F',
                borderRadius: '1.5rem',
            });
        @endif

        @if ($errors->any())
            Swal.fire({
                title: 'Atención',
                html: '<ul style="text-align: left; list-style-type: disc; padding-left: 1.5rem;">' +
                    @foreach ($errors->all() as $error)
                        '<li>{{ $error }}</li>' +
                    @endforeach
                    '</ul>',
                icon: 'warning',
                confirmButtonColor: '#003049',
                borderRadius: '1.5rem',
            });
        @endif

            function confirmLogout() {
                Swal.fire({
                    title: '¿Cerrar sesión?',
                    text: "¿Desea salir de su cuenta?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#003049',
                    cancelButtonColor: '#C1121F',
                    confirmButtonText: 'Sí, salir',
                    cancelButtonText: 'Cancelar',
                    borderRadius: '1.5rem',
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('logout-form').submit();
                    }
                })
            }

        function confirmDelete(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción no se puede deshacer.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#003049',
                cancelButtonColor: '#C1121F',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                borderRadius: '1.5rem',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            })
        }
    </script>
    @auth
    <script>
        // --- FUNCIONES DE NOTIFICACIONES ---
        function updateNotificationBadge() {
            fetch('{{ route("notifications.unread_count") }}')
                .then(res => res.json())
                .then(data => {
                    const badge = document.getElementById('notification-badge');
                    if (badge) {
                        if (data.count > 0) {
                            badge.innerText = data.count > 9 ? '9+' : data.count;
                            badge.classList.remove('hidden');
                        } else {
                            badge.classList.add('hidden');
                        }
                    }
                });
        }

        function fetchNotifications() {
            const container = document.getElementById('notifications-container');
            if(!container) return;
            fetch('{{ route("notifications.list") }}')
                .then(res => res.text())
                .then(html => { container.innerHTML = html; });
        }

        function markAsRead(id, url) {
            fetch(`/notifications/mark-read/${id}`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
            }).then(() => {
                updateNotificationBadge();
                if (url && url !== '#') window.location.href = url;
                else fetchNotifications();
            });
        }

        function markAllAsRead() {
            fetch('{{ route("notifications.mark_all_read") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
            }).then(() => {
                updateNotificationBadge();
                fetchNotifications();
            });
        }

        window.addEventListener('load', () => {
            // 1. EXISTENTE: Firebase Chat Notifications
            if (window.FirebaseChat) {
                const myId = "{{ Auth::id() }}";
                console.log('🔔 Escuchando notificaciones globales en Firebase para:', myId);
                
                window.FirebaseChat.listenToAllChats(myId, (chatData, chatId) => {
                    if (chatData.last_sender_id != myId && chatData.last_message) {
                        const urlParams = window.location.pathname;
                        if (!urlParams.includes(`/chats/${chatId}`)) {
                             Swal.fire({
                                title: 'Nuevo Mensaje',
                                text: chatData.last_message,
                                icon: 'info',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 4000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.addEventListener('click', () => {
                                        window.location.href = `/chats/${chatId}`;
                                    })
                                }
                            });
                        }
                    }
                });
            }

            // 2. NUEVO: Laravel Echo (Reverb) para Rentas y Disponibilidad
            if (window.Echo) {
                const myId = "{{ Auth::id() }}";
                console.log('⚡ Conectado a Laravel Echo (Reverb) - Usuario:', myId);

                // Escuchar cambios personales (Rentas, Aprobaciones, etc)
                window.Echo.private(`user.${myId}`)
                    .listen('ContratoStatusChanged', (e) => {
                        console.log('📩 Actualización de Renta Recibida:', e);
                        
                        Swal.fire({
                            title: 'Actualización de Renta',
                            text: `Tu contrato #${e.contratoId} ha cambiado a: ${e.nuevoEstado}`,
                            icon: 'success',
                            toast: true,
                            position: 'top-end',
                            timer: 5000,
                            timerProgressBar: true
                        }).then(() => {
                            // Si estamos en la página de rentas, refrescamos automáticamente
                            if (window.location.pathname.includes('mis-rentas') || window.location.pathname.includes('inmuebles')) {
                                window.location.reload();
                            }
                        });
                    });

                // Escuchar cambios globales de Inmuebles
                window.Echo.channel('inmuebles')
                    .listen('InmuebleStatusChanged', (e) => {
                        console.log('🏠 Inmueble cambió de estatus:', e);
                    });

                // ESCUCHAR NOTIFICACIONES
                window.Echo.private(`user.${myId}`)
                    .listen('.notification.received', (e) => {
                        console.log('🔔 Notificación en tiempo real:', e);
                        updateNotificationBadge();
                        Swal.fire({
                            title: e.notification.titulo,
                            text: e.notification.mensaje,
                            icon: 'info',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 6000,
                            timerProgressBar: true
                        });
                    });
            }
        });
    </script>
    @endauth
    @stack('scripts')
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