<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Reseñas — ArrendaOco</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 10px; color: #333; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #003049; padding-bottom: 15px; position: relative; }
        .logo { width: 60px; height: auto; margin-bottom: 10px; }
        .header h1 { color: #003049; margin: 0; font-size: 20px; text-transform: uppercase; letter-spacing: 1px; }
        .header p { margin: 8px 0 0; color: #666; font-size: 11px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; border: 1px solid #e2e8f0; }
        th { background-color: #003049; color: white; padding: 10px 8px; text-align: left; text-transform: uppercase; font-weight: bold; font-size: 9px; }
        td { padding: 10px 8px; border-bottom: 1px solid #e2e8f0; vertical-align: top; }
        
        .stars { color: #facc15; font-size: 14px; }
        .rating-text { font-size: 8px; color: #64748b; font-weight: bold; }
        
        .comment { font-style: italic; color: #4b5563; line-height: 1.4; }
        .date { color: #64748b; font-size: 8px; }
        
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; color: #94a3b8; font-size: 8px; padding: 15px 0; border-top: 1px solid #f1f5f9; }
        .page-number:after { content: counter(page); }
    </style>
</head>
<body>
    <div class="header">
        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('logo1.png'))) }}" class="logo" alt="Logo">
        <h1>Reporte de Reseñas y Comentarios</h1>
        <p>Sistema ArrendaOco — Generado el: {{ date('d/m/Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="10%">Fecha</th>
                <th width="20%">Usuario</th>
                <th width="20%">Propiedad</th>
                <th width="15%">Calificación</th>
                <th width="35%">Comentario</th>
            </tr>
        </thead>
        <tbody>
            @foreach($resenas as $resena)
            <tr>
                <td class="date">{{ $resena->created_at->format('d/m/Y') }}</td>
                <td style="font-weight: bold;">{{ $resena->usuario->nombre }}</td>
                <td>{{ $resena->inmueble->titulo }}</td>
                <td>
                    <div class="stars">
                        @for($i=0; $i<$resena->puntuacion; $i++)★@endfor
                        @for($i=$resena->puntuacion; $i<5; $i++)☆@endfor
                    </div>
                    <div class="rating-text">{{ $resena->puntuacion }} / 5</div>
                </td>
                <td class="comment">"{{ $resena->comentario }}"</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        © {{ date('Y') }} ArrendaOco — Panel de Administración • Todos los derechos reservados • Página <span class="page-number"></span>
    </div>
</body>
</html>
