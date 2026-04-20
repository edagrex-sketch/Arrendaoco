@extends('chats.index', ['currentChat' => $chat])

@section('chat_content')
@php $otro = $chat->getOtroUsuario(Auth::id()); @endphp

<!-- Header del Chat -->
<header class="h-[80px] px-6 border-b border-gray-100 flex items-center justify-between bg-white/80 backdrop-blur-md sticky top-0 z-10">
    <div class="flex items-center gap-4">
        <!-- Botón Volver (Móvil) -->
        <a href="{{ route('chats.index') }}" class="md:hidden p-2 -ml-2 text-gray-400 hover:text-[#003049]">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        
        <div class="relative">
            @if($otro->foto_perfil)
                <img src="{{ str_starts_with($otro->foto_perfil, 'http') ? $otro->foto_perfil : asset('storage/'.$otro->foto_perfil) }}" class="w-12 h-12 rounded-2xl object-cover shadow-sm">
            @else
                <div class="w-12 h-12 rounded-2xl bg-[#669BBC]/10 text-[#003049] flex items-center justify-center font-black">
                    {{ substr($otro->nombre, 0, 1) }}
                </div>
            @endif
            <div class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 bg-green-500 border-2 border-white rounded-full"></div>
        </div>
        
        <div>
            <h2 class="font-black text-[#003049] leading-none mb-1">{{ $otro->nombre }}</h2>
            <div class="flex items-center gap-2">
                <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">En línea</span>
            </div>
        </div>
    </div>

    <!-- Info del Inmueble (Compacta) -->
    @if($chat->inmueble)
    <div class="hidden sm:flex items-center gap-3 p-2 pr-4 bg-gray-50 rounded-2xl border border-gray-100 group cursor-pointer hover:border-[#669BBC]/30 transition-all">
        <img src="{{ asset('storage/'.($chat->inmueble->imagenes[0] ?? 'default.jpg')) }}" class="w-10 h-10 rounded-xl object-cover">
        <div class="max-w-[150px]">
            <p class="text-[10px] font-black text-[#003049] truncate">{{ $chat->inmueble->titulo }}</p>
            <p class="text-[9px] font-bold text-[#669BBC]">${{ number_format($chat->inmueble->precio, 0) }}/mes</p>
        </div>
    </div>
    @endif
</header>

<!-- Contenedor de Mensajes -->
<div id="chat-messages-container" class="flex-1 overflow-y-auto px-6 py-8 space-y-6 custom-scrollbar bg-[#FCFDFF]">
    @foreach($mensajes as $msj)
        @include('chats.partials.message-bubble', ['msj' => $msj, 'isMe' => $msj->sender_id == Auth::id()])
    @endforeach
</div>

<!-- Footer / Input -->
<footer class="p-6 bg-white border-t border-gray-100">
    <!-- Reply Preview (Hidden by default) -->
    <div id="reply-preview" class="hidden mb-4 p-4 bg-gray-50 rounded-[20px] border-l-4 border-[#003049] flex items-center justify-between animate-in slide-in-from-bottom-2">
        <div class="min-w-0">
            <p class="text-[10px] font-black text-[#003049] uppercase tracking-wider mb-1">Respondiendo a <span id="reply-name"></span></p>
            <p id="reply-text" class="text-xs text-gray-500 truncate"></p>
        </div>
        <button onclick="cancelReply()" class="p-2 text-gray-400 hover:text-red-500 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    <form id="chat-form" class="relative flex items-end gap-3">
        <input type="hidden" name="parent_id" id="parent_id">
        
        <!-- Botón Acciones -->
        <div class="relative">
            <button type="button" onclick="toggleActions()" class="p-4 bg-gray-50 text-gray-400 rounded-2xl hover:bg-gray-100 hover:text-[#003049] transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            </button>
            
            <!-- Menú de Acciones -->
            <div id="actions-menu" class="hidden absolute bottom-full left-0 mb-4 w-64 bg-white rounded-[24px] shadow-2xl border border-gray-100 p-3 animate-in fade-in zoom-in-95 duration-200">
                <button type="button" onclick="sendActionMessage('oferta')" class="w-full flex items-center gap-3 p-3 rounded-xl hover:bg-blue-50 text-[#003049] group transition-all">
                    <div class="p-2 bg-blue-100 rounded-lg group-hover:bg-blue-200 transition-all">
                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    </div>
                    <div class="text-left">
                        <p class="text-xs font-black">Enviar Oferta</p>
                        <p class="text-[10px] text-gray-400">Interés formal de renta</p>
                    </div>
                </button>
                <button type="button" onclick="consultarRocoMediador()" class="w-full flex items-center gap-3 p-3 rounded-xl hover:bg-purple-50 text-[#003049] group transition-all mt-1">
                    <div class="p-2 bg-purple-100 rounded-lg group-hover:bg-purple-200 transition-all">
                        <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/></svg>
                    </div>
                    <div class="text-left">
                        <p class="text-xs font-black">Roco Mediador AI</p>
                        <p class="text-[10px] text-gray-400">Analizar conversación</p>
                    </div>
                </button>
            </div>
        </div>

        <!-- Input Principal -->
        <div class="flex-1 bg-gray-50 rounded-[28px] flex items-end p-2 border border-gray-100 focus-within:bg-white focus-within:border-[#003049]/20 transition-all shadow-sm">
            <textarea id="chat-textarea" 
                    placeholder="Escribe un mensaje elegante..." 
                    class="flex-1 bg-transparent border-none focus:ring-0 text-sm py-3 px-4 resize-none max-h-32 min-h-[48px] placeholder:text-gray-400 font-medium"
                    rows="1"></textarea>
            
            <button type="submit" class="p-3 bg-[#003049] text-white rounded-2xl shadow-xl shadow-[#003049]/20 hover:scale-105 active:scale-95 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
            </button>
        </div>
    </form>
