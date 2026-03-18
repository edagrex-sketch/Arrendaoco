@extends('chats.index', ['currentChat' => $chat])

@section('chat_content')
<div class="flex flex-col h-full h-[600px]">
    <!-- Cabecera del Chat -->
    @php $otroUsuario = $chat->getOtroUsuario(Auth::id()); @endphp
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
                <p class="text-[10px] text-green-500 font-bold flex items-center gap-1">
                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span> Activo ahora
                </p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button class="p-2 text-gray-400 hover:text-[#003049] transition-colors rounded-lg hover:bg-gray-50">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
            </button>
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
