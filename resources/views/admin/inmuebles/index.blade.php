@extends('layouts.app')

@section('title', 'Gestión de Inmuebles - Admin')

@section('content')
<div class="px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-[#003049]">Gestión de Propiedades</h1>
        <div class="flex gap-2">
            <a href="{{ route('inmuebles.create') }}" class="bg-[#669BBC] text-white px-4 py-2 rounded-lg hover:bg-[#003049] transition-colors">
                Crear Propiedad
            </a>
            <a href="{{ route('admin.inmuebles.reporte') }}" class="bg-[#C1121F] text-white px-4 py-2 rounded-lg hover:bg-[#780000] transition-colors">
                Reporte PDF
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <table class="w-full">
            <thead class="bg-[#003049] text-white">
                <tr>
                    <th class="px-6 py-3 text-left">ID</th>
                    <th class="px-6 py-3 text-left">Título</th>
                    <th class="px-6 py-3 text-left">Tipo</th>
                    <th class="px-6 py-3 text-left">Precio</th>
                    <th class="px-6 py-3 text-left">Propietario</th>
                    <th class="px-6 py-3 text-left">Estatus</th>
                    <th class="px-6 py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($inmuebles as $inmueble)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $inmueble->id }}</td>
                    <td class="px-6 py-4 font-medium">{{ $inmueble->titulo }}</td>
                    <td class="px-6 py-4">{{ ucfirst($inmueble->tipo) }}</td>
                    <td class="px-6 py-4">${{ number_format($inmueble->renta_mensual) }}</td>
                    <td class="px-6 py-4">
                        {{ $inmueble->propietario->nombre ?? 'N/A' }} <br>
                        <span class="text-xs text-gray-400">{{ $inmueble->propietario->email ?? '' }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-full text-xs font-bold {{ $inmueble->estatus === 'disponible' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($inmueble->estatus) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('inmuebles.edit', $inmueble->id) }}" class="text-[#669BBC] hover:text-[#003049]">
                                Editar
                            </a>
                            <button onclick="confirmDelete({{ $inmueble->id }})" class="text-[#C1121F] hover:text-[#780000]">
                                Eliminar
                            </button>
                        </div>
                        <form id="delete-form-{{ $inmueble->id }}" action="{{ route('inmuebles.destroy', $inmueble->id) }}" method="POST" class="hidden">
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
