@extends('layouts.app')

@section('title', 'Mis Propiedades — ArrendaOco')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    {{-- ===== Encabezado ===== --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-10 gap-6">
        <div>
            <h1 class="text-4xl font-extrabold text-[#003049] tracking-tight">
                {{ (Auth::user()->es_admin || Auth::user()->tieneRol('admin')) ? 'Gestión de Propiedades' : 'Mis Propiedades' }}
            </h1>
            <p class="text-gray-500 mt-2 text-base">
                Gestiona tus anuncios y publicaciones desde aquí.
            </p>
        </div>
        <a href="{{ route('inmuebles.create') }}"
            class="inline-flex items-center gap-2 bg-[#C1121F] text-white px-8 py-4 rounded-2xl font-bold shadow-xl shadow-[#C1121F]/20 hover:bg-[#780000] hover:-translate-y-1 transition-all duration-300 shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
            </svg>
            Publicar Nuevo
        </a>
    </div>

    @if ($inmuebles->isEmpty())
        {{-- ===== Estado vacío ===== --}}
        <div class="bg-white rounded-3xl p-20 text-center border-2 border-dashed border-slate-200">
            <div class="bg-[#FDF0D5] w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6 text-[#003049]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-[#003049] mb-2">Aún no tienes propiedades</h3>
            <p class="text-gray-500 text-lg mb-8">Comienza a publicar hoy mismo y llega a miles de personas.</p>
            <a href="{{ route('inmuebles.create') }}" class="text-[#C1121F] font-bold hover:underline flex items-center justify-center gap-2">
                Crear mi primera publicación
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </a>
        </div>
    @else
        {{-- ===== Grid de propiedades ===== --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($inmuebles as $inmueble)
                @php
                    // Detectar contratos activos del flujo físico (nuevo) y del flujo anterior (legado)
                    $contratosPendientes   = $inmueble->contratos->where('estatus', 'pendiente_aprobacion');
                    $contratosPdfDesc      = $inmueble->contratos->where('estatus', 'pdf_descargado');
                    $contratoPendiente     = $contratosPendientes->first();
                    $contratoPdfDesc       = $contratosPdfDesc->first();
                    $contratoActivo        = $inmueble->contratos->where('estatus', 'activo')->first();
                    $enProceso             = $contratoPendiente !== null || $contratoPdfDesc !== null;
                    // El contrato activo para mostrar en el card (prioriza flujo físico)
                    $contratoEnCurso       = $contratoPdfDesc ?? $contratoPendiente;
                @endphp

                <div class="group bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500 border {{ $enProceso ? 'border-[#669BBC]/40 ring-2 ring-[#669BBC]/20' : 'border-slate-100' }} flex flex-col h-full relative">


                    {{-- Imagen --}}
                    <div class="relative h-56 overflow-hidden">
                        <img src="{{ $inmueble->imagen_url }}" alt="{{ $inmueble->titulo }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">

                        {{-- Badge de estatus --}}
                        <div class="absolute top-4 right-4">
                            @if($enProceso)
                                <span class="bg-[#669BBC]/95 backdrop-blur px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest shadow-lg text-white border border-white/20 flex items-center gap-1.5">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415l-2.414-2.414V6z" clip-rule="evenodd"/></svg>
                                    Proceso de renta
                                </span>
                            @elseif($inmueble->estatus === 'rentado')
                                <span class="bg-[#003049]/95 backdrop-blur px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest shadow-lg text-white border border-white/20">
                                    Rentado
                                </span>
                            @else
                                <span class="bg-[#669BBC]/95 backdrop-blur px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest shadow-lg text-white border border-white/20">
                                    Disponible
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Información principal --}}
                    <div class="p-6 flex-grow">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="bg-[#FDF0D5] text-[#003049] text-[10px] font-black uppercase px-2 py-0.5 rounded-sm">
                                {{ $inmueble->tipo }}
                            </span>
                        </div>
                        <h3 class="text-xl font-black text-[#003049] mb-1 line-clamp-1">{{ $inmueble->titulo }}</h3>
                        <p class="text-gray-500 text-sm flex items-center gap-1 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#C1121F] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            </svg>
                            <span class="line-clamp-1">{{ $inmueble->direccion }}</span>
                        </p>

                        <div class="flex items-center justify-between py-4 border-t border-slate-50">
                            <div class="text-2xl font-black text-[#003049]">
                                ${{ number_format($inmueble->renta_mensual) }}
                                <span class="text-xs font-medium text-gray-400 lowercase">/mes</span>
                            </div>
                        </div>

                        {{-- ===== Alerta: PDF Descargado — Esperando firmado físico ===== --}}
                        @if($contratoPdfDesc && $contratoPdfDesc->inquilino)
                            @php $inq = $contratoPdfDesc->inquilino; @endphp
                            <div class="mt-2 p-4 bg-[#FDF0D5] rounded-2xl border border-[#003049]/20 flex items-start gap-3">
                                <div class="h-9 w-9 rounded-xl bg-[#003049] flex items-center justify-center shrink-0 mt-0.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#FDF0D5]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-[#003049] mb-0.5">PDF Descargado &mdash; Subir firmado</p>
                                    <p class="text-sm font-bold text-[#003049] truncate">{{ $inq->nombre }}</p>
                                    <p class="text-[10px] text-slate-500">Descargado: {{ \Carbon\Carbon::parse($contratoPdfDesc->pdf_descargado_at)->format('d/m/Y H:i') }}</p>
                                </div>
                                <div class="flex flex-col gap-2 shrink-0">
                                    <a href="{{ route('contratos.subir-firmado', $contratoPdfDesc->id) }}"
                                       class="bg-[#003049] text-white text-center text-xs font-black px-3 py-2 rounded-xl hover:bg-[#002236] transition-colors whitespace-nowrap">
                                        Subir firmado
                                    </a>
                                    <a href="{{ route('chats.start', ['otroUsuarioId' => $inq->id, 'inmuebleId' => $inmueble->id]) }}"
                                       class="bg-white border border-[#003049]/20 text-[#003049] text-center text-[10px] font-bold px-3 py-2 rounded-xl hover:bg-[#003049]/5 transition-colors whitespace-nowrap flex items-center justify-center gap-1">
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                        Mensaje
                                    </a>
                                </div>
                            </div>

                        {{-- ===== Inquilino pendiente de aprobación (flujo legado) ===== --}}
                        @elseif($enProceso && $contratoEnCurso && $contratoEnCurso->inquilino)
                            @php $inq = $contratoEnCurso->inquilino; @endphp
                            <div class="mt-2 p-4 bg-[#FDF0D5] rounded-2xl border border-[#669BBC]/30 flex items-start gap-3">
                                <div class="w-11 h-11 rounded-full bg-white border-2 border-[#669BBC]/30 flex items-center justify-center text-[#003049] font-bold uppercase overflow-hidden shrink-0 shadow-sm text-sm mt-0.5">
                                    @if($inq->foto_perfil)
                                        <img src="{{ str_starts_with($inq->foto_perfil, 'http') ? $inq->foto_perfil : asset('storage/'.$inq->foto_perfil) }}" alt="Inquilino" class="w-full h-full object-cover">
                                    @else
                                        {{ substr($inq->nombre, 0, 2) }}
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1 mt-1">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-[#669BBC] mb-0.5">Firma recibida de</p>
                                    <p class="text-sm font-bold text-[#003049] truncate">{{ $inq->nombre }}</p>
                                    <p class="text-[10px] text-gray-500 truncate">Inicio: {{ \Carbon\Carbon::parse($contratoPendiente->fecha_inicio)->format('d/m/Y') }}</p>
                                </div>
                                <div class="flex flex-col gap-2 shrink-0">
                                    <a href="{{ route('contratos.revision', $contratoPendiente->id) }}"
                                        class="bg-[#003049] text-white text-center text-xs font-black px-3 py-2 rounded-xl hover:bg-[#002236] transition-colors whitespace-nowrap">
                                        Revisar
                                    </a>
                                    <a href="{{ route('chats.start', ['otroUsuarioId' => $inq->id, 'inmuebleId' => $inmueble->id]) }}"
                                       class="bg-white border border-[#003049]/20 text-[#003049] text-center text-[10px] font-bold px-3 py-2 rounded-xl hover:bg-[#003049]/5 transition-colors whitespace-nowrap flex items-center justify-center gap-1">
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                        Mensaje
                                    </a>
                                </div>
                            </div>

                        {{-- Inquilino activo --}}
                        @elseif($contratoActivo && $contratoActivo->inquilino)
                            @php $inq = $contratoActivo->inquilino; @endphp
                            <div class="mt-2 p-4 bg-[#003049]/5 rounded-2xl border border-[#003049]/10 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-white border border-[#669BBC]/20 flex items-center justify-center text-[#003049] font-bold uppercase overflow-hidden shrink-0 shadow-sm text-sm">
                                    @if($inq->foto_perfil)
                                        <img src="{{ str_starts_with($inq->foto_perfil, 'http') ? $inq->foto_perfil : asset('storage/'.$inq->foto_perfil) }}" alt="Inquilino" class="w-full h-full object-cover">
                                    @else
                                        {{ substr($inq->nombre, 0, 2) }}
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-[#669BBC] mb-0.5">Rentado por</p>
                                    <p class="text-sm font-bold text-[#003049] truncate">{{ $inq->nombre }}</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Footer con botones --}}
                    <div class="p-5 bg-slate-50 flex gap-3">
                        <a href="{{ route('inmuebles.show', $inmueble) }}"
                            class="flex-1 bg-white text-[#003049] font-bold py-3 rounded-xl border border-slate-200 hover:bg-[#003049] hover:text-white transition-all text-center text-sm">
                            Ver
                        </a>
                        @if($inmueble->estatus !== 'rentado' && !$enProceso)
                            <a href="{{ route('inmuebles.edit', $inmueble) }}"
                                class="flex-1 bg-white text-[#669BBC] font-bold py-3 rounded-xl border border-slate-200 hover:bg-[#669BBC] hover:text-white transition-all text-center text-sm">
                                Editar
                            </a>
                        @else
                            <span class="flex-1 bg-slate-100 text-slate-400 font-bold py-3 rounded-xl border border-slate-200 cursor-not-allowed text-center text-sm" title="No disponible en este estado">
                                Editar
                            </span>
                        @endif
                        @if($inmueble->estatus !== 'rentado' && !$enProceso)
                            <button onclick="confirmDelete({{ $inmueble->id }})" title="Eliminar propiedad"
                                class="w-12 h-12 flex items-center justify-center rounded-xl bg-white text-[#C1121F] border border-slate-200 hover:bg-[#C1121F] hover:text-white transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        @else
                            <button disabled title="No se puede eliminar en este estado"
                                class="w-12 h-12 flex items-center justify-center rounded-xl bg-slate-100 text-slate-300 border border-slate-200 cursor-not-allowed">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        @endif
                        <form id="delete-form-{{ $inmueble->id }}" action="{{ route('inmuebles.destroy', $inmueble) }}" method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Paginación --}}
        <div class="mt-12 flex justify-center">
            {{ $inmuebles->links() }}
        </div>
    @endif

</div>

@push('scripts')
<script>
function confirmDelete(id) {
    if (confirm('¿Estás seguro de que deseas eliminar esta propiedad? Esta acción no se puede deshacer.')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endpush

@endsection
