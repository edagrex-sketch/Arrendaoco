@extends('layouts.app')

@section('title', 'Publicar Inmueble')

@section('content')
    <div class="max-w-4xl mx-auto" x-data="wizardForm">
        {{-- Alerta de Configuración Bancaria (Usuarios Nuevos / Propietarios) --}}
        @if(auth()->check() && !auth()->user()->stripe_onboarding_completed)
        <div x-data="{ showBankingModal: true }" 
             x-show="showBankingModal" 
             class="fixed inset-0 z-[100] flex items-center justify-center bg-[#003049]/80 backdrop-blur-sm px-4"
             style="display: none;" x-cloak>
            
            <!-- El modal ahora es obligatorio form-bloqueante al no tener función de cierre -->
            <div x-show="showBankingModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-90 translate-y-8"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 class="bg-white rounded-[2rem] shadow-2xl max-w-2xl w-full relative overflow-hidden mt-10">
                 
                 <div class="flex flex-col md:flex-row items-stretch">
                     <!-- Columna Izquierda: Texto -->
                     <div class="p-8 pb-10 md:w-3/5">
                         <h2 class="text-2xl font-black text-[#003049] mb-4 leading-tight">¡Estás a un paso de recibir pagos! 💸</h2>
                         <p class="text-gray-600 mb-6 leading-relaxed text-sm">
                             Al publicar tu primer inmueble comenzarás tu camino como <strong>propietario</strong>.<br><br>
                             <strong>Necesitamos que vincules una cuenta bancaria o CLABE</strong> de forma segura con Stripe para que te lleguen tus pagos. Sin una cuenta configurada, tus inquilinos no podrán realizar transferencias automáticas desde la App.
                         </p>
                         
                         <div class="flex flex-col sm:flex-row gap-3 relative z-20 mt-4">
                             <a href="{{ route('stripe.connect.onboard') }}" class="w-full bg-[#C1121F] text-white font-bold py-3 px-4 rounded-xl text-center hover:bg-[#780000] shadow-lg shadow-[#C1121F]/30 transition-all text-sm flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg> 
                                Vincular Cuenta Ahora
                             </a>
                         </div>
                     </div>
    
                     <!-- Columna Derecha: Lottie Animation -->
                     <div class="md:w-2/5 bg-[#FDF0D5]/40 hidden md:flex items-center justify-center relative overflow-hidden">
                         <!-- Círculo decorativo -->
                         <div class="absolute inset-0 m-auto w-40 h-40 bg-[#FDF0D5] rounded-full blur-2xl"></div>
                         
                         <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
                         <div class="w-64 h-64 relative z-10 translate-y-6 -translate-x-2">
                             <lottie-player src="https://assets4.lottiefiles.com/packages/lf20_syqnfe7c.json" background="transparent" speed="1" style="width: 100%; height: 100%;" loop autoplay></lottie-player>
                         </div>
                     </div>
                 </div>
            </div>
        </div>
        @endif

        {{-- Encabezado de Steppers (Actualizado a 4 pasos) --}}
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-foreground mb-4">Publicar Propiedad</h1>
            <div class="flex items-center justify-center gap-4 relative">
                <div class="absolute top-1/2 left-0 w-full h-1 bg-gray-200 -z-10 rounded-full"></div>
                
                {{-- Paso 1: Básico --}}
                <div class="flex flex-col items-center cursor-pointer" @click="if(step > 1) step = 1">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold border-4 transition-colors bg-white z-10" :class="step >= 1 ? 'border-primary text-primary' : 'border-gray-300 text-gray-400'">1</div>
                    <span class="text-xs font-medium mt-1 bg-background px-1" :class="step >= 1 ? 'text-primary' : 'text-gray-400'">Básico</span>
                </div>
                
                {{-- Paso 2: Detalles --}}
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold border-4 transition-colors bg-white z-10" :class="step >= 2 ? 'border-primary text-primary' : 'border-gray-300 text-gray-400'">2</div>
                    <span class="text-xs font-medium mt-1 bg-background px-1" :class="step >= 2 ? 'text-primary' : 'text-gray-400'">Detalles</span>
                </div>

                {{-- Paso 3: Reglas y Pagos (NUEVO) --}}
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold border-4 transition-colors bg-white z-10" :class="step >= 3 ? 'border-primary text-primary' : 'border-gray-300 text-gray-400'">3</div>
                    <span class="text-xs font-medium mt-1 bg-background px-1" :class="step >= 3 ? 'text-primary' : 'text-gray-400'">Reglas</span>
                </div>
                
                {{-- Paso 4: Archivos --}}
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold border-4 transition-colors bg-white z-10" :class="step >= 4 ? 'border-primary text-primary' : 'border-gray-300 text-gray-400'">4</div>
                    <span class="text-xs font-medium mt-1 bg-background px-1" :class="step >= 4 ? 'text-primary' : 'text-gray-400'">Archivos</span>
                </div>
            </div>
        </div>

        {{-- Formulario --}}
        <div class="bg-card border border-border rounded-2xl shadow-lg p-6 sm:p-8">
            <form method="POST" action="{{ route('inmuebles.guardar') }}" class="space-y-6" enctype="multipart/form-data" novalidate>
                @csrf

                {{-- ==========================================
                     PASO 1: BÁSICO
                     ========================================== --}}
                <div x-show="step === 1" x-ref="step1" x-transition:enter="transition opacity-0 transform translate-x-4 ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                    <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                            <path fill-rule="evenodd" d="M9.293 2.293a1 1 0 011.414 0l7 7A1 1 0 0117 11h-1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-3a1 1 0 00-1-1H9a1 1 0 00-1 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-6H3a1 1 0 01-.707-1.707l7-7z" clip-rule="evenodd" />
                        </svg> ¿Qué vas a rentar?
                    </h2>
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="block text-sm font-medium mb-1">Nombre del Anuncio <span class="text-red-500">*</span></label>
                            <input type="text" name="nombre" value="{{ old('nombre') }}" placeholder="Ej. Depa moderno cerca de la UTC" required oninput="this.value = this.value.replace(/[^a-zA-Z0-9\s.,?!;:/\-áéíóúÁÉÍÓÚñÑüÜ]/g, '')" class="w-full rounded-lg border-input bg-white border py-3 px-4 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Tipo <span class="text-red-500">*</span></label>
                                <select name="tipo" x-model="tipo" required class="w-full rounded-lg border-input bg-white border py-3 px-4">
                                    <option value="Casa">Casa</option>
                                    <option value="Departamento">Departamento</option>
                                    <option value="Cuarto">Cuarto</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Renta Mensual <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span class="absolute left-3 top-3 text-gray-500">$</span>
                                    <input type="number" name="precio" x-model="precio" placeholder="0" required :min="minPrecio" step="1" oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value < 0) this.value = '';" class="w-full rounded-lg border-input bg-white border py-3 pl-8 px-4">
                                </div>
                            </div>
                            
                            {{-- Depósito --}}
                            <div class="md:col-span-2 bg-slate-50 p-5 rounded-xl border border-border mt-2">
                                <label class="block text-sm font-medium mb-3">¿El inquilino deberá dar depósito?</label>
                                <div class="flex gap-4 mb-3">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" x-model="requiereDeposito" value="si" name="requiere_deposito" class="text-primary focus:ring-primary h-4 w-4">
                                        <span class="text-sm font-medium">Sí</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" x-model="requiereDeposito" value="no" name="requiere_deposito" class="text-primary focus:ring-primary h-4 w-4">
                                        <span class="text-sm font-medium">No, sin depósito</span>
                                    </label>
                                </div>
                                
                                <div x-show="requiereDeposito === 'si'" x-transition class="mt-4 pt-4 border-t border-gray-200">
                                    <label class="block text-sm font-medium mb-3 text-slate-700">Cantidad del depósito</label>
                                    <div class="flex flex-col sm:flex-row gap-4 mb-4">
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio" x-model="tipoDeposito" value="mensualidad" class="text-primary focus:ring-primary h-4 w-4">
                                            <span class="text-sm">Una renta mensual (<span x-text="precio ? '$' + precio : '$0'" class="font-bold text-[#003049]"></span>)</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio" x-model="tipoDeposito" value="otra" class="text-primary focus:ring-primary h-4 w-4">
                                            <span class="text-sm">Otra cantidad</span>
                                        </label>
                                    </div>
                                    
                                    <div x-show="tipoDeposito === 'otra'" x-transition>
                                        <div class="relative w-full sm:w-1/2">
                                            <span class="absolute left-3 top-3 text-gray-500">$</span>
                                            <input type="text" x-model="depositoManual" placeholder="Ej. 1500" 
                                                oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value < 0) this.value = '';" 
                                                class="w-full rounded-lg border-input bg-white border py-3 pl-8 px-4" 
                                                x-bind:required="requiereDeposito === 'si' && tipoDeposito === 'otra'">
                                        </div>
                                    </div>
                                    <input type="hidden" name="deposito" x-bind:value="tipoDeposito === 'mensualidad' ? precio : depositoManual" x-bind:disabled="requiereDeposito === 'no'">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ==========================================
                     PASO 2: DETALLES Y MAPA
                     ========================================== --}}
                <div x-show="step === 2" x-ref="step2" x-transition style="display: none;">
                    <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-[#003049]">
                            <path fill-rule="evenodd" d="M10 2a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0v-1.5A.75.75 0 0110 2zM10 15.25a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0v-1.5a.75.75 0 01.75-.75zM13.636 5.864a.75.75 0 010 1.06l-1.06 1.06a.75.75 0 01-1.06-1.06l1.06-1.06a.75.75 0 011.06 0zM7.485 11.485a.75.75 0 010 1.06l-1.06 1.06a.75.75 0 01-1.06-1.06l1.06-1.06a.75.75 0 011.06 0zM14.696 14.696a.75.75 0 01-1.06 0l-1.06-1.06a.75.75 0 111.06-1.06l1.06 1.06a.75.75 0 010 1.06zM7.485 7.485a.75.75 0 01-1.06 0l-1.06-1.06a.75.75 0 111.06-1.06l1.06 1.06a.75.75 0 010 1.06zM15 10a.75.75 0 01-.75.75h-1.5a.75.75 0 010-1.5h1.5A.75.75 0 0115 10zM6.5 10a.75.75 0 01-.75.75h-1.5a.75.75 0 010-1.5h1.5A.75.75 0 016.5 10zM10 7a3 3 0 100 6 3 3 0 000-6z" clip-rule="evenodd" />
                        </svg> Características y Ubicación
                    </h2>

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Dirección Completa <span class="text-red-500">*</span></label>
                        <div class="flex gap-2">
                            <input type="text" name="direccion" id="direccion-input" value="{{ old('direccion') }}" placeholder="Calle, Número, Colonia..." required class="flex-1 rounded-lg border-input bg-white border py-3 px-4">
                            <button type="button" onclick="buscarDireccion()" class="bg-[#003049] hover:bg-[#003049]/90 text-white px-4 py-2 rounded-lg transition-all text-sm font-bold flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                Buscar en mapa
                            </button>
                            <button type="button" onclick="geolocalizar()" title="Usar mi ubicación actual" class="bg-blue-500 hover:bg-blue-600 text-white p-3 rounded-lg transition-all shadow-md group">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </button>
                        </div>
                        <p class="mt-2 text-[10px] text-gray-400 font-bold uppercase tracking-wider">
                            Marca el punto en el mapa (Seleccionado automáticamente al buscar dirección)
                        </p>
                    </div>

                    {{-- Selector de Mapa --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2">Marca el punto en el mapa <span class="text-xs text-muted-foreground font-normal">(Seleccionado automáticamente al buscar dirección)</span></label>
                        <div id="map-picker" class="w-full h-[300px] rounded-xl border border-border bg-slate-50 z-10 shadow-inner"></div>
                        <input type="hidden" name="latitud" id="lat-input" value="{{ old('latitud') }}">
                        <input type="hidden" name="longitud" id="lng-input" value="{{ old('longitud') }}">
                    </div>

                    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
                    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Habitaciones <span class="text-red-500">*</span></label>
                            <input type="number" name="habitaciones" value="{{ old('habitaciones') }}" required min="0" step="any" oninput="if(this.value < 0) this.value = '';" class="w-full rounded-lg border-input bg-white border py-2 px-3">
                        </div>
                        <!-- Baños -->
                        <div>
                            <label class="block text-sm font-medium mb-1">Baños <span class="text-red-500">*</span></label>
                            <select name="banos_casa" required class="w-full rounded-lg border-input bg-white border py-2 px-3">
                                <option value="0,1" {{ old('banos_casa') === '0,1' ? 'selected' : '' }}>Medio Baño</option>
                                <option value="1,0" {{ old('banos_casa') === '1,0' ? 'selected' : '' }}>1 Baño Completo</option>
                                <option value="1,1" {{ old('banos_casa') === '1,1' ? 'selected' : '' }}>1 Baño Completo y Medio Baño</option>
                                <option value="2,0" {{ old('banos_casa') === '2,0' ? 'selected' : '' }}>2 Baños Completos</option>
                                <option value="2,1" {{ old('banos_casa') === '2,1' ? 'selected' : '' }}>2 Baños Completos y Medio Baño</option>
                                <option value="3,0" {{ old('banos_casa') === '3,0' ? 'selected' : '' }}>3 Baños Completos</option>
                                <option value="3,1" {{ old('banos_casa') === '3,1' ? 'selected' : '' }}>3 Baños Completos y Medio Baño</option>
                                <option value="4,0" {{ old('banos_casa') === '4,0' ? 'selected' : '' }}>4 Baños o más</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6 sm:-mt-2" x-show="tipo === 'Cuarto'" style="display: none;">
                        <div class="hidden sm:block"></div>
                        <div class="flex items-center">
                            <label class="flex items-center gap-2 text-sm font-medium text-slate-500 cursor-pointer">
                                <input type="checkbox" name="bano_compartido" value="1" class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary">
                                ¿El baño es compartido?
                            </label>
                        </div>
                    </div>

                    {{-- Calculadora de Área --}}
                    <div class="mb-6 bg-slate-50 p-5 rounded-xl border border-gray-200">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-[#003049] flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                                </svg>
                            </span>
                            <label class="text-sm font-bold text-slate-700">Dimensiones (Opcional)</label>
                        </div>
                        <div class="grid grid-cols-3 gap-4 items-end">
                            <div>
                                <label class="block text-xs text-muted-foreground mb-1">Largo (m)</label>
                                <input type="number" x-model="largo" @input="calcularm2()" placeholder="0" min="0" step="any" oninput="if(this.value < 0) this.value = '';" class="w-full rounded-lg border-input bg-white border py-2 px-3 text-sm">
                            </div>
                            <div class="flex items-center justify-center pb-2 text-muted-foreground"><span>×</span></div>
                            <div>
                                <label class="block text-xs text-muted-foreground mb-1">Ancho (m)</label>
                                <input type="number" x-model="ancho" @input="calcularm2()" placeholder="0" min="0" step="any" oninput="if(this.value < 0) this.value = '';" class="w-full rounded-lg border-input bg-white border py-2 px-3 text-sm">
                            </div>
                        </div>
                        <div class="mt-4 pt-3 border-t border-gray-200">
                            <label class="block text-xs font-bold mb-1 uppercase text-slate-600">Área Total (m²) <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="number" name="metros" x-model="metros" required min="0" step="any" oninput="if(this.value < 0) this.value = '';" class="w-full rounded-lg border-input bg-white border-2 border-gray-200 py-2 px-3 font-bold text-slate-800 focus:border-blue-400 focus:ring-0">
                                <span class="absolute right-3 top-2.5 text-xs text-muted-foreground font-bold">M²</span>
                            </div>
                            <p class="text-xs text-slate-700 mt-2 flex items-center gap-1" x-show="largo && ancho">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3 text-[#003049]">
                                    <path fill-rule="evenodd" d="M10 2a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0v-1.5A.75.75 0 0110 2zM10 15.25a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0v-1.5a.75.75 0 01.75-.75zM13.636 5.864a.75.75 0 010 1.06l-1.06 1.06a.75.75 0 01-1.06-1.06l1.06-1.06a.75.75 0 011.06 0zM7.485 11.485a.75.75 0 010 1.06l-1.06 1.06a.75.75 0 01-1.06-1.06l1.06-1.06a.75.75 0 011.06 0zM14.696 14.696a.75.75 0 01-1.06 0l-1.06-1.06a.75.75 0 111.06-1.06l1.06 1.06a.75.75 0 010 1.06zM7.485 7.485a.75.75 0 01-1.06 0l-1.06-1.06a.75.75 0 111.06-1.06l1.06 1.06a.75.75 0 010 1.06zM15 10a.75.75 0 01-.75.75h-1.5a.75.75 0 010-1.5h1.5A.75.75 0 0115 10zM6.5 10a.75.75 0 01-.75.75h-1.5a.75.75 0 010-1.5h1.5A.75.75 0 016.5 10zM10 7a3 3 0 100 6 3 3 0 000-6z" clip-rule="evenodd" />
                                </svg> Cálculo automático: <span x-text="largo"></span>m x <span x-text="ancho"></span>m = <span x-text="metros"></span>m²
                            </p>
                        </div>
                    </div>
                    <div class="mb-6 bg-slate-50 p-5 rounded-xl border border-gray-200">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-end mb-2 gap-2">
                            <label class="block text-sm font-medium">Descripción <span class="text-red-500">*</span></label>
                            <span class="text-xs font-bold px-2 py-1 rounded-md" :class="{'bg-red-100 text-red-700': numPalabras < 20 || numPalabras > 120, 'bg-slate-100 text-[#003049]': numPalabras >= 20 && numPalabras <= 120}">
                                <span x-text="numPalabras"></span> / 120 palabras (Mín. 20)
                            </span>
                        </div>
                        <textarea name="descripcion" x-model="descripcion" @input="validarDescripcion($event.target)" rows="4" placeholder="Cuéntanos más detalles del inmueble (Mínimo 20 palabras)..." required oninput="this.value = this.value.replace(/[^a-zA-Z0-9\s.,?!;:/\-áéíóúÁÉÍÓÚñÑüÜ\r\n]/g, '')" class="w-full rounded-lg border-input bg-white border py-3 px-4 focus:ring-[#003049]/20 focus:border-[#003049] transition-all" x-init="validarDescripcion($el)"></textarea>
                    </div>

                    {{-- NUEVO: Opciones Específicas para Cuarto  --}}
                    <div class="mt-6 bg-slate-50 p-5 rounded-xl border border-gray-200" x-show="tipo === 'Cuarto'" style="display: none;">
                        <h3 class="font-bold text-[#003049] mb-3 text-sm">Configuración de Cuarto</h3>
                        
                        {{-- Zonas Comunes --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-2">¿Tendrá el inquilino acceso a zonas comunes?</label>
                            <div class="flex gap-4 mb-2">
                                <label class="flex items-center gap-2"><input type="radio" x-model="tieneZonasComunes" value="si" name="tiene_zonas_comunes"> Sí</label>
                                <label class="flex items-center gap-2"><input type="radio" x-model="tieneZonasComunes" value="no" name="tiene_zonas_comunes"> No</label>
                            </div>
                            <div x-show="tieneZonasComunes === 'si'" class="grid grid-cols-2 gap-2 mt-2 bg-white p-3 rounded-lg border">
                                <label class="flex items-center gap-2"><input type="checkbox" name="zonas_comunes[]" value="sala"> Sala</label>
                                <label class="flex items-center gap-2"><input type="checkbox" name="zonas_comunes[]" value="cocina"> Cocina</label>
                                <label class="flex items-center gap-2"><input type="checkbox" name="zonas_comunes[]" value="jardin"> Jardín</label>
                                <label class="flex items-center gap-2"><input type="checkbox" name="zonas_comunes[]" value="patio"> Patio</label>
                            </div>
                        </div>
                    </div>


                </div>

                {{-- ==========================================
                     PASO 3: REGLAS, SERVICIOS Y PAGOS (NUEVO)
                     ========================================== --}}
                <div x-show="step === 3" x-ref="step3" x-transition style="display: none;">
                    <h2 class="text-xl font-bold mb-4 flex items-center gap-2 text-primary">Reglas y Configuración de Pagos</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        {{-- Mobiliario --}}
                        <div>
                            <label class="block text-sm font-medium mb-1">Mobiliario <span class="text-red-500">*</span></label>
                            <select name="estado_mobiliario" required class="w-full rounded-lg border-input bg-white border py-3 px-4">
                                <option value="amueblada">Amueblada</option>
                                <option value="semiamueblada">Semiamueblada</option>
                                <option value="no amueblada">No amueblada</option>
                            </select>
                        </div>
                        
                        {{-- Estacionamiento --}}
                        <div>
                            <label class="block text-sm font-medium mb-1">¿Estacionamiento incluido?</label>
                            <select name="tiene_estacionamiento" class="w-full rounded-lg border-input bg-white border py-3 px-4">
                                <option value="1">Sí</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div>

                    {{-- Mascotas --}}
                    <div class="mb-6 bg-slate-50 p-5 rounded-xl border border-border">
                        <label class="block text-sm font-medium mb-3">¿Estarán permitidas las mascotas?</label>
                        <div class="flex gap-4 mb-3">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" x-model="permiteMascotas" value="si" name="permite_mascotas" class="text-primary focus:ring-primary h-4 w-4">
                                <span class="text-sm font-medium">Sí</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" x-model="permiteMascotas" value="no" name="permite_mascotas" class="text-primary focus:ring-primary h-4 w-4">
                                <span class="text-sm font-medium">No</span>
                            </label>
                        </div>
                        
                        <div x-show="permiteMascotas === 'si'" x-transition class="mt-4 pt-4 border-t border-gray-200">
                            <label class="block text-sm font-medium mb-3 text-slate-700">Selecciona las mascotas permitidas</label>
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                                @php
                                    $listaMascotas = [
                                        'Perros', 'Gatos', 'Pericos y loros', 'Pájaros de canto', 'Peces',
                                        'Hamsters y ratones', 'Conejos', 'Tortugas', 'Iguanas y lagartijas',
                                        'Serpientes', 'Ranas y ajolotes', 'Hurones', 'Arañas y tarántulas',
                                        'Cuyos', 'Pollos y gallinas', 'Otros'
                                    ];
                                @endphp
                                @foreach($listaMascotas as $mascota)
                                <label class="flex items-center gap-2 text-sm cursor-pointer bg-white p-2 rounded-lg border hover:border-primary transition-colors">
                                    <input type="checkbox" name="tipos_mascotas[]" value="{{ \Str::slug($mascota, '_') }}" class="text-primary focus:ring-primary rounded">
                                    <span class="truncate" title="{{ $mascota }}">{{ $mascota }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Selección de Servicios (NUEVO) --}}
                    @php
                        $listaServicios = [
                            'Agua', 'Electricidad', 'Gas', 'Internet',
                            'TV por Cable'
                        ];
                    @endphp

                    <div class="mb-6 bg-slate-50 p-5 rounded-xl border border-border">
                        <label class="block text-sm font-medium mb-3">¿Con qué servicios cuenta el inmueble?</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            @foreach($listaServicios as $servicio)
                            <label class="flex items-center gap-2 text-sm cursor-pointer bg-white p-2 rounded-lg border hover:border-primary transition-colors">
                                <input type="checkbox" x-model="serviciosSeleccionados" value="{{ $servicio }}" name="servicios_incluidos[]" class="text-primary focus:ring-primary rounded">
                                {{ $servicio }}
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Servicios (Matriz dinámica) --}}
                    <div class="mb-6 border rounded-xl overflow-hidden" x-show="serviciosSeleccionados.length > 0" x-transition style="display: none;">
                        <div class="bg-slate-100 p-3 border-b font-medium text-sm text-center">¿Quién será responsable de pagar los servicios?</div>
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-white">
                                    <th class="p-2 text-left font-normal text-slate-500">Servicio</th>
                                    <th class="p-2 text-center font-normal text-slate-500">Inquilino paga</th>
                                    <th class="p-2 text-center font-normal text-slate-500">Arrendador paga</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($listaServicios as $index => $servicio)
                                <tr class="border-t {{ $index % 2 == 0 ? 'bg-slate-50' : 'bg-white' }}" x-show="serviciosSeleccionados.includes('{{ $servicio }}')">
                                    <td class="p-3">{{ $servicio }}</td>
                                    <td class="p-3 text-center">
                                        <input type="radio" name="pago_servicio[{{ \Str::slug($servicio, '_') }}]" value="inquilino" class="w-4 h-4 text-primary" x-bind:required="serviciosSeleccionados.includes('{{ $servicio }}')" x-bind:disabled="!serviciosSeleccionados.includes('{{ $servicio }}')">
                                    </td>
                                    <td class="p-3 text-center">
                                        <input type="radio" name="pago_servicio[{{ \Str::slug($servicio, '_') }}]" value="arrendador" class="w-4 h-4 text-primary" x-bind:disabled="!serviciosSeleccionados.includes('{{ $servicio }}')">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div>
                <label class="block text-sm font-medium mb-1 text-muted-foreground">Momento de Pago <span class="text-red-500">*</span></label>
                <select name="momento_pago" required class="w-full rounded-lg border-input bg-white border py-2 px-3 text-sm">
                    <option value="adelantado">Por adelantado</option>
                    <option value="vencido">A tiempo vencido</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1 text-muted-foreground">Tolerancia (Días) <span class="text-red-500">*</span></label>
                <input type="number" name="dias_tolerancia" value="2" min="0" max="15" required class="w-full rounded-lg border-input bg-white border py-2 px-3 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1 text-muted-foreground">Preaviso Salida (Días) <span class="text-red-500">*</span></label>
                <input type="number" name="dias_preaviso" value="30" min="1" max="31" required class="w-full rounded-lg border-input bg-white border py-2 px-3 text-sm">
            </div>
        </div>

        {{-- Duración del Contrato --}}
        <div class="mb-6 bg-slate-50 p-5 rounded-xl border border-gray-200">
            <div class="flex items-center gap-2 mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#003049]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <label class="text-sm font-bold text-slate-700">Duración del Contrato <span class="text-red-500">*</span></label>
            </div>
            <p class="text-xs text-slate-500 mb-3">Define cuántos meses durará el contrato. El sistema calculará automáticamente la fecha de vencimiento.</p>
            <div class="flex items-center gap-4">
                <div class="relative w-40">
                    <input type="number"
                           name="duracion_contrato_meses"
                           id="duracion_contrato_meses"
                           value="{{ old('duracion_contrato_meses', 12) }}"
                           min="1" max="60" required
                           class="w-full rounded-lg bg-white border border-input py-2 px-3 font-bold text-slate-800 focus:ring-2 focus:ring-primary/20 focus:border-primary text-center transition-all">
                </div>
                <span class="text-sm font-medium text-slate-600">meses</span>
                <div class="flex gap-2">
                    <button type="button" onclick="setDuracion(6)"  class="px-3 py-1.5 text-xs font-bold bg-white border border-gray-200 rounded-lg hover:border-primary hover:text-primary transition-colors">6 meses</button>
                    <button type="button" onclick="setDuracion(12)" class="px-3 py-1.5 text-xs font-bold bg-white border border-gray-200 rounded-lg hover:border-primary hover:text-primary transition-colors">1 año</button>
                    <button type="button" onclick="setDuracion(24)" class="px-3 py-1.5 text-xs font-bold bg-white border border-gray-200 rounded-lg hover:border-primary hover:text-primary transition-colors">2 años</button>
                </div>
            </div>
        </div>

                    {{-- Cláusulas Adicionales  --}}
                    <div class="mb-6 bg-slate-50 p-5 rounded-xl border border-border">
                        <label class="block text-sm font-medium mb-1 text-slate-800">¿Quiere incluir alguna cláusula o información adicional?</label>
                        <p class="text-xs text-slate-500 mb-4 italic">La mayoría de las personas no necesitan incluir cláusulas adicionales.</p>
                        
                        <div class="flex gap-4 mb-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" x-model="incluirClausulas" value="si" class="text-primary focus:ring-primary h-4 w-4">
                                <span class="text-sm font-medium uppercase text-slate-700">Sí</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" x-model="incluirClausulas" value="no" class="text-primary focus:ring-primary h-4 w-4">
                                <span class="text-sm font-medium uppercase text-slate-700">No</span>
                            </label>
                        </div>

                        <div x-show="incluirClausulas === 'si'" x-transition class="space-y-4 pt-2">
                            <template x-for="(clausula, index) in clausulas" :key="index">
                                <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-sm relative group">
                                    <h4 class="font-bold text-sm text-[#003049] mb-2">Cláusula adicional <span x-show="clausulas.length > 1" x-text="index + 1"></span></h4>
                                    <label class="block text-sm font-medium text-slate-600 mb-2">Escriba la cláusula utilizando oraciones completas:</label>
                                    
                                    <textarea x-model="clausulas[index]" rows="2" placeholder="Ej. El inquilino tendrá acceso a la alberca comunitaria todos los viernes de 5:00 pm a 8:00 pm." oninput="this.value = this.value.replace(/[^a-zA-Z0-9\s.,?!;:/\-áéíóúÁÉÍÓÚñÑüÜ\r\n]/g, '')" class="w-full rounded-lg border-input bg-white border-b-2 focus:border-b-primary focus:ring-0 transition-all border-x-0 border-t-0 bg-transparent text-sm resize-none"></textarea>
                                    
                                    {{-- Botón para eliminar cláusula si hay más de una --}}
                                    <button type="button" @click="clausulas.splice(index, 1)" x-show="clausulas.length > 1" class="absolute top-4 right-4 text-red-400 hover:text-red-600 p-1 opacity-0 group-hover:opacity-100 transition-opacity" title="Eliminar cláusula">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </template>
                            
                            <button type="button" @click="clausulas.push('')" class="flex items-center gap-1 text-[#003049] hover:text-[#003049]/90 font-bold text-sm bg-transparent border-none cursor-pointer mt-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Añada otra cláusula
                            </button>
                        </div>
                        
                        {{-- Input oculto que envía todo al backend concatenado, para garantizar compatibilidad --}}
                        <input type="hidden" name="clausulas_extra" x-bind:value="incluirClausulas === 'si' ? clausulas.filter(c => c.trim() !== '').join('\n\n') : ''">
                    </div>

                </div>


                {{-- ==========================================
                     PASO 4: ARCHIVOS
                     ========================================== --}}
                <div x-show="step === 4" x-ref="step4" x-transition style="display: none;">
                    <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-gray-700">
                            <path fill-rule="evenodd" d="M1 8a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 018.07 3h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0016.07 6H17a2 2 0 012 2v7a2 2 0 01-2 2H3a2 2 0 01-2-2V8zm13.5 3a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM10 14a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                        </svg> Archivos de la Propiedad
                    </h2>

                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-1">Subir Fotos <span class="text-red-500">*</span></label>
                        <div class="relative group cursor-pointer hover:bg-slate-50 transition-colors rounded-xl border-2 border-dashed border-gray-300 p-8 flex flex-col items-center justify-center text-center">
                            <input type="file" id="fileInput" name="imagenes[]" multiple accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" @change="handleFileSelect">
                            <div class="text-primary-400 mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-foreground">Haz clic o arrastra más fotos</p>
                        </div>
                    </div>

                    {{-- Grid de Previsualización --}}
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-6" x-show="previews.length > 0">
                        <template x-for="(img, index) in previews" :key="index">
                            <div class="relative group aspect-square rounded-lg shadow-sm border border-gray-200" style="position: relative;">
                                <img :src="img" class="object-cover w-full h-full rounded-lg">
                                <button type="button" @click="removeFile(index)" style="position: absolute; top: -12px; right: -12px; background-color: #EF4444; color: white; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; z-index: 9999; border: 3px solid white; cursor: pointer; box-shadow: 0 4px 6px rgba(0,0,0,0.15);" title="Eliminar foto">
                                    <svg xmlns="http://www.w3.org/2000/svg" style="width: 16px; height: 16px; font-weight: bold;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Controles de Navegación --}}
                <div class="pt-6 border-t border-border flex justify-between items-center mt-6">
                    <button type="button" @click="step--" x-show="step > 1" class="text-muted-foreground hover:text-foreground font-medium px-4 py-2 transition-colors">← Atrás</button>
                    <a href="{{ route('inmuebles.index') }}" x-show="step === 1" class="bg-gray-400 text-white font-bold py-2 px-6 rounded-xl hover:bg-gray-500 transition-all shadow-md">Cancelar</a>
                    
                    <button type="button" @click="nextStep()" x-show="step < 4" class="bg-primary text-primary-foreground font-bold py-2 px-6 rounded-xl hover:bg-primary/90 transition-all shadow-md shadow-primary/20">Siguiente Paso →</button>

                    <button type="submit" @click="validarFotos" x-show="step === 4" class="flex items-center justify-center gap-2 bg-[#003049] text-white px-8 py-3 rounded-xl font-bold text-base border-none cursor-pointer shadow-lg shadow-[#003049]/30 hover:bg-[#003049]/90 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-[#003049]">
                            <path fill-rule="evenodd" d="M10 2a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0v-1.5A.75.75 0 0110 2zM10 15.25a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0v-1.5a.75.75 0 01.75-.75zM13.636 5.864a.75.75 0 010 1.06l-1.06 1.06a.75.75 0 01-1.06-1.06l1.06-1.06a.75.75 0 011.06 0zM7.485 11.485a.75.75 0 010 1.06l-1.06 1.06a.75.75 0 01-1.06-1.06l1.06-1.06a.75.75 0 011.06 0zM14.696 14.696a.75.75 0 01-1.06 0l-1.06-1.06a.75.75 0 111.06-1.06l1.06 1.06a.75.75 0 010 1.06zM7.485 7.485a.75.75 0 01-1.06 0l-1.06-1.06a.75.75 0 111.06-1.06l1.06 1.06a.75.75 0 010 1.06zM15 10a.75.75 0 01-.75.75h-1.5a.75.75 0 010-1.5h1.5A.75.75 0 0115 10zM6.5 10a.75.75 0 01-.75.75h-1.5a.75.75 0 010-1.5h1.5A.75.75 0 016.5 10zM10 7a3 3 0 100 6 3 3 0 000-6z" clip-rule="evenodd" />
                        </svg>
                        <span>¡Publicar Ahora!</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Script de Localización y Mapa --}}
    <script>
        var mapPicker, markerPicker;

        function initMapPicker() {
            if (mapPicker) return;
            setTimeout(() => {
                const osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap contributors'
                });

                const satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                    attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
                });

                mapPicker = L.map('map-picker', {
                    layers: [osm]
                }).setView([16.9068, -92.0941], 14);

                const baseMaps = {
                    'Callejero': osm,
                    'Satélite': satellite
                };
                L.control.layers(baseMaps, null, { collapsed: false, position: 'topright' }).addTo(mapPicker);

                mapPicker.on('click', function (e) {
                    actualizarPin(e.latlng.lat, e.latlng.lng);
                    obtenerDireccionDesdeMapa(e.latlng.lat, e.latlng.lng);
                });

                mapPicker.invalidateSize();
            }, 50);
        }

        function actualizarPin(lat, lng, zoom = null) {
            if (markerPicker) {
                markerPicker.setLatLng([lat, lng]);
            } else {
                markerPicker = L.marker([lat, lng]).addTo(mapPicker);
            }
            document.getElementById('lat-input').value = lat;
            document.getElementById('lng-input').value = lng;

            if (zoom) {
                mapPicker.setView([lat, lng], zoom);
            }
        }

        async function buscarDireccion() {
            const direccionRaw = document.getElementById('direccion-input').value;
            if (!direccionRaw) return;

            // Añadimos contexto para mejorar la precisión en Ocosingo
            const query = encodeURIComponent(direccionRaw + ", Ocosingo, Chiapas, México");
            const btn = event.currentTarget;
            const originalText = btn.innerHTML;

            btn.innerHTML = "🔍 Buscando...";
            btn.disabled = true;

            try {
                const response = await fetch(
                    `https://nominatim.openstreetmap.org/search?format=json&q=${query}&limit=1`);
                const data = await response.json();

                if (data && data.length > 0) {
                    const lat = parseFloat(data[0].lat);
                    const lon = parseFloat(data[0].lon);
                    actualizarPin(lat, lon, 17); // Zoom más cercano al encontrar dirección
                } else {
                    alert("No pudimos encontrar esa calle exacta. ¿Podrías marcar el punto manualmente en el mapa?");
                }
            } catch (error) {
                console.error("Error en geocodificación:", error);
            } finally {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        }

        async function obtenerDireccionDesdeMapa(lat, lng) {
            const inputDireccion = document.getElementById('direccion-input');
            const originalPlaceholder = inputDireccion.placeholder;

            inputDireccion.value = "Buscando dirección exacta...";
            // Efecto visual de carga
            inputDireccion.classList.add('bg-blue-50', 'animate-pulse');

            try {
                const response = await fetch(
                    `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`
                );
                const data = await response.json();

                if (data && data.display_name) {
                    let address = data.display_name;
                    inputDireccion.value = address;
                    inputDireccion.dispatchEvent(new Event('input'));
                } else {
                    inputDireccion.value = "";
                    inputDireccion.placeholder = "No se encontró dirección exacta, escríbela manual";
                }
            } catch (error) {
                console.error("Error al obtener dirección:", error);
                inputDireccion.value = "";
                inputDireccion.placeholder = "Error de conexión, escríbela manual";
            } finally {
                inputDireccion.classList.remove('bg-blue-50', 'animate-pulse');
            }
        }

        function geolocalizar() {
            if (!navigator.geolocation) {
                alert("Tu navegador no soporta geolocalización.");
                return;
            }

            const btn = event.currentTarget;
            const originalContent = btn.innerHTML;
            btn.innerHTML = '<svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
            btn.disabled = true;

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    actualizarPin(lat, lng, 18);
                    obtenerDireccionDesdeMapa(lat, lng);
                    btn.innerHTML = originalContent;
                    btn.disabled = false;
                },
                (error) => {
                    console.error("Error de geolocalización:", error);
                    let msg = "No pudimos obtener tu ubicación.";
                    if(error.code === 1) msg = "Por favor, permite el acceso a tu ubicación en el navegador.";
                    alert(msg);
                    btn.innerHTML = originalContent;
                    btn.disabled = false;
                },
                { enableHighAccuracy: true }
            );
        }

        document.addEventListener('alpine:init', () => {
            Alpine.data('wizardForm', () => ({
                step: 1,
                files: [],
                previews: [],
                largo: '',
                ancho: '',
                metros: "{{ old('metros') }}",
                tipo: "{{ old('tipo', 'Casa') }}",
                
                precio: "{{ old('precio', '') }}",
                requiereDeposito: "{{ old('requiere_deposito', 'no') }}",
                tipoDeposito: 'mensualidad',
                depositoManual: "{{ old('deposito', '') }}",

                tieneZonasComunes: 'no',
                tieneCerradura: 'no',
                permiteMascotas: 'no',
                serviciosSeleccionados: [],
                incluirClausulas: 'no',
                clausulas: [''],
                metodoPagoRegistro: 'clabe',
                tieneDatosBancarios: {{ auth()->check() && auth()->user()->tiene_datos_bancarios ? 'true' : 'false' }},
                usarDatosExistentes: 'si',
                descripcion: `{{ old('descripcion', '') }}`,

                get numPalabras() {
                    const texto = this.descripcion.trim();
                    return texto === '' ? 0 : texto.split(/\s+/).length;
                },

                validarDescripcion(el) {
                    const count = this.numPalabras;
                    if (count < 20) {
                        el.setCustomValidity("La descripción debe tener al menos 20 palabras.");
                    } else if (count > 120) {
                        el.setCustomValidity("La descripción no puede exceder las 120 palabras.");
                    } else {
                        el.setCustomValidity("");
                    }
                },

                get minPrecio() {
                    return 10;
                },

                calcularm2() {
                    if (this.largo && this.ancho) {
                        this.metros = (parseFloat(this.largo) * parseFloat(this.ancho)).toFixed(2);
                    }
                },

                handleFileSelect(event) {
                    const newFiles = Array.from(event.target.files);
                    const limiteBytes = 2 * 1024 * 1024;
                    const archivosValidos = [];

                    newFiles.forEach(file => {
                        if (file.size > limiteBytes) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Archivo omitido',
                                text: `No es posible subir el archivo ${file.name}, porque pesa demasiado.`,
                                confirmButtonColor: '#003049'
                            });
                        } else {
                            archivosValidos.push(file);
                        }
                    });

                    this.files = this.files.concat(archivosValidos);
                    archivosValidos.forEach(file => {
                        const reader = new FileReader();
                        reader.onload = (e) => this.previews.push(e.target.result);
                        reader.readAsDataURL(file);
                    });
                    this.updateInputFiles();
                },

                removeFile(index) {
                    this.files.splice(index, 1);
                    this.previews.splice(index, 1);
                    this.updateInputFiles();
                },

                updateInputFiles() {
                    const dataTransfer = new DataTransfer();
                    this.files.forEach(file => dataTransfer.items.add(file));
                    document.getElementById('fileInput').files = dataTransfer.files;
                },

                validarFotos(e) {
                    if (this.files.length < 5) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'warning',
                            title: 'Faltan fotos',
                            text: 'El inmueble debe de tener al menos 5 fotografías para poder publicarse.',
                            confirmButtonColor: '#003049'
                        });
                        return false;
                    }
                    return true;
                },

                nextStep() {
                    let currentDiv = this.$refs['step' + this.step];
                    let inputs = currentDiv.querySelectorAll(
                        'input:required, select:required, textarea:required');

                    let esValido = true;
                    for (let input of inputs) {
                        if (!input.checkValidity()) {
                            // Validaciones que están ocultas no prevendrán navegación
                            if(input.offsetWidth > 0 || input.offsetHeight > 0) {
                                console.error("Validación fallida en campo:", input.name);
                                input.reportValidity();
                                input.focus();
                                esValido = false;
                                break;
                            }
                        }
                    }

                    if (esValido) {
                        this.step++;
                        if (this.step === 2) {
                            initMapPicker();
                        }
                    }
                }
            }))
        });
    }
    // Buscar dirección usando Nominatim (OpenStreetMap)
    function buscarDireccion() {
        const query = document.getElementById('direccion-input').value;
        if (!query) return;

        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    const lat = data[0].lat;
                    const lon = data[0].lon;
                    map.setView([lat, lon], 16);
                    marker.setLatLng([lat, lon]);
                    document.getElementById('lat-input').value = lat;
                    document.getElementById('lng-input').value = lon;
                } else {
                    alert("No se encontró la dirección. Intenta ser más específico.");
                }
            })
            .catch(error => console.error('Error:', error));
    }

    // Obtener ubicación actual del usuario
    function obtenerUbicacionActual() {
        if (!navigator.geolocation) {
            alert("Tu navegador no soporta geolocalización.");
            return;
        }

        const btn = event.currentTarget;
        const originalContent = btn.innerHTML;
        btn.innerHTML = '<svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
        btn.disabled = true;

        navigator.geolocation.getCurrentPosition(
            (position) => {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                map.setView([lat, lng], 17);
                marker.setLatLng([lat, lng]);
                document.getElementById('lat-input').value = lat;
                document.getElementById('lng-input').value = lng;
                btn.innerHTML = originalContent;
                btn.disabled = false;
                
                // Opcional: Reversar geocodificación para el input
                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                    .then(r => r.json())
                    .then(data => {
                        if(data.display_name) document.getElementById('direccion-input').value = data.display_name;
                    });
            },
            (error) => {
                alert("Error al obtener ubicación: " + error.message);
                btn.innerHTML = originalContent;
                btn.disabled = false;
            },
            { enableHighAccuracy: true }
        );
    }
    window.onload = initMap;
</script>
@endsection