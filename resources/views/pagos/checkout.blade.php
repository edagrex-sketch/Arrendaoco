@extends('layouts.app')

@section('title', 'Finalizar Pago - ArrendaOco')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <div class="flex flex-col lg:flex-row gap-12">
        
        <!-- Columna Izquierda: Métodos de Pago -->
        <div class="flex-1">
            <a href="{{ route('pagos.test.index') }}" class="inline-flex items-center gap-2 text-[#669BBC] font-bold text-sm mb-8 hover:-translate-x-1 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Volver a mis pagos
            </a>

            <h1 class="text-4xl font-black text-[#003049] mb-4">Método de Pago</h1>
            <p class="text-gray-500 font-medium mb-10">Selecciona tu forma de pago preferida. Todas las transacciones están cifradas y protegidas.</p>

            <div class="space-y-4" x-data="{ method: 'card' }">
                <!-- Opción 1: Tarjeta -->
                <div @click="method = 'card'" :class="method === 'card' ? 'border-[#669BBC] bg-[#669BBC]/5 ring-2 ring-[#669BBC]/20' : 'border-gray-200 hover:border-gray-300'" class="p-6 border-2 rounded-3xl cursor-pointer transition-all duration-200 relative">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-5">
                            <div class="h-14 w-14 bg-white rounded-2xl flex items-center justify-center text-2xl shadow-sm">💳</div>
                            <div>
                                <p class="font-black text-[#003049] text-lg">Tarjeta de Crédito / Débito</p>
                                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Visa, Mastercard, American Express</p>
                            </div>
                        </div>
                        <div :class="method === 'card' ? 'border-4 border-[#669BBC]' : 'border-2 border-gray-300'" class="h-6 w-6 rounded-full transition-all"></div>
                    </div>

                    <!-- Detalles del Formulario (Solo visual) -->
                    <div x-show="method === 'card'" x-transition class="mt-8 pt-8 border-t border-[#669BBC]/20">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="text-xs font-black text-[#669BBC] uppercase tracking-widest pl-1">Número de Tarjeta</label>
                                <input type="text" placeholder="**** **** **** ****" class="w-full bg-white border-2 border-gray-100 rounded-2xl px-5 py-4 focus:border-[#669BBC] focus:ring-0 transition-colors">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label class="text-xs font-black text-[#669BBC] uppercase tracking-widest pl-1">Expiración</label>
                                    <input type="text" placeholder="MM/YY" class="w-full bg-white border-2 border-gray-100 rounded-2xl px-5 py-4 focus:border-[#669BBC] focus:ring-0 transition-colors">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-xs font-black text-[#669BBC] uppercase tracking-widest pl-1">CVC</label>
                                    <input type="text" placeholder="123" class="w-full bg-white border-2 border-gray-100 rounded-2xl px-5 py-4 focus:border-[#669BBC] focus:ring-0 transition-colors">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Opción 2: Transferencia SPEI -->
                <div @click="method = 'spei'" :class="method === 'spei' ? 'border-[#669BBC] bg-[#669BBC]/5 ring-2 ring-[#669BBC]/20' : 'border-gray-200 hover:border-gray-300'" class="p-6 border-2 rounded-3xl cursor-pointer transition-all duration-200">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-5">
                            <div class="h-14 w-14 bg-white rounded-2xl flex items-center justify-center text-2xl shadow-sm">🏦</div>
                            <div>
                                <p class="font-black text-[#003049] text-lg">Transferencia SPEI</p>
                                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Confirmación instantánea</p>
                            </div>
                        </div>
                        <div :class="method === 'spei' ? 'border-4 border-[#669BBC]' : 'border-2 border-gray-300'" class="h-6 w-6 rounded-full transition-all"></div>
                    </div>
                </div>

                <!-- Opción 3: Efectivo (OXXO) -->
                <div @click="method = 'oxxo'" :class="method === 'oxxo' ? 'border-[#669BBC] bg-[#669BBC]/5 ring-2 ring-[#669BBC]/20' : 'border-gray-200 hover:border-gray-300'" class="p-6 border-2 rounded-3xl cursor-pointer transition-all duration-200">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-5">
                            <div class="h-14 w-14 bg-white rounded-2xl flex items-center justify-center text-2xl shadow-sm">🏪</div>
                            <div>
                                <p class="font-black text-[#003049] text-lg">Efectivo (OXXO Pay)</p>
                                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Paga en cualquier sucursal</p>
                            </div>
                        </div>
                        <div :class="method === 'oxxo' ? 'border-4 border-[#669BBC]' : 'border-2 border-gray-300'" class="h-6 w-6 rounded-full transition-all"></div>
                    </div>
                </div>
            </div>
            
            <div class="mt-12 p-8 bg-[#003049]/5 rounded-[2.5rem] border-2 border-dashed border-[#003049]/10">
                <div class="flex items-start gap-4">
                    <span class="text-2xl">🛡️</span>
                    <div>
                        <p class="font-bold text-[#003049] mb-1">Pago 100% Seguro</p>
                        <p class="text-sm text-gray-500">ArrendaOco utiliza tecnología de cifrado de nivel bancario. Tus datos nunca son almacenados en nuestros servidores.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna Derecha: Resumen de Orden -->
        <div class="w-full lg:w-[400px]">
            <div class="bg-white rounded-[2.5rem] p-8 shadow-2xl shadow-[#003049]/10 border border-gray-100 sticky top-24">
                <h2 class="text-2xl font-black text-[#003049] mb-8">Resumen</h2>
                
                <div class="flex items-center gap-4 mb-10 p-4 bg-gray-50 rounded-2xl">
                    <div class="h-16 w-16 bg-white rounded-xl shadow-sm overflow-hidden flex-shrink-0">
                         <div class="h-full w-full bg-gradient-to-br from-[#669BBC] to-[#003049] flex items-center justify-center text-white text-xl">🏠</div>
                    </div>
                    <div>
                        <p class="font-bold text-[#003049] text-sm">Real de Ocosingo</p>
                        <p class="text-xs text-gray-400 font-medium">Depto. B-12 • Arrendador: Juan Pérez</p>
                    </div>
                </div>

                <div class="space-y-4 mb-10">
                    <div class="flex justify-between items-center">
                        <p class="text-gray-500 font-medium">Subtotal (Renta)</p>
                        <p class="font-bold text-[#003049]">$4,500.00</p>
                    </div>
                    <div class="flex justify-between items-center">
                        <p class="text-gray-500 font-medium">Comisión por servicio</p>
                        <p class="font-bold text-[#003049]">$0.00</p>
                    </div>
                    <div class="flex justify-between items-center">
                        <p class="text-gray-500 font-medium">Seguro de arrendamiento</p>
                        <p class="font-bold text-green-600">GRATIS</p>
                    </div>
                    <div class="pt-4 border-t border-gray-100 flex justify-between items-end">
                        <p class="font-black text-[#003049]">Total a Pagar</p>
                        <p class="text-3xl font-black text-[#003049]">$4,500.00</p>
                    </div>
                    <p class="text-[10px] text-right text-gray-400 font-bold uppercase tracking-wider">Precios en MXN</p>
                </div>

                <a href="{{ route('pagos.test.success') }}" class="block w-full text-center bg-[#C1121F] text-white py-5 rounded-2xl font-black text-lg shadow-xl shadow-[#C1121F]/20 hover:bg-[#780000] hover:-translate-y-1 transition-all active:scale-95">
                    Confirmar y Pagar
                </a>
                
                <p class="text-center mt-6 text-xs text-gray-400 leading-relaxed font-medium">
                    Al confirmar el pago, aceptas nuestros <a href="#" class="text-[#669BBC] underline">Términos de Servicio</a> y <a href="#" class="text-[#669BBC] underline">Política de Cancelación</a>.
                </p>
            </div>
        </div>

    </div>
</div>
@endsection
