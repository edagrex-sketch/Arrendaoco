{{-- HTML DEL OVERLAY (CASA) --}}
<div id="lock-overlay">
    <div class="w-64 h-64 relative">
        <lottie-player
            id="lottie-house"
            src="https://assets3.lottiefiles.com/packages/lf20_yr6zz3wv.json"
            background="transparent"
            speed="1"
            style="width: 100%; height: 100%;"
            loop
            autoplay
            renderer="svg"
        ></lottie-player>
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
        <path d="M70 25 C70 15 80 5 90 5 C95 5 100 10 100 25 C100 40 95 45 90 45 C80 45 70 35 70 25 Z M70 20 L10 20 L10 30 L70 30 Z M30 30 L30 40 L40 40 L40 30 Z M20 30 L20 45 L10 45 L10 30 Z" />
    </svg>
    <div id="key-tooltip" class="absolute top-full mt-2 right-0 bg-[#3E2723] text-white text-xs px-2 py-1 rounded shadow-lg opacity-0 transition-opacity whitespace-nowrap">
        ¡Regístrate para usarme!
    </div>
</div>

<div id="mascot-wrapper">

    {{-- Globo de Texto Interactivo --}}
    <div class="mascot-bubble" onclick="renameMascot()">
        Soy <span id="mascot-name-display" class="mascot-name-highlight">Arrendito</span>.
        <br><span style="font-weight:400; color:#888; font-size:10px;">¡Ponme nombre!</span>
    </div>

    {{-- Escena de la Mascarota --}}
    <div class="mascot-scene">
        <div onclick="renameMascot()" style="width:100%; height:100%; cursor: pointer;" title="¡Hazme clic!">
            <lottie-player
                id="arrendito-player"
                src="https://assets4.lottiefiles.com/packages/lf20_syqnfe7c.json"
                background="transparent"
                speed="1"
                loop
                autoplay
                renderer="svg">
            </lottie-player>
        </div>
        
        {{-- Bolita de Estambre (Física CSS) --}}
        <div class="yarn-pro" onclick="playWithYarn()" title="¡Juega conmigo!"></div>
    </div>
</div>
