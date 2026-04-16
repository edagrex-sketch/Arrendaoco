@extends('layouts.app')

@section('title', 'Contrato de Arrendamiento — ' . $inmueble->titulo)

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8 animate-fade-in-up"
     x-data="{ modalAbierto: false }">

    {{-- ══════════════════════════════════════════════════════
         HEADER — Breadcrumb + Título
    ══════════════════════════════════════════════════════ --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <nav class="flex items-center gap-2 text-xs text-slate-400 font-bold uppercase tracking-widest mb-2">
                <a href="{{ route('inicio') }}" class="hover:text-brand-dark transition-colors">Inicio</a>
                <span>/</span>
                <a href="{{ route('inmuebles.show', $inmueble) }}" class="hover:text-brand-dark transition-colors truncate max-w-[160px]">{{ $inmueble->titulo }}</a>
                <span>/</span>
                <span class="text-brand-dark">Contrato</span>
            </nav>
            <h1 class="text-2xl font-black text-brand-dark tracking-tight">
                Contrato de Arrendamiento
            </h1>
            <p class="text-sm text-slate-500 mt-1">
                @if($esPrevia)
                    Revisa los términos con atención. Para iniciar el proceso de renta, confirma y descarga el PDF.
                @else
                    Lee con atención antes de descargar. Ambas partes deberán firmar la copia impresa.
                @endif
            </p>
        </div>

        {{-- Botón Contactar Propietario --}}
        <a href="{{ route('chats.start', ['otroUsuarioId' => $inmueble->propietario_id, 'inmuebleId' => $inmueble->id]) }}"
           class="btn-outline flex items-center gap-2 shrink-0 text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
            Contactar Propietario
        </a>
    </div>

    {{-- ══════════════════════════════════════════════════════
         AVISOS DE ESTADO
    ══════════════════════════════════════════════════════ --}}

    {{-- Aviso: es una previsualización (sin contrato en BD aún) --}}
    @if($esPrevia)
    <div class="mb-6 flex items-start gap-3 bg-amber-50 border border-amber-200 rounded-2xl px-5 py-4 shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <p class="text-sm text-amber-800 font-semibold">
            <strong>Vista previa del contrato.</strong> Aún no has solicitado esta renta.
            Al confirmar y descargar el PDF, iniciarás formalmente el proceso de arrendamiento.
        </p>
    </div>
    @endif

    {{-- Aviso: contrato ya en proceso (PDF ya descargado) --}}
    @if(!$esPrevia && $contrato->estatus === 'pdf_descargado')
    <div class="mb-6 flex items-start gap-3 bg-brand-cream border border-brand-light/30 rounded-2xl px-5 py-4 shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-brand-dark shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-sm text-brand-dark font-semibold">
            El PDF fue descargado el <strong>{{ $contrato->pdf_descargado_at->format('d/m/Y \a \l\a\s H:i') }}</strong>.
            El propietario debe subir el escaneo del contrato firmado para activar el arrendamiento.
        </p>
    </div>
    @endif

    {{-- ══════════════════════════════════════════════════════
         LAYOUT: Preview (izq) + Panel de Acción (der)
    ══════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ── IZQUIERDA: Preview del contrato ─────────────── --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                {{-- Header de la card --}}
                <div class="bg-brand-dark px-6 py-4 flex items-center gap-3">
                    <div class="h-9 w-9 rounded-xl bg-white/10 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-brand-cream" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-white font-bold text-sm">Precontrato de Arrendamiento</p>
                        <p class="text-brand-light text-[10px] font-bold uppercase tracking-widest">Vista previa fiel al PDF final</p>
                    </div>
                </div>

                {{-- Scroll area del contrato --}}
                <div class="h-[520px] overflow-y-auto p-6 bg-slate-50/50">
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-inner p-6 text-sm text-slate-700 font-serif leading-relaxed space-y-3">
                        <x-contrato-legal :inmueble="$inmueble" :contrato="$contrato" />

                        {{-- Sección de firmas físicas (preview) --}}
                        <div class="grid grid-cols-2 gap-8 mt-10 pt-6 border-t border-slate-200 text-center text-xs">
                            <div class="flex flex-col items-center">
                                <div class="h-16 w-full border-b border-slate-400 mb-2"></div>
                                <p class="font-black text-[10px] uppercase tracking-widest text-brand-dark">Firma del Arrendador</p>
                                <p class="text-slate-500 mt-0.5">{{ optional($inmueble->propietario)->nombre }}</p>
                                <p class="text-[9px] text-slate-400">Nombre, firma y fecha</p>
                            </div>
                            <div class="flex flex-col items-center">
                                <div class="h-16 w-full border-b border-slate-400 mb-2"></div>
                                <p class="font-black text-[10px] uppercase tracking-widest text-brand-dark">Firma del Inquilino</p>
                                <p class="text-slate-500 mt-0.5">{{ auth()->user()->nombre }}</p>
                                <p class="text-[9px] text-slate-400">Nombre, firma y fecha</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── DERECHA: Panel de Acción ─────────────────────── --}}
        <div class="flex flex-col gap-4">

            {{-- Resumen del contrato --}}
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-5">
                <h3 class="text-xs font-black text-brand-light uppercase tracking-widest mb-4">
                    Resumen del Contrato
                </h3>
                <ul class="space-y-3 text-sm">
                    <li class="flex justify-between items-center py-2 border-b border-slate-50">
                        <span class="text-slate-500 font-medium">Propiedad</span>
                        <span class="font-bold text-brand-dark text-right max-w-[140px] leading-tight">{{ $inmueble->titulo }}</span>
                    </li>
                    <li class="flex justify-between items-center py-2 border-b border-slate-50">
                        <span class="text-slate-500 font-medium">Renta mensual</span>
                        <span class="font-black text-brand-dark">${{ number_format($inmueble->renta_mensual, 2) }}</span>
                    </li>
                    <li class="flex justify-between items-center py-2 border-b border-slate-50">
                        <span class="text-slate-500 font-medium">Depósito</span>
                        <span class="font-bold text-brand-dark">${{ number_format($inmueble->deposito ?? 0, 2) }}</span>
                    </li>
                    <li class="flex justify-between items-center py-2 border-b border-slate-50">
                        <span class="text-slate-500 font-medium">Duración</span>
                        <span class="font-bold text-brand-dark">{{ $contrato->plazo }}</span>
                    </li>
                    <li class="flex justify-between items-center py-2 border-b border-slate-50">
                        <span class="text-slate-500 font-medium">Inicio</span>
                        <span class="font-bold text-brand-dark">{{ \Carbon\Carbon::parse($contrato->fecha_inicio)->format('d/m/Y') }}</span>
                    </li>
                    <li class="flex justify-between items-center py-2">
                        <span class="text-slate-500 font-medium">Fin estimado</span>
                        <span class="font-bold text-brand-dark">{{ $contrato->fecha_fin ? \Carbon\Carbon::parse($contrato->fecha_fin)->format('d/m/Y') : '—' }}</span>
                    </li>
                </ul>
            </div>

            {{-- CTA — Descargar / Confirmar PDF --}}
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-5">
                <h3 class="text-xs font-black text-brand-light uppercase tracking-widest mb-3">
                    Siguiente Paso
                </h3>
                <p class="text-xs text-slate-500 mb-4 leading-relaxed">
                    @if($esPrevia)
                        Al confirmar, enviaremos al propietario tu solicitud de renta.
                        Una vez confirmado, podrás <strong class="text-brand-dark">descargar el PDF</strong> e imprimirlo para llevar a firmarlo.
                    @else
                        Descarga el PDF, <strong class="text-brand-dark">imprímelo en dos copias</strong> y llévalas junto con tu identificación al inmueble para firmar con el propietario.
                    @endif
                </p>

                {{-- Botón que abre el modal de instrucciones --}}
                <button @click="modalAbierto = true"
                        id="btn-abrir-modal-descarga"
                        class="btn-primary w-full justify-center text-sm">
                    @if($esPrevia) 
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Confirmar Renta 
                    @else 
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Descargar Contrato PDF 
                    @endif
                </button>

                <p class="text-[10px] text-center text-slate-400 mt-3">
                    ¿Necesitas cambiar algún término?
                    <a href="{{ route('chats.start', ['otroUsuarioId' => $inmueble->propietario_id, 'inmuebleId' => $inmueble->id]) }}"
                       class="text-brand-light hover:text-brand-dark font-bold transition-colors">Contacta al propietario</a>.
                </p>
            </div>

            {{-- Info legal --}}
            <div class="rounded-2xl border border-brand-light/20 bg-brand-cream/60 px-5 py-4 flex items-start gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-brand-dark shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                <p class="text-xs text-brand-dark/80 leading-relaxed font-medium">
                    ArrendaOco actúa como intermediario tecnológico. La validez legal del contrato reside en las firmas manuscritas sobre el documento impreso.
                </p>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════
         MODAL — Instrucciones de Formalización Física
    ══════════════════════════════════════════════════════ --}}
    <div x-show="modalAbierto"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center bg-brand-dark/60 backdrop-blur-sm px-4"
         style="display: none;" x-cloak>

        <div x-show="modalAbierto"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             @click.away="modalAbierto = false"
             class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden">

            {{-- Header del modal --}}
            <div class="bg-brand-dark px-6 pt-6 pb-5 flex items-start gap-4">
                <div class="h-12 w-12 rounded-2xl bg-brand-cream/20 flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-brand-cream" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-white font-black text-lg leading-tight">
                        @if($esPrevia) Confirmar solicitud de renta @else Instrucciones de Formalización @endif
                    </h2>
                    <p class="text-brand-light text-xs mt-0.5">
                        @if($esPrevia) Revisa antes de confirmar tu solicitud @else Lee antes de descargar el contrato @endif
                    </p>
                </div>
            </div>

            {{-- Cuerpo del modal --}}
            <div class="px-6 py-5">
                @if($esPrevia)
                <p class="text-sm font-bold text-brand-dark mb-4">
                    Al confirmar, notificaremos al propietario para iniciar el proceso.
                    Podrás descargar el contrato PDF en esta misma pantalla una vez confirmado.
                </p>
                @else
                <p class="text-sm font-bold text-brand-dark mb-4">
                    Para que el contrato tenga validez legal, sigue estos pasos:
                </p>
                @endif

                <ol class="space-y-3">
                    @php $pasos = [
                        ['icon' => 'M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4',
                         'texto' => 'Imprime <strong>dos copias</strong> del contrato PDF que descargarás aquí.'],
                        ['icon' => 'M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0',
                         'texto' => 'Lleva una <strong>copia de tu identificación oficial</strong> vigente al reunirte con el propietario.'],
                        ['icon' => 'M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z',
                         'texto' => 'En el inmueble, <strong>firma la copia del propietario</strong> y él firmará la tuya.'],
                        ['icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                         'texto' => 'Asegúrate de <strong>llevarte tu copia firmada</strong> por ambas partes.'],
                    ]; @endphp

                    @foreach($pasos as $i => $paso)
                    <li class="flex items-start gap-3">
                        <div class="h-7 w-7 rounded-full bg-brand-dark flex items-center justify-center shrink-0 mt-0.5">
                            <span class="text-white font-black text-[11px]">{{ $i + 1 }}</span>
                        </div>
                        <p class="text-sm text-slate-600 leading-snug pt-1">{!! $paso['texto'] !!}</p>
                    </li>
                    @endforeach
                </ol>

                <div class="mt-5 rounded-xl bg-brand-cream/60 border border-brand-light/20 px-4 py-3 flex items-start gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-brand-dark shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <p class="text-xs text-brand-dark/80 font-semibold leading-relaxed">
                        Sin la firma manuscrita en papel, el contrato <strong>no tiene validez legal</strong> ante terceros.
                    </p>
                </div>
            </div>

            {{-- Footer del modal: comportamiento diferente según esPrevia --}}
            <div class="px-6 pb-6 flex flex-col sm:flex-row gap-3">
                @if($esPrevia)
                    {{-- Primera vez: POST a confirmar-renta → crea contrato en BD --}}
                    <form action="{{ route('contratos.confirmar', $inmueble) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit"
                                id="btn-confirmar-descarga"
                                class="btn-primary w-full justify-center text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Confirmar Renta
                        </button>
                    </form>
                @else
                    {{-- Contrato ya en BD: GET a descargar-registrar --}}
                    <a href="{{ route('contratos.descargar-registrar', $contrato) }}"
                       id="btn-confirmar-descarga"
                       class="btn-primary flex-1 justify-center text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Descargar Contrato PDF
                    </a>
                @endif
                <button @click="modalAbierto = false"
                        class="btn-outline flex-1 justify-center text-sm">
                    Cancelar
                </button>
            </div>
        </div>
    </div>

</div>
@endsection
