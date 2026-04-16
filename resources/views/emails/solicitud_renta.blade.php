<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Solicitud de Renta</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f5f1e8; margin: 0; padding: 20px; color: #333; }
        .card { background: #fff; border-radius: 12px; max-width: 580px; margin: 0 auto; padding: 40px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        .header { background: #003049; color: white; border-radius: 8px; padding: 24px; text-align: center; margin-bottom: 28px; }
        .header h1 { margin: 0; font-size: 22px; }
        .pill { display: inline-block; background: #fdf0d5; color: #003049; border-radius: 20px; padding: 4px 14px; font-size: 12px; font-weight: bold; }
        .data-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #f0f0f0; }
        .data-row span:first-child { color: #669BBC; font-size: 12px; font-weight: bold; text-transform: uppercase; }
        .data-row span:last-child { font-weight: bold; color: #003049; }
        .btn { display: block; text-align: center; background: #003049; color: white; text-decoration: none; padding: 14px 28px; border-radius: 8px; font-weight: bold; margin-top: 28px; }
        .footer { text-align: center; font-size: 11px; color: #aaa; margin-top: 24px; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <div style="font-size: 36px; margin-bottom: 8px;">🏠</div>
            <h1>Nueva Solicitud de Renta</h1>
            <p style="margin: 4px 0 0; opacity: 0.85; font-size: 14px;">Tienes 24 horas para responder</p>
        </div>

        <p>Hola <strong>{{ optional($contrato->inmueble->propietario)->nombre ?? 'Propietario' }}</strong>,</p>
        <p>Un inquilino ha enviado una solicitud de renta para tu propiedad. Los fondos ya han sido pre-autorizados y están en espera de tu confirmación.</p>

        <div style="margin: 24px 0;">
            <div class="data-row">
                <span>Propiedad</span>
                <span>{{ optional($contrato->inmueble)->titulo }}</span>
            </div>
            <div class="data-row">
                <span>Inquilino</span>
                <span>{{ optional($contrato->inquilino)->nombre }}</span>
            </div>
            <div class="data-row">
                <span>Fecha Inicio</span>
                <span>{{ \Carbon\Carbon::parse($contrato->fecha_inicio)->translatedFormat('d \d\e F, Y') }}</span>
            </div>
            <div class="data-row">
                <span>Plazo</span>
                <span>{{ $contrato->plazo }}</span>
            </div>
            <div class="data-row">
                <span>Renta Mensual</span>
                <span>${{ number_format($contrato->renta_mensual, 2) }} MXN</span>
            </div>
            <div class="data-row" style="border-bottom: none;">
                <span>Depósito</span>
                <span>${{ number_format($contrato->deposito ?? 0, 2) }} MXN</span>
            </div>
        </div>

        <a href="{{ route('contratos.revision', $contrato->id) }}" class="btn">
            Revisar y Responder Solicitud →
        </a>

        <p class="footer">
            Si no puedes hacer clic en el botón, copia este enlace en tu navegador:<br>
            {{ route('contratos.revision', $contrato->id) }}<br><br>
            © {{ date('Y') }} ArrendaOco. Todos los derechos reservados.
        </p>
    </div>
</body>
</html>
