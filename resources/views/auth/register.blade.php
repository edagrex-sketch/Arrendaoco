@extends('layouts.app')
@section('title', 'Registro')
@section('content')
<div class="min-h-screen flex items-center justify-center">
    <form method="POST" action="{{ route('registro.post') }}" class="bg-white p-6 rounded shadow-lg w-96 border border-gray-100">
        @csrf
        <h1 class="text-xl font-bold mb-4 text-[#003049]">Crear cuenta</h1>
        <!-- Mensajes de error generales -->
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm">
                <ul class="list-disc pl-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="mb-3">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Nombre Completo</label>
            <input type="text" name="nombre" value="{{ old('nombre') }}" class="w-full border p-2 rounded focus:ring-2 focus:ring-[#003049] focus:outline-none transition" required>
        </div>
        <div class="mb-3">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Correo Electrónico</label>
            <input type="email" name="email" value="{{ old('email') }}" class="w-full border p-2 rounded focus:ring-2 focus:ring-[#003049] focus:outline-none transition" required>
        </div>
        <div class="mb-3">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Contraseña</label>
            <input type="password" name="password" class="w-full border p-2 rounded focus:ring-2 focus:ring-[#003049] focus:outline-none transition" required>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Confirmar Contraseña</label>
            <input type="password" name="password_confirmation" class="w-full border p-2 rounded focus:ring-2 focus:ring-[#003049] focus:outline-none transition" required>
        </div>
        <!-- Botón: Color #003049 -->
        <button class="w-full bg-[#003049] hover:opacity-90 text-white py-2 rounded font-bold shadow transition-all">
            Registrarse
        </button>
        <!-- Enlace Login: Color #780000 -->
        <div class="mt-4 text-center text-sm">
            <a href="{{ route('login') }}" class="text-[#780000] hover:underline font-bold">
                ¿Ya tienes cuenta? <span class="">Inicia sesión</span>
            </a>
        </div>
    </form>
</div>
@endsection