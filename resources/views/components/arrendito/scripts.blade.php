<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Migración forzada de nombre antiguo a nuevo
        const oldName = localStorage.getItem('arrenditoName');
        const currentName = localStorage.getItem('rocoName');

        // Si existe el nombre antiguo, migrarlo
        if (oldName) {
            localStorage.setItem('rocoName', oldName);
            localStorage.removeItem('arrenditoName');
        }

        // Si el nombre actual es "Arrendito", cambiarlo a "ROCO"
        if (currentName === 'Arrendito') {
            localStorage.setItem('rocoName', 'ROCO');
        }

        // 1. Sincronización con Base de Datos o LocalStorage
        @auth
        fetch("{{ route('arrendito.name') }}")
            .then(response => response.json())
            .then(data => {
                if (data.nombre) {
                    const finalName = data.nombre === 'Arrendito' ? 'ROCO' : data.nombre;
                    localStorage.setItem('rocoName', finalName);
                    updateMascotNameUI(finalName);
                }
            })
            .catch(err => console.error('Error al cargar nombre de ROCO:', err));
    @else
        const savedName = localStorage.getItem('rocoName') || 'ROCO';
        updateMascotNameUI(savedName);
    @endauth

    // 2. Lógica del Overlay (Carga)
    const overlay = document.getElementById('lock-overlay');
    if (overlay) {
        @if (auth()->check())
            @if (session('login_success'))
                setTimeout(() => {
                    overlay.classList.add('overlay-hidden');
                }, 3000);
            @else
                overlay.style.display = 'none';
            @endif
        @else
            setTimeout(() => {
                overlay.classList.add('overlay-hidden');
            }, 3000);
        @endif
    }

    // 3. Restaurar estado de visibilidad de ROCO
    const rocoHidden = localStorage.getItem('rocoHidden') === 'true';
    if (rocoHidden) {
        const wrapper = document.getElementById('mascot-wrapper');
        const btn = document.getElementById('maximize-assistant-btn');
        if (wrapper) wrapper.classList.add('hidden');
        if (btn) btn.classList.remove('hidden');
    }

    // 4. Sistema de Mensajes Contextuales
    setTimeout(() => {
        const currentPath = window.location.pathname;
        const mascotName = localStorage.getItem('rocoName') || 'ROCO';
        let message = "";

        if (currentPath === '/inicio') {
            message = `¡Guau! Soy <b>${mascotName}</b>. ¿Buscamos una casa con jardín hoy? 🏡🐶`;
        } else if (currentPath.includes('/inmuebles/')) {
            message = "¡Qué lugar tan acogedor! 🐶 ¿Exploramos juntos este hogar?";
        } else if (currentPath === '/favoritos') {
            message = "¡Tus favoritos son geniales! Los guardo como mis huesos favoritos. 🦴❤️";
        } else if (currentPath === '/perfil') {
            message = "¡Hola amigo! Actualiza tus datos para conocerte mejor. 🐾";
        } else {
            message = `¡Guau! Soy <b>${mascotName}</b>, tu compañero leal. ¿En qué te ayudo?`;
        }
        triggerMascotMessage(message);
    }, 2000);
    });

    function triggerMascotMessage(htmlContent) {
        const bubble = document.querySelector('.mascot-bubble');
        if (bubble) {
            bubble.style.opacity = '0';
            setTimeout(() => {
                const cleanContent = htmlContent.split('<br>')[0];
                bubble.innerHTML =
                    `${cleanContent} <br><span style="font-weight:400; color:#888; font-size:10px;">¡Haz clic para hablar!</span>`;
                bubble.style.opacity = '1';
            }, 300);
        }
    }

    function toggleMascotMenu() {
        const menu = document.getElementById('mascot-menu');
        const chat = document.getElementById('mascot-chat');
        if (menu) menu.classList.toggle('hidden-menu');
        if (chat && !chat.classList.contains('hidden-chat')) chat.classList.add('hidden-chat');
    }

    function toggleMascotChat() {
        const chat = document.getElementById('mascot-chat');
        const menu = document.getElementById('mascot-menu');
        if (chat) chat.classList.toggle('hidden-chat');
        if (menu && !menu.classList.contains('hidden-menu')) menu.classList.add('hidden-menu');

        if (chat && !chat.classList.contains('hidden-chat')) {
            document.getElementById('chat-input').focus();
        }
    }

    /**
     * Alterna la visibilidad total del asistente (Minimizar/Maximizar)
     */
    function toggleMascotVisibility() {
        const wrapper = document.getElementById('mascot-wrapper');
        const btn = document.getElementById('maximize-assistant-btn');
        const isHidden = wrapper.classList.toggle('hidden');

        if (btn) {
            if (isHidden) btn.classList.remove('hidden');
            else btn.classList.add('hidden');
        }

        localStorage.setItem('rocoHidden', isHidden);
    }

    function sendMascotMessage() {
        const input = document.getElementById('chat-input');
        const container = document.getElementById('chat-messages');
        const message = input.value.trim();

        if (!message) return;

        const quickReplies = document.getElementById('quick-replies');
        if (quickReplies) quickReplies.style.display = 'none';

        const userMsgDiv = document.createElement('div');
        userMsgDiv.className = 'msg-user';
        userMsgDiv.textContent = message;
        container.appendChild(userMsgDiv);

        input.value = '';
        updateCharCounter();
        container.scrollTop = container.scrollHeight;

        const loadingDiv = document.createElement('div');
        loadingDiv.className = 'msg-ai typing';
        loadingDiv.textContent = 'ROCO está pensando';
        container.appendChild(loadingDiv);
        container.scrollTop = container.scrollHeight;

        fetch("{{ route('arrendito.chat') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    message: message
                })
            })
            .then(response => response.json())
            .then(data => {
                container.removeChild(loadingDiv);
                const aiMsgDiv = document.createElement('div');
                aiMsgDiv.className = 'msg-ai';

                if (data.success) {
                    aiMsgDiv.innerHTML = data.response;
                } else {
                    aiMsgDiv.innerHTML = "⚠️ " + data.response;
                }

                container.appendChild(aiMsgDiv);
                container.scrollTop = container.scrollHeight;
                rotatePlaceholder();
            })
            .catch(err => {
                container.removeChild(loadingDiv);
                console.error('Error detallado:', err);
                const errorDiv = document.createElement('div');
                errorDiv.className = 'msg-ai';
                errorDiv.innerHTML =
                    '¡Guau! 🐾 Tuve un pequeño problema al procesar tu mensaje. ¿Podrías intentar de nuevo?';
                container.appendChild(errorDiv);
            });
    }

    function sendQuickReply(message) {
        const input = document.getElementById('chat-input');
        input.value = message;
        sendMascotMessage();
    }

    function updateCharCounter() {
        const input = document.getElementById('chat-input');
        const counter = document.getElementById('char-counter');
        if (input && counter) {
            const length = input.value.length;
            counter.textContent = `${length}/500`;
            counter.style.color = length > 450 ? '#e74c3c' : '#999';
        }
    }

    function handleChatKeyPress(event) {
        if (event.key === 'Enter' && !event.shiftKey) {
            event.preventDefault();
            sendMascotMessage();
        }
        if (event.key === 'Escape') {
            toggleMascotChat();
        }
    }

    const placeholders = [
        "Pregúntame sobre inmuebles...",
        "¿Buscas algo en particular?",
        "¿Cuál es tu presupuesto?",
        "¿Qué zona te interesa?",
        "Cuéntame qué necesitas 🏠"
    ];
    let placeholderIndex = 0;

    function rotatePlaceholder() {
        const input = document.getElementById('chat-input');
        if (input) {
            placeholderIndex = (placeholderIndex + 1) % placeholders.length;
            input.placeholder = placeholders[placeholderIndex];
        }
    }

    setInterval(rotatePlaceholder, 5000);

    function renameMascot() {
        const currentName = localStorage.getItem('rocoName') || 'ROCO';
        Swal.fire({
            title: '¡Ponle nombre a tu compañero Beagle!',
            input: 'text',
            inputValue: currentName,
            showCancelButton: true,
            confirmButtonColor: '#4A90E2',
            inputValidator: (value) => {
                if (!value) return '¡Tu perrito necesita un nombre!';
            }
        }).then((result) => {
                if (result.isConfirmed) {
                    const newName = result.value;
                    localStorage.setItem('rocoName', newName);
                    updateMascotNameUI(newName);
                    @auth
                    fetch("{{ route('arrendito.update') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            nombre: newName
                        })
                    });
                @endauth
            }
        });
    }

    function updateMascotNameUI(name) {
        const nameDisplay = document.getElementById('mascot-name-display');
        const chatTitle = document.getElementById('chat-mascot-name');
        if (nameDisplay) nameDisplay.innerText = name;
        if (chatTitle) chatTitle.innerText = name + ' AI';
    }

    function startMiniGuide() {
        toggleMascotMenu();
        Swal.fire({
            title: '🐶 Guía Rápida',
            text: 'ROCO te ayuda a encontrar el lugar ideal. Puedes filtrar por precio, zona y tipo de propiedad. ¡Vamos a encontrar tu hogar perfecto!',
            icon: 'info',
            confirmButtonColor: '#4A90E2'
        });
    }

    function playWithBone() {
        const bone = document.querySelector('.yarn-pro');
        if (bone) {
            bone.style.transform = "scale(1.4) rotate(360deg)";
            setTimeout(() => {
                bone.style.transform = "";
            }, 300);
        }
    }
</script>
