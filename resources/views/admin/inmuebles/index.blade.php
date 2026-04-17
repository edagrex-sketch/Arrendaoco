@extends('layouts.admin')

@section('title', 'Gestión de Inmuebles - Admin')
@section('page-title', 'Gestión de Propiedades')
@section('page-subtitle', 'Administra todas las propiedades publicadas en la plataforma')

@section('content')
<div class="px-4">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div class="flex gap-2">
            <a href="{{ route('inmuebles.create') }}" class="bg-[#669BBC] text-white px-5 py-2 rounded-xl font-bold hover:bg-[#003049] transition-all shadow-md shadow-blue-100 flex items-center gap-2 text-sm sm:text-base">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nueva Propiedad
            </a>
            <a href="{{ route('admin.inmuebles.reporte', request()->all()) }}" class="bg-[#C1121F] text-white px-5 py-2 rounded-xl font-bold hover:bg-[#780000] transition-all shadow-md shadow-red-100 flex items-center gap-2 text-sm sm:text-base">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                PDF Filtrado
            </a>
        </div>
    </div>

    {{-- Buscador y Filtros Avanzados --}}
    <div class="bg-white rounded-3xl shadow-md p-6 mb-8 border border-slate-100">
        <form action="{{ route('admin.inmuebles.index') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                {{-- Búsqueda --}}
                <div class="lg:col-span-2 relative">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 ml-1">Búsqueda rápida</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                        placeholder="Título, ubicación o dueño..." 
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-4 focus:ring-[#669BBC]/10 focus:border-[#669BBC] outline-none transition-all text-sm font-medium">
                    <div class="absolute left-3 top-[34px] text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                {{-- Tipo --}}
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 ml-1">Tipo de Propiedad</label>
                    <select name="tipo" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-4 focus:ring-[#669BBC]/10 focus:border-[#669BBC] outline-none transition-all text-sm font-bold text-[#003049]">
                        <option value="">Todos</option>
                        <option value="casa" {{ request('tipo') == 'casa' ? 'selected' : '' }}>Casa</option>
                        <option value="departamento" {{ request('tipo') == 'departamento' ? 'selected' : '' }}>Departamento</option>
                        <option value="cuarto" {{ request('tipo') == 'cuarto' ? 'selected' : '' }}>Cuarto</option>
                    </select>
                </div>

                {{-- Estatus --}}
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 ml-1">Estatus</label>
                    <select name="estatus" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-4 focus:ring-[#669BBC]/10 focus:border-[#669BBC] outline-none transition-all text-sm font-bold text-[#003049]">
                        <option value="">Cualquiera</option>
                        <option value="disponible" {{ request('estatus') == 'disponible' ? 'selected' : '' }}>Disponible</option>
                        <option value="rentado" {{ request('estatus') == 'rentado' ? 'selected' : '' }}>Rentado</option>
                    </select>
                </div>

                {{-- Botones --}}
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 bg-[#003049] text-white py-2.5 rounded-xl font-bold hover:bg-[#001d2e] transition-all shadow-md shadow-blue-900/10 flex items-center justify-center gap-2 text-sm">
                        Filtrar
                    </button>
                    @if(request()->anyFilled(['search', 'tipo', 'estatus', 'precio_min', 'precio_max', 'desde', 'hasta']))
                        <a href="{{ route('admin.inmuebles.index') }}" class="p-2.5 bg-slate-100 text-slate-500 rounded-xl hover:bg-slate-200 transition-all shadow-sm" title="Limpiar filtros">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                    @endif
                </div>
            </div>

            {{-- Filtros Secundarios (Precio y Fecha) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mt-4 pt-4 border-t border-slate-50">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 ml-1">Precio Min.</label>
                    <input type="number" name="precio_min" value="{{ request('precio_min') }}" placeholder="0" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-4 focus:ring-[#669BBC]/10 focus:border-[#669BBC] outline-none transition-all text-sm font-medium">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 ml-1">Precio Max.</label>
                    <input type="number" name="precio_max" value="{{ request('precio_max') }}" placeholder="99999" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-4 focus:ring-[#669BBC]/10 focus:border-[#669BBC] outline-none transition-all text-sm font-medium">
                </div>
                <div class="lg:col-span-1">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 ml-1">Fecha desde</label>
                    <input type="date" name="desde" value="{{ request('desde') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-4 focus:ring-[#669BBC]/10 focus:border-[#669BBC] outline-none transition-all text-sm font-medium">
                </div>
                <div class="lg:col-span-1">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 ml-1">Fecha hasta</label>
                    <input type="date" name="hasta" value="{{ request('hasta') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-4 focus:ring-[#669BBC]/10 focus:border-[#669BBC] outline-none transition-all text-sm font-medium">
                </div>
            </div>
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
                        <span class="px-2 py-1 rounded-full text-xs font-bold {{ $inmueble->estatus === 'disponible' ? 'bg-[#669BBC]/20 text-[#003049]' : 'bg-[#C1121F]/20 text-[#C1121F]' }}">
                            {{ ucfirst($inmueble->estatus) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('inmuebles.edit', $inmueble->id) }}" class="text-[#669BBC] hover:text-[#003049]">
                                Editar
                            </a>
                            @if($inmueble->estatus !== 'rentado')
                                <button onclick="confirmDelete({{ $inmueble->id }})" class="text-[#C1121F] hover:text-[#780000]">
                                    Eliminar
                                </button>
                            @else
                                <button disabled class="text-gray-400 cursor-not-allowed" title="No se puede eliminar un inmueble rentado">
                                    Eliminar
                                </button>
                            @endif
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
