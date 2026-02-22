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
            <div class="h-12 w-12 bg-white rounded-full flex items-center justify-center text-2xl shadow-sm">💰</div>
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
                        🏠
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
                                <div class="h-10 w-10 rounded-xl bg-green-50 flex items-center justify-center text-lg">✅</div>
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
                                <div class="h-10 w-10 rounded-xl bg-green-50 flex items-center justify-center text-lg">✅</div>
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
