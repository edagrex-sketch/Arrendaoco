@extends('layouts.app')

@section('title', 'Referencia OXXO - ' . $inmueble->titulo)

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-16">
        <div class="text-center mb-10">
            <div
                class="w-20 h-20 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm border border-orange-200">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                    class="w-10 h-10 text-orange-500">
                    <path fill-rule="evenodd"
                        d="M3 6a3 3 0 013-3h12a3 3 0 013 3v2.246a3 3 0 01-1.39.063l-2-.416a1.5 1.5 0 00-1.22.42V10.5a.75.75 0 01-1.5 0V8.316a1.5 1.5 0 00-1.22-.42l-2 .416A3 3 0 019 8.246v2.254a.75.75 0 01-1.5 0V8.246a3 3 0 01-1.39-.063l-2-.416a1.5 1.5 0 00-1.22.42V10.5a.75.75 0 01-1.5 0V6zm0 6h18v6.75A2.25 2.25 0 0118.75 21H5.25A2.25 2.25 0 013 18.75V12z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <h1 class="text-3xl md:text-5xl font-black text-[#003049] tracking-tight mb-4">Referencia Generada</h1>
            <p class="text-lg text-slate-500 max-w-2xl mx-auto">Tu reservación para <strong>{{ $inmueble->titulo }}</strong>
                está casi lista. Paga en cualquier sucursal OXXO para confirmarla.</p>
        </div>

        <div class="bg-white rounded-[2rem] shadow-2xl p-6 md:p-10 border border-slate-100 max-w-2xl mx-auto">
            <div
                class="flex flex-col md:flex-row items-center justify-between border-b border-dashed border-slate-200 pb-8 mb-8">
                <div class="text-center md:text-left mb-6 md:mb-0">
                    <p class="text-[10px] font-black uppercase tracking-widest text-[#669BBC] mb-1">Monto a Pagar</p>
                    <p class="text-4xl md:text-5xl font-black text-[#003049]">
                        ${{ number_format($contrato->renta_mensual + ($contrato->deposito ?? 0), 2) }} <span
                            class="text-xl text-slate-400">MXN</span></p>
                </div>
                <img src="https://upload.wikimedia.org/wikipedia/commons/2/29/OXXO_Logo.svg" alt="OXXO"
                    class="h-12 object-contain">
            </div>

            <div class="text-center bg-slate-50 p-8 rounded-3xl border border-slate-200 mb-8">
                <p class="text-xs font-bold uppercase tracking-widest text-slate-500 mb-3">Tu Número de Referencia OXXO PAY
                </p>
                <p
                    class="text-3xl md:text-4xl font-mono text-center tracking-widest font-bold text-slate-800 bg-white p-4 rounded-xl border-2 border-slate-300 shadow-inner break-all">
                    {{ $referencia }}
                </p>
                <p class="text-xs text-slate-400 mt-4">* Dile al cajero que quieres realizar un pago de <strong>OXXO
                        Pay</strong> e indícale esta referencia.</p>
            </div>

            <h3 class="font-bold text-[#003049] text-xl mb-4">Instrucciones:</h3>
            <ol class="space-y-4 text-slate-600 mb-8 list-decimal list-inside marker:text-[#669BBC] marker:font-bold">
                <li class="pl-2">Acude a tu sucursal OXXO más cercana.</li>
                <li class="pl-2">Indícale al cajero que harás un pago en efectivo usando <strong>OXXO Pay</strong>.</li>
                <li class="pl-2">Proporciona el número de referencia dictando los números mostrados arriba.</li>
                <li class="pl-2">Paga en efectivo el monto exacto (+ comisión respectiva de la tienda).</li>
                <li class="pl-2">Conserva tu ticket para cualquier aclaración. El pago se reflejará instantáneamente.</li>
            </ol>

            <a href="{{ route('inmuebles.mis_rentas') }}"
                class="block w-full text-center bg-[#003049] hover:bg-[#002233] text-white font-bold py-4 rounded-xl transition-colors shadow-lg shadow-[#003049]/20">
                Ir a Mis Rentas
            </a>
        </div>
    </div>
@endsection