@extends('layouts.app')

@section('title', 'Gestión de Inmuebles - Admin')

@section('content')
<div class="px-4">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <h1 class="text-3xl font-bold text-[#003049]">Gestión de Propiedades</h1>
        <div class="flex gap-2">
            <a href="{{ route('inmuebles.create') }}" class="bg-[#669BBC] text-white px-5 py-2 rounded-xl font-bold hover:bg-[#003049] transition-all shadow-md shadow-blue-100 flex items-center gap-2 text-sm sm:text-base">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nueva Propiedad
            </a>
            <a href="{{ route('admin.inmuebles.reporte') }}" class="bg-[#C1121F] text-white px-5 py-2 rounded-xl font-bold hover:bg-[#780000] transition-all shadow-md shadow-red-100 flex items-center gap-2 text-sm sm:text-base">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                PDF
            </a>
        </div>
    </div>

    {{-- Buscador Premium Celeste --}}
    <div class="mb-6">
        <form action="{{ route('inmuebles.index') }}" method="GET" class="relative w-full">
            <input type="text" name="search" value="{{ request('search') }}" 
                placeholder="Buscar por título, ubicación, tipo o propietario..." 
                class="w-full pl-12 pr-12 py-3 rounded-2xl border-none bg-[#669BBC] focus:ring-4 focus:ring-[#669BBC]/20 outline-none transition-all shadow-md text-lg placeholder:text-white/70 text-white font-medium">
            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-white/90">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            @if(request('search'))
                <a href="{{ route('inmuebles.index') }}" class="absolute right-4 top-1/2 -translate-y-1/2 text-white/70 hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </a>
            @endif
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <table class="w-full">
            <thead class="bg-[#003049] text-white">
                <tr>
                    <th class="px-6 py-4 text-left font-bold uppercase tracking-wider text-sm">Título</th>
                    <th class="px-6 py-4 text-left font-bold uppercase tracking-wider text-sm">Tipo</th>
                    <th class="px-6 py-4 text-left font-bold uppercase tracking-wider text-sm">Precio</th>
                    <th class="px-6 py-4 text-left font-bold uppercase tracking-wider text-sm">Propietario</th>
                    <th class="px-6 py-4 text-left font-bold uppercase tracking-wider text-sm">Estatus</th>
                    <th class="px-6 py-4 text-center font-bold uppercase tracking-wider text-sm">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($inmuebles as $inmueble)
                <tr class="hover:bg-slate-50 transition-colors">
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

        {{-- Paginación --}}
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
            {{ $inmuebles->links() }}
            @if($inmuebles->total() > 0)
                <p class="text-xs text-gray-400 mt-2 text-center">
                    Mostrando {{ $inmuebles->firstItem() }} a {{ $inmuebles->lastItem() }} de {{ $inmuebles->total() }} propiedades
                </p>
            @endif
        </div>
    </div>
</div>
@endsection
