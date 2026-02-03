<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Usuarios</title>
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
    <h1>Reporte de Usuarios - ArrendaOco</h1>
    <p>Fecha de generación: {{ date('d/m/Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Roles</th>
                <th>Estatus</th>
            </tr>
        </thead>
        <tbody>
            @foreach($usuarios as $usuario)
            <tr>
                <td>{{ $usuario->id }}</td>
                <td>{{ $usuario->nombre }}</td>
                <td>{{ $usuario->email }}</td>
                <td>
                    @foreach($usuario->roles as $role)
                        {{ $role->etiqueta }}{{ !$loop->last ? ', ' : '' }}
                    @endforeach
                </td>
                <td>{{ ucfirst($usuario->estatus) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Generado por ArrendaOco System
    </div>
</body>
</html>
