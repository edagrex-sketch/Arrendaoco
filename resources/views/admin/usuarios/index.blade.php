@extends('layouts.admin')

@section('title', 'Gestión de Usuarios - Admin')
@section('page-title', 'Gestión de Usuarios')
@section('page-subtitle', 'Administra los usuarios registrados en la plataforma')

@section('content')
<div>
    {{-- Header Row --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <p class="text-sm text-slate-400">{{ $usuarios->total() }} usuario(s) registrado(s) en la plataforma</p>

        <div class="flex gap-2">
            <a href="{{ route('admin.usuarios.create') }}" class="bg-[#669BBC] text-white px-5 py-2 rounded-xl font-bold hover:bg-[#003049] transition-all shadow-md shadow-blue-100 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Crear Usuario
            </a>
            <a href="{{ route('admin.usuarios.reporte', request()->all()) }}" class="bg-[#C1121F] text-white px-5 py-2 rounded-xl font-bold hover:bg-[#780000] transition-all shadow-md shadow-red-100 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                PDF Filtrado
            </a>
        </div>
    </div>

    {{-- Buscador y Filtros Avanzados --}}
    <div class="bg-white rounded-3xl shadow-md p-6 mb-8 border border-slate-100">
        <form action="{{ route('admin.usuarios.index') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                {{-- Búsqueda --}}
                <div class="lg:col-span-2 relative">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 ml-1">Búsqueda rápida</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                        placeholder="Nombre o email..." 
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-4 focus:ring-[#669BBC]/10 focus:border-[#669BBC] outline-none transition-all text-sm font-medium">
                    <div class="absolute left-3 top-[34px] text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                {{-- Rol --}}
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 ml-1">Filtrar por Rol</label>
                    <select name="rol" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-4 focus:ring-[#669BBC]/10 focus:border-[#669BBC] outline-none transition-all text-sm font-bold text-[#003049]">
                        <option value="">Todos los roles</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->nombre }}" {{ request('rol') == $role->nombre ? 'selected' : '' }}>{{ $role->etiqueta ?? ucfirst($role->nombre) }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Estatus --}}
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 ml-1">Estatus</label>
                    <select name="estatus" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-4 focus:ring-[#669BBC]/10 focus:border-[#669BBC] outline-none transition-all text-sm font-bold text-[#003049]">
                        <option value="">Todos</option>
                        <option value="activo" {{ request('estatus') == 'activo' ? 'selected' : '' }}>Activo</option>
                        <option value="inactivo" {{ request('estatus') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>

                {{-- Botones --}}
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 bg-[#003049] text-white py-2.5 rounded-xl font-bold hover:bg-[#001d2e] transition-all shadow-md shadow-blue-900/10 flex items-center justify-center gap-2 text-sm">
                        Filtrar
                    </button>
                    @if(request()->anyFilled(['search', 'rol', 'estatus', 'desde', 'hasta']))
                        <a href="{{ route('admin.usuarios.index') }}" class="p-2.5 bg-slate-100 text-slate-500 rounded-xl hover:bg-slate-200 transition-all shadow-sm" title="Limpiar filtros">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                    @endif
                </div>
            </div>

            {{-- Filtros de Fecha --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mt-4 pt-4 border-t border-slate-50">
                <div class="lg:col-span-1">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 ml-1">Registrado desde</label>
                    <input type="date" name="desde" value="{{ request('desde') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-4 focus:ring-[#669BBC]/10 focus:border-[#669BBC] outline-none transition-all text-sm font-medium">
                </div>
                <div class="lg:col-span-1">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 ml-1">Registrado hasta</label>
                    <input type="date" name="hasta" value="{{ request('hasta') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-4 focus:ring-[#669BBC]/10 focus:border-[#669BBC] outline-none transition-all text-sm font-medium">
                </div>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <table class="w-full">
            <thead class="bg-[#003049] text-white">
                <tr>
                    <th class="px-6 py-4 text-left font-bold uppercase tracking-wider text-sm">Nombre</th>
                    <th class="px-6 py-4 text-left font-bold uppercase tracking-wider text-sm">Email</th>
                    <th class="px-6 py-4 text-left font-bold uppercase tracking-wider text-sm">Roles</th>
                    <th class="px-6 py-4 text-left font-bold uppercase tracking-wider text-sm">Estatus</th>
                    <th class="px-6 py-4 text-center font-bold uppercase tracking-wider text-sm">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($usuarios as $usuario)
                <tr class="hover:bg-slate-50 transition-colors {{ $usuario->id == auth()->id() ? 'bg-blue-50/50' : '' }}">
                    <td class="px-6 py-4 font-medium">
                        <div class="flex items-center gap-2">
                            {{ $usuario->nombre }}
                            @if($usuario->id == auth()->id())
                                <span class="text-[10px] bg-blue-100 text-blue-600 px-2 py-0.5 rounded-full font-bold">Tú</span>
                            @endif
                            @if($usuario->google_id)
                                <span title="Vinculado con Google" class="text-[10px]">🔗</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm">{{ $usuario->email }}</td>
                    <td class="px-6 py-4">
                        @forelse($usuario->roles as $role)
                            <span class="inline-block rounded-full px-3 py-1 text-xs font-semibold mr-1 mb-1
                                {{ $role->nombre === 'admin' ? 'bg-amber-100 text-amber-800' : 'bg-gray-200 text-gray-700' }}">
                                {{ $role->etiqueta ?? $role->nombre }}
                            </span>
                        @empty
                            <span class="text-xs text-red-400 italic">Sin roles</span>
                        @endforelse
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-full text-xs font-bold {{ $usuario->estatus === 'activo' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($usuario->estatus) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('admin.usuarios.edit', $usuario->id) }}" 
                                class="text-[#669BBC] hover:text-[#003049] font-medium text-sm transition-colors">
                                Editar
                            </a>
                            @if($usuario->id != auth()->id())
                                <button onclick="confirmToggleStatus({{ $usuario->id }}, '{{ addslashes($usuario->nombre) }}', '{{ $usuario->estatus }}', {{ $usuario->inmuebles()->count() }}, {{ ($usuario->contratosComoPropietario()->whereIn('estatus', ['activo', 'vigente'])->count() + $usuario->contratosComoInquilino()->whereIn('estatus', ['activo', 'vigente'])->count()) }}, {{ $usuario->tieneRol('admin') || $usuario->es_admin ? 'true' : 'false' }})" 
                                    class="{{ $usuario->estatus === 'activo' ? 'text-[#C1121F] hover:text-[#780000]' : 'text-green-600 hover:text-green-800' }} font-medium text-sm transition-colors">
                                    {{ $usuario->estatus === 'activo' ? 'Desactivar' : 'Activar' }}
                                </button>
                            @else
                                <span class="text-slate-300 text-sm cursor-not-allowed" title="No puedes cambiar tu propio estatus">
                                    {{ $usuario->estatus === 'activo' ? 'Desactivar' : 'Activar' }}
                                </span>
                            @endif
                        </div>
                        @if($usuario->id != auth()->id())
                            <form id="delete-form-{{ $usuario->id }}" action="{{ route('admin.usuarios.destroy', $usuario->id) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <p class="font-bold text-slate-500">No se encontraron usuarios</p>
                        @if(request('search'))
                            <p class="text-sm mt-1">No hay resultados para "<strong>{{ request('search') }}</strong>"</p>
                            <a href="{{ route('admin.usuarios.index') }}" class="text-[#669BBC] hover:underline text-sm mt-2 inline-block">Limpiar búsqueda</a>
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        {{-- Paginación --}}
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
            {{ $usuarios->links() }}
            @if($usuarios->total() > 0)
                <p class="text-xs text-gray-400 mt-2 text-center">
                    Mostrando {{ $usuarios->firstItem() }} a {{ $usuarios->lastItem() }} de {{ $usuarios->total() }} usuarios
                </p>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmToggleStatus(id, nombre, estatusActual, inmueblesCount, contratosActivos, esAdmin) {
    if (esAdmin) {
        Swal.fire({
            title: 'Acción no permitida',
            html: `<p>No puedes desactivar al administrador <strong>${nombre}</strong>.</p>
                   <p style="font-size: 0.875rem; color: #666; margin-top: 0.5rem;">Primero quítale el rol de administrador desde la edición del usuario.</p>`,
            icon: 'error',
            confirmButtonColor: '#003049',
            confirmButtonText: 'Entendido'
        });
        return;
    }

    if (estatusActual === 'activo' && (contratosActivos > 0 || inmueblesCount > 0)) {
        Swal.fire({
            title: 'Acción no permitida',
            html: `<p>El usuario <strong>${nombre}</strong> no puede ser desactivado porque tiene propiedades publicadas o contratos vigentes.</p>`,
            icon: 'error',
            confirmButtonColor: '#003049',
            confirmButtonText: 'Entendido'
        });
        return;
    }

    let nuevoEstatus = estatusActual === 'activo' ? 'inactivo' : 'activo';
    let accionTexto = estatusActual === 'activo' ? 'Desactivar' : 'Activar';

    Swal.fire({
        title: `¿${accionTexto} este usuario?`,
        html: `
            <div style="text-align: left; padding: 0.5rem; background: #fef2f2; border-radius: 0.75rem; margin-top: 0.5rem;">
                <p style="margin-bottom: 0.25rem;"><strong>👤 Nombre:</strong> ${nombre}</p>
                <p style="margin-bottom: 0.25rem;"><strong>Estatus actual:</strong> ${estatusActual.toUpperCase()}</p>
            </div>
            <p style="font-size: 0.8rem; color: #003049; margin-top: 1rem; font-weight: bold;">
                El usuario pasará a estar ${nuevoEstatus.toUpperCase()}.
            </p>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: estatusActual === 'activo' ? '#C1121F' : '#22c55e',
        cancelButtonColor: '#003049',
        confirmButtonText: `Sí, ${accionTexto.toLowerCase()}`,
        cancelButtonText: 'Cancelar',
        focusCancel: true,
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    });
}
</script>
@endpush