</footer>

@push('scripts')
<script>
    const container = document.getElementById('chat-messages-container');
    const form = document.getElementById('chat-form');
    const input = document.getElementById('chat-textarea');
    const parentInput = document.getElementById('parent_id');
    const replyPreview = document.getElementById('reply-preview');
    const containerTop = 0;

    // Auto-resize textarea
    input.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });

    window.addEventListener('load', () => {
        const myId = "{{ Auth::id() }}";
        container.scrollTop = container.scrollHeight;
        
        if (window.Echo) {
            window.Echo.private('chat.' + "{{ $chat->id }}")
                .listen('.MessageSent', (data) => {
                    appendMessage(data.mensaje, data.mensaje.sender_id == myId);
                });
        }
    });

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const contenido = input.value.trim();
        if (!contenido) return;

        // Reset UI
        const tempId = 'temp-' + Date.now();
        const parentId = parentInput.value;
        input.value = '';
        input.style.height = 'auto';
        cancelReply();

        appendMessage({
            id: tempId,
            contenido: contenido,
            sender_id: "{{ Auth::id() }}",
            isTemp: true,
            created_at: new Date()
        }, true);

        const formData = new FormData();
        formData.append('contenido', contenido);
        if (parentId) formData.append('parent_id', parentId);
        formData.append('_token', '{{ csrf_token() }}');

        try {
            const socketId = window.Echo ? window.Echo.socketId() : null;
            const response = await fetch("{{ route('chats.messages.send', $chat->id) }}", {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-Socket-ID': socketId }
            });
            const data = await response.json();
            
            const tempMsg = document.querySelector(`[data-id="${tempId}"]`);
            if (tempMsg) tempMsg.closest('.message-wrapper').remove();
            appendMessage(data.mensaje, true);
        } catch (error) {
            console.error('Error:', error);
        }
    });

    function setReply(el) {
        const id = el.dataset.id;
        const nombre = el.dataset.sender;
        const texto = el.dataset.contenido;
        
        parentInput.value = id;
        document.getElementById('reply-name').innerText = nombre;
        document.getElementById('reply-text').innerText = texto;
        replyPreview.classList.remove('hidden');
        input.focus();
    }

    function cancelReply() {
        parentInput.value = '';
        replyPreview.classList.add('hidden');
    }

    function toggleActions() {
        document.getElementById('actions-menu').classList.toggle('hidden');
    }

    const appendMessage = (msg, isMe) => {
        if (msg.id && !msg.id.toString().startsWith('temp-') && document.querySelector(`[data-id="${msg.id}"]`)) return;

        const wrapper = document.createElement('div');
        wrapper.className = `message-wrapper w-full flex ${isMe ? 'justify-end' : 'justify-start'} animate-in fade-in slide-in-from-bottom-4 duration-500 mb-6`;
        
        let bubbleClass = isMe ? 'bg-[#003049] text-white rounded-l-[24px] rounded-tr-[24px] rounded-br-[4px]' : 'bg-white text-[#003049] rounded-r-[24px] rounded-tl-[24px] rounded-bl-[4px] border border-gray-100 shadow-sm';
        if (msg.isTemp) bubbleClass += ' opacity-60';

        let parentHtml = '';
        if (msg.parent) {
            parentHtml = `
                <div class="mb-2 p-2 bg-black/10 rounded-xl border-l-4 border-white/30 text-[10px] opacity-80">
                    <p class="font-black">${msg.parent.sender_nombre || 'Usuario'}</p>
                    <p class="truncate font-medium">${msg.parent.contenido}</p>
                </div>
            `;
        }

        const time = msg.isTemp ? 'Ahora' : new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});

        wrapper.innerHTML = `
            <div class="max-w-[80%] lg:max-w-xl flex flex-col ${isMe ? 'items-end' : 'items-start'}">
                <div data-id="${msg.id}" data-sender="${msg.sender?.nombre || 'Usuario'}" data-contenido="${msg.contenido}" 
                     ondblclick="setReply(this)"
                     class="message-bubble px-6 py-4 transition-all duration-300 transform cursor-pointer group relative ${bubbleClass}">
                    ${parentHtml}
                    <p class="text-[14px] leading-relaxed font-medium">${msg.contenido}</p>
                    
                    <div class="absolute inset-0 bg-white/0 group-hover:bg-white/5 rounded-inherit transition-colors pointer-events-none"></div>
                </div>
                <span class="text-[10px] font-black text-gray-300 mt-2 px-1 uppercase tracking-tighter">${time}</span>
            </div>
        `;
        
        container.appendChild(wrapper);
        container.scrollTop = container.scrollHeight;
    }
</script>
@endpush
@endsection
