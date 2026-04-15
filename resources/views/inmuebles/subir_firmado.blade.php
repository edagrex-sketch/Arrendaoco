@extends('layouts.app')

@section('title', 'Subir Contrato Firmado — ArrendaOco')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8 animate-fade-in-up">

    {{-- ══════════════════════════════════════════════════════
         BREADCRUMB
    ══════════════════════════════════════════════════════ --}}
    <nav class="flex items-center gap-2 text-xs text-slate-400 font-bold uppercase tracking-widest mb-6">
        <a href="{{ route('inmuebles.index') }}" class="hover:text-brand-dark transition-colors">Mis Propiedades</a>
        <span>/</span>
        <a href="{{ route('inmuebles.show', $contrato->inmueble) }}" class="hover:text-brand-dark transition-colors truncate max-w-[160px]">{{ $contrato->inmueble->titulo }}</a>
        <span>/</span>
        <span class="text-brand-dark">Subir Contrato Firmado</span>
    </nav>

    {{-- ══════════════════════════════════════════════════════
         HEADER — Ícono + Título
    ══════════════════════════════════════════════════════ --}}
    <div class="flex items-center gap-4 mb-8">
        <div class="h-14 w-14 rounded-2xl bg-brand-dark flex items-center justify-center shadow-xl shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-brand-cream" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
            </svg>
        </div>
        <div>
            <h1 class="text-2xl font-black text-brand-dark tracking-tight">
                Subir Contrato Firmado
            </h1>
            <p class="text-sm text-slate-500 mt-0.5">
                Activa el arrendamiento subiendo el escaneo o foto del contrato con firmas.
            </p>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════
         TARJETA RESUMEN DEL CONTRATO
    ══════════════════════════════════════════════════════ --}}
    <div class="bg-brand-cream/60 border border-brand-light/20 rounded-3xl p-5 mb-6">
        <h3 class="text-[10px] font-black text-brand-light uppercase tracking-widest mb-4">Detalles del Contrato</h3>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm">
            <div>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Inquilino</p>
                <p class="font-bold text-brand-dark mt-1">{{ optional($contrato->inquilino)->nombre }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Renta Mensual</p>
                <p class="font-black text-brand-dark mt-1">${{ number_format($contrato->renta_mensual, 2) }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Plazo</p>
                <p class="font-bold text-brand-dark mt-1">{{ $contrato->plazo }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">PDF Descargado</p>
                <p class="font-bold text-brand-dark mt-1">
                    {{ $contrato->pdf_descargado_at ? $contrato->pdf_descargado_at->format('d/m/Y H:i') : '—' }}
                </p>
            </div>
            <div class="col-span-2">
                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Propiedad</p>
                <p class="font-bold text-brand-dark mt-1">{{ $contrato->inmueble->titulo }}</p>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════
         AVISO — Qué se espera
    ══════════════════════════════════════════════════════ --}}
    <div class="flex items-start gap-3 bg-brand-cream border border-brand-light/30 rounded-2xl px-5 py-4 mb-6 shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-brand-dark shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div>
            <p class="text-sm font-bold text-brand-dark mb-1">¿Qué debo subir?</p>
            <p class="text-xs text-brand-dark/70 leading-relaxed">
                Sube una <strong>fotografía clara o escaneo</strong> del contrato impreso con ambas firmas visibles.
                Formatos aceptados: <strong>PDF, JPG, PNG o WebP</strong>. Máximo 10 MB.
                Este archivo quedará como respaldo digital oficial en el sistema.
            </p>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════
         FORMULARIO DE CARGA
    ══════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden"
         x-data="{ archivo: null, archivoNombre: '', arrastrando: false }">

        <div class="bg-brand-dark px-6 py-4 flex items-center gap-3">
            <div class="h-8 w-8 rounded-xl bg-white/10 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
            </div>
            <p class="text-white font-bold text-sm">Archivo del Contrato Firmado</p>
        </div>

        <form action="{{ route('contratos.subir-firmado.post', $contrato) }}"
              method="POST"
              enctype="multipart/form-data"
              class="p-6">
            @csrf

            {{-- Zona de Drop --}}
            <div
                class="relative border-2 border-dashed rounded-2xl p-8 text-center cursor-pointer transition-all duration-200"
                :class="arrastrando
                    ? 'border-brand-dark bg-brand-dark/5 scale-[1.01]'
                    : 'border-slate-200 hover:border-brand-light hover:bg-slate-50'"
                @dragover.prevent="arrastrando = true"
                @dragleave.prevent="arrastrando = false"
                @drop.prevent="
                    arrastrando = false;
                    const f = $event.dataTransfer.files[0];
                    if(f) { archivo = f; archivoNombre = f.name; $refs.fileInput.files = $event.dataTransfer.files; }
                "
                @click="$refs.fileInput.click()">

                <input type="file"
                       name="archivo_firmado"
                       id="archivo_firmado"
                       accept=".pdf,.jpg,.jpeg,.png,.webp"
                       x-ref="fileInput"
                       class="hidden"
                       @change="
                           archivo = $event.target.files[0];
                           archivoNombre = archivo ? archivo.name : '';
                       ">

                {{-- Estado sin archivo --}}
                <div x-show="!archivo" class="space-y-3">
                    <div class="h-14 w-14 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-brand-dark">
                            Arrastra el archivo aquí
                            <span class="text-brand-light">o haz clic para buscar</span>
                        </p>
                        <p class="text-xs text-slate-400 mt-1">PDF, JPG, PNG, WebP — Máx. 10 MB</p>
                    </div>
                </div>

                {{-- Estado con archivo seleccionado --}}
                <div x-show="archivo" x-cloak class="flex flex-col items-center gap-2">
                    <div class="h-14 w-14 rounded-2xl bg-brand-dark/10 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-brand-dark" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="text-sm font-bold text-brand-dark" x-text="archivoNombre"></p>
                    <p class="text-xs text-slate-400">
                        <span x-text="archivo ? (archivo.size / 1024 / 1024).toFixed(2) + ' MB' : ''"></span>
                    </p>
                    <span class="badge-primary mt-1">Listo para subir</span>
                </div>
            </div>

            {{-- Botones --}}
            <div class="flex flex-col sm:flex-row gap-3 mt-6">
                <button type="submit"
                        id="btn-subir-contrato"
                        :disabled="!archivo"
                        class="btn-primary flex-1 justify-center text-sm"
                        :class="!archivo && 'opacity-50 cursor-not-allowed hover:translate-y-0 active:scale-100'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Activar Arrendamiento
                </button>
                <a href="{{ route('inmuebles.index') }}"
                   class="btn-outline flex-1 justify-center text-sm">
                    Cancelar
                </a>
            </div>

            <p class="text-[11px] text-center text-slate-400 mt-4 leading-relaxed">
                Al subir este archivo, el contrato se marcará como <strong class="text-brand-dark">activo</strong>
                y el inmueble quedará registrado como <strong class="text-brand-dark">rentado</strong> en el sistema.
            </p>
        </form>
    </div>

</div>
@endsection
