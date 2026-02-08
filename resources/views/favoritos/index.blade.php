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
                <span class="text-primary font-bold" id="fav-counter">{{ $favoritos->total() }}</span>
                <span class="text-primary/70 text-sm ml-1">guardados</span>
            </div>
        </div>

        {{-- Estado Vacío: Se muestra si no hay favoritos al cargar o si el contador llega a cero --}}
        <div id="empty-state" 
            class="text-center py-20 bg-card rounded-3xl border border-dashed border-border shadow-sm"
            style="{{ $favoritos->isEmpty() ? '' : 'display: none;' }}">
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

        @if (!$favoritos->isEmpty())
            <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3" id="favorites-grid">
                @foreach ($favoritos as $inmueble)
                    <div x-data="{ 
                            visible: true,
                            loading: false,
                            remove() {
                                if (this.loading) return;
                                this.loading = true;
                                fetch('{{ route('favoritos.toggle', $inmueble) }}', {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json',
                                        'X-Requested-With': 'XMLHttpRequest'
                                    }
                                })
                                .then(res => res.json())
                                .then(data => {
                                    if (data.success) {
                                        this.visible = false;
                                        // Actualizar contador global si existe
                                        const counter = document.getElementById('fav-counter');
                                        if(counter) {
                                            const newCount = parseInt(counter.innerText) - 1;
                                            counter.innerText = newCount;
                                            if(newCount === 0) {
                                                document.getElementById('empty-state').style.display = 'block';
                                                document.getElementById('favorites-grid').style.display = 'none';
                                            }
                                        }
                                        
                                        const Toast = Swal.mixin({
                                            toast: true,
                                            position: 'top-end',
                                            showConfirmButton: false,
                                            timer: 1500
                                        });
                                        Toast.fire({ icon: 'success', title: 'Eliminado' });
                                    }
                                })
                                .finally(() => this.loading = false);
                            }
                        }" 
                        x-show="visible" 
                        x-transition:leave="transition ease-in duration-300"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-90"
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

                            {{-- Botón Quitar Favorito --}}
                            <div class="absolute top-4 right-4 z-10">
                                <button @click.prevent="remove()"
                                    class="w-10 h-10 rounded-full bg-white/90 backdrop-blur-sm flex items-center justify-center text-red-500 shadow-lg hover:bg-white hover:scale-110 transition-all">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 fill-current"
                                        viewBox="0 0 24 24">
                                        <path
                                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                </button>
                            </div>

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
                                    {{ $inmueble->direccion }}
                                </div>

                                {{-- Características --}}
                                <div class="flex gap-4 mt-2 border-t border-border pt-4 mb-4">
                                    <div class="flex items-center gap-1.5" title="Habitaciones">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 11V9a3 3 0 013-3h10a3 3 0 013 3v2M4 11H2a1 1 0 00-1 1v3a2 2 0 002 2h1M20 11h2a1 1 0 011 1v3a2 2 0 01-2 2h-1M4 11h16v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM8 17v2M16 17v2" />
                                        </svg>
                                        <span class="text-xs font-bold text-foreground">{{ $inmueble->habitaciones }} <span class="text-[9px] text-muted-foreground font-medium">Hab</span></span>
                                    </div>
                                    <div class="flex items-center gap-1.5" title="Baños">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12h18v3a4 4 0 01-4 4H7a4 4 0 01-4-4v-3zM3 12h18M21 12v-1a2 2 0 00-2-2h-3M7 12V7a3 3 0 013-3h2M12 2v4M14 3l-2 2M10 3l2 2M6 19v2M18 19v2" />
                                        </svg>
                                        <span class="text-xs font-bold text-foreground">{{ $inmueble->banos }} <span class="text-[9px] text-muted-foreground font-medium">Baño</span></span>
                                    </div>
                                </div>

                                {{-- Sección de Notas --}}
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
