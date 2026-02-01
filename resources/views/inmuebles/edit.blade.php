@extends('layouts.app')

@section('title', 'Editar Inmueble')

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-12">
        <div class="mb-10">
            <h1 class="text-4xl font-extrabold text-[#003049] tracking-tight">Editar Anuncio</h1>
            <p class="text-muted-foreground mt-2 text-lg">Modifica los detalles de tu publicación.</p>
        </div>

        <form action="{{ route('inmuebles.update', $inmueble) }}" method="POST" enctype="multipart/form-data"
            class="space-y-8">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100 space-y-6">
                {{-- Título --}}
                <div>
                    <label class="block text-sm font-bold text-[#003049] uppercase tracking-wider mb-2">Título del
                        Anuncio</label>
                    <input type="text" name="nombre" value="{{ $inmueble->titulo }}" required
                        class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#003049] focus:border-transparent outline-none transition-all">
                </div>

                <div class="grid grid-cols-2 gap-6">
                    {{-- Tipo --}}
                    <div>
                        <label class="block text-sm font-bold text-[#003049] uppercase tracking-wider mb-2">Tipo de
                            Inmueble</label>
                        <select name="tipo"
                            class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#003049] outline-none">
                            <option value="Casa" {{ $inmueble->tipo == 'Casa' ? 'selected' : '' }}>Casa</option>
                            <option value="Departamento" {{ $inmueble->tipo == 'Departamento' ? 'selected' : '' }}>
                                Departamento</option>
                            <option value="Local" {{ $inmueble->tipo == 'Local' ? 'selected' : '' }}>Local Comercial
                            </option>
                            <option value="Oficina" {{ $inmueble->tipo == 'Oficina' ? 'selected' : '' }}>Oficina</option>
                        </select>
                    </div>

                    {{-- Precio --}}
                    <div>
                        <label class="block text-sm font-bold text-[#003049] uppercase tracking-wider mb-2">Renta Mensual
                            ($)</label>
                        <input type="number" name="precio" value="{{ $inmueble->renta_mensual }}" required
                            class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#003049] outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-6">
                    <div>
                        <label
                            class="block text-sm font-bold text-[#003049] uppercase tracking-wider mb-2">Habitaciones</label>
                        <input type="number" name="habitaciones" value="{{ $inmueble->habitaciones }}"
                            class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#003049] outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-[#003049] uppercase tracking-wider mb-2">Baños</label>
                        <input type="number" name="banos" value="{{ $inmueble->banos }}"
                            class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#003049] outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-[#003049] uppercase tracking-wider mb-2">Metros
                            (m²)</label>
                        <input type="number" name="metros" value="{{ $inmueble->metros }}"
                            class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#003049] outline-none">
                    </div>
                </div>

                {{-- Dirección --}}
                <div>
                    <label class="block text-sm font-bold text-[#003049] uppercase tracking-wider mb-2">Dirección</label>
                    <input type="text" name="direccion" value="{{ $inmueble->direccion }}" required
                        class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#003049] outline-none">
                </div>

                {{-- Descripción --}}
                <div>
                    <label class="block text-sm font-bold text-[#003049] uppercase tracking-wider mb-2">Descripción</label>
                    <textarea name="descripcion" rows="5" required
                        class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#003049] outline-none transition-all">{{ $inmueble->descripcion }}</textarea>
                </div>
            </div>

            {{-- Sección de Fotos --}}
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100">
                <label class="block text-sm font-bold text-[#003049] uppercase tracking-wider mb-6">Fotos Actuales</label>
                <div class="flex flex-wrap gap-4 mb-8">
                    @foreach ($imagenes as $img)
                        <div class="relative group w-32 h-32 rounded-xl overflow-hidden border border-slate-200 shadow-sm">
                            <img src="{{ $img->ruta_imagen }}" class="w-full h-full object-cover">
                            <div
                                class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <span class="text-white text-[10px] font-bold uppercase">Actual</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <label class="block text-sm font-bold text-[#003049] uppercase tracking-wider mb-2">Subir más fotos</label>
                <input type="file" name="imagenes[]" multiple
                    class="w-full px-5 py-3 rounded-xl border border-dashed border-slate-300 bg-slate-50">
            </div>

            <div class="flex gap-4">
                <button type="submit"
                    class="flex-1 bg-[#003049] text-white font-black py-4 rounded-2xl shadow-xl hover:bg-[#003049]/90 transition-all uppercase tracking-widest">
                    Guardar Cambios
                </button>
                <a href="{{ route('inmuebles.index') }}"
                    class="flex-1 bg-white text-muted-foreground font-bold py-4 rounded-2xl border border-slate-200 hover:bg-slate-50 transition-all text-center uppercase tracking-widest">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
@endsection
