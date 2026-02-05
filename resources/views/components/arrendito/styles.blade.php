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

    /* --- 3. ARRENDITO WRAPPER --- */
    #mascot-wrapper {
        position: fixed;
        bottom: 120px;
        left: 30px;
        z-index: 9999;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
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
    }

    /* Botón Minimizar - GRANDE Y CLICABLE SIN BLOQUEAR */
    .minimize-assistant-btn {
        position: absolute;
        top: -15px;
        left: -15px;
        background: white;
        border: 2px solid var(--ai-accent);
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 100;
        /* Suficiente para estar arriba pero no bloquea todo el wrapper */
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        color: var(--ai-accent);
        pointer-events: auto;
        /* Aseguramos que sea clicable */
    }

    .minimize-assistant-btn:hover {
        background: #f8f9fa;
        transform: scale(1.1);
    }

    /* Botón Maximizar (Toggle) */
    .assistant-toggle-btn {
        position: fixed;
        bottom: 30px;
        left: 30px;
        background: white;
        border: 1px solid var(--ai-accent);
        padding: 10px 20px;
        border-radius: 30px;
        font-weight: 700;
        font-size: 14px;
        color: var(--ai-accent);
        box-shadow: 0 4px 15px rgba(74, 144, 226, 0.2);
        z-index: 9999;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .assistant-toggle-btn.hidden {
        transform: scale(0);
        opacity: 0;
        pointer-events: none;
    }

    .assistant-toggle-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 20px rgba(74, 144, 226, 0.3);
    }

    /* Resto de estilos del asistente... */
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
        pointer-events: auto;
        cursor: pointer;
        font-family: 'Inter', sans-serif;
    }

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
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .menu-options {
        padding: 12px;
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .menu-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 12px 16px;
        border-radius: 12px;
        text-decoration: none;
        color: var(--ai-primary);
        transition: all 0.2s;
        border: none;
        background: transparent;
        width: 100%;
        text-align: left;
        cursor: pointer;
    }

    .menu-item:hover {
        background: var(--ai-secondary);
        transform: translateX(4px);
    }

    #mascot-chat {
        position: fixed;
        bottom: 110px;
        left: 20px;
        width: 380px;
        height: 520px;
        background: white;
        border-radius: 24px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
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
        pointer-events: none;
    }

    .chat-header {
        padding: 18px 24px;
        background: linear-gradient(135deg, var(--ai-primary) 0%, #2d2d2d 100%);
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
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

    .msg-ai,
    .msg-user {
        max-width: 75%;
        padding: 12px 16px;
        border-radius: 16px;
        font-size: 0.875rem;
        line-height: 1.5;
    }

    .msg-ai {
        align-self: flex-start;
        background: white;
        border: 1px solid rgba(0, 0, 0, 0.06);
        border-bottom-left-radius: 4px;
    }

    .msg-user {
        align-self: flex-end;
        background: var(--ai-accent);
        color: white;
        border-bottom-right-radius: 4px;
    }

    .chat-footer {
        padding: 16px 20px;
        background: white;
        display: flex;
        gap: 10px;
        border-top: 1px solid rgba(0, 0, 0, 0.06);
    }

    #chat-input {
        flex: 1;
        border: 1px solid rgba(0, 0, 0, 0.1);
        padding: 12px 18px;
        border-radius: 24px;
        font-size: 0.875rem;
        outline: none;
        background: var(--ai-secondary);
    }

    #chat-send-btn {
        background: var(--ai-accent);
        color: white;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        cursor: pointer;
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
        background: inherit;
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

    @media (max-width: 768px) {

        #mascot-menu,
        #mascot-chat {
            width: calc(100vw - 40px);
            left: 20px;
        }

        .mascot-bubble {
            max-width: 200px;
            font-size: 0.8rem;
        }
    }
</style>
