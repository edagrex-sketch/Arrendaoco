<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel Admin') — ArrendaOco</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&display=swap"
        rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" href="{{ asset('logo1.png') }}" type="image/x-icon">

    <style>
        /* Admin-specific styles */
        .admin-sidebar {
            background: linear-gradient(180deg, #001d2e 0%, #003049 50%, #00405f 100%);
        }
        .admin-nav-link {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .admin-nav-link:hover,
        .admin-nav-link.active {
            background: rgba(102, 155, 188, 0.15);
            border-left-color: #669BBC;
        }
        .admin-nav-link.active {
            background: rgba(102, 155, 188, 0.2);
            color: #fff;
        }
        .admin-stat-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .admin-stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 40px -10px rgba(0, 48, 73, 0.15);
        }
        .admin-content {
            background: #f8fafc;
            min-height: 100vh;
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-8px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .admin-animate-in {
            animation: slideIn 0.3s ease-out forwards;
        }
    </style>
</head>

<body class="font-sans antialiased bg-[#f1f5f9]" x-data="{ sidebarOpen: false }">

    <div class="flex min-h-screen">

        {{-- ═══════════════════════════════════════════════════════ --}}
        {{-- SIDEBAR (Desktop: fixed, Mobile: overlay)             --}}
        {{-- ═══════════════════════════════════════════════════════ --}}

        {{-- Mobile overlay --}}
        <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/50 z-40 lg:hidden" @click="sidebarOpen = false" x-cloak>
        </div>

        {{-- Sidebar --}}
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="admin-sidebar fixed inset-y-0 left-0 z-50 w-72 flex flex-col transition-transform duration-300 lg:translate-x-0 lg:static lg:z-auto">

            {{-- Logo Area --}}
            <div class="flex items-center gap-3 px-6 py-6 border-b border-white/10">
                <img src="{{ asset('logo1.png') }}" alt="Logo" class="h-10 w-auto object-contain">
                <div>
                    <span class="text-lg font-bold text-white tracking-tight block">ArrendaOco</span>
                    <span class="text-[10px] font-bold text-[#669BBC] uppercase tracking-[0.2em]">Panel de Administración</span>
                </div>
            </div>

            {{-- User Info --}}
            <div class="px-6 py-4 border-b border-white/5">
                <div class="flex items-center gap-3">
                    @if (Auth::user()->foto_perfil)
                        <img src="{{ str_starts_with(Auth::user()->foto_perfil, 'http') ? Auth::user()->foto_perfil : asset('storage/' . Auth::user()->foto_perfil) }}"
                            alt="Perfil" class="h-10 w-10 rounded-full object-cover border-2 border-[#669BBC]/40">
                    @else
                        <div class="h-10 w-10 rounded-full bg-[#669BBC]/20 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-[#669BBC]">
                                <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.437.695A18.683 18.683 0 0112 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 01-.437-.695z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    @endif
                    <div class="overflow-hidden">
                        <p class="text-sm font-bold text-white truncate">{{ Auth::user()->nombre }}</p>
                        <p class="text-[11px] text-gray-400 truncate">{{ Auth::user()->email }}</p>
                    </div>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 px-3 py-6 space-y-1 overflow-y-auto">
                <p class="px-4 mb-3 text-[10px] font-black text-[#669BBC]/70 uppercase tracking-[0.2em]">Navegación</p>

                <a href="{{ route('admin.dashboard') }}"
                    class="admin-nav-link flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium border-l-4
                    {{ request()->routeIs('admin.dashboard') ? 'active border-[#669BBC] text-white' : 'border-transparent text-gray-300 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    Dashboard
                </a>

                <p class="px-4 mt-6 mb-3 text-[10px] font-black text-[#669BBC]/70 uppercase tracking-[0.2em]">Gestión</p>

                <a href="{{ route('admin.usuarios.index') }}"
                    class="admin-nav-link flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium border-l-4
                    {{ request()->routeIs('admin.usuarios.*') ? 'active border-[#669BBC] text-white' : 'border-transparent text-gray-300 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Usuarios
                </a>

                <a href="{{ route('admin.inmuebles.index') }}"
                    class="admin-nav-link flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium border-l-4
                    {{ request()->routeIs('admin.inmuebles.*') ? 'active border-[#669BBC] text-white' : 'border-transparent text-gray-300 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    Propiedades
                </a>

                <a href="{{ route('admin.contratos.index') }}"
                    class="admin-nav-link flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium border-l-4
                    {{ request()->routeIs('admin.contratos.*') ? 'active border-[#669BBC] text-white' : 'border-transparent text-gray-300 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Contratos
                </a>

                <a href="{{ route('admin.resenas.index') }}"
                    class="admin-nav-link flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium border-l-4
                    {{ request()->routeIs('admin.resenas.*') ? 'active border-[#669BBC] text-white' : 'border-transparent text-gray-300 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                    Reseñas
                </a>

                <p class="px-4 mt-6 mb-3 text-[10px] font-black text-[#669BBC]/70 uppercase tracking-[0.2em]">Reportes</p>

                <a href="{{ route('admin.usuarios.reporte', request()->all()) }}"
                    class="admin-nav-link flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium border-l-4 border-transparent text-gray-300 hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    PDF Usuarios
                </a>

                <a href="{{ route('admin.inmuebles.reporte', request()->all()) }}"
                    class="admin-nav-link flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium border-l-4 border-transparent text-gray-300 hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    PDF Propiedades
                </a>

                <a href="{{ route('admin.contratos.reporte', request()->all()) }}"
                    class="admin-nav-link flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium border-l-4 border-transparent text-gray-300 hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    PDF Contratos
                </a>

                <a href="{{ route('admin.resenas.reporte', request()->all()) }}"
                    class="admin-nav-link flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium border-l-4 border-transparent text-gray-300 hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    PDF Reseñas
                </a>

                <p class="px-4 mt-6 mb-3 text-[10px] font-black text-[#669BBC]/70 uppercase tracking-[0.2em]">Sistema</p>

                <a href="{{ route('admin.respaldos.index') }}"
                    class="admin-nav-link flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium border-l-4
                    {{ request()->routeIs('admin.respaldos.*') ? 'active border-[#669BBC] text-white' : 'border-transparent text-gray-300 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                    </svg>
                    Respaldos
                </a>
            </nav>

            {{-- Bottom Area --}}
            <div class="border-t border-white/10 px-3 py-4 space-y-1">
                <a href="{{ route('perfil.index') }}"
                    class="admin-nav-link flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium border-l-4 border-transparent text-gray-300 hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Mi Perfil
                </a>

                <form id="logout-form" method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="button" onclick="confirmLogout()"
                        class="admin-nav-link w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium border-l-4 border-transparent text-red-400 hover:text-red-300 hover:bg-red-500/10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Cerrar Sesión
                    </button>
                </form>
            </div>
        </aside>

        {{-- ═══════════════════════════════════════════════════════ --}}
        {{-- MAIN CONTENT AREA                                     --}}
        {{-- ═══════════════════════════════════════════════════════ --}}
        <div class="flex-1 flex flex-col admin-content min-w-0">

            {{-- Top Bar --}}
            <header class="sticky top-0 z-30 bg-white/80 backdrop-blur-xl border-b border-slate-200/60 px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    {{-- Mobile menu toggle --}}
                    <button @click="sidebarOpen = !sidebarOpen"
                        class="lg:hidden p-2 rounded-xl text-slate-500 hover:bg-slate-100 hover:text-[#003049] transition-all">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16m-7 6h7" />
                        </svg>
                    </button>

                    {{-- Page Title --}}
                    <div class="hidden lg:block">
                        <h1 class="text-xl font-black text-[#003049]">@yield('page-title', 'Dashboard')</h1>
                        <p class="text-xs text-slate-400 font-medium">@yield('page-subtitle', 'Panel de administración de ArrendaOco')</p>
                    </div>

                    {{-- Right actions --}}
                    <div class="flex items-center gap-3">
                        <span class="hidden sm:inline-flex items-center gap-1.5 text-xs font-bold text-slate-400 bg-slate-100 px-3 py-1.5 rounded-full">
                            <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                            Administrador
                        </span>
                        <a href="{{ route('welcome') }}" target="_blank"
                            class="flex items-center gap-2 text-xs font-bold text-[#669BBC] bg-[#669BBC]/10 px-4 py-2 rounded-xl hover:bg-[#669BBC]/20 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                            Ver sitio público
                        </a>
                    </div>
                </div>
            </header>

            {{-- Main Content --}}
            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                @yield('content')
            </main>

            {{-- Footer --}}
            <footer class="border-t border-slate-200/60 px-6 py-4">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-2 text-xs text-slate-400">
                    <p>&copy; {{ date('Y') }} <span class="font-bold text-slate-500">ArrendaOco</span> — Panel de Administración</p>
                    <p>v1.0 · Ocosingo, Chiapas</p>
                </div>
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if (session('success'))
            Swal.fire({
                title: '¡Éxito!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonColor: '#003049',
            });
        @endif

        @if (session('error'))
            Swal.fire({
                title: '¡Error!',
                text: "{{ session('error') }}",
                icon: 'error',
                confirmButtonColor: '#C1121F',
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
            });
        @endif

        function confirmLogout() {
            Swal.fire({
                title: '¿Cerrar sesión?',
                text: "¿Desea salir de su cuenta de administrador?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#003049',
                cancelButtonColor: '#C1121F',
                confirmButtonText: 'Sí, salir',
                cancelButtonText: 'Cancelar',
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
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            })
        }
    </script>
    @stack('scripts')
</body>

</html>
