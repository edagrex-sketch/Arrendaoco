@extends('layouts.app')

@section('title', 'Gestión de Usuarios - Admin')

@section('content')
<div class="px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-[#003049]">Gestión de Usuarios</h1>
        <div class="flex gap-2">
            <a href="{{ route('admin.usuarios.create') }}" class="bg-[#669BBC] text-white px-4 py-2 rounded-lg hover:bg-[#003049] transition-colors">
                Crear Usuario
            </a>
            <a href="{{ route('admin.usuarios.reporte') }}" class="bg-[#C1121F] text-white px-4 py-2 rounded-lg hover:bg-[#780000] transition-colors">
                Reporte PDF
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <table class="w-full">
            <thead class="bg-[#003049] text-white">
                <tr>
                    <th class="px-6 py-3 text-left">ID</th>
                    <th class="px-6 py-3 text-left">Nombre</th>
                    <th class="px-6 py-3 text-left">Email</th>
                    <th class="px-6 py-3 text-left">Roles</th>
                    <th class="px-6 py-3 text-left">Estatus</th>
                    <th class="px-6 py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($usuarios as $usuario)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $usuario->id }}</td>
                    <td class="px-6 py-4 font-medium">{{ $usuario->nombre }}</td>
                    <td class="px-6 py-4">{{ $usuario->email }}</td>
                    <td class="px-6 py-4">
                        @foreach($usuario->roles as $role)
                            <span class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2">
                                {{ $role->etiqueta ?? $role->nombre }}
                            </span>
                        @endforeach
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-full text-xs font-bold {{ $usuario->estatus === 'activo' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($usuario->estatus) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('admin.usuarios.edit', $usuario->id) }}" class="text-[#669BBC] hover:text-[#003049]">
                                Editar
                            </a>
                            <button onclick="confirmDelete({{ $usuario->id }})" class="text-[#C1121F] hover:text-[#780000]">
                                Eliminar
                            </button>
                        </div>
                        <form id="delete-form-{{ $usuario->id }}" action="{{ route('admin.usuarios.destroy', $usuario->id) }}" method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
