@extends('layouts.app')

@section('title', 'Solicitud Enviada — ArrendaOco')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-16 text-center">

    {{-- Ícono de espera con paleta del proyecto --}}
    <div class="mb-10 inline-flex items-center justify-center h-32 w-32 bg-[#FDF0D5] rounded-[3rem]">
        <div class="h-20 w-20 bg-[#669BBC] rounded-[2rem] flex items-center justify-center shadow-xl shadow-[#669BBC]/20">
            <svg class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
    </div>

    <h1 class="text-4xl font-black text-[#003049] mb-3">¡Formulario Completado!</h1>
    <p class="text-lg text-gray-500 font-medium mb-2">
        Tu monto ha sido <strong class="text-[#003049]">pre-autorizado y congelado</strong> de forma segura.
    </p>
    <p class="text-base text-gray-500 mb-12 max-w-xl mx-auto leading-relaxed">
        Los fondos <strong>no han sido cobrados</strong> aún. Están reservados en tu tarjeta mientras el propietario
        revisa tu solicitud. <span class="text-[#C1121F] font-bold">El propietario tiene 24 horas para confirmar</span>
        y recibirás una notificación con la respuesta.
    </p>

    {{-- Tarjeta de detalle --}}
    <div class="bg-white rounded-[3rem] p-10 shadow-2xl shadow-[#003049]/10 border border-gray-100 mb-10 relative overflow-hidden">
        {{-- Decoración --}}
        <div class="absolute top-0 right-0 h-32 w-32 bg-[#FDF0D5] rounded-bl-[5rem] -mr-16 -mt-16 opacity-60"></div>

        <div class="space-y-6 text-left relative z-10">
            <div class="grid grid-cols-2 gap-8">
                <div>
                    <p class="text-[10px] font-black text-[#669BBC] uppercase tracking-widest mb-1">ID de Solicitud</p>
                    <p class="font-bold text-[#003049]">#AO-{{ isset($contrato) ? str_pad($contrato->id, 6, '0', STR_PAD_LEFT) : rand(100000, 999999) }}-MEX</p>
                </div>
                <div>
                    <p class="text-[10px] font-black text-[#669BBC] uppercase tracking-widest mb-1">Fecha y Hora</p>
                    <p class="font-bold text-[#003049]">{{ now()->format('d M, Y • h:i A') }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-black text-[#669BBC] uppercase tracking-widest mb-1">Estado</p>
                    <span class="inline-flex items-center gap-1.5 bg-[#FDF0D5] text-[#003049] text-xs font-black px-3 py-1.5 rounded-full border border-[#669BBC]/30">
                        <svg class="w-3.5 h-3.5 text-[#669BBC]" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415l-2.414-2.414V6z" clip-rule="evenodd"/>
                        </svg>
                        Pendiente de aprobación
                    </span>
                </div>
                <div>
                    <p class="text-[10px] font-black text-[#669BBC] uppercase tracking-widest mb-1">Monto Congelado</p>
                    <p class="text-2xl font-black text-[#003049]">${{ isset($inmueble) ? number_format($inmueble->renta_mensual + ($inmueble->deposito ?? 0), 2) : '0.00' }} MXN</p>
                </div>
            </div>

            {{-- Barra de próximos pasos --}}
            <div class="pt-8 border-t border-gray-100">
                <p class="text-xs font-black text-[#669BBC] uppercase tracking-widest mb-4">Próximos Pasos</p>
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 rounded-full bg-[#003049] flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <p class="text-sm font-bold text-[#003049]">Contrato firmado y fondos pre-autorizados</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 rounded-full bg-[#669BBC] flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <p class="text-sm font-bold text-[#669BBC]">El propietario revisa tu solicitud (max. 24 hrs)</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 rounded-full bg-gray-200 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <p class="text-sm font-medium text-gray-400">Aprobación → cargo definitivo y contrato activo</p>
                    </div>
                </div>
            </div>

            {{-- Botón: Descargar Recibo (no el contrato, que aún está pendiente) --}}
            <div class="pt-6 border-t border-gray-100">
                @if(isset($pago))
                    <a href="{{ route('pagos.descargar_recibo', $pago->id) }}" target="_blank"
                        class="flex items-center justify-center gap-2 w-full bg-[#003049] text-white py-4 rounded-2xl font-black hover:bg-[#002538] transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Descargar Recibo de Pre-autorización
                    </a>
                @else
                    <div class="flex items-center justify-center gap-2 w-full bg-[#003049]/30 text-white py-4 rounded-2xl font-black cursor-not-allowed">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Recibo no disponible
                    </div>
                @endif
                <p class="text-center text-xs text-[#669BBC] mt-2 font-medium">
                    El contrato firmado estará disponible una vez que el propietario apruebe la solicitud.
                </p>
            </div>
        </div>
    </div>

    {{-- Aviso informativo con paleta del proyecto --}}
    <div class="bg-[#FDF0D5] border border-[#669BBC]/30 rounded-2xl p-4 text-sm text-[#003049] font-medium mb-8 text-left flex items-start gap-3 max-w-xl mx-auto">
        <svg class="w-5 h-5 shrink-0 mt-0.5 text-[#669BBC]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span>Si el propietario <strong>rechaza</strong> tu solicitud, los fondos congelados serán <strong>liberados de inmediato</strong> sin ningún cargo ni comisión.</span>
    </div>

    <div class="flex flex-col md:flex-row items-center justify-center gap-6">
        <a href="{{ route('inmuebles.mis_rentas') }}" class="text-[#669BBC] font-black hover:underline uppercase tracking-widest text-sm">Ver Mi Renta</a>
        <span class="hidden md:block h-1 w-1 bg-[#669BBC]/40 rounded-full"></span>
        <a href="{{ route('inicio') }}" class="text-[#003049] font-black hover:underline uppercase tracking-widest text-sm">Volver al Inicio</a>
    </div>
</div>
@endsection
