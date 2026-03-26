<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $accion === 'aprobada' ? '¡Renta Aprobada!' : 'Solicitud Rechazada' }}</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f5f1e8; margin: 0; padding: 20px; color: #333; }
        .card { background: #fff; border-radius: 12px; max-width: 580px; margin: 0 auto; padding: 40px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        .header-aprobada { background: #10b981; color: white; border-radius: 8px; padding: 24px; text-align: center; margin-bottom: 28px; }
        .header-rechazada { background: #ef4444; color: white; border-radius: 8px; padding: 24px; text-align: center; margin-bottom: 28px; }
        .header h1 { margin: 0; font-size: 22px; }
        .data-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #f0f0f0; }
        .data-row span:first-child { color: #669BBC; font-size: 12px; font-weight: bold; text-transform: uppercase; }
        .data-row span:last-child { font-weight: bold; color: #003049; }
        .btn { display: block; text-align: center; background: #003049; color: white; text-decoration: none; padding: 14px 28px; border-radius: 8px; font-weight: bold; margin-top: 28px; }
        .footer { text-align: center; font-size: 11px; color: #aaa; margin-top: 24px; }
    </style>
</head>
<body>
    <div class="card">
        @if($accion === 'aprobada')
        <div class="header-aprobada">
            <div style="font-size: 36px; margin-bottom: 8px;">✅</div>
            <h1>¡Tu renta fue aprobada!</h1>
            <p style="margin: 4px 0 0; opacity: 0.85; font-size: 14px;">El propietario ha aceptado tu solicitud</p>
        </div>
        <p>Hola <strong>{{ optional($contrato->inquilino)->nombre ?? 'Inquilino' }}</strong>,</p>
        <p>¡Excelentes noticias! El propietario ha <strong>aprobado</strong> tu solicitud de renta. El pago ha sido procesado y tu contrato ya está disponible para descargar.</p>
        @else
        <div class="header-rechazada">
            <div style="font-size: 36px; margin-bottom: 8px;">❌</div>
            <h1>Solicitud Rechazada</h1>
            <p style="margin: 4px 0 0; opacity: 0.85; font-size: 14px;">El propietario ha declinado tu solicitud</p>
        </div>
        <p>Hola <strong>{{ optional($contrato->inquilino)->nombre ?? 'Inquilino' }}</strong>,</p>
        <p>Lamentamos informarte que el propietario ha <strong>rechazado</strong> tu solicitud de renta. Tus fondos serán liberados de forma inmediata sin ningún cargo adicional.</p>
        @endif

        <div style="margin: 24px 0;">
            <div class="data-row">
                <span>Propiedad</span>
                <span>{{ optional($contrato->inmueble)->titulo }}</span>
            </div>
            <div class="data-row">
                <span>Fecha Inicio</span>
                <span>{{ \Carbon\Carbon::parse($contrato->fecha_inicio)->translatedFormat('d \d\e F, Y') }}</span>
            </div>
            <div class="data-row" style="border-bottom: none;">
                <span>Renta Mensual</span>
                <span>${{ number_format($contrato->renta_mensual, 2) }} MXN</span>
            </div>
        </div>

        @if($accion === 'aprobada')
        <a href="{{ route('inmuebles.mis_rentas') }}" class="btn">Ver Mi Renta y Descargar Contrato →</a>
        @else
        <a href="{{ route('inicio') }}" class="btn">Buscar Otra Propiedad →</a>
        @endif

        <p class="footer">© {{ date('Y') }} ArrendaOco. Todos los derechos reservados.</p>
    </div>
</body>
</html>
