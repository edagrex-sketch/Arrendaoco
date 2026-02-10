<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        <nav class="bg-[#003049] border-b border-[#003049] sticky top-0 z-50 shadow-lg"
            x-data="{ mobileMenuOpen: false }">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <!-- 1. Logo y Nombre -->
                <a href="{{ Auth::check() ? route('inicio') : route('welcome') }}"
                    class="flex items-center gap-2 group hover:opacity-90 transition-opacity">
                    <!-- Cuadrado del logo en un azul más claro para resaltar -->
                    <img src="{{ asset('logo1.png') }}" alt="Logo ArrendaOco" class="h-10 w-auto object-contain">
                    <span class="text-xl font-bold text-white tracking-tight">
                        ArrendaOco<span class="text-[#669BBC]"></span>
                    </span>
                </a>
                <!-- 2. Menú Central (Enlaces) -->
                <div class="hidden md:flex items-center gap-8">
                    <a href="{{ Auth::check() ? route('inicio') : route('welcome') }}"
                        class="text-sm font-medium text-white hover:text-[#669BBC] transition-colors border-b-2 border-transparent hover:border-[#669BBC] py-1">
                        Inicio
                    </a>
                    @auth
                        @unless(Auth::user()->tieneRol('admin') || Auth::user()->es_admin)
                            <a href="{{ route('favoritos.index') }}"
                                class="text-sm font-medium text-white hover:text-[#669BBC] transition-colors border-b-2 border-transparent hover:border-[#669BBC] py-1">
                                Favoritos
                            </a>
                        @endunless
                    @endauth
                    @if (Auth::check() && Auth::user()->tieneRol('propietario'))
                        <a href="{{ route('inmuebles.index') }}"
                            class="text-sm font-medium text-white hover:text-[#669BBC] transition-colors border-b-2 border-transparent hover:border-[#669BBC] py-1">
                            Mis Propiedades
                        </a>
                    @endif
                    <a href="{{ route('nosotros') }}"
                        class="text-sm font-medium text-white hover:text-[#669BBC] transition-colors border-b-2 border-transparent hover:border-[#669BBC] py-1">
                        Nosotros
                    </a>
                </div>
                <!-- 3. Botones (Auth) -->
                <div class="flex items-center gap-4">
                    {{-- Desktop Auth Menu --}}
                    <div class="hidden md:flex items-center gap-4">
                        @auth
                            <!-- Usuario: Hola + Botón Publicar -->
                            <span class="text-sm text-gray-200 hidden sm:inline flex items-center gap-2">
                                <a href="{{ route('perfil.index') }}"
                                    class="flex items-center gap-2 font-bold text-white hover:underline">
                                    @if (Auth::user()->foto_perfil)
                                        <img src="{{ asset('storage/' . Auth::user()->foto_perfil) }}" alt="Perfil"
                                            class="h-8 w-8 rounded-full object-cover border-2 border-white/20">
                                    @endif
                                    {{ Auth::user()->nombre }}
                                </a>
                            </span>

                            @if (Auth::user()->tieneRol('admin') || Auth::user()->es_admin)
                                <div x-data="{ open: false }" class="relative">
                                    <button @click="open = !open" @click.away="open = false"
                                        class="flex items-center gap-1 text-sm font-medium text-white hover:text-[#669BBC] transition-colors border-2 border-[#669BBC] rounded px-3 py-1">
                                        Gestiones
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>

                                    <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                        x-transition:enter-start="transform opacity-0 scale-95"
                                        x-transition:enter-end="transform opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-75"
                                        x-transition:leave-start="transform opacity-100 scale-100"
                                        x-transition:leave-end="transform opacity-0 scale-95"
                                        class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                        <a href="{{ route('admin.usuarios.index') }}"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Usuarios
                                        </a>
                                        <a href="{{ route('inmuebles.index') }}"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Propiedades
                                        </a>
                                        <a href="{{ route('admin.resenas.index') }}"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Reseñas
                                        </a>
                                    </div>
                                </div>
                            @endif

                            @if (Auth::user()->tieneRol('propietario'))
                                <a href="{{ route('inmuebles.create') }}"
                                    class="inline-flex items-center justify-center rounded-full bg-[#C1121F] px-5 py-2 text-sm font-bold text-white shadow-md hover:bg-[#780000] transition-colors">
                                    Publicar
                                </a>
                            @endif

                            <form id="logout-form" method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="button" onclick="confirmLogout()"
                                    class="text-sm font-medium text-gray-300 hover:text-white hover:underline transition-colors">
                                    Cerrar sesión
                                </button>
                            </form>
                        @else
                            <!-- Invitado: Login + Registro -->
                            <a href="{{ route('login') }}"
                                class="text-sm font-medium text-white hover:text-[#669BBC] transition-colors">
                                Iniciar Sesión
                            </a>

                            <a href="{{ route('registro') }}"
                                class="inline-flex items-center justify-center rounded-lg bg-[#FDF0D5] px-5 py-2 text-sm font-bold text-[#003049] shadow hover:bg-white transition-transform active:scale-95">
                                Registrarse
                            </a>
                        @endauth
                    </div>

                    {{-- Hamburger Button --}}
                    <div class="flex md:hidden">
                        <button @click="mobileMenuOpen = !mobileMenuOpen"
                            class="text-white hover:text-[#669BBC] transition-colors p-2 rounded-lg focus:outline-none">
                            <svg x-show="!mobileMenuOpen" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16m-7 6h7" />
                            </svg>
                            <svg x-show="mobileMenuOpen" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" x-cloak>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Menú Móvil (Dropdown) -->
            <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-4"
                class="md:hidden bg-[#002538] border-t border-white/5 shadow-2xl overflow-hidden"
                @click.away="mobileMenuOpen = false" x-cloak>
                <div class="px-4 pt-4 pb-8 space-y-2">
                    <a href="{{ Auth::check() ? route('inicio') : route('welcome') }}"
                        class="block px-4 py-4 text-base font-bold text-white hover:bg-white/5 rounded-2xl transition-all">
                        Inicio
                    </a>
                    @auth
                        @unless(Auth::user()->tieneRol('admin') || Auth::user()->es_admin)
                            <a href="{{ route('favoritos.index') }}"
                                class="block px-4 py-4 text-base font-bold text-white hover:bg-white/5 rounded-2xl transition-all">
                                Favoritos
                            </a>
                        @endunless
                    @endauth
                    @if (Auth::check() && Auth::user()->tieneRol('propietario'))
                        <a href="{{ route('inmuebles.index') }}"
                            class="block px-4 py-4 text-base font-bold text-white hover:bg-white/5 rounded-2xl transition-all">
                            Mis Propiedades
                        </a>
                    @endif
                    <a href="{{ route('nosotros') }}"
                        class="block px-4 py-4 text-base font-bold text-white hover:bg-white/5 rounded-2xl transition-all">
                        Nosotros
                    </a>

                    <div class="pt-6 mt-4 border-t border-white/10 space-y-4">
                        @auth
                            <div class="flex items-center gap-3 px-4 py-2">
                                @if (Auth::user()->foto_perfil)
                                    <img src="{{ asset('storage/' . Auth::user()->foto_perfil) }}" alt="Perfil"
                                        class="h-12 w-12 rounded-full border-2 border-[#669BBC]">
                                @else
                                    <div class="h-12 w-12 rounded-full bg-white/10 flex items-center justify-center text-xl">👤
                                    </div>
                                @endif
                                <div>
                                    <div class="text-white font-bold">{{ Auth::user()->nombre }}</div>
                                    <div class="text-gray-400 text-xs">{{ Auth::user()->email }}</div>
                                </div>
                            </div>

                            @if (Auth::user()->tieneRol('admin') || Auth::user()->es_admin)
                                <div class="bg-white/5 rounded-2xl p-2 space-y-1">
                                    <div class="px-4 py-2 text-[10px] font-black text-[#669BBC] uppercase tracking-widest">
                                        Administración</div>
                                    <a href="{{ route('admin.usuarios.index') }}"
                                        class="block px-4 py-3 text-sm font-bold text-white hover:bg-white/5 rounded-xl">Usuarios</a>
                                    <a href="{{ route('inmuebles.index') }}"
                                        class="block px-4 py-3 text-sm font-bold text-white hover:bg-white/5 rounded-xl">Propiedades</a>
                                    <a href="{{ route('admin.resenas.index') }}"
                                        class="block px-4 py-3 text-sm font-bold text-white hover:bg-white/5 rounded-xl">Reseñas</a>
                                </div>
                            @endif

                            @if (Auth::user()->tieneRol('propietario'))
                                <a href="{{ route('inmuebles.create') }}"
                                    class="flex w-full items-center justify-center px-4 py-4 bg-[#C1121F] text-white font-black rounded-2xl shadow-lg">
                                    Publicar Propiedad
                                </a>
                            @endif

                            <button type="button" onclick="confirmLogout()"
                                class="flex w-full items-center justify-center px-4 py-4 bg-white/5 text-gray-300 font-bold rounded-2xl hover:text-white transition-all">
                                Cerrar Sesión
                            </button>
                        @else
                            <div class="grid grid-cols-2 gap-3">
                                <a href="{{ route('login') }}"
                                    class="flex items-center justify-center px-4 py-4 bg-white/5 text-white font-bold rounded-2xl border border-white/10">
                                    Entrar
                                </a>
                                <a href="{{ route('registro') }}"
                                    class="flex items-center justify-center px-4 py-4 bg-[#FDF0D5] text-[#003049] font-black rounded-2xl shadow-xl">
                                    Registrarse
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>
        <!-- Contenido Principal -->
        <main class="flex-1 w-full max-w-7xl mx-auto py-8">
            @yield('content')
        </main>

        <!-- Footer -->
        <!-- Footer Premium -->
        <!-- Footer Premium (3 Columnas) -->
        <footer class="bg-[#003049] text-white pt-16 pb-8 border-t-4 border-[#669BBC]">
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
</body>

</html>