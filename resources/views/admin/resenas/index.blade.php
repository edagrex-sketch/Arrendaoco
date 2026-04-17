@extends('layouts.admin')

@section('title', 'Moderación de Reseñas')
@section('page-title', 'Moderación de Reseñas')
@section('page-subtitle', 'Supervisa y administra los comentarios de la comunidad')

@section('content')


<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
    <p class="text-sm text-slate-400">{{ $resenas->total() }} reseña(s) registradas</p>

    <div class="flex gap-2">
        <a href="{{ route('admin.resenas.reporte', request()->all()) }}" class="bg-[#C1121F] text-white px-5 py-2 rounded-xl font-bold hover:bg-[#780000] transition-all shadow-md shadow-red-100 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            PDF Filtrado
        </a>
    </div>
</div>

    {{-- Buscador y Filtros Avanzados --}}
    <div class="bg-white rounded-3xl shadow-md p-6 mb-8 border border-slate-100">
        <form action="{{ route('admin.resenas.index') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                {{-- Búsqueda --}}
                <div class="lg:col-span-2 relative">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 ml-1">Búsqueda rápida</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                        placeholder="Comentario, usuario o propiedad..." 
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-4 focus:ring-[#669BBC]/10 focus:border-[#669BBC] outline-none transition-all text-sm font-medium">
                    <div class="absolute left-3 top-[34px] text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                {{-- Calificación --}}
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 ml-1">Calificación</label>
                    <select name="puntuacion" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-4 focus:ring-[#669BBC]/10 focus:border-[#669BBC] outline-none transition-all text-sm font-bold text-[#003049]">
                        <option value="">Todas</option>
                        @for($i=5; $i>=1; $i--)
                            <option value="{{ $i }}" {{ request('puntuacion') == $i ? 'selected' : '' }}>{{ $i }} {{ $i == 1 ? 'estrella' : 'estrellas' }}</option>
                        @endfor
                    </select>
                </div>

                {{-- Botones --}}
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 bg-[#003049] text-white py-2.5 rounded-xl font-bold hover:bg-[#001d2e] transition-all shadow-md shadow-blue-900/10 flex items-center justify-center gap-2 text-sm">
                        Filtrar
                    </button>
                    @if(request()->anyFilled(['search', 'puntuacion', 'desde', 'hasta']))
                        <a href="{{ route('admin.resenas.index') }}" class="p-2.5 bg-slate-100 text-slate-500 rounded-xl hover:bg-slate-200 transition-all shadow-sm" title="Limpiar filtros">
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
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 ml-1">Publicado desde</label>
                    <input type="date" name="desde" value="{{ request('desde') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-4 focus:ring-[#669BBC]/10 focus:border-[#669BBC] outline-none transition-all text-sm font-medium">
                </div>
                <div class="lg:col-span-1">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 ml-1">Publicado hasta</label>
                    <input type="date" name="hasta" value="{{ request('hasta') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-4 focus:ring-[#669BBC]/10 focus:border-[#669BBC] outline-none transition-all text-sm font-medium">
                </div>
            </div>
        </form>
    </div>

<div class="bg-card rounded-3xl shadow-xl shadow-blue-900/5 border border-border overflow-hidden">

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-border">
                            <th class="px-6 py-5 text-xs uppercase tracking-widest font-black text-[#003049]">Usuario</th>
                            <th class="px-6 py-5 text-xs uppercase tracking-widest font-black text-[#003049]">Inmueble</th>
                            <th class="px-6 py-5 text-xs uppercase tracking-widest font-black text-[#003049]">Calificación
                            </th>
                            <th class="px-6 py-5 text-xs uppercase tracking-widest font-black text-[#003049]">Comentario
                            </th>
                            <th class="px-6 py-5 text-xs uppercase tracking-widest font-black text-[#003049]">Fecha</th>
                            <th class="px-6 py-5 text-xs uppercase tracking-widest font-black text-[#003049] text-right">
                                Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($resenas as $resena)
                            <tr class="hover:bg-slate-50/80 transition-colors group">
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="h-10 w-10 rounded-full bg-[#669BBC] flex items-center justify-center text-white font-bold text-sm">
                                            {{ strtoupper(substr($resena->usuario->nombre, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-[#003049] leading-tight">{{ $resena->usuario->nombre }}
                                            </p>
                                            <p class="text-xs text-muted-foreground">{{ $resena->usuario->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <a href="{{ route('inmuebles.show', $resena->inmueble) }}"
                                        class="text-sm font-medium text-[#669BBC] hover:underline line-clamp-1">
                                        {{ $resena->inmueble->titulo }}
                                    </a>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex gap-0.5">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 {{ $i <= $resena->puntuacion ? 'text-yellow-400' : 'text-slate-200' }}"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        @endfor
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <p class="text-sm text-slate-600 italic line-clamp-2 max-w-xs">
                                        "{{ $resena->comentario }}"</p>
                                </td>
                                <td class="px-6 py-5">
                                    <p class="text-xs font-bold text-muted-foreground uppercase tracking-widest">
                                        {{ $resena->created_at->format('d/m/Y') }}</p>
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <div class="flex justify-end gap-3">
                                        <form id="delete-form-resena-{{ $resena->id }}"
                                            action="{{ route('resenas.destroy', $resena) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete('resena-{{ $resena->id }}')"
                                                class="flex items-center gap-2 px-4 py-2 bg-[#FEE2E2] text-[#991B1B] font-bold text-xs uppercase tracking-tighter rounded-xl border border-[#FECACA] hover:bg-[#991B1B] hover:text-white hover:border-[#991B1B] transition-all duration-300 shadow-sm hover:shadow-md active:scale-95"
                                                title="Eliminar comentario">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-20 text-center">
                                    <div class="max-w-xs mx-auto">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-16 w-16 text-slate-200 mx-auto mb-4" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                        <p class="text-slate-500 font-bold">No hay reseñas que moderar.</p>
                                        <p class="text-xs text-muted-foreground mt-1">Cuando los usuarios califiquen
                                            inmuebles, aparecerán aquí.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($resenas->hasPages())
                <div class="px-6 py-4 bg-slate-50/50 border-t border-border">
                    {{ $resenas->links() }}
                </div>
            @endif
        </div>
@endsection

