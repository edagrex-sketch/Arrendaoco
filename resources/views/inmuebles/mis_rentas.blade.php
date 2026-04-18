@extends('layouts.app')

@section('title', 'Mi renta')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6">
            <div>
                <h1 class="text-4xl font-extrabold text-[#003049] tracking-tight">Mi renta</h1>
                <p class="text-muted-foreground mt-2 text-lg">Consulta las propiedades que rentas y gestiona tus mensualidades de forma segura.</p>
            </div>

            @if(!$contratos->isEmpty())
                <div class="bg-[#FDF0D5] px-6 py-4 rounded-3xl border-2 border-[#669BBC]/20 flex items-center gap-4">
                    <div class="h-12 w-12 bg-white rounded-full flex items-center justify-center text-2xl shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-yellow-500">
                            <path d="M10.464 8.746c.227-.18.497-.311.786-.394v2.795a2.252 2.252 0 01-.786-.393c-.394-.313-.546-.681-.546-1.004 0-.323.152-.691.546-1.004zM12.75 15.662v-2.824c.347.085.664.228.921.421.427.32.579.686.579.991 0 .305-.152.671-.579.991a2.534 2.534 0 01-.921.42z" />
                            <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12.75 6a.75.75 0 00-1.5 0v.816a3.836 3.836 0 00-1.72.756c-.712.566-1.112 1.35-1.112 2.178 0 .829.4 1.612 1.113 2.178.502.4 1.102.647 1.719.756v2.978a2.536 2.536 0 01-.921-.421l-.879-.66a.75.75 0 00-.9 1.2l.879.66c.533.4 1.169.645 1.821.75V18a.75.75 0 001.5 0v-.81a4.124 4.124 0 001.821-.749c.745-.559 1.179-1.344 1.179-2.191 0-.847-.434-1.632-1.179-2.191a4.122 4.122 0 00-1.821-.75V8.354c.29.082.559.213.786.393l.415.33a.75.75 0 00.933-1.175l-.415-.33a3.836 3.836 0 00-1.719-.755V6z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-[#669BBC] uppercase tracking-wider">Próximo Vencimiento</p>
                        @php
                            $proximoContrato = $contratos->first();
                            $diaPago = \Carbon\Carbon::parse($proximoContrato->fecha_inicio)->day;
                            $mesAct = now()->month;
                            $fechaVencimiento = now()->setDay($diaPago);
                            if (now()->day > $diaPago) {
                                $fechaVencimiento->addMonth();
                            }
                        @endphp
                        <p class="text-xl font-black text-[#003049]">{{ $fechaVencimiento->translatedFormat('d \d\e F, Y') }}</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- ===== AVISO DE RECHAZO (aparece solo una vez) ===== --}}
        @if(isset($contratoRechazado) && $contratoRechazado)
        <div
            x-data="{ visible: true }"
            x-show="visible"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-4"
            class="mb-8 bg-white border-2 border-[#C1121F]/30 rounded-3xl overflow-hidden shadow-lg"
        >
            <div class="bg-[#C1121F] px-6 py-4 flex items-center gap-3">
                <div class="h-10 w-10 bg-white/20 rounded-xl flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="font-black text-white text-base">Solicitud rechazada</p>
                    <p class="text-white/80 text-xs font-medium">El propietario no ha aceptado tu solicitud de renta.</p>
                </div>
                <button @click="visible = false" class="text-white/70 hover:text-white transition-colors ml-auto shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="px-6 py-5 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="h-16 w-16 rounded-2xl overflow-hidden bg-slate-100 shrink-0">
                        @if(optional($contratoRechazado->inmueble)->imagen)
                            <img src="{{ str_starts_with($contratoRechazado->inmueble->imagen, 'http') ? $contratoRechazado->inmueble->imagen : asset('storage/'.$contratoRechazado->inmueble->imagen) }}"
                                class="w-full h-full object-cover" alt="">
                        @endif
                    </div>
                    <div>
                        <p class="font-bold text-[#003049] text-base">{{ optional($contratoRechazado->inmueble)->titulo ?? 'Propiedad' }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">Renta: ${{ number_format($contratoRechazado->renta_mensual, 2) }}/mes · Inicio solicitado: {{ \Carbon\Carbon::parse($contratoRechazado->fecha_inicio)->format('d/m/Y') }}</p>
                        <p class="text-xs text-gray-400 mt-1">Tus fondos pre-autorizados han sido <strong class="text-[#003049]">liberados</strong>. No se realizó ningún cargo.</p>
                    </div>
                </div>
                <div class="flex gap-3 shrink-0">
                    <button @click="visible = false"
                        class="text-sm font-bold text-gray-400 hover:text-gray-600 transition-colors px-4 py-2">
                        Descartar
                    </button>
                    <a href="{{ route('inicio') }}"
                        class="inline-flex items-center gap-2 bg-[#003049] text-white font-bold px-6 py-3 rounded-2xl shadow-lg shadow-[#003049]/20 hover:bg-[#002236] hover:-translate-y-0.5 transition-all text-sm">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Seguir buscando
                    </a>
                </div>
            </div>
        </div>
        @endif

        {{-- ===== AVISO DE CONTRATO ACEPTADO (Nuevo) ===== --}}
        @php
            $nuevoContratoAceptado = $contratos->where('estatus', 'activo')->where('updated_at', '>', now()->subHours(24))->first();
            $primerPago = $nuevoContratoAceptado ? \App\Models\Pago::where('contrato_id', $nuevoContratoAceptado->id)->where('estatus', 'pagado')->first() : null;
        @endphp
        
        @if($nuevoContratoAceptado)
            <div class="mb-10 bg-white rounded-[3rem] p-10 shadow-2xl shadow-[#669BBC]/10 border-2 border-[#669BBC]/20 relative overflow-hidden group">
                {{-- Decoración: ROCO o un círculo --}}
                <div class="absolute top-0 right-0 h-40 w-40 bg-[#FDF0D5] rounded-bl-[100px] -mr-10 -mt-10 opacity-60 group-hover:scale-110 transition-transform duration-700"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row items-center gap-8 text-center md:text-left">
                    <div class="h-24 w-24 bg-[#003049] rounded-3xl flex items-center justify-center shrink-0 shadow-xl shadow-[#003049]/20 rotate-3">
                         <svg class="w-12 h-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-3xl font-black text-[#003049] mb-2 leading-tight">¡Enhorabuena! Tu renta ha sido confirmada.</h2>
                        <p class="text-[#669BBC] font-bold text-lg mb-6">El propietario ha firmado el contrato y tu nuevo hogar te espera.</p>
                        
                        <div class="flex flex-wrap gap-4 justify-center md:justify-start">
                            {{-- Botones de Descarga --}}
                            @if($nuevoContratoAceptado->archivo_firmado)
                                <a href="{{ str_starts_with($nuevoContratoAceptado->archivo_firmado, 'http') ? $nuevoContratoAceptado->archivo_firmado : asset('storage/' . $nuevoContratoAceptado->archivo_firmado) }}" target="_blank" class="inline-flex items-center gap-2 bg-[#003049] text-white px-5 py-2.5 rounded-2xl font-black text-sm hover:bg-[#669BBC] transition-colors shadow-lg shadow-[#003049]/10">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                    Ver Contrato Firmado
                                </a>
                            @else
                                <a href="{{ route('contratos.descargar', $nuevoContratoAceptado->id) }}" class="inline-flex items-center gap-2 bg-[#003049] text-white px-5 py-2.5 rounded-2xl font-black text-sm hover:bg-[#669BBC] transition-colors shadow-lg shadow-[#003049]/10">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                    Descargar Contrato Base
                                </a>
                            @endif
                            @if($primerPago)
                                <a href="{{ route('pagos.descargar_recibo', $primerPago->id) }}" class="inline-flex items-center gap-2 bg-[#FDF0D5] text-[#003049] px-5 py-2.5 rounded-2xl font-black text-sm border border-[#669BBC]/20 hover:bg-white transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                    Descargar Recibo
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-between flex-wrap gap-4">
                    <p class="text-sm text-gray-400 italic font-medium max-w-lg">**ROCO** está feliz de verte aquí. Ahora puedes realizar más acciones como reportar fallos o chatear con tu casero. **¡Que disfrutes de ArrendaOco!**</p>
                    <a href="{{ route('chats.index') }}" class="bg-[#003049] text-white px-8 py-3 rounded-2xl font-black shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all inline-flex items-center gap-2">
                        Enviar un mensaje al dueño
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                    </a>
                </div>
            </div>
        @endif

        @if($contratos->isEmpty())
            <div class="bg-white rounded-3xl p-12 text-center shadow-lg border border-slate-100 flex flex-col items-center">
                <div class="w-24 h-24 bg-[#FDF0D5] rounded-full flex items-center justify-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-[#669BBC]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-[#003049] mb-3">Aún no tienes rentas activas</h2>
                <p class="text-slate-500 max-w-md mx-auto mb-8">
                    Explora las propiedades disponibles y encuentra tu próximo hogar.
                </p>
                <a href="{{ route('inicio') }}"
                    class="bg-[#003049] text-white font-bold py-3 px-8 rounded-2xl shadow-lg hover:-translate-y-1 hover:shadow-xl hover:bg-[#002236] transition-all inline-flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Seguir buscando
                </a>
            </div>
        @else

            <div class="lg:flex lg:gap-10">
                <!-- Columna Izquierda / Propiedades Rentadas -->
                <div class="lg:w-1/3 mb-10 lg:mb-0">
                    <div class="grid grid-cols-1 gap-8">
                @foreach($contratos->unique('inmueble_id') as $contrato)
                    @php $inmueble = $contrato->inmueble; @endphp
                    <div
                        class="bg-white rounded-3xl overflow-hidden shadow-lg border border-slate-100 transition-all hover:-translate-y-2 hover:shadow-xl group flex flex-col">
                        <div class="relative h-48 bg-slate-200">
                            @if ($inmueble && $inmueble->imagen)
                                <img src="{{ str_starts_with($inmueble->imagen, 'http') ? $inmueble->imagen : (str_contains($inmueble->imagen, 'storage/') ? asset($inmueble->imagen) : asset('storage/' . $inmueble->imagen)) }}" alt="{{ $inmueble->titulo }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                            @if(isset($contrato) && $contrato->estatus === 'activo')
                                <div class="absolute top-4 right-4 bg-white/90 backdrop-blur text-green-700 text-sm font-black px-3 py-1 rounded-full shadow-md border border-green-200">
                                    ✓ Activa
                                </div>
                            @elseif(isset($contrato) && $contrato->estatus === 'pendiente_aprobacion')
                                <div class="absolute top-4 right-4 bg-amber-100/90 backdrop-blur text-amber-700 text-sm font-black px-3 py-1 rounded-full shadow-md flex items-center gap-1 border border-amber-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415l-2.414-2.414V6z" clip-rule="evenodd" /></svg>
                                    Pendiente aprobación
                                </div>
                            @elseif(isset($contrato) && $contrato->estatus === 'pdf_descargado')
                                <div class="absolute top-4 right-4 bg-[#003049]/90 backdrop-blur text-white text-sm font-black px-3 py-1 rounded-full shadow-md flex items-center gap-1 border border-[#669BBC]">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#FDF0D5]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    Solicitud Aprobada
                                </div>
                            @else
                                <div class="absolute top-4 right-4 bg-red-100/90 backdrop-blur text-red-700 text-sm font-black px-3 py-1 rounded-full shadow-md border border-red-200">
                                    {{ ucfirst($contrato->estatus) }}
                                </div>
                            @endif
                        </div>

                        <div class="p-6 flex-1 flex flex-col">
                            <div class="mb-4 flex-1">
                                <h3 class="text-xl font-bold text-[#003049] mb-2 line-clamp-1"
                                    title="{{ $inmueble ? $inmueble->titulo : 'Propiedad no disponible' }}">
                                    {{ $inmueble ? $inmueble->titulo : 'Propiedad no disponible' }}
                                </h3>
                                <p class="text-slate-500 text-sm flex items-start gap-1 mb-2 line-clamp-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0 mt-0.5 text-red-500"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    {{ $inmueble ? $inmueble->direccion : 'Sin dirección' }}
                                </p>
                                
                                @if(isset($contrato) && $contrato->estatus === 'pendiente_aprobacion')
                                    <div class="mb-4 bg-amber-50 rounded-xl p-4 border border-amber-200">
                                        <p class="text-sm text-amber-700 font-bold mb-1 flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                            En espera de confirmación
                                        </p>
                                        <p class="text-xs text-amber-600 mt-1">El propietario ha sido notificado. Cuando confirme la renta, aparecerá aquí la opción para descargar el contrato físico.</p>
                                    </div>
                                @elseif(isset($contrato) && $contrato->estatus === 'pdf_descargado')
                                    <div class="mb-4 bg-[#FDF0D5] rounded-xl p-4 shadow-sm">
                                        <p class="text-sm text-[#003049] font-black mb-2 flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#669BBC]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            ¡Solicitud Aprobada!
                                        </p>
                                        <p class="text-xs text-[#003049] mt-1 mb-3 font-medium">Descarga tu contrato, imprímelo y llévalo a tu cita con el propietario para firmarlo físicamente.</p>
                                        <a href="{{ route('contratos.descargar-registrar', $contrato) }}" target="_blank" class="w-full bg-[#003049] text-white text-center font-bold py-2 px-4 rounded-xl flex items-center justify-center gap-2 hover:bg-[#002236] transition-colors shadow-md text-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                            </svg>
                                            Descargar Contrato PDF
                                        </a>
                                    </div>
                                @else
                                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-100 flex flex-col justify-between mb-4 gap-2">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">Renta Mensual</p>
                                                <p class="text-[#003049] font-black text-lg">
                                                    ${{ number_format($contrato->renta_mensual, 2) }}</p>
                                            </div>
                                            <div class="w-px h-10 bg-slate-200 mx-2"></div>
                                            <div>
                                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1 text-right">Día de Pago</p>
                                                <p class="text-[#003049] font-black text-lg text-right">
                                                    {{ \Carbon\Carbon::parse($contrato->fecha_inicio)->format('d') }}</p>
                                            </div>
                                        </div>
                                        <div class="border-t border-slate-200 pt-2 flex items-center justify-between">
                                            <div>
                                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">Inicio Renta</p>
                                                <p class="text-xs text-slate-600 font-bold">
                                                    {{ \Carbon\Carbon::parse($contrato->fecha_inicio)->format('d/m/Y') }}
                                                </p>
                                            </div>
                                            <div>
                                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1 text-right">Fin Renta</p>
                                                <p class="text-xs text-[#003049] font-black text-right lowercase">
                                                    @php
                                                        $fechaFinCalculada = null;
                                                        if ($contrato->fecha_inicio && $contrato->plazo) {
                                                            try {
                                                                $inicio = \Carbon\Carbon::parse($contrato->fecha_inicio);
                                                                $plazo = strtolower($contrato->plazo);
                                                                $cantidad = (int) filter_var($plazo, FILTER_SANITIZE_NUMBER_INT);
                                                                
                                                                if (str_contains($plazo, 'año')) {
                                                                    $fechaFinCalculada = $inicio->addYears($cantidad ?: 1);
                                                                } elseif (str_contains($plazo, 'mes')) {
                                                                    $fechaFinCalculada = $inicio->addMonths($cantidad ?: 1);
                                                                }
                                                            } catch (\Exception $e) {
                                                                $fechaFinCalculada = null;
                                                            }
                                                        }
                                                    @endphp
                                                    {{ $contrato->fecha_fin ? \Carbon\Carbon::parse($contrato->fecha_fin)->format('d/m/Y') : ($fechaFinCalculada ? $fechaFinCalculada->format('d/m/Y') : 'Indefinido') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="flex items-center gap-3 text-sm text-slate-600 border-t border-slate-100 pt-4">
                                    @php
                                        $esPropietario = $contrato->propietario_id === auth()->id();
                                        $otraParte = $esPropietario ? $contrato->inquilino : ($inmueble ? $inmueble->propietario : null);
                                    @endphp
                                    @if($otraParte)
                                        <div
                                            class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs uppercase overflow-hidden shrink-0">
                                            @if($otraParte->foto_perfil)
                                                <img src="{{ str_starts_with($otraParte->foto_perfil, 'http') ? $otraParte->foto_perfil : (str_contains($otraParte->foto_perfil, 'storage/') ? asset($otraParte->foto_perfil) : asset('storage/' . $otraParte->foto_perfil)) }}"
                                                    alt="Usuario" class="w-full h-full object-cover">
                                            @else
                                                {{ substr($otraParte->nombre, 0, 2) }}
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs text-slate-400 font-medium leading-none mb-1">{{ $esPropietario ? 'Inquilino' : 'Propietario' }}</p>
                                            <p class="font-bold text-slate-800 truncate">{{ $otraParte->nombre }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="flex flex-col gap-2 mt-auto">
                                @if($inmueble)
                                    <a href="{{ route('inmuebles.show', $inmueble) }}"
                                        class="w-full bg-[#003049] text-white text-center font-bold py-3 rounded-xl hover:bg-[#003049]/90 transition-colors">
                                        Ver Propiedad
                                    </a>
                                    @if($inmueble->contrato_documento)
                                        <a href="{{ str_contains($inmueble->contrato_documento, 'storage/') ? asset($inmueble->contrato_documento) : asset('storage/' . $inmueble->contrato_documento) }}" target="_blank"
                                            class="w-full bg-[#FDF0D5] text-[#003049] text-center font-bold py-3 rounded-xl flex items-center justify-center gap-2 hover:bg-[#FDF0D5]/80 transition-colors shadow-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            Ver Contrato
                                        </a>
                                    @endif

                                    @php
                                        $estatusCancelable = ['activo', 'pendiente_aprobacion', 'pendiente', 'disponible', 'pdf_descargado'];
                                        $labelCancelar = match($contrato->estatus) {
                                            'pendiente_aprobacion' => 'Retirar solicitud',
                                            'disponible', 'pdf_descargado' => 'Cancelar solicitud',
                                            default => 'Cancelar mi renta',
                                        };
                                        $mensajeCancelar = in_array($contrato->estatus, ['pendiente_aprobacion', 'disponible', 'pdf_descargado'])
                                            ? '¿Cancelar esta solicitud de renta? El inmueble quedará disponible nuevamente.'
                                            : '¿Estás seguro de que deseas cancelar tu renta? El inmueble quedará disponible nuevamente.';
                                    @endphp
                                    @if(in_array($contrato->estatus, $estatusCancelable))
                                        <form action="{{ route('rentas.cancelar', $contrato) }}" method="POST" class="w-full">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('{{ $mensajeCancelar }}')"
                                                class="w-full bg-red-50 text-red-500 hover:text-white hover:bg-red-500 text-center font-bold py-3 rounded-xl transition-colors text-sm flex items-center justify-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                                {{ $labelCancelar }}
                                            </button>
                                        </form>
                                    @else
                                        <div class="w-full bg-slate-100 text-slate-400 text-center font-bold py-3 rounded-xl cursor-not-allowed text-sm flex items-center justify-center gap-2">
                                            {{ $contrato->estatus === 'rechazado' ? 'Solicitud rechazada' : 'Renta cancelada/finalizada' }}
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                </div>

                <!-- Columna Derecha / Pagos y Transacciones -->
                <div class="lg:w-2/3">
                    
                    <!-- Sección de Pagos Pendientes -->
                    <div class="mb-14">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-1.5 bg-[#C1121F] rounded-full"></div>
                                <h2 class="text-2xl font-black text-[#003049] tracking-tight">Pagos Pendientes</h2>
                            </div>
                            @if($pagosPendientes->count() > 0)
                                <div class="bg-red-100 text-red-700 text-xs font-bold px-3 py-1 rounded-full border border-red-200">
                                    {{ $pagosPendientes->count() }} por pagar
                                </div>
                            @endif
                        </div>
                        
                        <div class="grid grid-cols-1 gap-5">
                            @forelse($pagosPendientes as $pago)
                                <!-- Card de Pago Pendiente -->
                                <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 relative overflow-hidden group hover:shadow-md transition-shadow">
                                    <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-[#C1121F]"></div>
                                    
                                    <div class="flex items-center gap-5 w-full sm:w-auto pl-2">
                                        <div class="h-16 w-16 rounded-2xl bg-red-50 flex items-center justify-center text-red-500 shrink-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div class="min-w-0">
                                            <h3 class="text-lg font-bold text-[#003049] mb-0.5 line-clamp-1">{{ $pago->concepto }}</h3>
                                            <p class="text-slate-500 text-sm mb-2 line-clamp-1 truncatemax-w-[200px]">{{ $pago->contrato->inmueble->titulo ?? 'Propiedad' }}</p>
                                            
                                            @php
                                                $diaPago = \Carbon\Carbon::parse($pago->contrato->fecha_inicio)->day;
                                                $vence = \Carbon\Carbon::create($pago->anio, $pago->mes, $diaPago);
                                                if (now()->day > $diaPago && $pago->mes == now()->month && $pago->anio == now()->year) {
                                                    $vence->addMonth(); // Fallback date si ya pasó este mes
                                                }
                                                $diasRestantes = now()->startOfDay()->diffInDays($vence, false);
                                            @endphp
                                            
                                            <div class="flex items-center gap-2">
                                                <span class="px-2.5 py-1 bg-red-100/80 text-red-700 text-[10px] font-black uppercase tracking-widest rounded-lg border border-red-200">Pendiente</span>
                                                <span class="text-xs font-semibold {{ $diasRestantes < 0 ? 'text-red-500' : 'text-slate-500' }}">
                                                    {{ $diasRestantes < 0 ? 'Atrasado' : 'Vence el ' . $vence->format('d M, Y') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex flex-col sm:items-end w-full sm:w-auto shrink-0 border-t sm:border-t-0 sm:border-l border-slate-100 pt-4 sm:pt-0 sm:pl-6">
                                        <p class="text-sm font-semibold text-slate-400 mb-1">Monto a pagar</p>
                                        <p class="text-2xl font-black text-[#003049] mb-4">${{ number_format($pago->monto, 2) }}</p>
                                        
                                        <form action="{{ route('pagos.stripe.mensualidad', $pago->contrato) }}" method="POST" class="w-full">
                                            @csrf
                                            <button type="submit" class="w-full text-center px-6 py-2.5 bg-[#003049] text-white font-bold rounded-xl shadow-lg shadow-[#003049]/20 hover:bg-[#002538] hover:-translate-y-0.5 transition-all outline-none">
                                                Pagar Ahora
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="bg-white rounded-3xl p-10 text-center shadow-sm border border-slate-100 flex flex-col items-center justify-center">
                                    <div class="w-20 h-20 bg-green-50 rounded-full flex items-center justify-center mb-5 ring-8 ring-green-50/50">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-[#003049] mb-2">¡Todo al día!</h3>
                                    <p class="text-slate-500 font-medium">No tienes ningún pago pendiente en este momento.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Historial de Pagos -->
                    <div>
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-1.5 bg-[#669BBC] rounded-full"></div>
                                <h2 class="text-2xl font-black text-[#003049] tracking-tight">Historial de Transacciones</h2>
                            </div>
                        </div>

                        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                            <ul class="divide-y divide-slate-100">
                                @php
                                    $todosLosPagos = collect();
                                    foreach($contratos as $c) {
                                        // 1. Pago Inicial mock (basado en el inicio del contrato)
                                        $todosLosPagos->push((object)[
                                            'id_ref' => 'init_' . $c->id,
                                            'concepto' => 'Depósito y 1er Mes',
                                            'subconcepto' => $c->inmueble->titulo ?? 'N/A',
                                            'fecha' => \Carbon\Carbon::parse($c->fecha_inicio),
                                            'monto' => $c->renta_mensual + ($c->deposito ?? 0),
                                            'estatus' => 'Pagado',
                                            'es_inicial' => true,
                                            'recibo_url' => null
                                        ]);
                                        
                                        // 2. Pagos reales
                                        if ($c->pagos) {
                                            foreach($c->pagos as $p) {
                                                // Mostrar solo historial (no los pendientes, esos ya están arriba)
                                                if (strtolower($p->estatus) !== 'pendiente') {
                                                    $todosLosPagos->push((object)[
                                                        'id_ref' => 'pago_' . $p->id,
                                                        'concepto' => current(explode(' ', $p->concepto ?? 'Mensualidad')) . ' ' . str_pad($p->mes ?? '', 2, '0', STR_PAD_LEFT) . '/' . ($p->anio ?? ''),
                                                        'subconcepto' => $c->inmueble->titulo ?? 'N/A',
                                                        'fecha' => \Carbon\Carbon::parse($p->fecha_pago ?? $p->created_at),
                                                        'monto' => $p->total_con_recargo ?? $p->monto,
                                                        'estatus' => $p->estatus,
                                                        'es_inicial' => false,
                                                        'recibo_url' => route('pagos.descargar_recibo', $p->id)
                                                    ]);
                                                }
                                            }
                                        }
                                    }
                                    $todosLosPagos = $todosLosPagos->sortByDesc('fecha');
                                @endphp

                                @forelse($todosLosPagos as $pago)
                                    <li class="p-6 sm:px-8 hover:bg-slate-50/70 transition-colors flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                        <div class="flex items-center gap-4">
                                            <div class="h-12 w-12 rounded-[14px] {{ strtolower($pago->estatus) === 'pagado' ? 'bg-[#669BBC]/10 text-[#669BBC]' : 'bg-slate-100 text-slate-500' }} flex items-center justify-center shrink-0">
                                                @if(strtolower($pago->estatus) === 'pagado')
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                                @endif
                                            </div>
                                            <div>
                                                <p class="font-bold text-[#003049] text-base leading-snug">{{ $pago->concepto }}</p>
                                                <p class="text-sm text-slate-500 flex items-center gap-1.5 mt-0.5">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                                                    <span class="line-clamp-1">{{ $pago->subconcepto }}</span>
                                                </p>
                                                <p class="text-[11px] font-bold text-slate-400 mt-1 uppercase tracking-widest sm:hidden">{{ $pago->fecha->format('d M, Y') }}</p>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-center justify-between sm:justify-end gap-6 w-full sm:w-auto mt-2 sm:mt-0">
                                            <div class="text-left sm:text-right hidden sm:block">
                                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">{{ $pago->fecha->format('d M, Y') }}</p>
                                                <span class="px-2.5 py-1 text-[10px] font-black uppercase tracking-widest rounded-lg {{ strtolower($pago->estatus) === 'pagado' ? 'bg-[#669BBC]/10 text-[#003049]' : 'bg-slate-100 text-slate-600' }}">
                                                    {{ ucfirst($pago->estatus) }}
                                                </span>
                                            </div>
                                            
                                            <!-- En móvil mostrar monto y estado diferente -->
                                            <div class="sm:hidden text-left">
                                                <span class="px-2.5 py-1 text-[10px] font-black uppercase tracking-widest rounded-lg {{ strtolower($pago->estatus) === 'pagado' ? 'bg-[#669BBC]/10 text-[#003049]' : 'bg-slate-100 text-slate-600' }}">
                                                    {{ ucfirst($pago->estatus) }}
                                                </span>
                                            </div>
                                            
                                            <div class="flex flex-col sm:flex-row items-end sm:items-center gap-4 border-l border-slate-100 sm:pl-6 shrink-0">
                                                <p class="font-black text-xl text-[#003049]">${{ number_format($pago->monto, 2) }}</p>
                                                @if($pago->recibo_url)
                                                    <a href="{{ $pago->recibo_url }}" title="Descargar Recibo" class="h-10 w-10 rounded-full bg-slate-50 hover:bg-[#669BBC] text-slate-400 hover:text-white flex items-center justify-center transition-colors">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                                    </a>
                                                @else
                                                    <div class="h-10 w-10 hidden sm:block"></div>
                                                @endif
                                            </div>
                                        </div>
                                    </li>
                                @empty
                                    <li class="p-10 text-center">
                                        <div class="h-16 w-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <p class="text-slate-500 font-medium">Aún no hay transacciones en tu historial.</p>
                                    </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        @endif
    </div>
@endsection