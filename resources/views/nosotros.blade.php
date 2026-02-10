@extends('layouts.app')

@section('title', 'Nosotros | ArrendaOco')

@section('content')
    {{-- Hero Section --}}
    <section class="relative bg-[#003049] text-white py-20 px-4 sm:px-6 lg:px-8 rounded-3xl mx-4 my-6 overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <path d="M0 100 L100 0 L100 100 Z" fill="#669BBC" />
            </svg>
        </div>
        <div class="relative z-10 max-w-4xl mx-auto text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-6 tracking-tight">
                Conectando Ocosingo con su <span class="text-[#669BBC]">Hogar Ideal</span>
            </h1>
            <p class="text-lg md:text-xl text-gray-300 max-w-2xl mx-auto leading-relaxed">
                Somos la plataforma que transforma la experiencia de rentar en Ocosingo.
                Sin complicaciones, segura y diseñada para estudiantes y familias.
            </p>
        </div>
    </section>

    {{-- Nuestra Misión --}}
    <section class="py-16 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            <div class="relative">
                <div class="absolute -inset-4 bg-[#669BBC]/20 rounded-2xl blur-lg transform -rotate-3"></div>
                <div class="relative bg-white p-8 rounded-2xl shadow-xl border border-gray-100 space-y-6">
                    <div
                        class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#FDF0D5]/30 text-[#003049] font-medium text-sm">
                        <span class="w-2 h-2 rounded-full bg-[#003049]"></span>
                        Nuestra Misión
                    </div>
                    <h2 class="text-3xl font-bold text-[#003049]">Simplificar la búsqueda de tu próximo espacio</h2>
                    <p class="text-gray-600 text-lg leading-relaxed">
                        Sabemos lo difícil que puede ser encontrar un lugar seguro y adecuado en Ocosingo, especialmente
                        para
                        estudiantes foráneos. ArrendaOco nació con el propósito de centralizar la oferta inmobiliaria,
                        brindando
                        confianza tanto a propietarios como a inquilinos.
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex-shrink-0 w-10 h-10 rounded-full bg-[#E0F2FE] flex items-center justify-center text-[#0284C7]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <span class="font-medium text-gray-700">Verificado</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div
                                class="flex-shrink-0 w-10 h-10 rounded-full bg-[#E0F2FE] flex items-center justify-center text-[#0284C7]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <span class="font-medium text-gray-700">Rápido</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="relative">
                <div class="absolute -inset-4 bg-[#669BBC]/20 rounded-2xl blur-lg transform rotate-3"></div>
                <div class="relative bg-white p-8 rounded-2xl shadow-xl border border-gray-100">
                    <div
                        class="aspect-w-16 aspect-h-9 bg-gray-50 rounded-lg mb-6 flex items-center justify-center overflow-hidden p-8">
                        <img src="{{ asset('dc5.png') }}" alt="Logo Equipo DC5" class="w-full h-full object-contain">
                    </div>
                    <div class="flex justify-center mb-4">
                        <div
                            class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#FDF0D5]/30 text-[#003049] font-medium text-sm">
                            <span class="w-2 h-2 rounded-full bg-[#003049]"></span>
                            Nuestra Visión
                        </div>
                    </div>
                    <blockquote class="text-center">
                        <p class="text-lg italic text-gray-700 mb-4">"Nuestro compromiso es crear una comunidad donde
                            encontrar hogar sea tan fácil como dar un clic."</p>
                        <footer class="font-bold text-[#003049]">- Equipo DC5</footer>
                    </blockquote>
                </div>
            </div>
        </div>
    </section>

    {{-- Valores / Por qué elegirnos --}}
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-[#003049] mb-4">¿Por qué elegir ArrendaOco?</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Más que una plataforma, somos tu aliado en Ocosingo.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Valor 1 --}}
                <div
                    class="bg-white p-8 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow group">
                    <div
                        class="w-14 h-14 bg-[#003049] rounded-lg flex items-center justify-center text-white mb-6 group-hover:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Seguridad Primero</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Fomentamos un entorno seguro verificando usuarios y propiedades para evitar fraudes y malas
                        experiencias.
                    </p>
                </div>

                {{-- Valor 2 --}}
                <div
                    class="bg-white p-8 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow group">
                    <div
                        class="w-14 h-14 bg-[#669BBC] rounded-lg flex items-center justify-center text-white mb-6 group-hover:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Búsqueda Inteligente</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Filtros avanzados por precio, ubicación y tipo de inmueble para que encuentres exactamente lo que
                        necesitas.
                    </p>
                </div>

                {{-- Valor 3 --}}
                <div
                    class="bg-white p-8 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow group">
                    <div
                        class="w-14 h-14 bg-[#C1121F] rounded-lg flex items-center justify-center text-white mb-6 group-hover:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Comunidad Local</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Enfocados 100% en Ocosingo, entendemos las necesidades específicas de la zona y su gente.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Equipo Desarrollador --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-[#003049] mb-4">Equipo de Desarrollo</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Mentes creativas detrás de la plataforma.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 justify-center">

                {{-- Miembro 1 --}}
                <div
                    class="group relative bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="h-32 bg-gradient-to-r from-[#003049] to-[#669BBC]"></div>
                    <div class="px-6 pb-6 relative">
                        <div class="relative -mt-16 mb-4">
                                <div
                                    class=" w-32 h-32 mx-auto rounded-full border-4 border-white bg-gray-100
                            overflow-hidden shadow-md flex items-center justify-center">
                            <img src="{{ asset('eduardo.png') }}" alt="Eduardo Aguilar Reyes"
                                class="w-full h-full object-cover">
                        </div>
                    </div>
                    <div class="text-center">
                        <h3 class="text-xl font-bold text-[#003049] mb-1">Eduardo Aguilar Reyes</h3>
                        <p class="text-[#669BBC] font-medium text-sm mb-4">Full Stack Developer</p>
                        <p class="text-gray-500 text-sm italic mb-6">“Transformo ideas en software que impulsa sueños,
                            negocios y nuevas posibilidades.”</p>
                    </div>
                </div>
            </div>
            {{-- Miembro 2 --}}
            <div
                class="group relative bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="h-32 bg-gradient-to-r from-[#003049] to-[#669BBC]"></div>
                <div class="px-6 pb-6 relative">
                    <div class="relative -mt-16 mb-4">
                                <div
                                    class=" w-32 h-32 mx-auto rounded-full border-4 border-white bg-gray-100
                        overflow-hidden shadow-md flex items-center justify-center">
                        <img src="{{ asset('neyser.png') }}" alt="Neyser Manuel Estrada Pérez"
                            class="w-full h-full object-cover">
                    </div>
                </div>
                <div class="text-center">
                    <h3 class="text-xl font-bold text-[#003049] mb-1">Neyser Manuel Estrada Pérez</h3>
                    <p class="text-[#669BBC] font-medium text-sm mb-4">Full Stack Developer</p>
                    <p class="text-gray-500 text-sm italic mb-6">"Uso la tecnología para contar historias y resolver
                        problemas reales. Programo con atención al detalle para crear experiencias que valgan la
                        pena."</p>
                </div>
            </div>
        </div>
        {{-- Miembro 3 --}}
        <div
            class="group relative bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="h-32 bg-gradient-to-r from-[#003049] to-[#669BBC]"></div>
            <div class="px-6 pb-6 relative">
                <div class="relative -mt-16 mb-4">
                                <div
                                    class=" w-32 h-32 mx-auto rounded-full border-4 border-white bg-gray-100
                    overflow-hidden shadow-md flex items-center justify-center">
                    <img src="{{ asset('hannia.png') }}" alt="Hannia Lysset Gutiérrez López"
                        class="w-full h-full object-cover">
                </div>
            </div>
            <div class="text-center">
                <h3 class="text-xl font-bold text-[#003049] mb-1">Hannia Lysset Gutiérrez López</h3>
                <p class="text-[#669BBC] font-medium text-sm mb-4">Full Stack Developer</p>
                <p class="text-gray-500 text-sm italic mb-6">"Mi enfoque está en el desarrollo de software que
                    simplifique la
                    vida de las personas."</p>
            </div>
        </div>
        </div>
        {{-- Miembro 4 --}}
        <div
            class="group relative bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="h-32 bg-gradient-to-r from-[#003049] to-[#669BBC]"></div>
            <div class="px-6 pb-6 relative">
                <div class="relative -mt-16 mb-4">
                                <div
                                    class=" w-32 h-32 mx-auto rounded-full border-4 border-white bg-gray-100
                    overflow-hidden shadow-md flex items-center justify-center">
                    <img src="{{ asset('fatima.png') }}" alt="Fárima Marroquin Rentería" class="w-full h-full object-cover">
                </div>
            </div>
            <div class="text-center">
                <h3 class="text-xl font-bold text-[#003049] mb-1">Fárima Marroquin Rentería</h3>
                <p class="text-[#669BBC] font-medium text-sm mb-4">Full Stack Developer</p>
                <p class="text-gray-500 text-sm italic mb-6">"Si existe un problema, existe una solución
                    digital. Mi trabajo es construirla de la forma más elegante posible."</p>
            </div>
        </div>
        </div>
        </div>
        </div>
    </section>

    {{-- CTA Final --}}
    <section class="py-16 px-4">
        <div class="max-w-5xl mx-auto bg-[#003049] rounded-3xl p-12 text-center text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 p-12 opacity-5 translate-x-1/3 -translate-y-1/3">
                <svg width="400" height="400" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2L2 12h3v8h6v-6h2v6h6v-8h3L12 2z" />
                </svg>
            </div>
            <div class="relative z-10">
                <h2 class="text-3xl font-bold mb-6">¿Listo para encontrar tu lugar?</h2>
                <p class="text-blue-100 mb-8 text-lg max-w-2xl mx-auto">Únete a cientos de estudiantes y familias que ya
                    usan ArrendaOco.</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('welcome') }}"
                            class="inline-flex items-center justify-center px-8 py-3 bg-[#669BBC] hover:bg-[#5582a0] text-white font-bold rounded-xl transition-colors">
                        Buscar Propiedades
                    </a>
                    @guest
                        <a href="{{ route('registro') }}"
                                    class="inline-flex items-center justify-center px-8 py-3 bg-white text-[#003049] hover:bg-gray-100 font-bold rounded-xl transition-colors">
                            Registrarse
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </section>
@endsection