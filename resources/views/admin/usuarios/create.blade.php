@extends('layouts.app')

@section('title', 'Crear Usuario - Admin')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-2xl font-bold text-[#003049] mb-6">Crear Nuevo Usuario</h1>

        <form action="{{ route('admin.usuarios.store') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label for="nombre" class="block text-gray-700 font-bold mb-2">Nombre</label>
                <input type="text" name="nombre" id="nombre" class="w-full border-gray-300 rounded-lg focus:ring-[#003049] focus:border-[#003049]" value="{{ old('nombre') }}" required>
            </div>

            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
                <input type="email" name="email" id="email" class="w-full border-gray-300 rounded-lg focus:ring-[#003049] focus:border-[#003049]" value="{{ old('email') }}" required>
            </div>



            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-bold mb-2">Contraseña</label>
                <input type="password" name="password" id="password" class="w-full border-gray-300 rounded-lg focus:ring-[#003049] focus:border-[#003049]" required>
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="block text-gray-700 font-bold mb-2">Confirmar Contraseña</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="w-full border-gray-300 rounded-lg focus:ring-[#003049] focus:border-[#003049]" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Roles</label>
                <div class="flex gap-4">
                    @foreach($roles as $role)
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="roles[]" value="{{ $role->id }}" class="form-checkbox h-5 w-5 text-[#003049]">
                        <span class="ml-2 text-gray-700">{{ $role->etiqueta }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="mb-4">
                <label for="status" class="block text-gray-700 font-bold mb-2">Estatus</label>
                <select name="status" id="status" class="w-full border-gray-300 rounded-lg focus:ring-[#003049] focus:border-[#003049]">
                    <option value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
                </select>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <a href="{{ route('admin.usuarios.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="px-4 py-2 bg-[#003049] text-white rounded-lg hover:bg-[#002030]">Guardar Usuario</button>
            </div>
        </form>
    </div>
</div>
@endsection
