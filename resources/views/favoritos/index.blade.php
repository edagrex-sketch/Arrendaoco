@extends('layouts.app')

@section('title', 'Mis Favoritos')

@section('content')
    <div class="container mx-auto px-4 py-12">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h1 class="text-4xl font-extrabold text-foreground tracking-tight">Mis Favoritos</h1>
                <p class="text-muted-foreground mt-2">Propiedades que te han robado el corazón.</p>
            </div>
            <div class="bg-primary/10 px-4 py-2 rounded-full border border-primary/20">
                <span class="text-primary font-bold">{{ $favoritos->total() }}</span>
                <span class="text-primary/70 text-sm ml-1">guardados</span>
            </div>
        </div>

        @if ($favoritos->isEmpty())
            <div class="text-center py-20 bg-card rounded-3xl border border-dashed border-border shadow-sm">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-primary/10 mb-6 group">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-10 w-10 text-primary transition-transform group-hover:scale-110" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-foreground mb-3">Aún no tienes favoritos</h2>
                <p class="text-muted-foreground max-w-sm mx-auto mb-8">
                    Explora nuestras propiedades y haz clic en el corazón para guardarlas aquí.
                </p>
                <a href="{{ route('inicio') }}"
                    class="inline-flex h-11 items-center justify-center rounded-xl bg-primary px-8 text-sm font-medium text-primary-foreground shadow-lg hover:shadow-primary/20 transition-all">
                    Explorar Propiedades
                </a>
            </div>
        @else
            <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($favoritos as $inmueble)
                    <div
                        class="group relative overflow-hidden rounded-3xl border border-border bg-card text-card-foreground shadow-sm transition-all hover:shadow-2xl hover:-translate-y-2 flex flex-col h-full">

                        {{-- Imagen con Overlay --}}
                        <div class="relative h-64 overflow-hidden">
                            @if ($inmueble->imagen)
                                <img src="{{ $inmueble->imagen }}" alt="{{ $inmueble->titulo }}"
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                            @else
                                <div class="w-full h-full bg-secondary/30 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-muted-foreground/50" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif

                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                            </div>

                            {{-- Botón Quitar Favorito (RF-27: Baja) --}}
                            <form action="{{ route('favoritos.toggle', $inmueble) }}" method="POST"
                                class="absolute top-4 right-4 z-10">
                                @csrf
                                <button type="submit"
                                    class="w-10 h-10 rounded-full bg-white/90 backdrop-blur-sm flex items-center justify-center text-red-500 shadow-lg hover:bg-white hover:scale-110 transition-all">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 fill-current"
                                        viewBox="0 0 24 24">
                                        <path
                                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                </button>
                            </form>

                            <div class="absolute bottom-4 left-4 text-white">
                                <span class="text-2xl font-bold">${{ number_format($inmueble->renta_mensual) }}</span>
                                <span class="text-xs opacity-80 uppercase tracking-widest ml-1">/ mes</span>
                            </div>
                        </div>

                        <div class="p-6 flex-1 flex flex-col">
                            <div class="flex-1">
                                <h3 class="text-xl font-bold mb-2 group-hover:text-primary transition-colors line-clamp-1">
                                    {{ $inmueble->titulo }}</h3>
                                <div class="flex items-center text-muted-foreground text-sm mb-4">
                                    <svg class="w-4 h-4 mr-1 text-primary" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    {{ $inmueble->direccion }}
                                </div>

                                {{-- Sección de Notas (RF-28: Edición) --}}
                                <div class="mt-4 p-4 rounded-2xl bg-secondary/50 border border-border/50">
                                    <form action="{{ route('favoritos.update', $inmueble) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <label
                                            class="text-[10px] uppercase font-bold text-muted-foreground tracking-widest mb-2 block">Mis
                                            Notas</label>
                                        <textarea name="nota" rows="2"
                                            class="w-full bg-transparent border-none p-0 text-sm focus:ring-0 placeholder:italic"
                                            placeholder="Escribe aquí algún recordatorio...">{{ $inmueble->pivot->getOriginal('pivot_nota') ?? $inmueble->pivot->nota }}</textarea>
                                        <button type="submit"
                                            class="text-[10px] text-primary font-bold mt-2 hover:underline">GUARDAR
                                            NOTA</button>
                                    </form>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mt-8">
                                <a href="{{ route('inmuebles.show', $inmueble) }}"
                                    class="flex items-center justify-center py-3 px-4 rounded-xl bg-primary text-primary-foreground font-bold shadow-lg shadow-primary/20 hover:scale-[1.02] transition-all">
                                    Ver Detalles
                                </a>
                                <button
                                    class="flex items-center justify-center py-3 px-4 rounded-xl border border-primary/20 bg-primary/5 text-primary font-bold hover:bg-primary/10 transition-all">
                                    Compartir
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-12">
                {{ $favoritos->links() }}
            </div>
        @endif
    </div>

    <x-arrendito />
@endsection
