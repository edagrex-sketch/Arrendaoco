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
</head>

<body class="bg-background text-foreground font-sans antialiased">

    <div class="min-h-screen flex flex-col">

        <!-- Barra de Navegación -->
        <nav class="bg-card/80 backdrop-blur-md border-b border-border sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">

                <!-- Logo -->
                <a href="{{ route('inicio') }}" class="flex items-center gap-2 group">
                    <div
                        class="h-8 w-8 bg-primary rounded-lg flex items-center justify-center text-primary-foreground font-bold text-sm">
                        AO
                    </div>
                    <span
                        class="text-xl font-bold bg-gradient-to-r from-primary to-accent bg-clip-text text-transparent group-hover:opacity-80 transition-opacity">
                        ArrendaOco
                    </span>
                </a>

                <!-- Menú -->
                <div class="flex items-center gap-4">
                    <a href="#"
                        class="text-sm font-medium text-muted-foreground hover:text-foreground transition-colors hidden sm:block">
                        Mis Rentas
                    </a>

                    <a href="#"
                        class="hidden sm:inline-flex items-center rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-primary-foreground shadow-sm hover:bg-primary/90 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                clip-rule="evenodd" />
                        </svg>
                        Publicar
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="ml-2 border-l border-border pl-4">
                        @csrf
                        <button type="submit" class="text-xs font-medium text-destructive hover:underline">
                            Salir
                        </button>
                    </form>
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
