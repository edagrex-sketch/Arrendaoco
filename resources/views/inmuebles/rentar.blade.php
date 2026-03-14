@extends('layouts.app')

@section('title', 'Rentar ' . $inmueble->titulo . ' - ArrendaOco')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-8 lg:py-16">
        <div class="flex flex-col lg:flex-row gap-12">

            <!-- Columna Izquierda: Métodos de Pago -->
            <div class="flex-1">
                <a href="{{ route('inmuebles.show', $inmueble) }}"
                    class="inline-flex items-center gap-2 text-[#669BBC] font-bold text-sm mb-8 hover:-translate-x-1 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Volver a la Propiedad
                </a>

                <h1 class="text-4xl lg:text-5xl font-black text-[#003049] mb-4 tracking-tight">Finaliza tu Renta</h1>
                <p class="text-gray-500 font-medium mb-10 text-lg">Selecciona tu forma de pago preferida para reservar esta
                    propiedad. Todas las transacciones están cifradas y protegidas.</p>

                <div class="space-y-4" x-data="{ method: 'card' }">
                    <!-- Opción 1: Tarjeta -->
                    <div @click="method = 'card'"
                        :class="method === 'card' ? 'border-[#003049] bg-[#003049]/5 ring-2 ring-[#003049]/20' : 'border-gray-200 hover:border-gray-300 bg-white'"
                        class="p-6 md:p-8 border-2 rounded-3xl cursor-pointer transition-all duration-300 relative">
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex items-center gap-5">
                                <div
                                    class="h-16 w-16 bg-white rounded-2xl flex items-center justify-center text-2xl shadow-md border border-gray-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                        class="w-8 h-8 text-blue-600">
                                        <path d="M4.5 3.75a3 3 0 00-3 3v.75h21v-.75a3 3 0 00-3-3h-15z" />
                                        <path fill-rule="evenodd"
                                            d="M22.5 9.75h-21v7.5a3 3 0 003 3h15a3 3 0 003-3v-7.5zm-18 3.75a.75.75 0 01.75-.75h6a.75.75 0 010 1.5h-6a.75.75 0 01-.75-.75zm.75 2.25a.75.75 0 000 1.5h3a.75.75 0 000-1.5h-3z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-black text-[#003049] text-xl">Tarjeta de Crédito / Débito</p>
                                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Visa, Mastercard,
                                        American Exp.</p>
                                </div>
                            </div>
                            <div :class="method === 'card' ? 'border-[6px] border-[#003049]' : 'border-2 border-gray-300'"
                                class="h-8 w-8 rounded-full transition-all"></div>
                        </div>

                        <!-- Detalles del Formulario (Solo visual) -->
                        <div x-show="method === 'card'" x-transition class="mt-8 pt-8 border-t border-[#003049]/10">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div class="space-y-2">
                                    <label
                                        class="text-[11px] font-black text-[#003049] uppercase tracking-widest pl-2">Número
                                        de Tarjeta</label>
                                    <input type="text" placeholder="**** **** **** ****"
                                        class="w-full bg-white border-2 border-gray-200 rounded-2xl px-5 py-4 focus:border-[#003049] focus:ring-0 transition-colors placeholder-gray-300 font-medium">
                                </div>
                                <div class="grid grid-cols-2 gap-5">
                                    <div class="space-y-2">
                                        <label
                                            class="text-[11px] font-black text-[#003049] uppercase tracking-widest pl-2">Expiración</label>
                                        <input type="text" placeholder="MM/YY"
                                            class="w-full bg-white border-2 border-gray-200 rounded-2xl px-5 py-4 focus:border-[#003049] focus:ring-0 transition-colors placeholder-gray-300 font-medium">
                                    </div>
                                    <div class="space-y-2">
                                        <label
                                            class="text-[11px] font-black text-[#003049] uppercase tracking-widest pl-2">CVC</label>
                                        <input type="text" placeholder="123"
                                            class="w-full bg-white border-2 border-gray-200 rounded-2xl px-5 py-4 focus:border-[#003049] focus:ring-0 transition-colors placeholder-gray-300 font-medium">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Opción 3: Efectivo (OXXO) -->
                    <div @click="method = 'oxxo'"
                        :class="method === 'oxxo' ? 'border-[#003049] bg-[#003049]/5 ring-2 ring-[#003049]/20' : 'border-gray-200 hover:border-gray-300 bg-white'"
                        class="p-6 md:p-8 border-2 rounded-3xl cursor-pointer transition-all duration-300">
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex items-center gap-5">
                                <div
                                    class="h-16 w-16 bg-white rounded-2xl flex items-center justify-center text-2xl shadow-md border border-gray-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                        class="w-8 h-8 text-orange-500">
                                        <path fill-rule="evenodd"
                                            d="M3 6a3 3 0 013-3h12a3 3 0 013 3v2.246a3 3 0 01-1.39.063l-2-.416a1.5 1.5 0 00-1.22.42V10.5a.75.75 0 01-1.5 0V8.316a1.5 1.5 0 00-1.22-.42l-2 .416A3 3 0 019 8.246v2.254a.75.75 0 01-1.5 0V8.246a3 3 0 01-1.39-.063l-2-.416a1.5 1.5 0 00-1.22.42V10.5a.75.75 0 01-1.5 0V6zm0 6h18v6.75A2.25 2.25 0 0118.75 21H5.25A2.25 2.25 0 013 18.75V12z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-black text-[#003049] text-xl">Efectivo (OXXO Pay)</p>
                                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Paga en cualquier
                                        sucursal</p>
                                </div>
                            </div>
                            <div :class="method === 'oxxo' ? 'border-[6px] border-[#003049]' : 'border-2 border-gray-300'"
                                class="h-8 w-8 rounded-full transition-all"></div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Columna Derecha: Resumen de Orden -->
            <div class="w-full lg:w-[420px]">
                <div
                    class="bg-white rounded-[2.5rem] p-8 shadow-2xl shadow-[#003049]/10 border border-gray-100 sticky top-24">
                    <h2 class="text-2xl font-black text-[#003049] mb-8 tracking-tight">Resumen de Renta</h2>

                    <div class="flex items-center gap-4 mb-10 p-4 bg-gray-50 rounded-[2rem] border border-gray-100">
                        <div class="h-24 w-24 bg-gray-200 rounded-2xl shadow-sm overflow-hidden flex-shrink-0">
                            <img src="{{ str_starts_with($inmueble->imagen, 'http') ? $inmueble->imagen : (str_contains($inmueble->imagen, 'storage/') ? asset($inmueble->imagen) : asset('storage/' . $inmueble->imagen)) }}"
                                class="h-full w-full object-cover">
                        </div>
                        <div class="pr-2">
                            <p class="font-black text-[#003049] text-base leading-tight">{{ $inmueble->titulo }}</p>
                            <p class="text-xs text-gray-500 font-bold mt-2">{{ $inmueble->tipo }} <br> Propietario: <span
                                    class="text-[#003049]">{{ $inmueble->propietario->nombre }}</span></p>
                        </div>
                    </div>

                    <div class="space-y-4 mb-8 pt-4 border-t border-gray-100">
                        <div class="flex justify-between items-center text-sm">
                            <p class="text-gray-500 font-bold">1er Mes de Renta</p>
                            <p class="font-black text-[#003049]">${{ number_format($inmueble->renta_mensual, 2) }}</p>
                        </div>
                        @if(isset($inmueble->deposito) && $inmueble->deposito > 0)
                            <div class="flex justify-between items-center text-sm">
                                <p class="text-gray-500 font-bold">Depósito (Reembolsable)</p>
                                <p class="font-black text-[#003049]">${{ number_format($inmueble->deposito, 2) }}</p>
                            </div>
                        @else
                            <div class="flex justify-between items-center text-sm">
                                <p class="text-gray-500 font-bold">Depósito Inicial</p>
                                <p class="font-black text-green-600">No aplica</p>
                            </div>
                        @endif
                        <div class="flex justify-between items-center text-sm">
                            <p class="text-gray-500 font-bold">Comisión ArrendaOco</p>
                            <p class="font-black text-green-600">GRATIS</p>
                        </div>

                        <div class="pt-6 mt-6 border-t border-gray-200 border-dashed flex justify-between items-end">
                            <p class="font-black text-[#003049] uppercase tracking-widest text-sm mb-1">Total</p>
                            <p class="text-4xl font-black text-[#003049] tracking-tighter">
                                ${{ number_format($inmueble->renta_mensual + ($inmueble->deposito ?? 0), 2) }}</p>
                        </div>
                        <p class="text-[10px] text-right text-gray-400 font-bold uppercase tracking-wider mt-1">Suma en
                            Pesos Mexicanos MXN</p>
                    </div>

                    @if($inmueble->contrato_documento)
                        <div class="mb-6 bg-[#003049]/5 p-4 rounded-2xl border border-[#003049]/10">
                            <div class="flex items-center gap-3 mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#003049]" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span class="font-bold text-[#003049] text-sm tracking-tight">Contrato de Arrendamiento</span>
                            </div>
                            <p class="text-xs text-gray-500 mb-3">Revisa los términos del propietario antes de finalizar tu
                                reserva.</p>
                            <a href="{{ str_contains($inmueble->contrato_documento, 'storage/') ? asset($inmueble->contrato_documento) : asset('storage/' . $inmueble->contrato_documento) }}" target="_blank"
                                class="flex items-center justify-center gap-2 w-full bg-white border-2 border-[#003049]/10 text-[#003049] font-bold text-sm py-2 rounded-xl hover:bg-[#003049]/5 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Leer Contrato
                            </a>
                        </div>
                    @endif

                    <form action="{{ route('pagos.test.success.process', $inmueble->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="metodo_pago" :value="method">
                        <button type="submit"
                            class="flex items-center justify-center gap-3 w-full text-center bg-[#C1121F] text-white py-5 rounded-2xl font-black text-lg shadow-xl shadow-[#C1121F]/30 hover:bg-[#780000] hover:-translate-y-1 transition-all active:scale-95 border border-[#780000]">
                            Pagar y Reservar
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </form>

                    <p
                        class="text-center mt-6 text-[10px] text-gray-400 leading-relaxed font-bold uppercase tracking-wider">
                        Al confirmar, aceptas nuestros <a href="{{ route('terminos') }}" class="text-[#003049] underline"
                            target="_blank">Términos</a> y <a href="{{ route('privacidad') }}"
                            class="text-[#003049] underline" target="_blank">Privacidad</a>.
                    </p>
                </div>
            </div>

        </div>
    </div>
@endsection