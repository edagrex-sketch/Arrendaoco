@extends('layouts.app')
@section('title', 'Recuperar Contraseña')
@section('content')
<div class="min-h-screen flex items-center justify-center">
    <div class="bg-white p-6 rounded shadow w-96">
        <h1 class="text-xl font-bold mb-4">Recuperar Contraseña</h1>
        @if (session('status'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-sm">
                {{ session('status') }}
            </div>
        @endif
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">Correo Electrónico</label>
                <input type="email" name="email" class="w-full border p-2 rounded" required autofocus>
                @error('email')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>
            <button class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
                Enviar enlace de recuperación
            </button>
        </form>
    </div>
</div>
@endsection