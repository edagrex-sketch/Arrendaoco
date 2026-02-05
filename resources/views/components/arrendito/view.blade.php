{{-- HTML DEL OVERLAY (CASA) --}}
<div id="lock-overlay">
    <div class="w-64 h-64 relative">
        <lottie-player id="lottie-house" src="https://assets3.lottiefiles.com/packages/lf20_yr6zz3wv.json"
            background="transparent" speed="1" style="width: 100%; height: 100%;" loop autoplay renderer="svg">
        </lottie-player>
    </div>
    <h3 id="lock-message" class="mt-8 text-2xl font-bold text-[#3E2723] animate-pulse text-center px-4">
        @auth
            Bienvenido a casa...
        @else
            Construyendo tu experiencia...
            <span class="block text-sm font-normal text-gray-500 mt-2">Modo Invitado Activo</span>
        @endauth
    </h3>
</div>

{{-- HTML DE LA LLAVE FLOTANTE --}}
<div id="floating-key-container">
    <svg class="floating-key" viewBox="0 0 100 50">
        <path
            d="M70 25 C70 15 80 5 90 5 C95 5 100 10 100 25 C100 40 95 45 90 45 C80 45 70 35 70 25 Z M70 20 L10 20 L10 30 L70 30 Z M30 30 L30 40 L40 40 L40 30 Z M20 30 L20 45 L10 45 L10 30 Z" />
    </svg>
    <div id="key-tooltip"
        class="absolute top-full mt-2 right-0 bg-[#3E2723] text-white text-xs px-2 py-1 rounded shadow-lg opacity-0 transition-opacity whitespace-nowrap">
        ¡Regístrate para usarme!
    </div>
</div>

{{-- Contenedor Principal de Arrendito --}}
<div id="mascot-wrapper">

    {{-- Menú de Asistente (Oculto por defecto) --}}
    <div id="mascot-menu" class="hidden-menu">
        <div class="menu-header">
            <span class="text-xs font-black uppercase tracking-tighter text-primary">Asistente Arrendaoco</span>
            <button onclick="toggleMascotMenu()" class="text-muted-foreground hover:text-foreground">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="menu-options">
            <button onclick="toggleMascotChat()" class="menu-item">
                <div class="item-icon">💬</div>
                <div class="item-text"><b>Hablar</b> con IA</div>
            </button>
            <a href="{{ route('favoritos.index') }}" class="menu-item">
                <div class="item-icon">❤️</div>
                <div class="item-text">Ver mis <b>Favoritos</b></div>
            </a>
            <button onclick="startMiniGuide()" class="menu-item">
                <div class="item-icon">💡</div>
                <div class="item-text">¿Cómo <b>rentar</b>?</div>
            </button>
            <button onclick="renameMascot()" class="menu-item">
                <div class="item-icon">✏️</div>
                <div class="item-text">Cambiar mi <b>nombre</b></div>
            </button>
        </div>
        <div class="menu-footer">
            "Tu hogar a un clic de distancia"
        </div>
    </div>

    {{-- Ventana de Chat IA --}}
    <div id="mascot-chat" class="hidden-chat">
        <div class="chat-header">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-primary/20 flex items-center justify-center text-lg">🐶</div>
                <div>
                    <div class="text-xs font-bold text-primary leading-none" id="chat-mascot-name">ROCO AI</div>
                    <div class="text-[10px] text-green-500 flex items-center gap-1">
                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span> En línea
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="toggleMascotMenu(); event.stopPropagation();"
                    class="text-white/80 hover:text-white transition-colors p-1 rounded-lg hover:bg-white/10"
                    title="Más opciones">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                        </path>
                    </svg>
                </button>
                <button onclick="toggleMascotChat()"
                    class="text-white/80 hover:text-white transition-colors p-1 rounded-lg hover:bg-white/10"
                    title="Cerrar chat">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        </div>

        <div id="chat-messages" class="chat-body">
            <div class="msg-ai">¡Guau! Soy ROCO, tu asistente Beagle. ¿En qué puedo ayudarte hoy? 🐶🦴</div>

            {{-- Botones de respuesta rápida --}}
            <div class="quick-replies" id="quick-replies">
                <button onclick="sendQuickReply('¿Qué inmuebles tienes disponibles?')" class="quick-reply-btn">
                    🏠 Ver inmuebles
                </button>
                <button onclick="sendQuickReply('¿Cómo funciona Arrendaoco?')" class="quick-reply-btn">
                    ❓ ¿Cómo funciona?
                </button>
                <button onclick="sendQuickReply('Busco algo barato en Ocosingo')" class="quick-reply-btn">
                    💰 Opciones económicas
                </button>
            </div>
        </div>

        <div class="chat-footer">
            <div style="width: 100%;">
                <input type="text" id="chat-input" placeholder="Pregúntame sobre inmuebles..."
                    onkeypress="handleChatKeyPress(event)" oninput="updateCharCounter()" maxlength="500">
                <div class="char-counter" id="char-counter">0/500</div>
            </div>
            <button onclick="sendMascotMessage()" id="chat-send-btn" title="Enviar mensaje (Enter)">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                </svg>
            </button>
        </div>
    </div>

    {{-- Globo de Texto Interactivo --}}
    <div class="mascot-bubble" onclick="toggleMascotChat()">
        Soy <span id="mascot-name-display" class="mascot-name-highlight">ROCO</span>.
        <br><span style="font-weight:400; color:#888; font-size:10px;">¡Haz clic para hablar!</span>
    </div>

    {{-- Escena de la Mascarota --}}
    <div class="mascot-scene">
        {{-- Click directo abre el chat --}}
        <div onclick="toggleMascotChat()" style="width:100%; height:100%; cursor: pointer; position: relative;"
            title="¡Haz clic para chatear conmigo!">
            <lottie-player id="roco-player" src="https://assets4.lottiefiles.com/packages/lf20_syqnfe7c.json"
                background="transparent" speed="1" loop autoplay renderer="svg">
            </lottie-player>

            {{-- Tooltip flotante --}}
            <div class="chat-tooltip" id="chat-tooltip">
                💬 ¡Chatea conmigo!
            </div>
        </div>

        {{-- Hueso de Juguete --}}
        <div class="yarn-pro" onclick="playWithBone(); event.stopPropagation();" title="¡Juega conmigo!"></div>
    </div>

    {{-- Menú de opciones (accesible con botón en el chat) --}}
    <div id="mascot-menu" class="hidden-menu">
        <div class="menu-option" onclick="window.location.href='/favoritos'">
            <span class="menu-icon">❤️</span>
            <span class="menu-text">Ver mis <b>Favoritos</b></span>
        </div>
        <div class="menu-option" onclick="window.location.href='/inicio#como-rentar'">
            <span class="menu-icon">💡</span>
            <span class="menu-text">¿Cómo <b>rentar</b>?</span>
        </div>
        <div class="menu-option" onclick="renameMascot()">
            <span class="menu-icon">✏️</span>
            <span class="menu-text">Cambiar mi <b>nombre</b></span>
        </div>
    </div>
</div>
