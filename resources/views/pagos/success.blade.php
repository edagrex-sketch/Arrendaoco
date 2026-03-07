@extends('layouts.app')

@section('title', 'Pago Exitoso - ArrendaOco')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-16 text-center">
    <div class="mb-10 inline-flex items-center justify-center h-32 w-32 bg-green-50 rounded-[3rem] animate-bounce">
        <div class="h-20 w-20 bg-green-500 rounded-[2rem] flex items-center justify-center text-5xl text-white shadow-xl shadow-green-500/20">
            ✓
        </div>
    </div>

    <h1 class="text-5xl font-black text-[#003049] mb-4">¡Pago Realizado!</h1>
    <p class="text-xl text-gray-500 font-medium mb-12">Tu transacción se ha procesado correctamente. Hemos enviado el recibo a tu correo electrónico.</p>

    <div class="bg-white rounded-[3rem] p-10 shadow-2xl shadow-gray-200/50 border border-gray-100 mb-12 relative overflow-hidden">
        <!-- Decoración -->
        <div class="absolute top-0 right-0 h-32 w-32 bg-[#FDF0D5] rounded-bl-[5rem] -mr-16 -mt-16 opacity-50"></div>
        
        <div class="space-y-6 text-left relative z-10">
            <div class="grid grid-cols-2 gap-8">
                <div>
                    <p class="text-[10px] font-black text-[#669BBC] uppercase tracking-widest mb-1">ID de Transacción</p>
                    <p class="font-bold text-[#003049]">#AO-{{ rand(100000, 999999) }}-MEX</p>
                </div>
                <div>
                    <p class="text-[10px] font-black text-[#669BBC] uppercase tracking-widest mb-1">Fecha y Hora</p>
                    <p class="font-bold text-[#003049]">{{ now()->format('d M, Y • h:i A') }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-black text-[#669BBC] uppercase tracking-widest mb-1">Método de Pago</p>
                    <p class="font-bold text-[#003049]">Tarjeta • Visa **** {{ rand(1000, 9999) }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-black text-[#669BBC] uppercase tracking-widest mb-1">Monto Total</p>
                    <p class="text-2xl font-black text-[#003049]">${{ isset($inmueble) ? number_format($inmueble->renta_mensual + ($inmueble->deposito ?? 0), 2) : '4,500.00' }} MXN</p>
                </div>
            </div>
            
            <div class="pt-8 border-t border-gray-100 flex flex-col md:flex-row gap-4">
                <button class="flex-1 flex items-center justify-center gap-2 bg-[#003049] text-white py-4 rounded-2xl font-black hover:bg-[#002538] transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Descargar Recibo (PDF)
                </button>
                <button class="flex-1 flex items-center justify-center gap-2 border-2 border-[#669BBC] text-[#669BBC] py-4 rounded-2xl font-black hover:bg-[#669BBC]/5 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                    </svg>
                    Compartir Comprobante
                </button>
            </div>
        </div>
    </div>

    <div class="flex flex-col md:flex-row items-center justify-center gap-6">
        <a href="{{ route('pagos.test.index') }}" class="text-[#669BBC] font-black hover:underline uppercase tracking-widest text-sm">Ir al panel de pagos</a>
        <span class="hidden md:block h-1 w-1 bg-gray-300 rounded-full"></span>
        <a href="{{ route('inicio') }}" class="text-[#003049] font-black hover:underline uppercase tracking-widest text-sm">Volver al Inicio</a>
    </div>

    <!-- Lottie Animation o Confetti (Simulado con CSS) -->
    <div class="pointer-events-none fixed inset-0 flex items-center justify-center z-50 overflow-hidden">
        <!-- Aquí podría ir una animación de confeti si se desea -->
    </div>
</div>
@endsection
