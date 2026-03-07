@extends('layouts.app')

@section('title', 'Mis Pagos - ArrendaOco')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6">
        <div>
            <h1 class="text-4xl font-black text-[#003049] mb-2 tracking-tight">Mis Pagos y Rentas</h1>
            <p class="text-gray-500 font-medium">Gestiona tus mensualidades de forma segura y transparente.</p>
        </div>
        <div class="bg-[#FDF0D5] px-6 py-4 rounded-3xl border-2 border-[#669BBC]/20 flex items-center gap-4">
            <div class="h-12 w-12 bg-white rounded-full flex items-center justify-center text-2xl shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-yellow-500">
                    <path d="M10.464 8.746c.227-.18.497-.311.786-.394v2.795a2.252 2.252 0 01-.786-.393c-.394-.313-.546-.681-.546-1.004 0-.323.152-.691.546-1.004zM12.75 15.662v-2.824c.347.085.664.228.921.421.427.32.579.686.579.991 0 .305-.152.671-.579.991a2.534 2.534 0 01-.921.42z" />
                    <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12.75 6a.75.75 0 00-1.5 0v.816a3.836 3.836 0 00-1.72.756c-.712.566-1.112 1.35-1.112 2.178 0 .829.4 1.612 1.113 2.178.502.4 1.102.647 1.719.756v2.978a2.536 2.536 0 01-.921-.421l-.879-.66a.75.75 0 00-.9 1.2l.879.66c.533.4 1.169.645 1.821.75V18a.75.75 0 001.5 0v-.81a4.124 4.124 0 001.821-.749c.745-.559 1.179-1.344 1.179-2.191 0-.847-.434-1.632-1.179-2.191a4.122 4.122 0 00-1.821-.75V8.354c.29.082.559.213.786.393l.415.33a.75.75 0 00.933-1.175l-.415-.33a3.836 3.836 0 00-1.719-.755V6z" clip-rule="evenodd" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold text-[#669BBC] uppercase tracking-wider">Próximo Vencimiento</p>
                <p class="text-xl font-black text-[#003049]">05 de Marzo, 2026</p>
            </div>
        </div>
    </div>

    <!-- Sección de Pagos Pendientes -->
    <div class="mb-16">
        <div class="flex items-center gap-3 mb-6">
            <div class="h-8 w-1 bg-[#C1121F] rounded-full"></div>
            <h2 class="text-2xl font-bold text-[#003049]">Pendientes por Pagar</h2>
        </div>

        <div class="grid grid-cols-1 gap-6">
            <!-- Card de Pago Pendiente -->
            <div class="bg-white rounded-[2.5rem] p-8 shadow-xl shadow-gray-200/50 border border-gray-100 flex flex-col md:flex-row items-center justify-between gap-8 group hover:border-[#669BBC]/30 transition-all duration-300">
                <div class="flex items-center gap-6 w-full md:w-auto">
                    <div class="h-20 w-20 rounded-3xl bg-[#FDF0D5] flex items-center justify-center text-3xl shadow-inner group-hover:scale-105 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-8 h-8 text-[#003049]">
                            <path fill-rule="evenodd" d="M9.293 2.293a1 1 0 011.414 0l7 7A1 1 0 0117 11h-1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-3a1 1 0 00-1-1H9a1 1 0 00-1 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-6H3a1 1 0 01-.707-1.707l7-7z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-[#003049] mb-1">Renta Marzo 2026</h3>
                        <p class="text-gray-500 text-sm font-medium">Departamento en Real de Ocosingo</p>
                        <div class="flex items-center gap-2 mt-2">
                             <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-[10px] font-black uppercase tracking-widest rounded-full">Pendiente</span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col md:items-end w-full md:w-auto">
                    <p class="text-3xl font-black text-[#003049] mb-1">$4,500.00 <span class="text-sm font-bold text-gray-400">MXN</span></p>
                    <p class="text-xs text-gray-400 font-bold mb-4">Vence en 14 días</p>
                    <a href="{{ route('pagos.test.checkout') }}" class="w-full md:w-auto text-center px-10 py-4 bg-[#003049] text-white font-black rounded-2xl shadow-lg shadow-[#003049]/20 hover:bg-[#002538] hover:-translate-y-1 transition-all active:scale-95">
                        Pagar Ahora
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Historial de Pagos -->
    <div>
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-3">
                <div class="h-8 w-1 bg-[#669BBC] rounded-full"></div>
                <h2 class="text-2xl font-bold text-[#003049]">Historial de Transacciones</h2>
            </div>
            <button class="text-sm font-bold text-[#669BBC] hover:underline">Descargar todo (PDF)</button>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-gray-200/40 border border-gray-100 overflow-hidden">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-[#FDF0D5]/30 border-b border-gray-100">
                        <th class="px-8 py-5 text-xs font-black text-[#669BBC] uppercase tracking-widest">Concepto</th>
                        <th class="px-8 py-5 text-xs font-black text-[#669BBC] uppercase tracking-widest">Fecha</th>
                        <th class="px-8 py-5 text-xs font-black text-[#669BBC] uppercase tracking-widest">Monto</th>
                        <th class="px-8 py-5 text-xs font-black text-[#669BBC] uppercase tracking-widest text-right">Estado / Recibo</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <!-- Fila 1 -->
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="h-10 w-10 rounded-xl bg-green-50 flex items-center justify-center text-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-green-500">
                                        <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-bold text-[#003049]">Renta Febrero 2026</p>
                                    <p class="text-xs text-gray-400">ID: PAY-789234</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-sm font-medium text-gray-600">01 Feb, 2026</td>
                        <td class="px-8 py-6 text-lg font-black text-[#003049]">$4,500.00</td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <span class="px-3 py-1 bg-green-100 text-green-700 text-[10px] font-black uppercase tracking-widest rounded-full">Pagado</span>
                                <button class="p-2 hover:bg-[#669BBC]/10 rounded-lg text-[#669BBC] transition-colors" title="Ver Recibo">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <!-- Fila 2 -->
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="h-10 w-10 rounded-xl bg-green-50 flex items-center justify-center text-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-green-500">
                                        <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-bold text-[#003049]">Renta Enero 2026</p>
                                    <p class="text-xs text-gray-400">ID: PAY-654122</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-sm font-medium text-gray-600">02 Ene, 2026</td>
                        <td class="px-8 py-6 text-lg font-black text-[#003049]">$4,500.00</td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <span class="px-3 py-1 bg-green-100 text-green-700 text-[10px] font-black uppercase tracking-widest rounded-full">Pagado</span>
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
            <div class="p-6 bg-gray-50 flex justify-center">
                <button class="text-sm font-black text-[#003049] uppercase tracking-widest hover:text-[#669BBC] transition-colors">Ver historial completo</button>
            </div>
        </div>
    </div>
</div>
@endsection
