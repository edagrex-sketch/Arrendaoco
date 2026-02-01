@extends('layouts.app')
@section('title', 'Login')
@section('content')
    <div class="min-h-screen flex items-center justify-center bg-[#F5F1E8] p-4">
        <!-- Card Container -->
        <div class="w-full max-w-5xl flex bg-white rounded-2xl shadow-2xl overflow-hidden">

            <!-- Left Side - Image -->
            <div class="hidden lg:flex w-1/2 overflow-hidden relative">
                <div class="absolute inset-0 bg-cover bg-center animate-scale-in"
                    style="background-image: url('{{ asset('imagen2.png') }}');"></div>
                <div
                    class="absolute inset-0 bg-[#003049]/40 backdrop-brightness-75 flex flex-col items-center justify-center z-10 p-8 text-center">
                    <h2 class="text-4xl font-bold text-white mb-4 drop-shadow-lg animate-fade-in-up delay-200">Más que una casa, encuentra tu hogar
                    </h2>
                    <p class="text-white/90 text-lg font-medium drop-shadow-md animate-fade-in-up delay-300 max-w-xs">
                        Rentar nunca fue tan fácil</p>
                </div>
            </div>

            <!-- Right Side - Form -->
            <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-12">
                <div class="w-full max-w-md">
                    <!-- Header -->
                    <div class="mb-8 text-center sm:text-left animate-fade-in-up">
                        <h1 class="text-3xl font-bold text-[#003049] mb-2">¡Bienvenido de nuevo!</h1>
                        <p class="text-gray-500">Ingresa tus datos para acceder a tu cuenta.</p>
                    </div>

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        <!-- Mensajes de error -->
                        @error('email')
                            <div
                                class="bg-red-50 border-l-4 border-red-500 text-red-700 p-3 text-sm rounded-r shadow-sm animate-fade-in-up">
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="space-y-1 animate-fade-in-up delay-100">
                            <label class="block text-sm font-semibold text-[#003049]">Correo electrónico</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-[#003049] focus:border-transparent outline-none transition-all duration-200"
                                placeholder="nombre@ejemplo.com">
                        </div>

                        <div class="space-y-1 animate-fade-in-up delay-200">
                            <label class="block text-sm font-semibold text-[#003049]">Contraseña</label>
                            <input type="password" name="password"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-[#003049] focus:border-transparent outline-none transition-all duration-200"
                                placeholder="••••••••">
                        </div>

                        <!-- Botón -->
                        <button
                            class="w-full bg-[#003049] hover:bg-[#002538] text-white py-3.5 rounded-lg font-bold shadow-md transform transition-all duration-200 animate-fade-in-up delay-300 hover-lift">
                            Iniciar sesión
                        </button>

                        <div class="flex items-center justify-between mt-6 text-sm animate-fade-in-up delay-400">
                            <a href="{{ route('registro') }}"
                                class="text-gray-600 hover:text-[#780000] font-medium transition-colors">
                                ¿No tienes cuenta? <span class="text-[#780000] font-bold">Regístrate</span>
                            </a>

                            <a href="{{ route('password.request') }}"
                                class="text-[#c1121f] hover:text-[#9b0f19] font-semibold transition-colors">
                                ¿Olvidaste tu contraseña?
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection