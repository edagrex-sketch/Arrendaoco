<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago Exitoso | ArrendaOco</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .stunning-gradient {
            background: linear-gradient(135deg, #6A11CB 0%, #2575FC 100%);
        }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-6">
    <div class="max-w-md w-full bg-white rounded-[2rem] shadow-2xl overflow-hidden p-8 text-center border border-slate-100">
        <div class="mb-6 inline-flex items-center justify-center w-24 h-24 stunning-gradient rounded-full shadow-lg">
            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        
        <h1 class="text-3xl font-black text-slate-800 mb-4">{{ $mensaje ?? '¡Pago Exitoso!' }}</h1>
        <p class="text-slate-500 mb-8 leading-relaxed">
            {{ $subtitulo ?? 'Tu operación se ha completado correctamente.' }}
        </p>

        <div class="bg-slate-50 rounded-2xl p-4 mb-8">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Estatus del trámite</p>
            <p class="text-blue-600 font-bold">Pendiente de aprobación</p>
            <p class="text-[10px] text-slate-400 mt-2">Redireccionando a la app en <span id="countdown">3</span>s...</p>
        </div>

        <a href="arrendaoco://success" class="block w-full stunning-gradient text-white text-center font-bold py-4 rounded-2xl shadow-lg hover:opacity-90 transition-all active:scale-95">
            VOLVER A LA APP
        </a>

        <p class="mt-6 text-slate-400 text-xs">Si no eres redirigido automáticamente, presiona el botón.</p>
    </div>

    <script>
        let count = 3;
        const countdownEl = document.getElementById('countdown');
        
        const timer = setInterval(() => {
            count--;
            if (countdownEl) countdownEl.innerText = count;
            if (count <= 0) {
                clearInterval(timer);
                window.location.href = "arrendaoco://success";
            }
        }, 1000);

        // Intento inmediato
        setTimeout(() => {
            window.location.href = "arrendaoco://success";
        }, 3500);
    </script>
</body>
</html>
