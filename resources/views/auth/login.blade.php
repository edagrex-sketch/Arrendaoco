@extends('layouts.app')
@section('title', 'Login')
@section('content')
<div class="min-h-screen flex items-center justify-center">
    <form method="POST" action="{{ route('login') }}" class="bg-white p-6 rounded shadow-lg w-80 border border-gray-100">
        @csrf
        <h1 class="text-xl font-bold mb-4 text-[#003049]">Iniciar sesión</h1>
        <!-- Mensajes de error -->
        @error('email')
            <div class="bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded relative mb-3 text-xs">
                {{ $message }}
            </div>
        @enderror
        <div class="mb-3">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Correo electrónico</label>
            <input type="email" name="email" value="{{ old('email') }}" class="w-full border p-2 rounded focus:ring-2 focus:ring-[#003049] focus:outline-none transition">
        </div>
        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Contraseña</label>
            <input type="password" name="password" class="w-full border p-2 rounded focus:ring-2 focus:ring-[#003049] focus:outline-none transition">
        </div>
        <!-- Botón: Color #003049 -->
        <button class="w-full bg-[#003049] hover:opacity-90 text-white py-2 rounded font-bold shadow transition-all">
            Iniciar sesión
        </button>
        
        <!-- Recuperar Contraseña: Color #c1121f -->
        <div class="flex justify-end mt-3 mb-4">
            <a href="{{ route('password.request') }}" class="text-xs text-[#c1121f] hover:underline font-medium">
                ¿Olvidaste tu contraseña?
            </a>
        </div>
        <!-- Registro: Color #780000 -->
        <div class="mt-4 text-center text-sm">
            <a href="{{ route('registro') }}" class="text-[#780000] hover:underline font-bold">
                ¿No tienes cuenta? <span class="">Regístrate</span>
            </a>
        </div>
    </form>
</div>
@endsection
