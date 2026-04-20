<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operación Cancelada | ArrendaOco</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .cancel-gradient {
            background: linear-gradient(135deg, #FF416C 0%, #FF4B2B 100%);
        }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-6">
    <div class="max-w-md w-full bg-white rounded-[2rem] shadow-2xl overflow-hidden p-8 text-center border border-slate-100">
        <div class="mb-6 inline-flex items-center justify-center w-24 h-24 cancel-gradient rounded-full shadow-lg">
            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </div>
        
        <h1 class="text-3xl font-black text-slate-800 mb-4">Operación Cancelada</h1>
        <p class="text-slate-500 mb-8 leading-relaxed">
            Has cancelado el proceso de pago. No se ha realizado ningún cargo a tu cuenta.
        </p>

        <a href="arrendaoco://cancel" class="block w-full cancel-gradient text-white text-center font-bold py-4 rounded-2xl shadow-lg hover:opacity-90 transition-all active:scale-95">
            VOLVER A LA APP
        </a>

        <p class="mt-6 text-slate-400 text-xs">Redireccionando automáticamente...</p>
    </div>

    <script>
        setTimeout(() => {
            window.location.href = "arrendaoco://cancel";
        }, 2000);
    </script>
</body>
</html>
