<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ArrendaOco')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" href="{{ asset('logo1.png') }}" type="image/x-icon">
</head>
<body class="bg-[#F5F1E8] font-sans antialiased h-screen overflow-hidden">

    <div class="flex w-full h-full">
        <!-- Izquierda: Área del Formulario (Fondo Crema) -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-4 bg-[#F5F1E8]">
            <!-- Contenedor del contenido que se centrará -->
            <div class="w-full max-w-md animate-fade-in-up">
                @yield('content')
            </div>
        </div>

        <!-- Derecha: Área Visual (Fondo Azul Oscuro) -->
        <div class="hidden lg:flex w-1/2 bg-[#003049] relative items-center justify-center overflow-hidden">
            <!-- Aquí iría la ilustración del mapa. 
                 Si tienes una imagen específica (ej. map-illustration.png), úsala aquí.
                 Por ahora usaremos la imagen de referencia como fondo o un placeholder. -->
            
            <!-- Opción A: Imagen de fondo cubriendo todo -->
            <!-- <img src="{{ asset('images/auth-bg.png') }}" class="absolute inset-0 w-full h-full object-cover opacity-50 mix-blend-overlay"> -->

            <!-- Texto descriptivo en la parte inferior -->
            <div class="absolute bottom-0 left-0 p-16 text-white z-10">
                <h2 class="text-4xl font-bold mb-4">Encuentra tu hogar ideal.</h2>
                <p class="text-lg text-gray-300">Explora las mejores propiedades en ArrendaOco.<br>Tu próximo espacio te espera.</p>
                <!-- Decoración (estrella) -->
                <div class="mt-8">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="currentColor" class="text-[#8ECAE6]">
                        <path d="M12 0L14.59 9.41L24 12L14.59 14.59L12 24L9.41 14.59L0 12L9.41 9.41L12 0Z"/>
                    </svg>
                </div>
            </div>
            
            <!-- Placeholder para la ilustración del mapa si no hay imagen -->
            <!-- Puedes reemplazar esto con <img src="..." /> cuando tengas el asset del mapa -->
             <div class="absolute inset-0 opacity-20 bg-[url('/public/logo.png')] bg-center bg-no-repeat bg-contain"></div>
        </div>
    </div>

</body>
</html>
