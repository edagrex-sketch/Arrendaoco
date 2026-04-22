<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regresando a ArrendaOco</title>
    <style>
        body { font-family: sans-serif; display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100vh; margin: 0; background-color: #f5f7fa; color: #1a2a4e; text-align: center; }
        .card { background: white; padding: 40px; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); max-width: 90%; }
        .icon { font-size: 50px; margin-bottom: 20px; }
        h1 { font-size: 24px; margin-bottom: 10px; }
        p { color: #64748b; margin-bottom: 30px; }
        .btn { background: #1a2a4e; color: white; padding: 15px 30px; border-radius: 12px; text-decoration: none; font-weight: bold; display: inline-block; }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon">✅</div>
        <h1>¡Registro completado!</h1>
        <p>Estamos redirigiéndote de vuelta a la aplicación móvil para que puedas continuar con tu publicación.</p>
        <a href="arrendaoco://verify-stripe" class="btn">Regresar a la aplicación</a>
    </div>

    <script>
        // Intentar redirección automática después de 2 segundos
        setTimeout(function() {
            window.location.href = "arrendaoco://verify-stripe";
        }, 2000);
    </script>
</body>
</html>
