@extends('layouts.app')

@section('title', 'Publicar Inmueble')

@section('content')
    <div class="max-w-3xl mx-auto" x-data="wizardForm">

        {{-- Encabezado --}}
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-foreground mb-4">Publicar Propiedad</h1>
            <div class="flex items-center justify-center gap-4 relative">
                <div class="absolute top-1/2 left-0 w-full h-1 bg-gray-200 -z-10 rounded-full"></div>
                <div class="flex flex-col items-center cursor-pointer" @click="if(step > 1) step = 1">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold border-4 transition-colors bg-white z-10"
                        :class="step >= 1 ? 'border-primary text-primary' : 'border-gray-300 text-gray-400'">1</div>
                    <span class="text-xs font-medium mt-1 bg-background px-1"
                        :class="step >= 1 ? 'text-primary' : 'text-gray-400'">Básico</span>
                </div>
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold border-4 transition-colors bg-white z-10"
                        :class="step >= 2 ? 'border-primary text-primary' : 'border-gray-300 text-gray-400'">2</div>
                    <span class="text-xs font-medium mt-1 bg-background px-1"
                        :class="step >= 2 ? 'text-primary' : 'text-gray-400'">Detalles</span>
                </div>
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold border-4 transition-colors bg-white z-10"
                        :class="step >= 3 ? 'border-primary text-primary' : 'border-gray-300 text-gray-400'">3</div>
                    <span class="text-xs font-medium mt-1 bg-background px-1"
                        :class="step >= 3 ? 'text-primary' : 'text-gray-400'">Fotos</span>
                </div>
            </div>
        </div>

        {{-- Formulario --}}
        <div class="bg-card border border-border rounded-2xl shadow-lg p-6 sm:p-8">
            <form method="POST" action="{{ route('inmuebles.guardar') }}" class="space-y-6" enctype="multipart/form-data">
                @csrf

                {{-- PASO 1 --}}
                <div x-show="step === 1" x-ref="step1"
                    x-transition:enter="transition opacity-0 transform translate-x-4 ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                    <h2 class="text-xl font-bold mb-4 flex items-center gap-2">🏠 ¿Qué vas a rentar?</h2>
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="block text-sm font-medium mb-1">Nombre del Anuncio <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="nombre" value="{{ old('nombre') }}"
                                placeholder="Ej. Depa moderno cerca de la UTC" required
                                class="w-full rounded-lg border-input bg-background/50 border py-3 px-4 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Dirección <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="direccion" value="{{ old('direccion') }}"
                                placeholder="Calle, Número, Colonia..." required
                                class="w-full rounded-lg border-input bg-background/50 border py-3 px-4 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Tipo <span
                                        class="text-red-500">*</span></label>
                                <select name="tipo" required
                                    class="w-full rounded-lg border-input bg-background/50 border py-3 px-4">
                                    <option value="Casa" {{ old('tipo') == 'Casa' ? 'selected' : '' }}>Casa</option>
                                    <option value="Departamento" {{ old('tipo') == 'Departamento' ? 'selected' : '' }}>
                                        Departamento</option>
                                    <option value="Cuarto" {{ old('tipo') == 'Cuarto' ? 'selected' : '' }}>Cuarto</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Precio <span
                                        class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span class="absolute left-3 top-3 text-gray-500">$</span>
                                    <input type="number" name="precio" value="{{ old('precio') }}" placeholder="0.00"
                                        required
                                        class="w-full rounded-lg border-input bg-background/50 border py-3 pl-8 px-4">
                                </div>
                            </div>
                            {{-- 
                            <div>
                                <label class="block text-sm font-medium mb-1">Depósito <span
                                        class="text-xs text-muted-foreground font-normal">(Opcional)</span></label>
                                <div class="relative">
                                    <span class="absolute left-3 top-3 text-gray-500">$</span>
                                    <input type="number" name="deposito" value="{{ old('deposito') }}" placeholder="0.00"
                                        class="w-full rounded-lg border-input bg-background/50 border py-3 pl-8 px-4">
                                </div>
                            </div>
                            --}}
                        </div>
                    </div>
                </div>

                {{-- PASO 2 --}}
                <div x-show="step === 2" x-ref="step2" x-transition style="display: none;">
                    <h2 class="text-xl font-bold mb-4 flex items-center gap-2">✨ Características y Ubicación</h2>

                    {{-- 
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Dirección Completa <span
                                class="text-red-500">*</span></label>
                        <div class="flex gap-2">
                            <input type="text" name="direccion" id="direccion-input" value="{{ old('direccion') }}"
                                placeholder="Calle, Número, Colonia..." required
                                class="flex-1 rounded-lg border-input bg-background/50 border py-3 px-4">
                            <button type="button" onclick="buscarDireccion()"
                                class="bg-primary/10 text-primary border border-primary/20 px-4 py-2 rounded-lg hover:bg-primary hover:text-white transition-all text-sm font-bold flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Buscar en mapa
                            </button>
                        </div>
                    </div>
                    --}}

                    {{-- 🗺️ Selector de Mapa (SUSPENDIDO) --}}
                    {{-- 
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2">Marca el punto en el mapa <span
                                class="text-xs text-muted-foreground font-normal">(Seleccionado automáticamente al buscar
                                dirección)</span></label>
                        <div id="map-picker"
                            class="w-full h-[300px] rounded-xl border border-border bg-slate-50 z-10 shadow-inner"></div>
                        <input type="hidden" name="latitud" id="lat-input" value="{{ old('latitud') }}">
                        <input type="hidden" name="longitud" id="lng-input" value="{{ old('longitud') }}">
                    </div>

                    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
                    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                    --}}

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-xs font-medium mb-1 uppercase text-muted-foreground">Habitaciones <span
                                    class="text-red-500">*</span></label>
                            <input type="number" name="habitaciones" value="{{ old('habitaciones') }}" required
                                class="w-full rounded-lg border-input bg-background/50 border py-2 px-3">
                        </div>
                        <div>
                            <label class="block text-xs font-medium mb-1 uppercase text-muted-foreground">Baños <span
                                    class="text-red-500">*</span></label>
                            <input type="number" name="banos" value="{{ old('banos') }}" required
                                class="w-full rounded-lg border-input bg-background/50 border py-2 px-3">
                        </div>
                    </div>

                    {{-- Calculadora de Área --}}
                    <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-100 mb-6">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-lg">📏</span>
                            <label class="text-sm font-bold text-slate-700">Dimensiones (Opcional)</label>
                        </div>
                        
                        <div class="grid grid-cols-3 gap-4 items-end">
                            <div>
                                <label class="block text-xs text-muted-foreground mb-1">Largo (m)</label>
                                <input type="number" x-model="largo" @input="calcularm2()" placeholder="0"
                                    class="w-full rounded-lg border-input bg-white border py-2 px-3 text-sm">
                            </div>
                            <div class="flex items-center justify-center pb-2 text-muted-foreground">
                                <span>×</span>
                            </div>
                            <div>
                                <label class="block text-xs text-muted-foreground mb-1">Ancho (m)</label>
                                <input type="number" x-model="ancho" @input="calcularm2()" placeholder="0"
                                    class="w-full rounded-lg border-input bg-white border py-2 px-3 text-sm">
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-t border-blue-100">
                            <label class="block text-xs font-bold mb-1 uppercase text-slate-600">Área Total (m²) <span
                                    class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="number" name="metros" x-model="metros" required
                                    class="w-full rounded-lg border-input bg-white border-2 border-blue-100 py-2 px-3 font-bold text-slate-800 focus:border-blue-400 focus:ring-0">
                                <span class="absolute right-3 top-2.5 text-xs text-muted-foreground font-bold">M²</span>
                            </div>
                            <p class="text-xs text-blue-600 mt-2" x-show="largo && ancho">
                                ✨ Cálculo automático: <span x-text="largo"></span>m x <span x-text="ancho"></span>m = <span x-text="metros"></span>m²
                            </p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Descripción <span
                                class="text-red-500">*</span></label>
                        <textarea name="descripcion" rows="4" placeholder="Cuéntanos más detalles..." required
                            class="w-full rounded-lg border-input bg-background/50 border py-3 px-4">{{ old('descripcion') }}</textarea>
                    </div>
                </div>

                {{-- PASO 3: FOTOS ILIMITADAS --}}
                <div x-show="step === 3" x-ref="step3" x-transition style="display: none;">
                    <h2 class="text-xl font-bold mb-4 flex items-center gap-2">📷 Galería de Fotos</h2>

                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-1">Subir Fotos <span
                                class="text-red-500">*</span></label>

                        <div
                            class="relative group cursor-pointer hover:bg-slate-50 transition-colors rounded-xl border-2 border-dashed border-gray-300 p-8 flex flex-col items-center justify-center text-center">

                            {{-- Input oculto pero funcional --}}
                            <input type="file" id="fileInput" name="imagenes[]" multiple accept="image/*"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                @change="handleFileSelect">

                            <div class="text-primary-400 mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto text-muted-foreground"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-foreground">Haz clic o arrastra más fotos</p>
                        </div>
                    </div>

                    {{-- Grid de Previsualización --}}
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-6" x-show="previews.length > 0">
                        <template x-for="(img, index) in previews" :key="index">
                            <div class="relative group aspect-square rounded-lg shadow-sm border border-gray-200"
                                style="position: relative;">
                                {{-- Imagen --}}
                                <img :src="img" class="object-cover w-full h-full rounded-lg">

                                {{-- Botón Eliminar --}}
                                <button type="button" @click="removeFile(index)"
                                    style="position: absolute; top: -12px; right: -12px; background-color: #EF4444; color: white; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; z-index: 9999; border: 3px solid white; cursor: pointer; box-shadow: 0 4px 6px rgba(0,0,0,0.15);"
                                    title="Eliminar foto">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        style="width: 16px; height: 16px; font-weight: bold;" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="pt-6 border-t border-border flex justify-between items-center mt-6">
                    <button type="button" @click="step--" x-show="step > 1"
                        class="text-muted-foreground hover:text-foreground font-medium px-4 py-2 transition-colors">←
                        Atrás</button>
                    <div x-show="step === 1"></div>
                    <button type="button" @click="nextStep()" x-show="step < 3"
                        class="bg-primary text-primary-foreground font-bold py-2 px-6 rounded-xl hover:bg-primary/90 transition-all shadow-md shadow-primary/20">Siguiente
                        Paso →</button>

                    <button type="submit" x-show="step === 3"
                        style="background-color: #003049; color: white; padding: 12px 32px; border-radius: 12px; font-weight: bold; font-size: 16px; border: none; cursor: pointer; box-shadow: 0 4px 15px rgba(0, 48, 73, 0.3); display: flex; align-items: center; gap: 8px;"
                        <span>✨</span> ¡Publicar Ahora!
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
                mapPicker = L.map('map-picker').setView([16.9068, -92.0941], 14);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(mapPicker);

                mapPicker.on('click', function(e) {
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
                    alert("No pudimos encontrar esa calle exacta. ¿Podrías marcar el punto manualmente en el mapa? 🐾");
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
            
            inputDireccion.value = "📍 Buscando dirección exacta...";
            // Efecto visual de carga
            inputDireccion.classList.add('bg-blue-50', 'animate-pulse');

            try {
                const response = await fetch(
                    `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`
                );
                const data = await response.json();

                if (data && data.display_name) {
                    // Limpiamos un poco la dirección para que no sea tan larga
                    let address = data.display_name;
                    inputDireccion.value = address;
                    // Disparamos evento input para que si hubiera validación se active
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


        document.addEventListener('alpine:init', () => {
            Alpine.data('wizardForm', () => ({
                step: 1,
                files: [],
                previews: [],
                largo: '',
                ancho: '',
                metros: '{{ old('metros') }}',

                calcularm2() {
                    if (this.largo && this.ancho) {
                        this.metros = (parseFloat(this.largo) * parseFloat(this.ancho)).toFixed(2);
                    }
                },

                handleFileSelect(event) {
                    const newFiles = Array.from(event.target.files);
                    this.files = this.files.concat(newFiles);
                    newFiles.forEach(file => {
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

                nextStep() {
                    let currentDiv = this.$refs['step' + this.step];
                    let inputs = currentDiv.querySelectorAll(
                        'input:required, select:required, textarea:required');

                    let esValido = true;
                    for (let input of inputs) {
                        if (!input.checkValidity()) {
                            input.reportValidity();
                            esValido = false;
                            break;
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
    </script>
@endsection
