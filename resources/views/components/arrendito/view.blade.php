@include('components.arrendito.styles')

<div id="roco-widget-container">
    
    {{-- GRUPO ASISTENTE (Burbuja + Mascota) --}}
    <div id="roco-visual-group">
        {{-- Burbuja --}}
        <div class="mascot-bubble" id="roco-bubble" onclick="toggleMascotChat()" style="margin-bottom: 5px; cursor: pointer; position: relative; z-index: 10;">
            ¡Guau! Soy **Roco**. <br>
            ¿En qué puedo ayudarte?
        </div>

        {{-- Mascota --}}
        <div id="roco-mascot-wrapper" onclick="toggleMascotChat()" style="position: relative; cursor: pointer; display: flex; align-items: center; justify-content: center;">
            <lottie-player id="roco-lottie" src="https://assets4.lottiefiles.com/packages/lf20_syqnfe7c.json"
                background="transparent" speed="1" loop autoplay style="display: block;">
            </lottie-player>
        </div>
    </div>

    {{-- BARRA DE CONTROLES --}}
    <div style="display: flex; align-items: center; gap: 10px; margin-top: 5px; pointer-events: auto;">
        {{-- Toggle Flecha --}}
        <button id="roco-toggle-btn" onclick="toggleRoco()" style="background: white; border: 2px solid #e2e8f0; border-radius: 50%; width: 38px; height: 38px; cursor: pointer; color: #1F3A5F; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(0,0,0,0.1); transition: transform 0.3s ease;">
            <svg id="roco-arrow" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                <path id="roco-arrow-path" stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        {{-- Botón Chat --}}
        <button class="assistant-toggle-btn" onclick="toggleMascotChat()" style="margin: 0; min-width: 170px; justify-content: center;">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                </svg>
                <span style="font-size: 13px; font-weight: 800;">HABLAR CON ROCO</span>
            </div>
        </button>
    </div>
</div>

{{-- CHAT BOX --}}
<div id="mascot-chat" class="hidden-chat">
    <div class="chat-header">
        <div class="flex items-center gap-3">
            <span style="font-size: 18px;">🐶</span>
            <div class="font-bold">ROCO AI</div>
        </div>
        <button onclick="toggleMascotChat()" class="text-white hover:opacity-70"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg></button>
    </div>
    <div class="chat-body" id="chat-body">
        <div class="msg-ai">¡Hola! Soy Roco. ¿Buscas casa o eres dueño?</div>
    </div>
    <div class="chat-footer">
        <input type="text" id="chat-input" placeholder="Escribe aquí..." onkeydown="if(event.key === 'Enter') sendRocoMsg()">
        <button onclick="sendRocoMsg()"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 12h14M12 5l7 7-7 7" /></svg></button>
    </div>
</div>

<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
<script>
    let isRocoVisible = true;
    let isChatOpen = false;

    function toggleRoco() {
        const group = document.getElementById('roco-visual-group');
        const arrow = document.getElementById('roco-arrow-path');
        const player = document.getElementById('roco-lottie');
        
        isRocoVisible = !isRocoVisible;

        if (isRocoVisible) {
            group.style.opacity = '1';
            group.style.visibility = 'visible';
            group.style.height = 'auto';
            group.style.transform = 'scale(1)';
            arrow.setAttribute('d', 'M19 9l-7 7-7-7');
            if(player.play) player.play();
        } else {
            group.style.opacity = '0';
            group.style.visibility = 'hidden';
            group.style.height = '0';
            group.style.transform = 'scale(0.8)';
            arrow.setAttribute('d', 'M5 15l7-7 7 7');
        }
    }

    function toggleMascotChat() {
        const chat = document.getElementById('mascot-chat');
        const container = document.getElementById('roco-widget-container');
        isChatOpen = !isChatOpen;
        if (isChatOpen) {
            chat.classList.remove('hidden-chat');
            container.style.display = 'none';
            document.getElementById('chat-input').focus();
        } else {
            chat.classList.add('hidden-chat');
            container.style.display = 'flex';
        }
    }

    async function sendRocoMsg() {
        const input = document.getElementById('chat-input');
        const text = input.value.trim();
        if(!text) return;
        
        addMessage(text, 'user');
        input.value = '';
        
        // Indicador de "Escribiendo..."
        const body = document.getElementById('chat-body');
        const typingDiv = document.createElement('div');
        typingDiv.className = 'msg-ai';
        typingDiv.id = 'roco-typing';
        typingDiv.innerText = 'Roco está escribiendo...';
        typingDiv.style.fontStyle = 'italic';
        typingDiv.style.color = '#94a3b8';
        body.appendChild(typingDiv);
        body.scrollTop = body.scrollHeight;

        try {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            const response = await fetch('/arrendito/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({ message: text })
            });

            // Quitar "escribiendo..."
            const tDiv = document.getElementById('roco-typing');
            if (tDiv) tDiv.remove();

            if (!response.ok) throw new Error('Error en la respuesta del servidor');
            
            const data = await response.json();
            addMessage(data.response || 'Lo siento, no pude procesar tu solicitud.', 'ai');
        } catch (error) {
            console.error(error);
            const tDiv = document.getElementById('roco-typing');
            if (tDiv) tDiv.remove();
            addMessage('Hubo un error de conexión. Intenta de nuevo más tarde.', 'ai');
        }
    }

    function addMessage(text, sender) {
        const body = document.getElementById('chat-body');
        const div = document.createElement('div');
        div.className = sender === 'user' ? 'msg-user' : 'msg-ai';
        div.innerHTML = text; // Allow HTML tags from AI
        body.appendChild(div);
        body.scrollTop = body.scrollHeight;
    }
</script>
