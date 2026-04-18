@extends('layouts.app')

@section('title', 'Revisar Solicitud de Renta — ArrendaOco')


@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ modalAprobarAbierto: false }">

    {{-- Encabezado --}}
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('inmuebles.index') }}" class="text-[#669BBC] hover:text-[#003049] transition-colors text-sm font-medium flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                Mis Propiedades
            </a>
        </div>
        <h1 class="text-3xl font-extrabold text-[#003049]">Revisar Solicitud de Renta</h1>
        <p class="text-gray-500 mt-1">Propiedad: <span class="font-semibold text-[#003049]">{{ $contrato->inmueble->titulo }}</span></p>
    </div>

    {{-- Alerta de urgencia --}}
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 flex items-start gap-3 mb-8">
        <svg class="h-6 w-6 text-amber-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
        <div>
            <p class="font-bold text-amber-800 text-sm">Fondos pre-autorizados — Responde en 24 horas</p>
            <p class="text-amber-700 text-xs mt-1">Los fondos del inquilino están congelados en su cuenta. Si no respondes en 24 hrs, la solicitud expira automáticamente y los fondos se liberan.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">

        {{-- Columna Izquierda: Previsualización del contrato --}}
        <div class="lg:col-span-3">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-[#003049] text-white px-6 py-4 flex items-center gap-3">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    <span class="font-bold">Previsualización del Contrato</span>
                </div>
                <div class="p-6 h-[560px] overflow-y-auto bg-slate-50">
                    <div class="bg-white shadow-sm border border-gray-200 rounded p-8 text-sm text-gray-700 space-y-4 font-serif leading-relaxed">
                        <x-contrato-legal :inmueble="$contrato->inmueble" :contrato="$contrato" />

                        <div class="grid grid-cols-2 gap-6 mt-4 text-center text-xs">
                            {{-- Firma Propietario --}}
                            <div class="flex flex-col items-center">
                                <div class="h-16 w-full mb-2 flex items-center justify-center bg-amber-50 rounded border border-amber-200 p-2" id="preview-firma-propietario">
                                    <p class="text-[9px] text-amber-800 leading-tight">← Agrega tu firma digital abajo</p>
                                </div>
                                <div class="w-full border-t border-gray-400 pt-2">
                                    <p class="font-bold text-xs uppercase">Firma del Arrendador</p>
                                    <p class="text-gray-500 mt-1">{{ optional($contrato->inmueble->propietario)->nombre }}</p>
                                </div>
                            </div>
                            {{-- Firma Inquilino --}}
                            <div class="flex flex-col items-center">
                                <div class="h-16 w-full mb-2 relative">
                                    @if($contrato->firma_digital)
                                        <img src="{{ $contrato->firma_digital }}" class="w-full h-full object-contain" alt="Firma Inquilino">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-300 text-xs italic">Sin firma</div>
                                    @endif
                                </div>
                                <div class="w-full border-t border-gray-800 pt-2">
                                    <p class="font-bold text-xs uppercase">Firma del Inquilino</p>
                                    <p class="text-gray-500 mt-1">{{ optional($contrato->inquilino)->nombre }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Columna Derecha: Panel de Acción --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Datos del Inquilino --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-[#003049] mb-4 flex items-center gap-2">
                    <svg class="h-5 w-5 text-[#669BBC]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    Datos del Inquilino
                </h3>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-full bg-[#003049]/10 flex items-center justify-center font-bold text-[#003049] text-lg uppercase overflow-hidden">
                        @if(optional($contrato->inquilino)->foto_perfil)
                            <img src="{{ str_starts_with($contrato->inquilino->foto_perfil, 'http') ? $contrato->inquilino->foto_perfil : asset('storage/'.$contrato->inquilino->foto_perfil) }}" alt="Foto" class="w-full h-full object-cover">
                        @else
                            {{ substr(optional($contrato->inquilino)->nombre ?? 'I', 0, 1) }}
                        @endif
                    </div>
                    <div>
                        <p class="font-bold text-[#003049]">{{ optional($contrato->inquilino)->nombre ?? 'Inquilino' }}</p>
                        <p class="text-xs text-gray-400">{{ optional($contrato->inquilino)->email }}</p>
                    </div>
                </div>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between text-gray-600">
                        <span class="text-xs font-bold text-[#669BBC] uppercase">Inicio</span>
                        <span class="font-semibold">{{ \Carbon\Carbon::parse($contrato->fecha_inicio)->translatedFormat('d M, Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center text-gray-600">
                        <span class="text-xs font-bold text-[#669BBC] uppercase">Plazo</span>
                        <select form="form-aprobar" name="duracion_meses" class="font-semibold bg-gray-50 border border-gray-200 rounded text-sm px-2 py-1 focus:ring-[#003049] text-[#003049]">
                            @php
                                $dM = $contrato->inmueble->duracion_contrato_meses ?? 12;
                            @endphp
                            <option value="6" {{ $dM == 6 ? 'selected' : '' }}>6 meses</option>
                            <option value="12" {{ $dM == 12 ? 'selected' : '' }}>1 año</option>
                            <option value="18" {{ $dM == 18 ? 'selected' : '' }}>18 meses</option>
                            <option value="24" {{ $dM == 24 ? 'selected' : '' }}>2 años</option>
                            <option value="36" {{ $dM == 36 ? 'selected' : '' }}>3 años</option>
                        </select>
                    </div>
                    <div class="flex justify-between text-gray-600 border-t pt-2 mt-2">
                        <span class="text-xs font-bold text-[#669BBC] uppercase">Renta/mes</span>
                        <span class="font-black text-[#003049]">${{ number_format($contrato->renta_mensual, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span class="text-xs font-bold text-[#669BBC] uppercase">Depósito</span>
                        <span class="font-black text-[#003049]">${{ number_format($contrato->deposito ?? 0, 2) }}</span>
                    </div>
                    <div class="flex justify-between border-t pt-2">
                        <span class="text-xs font-bold text-[#669BBC] uppercase">Total congelado</span>
                        <span class="font-black text-lg text-[#003049]">${{ number_format($contrato->renta_mensual + ($contrato->deposito ?? 0), 2) }}</span>
                    </div>
                </div>
            </div>

            {{-- Botones de Acción --}}
            <div class="space-y-3">
                <button id="btn-aprobar" @click="modalAprobarAbierto = true"
                    class="w-full bg-[#003049] text-white font-bold py-4 px-6 rounded-xl shadow-xl shadow-[#003049]/20 hover:bg-[#002236] hover:-translate-y-0.5 transition-all flex justify-center items-center gap-2 text-lg">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Aprobar y Descargar Contrato
                </button>
                <button id="btn-rechazar" onclick="accionContrato('rechazar')"
                    class="w-full bg-white border-2 border-red-200 text-red-500 hover:bg-red-50 hover:border-red-400 font-bold py-3 px-6 rounded-xl transition-all flex justify-center items-center gap-2">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Rechazar Solicitud
                </button>
                
                <div class="pt-4 border-t border-gray-100">
                    <a href="{{ route('inmuebles.edit', ['inmueble' => $contrato->inmueble->id, 'return_to_contrato' => $contrato->id]) }}" 
                       class="w-full bg-slate-50 border border-slate-200 text-slate-600 hover:bg-slate-100 font-bold py-3 px-6 rounded-xl transition-all flex justify-center items-center gap-2 text-sm">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        Editar más datos del Inmueble
                    </a>
                </div>
            </div>

            {{-- Forms Hidden --}}
            <form id="form-aprobar" action="{{ route('contratos.aprobar', $contrato->id) }}" method="POST" class="hidden">
                @csrf
            </form>
            <form id="form-rechazar" action="{{ route('contratos.rechazar', $contrato->id) }}" method="POST" class="hidden">
                @csrf
            </form>

        </div>
    </div>

    {{-- MODAL — Instrucciones de Formalización Física (Propietario) --}}
    <div x-show="modalAprobarAbierto"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center bg-[#003049]/80 backdrop-blur-sm px-4"
         style="display: none;" x-cloak>

        <div x-show="modalAprobarAbierto"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             @click.away="modalAprobarAbierto = false"
             class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden">

            <div class="bg-[#003049] px-6 pt-6 pb-5 flex items-start gap-4">
                <div class="h-12 w-12 rounded-2xl bg-white/10 flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-white font-black text-lg leading-tight">Confirmar aprobación de renta</h2>
                    <p class="text-[#669BBC] text-xs mt-0.5">Revisa antes de confirmar esta acción</p>
                </div>
            </div>

            <div class="px-6 py-5">
                <p class="text-sm font-bold text-[#003049] mb-4">
                    Al confirmar, el inquilino será notificado y los fondos se capturarán. Podrás descargar el contrato PDF en esta misma pantalla una vez aprobado.
                </p>

                <ol class="space-y-3">
                    @php $pasos = [
                        ['texto' => 'Imprime <strong>dos copias</strong> del contrato PDF que descargarás aquí.'],
                        ['texto' => 'Lleva una <strong>copia de tu identificación oficial</strong> vigente al reunirte con el inquilino.'],
                        ['texto' => 'En el inmueble, <strong>firma la copia del inquilino</strong> y él/ella firmará la tuya.'],
                        ['texto' => 'Asegúrate de <strong>llevarte tu copia firmada</strong> por ambas partes.'],
                    ]; @endphp

                    @foreach($pasos as $i => $paso)
                    <li class="flex items-start gap-3">
                        <div class="h-7 w-7 rounded-full bg-[#003049] flex items-center justify-center shrink-0 mt-0.5"><span class="text-white font-black text-[11px]">{{ $i + 1 }}</span></div>
                        <p class="text-sm text-slate-600 leading-snug pt-1">{!! $paso['texto'] !!}</p>
                    </li>
                    @endforeach
                </ol>

                <div class="mt-5 rounded-xl bg-amber-50 border border-amber-200 px-4 py-3 flex items-start gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <p class="text-xs text-amber-800 font-semibold leading-relaxed">
                        Sin la firma manuscrita en papel, el contrato <strong>no tiene validez legal</strong> ante terceros.
                    </p>
                </div>
            </div>

            <div class="px-6 pb-6 flex flex-col sm:flex-row gap-3">
                <button onclick="document.getElementById('form-aprobar').submit();" class="flex-1 bg-[#003049] text-white font-bold py-3 px-4 rounded-xl hover:bg-[#002236] transition-colors flex justify-center items-center gap-2 text-sm z-10 relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Aprobar y Descargar Contrato
                </button>
                <button @click="modalAprobarAbierto = false" class="flex-1 bg-white border border-slate-300 text-slate-600 font-bold py-3 px-4 rounded-xl hover:bg-slate-50 transition-colors text-sm z-10 relative">
                    Cancelar
                </button>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function accionContrato(accion) {
        if (accion === 'rechazar') {
            Swal.fire({
                icon: 'warning',
                title: '¿Rechazar esta solicitud?',
                html: 'Los fondos congelados serán <strong>liberados de inmediato</strong> sin cargos. El inquilino será notificado.',
                showCancelButton: true,
                confirmButtonText: 'Sí, Rechazar',
                cancelButtonText: 'Volver',
                confirmButtonColor: '#C1121F',
                cancelButtonColor: '#6b7280'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-rechazar').submit();
                }
            });
        }
    }
</script>
@endpush
@endsection
