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
                <a href="{{ route('inicio') }}" class="flex items-center gap-2 group hover:opacity-90 transition-opacity">
                    <!-- Cuadrado del logo en un azul más claro para resaltar -->
                    <img src="{{ asset('logo1.png') }}" alt="Logo ArrendaOco" class="h-10 w-auto object-contain">
                    <span class="text-xl font-bold text-white tracking-tight">
                        ArrendaOco<span class="text-[#669BBC]"></span>
                    </span>
                </a>
                <!-- 2. Menú Central (Enlaces) -->
                <div class="hidden md:flex items-center gap-8">
                    <a href="{{ route('inicio') }}" class="text-sm font-medium text-white hover:text-[#669BBC] transition-colors border-b-2 border-transparent hover:border-[#669BBC] py-1">
                        Inicio
                    </a>
                    <a href="#" class="text-sm font-medium text-white hover:text-[#669BBC] transition-colors border-b-2 border-transparent hover:border-[#669BBC] py-1">
                        Nosotros
                    </a>
                    <a href="#" class="text-sm font-medium text-white hover:text-[#669BBC] transition-colors border-b-2 border-transparent hover:border-[#669BBC] py-1">
                        Propiedades
                    </a>
                </div>
                <!-- 3. Botones (Auth) -->
                <div class="flex items-center gap-4">
                    @auth
                        <!-- Usuario: Hola + Botón Publicar -->
                        <span class="text-sm text-gray-200 hidden sm:inline">
                            Hola, <span class="font-bold text-white">{{ Auth::user()->nombre }}</span>
                        </span>
                        
                        <a href="{{ route('inmuebles.create') }}" class="inline-flex items-center justify-center rounded-full bg-[#C1121F] px-5 py-2 text-sm font-bold text-white shadow-md hover:bg-[#780000] transition-colors">
                            Publicar
                        </a>
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm font-medium text-gray-300 hover:text-white hover:underline transition-colors">
                                Salir
                            </button>
                        </form>
                    @else
                        <!-- Invitado: Login + Registro -->
                        <a href="{{ route('login') }}" class="text-sm font-medium text-white hover:text-[#669BBC] transition-colors">
                            Iniciar Sesión
                        </a>
                        
                        <a href="{{ route('registro') }}" class="inline-flex items-center justify-center rounded-lg bg-[#FDF0D5] px-5 py-2 text-sm font-bold text-[#003049] shadow hover:bg-white transition-transform active:scale-95">
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

</body>

</html>
