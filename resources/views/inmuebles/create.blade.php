@extends('layouts.app')

@section('title', 'Publicar Propiedad')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8" x-data="wizardForm">
    {{-- Alerta de Configuración Bancaria (Stripe Modal) --}}
    @if(auth()->check() && !auth()->user()->stripe_onboarding_completed)
        @include('inmuebles.partials.stripe_modal')
    @endif

    {{-- Stepper Superior --}}
    <div class="mb-12 text-center">
        <h1 class="text-3xl font-black text-[#003049] mb-8">Publicar Propiedad</h1>
        <div class="max-w-xs mx-auto flex items-center justify-between relative">
            <div class="absolute top-5 left-0 w-full h-[2px] bg-gray-200 -z-10"></div>
            
            <template x-for="n in 4" :key="n">
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold border-2 transition-all duration-300 z-10 shadow-sm"
                        :class="step >= n ? 'bg-white border-[#003049] text-[#003049]' : 'bg-gray-100 border-gray-200 text-gray-400'">
                        <span x-text="n"></span>
                    </div>
                    <span class="text-[10px] font-bold mt-2 uppercase tracking-tighter" 
                        :class="step >= n ? 'text-[#003049]' : 'text-gray-400'"
                        x-text="['Básico', 'Detalles', 'Reglas', 'Archivos'][n-1]"></span>
                </div>
            </template>
        </div>
    </div>

    {{-- Formulario Principal --}}
    <div class="bg-white rounded-[2rem] shadow-xl border border-gray-100 p-6 md:p-10">
        <form method="POST" action="{{ route('inmuebles.guardar') }}" enctype="multipart/form-data" novalidate>
            @csrf

            {{-- PASO 1: BÁSICO --}}
            <div x-show="step === 1" x-transition>
                <div class="flex items-center gap-3 mb-8">
                    <div class="p-2 bg-blue-50 rounded-lg text-[#003049]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                    </div>
                    <h2 class="text-2xl font-black text-[#003049]">¿Qué vas a rentar?</h2>
                </div>

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nombre del Anuncio <span class="text-red-500">*</span></label>
                        <input type="text" name="nombre" value="{{ old('nombre') }}" placeholder="Ej. Depa moderno cerca de la UTC" required class="w-full bg-gray-50 border-none rounded-xl py-4 px-5 focus:ring-2 focus:ring-[#003049]/10">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Tipo <span class="text-red-500">*</span></label>
                            <select name="tipo" x-model="tipo" class="w-full bg-gray-50 border-none rounded-xl py-4 px-5 appearance-none">
                                <option value="Casa">Casa</option>
                                <option value="Departamento">Departamento</option>
                                <option value="Cuarto">Cuarto</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Renta Mensual <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-5 top-4 text-gray-400">$</span>
                                <input type="number" name="precio" x-model="precio" placeholder="0.00" required class="w-full bg-gray-50 border-none rounded-xl py-4 pl-10 px-5 focus:ring-2 focus:ring-[#003049]/10">
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100">
                        <label class="block text-sm font-bold text-[#003049] mb-4">¿El inquilino deberá dar depósito?</label>
                        <div class="flex items-center gap-6">
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="radio" x-model="requiereDeposito" value="si" name="requiere_deposito" class="w-5 h-5 text-[#003049] focus:ring-0">
                                <span class="text-sm font-bold text-gray-600">Sí</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="radio" x-model="requiereDeposito" value="no" name="requiere_deposito" class="w-5 h-5 text-[#c1121f] focus:ring-0">
                                <span class="text-sm font-bold text-gray-600">No, sin depósito</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="mt-12 flex justify-between items-center">
                    <a href="{{ route('inmuebles.index') }}" class="bg-gray-200 text-gray-600 font-bold py-3 px-8 rounded-xl hover:bg-gray-300 transition-all shadow-md">Cancelar</a>
                    <button type="button" @click="step = 2" class="bg-[#003049] text-white font-bold py-3 px-10 rounded-xl hover:bg-[#002030] transition-all shadow-lg flex items-center gap-2">
                        Siguiente Paso <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </button>
                </div>
            </div>

            {{-- PASO 2: DETALLES --}}
            <div x-show="step === 2" x-transition style="display: none;">
                <div class="flex items-center gap-3 mb-8">
                    <div class="p-2 bg-blue-50 rounded-lg text-[#003049]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.382-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>
                    </div>
                    <h2 class="text-2xl font-black text-[#003049]">Características y Ubicación</h2>
                </div>

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Dirección Completa <span class="text-red-500">*</span></label>
                        <div class="flex gap-2">
                            <input type="text" name="direccion" id="direccion-input" value="{{ old('direccion') }}" placeholder="Calle, Número, Colonia..." required class="flex-1 bg-gray-50 border-none rounded-xl py-4 px-5">
                            <button type="button" onclick="buscarDireccion()" class="bg-[#003049] text-white px-6 py-4 rounded-xl font-bold flex items-center gap-2 hover:bg-[#002030] transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                Buscar en mapa
                            </button>
                        </div>
                    </div>

                    <div class="rounded-2xl border-2 border-gray-100 overflow-hidden relative shadow-inner">
                        <div id="map-picker" class="w-full h-[300px] z-10"></div>
                        <input type="hidden" name="latitud" id="lat-input">
                        <input type="hidden" name="longitud" id="lng-input">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Habitaciones <span class="text-red-500">*</span></label>
                            <input type="number" name="habitaciones" required class="w-full bg-gray-50 border-none rounded-xl py-4 px-5">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Baños <span class="text-red-500">*</span></label>
                            <select name="banos_casa" class="w-full bg-gray-50 border-none rounded-xl py-4 px-5 appearance-none">
                                <option value="0,1">Medio Baño</option>
                                <option value="1,0">1 Baño Completo</option>
                                <option value="2,0">2 Baños Completos</option>
                            </select>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100">
                        <div class="flex items-center gap-2 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#003049]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" /></svg>
                            <span class="text-sm font-bold text-[#003049]">Dimensiones (Opcional)</span>
                        </div>
                        <div class="grid grid-cols-2 gap-6 items-end mb-4">
                            <div>
                                <label class="text-xs text-gray-400 mb-1 block">Largo (m)</label>
                                <input type="number" x-model="largo" @input="calcularm2()" class="w-full bg-white border-2 border-gray-100 rounded-xl py-3 px-4">
                            </div>
                            <div>
                                <label class="text-xs text-gray-400 mb-1 block">Ancho (m)</label>
                                <input type="number" x-model="ancho" @input="calcularm2()" class="w-full bg-white border-2 border-gray-100 rounded-xl py-3 px-4">
                            </div>
                        </div>
                        <label class="block text-xs font-bold text-gray-700 mb-1 uppercase">Área Total (M²)*</label>
                        <div class="relative">
                            <input type="number" name="metros" x-model="metros" required class="w-full bg-white border-2 border-gray-100 rounded-xl py-4 px-5 font-bold">
                            <span class="absolute right-5 top-4 text-gray-400 font-bold">M²</span>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100">
                        <div class="flex justify-between items-center mb-4">
                            <label class="block text-sm font-bold text-gray-700">Descripción <span class="text-red-500">*</span></label>
                            <span class="bg-red-50 text-[#c1121f] text-[10px] font-bold px-3 py-1 rounded-full" 
                                  :class="numPalabras >= 30 ? 'bg-green-50 text-green-700' : ''">
                                <span x-text="numPalabras"></span> / 120 palabras (Mín. 30)
                            </span>
                        </div>
                        <textarea name="descripcion" x-model="descripcion" rows="5" placeholder="Cuéntanos más detalles del inmueble (Mínimo 30 palabras)..." required class="w-full bg-white border-2 border-gray-100 rounded-2xl py-4 px-5 focus:ring-0"></textarea>
                    </div>
                </div>

                <div class="mt-12 flex justify-between items-center">
                    <button type="button" @click="step = 1" class="text-gray-500 font-bold hover:text-gray-700 transition-colors">← Atrás</button>
                    <button type="button" @click="if(numPalabras >= 30) step = 3" class="bg-[#003049] text-white font-bold py-3 px-10 rounded-xl hover:bg-[#002030] transition-all shadow-lg flex items-center gap-2" :disabled="numPalabras < 30">
                        Siguiente Paso <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </button>
                </div>
            </div>

            {{-- PASO 3: REGLAS --}}
            <div x-show="step === 3" x-transition style="display: none;">
                <div class="flex items-center gap-3 mb-8">
                    <div class="p-2 bg-blue-50 rounded-lg text-[#003049]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                    </div>
                    <h2 class="text-2xl font-black text-[#003049]">Reglas y Configuración de Pagos</h2>
                </div>

                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Mobiliario <span class="text-red-500">*</span></label>
                            <select name="estado_mobiliario" class="w-full bg-gray-50 border-none rounded-xl py-4 px-5 appearance-none">
                                <option value="amueblada">Amueblada</option>
                                <option value="semiamueblada">Semiamueblada</option>
                                <option value="no amueblada">No amueblada</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">¿Estacionamiento incluido?</label>
                            <select name="tiene_estacionamiento" class="w-full bg-gray-50 border-none rounded-xl py-4 px-5 appearance-none">
                                <option value="1">Sí</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100">
                        <label class="block text-sm font-bold text-gray-700 mb-4">¿Estarán permitidas las mascotas?</label>
                        <div class="flex items-center gap-6">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" value="si" name="permite_mascotas" class="w-5 h-5 text-[#003049]">
                                <span class="text-sm font-bold text-gray-600">Sí</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" value="no" name="permite_mascotas" checked class="w-5 h-5 text-[#c1121f]">
                                <span class="text-sm font-bold text-gray-600">No</span>
                            </label>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100">
                        <label class="block text-sm font-bold text-gray-700 mb-4">¿Con qué servicios cuenta el inmueble?</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach(['Agua', 'Electricidad', 'Gas', 'Internet', 'TV por Cable'] as $serv)
                            <label class="flex items-center gap-2 bg-white p-3 rounded-xl border border-gray-100 cursor-pointer">
                                <input type="checkbox" name="servicios_incluidos[]" value="{{ $serv }}" @change="updateServices" class="rounded text-[#003049] focus:ring-0">
                                <span class="text-xs font-bold">{{ $serv }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Momento de Pago <span class="text-red-500">*</span></label>
                            <select name="momento_pago" class="w-full bg-gray-50 border-none rounded-xl py-4 px-5 appearance-none">
                                <option value="adelantado">Por adelantado</option>
                                <option value="vencido">A tiempo vencido</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Tolerancia (Días)</label>
                            <input type="number" name="dias_tolerancia" value="2" class="w-full bg-gray-50 border-none rounded-xl py-4 px-5">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Preaviso Salida</label>
                            <input type="number" name="dias_preaviso" value="30" class="w-full bg-gray-50 border-none rounded-xl py-4 px-5">
                        </div>
                    </div>

                    {{-- Mas campos como duración, contrato, clabe... --}}
                    <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100">
                        <div class="flex items-center gap-2 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#003049]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            <span class="text-sm font-bold text-[#003049]">Duración del Contrato *</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <input type="number" name="duracion_contrato_meses" x-model="duracion" class="w-24 bg-white border-2 border-gray-100 rounded-xl py-3 px-4 text-center font-bold">
                            <span class="text-sm text-gray-500">meses</span>
                            <div class="flex gap-2">
                                <button type="button" @click="duracion = 6" class="px-4 py-2 text-xs font-bold border rounded-lg hover:border-[#003049] transition-all">6 m</button>
                                <button type="button" @click="duracion = 12" class="px-4 py-2 text-xs font-bold border rounded-lg hover:border-[#003049] transition-all">1 año</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-12 flex justify-between items-center">
                    <button type="button" @click="step = 2" class="text-gray-500 font-bold hover:text-gray-700 transition-colors">← Atrás</button>
                    <button type="button" @click="step = 4" class="bg-[#003049] text-white font-bold py-3 px-10 rounded-xl hover:bg-[#002030] transition-all shadow-lg flex items-center gap-2">
                        Siguiente Paso <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </button>
                </div>
            </div>

            {{-- PASO 4: ARCHIVOS --}}
            <div x-show="step === 4" x-transition style="display: none;">
                <div class="flex items-center gap-3 mb-8">
                    <div class="p-2 bg-blue-50 rounded-lg text-[#003049]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 018.07 3h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0016.07 6H17a2 2 0 012 2v7a2 2 0 01-2 2H3a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    </div>
                    <h2 class="text-2xl font-black text-[#003049]">Archivos de la Propiedad</h2>
                </div>

                <div class="mb-8">
                    <div class="relative group cursor-pointer hover:bg-gray-50 transition-colors rounded-2xl border-2 border-dashed border-gray-200 p-12 flex flex-col items-center justify-center text-center">
                        <input type="file" name="imagenes[]" multiple accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" @change="handleFileSelect">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        <p class="text-sm font-bold text-gray-500">Haz clic o arrastra más fotos</p>
                    </div>
                </div>

                <div class="mt-12 flex justify-between items-center">
                    <button type="button" @click="step = 3" class="text-gray-500 font-bold hover:text-gray-700 transition-colors">← Atrás</button>
                    <button type="submit" class="bg-[#003049] text-white font-black py-4 px-12 rounded-2xl hover:bg-[#002030] transition-all shadow-xl shadow-[#003049]/20 text-lg">
                        ¡Publicar Ahora!
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('wizardForm', () => ({
        step: 1,
        tipo: 'Casa',
        precio: '',
        requiereDeposito: 'no',
        largo: '',
        ancho: '',
        metros: '',
        descripcion: '',
        duracion: 12,
        get numPalabras() {
            return this.descripcion.trim().split(/\s+/).filter(p => p.length > 0).length;
        },
        calcularm2() {
            if(this.largo && this.ancho) this.metros = (this.largo * this.ancho).toFixed(2);
        },
        handleFileSelect(e) { /* Lógica de preview opcional */ }
    }));
});
</script>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // Inicialización del mapa (Mapa simple para restaurar diseño)
    var map, marker;
    function initMap() {
        map = L.map('map-picker').setView([16.9068, -92.0941], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
        marker = L.marker([16.9068, -92.0941], {draggable: true}).addTo(map);
        marker.on('dragend', function(e) {
            document.getElementById('lat-input').value = e.target.getLatLng().lat;
            document.getElementById('lng-input').value = e.target.getLatLng().lng;
        });
    }
    window.onload = initMap;
</script>
@endsection