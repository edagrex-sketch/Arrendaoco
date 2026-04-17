@extends('layouts.admin')

@section('title', 'Gestión de Contratos - Admin')
@section('page-title', 'Gestión de Contratos')
@section('page-subtitle', 'Supervisa y administra los acuerdos de renta en la plataforma')

@section('content')
<div>
    {{-- Header Row --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <p class="text-sm text-slate-400">{{ $contratos->total() }} contrato(s) registrado(s)</p>

        <div class="flex gap-2">
            <a href="{{ route('admin.contratos.reporte', request()->all()) }}" class="bg-[#C1121F] text-white px-5 py-2 rounded-xl font-bold hover:bg-[#780000] transition-all shadow-md shadow-red-100 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                PDF Filtrado
            </a>
        </div>
    </div>

    {{-- Buscador y Filtros Avanzados --}}
    <div class="bg-white rounded-3xl shadow-md p-6 mb-8 border border-slate-100">
        <form action="{{ route('admin.contratos.index') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                {{-- Búsqueda --}}
                <div class="lg:col-span-2 relative">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 ml-1">Búsqueda rápida</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                        placeholder="Propiedad, dueño o inquilino..." 
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-4 focus:ring-[#669BBC]/10 focus:border-[#669BBC] outline-none transition-all text-sm font-medium">
                    <div class="absolute left-3 top-[34px] text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                {{-- Estatus --}}
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 ml-1">Estatus</label>
                    <select name="estatus" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-4 focus:ring-[#669BBC]/10 focus:border-[#669BBC] outline-none transition-all text-sm font-bold text-[#003049]">
                        <option value="">Todos</option>
                        <option value="pendiente_aprobacion" {{ request('estatus') == 'pendiente_aprobacion' ? 'selected' : '' }}>Pendiente</option>
                        <option value="pdf_descargado" {{ request('estatus') == 'pdf_descargado' ? 'selected' : '' }}>PDF listo</option>
                        <option value="activo" {{ request('estatus') == 'activo' ? 'selected' : '' }}>Vigente</option>
                        <option value="finalizado" {{ request('estatus') == 'finalizado' ? 'selected' : '' }}>Finalizado</option>
                        <option value="cancelado" {{ request('estatus') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                    </select>
                </div>

                {{-- Botones --}}
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 bg-[#003049] text-white py-2.5 rounded-xl font-bold hover:bg-[#001d2e] transition-all shadow-md shadow-blue-900/10 flex items-center justify-center gap-2 text-sm">
                        Filtrar
                    </button>
                    @if(request()->anyFilled(['search', 'estatus', 'desde', 'hasta']))
                        <a href="{{ route('admin.contratos.index') }}" class="p-2.5 bg-slate-100 text-slate-500 rounded-xl hover:bg-slate-200 transition-all shadow-sm" title="Limpiar filtros">
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
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 ml-1">Creado desde</label>
                    <input type="date" name="desde" value="{{ request('desde') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-4 focus:ring-[#669BBC]/10 focus:border-[#669BBC] outline-none transition-all text-sm font-medium">
                </div>
                <div class="lg:col-span-1">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 ml-1">Creado hasta</label>
                    <input type="date" name="hasta" value="{{ request('hasta') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-4 focus:ring-[#669BBC]/10 focus:border-[#669BBC] outline-none transition-all text-sm font-medium">
                </div>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-3xl shadow-xl shadow-blue-900/5 border border-border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-border">
                        <th class="px-6 py-5 text-xs uppercase tracking-widest font-black text-[#003049]">Inmueble</th>
                        <th class="px-6 py-5 text-xs uppercase tracking-widest font-black text-[#003049]">Partes</th>
                        <th class="px-6 py-5 text-xs uppercase tracking-widest font-black text-[#003049]">Vigencia</th>
                        <th class="px-6 py-5 text-xs uppercase tracking-widest font-black text-[#003049]">Monto</th>
                        <th class="px-6 py-5 text-xs uppercase tracking-widest font-black text-[#003049]">Estatus</th>
                        <th class="px-6 py-5 text-xs uppercase tracking-widest font-black text-[#003049] text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($contratos as $contrato)
                        <tr class="hover:bg-slate-50/80 transition-colors group">
                            <td class="px-6 py-5">
                                <p class="font-bold text-[#003049] leading-tight">{{ $contrato->inmueble->titulo }}</p>
                                <p class="text-xs text-muted-foreground">{{ $contrato->inmueble->direccion }}</p>
                            </td>
                            <td class="px-6 py-5">
                                <div class="space-y-1">
                                    <p class="text-xs"><span class="font-bold text-[#669BBC]">Prop:</span> {{ $contrato->propietario->nombre }}</p>
                                    <p class="text-xs"><span class="font-bold text-[#C1121F]">Inq:</span> {{ $contrato->inquilino->nombre }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <p class="text-xs font-bold text-[#003049]">{{ $contrato->fecha_inicio->format('d/m/Y') }}</p>
                                <p class="text-[10px] text-muted-foreground uppercase tracking-widest">al {{ $contrato->fecha_fin->format('d/m/Y') }}</p>
                            </td>
                            <td class="px-6 py-5">
                                <p class="font-black text-[#003049]">${{ number_format($contrato->renta_mensual, 2) }}</p>
                                <p class="text-[10px] text-muted-foreground uppercase tracking-widest">mensuales</p>
                            </td>
                            <td class="px-6 py-5">
                                @php
                                    $estatusColors = [
                                        'pendiente_aprobacion' => 'bg-amber-100 text-amber-700',
                                        'pdf_descargado' => 'bg-blue-100 text-blue-700',
                                        'activo' => 'bg-green-100 text-green-700',
                                        'finalizado' => 'bg-gray-100 text-gray-700',
                                        'cancelado' => 'bg-red-100 text-red-700',
                                    ];
                                    $estatusLabels = [
                                        'pendiente_aprobacion' => 'Pendiente',
                                        'pdf_descargado' => 'PDF listo',
                                        'activo' => 'Vigente',
                                        'finalizado' => 'Finalizado',
                                        'cancelado' => 'Cancelado',
                                    ];
                                @endphp
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $estatusColors[$contrato->estatus] ?? 'bg-slate-100 text-slate-700' }}">
                                    {{ $estatusLabels[$contrato->estatus] ?? $contrato->estatus }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('inmuebles.descargarContratoPdf', $contrato) }}" 
                                        class="p-2 bg-slate-100 text-[#003049] rounded-xl hover:bg-[#003049] hover:text-white transition-all shadow-sm"
                                        title="Descargar PDF Original">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-20 text-center">
                                <p class="text-slate-400 font-bold">No se encontraron contratos con los filtros aplicados.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($contratos->hasPages())
            <div class="px-6 py-4 bg-slate-50/50 border-t border-border">
                {{ $contratos->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
