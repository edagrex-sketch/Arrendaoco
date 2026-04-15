@extends('chats.index', ['currentChat' => $chat])

@section('chat_content')
<div class="flex flex-col h-full h-[600px]">
    <!-- Cabecera del Chat -->
    @php
        $otroUsuario = $chat->getOtroUsuario(Auth::id());
        // Detectar si hay contrato en estado pdf_descargado para este inmueble (solo visible al propietario)
        $contratoParaSubir = null;
        if ($chat->inmueble && $chat->inmueble->propietario_id === Auth::id()) {
            $contratoParaSubir = \App\Models\Contrato::where('inmueble_id', $chat->inmueble_id)
                ->where('estatus', 'pdf_descargado')
                ->latest()
                ->first();
        }
    @endphp

    {{-- Banner de Accion Rapida: PDF pendiente de subir --}}
    @if($contratoParaSubir)
    <div class="bg-[#FDF0D5] border-b border-[#669BBC]/20 px-6 py-3 flex items-center justify-between gap-3 flex-wrap">
        <div class="flex items-center gap-3">
            <div class="h-8 w-8 rounded-lg bg-[#003049] flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#FDF0D5]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-[#003049]">Acción requerida</p>
                <p class="text-xs font-bold text-[#003049]/80">
                    El inquilino descargó el contrato el {{ \Carbon\Carbon::parse($contratoParaSubir->pdf_descargado_at)->format('d/m/Y') }}.
                    Sube el escaneo firmado para activar el arrendamiento.
                </p>
            </div>
        </div>
        <a href="{{ route('contratos.subir-firmado', $contratoParaSubir->id) }}"
           class="shrink-0 bg-[#003049] text-white text-xs font-black px-4 py-2 rounded-xl hover:bg-[#002236] transition-all hover:-translate-y-0.5 shadow-sm whitespace-nowrap">
            Subir contrato firmado →
        </a>
    </div>
    @endif

    <div class="p-6 border-b border-gray-100 bg-white flex items-center justify-between shadow-sm z-10">
        <div class="flex items-center gap-4">
            <div class="flex-shrink-0">
                @if($otroUsuario->foto_perfil)
                    <img src="{{ str_starts_with($otroUsuario->foto_perfil, 'http') ? $otroUsuario->foto_perfil : asset('storage/'.$otroUsuario->foto_perfil) }}" 
                        class="w-10 h-10 rounded-xl object-cover">
                @else
                    <div class="w-10 h-10 rounded-xl bg-[#003049] text-white flex items-center justify-center font-bold">
                        {{ substr($otroUsuario->nombre, 0, 1) }}
                    </div>
                @endif
            </div>
            <div>
                <h2 class="font-bold text-[#003049] text-lg">{{ $otroUsuario->nombre }}</h2>
                @if($chat->inmueble)
                    <p class="text-[10px] text-[#669BBC] font-bold flex items-center gap-1 truncate max-w-[200px]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        {{ $chat->inmueble->titulo }}
                    </p>
                @else
                    <p class="text-[10px] text-green-500 font-bold flex items-center gap-1">
                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span> Activo ahora
                    </p>
                @endif
            </div>
        </div>
        <div class="flex items-center gap-2">
            {{-- Botón Ver Propiedad si el chat está asociado a un inmueble --}}
            @if($chat->inmueble)
            <a href="{{ route('inmuebles.show', $chat->inmueble) }}"
               class="p-2 text-[#669BBC] hover:text-[#003049] transition-colors rounded-lg hover:bg-gray-50" title="Ver propiedad">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
            </a>
            @endif
            <button class="p-2 text-gray-400 hover:text-[#003049] transition-colors rounded-lg hover:bg-gray-50">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
            </button>
        </div>
    </div>

    <!-- Área de Mensajes -->
    <div id="mensajes-container" class="flex-1 overflow-y-auto p-6 space-y-4 bg-gray-50/20">
        @foreach($mensajes as $mensaje)
            @php $isMe = $mensaje->sender_id == Auth::id(); @endphp
            <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }} animate-fade-in">
                <div class="max-w-[75%] space-y-1">
                    <div class="px-5 py-3 rounded-2xl shadow-sm relative group {{ $isMe ? 'bg-[#003049] text-white rounded-tr-none' : 'bg-white text-gray-800 rounded-tl-none border border-gray-100' }}">
                        <p class="text-sm leading-relaxed">{{ $mensaje->contenido }}</p>
                    </div>
                    <div class="flex items-center gap-1 {{ $isMe ? 'justify-end' : 'justify-start' }}">
                        <span class="text-[9px] text-gray-400 font-medium">
                            {{ $mensaje->created_at->format('H:i') }}
                        </span>
                        @if($isMe)
                            <svg class="w-3 h-3 {{ $mensaje->leido ? 'text-[#669BBC]' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/>
                            </svg>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Formulario de Envío -->
    <div class="p-6 bg-white border-t border-gray-100">
        <form id="form-mensaje" class="flex items-center gap-4 bg-gray-100 p-2 rounded-2xl focus-within:ring-2 focus-within:ring-[#669BBC] transition-all">
            @csrf
            <button type="button" class="p-2 text-gray-400 hover:text-[#003049] transition-colors rounded-xl hover:bg-white/50">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
            </button>
            <input type="text" id="input-contenido" name="contenido" placeholder="Escribe un mensaje..." autocomplete="off"
                class="flex-1 bg-transparent border-none text-sm placeholder-gray-400 focus:ring-0">
            <button type="submit" class="bg-[#003049] text-white p-3 rounded-xl shadow-lg hover:bg-[#002538] hover:scale-105 active:scale-95 transition-all">
                <svg class="w-5 h-5 transform rotate-90" fill="currentColor" viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const container = document.getElementById('mensajes-container');
    const form = document.getElementById('form-mensaje');
    const input = document.getElementById('input-contenido');

    // Scroll al fondo al cargar
    container.scrollTop = container.scrollHeight;

    // Escucha de tiempo real con Laravel Echo
    window.addEventListener('load', () => {
        if (window.Echo) {
            window.Echo.private(`chat.{{ $chat->id }}`)
                .listen('MessageSent', (e) => {
                    appendMessage(e.mensaje, false);
                    container.scrollTop = container.scrollHeight;
                });
        }
    });

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const contenido = input.value.trim();
        if (!contenido) return;

        input.value = '';
        
        try {
            const response = await fetch("{{ route('chats.message.send', $chat) }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                body: JSON.stringify({ contenido })
            });

            const data = await response.json();
            if (data.success) {
                appendMessage(data.mensaje, true);
                container.scrollTop = container.scrollHeight;
            }
        } catch (error) {
            console.error('Error enviando mensaje:', error);
        }
    });

    function appendMessage(msg, isMe = true) {
        const div = document.createElement('div');
        div.className = `flex ${isMe ? 'justify-end' : 'justify-start'} animate-fade-in`;
        
        const bgColor = isMe ? 'bg-[#003049] text-white rounded-tr-none' : 'bg-white text-gray-800 rounded-tl-none border border-gray-100';
        const timeAlign = isMe ? 'justify-end' : 'justify-start';

        div.innerHTML = `
            <div class="max-w-[75%] space-y-1">
                <div class="px-5 py-3 rounded-2xl shadow-sm relative group ${bgColor}">
                    <p class="text-sm leading-relaxed">${msg.contenido}</p>
                </div>
                <div class="flex items-center gap-1 ${timeAlign}">
                    <span class="text-[9px] text-gray-400 font-medium">Ahora</span>
                    ${isMe ? `
                        <svg class="w-3 h-3 text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/>
                        </svg>
                    ` : ''}
                </div>
            </div>
        `;
        container.appendChild(div);
    }
</script>
@endpush
@endsection
