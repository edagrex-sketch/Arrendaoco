@extends('layouts.app')

@section('title', 'Gestión de Usuarios - Admin')

@section('content')
<div class="px-4">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <h1 class="text-3xl font-bold text-[#003049]">Gestión de Usuarios</h1>
            <p class="text-sm text-slate-400 mt-1">{{ $usuarios->total() }} usuario(s) registrado(s) en la plataforma</p>
        </div>
        
        <div class="flex gap-2">
            <a href="{{ route('admin.usuarios.create') }}" class="bg-[#669BBC] text-white px-5 py-2 rounded-xl font-bold hover:bg-[#003049] transition-all shadow-md shadow-blue-100 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Crear Usuario
            </a>
            <a href="{{ route('admin.usuarios.reporte') }}" class="bg-[#C1121F] text-white px-5 py-2 rounded-xl font-bold hover:bg-[#780000] transition-all shadow-md shadow-red-100 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                PDF
            </a>
        </div>
    </div>

    {{-- Buscador Full Width --}}
    <div class="mb-6">
        <form action="{{ route('admin.usuarios.index') }}" method="GET" class="relative w-full">
            <input type="text" name="search" value="{{ request('search') }}" 
                placeholder="Buscar usuarios por nombre completo o correo electrónico..." 
                class="w-full pl-12 pr-12 py-3 rounded-2xl border-none bg-[#669BBC] focus:ring-4 focus:ring-[#669BBC]/20 outline-none transition-all shadow-md text-lg placeholder:text-white/70 text-white font-medium">
            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-white/90">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            @if(request('search'))
                <a href="{{ route('admin.usuarios.index') }}" class="absolute right-4 top-1/2 -translate-y-1/2 text-white/70 hover:text-white transition-colors">
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
                                <button onclick="confirmDeleteUser({{ $usuario->id }}, '{{ addslashes($usuario->nombre) }}', '{{ $usuario->email }}', {{ $usuario->roles->count() }}, {{ $usuario->inmuebles()->count() }}, {{ ($usuario->contratosComoPropietario()->where('estatus', 'activo')->count() + $usuario->contratosComoInquilino()->where('estatus', 'activo')->count()) }}, {{ $usuario->tieneRol('admin') || $usuario->es_admin ? 'true' : 'false' }})" 
                                    class="text-[#C1121F] hover:text-[#780000] font-medium text-sm transition-colors">
                                    Eliminar
                                </button>
                            @else
                                <span class="text-slate-300 text-sm cursor-not-allowed" title="No puedes eliminarte a ti mismo">
                                    Eliminar
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
</div>

@push('scripts')
<script>
function confirmDeleteUser(id, nombre, email, rolesCount, inmueblesCount, contratosActivos, esAdmin) {
    // Bloquear si es admin
    if (esAdmin) {
        Swal.fire({
            title: 'Acción no permitida',
            html: `<p>No puedes eliminar al administrador <strong>${nombre}</strong>.</p>
                   <p style="font-size: 0.875rem; color: #666; margin-top: 0.5rem;">Primero quítale el rol de administrador desde la edición del usuario.</p>`,
            icon: 'error',
            confirmButtonColor: '#003049',
            confirmButtonText: 'Entendido'
        });
        return;
    }

    // Verificar si tiene datos que impiden eliminación
    if (contratosActivos > 0) {
        Swal.fire({
            title: 'No se puede eliminar',
            html: `<p>El usuario <strong>${nombre}</strong> tiene <strong>${contratosActivos} contrato(s) activo(s)</strong>.</p>
                   <p style="font-size: 0.875rem; color: #666; margin-top: 0.5rem;">Finaliza los contratos antes de eliminar al usuario.</p>`,
            icon: 'error',
            confirmButtonColor: '#003049',
            confirmButtonText: 'Entendido'
        });
        return;
    }

    if (inmueblesCount > 0) {
        Swal.fire({
            title: 'No se puede eliminar',
            html: `<p>El usuario <strong>${nombre}</strong> tiene <strong>${inmueblesCount} propiedad(es)</strong> registrada(s).</p>
                   <p style="font-size: 0.875rem; color: #666; margin-top: 0.5rem;">Elimina o transfiere las propiedades antes de borrar al usuario.</p>`,
            icon: 'error',
            confirmButtonColor: '#003049',
            confirmButtonText: 'Entendido'
        });
        return;
    }

    // Confirmación con información detallada
    Swal.fire({
        title: '¿Eliminar este usuario?',
        html: `
            <div style="text-align: left; padding: 0.5rem; background: #fef2f2; border-radius: 0.75rem; margin-top: 0.5rem;">
                <p style="margin-bottom: 0.25rem;"><strong>👤 Nombre:</strong> ${nombre}</p>
                <p style="margin-bottom: 0.25rem;"><strong>📧 Email:</strong> ${email}</p>
                <p><strong>🏷️ Roles:</strong> ${rolesCount} rol(es)</p>
            </div>
            <p style="font-size: 0.8rem; color: #dc2626; margin-top: 1rem; font-weight: bold;">
                ⚠️ Esta acción eliminará permanentemente al usuario, sus reseñas y favoritos.
            </p>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#C1121F',
        cancelButtonColor: '#003049',
        confirmButtonText: 'Sí, eliminar usuario',
        cancelButtonText: 'Cancelar',
        focusCancel: true,
    }).then((result) => {
        if (result.isConfirmed) {
            // Segunda confirmación para seguridad extra
            Swal.fire({
                title: '⚠️ Confirmación final',
                text: `Escribe "ELIMINAR" para confirmar la eliminación de ${nombre}`,
                input: 'text',
                inputPlaceholder: 'Escribe ELIMINAR',
                showCancelButton: true,
                confirmButtonColor: '#C1121F',
                cancelButtonColor: '#003049',
                confirmButtonText: 'Confirmar eliminación',
                cancelButtonText: 'Cancelar',
                inputValidator: (value) => {
                    if (value !== 'ELIMINAR') {
                        return 'Debes escribir "ELIMINAR" exactamente para confirmar';
                    }
                }
            }).then((result2) => {
                if (result2.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    });
}
</script>
@endpush
@endsection
