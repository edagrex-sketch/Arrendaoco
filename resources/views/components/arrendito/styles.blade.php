<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

    :root {
        --coffee-dark: #3E2723;
        --coffee-mid: #5D4037;
        --coffee-light: #D7CCC8;
        --coffee-gold: #FFCA28;
        --ai-primary: #1a1a1a;
        --ai-secondary: #f5f5f5;
        --ai-accent: #4A90E2;
        --ai-success: #10B981;
    }

    /* --- 1. OVERLAY --- */
    #lock-overlay {
        position: fixed;
        inset: 0;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background-color: rgba(255, 255, 255, 0.98);
        transition: opacity 0.8s ease-out, visibility 0.8s;
        backdrop-filter: blur(10px);
        font-family: 'Inter', sans-serif;
    }

    .overlay-hidden {
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
    }

    /* --- 2. LLAVE FLOTANTE --- */
    #floating-key-container {
        position: fixed;
        top: 20%;
        right: 10%;
        z-index: 9050;
        pointer-events: none;
        opacity: 0;
        transition: opacity 0.5s, transform 1s cubic-bezier(0.25, 1, 0.5, 1);
    }

    .floating-key {
        width: 60px;
        height: 60px;
        fill: var(--coffee-gold);
        filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.3));
        animation: floatKey 3s ease-in-out infinite;
    }

    @keyframes floatKey {

        0%,
        100% {
            transform: translateY(0) rotate(0deg);
        }

        50% {
            transform: translateY(-15px) rotate(5deg);
        }
    }

    @keyframes rocoFloat {

        0%,
        100% {
            transform: translateY(0) scale(1);
        }

        50% {
            transform: translateY(-10px) scale(1.05);
        }
    }

    @keyframes rocoBreathe {

        0%,
        100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.02);
        }
    }

    @keyframes rocoWiggle {

        0%,
        100% {
            transform: rotate(0deg);
        }

        25% {
            transform: rotate(-2deg);
        }

        75% {
            transform: rotate(2deg);
        }
    }

    /* Estilo para ROCO animado */
    .roco-animated {
        width: 120px;
        height: auto;
        animation: rocoFloat 3s ease-in-out infinite,
            rocoBreathe 2s ease-in-out infinite,
            rocoWiggle 4s ease-in-out infinite;
        filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.15));
        transition: transform 0.3s ease;
    }

    .roco-animated:hover {
        transform: scale(1.1) rotate(5deg);
        filter: drop-shadow(0 6px 12px rgba(0, 0, 0, 0.25));
    }

    /* --- 3. ARRENDITO WRAPPER --- */
    #mascot-wrapper {
        position: fixed;
        bottom: 120px;
        left: 30px;
        z-index: 9999;
        transition: all 0.3s ease;
        width: 160px;
        pointer-events: none;
        font-family: 'Inter', sans-serif;
    }

    #mascot-wrapper:hover {
        transform: scale(1.05);
    }

    .mascot-scene {
        position: relative;
        width: 100%;
        height: 140px;
        pointer-events: auto;
    }

    /* Globo de Texto Profesional */
    .mascot-bubble {
        background: white;
        padding: 14px 20px;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 0, 0, 0.06);
        margin-bottom: -15px;
        margin-left: 35px;
        max-width: 240px;
        font-size: 0.875rem;
        color: var(--ai-primary);
        font-weight: 500;
        position: relative;
        z-index: 9001;
        pointer-events: auto;
        cursor: pointer;
        animation: bubblePopIn 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        transition: all 0.2s ease;
    }

    .mascot-bubble:hover {
        transform: translateX(-50%) scale(1.05);
        box-shadow: 0 12px 32px rgba(74, 144, 226, 0.3);
    }

    /* Tooltip de chat flotante */
    .chat-tooltip {
        position: absolute;
        bottom: -50px;
        left: 50%;
        transform: translateX(-50%);
        background: linear-gradient(135deg, var(--ai-accent) 0%, #357ABD 100%);
        color: white;
        padding: 10px 18px;
        border-radius: 24px;
        font-size: 13px;
        font-weight: 700;
        white-space: nowrap;
        box-shadow: 0 6px 20px rgba(74, 144, 226, 0.6);
        animation: pulseTooltip 2s ease-in-out infinite;
        z-index: 99999;
        pointer-events: none;
        font-family: 'Inter', sans-serif;
    }

    .msg-ai {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        color: var(--ai-primary);
        align-self: flex-start;
        max-width: 75%;
        border-radius: 18px 18px 18px 4px;
        padding: 14px 18px;
        margin-bottom: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        animation: msgSlideIn 0.3s ease-out;
        font-size: 0.9rem;
        line-height: 1.6;
        font-family: 'Inter', sans-serif;
    }

    .msg-ai b {
        color: var(--ai-accent);
        font-weight: 700;
    }

    .msg-ai br {
        display: block;
        content: "";
        margin: 8px 0;
    }

    .chat-tooltip::before {
        content: '';
        position: absolute;
        top: -8px;
        left: 50%;
        transform: translateX(-50%);
        width: 0;
        height: 0;
        border-left: 8px solid transparent;
        border-right: 8px solid transparent;
        border-bottom: 8px solid var(--ai-accent);
    }

    @keyframes floatBubble {

        0%,
        100% {
            transform: translateX(-50%) translateY(0);
        }

        50% {
            transform: translateX(-50%) translateY(-5px);
        }
    }

    @keyframes pulseTooltip {

        0%,
        100% {
            transform: translateX(-50%) scale(1);
            opacity: 1;
        }

        50% {
            transform: translateX(-50%) scale(1.05);
            opacity: 0.9;
        }
    }

    /* --- 4. MENÚ INTERACTIVO PROFESIONAL --- */
    #mascot-menu {
        position: fixed;
        bottom: 120px;
        left: 20px;
        width: 280px;
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        z-index: 9010;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        pointer-events: auto;
    }

    .hidden-menu {
        transform: scale(0.9) translateY(20px);
        opacity: 0;
        pointer-events: none;
    }

    .menu-header {
        padding: 16px 20px;
        background: linear-gradient(135deg, var(--ai-primary) 0%, #2d2d2d 100%);
        color: white;
        display: flex !important;
        flex-direction: row !important;
        justify-content: space-between !important;
        align-items: center !important;
    }

    .menu-header span {
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .menu-header button {
        background: rgba(255, 255, 255, 0.1);
        border: none;
        border-radius: 8px;
        padding: 6px;
        cursor: pointer;
        transition: background 0.2s;
    }

    .menu-header button:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    .menu-options {
        padding: 12px;
        display: flex !important;
        flex-direction: column !important;
        gap: 6px;
    }

    .menu-item {
        display: flex !important;
        flex-direction: row !important;
        align-items: center !important;
        gap: 14px;
        padding: 12px 16px;
        border-radius: 12px;
        text-decoration: none;
        color: var(--ai-primary) !important;
        transition: all 0.2s;
        border: none;
        background: transparent;
        width: 100%;
        text-align: left;
        cursor: pointer;
        font-family: 'Inter', sans-serif;
    }

    .menu-item:hover {
        background: var(--ai-secondary);
        transform: translateX(4px);
    }

    .item-icon {
        font-size: 1.25rem;
        flex-shrink: 0;
        width: 24px;
        text-align: center;
    }

    .item-text {
        font-size: 0.875rem;
        flex: 1;
        font-weight: 500;
    }

    .menu-footer {
        padding: 14px 20px;
        background: var(--ai-secondary);
        text-align: center;
        font-size: 0.75rem;
        color: #666;
        font-style: italic;
        border-top: 1px solid rgba(0, 0, 0, 0.05);
    }

    /* --- 5. VENTANA DE CHAT PROFESIONAL --- */
    #mascot-chat {
        position: fixed;
        bottom: 110px;
        left: 20px;
        width: 380px;
        height: 520px;
        background: white;
        border-radius: 24px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
        z-index: 9020;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        pointer-events: auto;
        font-family: 'Inter', sans-serif;
    }

    .hidden-chat {
        transform: scale(0.85) translateY(40px);
        opacity: 0;
        pointer-events: none;
    }

    .chat-header {
        padding: 18px 24px;
        background: linear-gradient(135deg, var(--ai-primary) 0%, #2d2d2d 100%);
        color: white;
        display: flex !important;
        flex-direction: row !important;
        justify-content: space-between !important;
        align-items: center !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .chat-header .flex {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .chat-header .w-8 {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--ai-accent) 0%, #357ABD 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .chat-header button {
        background: rgba(255, 255, 255, 0.1);
        border: none;
        border-radius: 8px;
        padding: 8px;
        cursor: pointer;
        transition: background 0.2s;
        color: white;
    }

    .chat-header button:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    #chat-mascot-name {
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 2px;
    }

    .chat-body {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 12px;
        background: #fafafa;
    }

    .chat-body::-webkit-scrollbar {
        width: 6px;
    }

    .chat-body::-webkit-scrollbar-thumb {
        background: #ddd;
        border-radius: 10px;
    }

    /* Burbujas de Chat Profesionales */
    .msg-ai,
    .msg-user {
        max-width: 75%;
        padding: 12px 16px;
        border-radius: 16px;
        font-size: 0.875rem;
        line-height: 1.5;
        animation: msgSlideIn 0.3s ease-out;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    .msg-ai {
        align-self: flex-start;
        background: white;
        color: var(--ai-primary);
        border: 1px solid rgba(0, 0, 0, 0.06);
        border-bottom-left-radius: 4px;
    }

    .msg-user {
        align-self: flex-end;
        background: linear-gradient(135deg, var(--ai-accent) 0%, #357ABD 100%);
        color: white;
        font-weight: 500;
        border-bottom-right-radius: 4px;
    }

    .msg-ai.typing {
        font-style: italic;
        opacity: 0.7;
    }

    .chat-footer {
        padding: 16px 20px;
        background: white;
        display: flex;
        gap: 10px;
        border-top: 1px solid rgba(0, 0, 0, 0.06);
        flex-direction: row;
        align-items: flex-end;
    }

    #chat-input {
        flex: 1;
        border: 1px solid rgba(0, 0, 0, 0.1);
        padding: 12px 18px;
        border-radius: 24px;
        font-size: 0.875rem;
        outline: none;
        transition: all 0.2s;
        font-family: 'Inter', sans-serif;
        background: var(--ai-secondary);
    }

    #chat-input:focus {
        border-color: var(--ai-accent);
        background: white;
        box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
    }

    .char-counter {
        font-size: 0.7rem;
        color: #999;
        text-align: right;
        margin-top: 4px;
        font-family: 'Inter', sans-serif;
    }

    /* Botones de respuesta rápida */
    .quick-replies {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 12px;
        padding: 0 4px;
    }

    .quick-reply-btn {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: 1px solid rgba(0, 0, 0, 0.08);
        padding: 8px 14px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
        color: var(--ai-primary);
        cursor: pointer;
        transition: all 0.2s;
        font-family: 'Inter', sans-serif;
        white-space: nowrap;
    }

    .quick-reply-btn:hover {
        background: linear-gradient(135deg, var(--ai-accent) 0%, #357ABD 100%);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(74, 144, 226, 0.3);
    }

    .quick-reply-btn:active {
        transform: translateY(0);
    }

    /* Indicador de escritura mejorado */
    .msg-ai.typing {
        font-style: normal;
        opacity: 0.8;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .msg-ai.typing::after {
        content: '';
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--ai-accent);
        animation: typingDot 1.4s infinite;
    }

    #chat-send-btn {
        background: linear-gradient(135deg, var(--ai-accent) 0%, #357ABD 100%);
        color: white;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(74, 144, 226, 0.3);
    }

    #chat-send-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 16px rgba(74, 144, 226, 0.4);
    }

    #chat-send-btn:active {
        transform: scale(0.95);
    }

    @keyframes msgSlideIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes bubblePopIn {
        from {
            opacity: 0;
            transform: scale(0.9) translateY(10px);
        }

        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    @keyframes typingDot {

        0%,
        60%,
        100% {
            transform: scale(1);
            opacity: 0.6;
        }

        30% {
            transform: scale(1.3);
            opacity: 1;
        }
    }

    /* Hueso de Juguete */
    .yarn-pro {
        position: absolute;
        bottom: 15px;
        right: 30px;
        width: 30px;
        height: 12px;
        background: linear-gradient(135deg, #f5e6d3 0%, #d4a574 100%);
        border-radius: 3px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        z-index: 9002;
        cursor: pointer;
        transition: transform 0.3s;
    }

    .yarn-pro::before,
    .yarn-pro::after {
        content: '';
        position: absolute;
        width: 8px;
        height: 8px;
        background: linear-gradient(135deg, #f5e6d3 0%, #d4a574 100%);
        border-radius: 50%;
        top: 50%;
        transform: translateY(-50%);
    }

    .yarn-pro::before {
        left: -4px;
    }

    .yarn-pro::after {
        right: -4px;
    }

    .yarn-pro:hover {
        transform: scale(1.15) rotate(15deg);
    }

    /* Móvil */
    @media (max-width: 768px) {

        #mascot-menu,
        #mascot-chat {
            width: calc(100vw - 40px);
            left: 20px;
            max-width: 380px;
        }

        #mascot-chat {
            height: 480px;
        }

        .mascot-bubble {
            max-width: 200px;
            font-size: 0.8rem;
        }
    }
</style>
