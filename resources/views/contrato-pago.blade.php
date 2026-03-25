<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generar Contrato y Pagar - ArrendaOco</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#003049',
                        success: '#10b981',
                        danger: '#ef4444'
                    }
                }
            }
        }
    </script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Signature Pad -->
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-50 text-gray-800 font-sans antialiased">
    <!-- Main Wrapper -->
    <div class="min-h-screen flex flex-col justify-center items-center py-10 px-4" x-data="wizardPago()">
        
        <!-- Contenedor Principal (Wizard) -->
        <div class="w-full max-w-4xl bg-white shadow-xl rounded-2xl overflow-hidden" x-show="!isSuccess" x-transition>
            
            <!-- Header / Stepper -->
            <div class="bg-primary px-6 sm:px-8 py-6 text-white flex justify-between items-center sm:flex-row flex-col gap-4">
                <div class="text-xl font-bold">Generar Contrato y Pagar</div>
                
                <!-- Steppers -->
                <div class="flex items-center space-x-2 sm:space-x-4 text-sm font-medium">
                    <!-- Paso 1 -->
                    <div class="flex items-center" :class="{ 'text-white': step >= 1, 'text-gray-400': step < 1 }">
                        <span class="w-8 h-8 flex items-center justify-center rounded-full border-2"
                              :class="{ 'bg-white text-primary border-white': step >= 1, 'border-gray-400': step < 1 }">1</span>
                        <span class="ml-2 hidden sm:inline">Detalles</span>
                    </div>
                    <div class="w-4 sm:w-8 h-px" :class="{ 'bg-white': step >= 2, 'bg-gray-400': step < 2 }"></div>
                    
                    <!-- Paso 2 -->
                    <div class="flex items-center" :class="{ 'text-white': step >= 2, 'text-gray-400': step < 2 }">
                        <span class="w-8 h-8 flex items-center justify-center rounded-full border-2"
                              :class="{ 'bg-white text-primary border-white': step >= 2, 'border-gray-400': step < 2 }">2</span>
                        <span class="ml-2 hidden sm:inline">Firma Digital</span>
                    </div>
                    <div class="w-4 sm:w-8 h-px" :class="{ 'bg-white': step >= 3, 'bg-gray-400': step < 3 }"></div>
                    
                    <!-- Paso 3 -->
                    <div class="flex items-center" :class="{ 'text-white': step >= 3, 'text-gray-400': step < 3 }">
                        <span class="w-8 h-8 flex items-center justify-center rounded-full border-2"
                              :class="{ 'bg-white text-primary border-white': step >= 3, 'border-gray-400': step < 3 }">3</span>
                        <span class="ml-2 hidden sm:inline">Confirmar</span>
                    </div>
                </div>
            </div>

            <!-- Formularios del Wizard -->
            <form id="wizard-form" method="POST" action="{{ route('pagos.test.success.process', $inmueble->id) }}">
                @csrf
                <input type="hidden" name="metodo_pago" value="{{ request('metodo_pago', 'card') }}">
                <input type="hidden" name="firma_digital" id="firma_digital_input">
                <div class="p-6 sm:p-8">
                
                <!-- Paso 1: Detalles de Estancia -->
                <div x-show="step === 1" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    <h2 class="text-2xl font-semibold mb-6 text-primary">Detalles de la Estancia</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de inicio de arrendamiento</label>
                            <input type="date" name="fecha_inicio" x-model="formData.fechaInicio" required min="{{ date('Y-m-d') }}" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary outline-none transition">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Plazo de arrendamiento</label>
                            <select name="plazo" id="plazo-select" x-model="formData.plazo" required class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary outline-none transition bg-white">
                                <option value="">Selecciona un plazo</option>
                                <option value="4 meses">4 meses</option>
                                <option value="1 año">1 año</option>
                                <option value="2 años">2 años</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                        
                        <div x-show="formData.plazo === 'Otro'" class="md:col-span-2" x-transition>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Especificar plazo en meses</label>
                            <input type="number" min="1" max="120" step="1" x-model="formData.plazoOtro" placeholder="Ej. 6" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary outline-none transition">
                            <p class="text-xs text-gray-500 mt-2 font-medium" x-show="formData.plazoOtro >= 12" x-text="formatMeses(formData.plazoOtro)"></p>
                        </div>
                    </div>
                </div>

                <!-- Paso 2: Firma Digital -->
                <div x-show="step === 2" style="display: none;" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    <h2 class="text-2xl font-semibold mb-6 text-primary">Firma Digital</h2>
                    
                    <div class="max-w-2xl mx-auto">
                        <!-- Firma Digital -->
                        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm flex flex-col">
                            <h3 class="text-lg font-medium text-gray-800 mb-4 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                Firma Digital
                            </h3>
                            <p class="text-xs text-gray-500 mb-3">Dibuja tu firma en el recuadro inferior para aceptar los términos del contrato de arrendamiento.</p>
                            
                            <div class="flex-grow border-2 border-dashed border-gray-300 rounded-xl bg-slate-50 relative min-h-[160px]">
                                <canvas id="signature-pad" class="absolute top-0 left-0 w-full h-full rounded-xl cursor-crosshair"></canvas>
                            </div>
                            <div class="flex justify-end gap-4 mt-3">
                                <button @click="undoSignature" type="button" class="text-sm text-gray-500 hover:text-[#003049] font-medium transition-colors focus:outline-none flex items-center gap-1 bg-gray-100 hover:bg-gray-200 px-3 py-1.5 rounded-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" /></svg>
                                    Deshacer trazo
                                </button>
                                <button @click="clearSignature" type="button" class="text-sm text-red-500 hover:text-red-700 font-medium transition-colors focus:outline-none flex items-center gap-1 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    Limpiar todo
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Paso 3: Previsualización y Pago Final -->
                <div x-show="step === 3" style="display: none;" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    <h2 class="text-2xl font-semibold mb-6 text-primary">Previsualización de Contrato y Pago</h2>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Preview Contrato (Simulado) -->
                        <div class="lg:col-span-2 border border-gray-200 rounded-xl bg-slate-100 p-4 h-96 overflow-y-auto shadow-inner relative">
                            <div class="p-8 bg-white shadow-sm border border-gray-200 rounded text-sm text-gray-700 space-y-4 font-serif leading-relaxed">
                                <h3 class="text-center font-bold text-xl mb-6 uppercase text-gray-800">CONTRATO DE ARRENDAMIENTO DE HABITACIÓN TEMPORAL</h3>
                                
                                <p class="text-justify">El presente contrato de arrendamiento de habitación temporal es celebrado en <strong>{{ $inmueble->ciudad ?? 'Ocosingo, Chiapas' }}</strong> en fecha <strong>{{ date('d') }}</strong> de <strong>{{ \Carbon\Carbon::now()->translatedFormat('F') }}</strong> del <strong>{{ date('Y') }}</strong> (en adelante, el "Contrato").</p>
                                
                                <h4 class="font-bold text-md mt-6 mb-2 text-gray-800">ENTRE</h4>
                                <p class="text-justify"><strong>{{ optional($inmueble->propietario)->nombre ?? 'El Arrendador' }}</strong>, con número de credencial para votar (INE) ________________________, actuando en su propio nombre y derecho. De aquí en adelante el “Arrendador”.</p>
                                <p class="text-center my-2 text-gray-500">- Y -</p>
                                <p class="text-justify"><strong>{{ auth()->user()->nombre ?? 'El Inquilino' }}</strong>, con número de credencial para votar (INE) ________________________, actuando en su propio nombre y derecho. De aquí en adelante el “Inquilino”.</p>
                                <p class="mt-4 text-justify">Estos serán considerados individualmente como la “Parte” y conjuntamente como las “Partes”.</p>

                                <h4 class="font-bold text-lg mt-8 mb-4 border-b border-gray-200 pb-2 text-gray-800">DECLARACIONES</h4>
                                
                                <p class="font-bold text-gray-800 mt-4 mb-2">EL ARRENDADOR DECLARA:</p>
                                <ul class="list-[lower-roman] list-inside space-y-2 ml-4 text-gray-600 text-justify">
                                    <li>Que es de su voluntad rentar al Inquilino la Habitación descrita en la cláusula primera de este Contrato.</li>
                                    <li>Que la Habitación reúne las condiciones de higiene y salubridad exigidas por la ley estatal y federal en la materia.</li>
                                    <li>Que dispone de poder y capacidad legal suficiente para poder celebrar el presente Contrato y que no existe ningún impedimento ni limitación que impida lo anterior.</li>
                                </ul>

                                <p class="font-bold text-gray-800 mt-6 mb-2">EL INQUILINO DECLARA:</p>
                                <ul class="list-[lower-roman] list-inside space-y-2 ml-4 text-gray-600 text-justify">
                                    <li>Que está interesado en rentar la Habitación para su uso habitacional.</li>
                                    <li>Que conoce las características y estado de conservación actual de la Habitación.</li>
                                    <li>Que destinará la Habitación única y exclusivamente para fines de habitación, no pudiendo utilizar la misma con fines diferentes a los expresamente acordados en este Contrato.</li>
                                    <li>Que tiene capacidad legal suficiente y adecuada para poder celebrar el presente Contrato, y no tiene ningún impedimento ni limitación que le impida realizar lo anterior.</li>
                                </ul>

                                <p class="mt-6 text-justify">En virtud de lo anterior, las Partes deciden suscribir este Contrato, el cual se regirá de conformidad con lo indicado en las siguientes:</p>

                                <h4 class="font-bold text-lg text-center mt-10 mb-6 uppercase tracking-wider text-gray-800">CLÁUSULAS</h4>

                                <p class="font-bold text-gray-800 mt-6 mb-2">OBJETO DEL CONTRATO Y FINALIDAD DE USO</p>
                                <ol class="list-decimal list-outside space-y-3 ml-6 text-gray-600 text-justify marker:font-semibold">
                                    <li>Mediante este Contrato, el Arrendador acepta alquilar al Inquilino una habitación de la vivienda localizada en <strong>{{ $inmueble->titulo }}</strong> (la "Habitación").</li>
                                    <li>La Habitación se destinará única y exclusivamente con fines de habitación, sin que el Inquilino pueda utilizarla para una finalidad diferente a las expresamente indicadas en este Contrato.</li>
                                    <li>Salvo permiso expreso por escrito por parte del Arrendador, el Inquilino no podrá utilizar la Habitación como lugar de negocio o trabajo.</li>
                                </ol>

                                <p class="font-bold text-gray-800 mt-6 mb-2">HABITACIÓN ARRENDADA</p>
                                <ol class="list-decimal list-outside space-y-3 ml-6 text-gray-600 text-justify marker:font-semibold" start="4">
                                    <li>La Habitación se renta sin ningún tipo de mueble.</li>
                                    <li>La Habitación incluye cerradura propia.</li>
                                    <li>Salvo disposición contraria en el Contrato, el alquiler de la Habitación no incluye plaza de estacionamiento.</li>
                                </ol>

                                <p class="font-bold text-gray-800 mt-6 mb-2">DURACIÓN Y PRÓRROGAS</p>
                                <ol class="list-decimal list-outside space-y-3 ml-6 text-gray-600 text-justify marker:font-semibold" start="7">
                                    <li>Este Contrato tendrá una duración de un (1) año a contar desde las 12:00 pm del 18 de marzo del 2026.</li>
                                    <li>Si después de terminar el Plazo, el Inquilino continúa sin oposición en el uso y goce de la Habitación continuará el arrendamiento por tiempo indeterminado...</li>
                                </ol>

                                <p class="font-bold text-gray-800 mt-6 mb-2">ACCESO ANTICIPADO</p>
                                <ol class="list-decimal list-outside space-y-3 ml-6 text-gray-600 text-justify marker:font-semibold" start="9">
                                    <li>Aunque la fecha de comienzo de este Contrato es el 18 de marzo del 2026, las Partes acuerdan que el Inquilino tendrá derecho a acceder a la vivienda y a la Habitación de manera anticipada a partir de las 12:00 del mediodía.</li>
                                </ol>

                                <p class="font-bold text-gray-800 mt-6 mb-2">RENTA Y DEPÓSITO</p>
                                <ol class="list-decimal list-outside space-y-3 ml-6 text-gray-600 text-justify marker:font-semibold" start="10">
                                    <li>El Inquilino deberá pagar al Arrendador <strong>${{ number_format($inmueble->renta_mensual, 2) }} MXN mensuales</strong> por el alquiler de la Habitación (la "Renta").</li>
                                    <li>A efectos de lo anterior, mientras el Contrato esté en vigor, el Inquilino deberá pagar la Renta al Arrendador mensualmente y por adelantado dentro de los cinco primeros días de cada mes.</li>
                                    <li value="19">A la firma del presente Contrato el Inquilino entrega al Arrendador una (1) mensualidad de la Renta en concepto de fianza o depósito de garantía (la "Fianza").</li>
                                </ol>

                                <p class="font-bold text-gray-800 mt-6 mb-2">NORMAS DE CONVIVENCIA Y REGLAS A RESPETAR</p>
                                <ol class="list-decimal list-outside space-y-3 ml-6 text-gray-600 text-justify marker:font-semibold" start="37">
                                    <li>El Inquilino tendrá permitido tener mascotas en la Habitación.</li>
                                    <li>El Inquilino podrá utilizar las zonas comunes de la vivienda que incluye la Habitación: cocina.</li>
                                    <li>El Inquilino tendrá prohibido fumar tanto en la Habitación como en las zonas comunes de la vivienda.</li>
                                    <li value="42">Quedan rigurosamente prohibidas en la Habitación... toda aquella actividad, comportamiento o actuación que resulte ruidosa, molesta, nociva, insalubre o peligrosa.</li>
                                </ol>

                                <div class="bg-gray-50 border border-gray-200 p-4 rounded-xl mt-8 mb-6 italic text-gray-500 text-center text-xs shadow-inner">
                                    [Las cláusulas restantes hasta la septuagésima séptima (77) se han omitido en esta previsualización simplificada, pero forman parte íntegra del contrato general y el usuario las acepta todas al firmar.]
                                </div>

                                <p class="mt-8 mb-10 text-center text-gray-800 font-medium">Firman de conformidad todas las Partes por duplicado en fecha ___ de___________________ del 20_____.</p>

                                <!-- Bloque de firmas estructurado -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-12 mt-4 pt-6 text-center text-sm">
                                    
                                    <!-- Firma Arrendador (Pendiente) -->
                                    <div class="flex flex-col items-center">
                                        <div class="h-24 flex items-center justify-center mb-2 w-full bg-amber-50 rounded border border-amber-200 p-3">
                                            <p class="text-[9px] text-amber-800 font-medium leading-tight text-center">
                                                * Pendiente de firma del Arrendador. Se le notificará y validará la transacción. Por consiguiente, la renta AÚN NO ESTÁ APROBADA de forma definitiva hasta confirmar su ocupación.
                                            </p>
                                        </div>
                                        <div class="w-full border-t border-gray-400 pt-2">
                                            <p class="font-bold text-xs uppercase">Firma del Arrendador</p>
                                            <p class="text-xs text-gray-500 mt-1 px-4">En su condición de Arrendador y en propio nombre y derecho.</p>
                                            <p class="font-medium mt-1">{{ optional($inmueble->propietario)->nombre ?? 'El Arrendador' }}</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Firma Inquilino -->
                                    <div class="flex flex-col items-center">
                                        <!-- Contenedor relativo para recibir la firma inyectada -->
                                        <div class="h-20 w-full mb-2 relative" id="signature-preview">
                                            <!-- Aquí se inyectará la imagen de la firma desde el SignaturePad en base64 -->
                                            <div class="absolute inset-0 flex items-center justify-center text-gray-300 italic text-xs">
                                                (Pendiente de firma en el paso anterior)
                                            </div>
                                        </div>
                                        <div class="w-full border-t border-gray-800 pt-2">
                                            <p class="font-bold text-xs uppercase">Firma del Inquilino</p>
                                            <p class="text-xs text-gray-500 mt-1 px-4">En su condición de Inquilino y en su propio nombre y derecho.</p>
                                            <p class="font-medium mt-1 text-gray-600">{{ auth()->user()->nombre ?? 'El Inquilino' }}</p>
                                        </div>
                                    </div>

                                    <!-- Primer Testigo -->
                                    <div class="flex flex-col items-center">
                                        <div class="h-16 flex items-end justify-center mb-2 w-full"></div>
                                        <div class="w-full border-t border-gray-300 pt-2 text-gray-400">
                                            <p class="font-bold text-xs uppercase">Primer Testigo</p>
                                            <p class="text-xs mt-1">En su condición de primer testigo.</p>
                                        </div>
                                    </div>

                                    <!-- Segundo Testigo -->
                                    <div class="flex flex-col items-center">
                                        <div class="h-16 flex items-end justify-center mb-2 w-full"></div>
                                        <div class="w-full border-t border-gray-300 pt-2 text-gray-400">
                                            <p class="font-bold text-xs uppercase">Segundo Testigo</p>
                                            <p class="text-xs mt-1">En su condición de segundo testigo.</p>
                                        </div>
                                    </div>

                                </div>

                                <p class="mt-12 pt-6 border-t border-gray-200 text-center text-[10px] text-gray-400">
                                    El Inquilino reconoce haber recibido copia duplicada de este Contrato firmado por las Partes en fecha _____ de ______________________ del 20____<br>
                                    ©2002-2026 ArrendaOco
                                </p>
                            </div>
                        </div>
                        
                        <!-- Resumen y Pago Checkout -->
                        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-between h-full">
                            <div>
                                <h3 class="text-xl font-bold text-gray-800 mb-6 border-b pb-2">Resumen de Cargo</h3>
                                <div class="space-y-4 text-gray-600">
                                    <div class="flex justify-between items-center">
                                        <span>Mensualidad (Mes 1)</span>
                                        <span class="font-semibold text-gray-800">${{ number_format($inmueble->renta_mensual, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span>Depósito en Garantía</span>
                                        <span class="font-semibold text-gray-800">${{ number_format($inmueble->deposito ?? 0, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center text-sm">
                                        <span>Comisión por servicio plataforma</span>
                                        <span class="font-semibold text-green-600">GRATIS</span>
                                    </div>
                                    
                                    <div class="border-t border-gray-200 pt-4 mt-4 flex justify-between items-end">
                                        <div>
                                            <span class="block text-sm font-medium text-gray-500">Total a Pagar Hoy</span>
                                        </div>
                                        <span class="font-bold text-3xl text-primary">${{ number_format($inmueble->renta_mensual + ($inmueble->deposito ?? 0), 2) }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-8 space-y-4">
                                <button type="button" @click="procesarPago" :disabled="isProcessing" 
                                    class="w-full bg-primary hover:bg-[#002236] text-white font-bold py-4 px-6 rounded-xl shadow-md transition-all flex justify-center items-center disabled:opacity-75 disabled:cursor-not-allowed transform active:scale-[0.98]">
                                    <span x-show="!isProcessing" class="text-lg">Pagar Ahora</span>
                                    <span x-show="isProcessing" class="flex items-center text-lg">
                                        <svg class="animate-spin -ml-1 mr-3 h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Procesando...
                                    </span>
                                </button>
                                
                                <div class="bg-amber-50 rounded p-3 text-center border border-amber-200">
                                    <label class="inline-flex items-center text-xs text-amber-800 font-medium cursor-pointer">
                                        <input type="checkbox" x-model="simularError" class="rounded border-amber-300 text-amber-600 shadow-sm focus:border-amber-300 focus:ring focus:ring-amber-200 focus:ring-opacity-50">
                                        <span class="ml-2">Modo Pruebas: Forzar error en pasarela de pago</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                </div>
            </form>
            
            <!-- Flujo de Botones de Navegación -->
            <div class="bg-gray-50 px-6 sm:px-8 py-5 border-t border-gray-200 flex justify-between items-center">
                <button type="button" @click="step--" x-show="step > 1 && !isProcessing" class="px-6 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium shadow-sm">
                    Volver Atrás
                </button>
                <div x-show="step === 1 || isProcessing" class="flex-grow"></div> <!-- Spacer -->
                
                <div class="ml-auto">
                    <button type="button" @click="nextStep" x-show="step < 3" class="px-8 py-2.5 bg-primary text-white rounded-lg hover:bg-[#002236] shadow-md transition font-medium flex items-center">
                        Siguiente Paso
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                </div>
            </div>
            
        </div>

        <!-- Pantalla de Éxito (Success State) -->
        <div class="w-full max-w-3xl bg-white shadow-2xl rounded-2xl overflow-hidden text-center border border-gray-100" style="display: none;" x-show="isSuccess" x-transition:enter="transition ease-out duration-700 transform" x-transition:enter-start="opacity-0 scale-95 translate-y-8" x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            
            <div class="bg-success text-white py-12 px-6">
                <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-white bg-opacity-20 mb-6 backdrop-blur-sm border-4 border-white shadow-inner">
                    <svg class="h-12 w-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h2 class="text-3xl sm:text-4xl font-extrabold mb-2">¡Renta Confirmada!</h2>
                <h3 class="text-xl sm:text-2xl font-medium opacity-90">Contrato Firmado Exitosamente</h3>
            </div>
            
            <div class="p-8 sm:p-12">
                <p class="text-gray-600 mb-10 text-lg leading-relaxed max-w-xl mx-auto">
                    Tu pago se ha procesado con éxito y el contrato digital ha sido almacenado. 
                    El arrendador ha sido notificado y pronto pondrá en contacto contigo para la entrega de llaves.
                </p>
                
                <div class="flex flex-col sm:flex-row justify-center items-center gap-4 mb-8">
                    <button class="w-full sm:w-auto flex items-center justify-center px-6 py-3.5 border-2 border-primary text-primary font-bold rounded-xl hover:bg-primary hover:text-white transition group">
                        <svg class="w-5 h-5 mr-3 group-hover:animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Descargar Contrato PDF
                    </button>
                    
                    <button class="w-full sm:w-auto flex items-center justify-center px-6 py-3.5 border-2 border-gray-300 text-gray-700 font-bold rounded-xl hover:bg-gray-100 transition">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                        Compartir Contrato
                    </button>
                </div>
                
                <hr class="border-gray-200 w-32 mx-auto mb-8">
                
                <button onclick="window.location.href='/mis-rentas'" class="w-full sm:w-80 mx-auto flex items-center justify-center px-8 py-4 bg-primary text-white font-bold rounded-xl hover:bg-[#002236] hover:shadow-lg hover:-translate-y-1 transition-all duration-300 text-lg">
                    Ir a Mi Renta
                    <svg class="w-6 h-6 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </div>
        </div>

    </div>

    <!-- Scripts de Alpine y Lógica del Formulario -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('wizardPago', () => ({
                step: 1,
                isProcessing: false,
                isSuccess: false,
                simularError: false,
                signaturePad: null,
                
                formData: {
                    fechaInicio: '',
                    plazo: '',
                    plazoOtro: ''
                },

                init() {
                    // Inicializar Signature Pad cuando lleguemos al paso 2
                    this.$watch('step', value => {
                        if (value === 2 && !this.signaturePad) {
                            // Pequeño timeout para permitir que el DOM se muestre antes de inicializar canvas
                            setTimeout(() => {
                                const canvas = document.getElementById('signature-pad');
                                
                                // Ajusta el tamaño del canvas nativamente
                                function resizeCanvas() {
                                    const ratio =  Math.max(window.devicePixelRatio || 1, 1);
                                    canvas.width = canvas.offsetWidth * ratio;
                                    canvas.height = canvas.offsetHeight * ratio;
                                    canvas.getContext("2d").scale(ratio, ratio);
                                }
                                
                                window.addEventListener("resize", resizeCanvas);
                                resizeCanvas();
                                
                                this.signaturePad = new SignaturePad(canvas, {
                                    penColor: "#003049", // Color primario de ArrendaOco
                                    minWidth: 1.5,
                                    maxWidth: 3
                                });
                            }, 350); // Tiempo levemente mayor que la animación de Alpine
                        }
                        
                        // Para el paso 3, inyectar la imagen de la firma en la previsualización
                        if (value === 3 && this.signaturePad && !this.signaturePad.isEmpty()) {
                            const preview = document.getElementById('signature-preview');
                            if(preview) {
                                preview.innerHTML = `<img src="${this.signaturePad.toDataURL()}" class="w-full h-full object-contain absolute bottom-0 left-0">`;
                            }
                        }
                    });
                },

                clearSignature() {
                    if (this.signaturePad) {
                        this.signaturePad.clear();
                    }
                },

                undoSignature() {
                    if (this.signaturePad) {
                        const data = this.signaturePad.toData();
                        if (data) {
                            data.pop(); // Elimina el último trazo
                            this.signaturePad.fromData(data);
                        }
                    }
                },

                nextStep() {
                    // Validaciones del Paso 1
                    if (this.step === 1) {
                        if (!this.formData.fechaInicio || !this.formData.plazo || (this.formData.plazo === 'Otro' && !this.formData.plazoOtro)) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Datos Incompletos',
                                text: 'Por favor, llena todos los detalles de la estancia antes de continuar.',
                                confirmButtonColor: '#003049',
                                customClass: {
                                    confirmButton: 'rounded-lg px-6 py-2 shadow-sm'
                                }
                            });
                            return;
                        }

                        // Validar fecha futura o igual a hoy (evitar fechas pasadas)
                        const SelectedDate = new Date(this.formData.fechaInicio + 'T00:00:00');
                        const Today = new Date();
                        Today.setHours(0, 0, 0, 0);
                        
                        if (SelectedDate < Today) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Fecha Inválida',
                                text: 'La fecha de inicio no puede ser anterior al día de hoy.',
                                confirmButtonColor: '#003049'
                            });
                            return;
                        }

                        // Validar que el plazo no sea muy grande o inválido
                        if (this.formData.plazo === 'Otro') {
                            const meses = Number(this.formData.plazoOtro);
                            if (!Number.isInteger(meses) || meses < 1 || meses > 120) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Plazo Inválido',
                                    text: 'Por favor ingresa un número entero de meses (entre 1 y 120).',
                                    confirmButtonColor: '#003049'
                                });
                                return;
                            }
                        }

                        this.step++;
                    } 
                    // Validaciones del Paso 2
                    else if (this.step === 2) {
                        if (!this.signaturePad || this.signaturePad.isEmpty()) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Firma Requerida',
                                text: 'Para avanzar, debes plasmar tu firma digital aceptando los términos.',
                                confirmButtonColor: '#003049',
                                customClass: {
                                    confirmButton: 'rounded-lg px-6 py-2 shadow-sm'
                                }
                            });
                            return;
                        }
                        // Aquí también se podrían agregar validaciones para los campos de tarjeta reales
                        this.step++;
                    }
                },

                formatMeses(mesesStr) {
                    const meses = parseInt(mesesStr);
                    if (isNaN(meses) || meses < 12) return '';
                    const anos = Math.floor(meses / 12);
                    const mesesRestantes = meses % 12;
                    
                    let str = anos === 1 ? '1 año' : anos + ' años';
                    if (mesesRestantes > 0) {
                        str += ' y ' + (mesesRestantes === 1 ? '1 mes' : mesesRestantes + ' meses');
                    }
                    return '(' + str + ')';
                },

                procesarPago() {
                    this.isProcessing = true;
                    
                    // Simular latencia de petición asíncrona (como Stripe o pasarela bancaria)
                    setTimeout(() => {
                        this.isProcessing = false;
                        
                        const errors = [
                            "Fondos insuficientes en la tarjeta proporcionada.",
                            "Error temporal en la pasarela, intenta más tarde.",
                            "Múltiples intentos fallidos, la tarjeta ha sido declinada.",
                            "El código de seguridad CVC es incorrecto."
                        ];
                        
                        // Una probabilidad baja aleatoria (15%) para fallar, si no está activado el switch manual
                        const randomFail = Math.random() < 0.15; 
                        
                        if (this.simularError || randomFail) {
                            const errorMessage = errors[Math.floor(Math.random() * errors.length)];
                            Swal.fire({
                                icon: 'error',
                                title: 'Pago Declinado',
                                text: this.simularError ? "Este es un error forzado (Modo Pruebas activado)." : errorMessage,
                                confirmButtonColor: '#003049',
                                customClass: {
                                    confirmButton: 'rounded-lg px-6 py-2 shadow-sm'
                                }
                            });
                        } else {
                            // Flujo Exitoso - Modificamos esto para POSTEAR el formulario
                            document.getElementById('firma_digital_input').value = this.signaturePad.toDataURL();
                            
                            if (this.formData.plazo === 'Otro') {
                                document.getElementById('plazo-select').value = this.formData.plazoOtro + ' meses';
                            }
                            
                            document.getElementById('wizard-form').submit();
                        }
                        
                    }, 2500); // 2.5 segundos de "procesamiento"
                }
            }));
        });
    </script>
</body>
</html>
