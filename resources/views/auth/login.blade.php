@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="min-h-screen flex items-center justify-center">
    <form method="POST" action="{{ route('login') }}" class="bg-white p-6 rounded shadow w-80">
        @csrf

        <h1 class="text-xl font-bold mb-4">Iniciar sesión</h1>

        <div class="mb-3">
            <label class="block text-sm">Correo electrónico</label>
            <input type="email" name="email" class="w-full border p-2 rounded">
        </div>

        <div class="mb-4">
            <label class="block text-sm">Contraseña</label>
            <input type="password" name="password" class="w-full border p-2 rounded">
        </div>

        <button class="w-full bg-blue-600 text-white py-2 rounded">
            Iniciar sesión
        </button>
    </form>
</div>
@endsection
