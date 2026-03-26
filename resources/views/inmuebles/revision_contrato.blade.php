@extends('layouts.app')

@section('title', 'Revisar Solicitud de Renta — ArrendaOco')


@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Encabezado --}}
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('inmuebles.index') }}" class="text-[#669BBC] hover:text-[#003049] transition-colors text-sm font-medium flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                Mis Propiedades
            </a>
        </div>
        <h1 class="text-3xl font-extrabold text-[#003049]">Revisar Solicitud de Renta</h1>
        <p class="text-gray-500 mt-1">Propiedad: <span class="font-semibold text-[#003049]">{{ $contrato->inmueble->titulo }}</span></p>
    </div>

    {{-- Alerta de urgencia --}}
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 flex items-start gap-3 mb-8">
        <svg class="h-6 w-6 text-amber-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
        <div>
            <p class="font-bold text-amber-800 text-sm">Fondos pre-autorizados — Responde en 24 horas</p>
            <p class="text-amber-700 text-xs mt-1">Los fondos del inquilino están congelados en su cuenta. Si no respondes en 24 hrs, la solicitud expira automáticamente y los fondos se liberan.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">

        {{-- Columna Izquierda: Previsualización del contrato --}}
        <div class="lg:col-span-3">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-[#003049] text-white px-6 py-4 flex items-center gap-3">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    <span class="font-bold">Previsualización del Contrato</span>
                </div>
                <div class="p-6 h-[560px] overflow-y-auto bg-slate-50">
                    <div class="bg-white shadow-sm border border-gray-200 rounded p-8 text-sm text-gray-700 space-y-4 font-serif leading-relaxed">
                        <h3 class="text-center font-bold text-lg mb-6 uppercase text-gray-800">Contrato de Arrendamiento Temporal</h3>

                        <p class="text-justify">El presente contrato es celebrado en <strong>{{ $contrato->inmueble->ciudad ?? 'Ocosingo, Chiapas' }}</strong> en fecha <strong>{{ \Carbon\Carbon::parse($contrato->created_at)->translatedFormat('d \d\e F \d\e\l Y') }}</strong>.</p>

                        <h4 class="font-bold mt-4">ENTRE</h4>
                        <p class="text-justify"><strong>{{ optional($contrato->inmueble->propietario)->nombre ?? 'El Arrendador' }}</strong> — De aquí en adelante el "Arrendador".</p>
                        <p class="text-center text-gray-400">— Y —</p>
                        <p class="text-justify"><strong>{{ optional($contrato->inquilino)->nombre ?? 'El Inquilino' }}</strong> — De aquí en adelante el "Inquilino".</p>

                        <h4 class="font-bold mt-6 border-b pb-1">CLÁUSULAS</h4>

                        <p class="font-bold mt-3">1. Objeto del Contrato</p>
                        <p class="text-justify">El Arrendador acepta alquilar al Inquilino la propiedad <strong>{{ $contrato->inmueble->titulo }}</strong> ubicada en <strong>{{ $contrato->inmueble->direccion ?? 'la dirección acordada' }}</strong> para uso habitacional exclusivo.</p>

                        <p class="font-bold mt-3">2. Duración y Renta</p>
                        <p class="text-justify">Este Contrato tendrá un plazo de <strong>{{ $contrato->plazo }}</strong> a contar desde el <strong>{{ \Carbon\Carbon::parse($contrato->fecha_inicio)->translatedFormat('d \d\e F \d\e\l Y') }}</strong>.<br>
                        Renta mensual: <strong>${{ number_format($contrato->renta_mensual, 2) }} MXN</strong>.<br>
                        Depósito de garantía: <strong>${{ number_format($contrato->deposito ?? 0, 2) }} MXN</strong>.</p>

                        <p class="font-bold mt-3">3. Condiciones Generales</p>
                        <p class="text-justify">El Inquilino se compromete a respetar las normas de convivencia y políticas de la propiedad definidas por el Arrendador en la plataforma ArrendaOco.</p>

                        <div class="bg-gray-50 border border-gray-200 p-3 rounded text-center italic text-gray-400 text-xs mt-6">
                            [Las cláusulas restantes forman parte íntegra del contrato y el Inquilino las acepta todas al firmar.]
                        </div>

                        <p class="mt-8 mb-6 text-center text-gray-700 font-medium">Firman de conformidad todas las Partes.</p>

                        <div class="grid grid-cols-2 gap-6 mt-4 text-center text-xs">
                            {{-- Firma Propietario --}}
                            <div class="flex flex-col items-center">
                                <div class="h-16 w-full mb-2 flex items-center justify-center bg-amber-50 rounded border border-amber-200 p-2" id="preview-firma-propietario">
                                    <p class="text-[9px] text-amber-800 leading-tight">← Agrega tu firma digital abajo</p>
                                </div>
                                <div class="w-full border-t border-gray-400 pt-2">
                                    <p class="font-bold text-xs uppercase">Firma del Arrendador</p>
                                    <p class="text-gray-500 mt-1">{{ optional($contrato->inmueble->propietario)->nombre }}</p>
                                </div>
                            </div>
                            {{-- Firma Inquilino --}}
                            <div class="flex flex-col items-center">
                                <div class="h-16 w-full mb-2 relative">
                                    @if($contrato->firma_digital)
                                        <img src="{{ $contrato->firma_digital }}" class="w-full h-full object-contain" alt="Firma Inquilino">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-300 text-xs italic">Sin firma</div>
                                    @endif
                                </div>
                                <div class="w-full border-t border-gray-800 pt-2">
                                    <p class="font-bold text-xs uppercase">Firma del Inquilino</p>
                                    <p class="text-gray-500 mt-1">{{ optional($contrato->inquilino)->nombre }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Columna Derecha: Panel de Acción --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Datos del Inquilino --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-[#003049] mb-4 flex items-center gap-2">
                    <svg class="h-5 w-5 text-[#669BBC]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    Datos del Inquilino
                </h3>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-full bg-[#003049]/10 flex items-center justify-center font-bold text-[#003049] text-lg uppercase overflow-hidden">
                        @if(optional($contrato->inquilino)->foto_perfil)
                            <img src="{{ str_starts_with($contrato->inquilino->foto_perfil, 'http') ? $contrato->inquilino->foto_perfil : asset('storage/'.$contrato->inquilino->foto_perfil) }}" alt="Foto" class="w-full h-full object-cover">
                        @else
                            {{ substr(optional($contrato->inquilino)->nombre ?? 'I', 0, 1) }}
                        @endif
                    </div>
                    <div>
                        <p class="font-bold text-[#003049]">{{ optional($contrato->inquilino)->nombre ?? 'Inquilino' }}</p>
                        <p class="text-xs text-gray-400">{{ optional($contrato->inquilino)->email }}</p>
                    </div>
                </div>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between text-gray-600">
                        <span class="text-xs font-bold text-[#669BBC] uppercase">Inicio</span>
                        <span class="font-semibold">{{ \Carbon\Carbon::parse($contrato->fecha_inicio)->translatedFormat('d M, Y') }}</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span class="text-xs font-bold text-[#669BBC] uppercase">Plazo</span>
                        <span class="font-semibold">{{ $contrato->plazo }}</span>
                    </div>
                    <div class="flex justify-between text-gray-600 border-t pt-2 mt-2">
                        <span class="text-xs font-bold text-[#669BBC] uppercase">Renta/mes</span>
                        <span class="font-black text-[#003049]">${{ number_format($contrato->renta_mensual, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span class="text-xs font-bold text-[#669BBC] uppercase">Depósito</span>
                        <span class="font-black text-[#003049]">${{ number_format($contrato->deposito ?? 0, 2) }}</span>
                    </div>
                    <div class="flex justify-between border-t pt-2">
                        <span class="text-xs font-bold text-[#669BBC] uppercase">Total congelado</span>
                        <span class="font-black text-lg text-[#003049]">${{ number_format($contrato->renta_mensual + ($contrato->deposito ?? 0), 2) }}</span>
                    </div>
                </div>
            </div>

            {{-- Firma Digital del Propietario --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-[#003049] mb-3 flex items-center gap-2">
                    <svg class="h-5 w-5 text-[#669BBC]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    Tu Firma Digital
                </h3>
                <p class="text-xs text-gray-500 mb-3">Dibuja tu firma para aceptar el contrato. Solo es requerida si deseas <strong>aprobar</strong>.</p>
                <div class="border-2 border-dashed border-gray-300 rounded-xl bg-slate-50 relative" style="height: 130px;">
                    <canvas id="signature-pad-propietario" class="absolute top-0 left-0 w-full h-full rounded-xl cursor-crosshair"></canvas>
                </div>
                <div class="flex justify-end gap-2 mt-2">
                    <button type="button" onclick="undoFirma()" class="text-xs text-gray-500 hover:text-[#003049] font-medium bg-gray-100 hover:bg-gray-200 px-3 py-1.5 rounded-lg transition-colors">Deshacer</button>
                    <button type="button" onclick="clearFirma()" class="text-xs text-red-500 hover:text-red-700 font-medium bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition-colors">Limpiar</button>
                </div>
            </div>

            {{-- Botones de Acción --}}
            <div class="space-y-3">
                <button id="btn-aprobar" onclick="accionContrato('aprobar')"
                    class="w-full bg-[#003049] text-white font-bold py-4 px-6 rounded-xl shadow-xl shadow-[#003049]/20 hover:bg-[#002236] hover:-translate-y-0.5 transition-all flex justify-center items-center gap-2 text-lg">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Aprobar Renta
                </button>
                <button id="btn-rechazar" onclick="accionContrato('rechazar')"
                    class="w-full bg-white border-2 border-red-200 text-red-500 hover:bg-red-50 hover:border-red-400 font-bold py-3 px-6 rounded-xl transition-all flex justify-center items-center gap-2">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Rechazar Solicitud
                </button>
            </div>

            {{-- Forms Hidden --}}
            <form id="form-aprobar" action="{{ route('contratos.aprobar', $contrato->id) }}" method="POST" class="hidden">
                @csrf
                <input type="hidden" name="firma_propietario" id="input-firma-propietario">
            </form>
            <form id="form-rechazar" action="{{ route('contratos.rechazar', $contrato->id) }}" method="POST" class="hidden">
                @csrf
            </form>

        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>

<script>
    let signaturePad = null;
    let canvas = null;

    function initSignature() {
        canvas = document.getElementById('signature-pad-propietario');
        if (!canvas) return;

        // Limpiar para evitar duplicados si se llama de nuevo
        if (signaturePad) {
            signaturePad.off();
            signaturePad = null;
        }

        // Dimensiones físicas exactas
        const rect = canvas.getBoundingClientRect();
        canvas.width = rect.width;
        canvas.height = rect.height;

        // Importante para dispositivos móviles
        canvas.style.touchAction = 'none';

        signaturePad = new SignaturePad(canvas, {
            penColor: '#003049',
            minWidth: 1,
            maxWidth: 3,
            throttle: 16
        });

        // Eventos de actualización de previsualización
        canvas.addEventListener('mouseup',  actualizarPreview);
        canvas.addEventListener('touchend', actualizarPreview);
        canvas.addEventListener('mousemove', () => { if(signaturePad) actualizarPreview(); });
        canvas.addEventListener('touchmove', () => { if(signaturePad) actualizarPreview(); });

        console.log('SignaturePad inicializado en canvas:', canvas.width, 'x', canvas.height);
    }

    document.addEventListener('DOMContentLoaded', () => {
        // Ejecución inmediata + un pequeño delay de respaldo para layout fluido
        initSignature();
        setTimeout(initSignature, 500);

        window.addEventListener('resize', initSignature);
    });

    function actualizarPreview() {
        if (!signaturePad || signaturePad.isEmpty()) return;
        const preview = document.getElementById('preview-firma-propietario');
        if (preview) {
            preview.innerHTML = `<img src="${signaturePad.toDataURL()}" class="w-full h-full object-contain">`;
        }
    }

    function clearFirma() {
        if (signaturePad) {
            signaturePad.clear();
            const preview = document.getElementById('preview-firma-propietario');
            if (preview) {
                preview.innerHTML = '<p class="text-[9px] text-amber-800 leading-tight">← Agrega tu firma digital abajo</p>';
            }
        }
    }

    function undoFirma() {
        if (signaturePad) {
            const data = signaturePad.toData();
            if (data && data.length > 0) { 
                data.pop(); 
                signaturePad.fromData(data); 
                actualizarPreview(); 
            }
        }
    }

    function accionContrato(accion) {
        if (accion === 'aprobar') {
            if (!signaturePad || signaturePad.isEmpty()) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Firma Requerida',
                    text: 'Debes dibujar tu firma digital en el cuadro para poder aprobar el contrato.',
                    confirmButtonColor: '#003049'
                });
                return;
            }

            Swal.fire({
                icon: 'question',
                title: '¿Aprobar esta renta?',
                html: 'Al aprobar, los fondos congelados serán <strong>capturados definitivamente</strong> y el contrato quedará activo.',
                showCancelButton: true,
                confirmButtonText: 'Sí, Aprobar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#003049',
                cancelButtonColor: '#6b7280'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('input-firma-propietario').value = signaturePad.toDataURL();
                    document.getElementById('form-aprobar').submit();
                }
            });
        } else {
            Swal.fire({
                icon: 'warning',
                title: '¿Rechazar esta solicitud?',
                html: 'Los fondos congelados serán <strong>liberados de inmediato</strong> sin cargos. El inquilino será notificado.',
                showCancelButton: true,
                confirmButtonText: 'Sí, Rechazar',
                cancelButtonText: 'Volver',
                confirmButtonColor: '#C1121F',
                cancelButtonColor: '#6b7280'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-rechazar').submit();
                }
            });
        }
    }
</script>
@endpush
@endsection
