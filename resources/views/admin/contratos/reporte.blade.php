<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Contratos — ArrendaOco</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 10px; color: #333; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #003049; padding-bottom: 15px; position: relative; }
        .logo { width: 60px; height: auto; margin-bottom: 10px; }
        .header h1 { color: #003049; margin: 0; font-size: 20px; text-transform: uppercase; letter-spacing: 1px; }
        .header p { margin: 8px 0 0; color: #666; font-size: 11px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; border: 1px solid #e2e8f0; }
        th { background-color: #003049; color: white; padding: 10px 8px; text-align: left; text-transform: uppercase; font-weight: bold; font-size: 9px; }
        td { padding: 10px 8px; border-bottom: 1px solid #e2e8f0; vertical-align: middle; }
        
        .price { font-weight: bold; color: #003049; }
        .status-badge { display: inline-block; padding: 2px 6px; border-radius: 4px; font-weight: bold; font-size: 8px; text-transform: uppercase; }
        
        .date-range { font-size: 9px; color: #64748b; }
        
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; color: #94a3b8; font-size: 8px; padding: 15px 0; border-top: 1px solid #f1f5f9; }
        .page-number:after { content: counter(page); }
    </style>
</head>
<body>
    <div class="header">
        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('logo1.png'))) }}" class="logo" alt="Logo">
        <h1>Reporte Detallado de Contratos</h1>
        <p>Sistema ArrendaOco — Generado el: {{ date('d/m/Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="20%">Propiedad</th>
                <th width="20%">Propietario</th>
                <th width="20%">Inquilino</th>
                <th width="15%">Vigencia</th>
                <th width="10%">Monto Renta</th>
                <th width="15%">Estatus</th>
            </tr>
        </thead>
        <tbody>
            @foreach($contratos as $contrato)
            <tr>
                <td style="font-weight: bold;">{{ $contrato->inmueble->titulo }}</td>
                <td>{{ $contrato->propietario->nombre }}</td>
                <td>{{ $contrato->inquilino->nombre }}</td>
                <td class="date-range">
                    {{ $contrato->fecha_inicio->format('d/m/Y') }}<br>al {{ $contrato->fecha_fin->format('d/m/Y') }}
                </td>
                <td class="price">${{ number_format($contrato->renta_mensual, 2) }}</td>
                <td>
                    <span class="status-badge">
                        {{ str_replace('_', ' ', ucfirst($contrato->estatus)) }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        © {{ date('Y') }} ArrendaOco — Panel de Administración • Todos los derechos reservados • Página <span class="page-number"></span>
    </div>
</body>
</html>
