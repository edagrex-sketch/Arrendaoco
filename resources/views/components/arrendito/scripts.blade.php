<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Migración forzada de nombre antiguo a nuevo
        const oldName = localStorage.getItem('arrenditoName');
        const currentName = localStorage.getItem('rocoName');

        if (oldName) {
            localStorage.setItem('rocoName', oldName);
            localStorage.removeItem('arrenditoName');
        }

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
                @if (session('logged_out'))
                    setTimeout(() => {
                        overlay.classList.add('overlay-hidden');
                    }, 3000);
                @else
                    setTimeout(() => {
                        overlay.classList.add('overlay-hidden');
                    }, 3000);
                @endif
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
                message = `¡Hola! Soy <b>${mascotName}</b>. ¿Buscamos una casa con jardín hoy?`;
            } else if (currentPath.includes('/inmuebles/')) {
                message = "¡Qué lugar tan acogedor! ¿Exploramos juntos este hogar?";
            } else if (currentPath === '/favoritos') {
                message = "¡Tus favoritos son geniales! Los guardo con cuidado.";
            } else if (currentPath === '/perfil') {
                message = "¡Hola amigo! Actualiza tus datos para conocerte mejor.";
            } else {
                message = `¡Hola! Soy <b>${mascotName}</b>, tu compañero leal. ¿En qué te ayudo?`;
            }
            triggerMascotMessage(message);
        }, 2000);
    });

    function triggerMascotMessage(htmlContent) {
        const bubble = document.querySelector('.mascot-bubble');
        if (bubble) {
            bubble.style.opacity = '0';
            bubble.style.transform = 'translateY(5px)';
            setTimeout(() => {
                const cleanContent = htmlContent.split('<br>')[0];
                bubble.innerHTML =
                    `${cleanContent} <br><span style="font-weight:400; color:#94a3b8; font-size:10px;">¡Haz clic para hablar!</span>`;
                bubble.style.opacity = '1';
                bubble.style.transform = 'translateY(0)';
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
            setTimeout(() => {
                document.getElementById('chat-input').focus();
            }, 300);
        }
    }

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

        processMascotMessage(message);
    }

    let currentInmuebleContext = null;

    function processMascotMessage(message, inmuebleId = null) {
        const input = document.getElementById('chat-input');
        const container = document.getElementById('chat-messages');
        
        // Si se pasa un inmuebleId, lo guardamos para esta sesión
        if (inmuebleId) {
            currentInmuebleContext = inmuebleId;
        }

        // Ocultar quick replies
        const quickReplies = document.getElementById('quick-replies');
        if (quickReplies) quickReplies.style.display = 'none';

        // Mensaje del usuario
        const userMsgDiv = document.createElement('div');
        userMsgDiv.className = 'msg-user';
        userMsgDiv.textContent = message;
        container.appendChild(userMsgDiv);

        input.value = '';
        updateCharCounter();
        container.scrollTop = container.scrollHeight;

        // Indicador de typing mejorado
        const loadingDiv = document.createElement('div');
        loadingDiv.className = 'msg-ai typing';
        loadingDiv.innerHTML = '<span>ROCO está pensando</span>';
        container.appendChild(loadingDiv);
        container.scrollTop = container.scrollHeight;

        // Deshabilitar input mientras procesa
        input.disabled = true;
        document.getElementById('chat-send-btn').disabled = true;

        fetch("{{ route('arrendito.chat') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    message: message,
                    inmueble_id: currentInmuebleContext
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
                    aiMsgDiv.innerHTML = data.response;
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
                    'Lo siento, tuve un problema al procesar tu mensaje. ¿Podrías intentar de nuevo?';
                container.appendChild(errorDiv);
            })
            .finally(() => {
                // Re-habilitar input
                input.disabled = false;
                document.getElementById('chat-send-btn').disabled = false;
                input.focus();
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
            if (length > 450) {
                counter.style.color = '#ef4444';
                counter.style.fontWeight = '700';
            } else if (length > 350) {
                counter.style.color = '#f59e0b';
                counter.style.fontWeight = '600';
            } else {
                counter.style.color = '#94a3b8';
                counter.style.fontWeight = '500';
            }
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
        "Cuéntame qué necesitas.",
        "¿Cerca de la UTS?",
        "¿Casa, depa o cuarto?"
    ];
    let placeholderIndex = 0;

    function rotatePlaceholder() {
        const input = document.getElementById('chat-input');
        if (input && !input.disabled) {
            placeholderIndex = (placeholderIndex + 1) % placeholders.length;
            input.style.transition = 'opacity 0.2s';
            input.style.opacity = '0.5';
            setTimeout(() => {
                input.placeholder = placeholders[placeholderIndex];
                input.style.opacity = '1';
            }, 200);
        }
    }

    setInterval(rotatePlaceholder, 5000);

    function renameMascot() {
        const currentName = localStorage.getItem('rocoName') || 'ROCO';
        Swal.fire({
            title: '¡Ponle nombre a tu asistente!',
            text: 'Tu asistente te acompañará en toda tu búsqueda',
            input: 'text',
            inputValue: currentName,
            showCancelButton: true,
            confirmButtonText: '¡Listo!',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#1F3A5F',
            cancelButtonColor: '#94a3b8',
            inputValidator: (value) => {
                if (!value) return '¡Tu perrito necesita un nombre!';
                if (value.length > 20) return 'El nombre es muy largo (máx. 20 caracteres)';
            },
            customClass: {
                popup: 'rounded-2xl',
                input: 'rounded-xl',
            }
        }).then((result) => {
                if (result.isConfirmed) {
                    const newName = result.value;
                    localStorage.setItem('rocoName', newName);
                    updateMascotNameUI(newName);

                    Swal.fire({
                        title: '¡Listo!',
                        text: `Ahora me llamo ${newName}.`,
                        icon: 'success',
                        confirmButtonColor: '#1F3A5F',
                        timer: 2500,
                        showConfirmButton: false,
                    });

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
            title: 'Guía Rápida de ArrendaOco',
            html: `
                <div style="text-align: left; font-size: 14px; line-height: 1.8; color: #334155;">
                    <p><b>1.</b> Explora los inmuebles disponibles</p>
                    <p><b>2.</b> Guarda tus favoritos</p>
                    <p><b>3.</b> Pregúntame por zona, precio o tipo</p>
                    <p><b>4.</b> Contacta al propietario</p>
                    <p><b>5.</b> ¡Renta tu nuevo hogar!</p>
                </div>
            `,
            icon: 'info',
            confirmButtonColor: '#1F3A5F',
            confirmButtonText: '¡Entendido!',
            customClass: {
                popup: 'rounded-2xl',
            }
        });
    }

    function playWithBone() {
        const bone = document.querySelector('.yarn-pro');
        if (bone) {
            bone.style.transform = "scale(1.5) rotate(360deg)";
            bone.style.transition = "all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275)";
            setTimeout(() => {
                bone.style.transform = "";
            }, 500);
        }
    }

    // Función global para abrir ROCO con contexto
    window.openRocoWithContext = function(inmuebleId, initialMessage) {
        const chat = document.getElementById('mascot-chat');
        if (chat && chat.classList.contains('hidden-chat')) {
            toggleMascotChat();
        }
        
        // Guardar contexto
        currentInmuebleContext = inmuebleId;
        
        // Enviar mensaje inicial
        if (initialMessage) {
            const input = document.getElementById('chat-input');
            input.value = initialMessage;
            sendMascotMessage();
        }
    };
</script>
