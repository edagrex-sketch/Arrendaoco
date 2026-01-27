<!DOCTYPE html>
<html lang="es">
<body>
    <p>Hola,</p>

    <p>
        Se adjunta su <strong>estado de cuenta</strong> correspondiente al
        <strong>{{ $estadoCuenta->mes }}/{{ $estadoCuenta->anio }}</strong>.
    </p>

    <p>
        Contrato #{{ $estadoCuenta->contrato_id }}
    </p>

    <p>
        Gracias por usar <strong>ArrendaOco</strong>.
    </p>

    <p style="font-size:12px;color:#666;">
        Este correo fue generado automáticamente.
    </p>
</body>
</html>
