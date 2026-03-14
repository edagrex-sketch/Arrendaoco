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

        @if($contratos->isEmpty())
            <div class="bg-white rounded-3xl p-12 text-center shadow-lg border border-slate-100 flex flex-col items-center">
                <div class="w-24 h-24 bg-blue-50 rounded-full flex items-center justify-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-blue-300" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-[#003049] mb-3">Aún no tienes rentas activas</h2>
                <p class="text-slate-500 max-w-md mx-auto mb-8">Parece que todavía no has rentado ninguna propiedad. ¡Explora
                    las opciones disponibles y encuentra tu próximo hogar!</p>
                <a href="{{ route('welcome') }}"
                    class="bg-[#003049] text-white font-bold py-3 px-8 rounded-full shadow-lg hover:translate-y-[-2px] hover:shadow-xl transition-all inline-flex items-center gap-2">
                    Explorar Propiedades
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>
        @else
            <div class="lg:flex lg:gap-10">
                <!-- Columna Izquierda / Propiedades Rentadas -->
                <div class="lg:w-1/3 mb-10 lg:mb-0">
                    <div class="grid grid-cols-1 gap-8">
                @foreach($contratos as $contrato)
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
                            <div
                                class="absolute top-4 right-4 bg-white/90 backdrop-blur text-[#003049] text-sm font-black px-3 py-1 rounded-full shadow-md">
                                Activa
                            </div>
                        </div>

                        <div class="p-6 flex-1 flex flex-col">
                            <div class="mb-4 flex-1">
                                <h3 class="text-xl font-bold text-[#003049] mb-2 line-clamp-1"
                                    title="{{ $inmueble ? $inmueble->titulo : 'Propiedad no disponible' }}">
                                    {{ $inmueble ? $inmueble->titulo : 'Propiedad no disponible' }}
                                </h3>
                                <p class="text-slate-500 text-sm flex items-start gap-1 mb-4 line-clamp-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0 mt-0.5 text-red-500"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    {{ $inmueble ? $inmueble->direccion : 'Sin dirección' }}
                                </p>

                                <div
                                    class="bg-slate-50 rounded-xl p-4 border border-slate-100 flex items-center justify-between mb-4">
                                    <div>
                                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-1">Renta Mensual</p>
                                        <p class="text-[#003049] font-black text-lg">
                                            ${{ number_format($contrato->renta_mensual, 2) }}</p>
                                    </div>
                                    <div class="w-px h-10 bg-slate-200"></div>
                                    <div>
                                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-1">Día de Pago</p>
                                        <p class="text-[#003049] font-black text-lg text-right">
                                            {{ \Carbon\Carbon::parse($contrato->fecha_inicio)->format('d') }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3 text-sm text-slate-600 border-t border-slate-100 pt-4">
                                    @if($inmueble && $inmueble->propietario)
                                        <div
                                            class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs uppercase overflow-hidden shrink-0">
                                            @if($inmueble->propietario->foto_perfil)
                                                <img src="{{ str_starts_with($inmueble->propietario->foto_perfil, 'http') ? $inmueble->propietario->foto_perfil : (str_contains($inmueble->propietario->foto_perfil, 'storage/') ? asset($inmueble->propietario->foto_perfil) : asset('storage/' . $inmueble->propietario->foto_perfil)) }}"
                                                    alt="Propietario" class="w-full h-full object-cover">
                                            @else
                                                {{ substr($inmueble->propietario->nombre, 0, 2) }}
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs text-slate-400 font-medium leading-none mb-1">Propietario</p>
                                            <p class="font-bold text-slate-800 truncate">{{ $inmueble->propietario->nombre }}</p>
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
                                    @else
                                        <button disabled
                                            class="w-full bg-slate-100 text-slate-400 text-center font-bold py-3 rounded-xl cursor-not-allowed text-sm flex items-center justify-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                            </svg>
                                            Cancelar mi renta
                                        </button>
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
                    <div class="mb-16">
                        <div class="flex items-center gap-3 mb-8">
                            <div class="h-8 w-1 bg-[#C1121F] rounded-full"></div>
                            <h2 class="text-3xl font-black text-[#003049] tracking-tight">Pagos Pendientes</h2>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-6">
                    @foreach($contratos as $contrato)
                        <!-- Card de Pago Pendiente (Ejemplo UI) -->
                        <div class="bg-white rounded-[2.5rem] p-8 shadow-xl shadow-gray-200/50 border border-gray-100 flex flex-col md:flex-row items-center justify-between gap-8 group hover:border-[#669BBC]/30 transition-all duration-300">
                            <div class="flex items-center gap-6 w-full md:w-auto">
                                <div class="h-20 w-20 rounded-3xl bg-[#FDF0D5] flex items-center justify-center text-3xl shadow-inner group-hover:scale-105 transition-transform shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-8 h-8 text-[#003049]">
                                        <path fill-rule="evenodd" d="M9.293 2.293a1 1 0 011.414 0l7 7A1 1 0 0117 11h-1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-3a1 1 0 00-1-1H9a1 1 0 00-1 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-6H3a1 1 0 01-.707-1.707l7-7z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <h3 class="text-xl font-bold text-[#003049] mb-1 line-clamp-1">Renta {{ now()->translatedFormat('F Y') }}</h3>
                                    <p class="text-gray-500 text-sm font-medium line-clamp-1">{{ $contrato->inmueble->titulo ?? 'Propiedad' }}</p>
                                    <div class="flex items-center gap-2 mt-2">
                                        <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-[10px] font-black uppercase tracking-widest rounded-full">Pendiente</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col md:items-end w-full md:w-auto shrink-0 mt-4 md:mt-0">
                                <p class="text-3xl font-black text-[#003049] mb-1">${{ number_format($contrato->renta_mensual, 2) }} <span class="text-sm font-bold text-gray-400">MXN</span></p>
                                @php
                                    $diaPago = \Carbon\Carbon::parse($contrato->fecha_inicio)->day;
                                    $vence = now()->setDay($diaPago);
                                    if (now()->day > $diaPago)
                                        $vence->addMonth();
                                @endphp
                                <p class="text-xs text-gray-400 font-bold mb-4">Vence el {{ $vence->format('d/m/Y') }}</p>
                                <a href="#" class="w-full md:w-auto text-center px-10 py-4 bg-[#003049] text-white font-black rounded-2xl shadow-lg shadow-[#003049]/20 hover:bg-[#002538] hover:-translate-y-1 transition-all active:scale-95">
                                    Pagar Ahora
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Historial de Pagos -->
            <div>
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-3">
                        <div class="h-8 w-1 bg-[#669BBC] rounded-full"></div>
                        <h2 class="text-3xl font-black text-[#003049] tracking-tight">Historial de Transacciones</h2>
                    </div>
                </div>

                <div class="bg-white rounded-[2.5rem] shadow-xl shadow-gray-200/40 border border-gray-100 overflow-hidden overflow-x-auto">
                    <table class="w-full text-left min-w-[700px]">
                        <thead>
                            <tr class="bg-[#FDF0D5]/30 border-b border-gray-100">
                                <th class="px-8 py-5 text-xs font-black text-[#669BBC] uppercase tracking-widest">Concepto</th>
                                <th class="px-8 py-5 text-xs font-black text-[#669BBC] uppercase tracking-widest">Fecha</th>
                                <th class="px-8 py-5 text-xs font-black text-[#669BBC] uppercase tracking-widest">Monto</th>
                                <th class="px-8 py-5 text-xs font-black text-[#669BBC] uppercase tracking-widest text-right">Estado / Recibo</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <!-- Datos Demostrativos Historial -->
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div class="h-10 w-10 rounded-xl bg-[#669BBC]/10 flex items-center justify-center text-lg shrink-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-[#669BBC]">
                                                <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-bold text-[#003049] whitespace-nowrap">Depósito y 1er Mes</p>
                                            <p class="text-xs text-gray-400">Pago Inicial</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-sm font-medium text-gray-600 whitespace-nowrap">{{ $contratos->first()->fecha_inicio ?? now()->format('d/m/Y') }}</td>
                                <td class="px-8 py-6 text-lg font-black text-[#003049] whitespace-nowrap">${{ number_format($contratos->first()->renta_mensual + ($contratos->first()->deposito ?? 0), 2) }}</td>
                                <td class="px-8 py-6 text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        <span class="px-3 py-1 bg-[#669BBC]/20 text-[#003049] text-[10px] font-black uppercase tracking-widest rounded-full">Pagado</span>
                                        <button class="p-2 hover:bg-[#669BBC]/10 rounded-lg text-[#669BBC] transition-colors" title="Ver Recibo">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
@endsection