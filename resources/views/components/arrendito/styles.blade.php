<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

    :root {
        --roco-primary: #1F3A5F;
        --roco-accent: #2E5E8C;
        --roco-dark: #0F1B2D;
        --roco-light: #F5F1E8;
        --roco-gold: #F0B429;
        --roco-gradient-start: #1F3A5F;
        --roco-gradient-mid: #2E5E8C;
        --roco-gradient-end: #3B82F6;
        --roco-surface: #ffffff;
        --roco-surface-hover: #f8fafc;
        --roco-border: rgba(31, 58, 95, 0.08);
        --roco-shadow: rgba(31, 58, 95, 0.12);
        --roco-msg-ai-bg: #f0f4f8;
        --roco-msg-user-bg: linear-gradient(135deg, #1F3A5F 0%, #2E5E8C 100%);
        --roco-success: #10B981;
        --roco-radius: 20px;
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
        fill: var(--roco-gold);
        filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.3));
        animation: floatKey 3s ease-in-out infinite;
    }

    @keyframes floatKey {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-15px) rotate(5deg); }
    }

    @keyframes rocoFloat {
        0%, 100% { transform: translateY(0) scale(1); }
        50% { transform: translateY(-8px) scale(1.03); }
    }

    @keyframes rocoBreath {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.02); }
    }

    @keyframes shimmer {
        0% { background-position: -200% center; }
        100% { background-position: 200% center; }
    }

    @keyframes pulseRing {
        0% { transform: scale(1); opacity: 0.6; }
        100% { transform: scale(1.8); opacity: 0; }
    }

    @keyframes slideInChat {
        from {
            opacity: 0;
            transform: scale(0.92) translateY(20px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    @keyframes messageSlideIn {
        from {
            opacity: 0;
            transform: translateY(8px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes typingBounce {
        0%, 60%, 100% { transform: translateY(0); }
        30% { transform: translateY(-6px); }
    }

    @keyframes bubblePopIn {
        from {
            opacity: 0;
            transform: scale(0.85) translateY(12px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    @keyframes pulseTooltip {
        0%, 100% { transform: translateX(-50%) scale(1); box-shadow: 0 4px 15px rgba(31, 58, 95, 0.4); }
        50% { transform: translateX(-50%) scale(1.05); box-shadow: 0 6px 25px rgba(31, 58, 95, 0.6); }
    }

    @keyframes gradientFlow {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    /* --- 3. MASCOT WRAPPER --- */
    #mascot-wrapper {
        width: 160px;
        pointer-events: none;
        font-family: 'Inter', sans-serif;
    }

    #mascot-wrapper.hidden {
        transform: translateX(-200px) scale(0);
        opacity: 0;
        pointer-events: none;
    }

    .mascot-scene {
        position: relative;
        width: 100%;
        height: 140px;
        pointer-events: auto;
        animation: rocoBreath 4s ease-in-out infinite;
    }

    /* --- 4. BOTÓN MINIMIZAR --- */
    .minimize-assistant-btn {
        position: absolute;
        top: -12px;
        left: -12px;
        background: var(--roco-surface);
        border: 2px solid var(--roco-accent);
        border-radius: 50%;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 100;
        box-shadow: 0 4px 12px var(--roco-shadow);
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        color: var(--roco-accent);
        pointer-events: auto;
    }

    .minimize-assistant-btn:hover {
        background: var(--roco-accent);
        color: white;
        transform: scale(1.15) rotate(-90deg);
        box-shadow: 0 6px 20px rgba(46, 94, 140, 0.4);
    }

    /* --- 5. BOTÓN MAXIMIZAR (TOGGLE) --- */
    .assistant-toggle-btn {
        background: linear-gradient(135deg, var(--roco-primary) 0%, var(--roco-accent) 100%);
        border: none;
        padding: 12px 24px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 14px;
        color: white;
        box-shadow: 0 8px 30px rgba(31, 58, 95, 0.35);
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        display: flex;
        align-items: center;
        gap: 8px;
        letter-spacing: 0.3px;
        position: relative !important;
        left: auto !important;
        bottom: auto !important;
        margin: 0 auto;
    }

    .assistant-toggle-btn.hidden {
        transform: scale(0);
        opacity: 0;
        pointer-events: none;
    }

    .assistant-toggle-btn:hover {
        transform: scale(1.08);
        box-shadow: 0 12px 40px rgba(31, 58, 95, 0.5);
    }

    /* --- 6. BURBUJA DE TEXTO --- */
    .mascot-bubble {
        background: var(--roco-surface);
        padding: 14px 20px;
        border-radius: 18px;
        box-shadow: 0 4px 24px var(--roco-shadow), 0 0 0 1px var(--roco-border);
        margin-bottom: 5px;
        margin-left: 0;
        max-width: 240px;
        font-size: 0.85rem;
        color: var(--roco-dark);
        font-weight: 500;
        position: relative;
        z-index: 9001;
        pointer-events: auto;
        cursor: pointer;
        animation: bubblePopIn 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        transition: all 0.25s ease;
        line-height: 1.5;
    }

    .mascot-bubble:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 32px rgba(31, 58, 95, 0.18), 0 0 0 1px rgba(46, 94, 140, 0.15);
    }

    .mascot-bubble::after {
        content: '';
        position: absolute;
        bottom: -6px;
        left: 50%;
        width: 12px;
        height: 12px;
        background: var(--roco-surface);
        transform: translateX(-50%) rotate(45deg);
        box-shadow: 2px 2px 3px var(--roco-shadow);
    }

    .mascot-name-highlight {
        background: linear-gradient(135deg, var(--roco-primary), var(--roco-accent));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-weight: 800;
    }

    /* --- 7. TOOLTIP CHAT --- */
    .chat-tooltip {
        position: absolute;
        bottom: -50px;
        left: 50%;
        transform: translateX(-50%);
        background: linear-gradient(135deg, var(--roco-primary) 0%, var(--roco-accent) 100%);
        color: white;
        padding: 10px 20px;
        border-radius: 50px;
        font-size: 13px;
        font-weight: 700;
        white-space: nowrap;
        box-shadow: 0 6px 25px rgba(31, 58, 95, 0.5);
        animation: pulseTooltip 2.5s ease-in-out infinite;
        z-index: 99999;
        pointer-events: auto;
        cursor: pointer;
        font-family: 'Inter', sans-serif;
        letter-spacing: 0.3px;
    }

    .chat-tooltip::before {
        content: '';
        position: absolute;
        top: -5px;
        left: 50%;
        transform: translateX(-50%) rotate(45deg);
        width: 10px;
        height: 10px;
        background: var(--roco-primary);
    }

    /* --- 8. MENÚ DEL ASISTENTE --- */
    #mascot-menu {
        position: fixed;
        bottom: 120px;
        left: 20px;
        width: 300px;
        background: var(--roco-surface);
        border-radius: var(--roco-radius);
        box-shadow: 0 20px 60px rgba(31, 58, 95, 0.18), 0 0 0 1px var(--roco-border);
        z-index: 9010;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        pointer-events: auto;
        backdrop-filter: blur(20px);
    }

    .hidden-menu {
        transform: scale(0.9) translateY(20px);
        opacity: 0;
        pointer-events: none !important;
    }

    .menu-header {
        padding: 18px 22px;
        background: linear-gradient(135deg, var(--roco-gradient-start) 0%, var(--roco-gradient-mid) 50%, var(--roco-gradient-end) 100%);
        background-size: 200% 200%;
        animation: gradientFlow 6s ease infinite;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .menu-header span {
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1.5px;
    }

    .menu-options {
        padding: 10px;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .menu-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 13px 16px;
        border-radius: 14px;
        text-decoration: none;
        color: var(--roco-dark);
        transition: all 0.25s ease;
        border: none;
        background: transparent;
        width: 100%;
        text-align: left;
        cursor: pointer;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .menu-item:hover {
        background: linear-gradient(135deg, rgba(31, 58, 95, 0.06) 0%, rgba(46, 94, 140, 0.08) 100%);
        transform: translateX(4px);
    }

    .menu-item .item-icon {
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: linear-gradient(135deg, rgba(31, 58, 95, 0.08) 0%, rgba(46, 94, 140, 0.06) 100%);
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .menu-footer {
        padding: 14px 22px;
        text-align: center;
        font-size: 11px;
        color: #94a3b8;
        border-top: 1px solid var(--roco-border);
        font-style: italic;
        font-weight: 500;
    }

    /* --- 9. WIDGET CONTAINER --- */
    #roco-widget-container {
        position: fixed;
        bottom: 30px;
        left: 30px;
        z-index: 99998;
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 220px;
        pointer-events: none;
        transition: all 0.3s ease;
    }

    #roco-visual-group {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100%;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        pointer-events: auto;
        transform-origin: bottom;
    }

    #roco-mascot-wrapper {
        width: 140px;
        height: 120px;
        transition: all 0.3s ease;
    }

    #roco-lottie {
        width: 140px;
        height: 120px;
    }

    /* --- 10. VENTANA DE CHAT IA (PREMIUM) --- */
    #mascot-chat {
        position: fixed;
        bottom: 110px;
        left: 20px;
        width: 400px;
        height: 560px;
        background: var(--roco-surface);
        border-radius: var(--roco-radius);
        box-shadow:
            0 25px 80px rgba(31, 58, 95, 0.22),
            0 0 0 1px var(--roco-border),
            0 0 60px rgba(46, 94, 140, 0.06);
        z-index: 99999;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        pointer-events: auto;
    }

    .hidden-chat {
        transform: scale(0.85) translateY(40px);
        opacity: 0;
        pointer-events: none !important;
    }

    /* Header Premium con Aurora Effect */
    .chat-header {
        padding: 16px 20px;
        background: linear-gradient(135deg, var(--roco-gradient-start) 0%, var(--roco-gradient-mid) 50%, var(--roco-gradient-end) 100%);
        background-size: 200% 200%;
        animation: gradientFlow 8s ease infinite;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        overflow: hidden;
    }

    .chat-header::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.06) 0%, transparent 60%);
        animation: gradientFlow 6s ease infinite reverse;
        pointer-events: none;
    }

    .chat-header .flex {
        position: relative;
        z-index: 1;
    }

    /* Avatar en header */
    .chat-header .w-8.h-8 {
        background: rgba(255, 255, 255, 0.2) !important;
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255, 255, 255, 0.3);
        position: relative;
    }

    .chat-header .w-8.h-8::after {
        content: '';
        position: absolute;
        bottom: -1px;
        right: -1px;
        width: 10px;
        height: 10px;
        background: var(--roco-success);
        border-radius: 50%;
        border: 2px solid var(--roco-primary);
    }

    #chat-mascot-name {
        font-size: 14px;
        font-weight: 800;
        letter-spacing: 0.5px;
        color: white !important;
        text-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }

    /* --- 10. CUERPO DEL CHAT --- */
    .chat-body {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 14px;
        background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
        scroll-behavior: smooth;
    }

    /* Scrollbar Personalizado */
    .chat-body::-webkit-scrollbar {
        width: 5px;
    }

    .chat-body::-webkit-scrollbar-track {
        background: transparent;
    }

    .chat-body::-webkit-scrollbar-thumb {
        background: rgba(31, 58, 95, 0.15);
        border-radius: 10px;
    }

    .chat-body::-webkit-scrollbar-thumb:hover {
        background: rgba(31, 58, 95, 0.3);
    }

    /* --- 11. BURBUJAS DE MENSAJES --- */
    .msg-ai,
    .msg-user {
        max-width: 82%;
        padding: 12px 16px;
        border-radius: 18px;
        font-size: 0.875rem;
        line-height: 1.6;
        animation: messageSlideIn 0.3s ease-out;
        position: relative;
    }

    .msg-ai {
        align-self: flex-start;
        background: var(--roco-surface);
        color: var(--roco-dark);
        border: 1px solid var(--roco-border);
        border-bottom-left-radius: 6px;
        box-shadow: 0 2px 8px rgba(31, 58, 95, 0.06);
    }

    .msg-ai::before {
        content: '🐶';
        position: absolute;
        top: -8px;
        left: -4px;
        font-size: 14px;
        filter: drop-shadow(0 1px 2px rgba(0,0,0,0.15));
    }

    .msg-ai a {
        display: inline-block;
        margin-top: 8px;
        padding: 8px 16px;
        background: linear-gradient(135deg, var(--roco-primary) 0%, var(--roco-accent) 100%);
        color: white !important;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 700;
        font-size: 12px;
        transition: all 0.25s ease;
        box-shadow: 0 4px 12px rgba(31, 58, 95, 0.3);
    }

    .msg-ai a:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(31, 58, 95, 0.4);
    }

    .msg-user {
        align-self: flex-end;
        background: linear-gradient(135deg, var(--roco-primary) 0%, var(--roco-accent) 100%);
        color: white;
        border-bottom-right-radius: 6px;
        box-shadow: 0 4px 15px rgba(31, 58, 95, 0.2);
    }

    /* Typing Indicator Premium */
    .msg-ai.typing {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #94a3b8;
        font-style: italic;
        font-weight: 500;
        padding: 16px 20px;
    }

    .msg-ai.typing::after {
        content: '';
        display: inline-flex;
        width: 6px;
        height: 6px;
        background: var(--roco-accent);
        border-radius: 50%;
        animation: typingBounce 1.2s ease-in-out infinite;
        margin-left: 2px;
    }

    /* --- 12. RESPUESTAS RÁPIDAS --- */
    .quick-replies {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        padding: 4px 0;
        animation: messageSlideIn 0.4s ease-out;
    }

    .quick-reply-btn {
        background: var(--roco-surface);
        border: 1.5px solid rgba(31, 58, 95, 0.15);
        padding: 8px 16px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 600;
        color: var(--roco-primary);
        cursor: pointer;
        transition: all 0.25s ease;
        white-space: nowrap;
        font-family: 'Inter', sans-serif;
    }

    .quick-reply-btn:hover {
        background: linear-gradient(135deg, var(--roco-primary) 0%, var(--roco-accent) 100%);
        color: white;
        border-color: transparent;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(31, 58, 95, 0.25);
    }

    /* --- 13. FOOTER DEL CHAT --- */
    .chat-footer {
        padding: 14px 16px;
        background: var(--roco-surface);
        display: flex;
        gap: 10px;
        align-items: flex-end;
        border-top: 1px solid var(--roco-border);
        position: relative;
    }

    .chat-footer > div {
        flex: 1;
        position: relative;
    }

    #chat-input {
        width: 100%;
        border: 2px solid rgba(31, 58, 95, 0.1);
        padding: 12px 18px;
        padding-right: 55px;
        border-radius: 50px;
        font-size: 0.875rem;
        outline: none;
        background: #f8fafc;
        color: var(--roco-dark);
        font-family: 'Inter', sans-serif;
        font-weight: 500;
        transition: all 0.3s ease;
        box-sizing: border-box;
    }

    #chat-input::placeholder {
        color: #94a3b8;
        font-weight: 400;
    }

    #chat-input:focus {
        border-color: var(--roco-accent);
        background: white;
        box-shadow: 0 0 0 4px rgba(46, 94, 140, 0.1);
    }

    .char-counter {
        position: absolute;
        right: 16px;
        bottom: -18px;
        font-size: 10px;
        color: #94a3b8;
        font-weight: 500;
        font-family: 'Inter', sans-serif;
    }

    #chat-send-btn {
        background: linear-gradient(135deg, var(--roco-primary) 0%, var(--roco-accent) 100%);
        color: white;
        width: 46px;
        height: 46px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(31, 58, 95, 0.3);
        flex-shrink: 0;
    }

    #chat-send-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 8px 25px rgba(31, 58, 95, 0.45);
    }

    #chat-send-btn:active {
        transform: scale(0.95);
    }

    /* --- 14. HUESO DE JUGUETE --- */
    .yarn-pro {
        position: absolute;
        bottom: 15px;
        right: 30px;
        width: 28px;
        height: 11px;
        background: linear-gradient(135deg, #f5e6d3 0%, #d4a574 100%);
        border-radius: 3px;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
        z-index: 9002;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .yarn-pro:hover {
        transform: rotate(15deg) scale(1.2);
    }

    .yarn-pro::before,
    .yarn-pro::after {
        content: '';
        position: absolute;
        width: 8px;
        height: 8px;
        background: inherit;
        border-radius: 50%;
        top: 50%;
        transform: translateY(-50%);
    }

    .yarn-pro::before { left: -4px; }
    .yarn-pro::after { right: -4px; }

    /* --- 15. POWERED BY BADGE --- */
    .roco-powered-by {
        text-align: center;
        padding: 6px 16px 10px;
        font-size: 10px;
        color: #b0b8c8;
        font-weight: 500;
        background: var(--roco-surface);
        letter-spacing: 0.3px;
    }

    .roco-powered-by span {
        background: linear-gradient(135deg, var(--roco-primary), var(--roco-accent));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-weight: 700;
    }

    /* --- 16. RESPONSIVE --- */

    /* === Tablets y móviles grandes (≤768px) === */
    @media (max-width: 768px) {
        #roco-widget-container {
            bottom: 20px;
            left: auto;
            right: 20px;
            width: 180px;
            transform-origin: bottom right;
        }

        #mascot-chat {
            bottom: 20px;
            left: 20px;
            width: calc(100vw - 40px);
            height: calc(100vh - 120px);
        }

        #roco-mascot-wrapper {
            width: 100px !important;
            height: 90px !important;
        }

        #roco-lottie {
            width: 100px !important;
            height: 90px !important;
        }

        .mascot-scene {
            height: 80px;
        }

        /* Burbuja compacta que no tape contenido */
        .mascot-bubble {
            max-width: 140px;
            font-size: 0.7rem;
            padding: 8px 12px;
            margin-left: 0;
            margin-bottom: -10px;
            border-radius: 14px;
            line-height: 1.4;
        }

        .mascot-bubble::after {
            left: 20px;
            width: 8px;
            height: 8px;
        }

        /* Tooltip más pequeño */
        .chat-tooltip {
            font-size: 11px;
            padding: 6px 14px;
            bottom: -35px;
        }

        .chat-tooltip::before {
            width: 7px;
            height: 7px;
        }

        /* Botón minimizar más pequeño */
        .minimize-assistant-btn {
            width: 28px;
            height: 28px;
            top: -8px;
            left: -8px;
        }

        .minimize-assistant-btn svg {
            width: 12px;
            height: 12px;
        }

        /* Hueso más pequeño */
        .yarn-pro {
            width: 20px;
            height: 8px;
            right: 15px;
            bottom: 10px;
        }

        .yarn-pro::before,
        .yarn-pro::after {
            width: 6px;
            height: 6px;
        }

        .yarn-pro::before { left: -3px; }
        .yarn-pro::after { right: -3px; }

        /* Botón maximizar reposicionado */
        .assistant-toggle-btn {
            bottom: 20px;
            left: auto;
            right: 12px;
            padding: 10px 18px;
            font-size: 12px;
        }

        /* Menú en pantalla completa con margen */
        #mascot-menu {
            width: calc(100vw - 32px);
            left: 16px;
            bottom: 110px;
            border-radius: 18px;
        }

        .menu-header {
            padding: 14px 18px;
        }

        .menu-header span {
            font-size: 11px;
            letter-spacing: 1px;
        }

        .menu-item {
            padding: 10px 14px;
            font-size: 0.82rem;
            gap: 10px;
        }

        .menu-item .item-icon {
            width: 32px;
            height: 32px;
            border-radius: 10px;
            font-size: 1rem;
        }

        .menu-footer {
            padding: 10px 16px;
            font-size: 10px;
        }

        /* Chat ocupa casi toda la pantalla */
        #mascot-chat {
            width: calc(100vw - 16px);
            left: 8px;
            bottom: 8px;
            height: calc(100vh - 70px);
            height: calc(100dvh - 70px);
            border-radius: 20px;
        }

        .chat-header {
            padding: 12px 14px;
        }

        .chat-header .w-8.h-8 {
            width: 28px !important;
            height: 28px !important;
        }

        #chat-mascot-name {
            font-size: 13px;
        }

        .chat-body {
            padding: 14px;
            gap: 10px;
        }

        .msg-ai, .msg-user {
            max-width: 88%;
            font-size: 0.82rem;
            padding: 10px 14px;
        }

        .quick-replies {
            gap: 6px;
        }

        .quick-reply-btn {
            font-size: 11px;
            padding: 6px 12px;
        }

        .chat-footer {
            padding: 10px 12px;
        }

        #chat-input {
            padding: 10px 14px;
            padding-right: 45px;
            font-size: 0.82rem;
        }

        #chat-send-btn {
            width: 40px;
            height: 40px;
        }

        .roco-powered-by {
            font-size: 9px;
            padding: 4px 12px 8px;
        }
    }

    /* === Móviles medianos (≤480px) === */
    @media (max-width: 480px) {
        #roco-widget-container {
            bottom: 15px;
            right: 10px;
            width: 150px;
        }

        #roco-mascot-wrapper {
            width: 85px !important;
            height: 75px !important;
        }

        #roco-lottie {
            width: 85px !important;
            height: 75px !important;
        }

        .mascot-bubble {
            max-width: 120px;
            font-size: 0.65rem;
            padding: 6px 10px;
        }

        .assistant-toggle-btn {
            padding: 8px 14px;
            font-size: 11px;
            gap: 6px;
        }

        #mascot-chat {
            width: calc(100vw - 20px);
            left: 10px;
            bottom: 10px;
            height: calc(100vh - 80px);
            height: calc(100dvh - 80px);
            border-radius: 20px;
        }
    }

    /* === Móviles muy pequeños (≤380px) === */
    @media (max-width: 380px) {
        #roco-widget-container {
            bottom: 10px;
            right: 5px;
            width: 130px;
        }

        #roco-mascot-wrapper {
            width: 75px !important;
            height: 65px !important;
        }

        #roco-lottie {
            width: 75px !important;
            height: 65px !important;
        }

        .mascot-bubble {
            max-width: 100px;
            font-size: 0.6rem;
            padding: 5px 8px;
        }

        #mascot-chat {
            width: 100vw;
            left: 0;
            bottom: 0;
            height: 100vh;
            height: 100dvh;
            border-radius: 0;
        }

        .assistant-toggle-btn {
            padding: 8px 12px;
            font-size: 10px;
        }
    }
</style>
