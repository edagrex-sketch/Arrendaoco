<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Inmuebles</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #003049; color: white; }
        h1 { color: #003049; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #555; }
    </style>
</head>
<body>
    <h1>Reporte de Propiedades - ArrendaOco</h1>
    <p>Fecha de generación: {{ date('d/m/Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Tipo</th>
                <th>Precio</th>
                <th>Propietario</th>
                <th>Estatus</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inmuebles as $inmueble)
            <tr>
                <td>{{ $inmueble->id }}</td>
                <td>{{ $inmueble->titulo }}</td>
                <td>{{ ucfirst($inmueble->tipo) }}</td>
                <td>${{ number_format($inmueble->renta_mensual) }}</td>
                <td>
                    {{ $inmueble->propietario->nombre ?? 'N/A' }} <br>
                    <small>{{ $inmueble->propietario->email ?? '' }}</small>
                </td>
                <td>{{ ucfirst($inmueble->estatus) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Generado por ArrendaOco System
    </div>
</body>
</html>
