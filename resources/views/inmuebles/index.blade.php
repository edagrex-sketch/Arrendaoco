@extends('layouts.app')

@section('title', (Auth::user()->es_admin || Auth::user()->tieneRol('admin')) ? 'Gestión de Propiedades' : 'Mis Propiedades')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex justify-between items-end mb-10">
            <div>
                <h1 class="text-4xl font-extrabold text-[#003049] tracking-tight">
                    {{ (Auth::user()->es_admin || Auth::user()->tieneRol('admin')) ? 'Gestión de Propiedades' : 'Mis Propiedades' }}
                </h1>
                <p class="text-muted-foreground mt-2 text-lg">
                    {{ (Auth::user()->es_admin || Auth::user()->tieneRol('admin')) ? 'Administra todos los inmuebles del sistema.' : 'Gestiona tus anuncios y publicaciones desde aquí.' }}
                </p>
            </div>
            <a href="{{ route('inmuebles.create') }}"
                class="inline-flex items-center gap-2 bg-[#C1121F] text-white px-8 py-4 rounded-2xl font-bold shadow-xl shadow-red-500/20 hover:bg-[#780000] hover:-translate-y-1 transition-all duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                </svg>
                Publicar Nuevo
            </a>
        </div>

        @if ($inmuebles->isEmpty())
            <div class="bg-white rounded-3xl p-20 text-center border-2 border-dashed border-slate-200">
                <div
                    class="bg-[#FDF0D5] w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6 text-[#003049]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-[#003049] mb-2">Aún no tienes propiedades</h3>
                <p class="text-muted-foreground text-lg mb-8">Comienza a publicar hoy mismo y llega a miles de personas.</p>
                <a href="{{ route('inmuebles.create') }}"
                    class="text-[#C1121F] font-bold hover:underline flex items-center justify-center gap-2">
                    Crear mi primera publicación
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($inmuebles as $inmueble)
                    <div
                        class="group bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500 border border-slate-100 flex flex-col h-full">
                        {{-- Cabecera con Imagen --}}
                        <div class="relative h-64 overflow-hidden">
                            <img src="{{ $inmueble->imagen }}" alt="{{ $inmueble->titulo }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            {{-- Badge de Estatus --}}
                            <div class="absolute top-4 right-4">
                                <span
                                    class="bg-white/95 backdrop-blur px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest shadow-lg text-[#003049] border border-white/50">
                                    {{ $inmueble->estatus }}
                                </span>
                            </div>
                        </div>

                        {{-- Información --}}
                        <div class="p-8 flex-grow">
                            <div class="flex items-center gap-2 mb-3">
                                <span
                                    class="bg-[#FDF0D5] text-[#003049] text-[10px] font-black uppercase px-2 py-0.5 rounded-sm">
                                    {{ $inmueble->tipo }}
                                </span>
                            </div>
                            <h3 class="text-2xl font-black text-[#003049] mb-2 line-clamp-1">
                                {{ $inmueble->titulo }}
                            </h3>
                            <p class="text-muted-foreground text-sm flex items-center gap-1 mb-6">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#C1121F]" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                </svg>
                                {{ $inmueble->direccion }}
                            </p>

                            <div class="flex items-center justify-between py-5 border-t border-slate-50">
                                <div class="text-2xl font-black text-[#003049]">
                                    ${{ number_format($inmueble->renta_mensual) }}
                                    <span class="text-xs font-medium text-muted-foreground lowercase">/mes</span>
                                </div>
                            </div>
                        </div>

                        {{-- Footer con Botones --}}
                        <div class="p-6 bg-slate-50 flex gap-3">
                            <a href="{{ route('inmuebles.show', $inmueble) }}"
                                class="flex-1 bg-white text-[#003049] font-bold py-3 rounded-xl border border-slate-200 hover:bg-[#003049] hover:text-white transition-all text-center">
                                Ver
                            </a>
                            <a href="{{ route('inmuebles.edit', $inmueble) }}"
                                class="flex-1 bg-white text-[#669BBC] font-bold py-3 rounded-xl border border-slate-200 hover:bg-[#669BBC] hover:text-white transition-all text-center">
                                Editar
                            </a>
                            <button onclick="confirmDelete({{ $inmueble->id }})"
                                class="w-12 h-12 flex items-center justify-center rounded-xl bg-white text-[#C1121F] border border-slate-200 hover:bg-[#C1121F] hover:text-white transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                            <form id="delete-form-{{ $inmueble->id }}"
                                action="{{ route('inmuebles.destroy', $inmueble) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

@endsection
