@extends('layouts.app')
@section('title', 'Nueva Contraseña')
@section('content')
<div class="min-h-screen flex items-center justify-center">
    <div class="bg-white p-6 rounded shadow w-96">
        <h1 class="text-xl font-bold mb-4">Nueva Contraseña</h1>
        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="mb-3">
                <label class="block text-sm font-semibold mb-1">Correo Electrónico</label>
                <input type="email" name="email" value="{{ request()->email }}" class="w-full border p-2 rounded" required>
                @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div class="mb-3">
                <label class="block text-sm font-semibold mb-1">Nueva Contraseña</label>
                <input type="password" name="password" class="w-full border p-2 rounded" required>
                @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-1">Confirmar Contraseña</label>
                <input type="password" name="password_confirmation" class="w-full border p-2 rounded" required>
            </div>
            <button class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700 transition">
                Cambiar Contraseña
            </button>
        </form>
    </div>
</div>
@endsection