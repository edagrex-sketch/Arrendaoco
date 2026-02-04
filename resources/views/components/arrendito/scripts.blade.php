
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Sincronización con Base de Datos o LocalStorage
        @auth
            fetch("{{ route('arrendito.name') }}")
                .then(response => response.json())
                .then(data => {
                    if (data.nombre) {
                        localStorage.setItem('arrenditoName', data.nombre);
                        updateMascotNameUI(data.nombre);
                    }
                })
                .catch(err => console.error('Error al cargar nombre de Arrendito:', err));
        @else
            const savedName = localStorage.getItem('arrenditoName') || 'Arrendito';
            updateMascotNameUI(savedName);
        @endauth

        // 2. Lógica del Overlay (Carga)
        const overlay = document.getElementById('lock-overlay');
        if(overlay) {
            @if(auth()->check())
                @if(session('login_success'))
                    setTimeout(() => { overlay.classList.add('overlay-hidden'); }, 4000);
                @else
                    overlay.style.display = 'none';
                @endif
            @else
                setTimeout(() => { overlay.classList.add('overlay-hidden'); }, 4000); 
            @endif
        }
    });

    /**
     * Gamificación: Llave flotante que persigue el cursor al interactuar con bloqueos
     */
    function triggerGamification(element) {
        const keyContainer = document.getElementById('floating-key-container');
        const keyTooltip = document.getElementById('key-tooltip');
        if(!keyContainer) return;

        const elementRect = element.getBoundingClientRect();

        keyContainer.style.opacity = '1';
        keyContainer.style.top = (elementRect.top - 20) + 'px';
        keyContainer.style.left = (elementRect.left + (elementRect.width / 2)) + 'px';
        keyContainer.style.transform = "scale(1.2)";

        if(keyTooltip) keyTooltip.style.opacity = '1';

        setTimeout(() => {
            mostrarAlertaRegistro();
            setTimeout(() => {
                keyContainer.style.opacity = '0';
                if(keyTooltip) keyTooltip.style.opacity = '0';
            }, 2000);
        }, 600);
    }

    /**
     * Alerta persuasiva para registro
     */
    function mostrarAlertaRegistro() {
        Swal.fire({
            title: '¡Desbloquea tu Hogar!',
            text: "Esta llave abre todas las puertas. Regístrate para ver detalles y contactar.",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3E2723',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Obtener Llave (Login)',
            cancelButtonText: 'Solo mirar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "{{ route('login') }}";
            }
        })
    }

    /**
     * Actualiza la interfaz del globo de texto con el nombre actual
     */
    function updateMascotNameUI(name) {
        const nameDisplay = document.getElementById('mascot-name-display');
        const bubble = document.querySelector('.mascot-bubble');
        
        if (nameDisplay) {
            nameDisplay.innerText = name;
        }
        
        if (bubble) {
            bubble.innerHTML = `¡Aquí estoy, <span class="mascot-name-highlight">${name}</span> tu compañero en esta búsqueda! 😺 <br><span style="font-weight:400; color:#888; font-size:10px;">(Haz clic para renombrarme)</span>`;
        }
    }

    /**
     * Función para renombrar la mascota mediante un SweetAlert interactivo
     */
    function renameMascot() {
        const currentName = localStorage.getItem('arrenditoName') || 'Arrendito';

        Swal.fire({
            title: '¡Ponle nombre a tu compañero!',
            text: 'Este gatito te acompañará en tu búsqueda de hogar.',
            input: 'text',
            inputValue: currentName,
            inputPlaceholder: 'Ej: Pelusa, Michi, Bigotes...',
            imageUrl: 'https://media.giphy.com/media/v1.Y2lkPTc5MGI3NjExbDN4eTM1bjN5eW15eW15eW15eW15eW15eW15eW15eW15eW15/MDJ9iboxlC6tJp1A9c/giphy.gif',
            imageWidth: 200,
            imageHeight: 150,
            imageAlt: 'Gatito esperando nombre',
            showCancelButton: true,
            confirmButtonColor: '#5D4037',
            cancelButtonColor: '#d33',
            confirmButtonText: '¡Guardar Nombre!',
            cancelButtonText: 'Cancelar',
            inputValidator: (value) => {
                if (!value) return '¡El gatito necesita un nombre!';
                if (value.length > 15) return '¡Ese nombre es muy largo!';
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const newName = result.value;
                localStorage.setItem('arrenditoName', newName);
                updateMascotNameUI(newName);

                // Guardar en Base de Datos si está autenticado
                @auth
                    fetch("{{ route('arrendito.update') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ nombre: newName })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            console.log('Nombre de Arrendito sincronizado');
                        }
                    })
                    .catch(err => console.error('Error al sincronizar nombre:', err));
                @endauth
                
                Swal.fire({
                    title: `¡Miau! 😻`,
                    text: `Me encanta mi nuevo nombre: ${newName}`,
                    icon: 'success',
                    confirmButtonColor: '#5D4037',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        });
    }

    /**
     * Reacción visual del estambre al hacerle clic
     */
    function playWithYarn() {
        const yarn = document.querySelector('.yarn-pro');
        if(yarn) {
            yarn.style.transform = "scale(1.4) rotate(360deg)";
            yarn.style.transition = "transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275)";
            setTimeout(() => {
                yarn.style.transform = "";
            }, 300);
        }
    }
</script>
