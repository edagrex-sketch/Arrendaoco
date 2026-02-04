<style>
    :root {
        --coffee-dark: #3E2723;
        --coffee-mid: #5D4037;
        --coffee-light: #D7CCC8;
        --coffee-gold: #FFCA28;
    }

    /* --- 1. OVERLAY (PANTALLA DE CARGA/BLOQUEO) --- */
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
        z-index: 9050; /* Por encima de la mascota */
        pointer-events: none;
        opacity: 0;
        transition: opacity 0.5s, transform 1s cubic-bezier(0.25, 1, 0.5, 1);
    }

    .floating-key {
        width: 60px;
        height: 60px;
        fill: var(--coffee-gold);
        filter: drop-shadow(0 4px 6px rgba(0,0,0,0.3));
        animation: floatKey 3s ease-in-out infinite;
    }

    @keyframes floatKey {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-15px) rotate(5deg); }
    }

    /* --- 3. TARJETAS DE PROPIEDADES (BLOQUEO) --- */
    .blur-content { filter: blur(4px); transition: filter 0.3s ease; }
    
    .card-lock-overlay {
        position: absolute;
        inset: 0;
        background: rgba(255,255,255,0.4);
        backdrop-filter: blur(2px);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .group:hover .card-lock-overlay { opacity: 1; }
    .group:hover .blur-content { filter: blur(6px); }

    .lock-icon-hover {
        font-size: 2rem;
        color: var(--coffee-dark);
        animation: shakeLock 2s infinite;
    }

    @keyframes shakeLock {
        0%, 100% { transform: rotate(0deg); }
        10%, 30%, 50%, 70%, 90% { transform: rotate(-10deg); }
        20%, 40%, 60%, 80% { transform: rotate(10deg); }
    }

    .input-locked {
        background-color: #f3f4f6;
        cursor: not-allowed;
        opacity: 0.8;
        position: relative;
        overflow: hidden;
    }

    /* --- 4. ARRENDITO: CSS BLINDADO --- */
    #mascot-wrapper {
        position: fixed;
        bottom: 20px; 
        left: 20px;
        z-index: 9000;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        width: 160px; 
        height: auto;
        pointer-events: none;
        transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }


    #mascot-wrapper:hover {
        transform: translateY(-5px) scale(1.02);
    }

    .mascot-scene {
        position: relative;
        width: 100%;
        height: 140px; 
        pointer-events: auto;
    }

    lottie-player#arrendito-player {
        width: 100% !important;
        height: 100% !important;
    }

    /* Globo de Texto */
    .mascot-bubble {
        background: rgba(255, 255, 255, 0.98);
        padding: 12px 18px;
        border-radius: 18px 18px 18px 0;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        border: 1px solid #e5e7eb;
        margin-bottom: -15px; 
        margin-left: 35px;
        max-width: 200px;
        font-size: 0.85rem;
        color: var(--coffee-dark);
        font-weight: 600;
        position: relative;
        z-index: 9001; 
        opacity: 0;
        transform: translateY(10px);
        pointer-events: auto;
        cursor: pointer;
        animation: bubblePopIn 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        animation-delay: 1s;
    }

    .mascot-bubble::after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 0;
        border-left: 12px solid white;
        border-top: 12px solid transparent;
        border-bottom: 0;
    }

    .mascot-name-highlight { 
        color: #d97706; 
        font-weight: 800;
        text-shadow: 0 0 10px rgba(217, 119, 6, 0.1);
    }

    /* Estambre (Física Simulada) */
    .yarn-pro {
        position: absolute;
        bottom: 15px;
        right: 30px;
        width: 28px;
        height: 28px;
        background: radial-gradient(circle at 30% 30%, #ff6b6b, #c0392b);
        border-radius: 50%;
        box-shadow: 2px 4px 10px rgba(0,0,0,0.2), inset -2px -2px 5px rgba(0,0,0,0.3);
        z-index: 9002;
        animation: yarnBounce 4s infinite ease-in-out;
        cursor: pointer;
        pointer-events: auto;
    }
    
    .yarn-pro::before {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: 50%;
        background: repeating-linear-gradient(45deg, transparent, transparent 2px, rgba(0,0,0,0.1) 2px, rgba(0,0,0,0.1) 4px);
        opacity: 0.6;
        animation: yarnSpin 5s infinite linear;
    }

    @keyframes bubblePopIn { 
        to { 
            opacity: 1; 
            transform: translateY(0); 
        } 
    }
    
    @keyframes yarnBounce { 
        0%, 100% { transform: translate(0, 0) rotate(0deg); } 
        25% { transform: translate(-15px, -5px) rotate(-10deg); }
        50% { transform: translate(-5px, 0) scale(0.95) rotate(5deg); }
        75% { transform: translate(-12px, -3px) rotate(-5deg); }
    }
    
    @keyframes yarnSpin { 
        0% { transform: rotate(0deg); } 
        100% { transform: rotate(-360deg); } 
    }

    /* Móvil: Ajuste de tamaño */
    @media (max-width: 768px) {
        #mascot-wrapper { width: 120px; bottom: 15px; left: 15px; }
        .mascot-scene { height: 110px; }
        .mascot-bubble { font-size: 0.75rem; margin-left: 15px; max-width: 150px; padding: 8px 12px; }
        .yarn-pro { width: 20px; height: 20px; bottom: 12px; right: 25px; }
    }
</style>
