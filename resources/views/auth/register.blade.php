@extends('layouts.app')
@section('title', 'Registro')
@section('content')
    <div class="min-h-screen flex items-center justify-center bg-[#F5F1E8] p-4 text-left">
        <!-- Card Container -->
        <div class="w-full max-w-5xl flex bg-white rounded-2xl shadow-2xl overflow-hidden">

            <!-- Left Side - Image -->
        <div class="hidden lg:flex w-1/2 overflow-hidden relative">
            <div class="absolute inset-0 bg-cover bg-center animate-scale-in" style="background-image: url('{{ asset('imagen2.png') }}');"></div>
            <div class="absolute inset-0 bg-[#003049]/40 backdrop-brightness-75 flex flex-col items-center justify-center z-10 p-8 text-center">
                <h2 class="text-4xl font-bold text-white mb-4 drop-shadow-lg animate-fade-in-up delay-200">Únete a ArrendaOco</h2>
                <p class="text-white/90 text-lg font-medium drop-shadow-md animate-fade-in-up delay-300 max-w-xs">Comienza tu búsqueda hoy mismo.</p>
            </div>
        </div>
        
        <!-- Right Side - Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-12">
            <div class="w-full max-w-md">
                <!-- Header -->
                <div class="mb-8 text-center sm:text-left animate-fade-in-up">
                    <h1 class="text-3xl font-bold text-[#003049] mb-2">Crear nueva cuenta</h1>
                    <p class="text-gray-500">Únete a nosotros completando tus datos.</p>
                </div>

                <form method="POST" action="{{ route('registro.post') }}" class="space-y-4" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Mensajes de error generales -->
                    @if ($errors->any())
                        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r shadow-sm mb-6 animate-fade-in-up">
                            <ul class="list-disc pl-4 space-y-1 text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="space-y-1 animate-fade-in-up delay-100">
                        <label class="block text-sm font-semibold text-[#003049]">Nombre Completo</label>
                        <input type="text" name="nombre" value="{{ old('nombre') }}" 
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-[#003049] focus:border-transparent outline-none transition-all duration-200" 
                            required placeholder="Tu nombre">
                    </div>

                    <div class="space-y-1 animate-fade-in-up delay-150">
                        <label class="block text-sm font-semibold text-[#003049]">Foto de Perfil <span class="text-gray-400 font-normal">(Opcional)</span></label>
                        <input type="file" name="foto_perfil" accept="image/*"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-[#003049] focus:border-transparent outline-none transition-all duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#003049]/10 file:text-[#003049] hover:file:bg-[#003049]/20">
                    </div>

                    <div class="space-y-1 animate-fade-in-up delay-200">
                        <label class="block text-sm font-semibold text-[#003049]">Correo Electrónico</label>
                        <input type="email" name="email" value="{{ old('email') }}" 
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-[#003049] focus:border-transparent outline-none transition-all duration-200" 
                            required placeholder="nombre@ejemplo.com">
                    </div>

                    <div class="space-y-1 animate-fade-in-up delay-300">
                        <label class="block text-sm font-semibold text-[#003049]">Contraseña</label>
                        <input type="password" name="password" 
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-[#003049] focus:border-transparent outline-none transition-all duration-200" 
                            required placeholder="••••••••">
                    </div>

                    <div class="space-y-1 animate-fade-in-up delay-400">
                        <label class="block text-sm font-semibold text-[#003049]">Confirmar Contraseña</label>
                        <input type="password" name="password_confirmation" 
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-[#003049] focus:border-transparent outline-none transition-all duration-200" 
                            required placeholder="••••••••">
                    </div>

                    <!-- Botón -->
                    <button class="w-full bg-[#003049] hover:bg-[#002538] text-white py-3.5 rounded-lg font-bold shadow-md transform transition-all duration-200 mt-2 animate-fade-in-up delay-500 hover-lift">
                        Registrarse
                    </button>
                    
                    <div class="mt-6 text-center text-sm animate-fade-in-up delay-500">
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-[#780000] font-medium transition-colors">
                            ¿Ya tienes cuenta? <span class="text-[#780000] font-bold uppercase">Iniciar sesión</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>
        </div>
    </div>
@endsection