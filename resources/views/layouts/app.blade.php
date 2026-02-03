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

</head>

<body class="bg-background text-foreground font-sans antialiased">

    <div class="min-h-screen flex flex-col">

        <!-- Barra de Navegación -->
        <nav class="bg-[#003049] border-b border-[#003049] sticky top-0 z-50 shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <!-- 1. Logo y Nombre -->
                <a href="{{ route('inicio') }}"
                    class="flex items-center gap-2 group hover:opacity-90 transition-opacity">
                    <!-- Cuadrado del logo en un azul más claro para resaltar -->
                    <img src="{{ asset('logo1.png') }}" alt="Logo ArrendaOco" class="h-10 w-auto object-contain">
                    <span class="text-xl font-bold text-white tracking-tight">
                        ArrendaOco<span class="text-[#669BBC]"></span>
                    </span>
                </a>
                <!-- 2. Menú Central (Enlaces) -->
                <div class="hidden md:flex items-center gap-8">
                    <a href="{{ route('inicio') }}"
                        class="text-sm font-medium text-white hover:text-[#669BBC] transition-colors border-b-2 border-transparent hover:border-[#669BBC] py-1">
                        Inicio
                    </a>
                    @if(Auth::check() && Auth::user()->tieneRol('propietario'))
                    <a href="{{ route('inmuebles.index') }}"
                        class="text-sm font-medium text-white hover:text-[#669BBC] transition-colors border-b-2 border-transparent hover:border-[#669BBC] py-1">
                        Mis Propiedades
                    </a>
                    @endif
                    <a href="#"
                        class="text-sm font-medium text-white hover:text-[#669BBC] transition-colors border-b-2 border-transparent hover:border-[#669BBC] py-1">
                        Nosotros
                    </a>
                </div>
                <!-- 3. Botones (Auth) -->
                <div class="flex items-center gap-4">
                    @auth
                        <!-- Usuario: Hola + Botón Publicar -->
                        <span class="text-sm text-gray-200 hidden sm:inline">
                            Hola, <a href="{{ route('perfil.index') }}" class="font-bold text-white hover:underline">{{ Auth::user()->nombre }}</a>
                        </span>

                        @if(Auth::user()->tieneRol('admin') || Auth::user()->es_admin)
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" 
                                        @click.away="open = false"
                                        class="flex items-center gap-1 text-sm font-medium text-white hover:text-[#669BBC] transition-colors border-2 border-[#669BBC] rounded px-3 py-1">
                                    Gestiones
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                
                                <div x-show="open" 
                                     x-transition:enter="transition ease-out duration-100"
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
                                </div>
                            </div>
                        @endif

                        @if(Auth::user()->tieneRol('propietario'))
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
            </div>
        </nav>
        <!-- Contenido Principal -->
        <main class="flex-1 w-full max-w-7xl mx-auto py-8">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="py-6 text-center text-xs text-muted-foreground border-t border-border mt-auto">
            &copy; {{ date('Y') }} ArrendaOco - Ocosingo, Chiapas
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
