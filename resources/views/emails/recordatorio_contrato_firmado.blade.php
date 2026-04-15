<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contrato pendiente — ArrendaOco</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #F5F1E8; color: #003049; }
        .wrapper { max-width: 600px; margin: 32px auto; background: #ffffff; border-radius: 24px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,48,73,0.08); }
        .header { background: #003049; padding: 32px 40px; text-align: center; }
        .header img { height: 40px; margin-bottom: 12px; }
        .header h1 { color: #FDF0D5; font-size: 22px; font-weight: 800; letter-spacing: -0.5px; }
        .header p { color: #669BBC; font-size: 12px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; margin-top: 4px; }
        .alert-bar { background: #FDF0D5; border-left: 4px solid #003049; padding: 16px 40px; }
        .alert-bar p { font-size: 13px; font-weight: 700; color: #003049; }
        .body { padding: 40px; }
        .greeting { font-size: 16px; font-weight: 600; margin-bottom: 16px; }
        .body p { font-size: 14px; line-height: 1.7; color: #475569; margin-bottom: 16px; }
        .highlight { color: #003049; font-weight: 700; }
        .info-card { background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 16px; padding: 20px 24px; margin: 24px 0; }
        .info-card p { margin: 0; font-size: 13px; color: #64748B; }
        .info-card p + p { margin-top: 8px; }
        .info-card .label { font-weight: 800; text-transform: uppercase; font-size: 10px; letter-spacing: 1.5px; color: #669BBC; }
        .info-card .value { font-size: 15px; font-weight: 700; color: #003049; margin-top: 2px; }
        .btn-wrap { text-align: center; margin: 32px 0; }
        .btn { display: inline-block; background: #003049; color: #ffffff !important; text-decoration: none; font-weight: 800; font-size: 14px; padding: 16px 36px; border-radius: 14px; letter-spacing: 0.3px; }
        .steps { background: #FDF0D5; border-radius: 16px; padding: 20px 24px; margin: 24px 0; }
        .steps h3 { font-size: 13px; font-weight: 800; color: #003049; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 14px; }
        .step { display: flex; align-items: flex-start; gap: 12px; margin-bottom: 10px; }
        .step-num { min-width: 24px; height: 24px; background: #003049; color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 900; }
        .step p { font-size: 13px; color: #475569; margin: 0; line-height: 1.5; padding-top: 3px; }
        .footer { background: #F5F1E8; padding: 24px 40px; text-align: center; border-top: 1px solid #E2E8F0; }
        .footer p { font-size: 11px; color: #94A3B8; line-height: 1.8; }
        .footer a { color: #669BBC; text-decoration: none; font-weight: 700; }
    </style>
</head>
<body>
<div class="wrapper">

    {{-- Header --}}
    <div class="header">
        <h1>ArrendaOco</h1>
        <p>Plataforma de arrendamiento</p>
    </div>

    {{-- Alerta --}}
    <div class="alert-bar">
        <p>⚠️ Tienes un contrato pendiente de formalizar desde hace <strong>{{ $diasEsperando }} día(s)</strong>.</p>
    </div>

    {{-- Cuerpo --}}
    <div class="body">
        <p class="greeting">Hola, <span class="highlight">{{ $propietario->nombre }}</span></p>

        <p>
            <strong>{{ $nombreInquilino }}</strong> descargó el contrato de arrendamiento para
            <strong>{{ $tituloInmueble }}</strong> hace {{ $diasEsperando }} día(s). Para activar
            el arrendamiento, solo necesitas subir el escaneo o fotografía del contrato
            con ambas firmas.
        </p>

        {{-- Tarjeta de info --}}
        <div class="info-card">
            <p class="label">Propiedad</p>
            <p class="value">{{ $tituloInmueble }}</p>
            <p class="label" style="margin-top:12px">Inquilino</p>
            <p class="value">{{ $nombreInquilino }}</p>
            <p class="label" style="margin-top:12px">PDF descargado el</p>
            <p class="value">{{ \Carbon\Carbon::parse($contrato->pdf_descargado_at)->format('d \d\e F \d\e Y') }}</p>
        </div>

        {{-- CTA --}}
        <div class="btn-wrap">
            <a href="{{ $enlaceSubir }}" class="btn">
                Subir Contrato Firmado →
            </a>
        </div>

        {{-- Pasos --}}
        <div class="steps">
            <h3>¿Cómo activar el arrendamiento?</h3>
            <div class="step">
                <div class="step-num">1</div>
                <p>Reúnete con el inquilino y firmen las <strong>dos copias impresas</strong> del contrato.</p>
            </div>
            <div class="step">
                <div class="step-num">2</div>
                <p>Toma una foto clara o escanea el contrato con ambas firmas.</p>
            </div>
            <div class="step">
                <div class="step-num">3</div>
                <p>Sube el archivo haciendo clic en el botón de arriba (PDF, JPG, PNG — máx. 10 MB).</p>
            </div>
            <div class="step">
                <div class="step-num">4</div>
                <p>¡Listo! El arrendamiento quedará activo y la propiedad se marcará como rentada.</p>
            </div>
        </div>

        <p style="font-size:13px; color:#94A3B8;">
            Si ya realizaste este trámite, puedes ignorar este correo.
            Si tienes dudas, contáctanos en
            <a href="mailto:arrendaoco@gmail.com" style="color:#669BBC;font-weight:700;">arrendaoco@gmail.com</a>.
        </p>
    </div>

    {{-- Footer --}}
    <div class="footer">
        <p>
            © {{ date('Y') }} <strong>ArrendaOco</strong> · Ocosingo, Chiapas.<br>
            Este correo fue generado automáticamente. Por favor no respondas a él.<br>
            <a href="{{ url('/') }}">Ir a ArrendaOco</a>
        </p>
    </div>

</div>
</body>
</html>
