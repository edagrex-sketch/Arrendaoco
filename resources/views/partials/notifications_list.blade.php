@forelse($notificaciones as $notificacion)
    <div class="px-5 py-4 border-b border-gray-50 hover:bg-gray-50 transition-colors cursor-pointer group {{ $notificacion->leida ? 'opacity-70' : 'bg-brand-light/5' }}"
         onclick="markAsRead({{ $notificacion->id }}, '{{ $notificacion->url ?? '#' }}')">
        <div class="flex items-start gap-4">
            {{-- Icono según tipo --}}
            <div class="flex-shrink-0 mt-1">
                @switch($notificacion->tipo)
                    @case('renta')
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        @break
                    @case('pago')
                        <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3 1.343 3-3-1.343-3-3-3zM12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2z" />
                            </svg>
                        </div>
                        @break
                    @default
                        <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                @endswitch
            </div>

            <div class="flex-1">
                <div class="flex justify-between items-start mb-1">
                    <h4 class="text-sm font-bold text-gray-900 group-hover:text-brand-dark transition-colors">
                        {{ $notificacion->titulo }}
                    </h4>
                    <span class="text-[10px] font-medium text-gray-400 whitespace-nowrap ml-2">
                        {{ $notificacion->created_at->diffForHumans() }}
                    </span>
                </div>
                <p class="text-xs text-gray-500 leading-relaxed line-clamp-2">
                    {{ $notificacion->mensaje }}
                </p>
            </div>
            
            @if(!$notificacion->leida)
                <div class="w-2 h-2 mt-2 bg-brand-light rounded-full flex-shrink-0 animate-pulse"></div>
            @endif
        </div>
    </div>
@empty
    <div class="py-12 px-5 text-center">
        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
        </div>
        <p class="text-sm text-gray-500 font-medium tracking-tight">No tienes notificaciones pendientes</p>
        <p class="text-xs text-gray-400 mt-1">¡Te avisaremos cuando pase algo importante!</p>
    </div>
@endforelse
