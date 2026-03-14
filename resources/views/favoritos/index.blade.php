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
                                <img src="{{ $inmueble->imagen_url }}" alt="{{ $inmueble->titulo }}"
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
                                    class="w-10 h-10 rounded-full bg-white/90 backdrop-blur-sm flex items-center justify-center text-red-500 shadow-lg hover:scale-110 active:scale-95 transition-all group/fav">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 fill-current transition-all duration-300"
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
                                <div class="flex items-center gap-4 py-4 border-t border-slate-100 mt-2 mb-4">
                                    <div class="flex items-center gap-1.5 text-slate-500" title="Habitaciones">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="w-5 h-5 opacity-70" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M3 11v3a2 2 0 002 2h14a2 2 0 002-2v-3"></path><path d="M5 16v2"></path><path d="M19 16v2"></path><path d="M5 11V7a2 2 0 012-2h10a2 2 0 012 2v4"></path><path d="M5 11h14"></path>
                                        </svg>
                                        <span class="text-base font-bold text-slate-700">{{ $inmueble->habitaciones }} <span class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider ml-0.5">Hab</span></span>
                                    </div>
                                    <div class="flex items-center gap-1.5 text-slate-500" title="Baños">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="w-5 h-5 opacity-70" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M9 6 6.5 3.5a1.5 1.5 0 0 0-1-.5C4.683 3 4 3.683 4 4.5V17a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-5"/><line x1="10" x2="8" y1="5" y2="7"/><line x1="2" x2="22" y1="12" y2="12"/><line x1="7" x2="7" y1="19" y2="21"/><line x1="17" x2="17" y1="19" y2="21"/>
                                        </svg>
                                        <span class="text-base font-bold text-slate-700">{{ $inmueble->banos }} <span class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider ml-0.5">Baño</span></span>
                                    </div>
                                    <div class="flex items-center gap-1.5 text-slate-500" title="Superficie">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="w-5 h-5 opacity-70" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="m21 21-6-6m6 6v-4.8m0 4.8h-4.8"/><path d="M3 16.2V21m0 0h4.8M3 21l6-6"/><path d="M21 7.8V3m0 0h-4.8M21 3l-6 6"/><path d="M3 7.8V3m0 0h4.8M3 3l6 6"/>
                                        </svg>
                                        <span class="text-base font-bold text-slate-700">{{ number_format($inmueble->metros ?? 0, 0) }} <span class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider ml-0.5">M²</span></span>
                                    </div>
                                </div>

                            </div>

                            <div class="mt-8">
                                <a href="{{ route('inmuebles.show', $inmueble) }}"
                                    class="flex items-center justify-center py-3.5 px-4 rounded-2xl bg-primary text-white font-bold shadow-xl shadow-primary/20 hover:scale-[1.02] transition-all uppercase tracking-widest text-xs">
                                    Ver Detalles
                                </a>
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

@endsection
