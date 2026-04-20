@php
    $isMe = $msj->sender_id == Auth::id();
    $bubbleClass = $isMe 
        ? 'bg-[#003049] text-white rounded-l-[24px] rounded-tr-[24px] rounded-br-[4px] shadow-sm' 
        : 'bg-white text-[#003049] rounded-r-[24px] rounded-tl-[24px] rounded-bl-[4px] border border-gray-100 shadow-sm';
@endphp

<div class="message-wrapper w-full flex {{ $isMe ? 'justify-end' : 'justify-start' }} animate-in fade-in slide-in-from-bottom-4 duration-500 mb-6 group">
    <div class="max-w-[80%] lg:max-w-xl flex flex-col {{ $isMe ? 'items-end' : 'items-start' }}">
        
        <div data-id="{{ $msj->id }}" 
             data-sender="{{ $msj->sender->nombre ?? 'Usuario' }}" 
             data-contenido="{{ $msj->contenido }}"
             ondblclick="setReply(this)"
             class="message-bubble px-6 py-4 transition-all duration-300 transform cursor-pointer relative {{ $bubbleClass }} hover:translate-y-[-2px]">
            
            {{-- Respuesta (si existe) --}}
            @if($msj->parent)
                <div class="mb-3 p-3 bg-black/10 rounded-xl border-l-4 border-white/30 text-[10px] opacity-80 backdrop-blur-sm">
                    <p class="font-black mb-1 uppercase tracking-tighter">{{ $msj->parent->sender->nombre ?? 'Usuario' }}</p>
                    <p class="truncate font-medium italic opacity-90">"{{ $msj->parent->contenido }}"</p>
                </div>
            @endif

            {{-- Contenido según tipo --}}
            @if($msj->tipo == 'oferta')
                <div class="flex items-center gap-3 mb-2 pb-2 border-b border-white/20">
                    <div class="p-2 bg-blue-400/20 rounded-lg">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a8 8 0 100 16 8 8 0 000-16zm1 11H9v-2h2v2zm0-4H9V7h2v2z"/></svg>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-widest">Solicitud de Reserva</span>
                </div>
            @endif

            <p class="text-[14px] leading-relaxed font-medium">{{ $msj->contenido }}</p>
            
            {{-- Acciones rápidas (aparecen en hover) --}}
            <div class="absolute {{ $isMe ? '-left-8' : '-right-8' }} top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity p-2">
                <button onclick="setReply(this.closest('.message-bubble'))" class="text-gray-300 hover:text-[#669BBC] transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                </button>
            </div>
        </div>

        <div class="flex items-center gap-2 mt-2 px-2">
            <span class="text-[10px] font-black text-gray-400 uppercase tracking-tighter">
                {{ $msj->created_at->format('h:i A') }}
            </span>
            @if($isMe)
                <div class="flex items-center">
                    <svg class="w-3.5 h-3.5 text-blue-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            @endif
        </div>
    </div>
</div>
