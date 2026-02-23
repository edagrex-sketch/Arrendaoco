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

{{-- BOTÓN DE MAXIMIZAR ASISTENTE (Oculto por defecto) --}}
<button id="maximize-assistant-btn" onclick="toggleMascotVisibility()" class="assistant-toggle-btn hidden">
    🐶 <span>Llamar a ROCO</span>
</button>

{{-- Contenedor Principal de Arrendito --}}
<div id="mascot-wrapper">

    {{-- Menú de Asistente --}}
    <div id="mascot-menu" class="hidden-menu">
        <div class="menu-header">
            <span>Asistente ArrendaOco</span>
            <button onclick="toggleMascotMenu()" class="text-white/70 hover:text-white transition-all hover:rotate-90 duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="menu-options">
            <button onclick="toggleMascotChat()" class="menu-item">
                <div class="item-icon">💬</div>
                <div class="item-text"><b>Hablar</b> con ROCO AI</div>
            </button>
            @unless(Auth::check() && (Auth::user()->tieneRol('admin') || Auth::user()->es_admin))
            <a href="{{ route('favoritos.index') }}" class="menu-item">
                <div class="item-icon">❤️</div>
                <div class="item-text">Ver mis <b>Favoritos</b></div>
            </a>
            @endunless
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
            🏠 Tu hogar a un clic de distancia
        </div>
    </div>

    {{-- Ventana de Chat IA (Premium) --}}
    <div id="mascot-chat" class="hidden-chat">
        {{-- Header Premium --}}
        <div class="chat-header">
            <div class="flex items-center gap-3" style="position: relative; z-index: 1;">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-lg"
                     style="background: rgba(255,255,255,0.2); backdrop-filter: blur(10px); border: 2px solid rgba(255,255,255,0.3);">
                    🐶
                </div>
                <div>
                    <div class="font-bold leading-none" id="chat-mascot-name" style="font-size: 14px; letter-spacing: 0.5px;">ROCO AI</div>
                    <div style="font-size: 11px; color: rgba(255,255,255,0.8); display: flex; align-items: center; gap: 5px; margin-top: 2px;">
                        <span style="width: 7px; height: 7px; background: #10B981; border-radius: 50%; display: inline-block; box-shadow: 0 0 6px rgba(16, 185, 129, 0.6);"></span>
                        En línea · Gemini 2.5
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-1" style="position: relative; z-index: 1;">
                <button onclick="toggleMascotMenu(); event.stopPropagation();"
                    class="transition-all p-2 rounded-xl hover:bg-white/15"
                    title="Más opciones" style="color: rgba(255,255,255,0.8);">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                        </path>
                    </svg>
                </button>
                <button onclick="toggleMascotChat()"
                    class="transition-all p-2 rounded-xl hover:bg-white/15"
                    title="Cerrar chat" style="color: rgba(255,255,255,0.8);">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Cuerpo del Chat --}}
        <div id="chat-messages" class="chat-body">
            <div class="msg-ai">
                ¡Guau! 🦴 Soy <b>ROCO</b>, tu asistente Beagle con inteligencia artificial.<br>
                Puedo ayudarte a encontrar el inmueble perfecto en <b>Ocosingo</b>. ¿En qué te ayudo hoy? 🏠
            </div>

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
                <button onclick="sendQuickReply('¿Qué hay cerca de la UTS?')" class="quick-reply-btn">
                    🎓 Cerca de la UTS
                </button>
            </div>
        </div>

        {{-- Footer Premium --}}
        <div class="chat-footer">
            <div style="width: 100%;">
                <input type="text" id="chat-input" placeholder="Pregúntame sobre inmuebles..."
                    onkeypress="handleChatKeyPress(event)" oninput="updateCharCounter()" maxlength="500"
                    autocomplete="off">
                <div class="char-counter" id="char-counter">0/500</div>
            </div>
            <button onclick="sendMascotMessage()" id="chat-send-btn" title="Enviar mensaje (Enter)">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                </svg>
            </button>
        </div>

        {{-- Powered By --}}
        <div class="roco-powered-by">
            Potenciado por <span>Google Gemini AI</span> · ArrendaOco
        </div>
    </div>

    {{-- Globo de Texto Interactivo --}}
    <div class="mascot-bubble" onclick="toggleMascotChat()">
        Soy <span id="mascot-name-display" class="mascot-name-highlight">ROCO</span>.
        <br><span style="font-weight:400; color:#94a3b8; font-size:10px;">¡Haz clic para hablar!</span>
    </div>

    {{-- Escena de la Mascota --}}
    <div class="mascot-scene">
        {{-- Botón de minimizar mascota --}}
        <button onclick="toggleMascotVisibility()" class="minimize-assistant-btn" title="Ocultar asistente">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        {{-- Click directo abre el chat --}}
        <div onclick="toggleMascotChat()" style="width:100%; height:100%; cursor: pointer; position: relative;"
            title="¡Haz clic para chatear conmigo!">
            <lottie-player id="roco-player" src="https://assets4.lottiefiles.com/packages/lf20_syqnfe7c.json"
                background="transparent" speed="1" loop autoplay renderer="svg">
            </lottie-player>

            {{-- Tooltip flotante --}}
            <div class="chat-tooltip" id="chat-tooltip" onclick="toggleMascotChat(); event.stopPropagation();"
                style="cursor: pointer;">
                💬 ¡Chatea conmigo!
            </div>
        </div>

        {{-- Hueso de Juguete --}}
        <div class="yarn-pro" onclick="playWithBone(); event.stopPropagation();" title="¡Juega conmigo!"></div>
    </div>
</div>
