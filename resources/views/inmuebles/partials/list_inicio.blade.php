        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold text-[#003049]">Propiedades Disponibles</h2>
            <div class="flex items-center gap-4">
                <span class="text-xs font-bold text-slate-400 bg-slate-50 px-3 py-1.5 rounded-full border border-slate-100">
                    {{ $inmuebles->total() }} resultados
                    @guest <span>(Vista Invitado)</span> @endguest
                </span>
            </div>
        </div>

        {{-- Grid de Tarjetas --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-12">
            @forelse ($inmuebles as $inmueble)
                <div class="group bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md hover:-translate-y-1">
                    {{-- Imagen --}}
                    <div class="relative h-56 overflow-hidden">
                        @if ($inmueble->imagen)
                            <img src="{{ str_starts_with($inmueble->imagen, 'http') ? $inmueble->imagen : (str_contains($inmueble->imagen, 'storage/') ? asset($inmueble->imagen) : asset('storage/' . $inmueble->imagen)) }}" alt="{{ $inmueble->titulo }}"
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        @else
                            <div class="w-full h-full bg-slate-50 flex items-center justify-center text-slate-300">
                                <span class="text-xs font-bold uppercase tracking-widest">Sin imagen</span>
                            </div>
                        @endif

                        {{-- Badge de Precio --}}
                        <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm px-3 py-1.5 rounded-full shadow-sm border border-slate-100">
                            <span class="font-bold text-[#003049]">${{ number_format($inmueble->renta_mensual ?? 0) }}</span>
                            <span class="text-[10px] text-slate-500">/ mes</span>
                        </div>

                        {{-- Botón Favorito --}}
                        @auth
                            @unless(Auth::user()->tieneRol('admin') || Auth::user()->es_admin)
                            <div class="absolute top-4 left-4 z-10" x-data="{ 
                                isFavorited: {{ in_array($inmueble->id, $favoritosIds) ? 'true' : 'false' }},
                                loading: false,
                                toggle() {
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
                                        if(data.success) {
                                            this.isFavorited = data.agregado;
                                            
                                            // Toast de confirmación premium
                                             const Toast = Swal.mixin({
                                                 toast: true,
                                                 position: 'top-end',
                                                 showConfirmButton: false,
                                                 timer: 1500
                                             });

                                             Toast.fire({
                                                 icon: 'success',
                                                 title: data.agregado ? 'Agregado' : 'Eliminado'
                                             });
                                        }
                                    })
                                    .finally(() => this.loading = false);
                                }
                            }">
                                <button @click.prevent="toggle()" 
                                    class="h-10 w-10 flex items-center justify-center rounded-full bg-white/90 backdrop-blur-md shadow-lg transition-all hover:scale-110 active:scale-95 group/fav"
                                    :class="isFavorited ? 'text-red-500' : 'text-slate-400'">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 transition-all duration-300" 
                                         :class="isFavorited ? 'fill-current' : 'fill-none'" 
                                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                </button>
                            </div>
                            @endunless
                        @endauth
                    </div>

                    {{-- Contenido --}}
                    <div class="p-6">
                        <h3 class="font-bold text-lg text-[#003049] line-clamp-1 mb-1">
                            {{ $inmueble->titulo }}</h3>
                        <p class="text-sm text-slate-400 flex items-center gap-1.5 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ $inmueble->direccion }}
                        </p>

                        <div class="flex items-center gap-4 py-4 border-t border-slate-100 mb-6">
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

                            @if(Auth::id() === $inmueble->propietario_id)
                                <a href="{{ route('inmuebles.show', $inmueble) }}"
                                    class="flex w-full py-4 items-center justify-center rounded-2xl bg-gradient-to-br from-[#003049] to-[#004e7a] text-sm font-black text-white transition-all hover:-translate-y-1 shadow-lg shadow-[#003049]/20 uppercase tracking-widest">
                                    Gestionar Propiedad
                                </a>
                            @else
                                <a href="{{ route('inmuebles.show', $inmueble) }}"
                                    class="flex w-full py-4 items-center justify-center rounded-2xl bg-slate-100 text-sm font-black text-[#003049] transition-all hover:bg-slate-200 uppercase tracking-widest">
                                    Ver Detalles
                                </a>
                            @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center text-slate-400 font-medium uppercase tracking-widest opacity-50">No hay propiedades disponibles.</div>
            @endforelse
        {{-- Paginación --}}
        <div class="mt-16 px-4 flex justify-center">
            {{ $inmuebles->links() }}
        </div>
