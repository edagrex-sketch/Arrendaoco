@extends('layouts.app')
@section('title', 'Nueva Contraseña')
@section('content')
    <div class="min-h-screen flex items-center justify-center relative overflow-hidden">
        <!-- Background Image with Overlay -->
        <div class="absolute inset-0 bg-cover bg-center animate-scale-in"
            style="background-image: url('{{ asset('imagen2.png') }}');"></div>
        <div class="absolute inset-0 bg-[#003049]/80 backdrop-blur-sm"></div>

        <!-- Card -->
        <div class="relative w-full max-w-md bg-white p-8 rounded-2xl shadow-2xl m-4 animate-fade-in-up">
            <div class="text-center mb-6 animate-fade-in-up delay-100">
                <h1 class="text-2xl font-bold text-[#003049]">Nueva Contraseña</h1>
                <p class="text-gray-500 text-sm mt-2">Introduce y confirma tu nueva contraseña para recuperar el acceso
                    secure.</p>
            </div>

            <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <div class="space-y-1 animate-fade-in-up delay-200">
                    <label class="block text-sm font-semibold text-[#003049] ml-1">Correo Electrónico</label>
                    <div class="relative">
                        <input type="email" name="email" value="{{ request()->email }}"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-[#003049] focus:border-transparent outline-none transition-all duration-200"
                            required autofocus placeholder="nombre@ejemplo.com">
                    </div>
                    @error('email') <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-1 animate-fade-in-up delay-300">
                    <label class="block text-sm font-semibold text-[#003049] ml-1">Nueva Contraseña</label>
                    <div class="relative">
                        <input type="password" name="password"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-[#003049] focus:border-transparent outline-none transition-all duration-200"
                            required placeholder="••••••••">
                    </div>
                    @error('password') <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-1 animate-fade-in-up delay-400">
                    <label class="block text-sm font-semibold text-[#003049] ml-1">Confirmar Contraseña</label>
                    <div class="relative">
                        <input type="password" name="password_confirmation"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-[#003049] focus:border-transparent outline-none transition-all duration-200"
                            required placeholder="••••••••">
                    </div>
                </div>

                <button
                    class="w-full bg-[#003049] hover:bg-[#002538] text-white py-3.5 rounded-lg font-bold shadow-md hover:shadow-lg transform transition-all duration-200 mt-4 animate-fade-in-up delay-500 hover-lift">
                    Cambiar Contraseña
                </button>
            </form>

            <div class="mt-8 text-center animate-fade-in-up delay-500">
                <a href="{{ route('login') }}"
                    class="inline-flex items-center text-sm text-gray-400 hover:text-[#780000] transition-colors font-medium hover-lift">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver al inicio de sesión
                </a>
            </div>
        </div>
    </div>
@endsection