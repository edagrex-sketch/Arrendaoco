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
                            <div class="h-14 w-14 bg-white rounded-2xl flex items-center justify-center text-2xl shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-blue-500">
                                    <path d="M4.5 3.75a3 3 0 00-3 3v.75h21v-.75a3 3 0 00-3-3h-15z" />
                                    <path fill-rule="evenodd" d="M22.5 9.75h-21v7.5a3 3 0 003 3h15a3 3 0 003-3v-7.5zm-18 3.75a.75.75 0 01.75-.75h6a.75.75 0 010 1.5h-6a.75.75 0 01-.75-.75zm.75 2.25a.75.75 0 000 1.5h3a.75.75 0 000-1.5h-3z" clip-rule="evenodd" />
                                </svg>
                            </div>
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
                            <div class="h-14 w-14 bg-white rounded-2xl flex items-center justify-center text-2xl shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-6 h-6 text-green-600">
                                    <path fill-rule="evenodd" d="M10.45 1.638a.75.75 0 00-.9 0l-7 5.25a.75.75 0 00.9 1.206L4 7.644V15.5a1.5 1.5 0 00-1.5 1.5.75.75 0 00.75.75h14a.75.75 0 00.75-.75 1.5 1.5 0 00-1.5-1.5V7.644l.55.413a.75.75 0 10.9-1.206l-7-5.25zm1.55 14.612v-5.5a1.5 1.5 0 00-1.5-1.5h-1a1.5 1.5 0 00-1.5 1.5v5.5H5.5v-8.48L10 2.876l4.5 3.376v8.48h-2.5z" clip-rule="evenodd" />
                                </svg>
                            </div>
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
                            <div class="h-14 w-14 bg-white rounded-2xl flex items-center justify-center text-2xl shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-orange-500">
                                    <path fill-rule="evenodd" d="M3 6a3 3 0 013-3h12a3 3 0 013 3v2.246a3 3 0 01-1.39.063l-2-.416a1.5 1.5 0 00-1.22.42V10.5a.75.75 0 01-1.5 0V8.316a1.5 1.5 0 00-1.22-.42l-2 .416A3 3 0 019 8.246v2.254a.75.75 0 01-1.5 0V8.246a3 3 0 01-1.39-.063l-2-.416a1.5 1.5 0 00-1.22.42V10.5a.75.75 0 01-1.5 0V6zm0 6h18v6.75A2.25 2.25 0 0118.75 21H5.25A2.25 2.25 0 013 18.75V12z" clip-rule="evenodd" />
                                </svg>
                            </div>
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
                    <span class="text-2xl text-green-500">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8">
                            <path fill-rule="evenodd" d="M12.516 2.17a.75.75 0 00-1.032 0 11.209 11.209 0 01-7.877 3.08.75.75 0 00-.722.515A12.74 12.74 0 008.38 21.41a.75.75 0 00.916.326 11.22 11.22 0 017.41-.013.75.75 0 00.915-.325A12.74 12.74 0 0021.115 5.765a.75.75 0 00-.722-.515 11.209 11.209 0 01-7.877-3.08zm-2.028 10.9l-2.75-2.75a.75.75 0 111.06-1.06l2.22 2.22 5.22-5.22a.75.75 0 111.06 1.06l-5.75 5.75a.75.75 0 01-1.06 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
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
                         <div class="h-full w-full bg-gradient-to-br from-[#669BBC] to-[#003049] flex items-center justify-center text-white text-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-8 h-8">
                                <path fill-rule="evenodd" d="M9.293 2.293a1 1 0 011.414 0l7 7A1 1 0 0117 11h-1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-3a1 1 0 00-1-1H9a1 1 0 00-1 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-6H3a1 1 0 01-.707-1.707l7-7z" clip-rule="evenodd" />
                            </svg>
                         </div>
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
