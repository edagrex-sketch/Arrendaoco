<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Usuarios — ArrendaOco</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 10px; color: #333; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #003049; padding-bottom: 15px; position: relative; }
        .logo { width: 60px; height: auto; margin-bottom: 10px; }
        .header h1 { color: #003049; margin: 0; font-size: 20px; text-transform: uppercase; letter-spacing: 1px; }
        .header p { margin: 8px 0 0; color: #666; font-size: 11px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; border: 1px solid #e2e8f0; }
        th { background-color: #003049; color: white; padding: 10px 8px; text-align: left; text-transform: uppercase; font-weight: bold; font-size: 9px; }
        td { padding: 10px 8px; border-bottom: 1px solid #e2e8f0; vertical-align: middle; }
        
        .role-badge { display: inline-block; padding: 2px 6px; border-radius: 4px; background-color: #f1f5f9; color: #475569; font-weight: bold; font-size: 8px; margin-right: 2px; }
        .status-active { color: #16a34a; font-weight: bold; }
        .status-inactive { color: #dc2626; font-weight: bold; }
        
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; color: #94a3b8; font-size: 8px; padding: 15px 0; border-top: 1px solid #f1f5f9; }
        .page-number:after { content: counter(page); }
    </style>
</head>
<body>
    <div class="header">
        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('logo1.png'))) }}" class="logo" alt="Logo">
        <h1>Reporte de Usuarios Registrados</h1>
        <p>Sistema ArrendaOco — Generado el: {{ date('d/m/Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="25%">Nombre Completo</th>
                <th width="30%">Correo Electrónico</th>
                <th width="25%">Roles del Sistema</th>
                <th width="10%">Estatus</th>
                <th width="10%">Registro</th>
            </tr>
        </thead>
        <tbody>
            @foreach($usuarios as $usuario)
            <tr>
                <td style="font-weight: bold;">{{ $usuario->nombre }}</td>
                <td>{{ $usuario->email }}</td>
                <td>
                    @foreach($usuario->roles as $role)
                        <span class="role-badge">{{ $role->etiqueta ?? ucfirst($role->nombre) }}</span>
                    @endforeach
                </td>
                <td class="{{ $usuario->estatus === 'activo' ? 'status-active' : 'status-inactive' }}">
                    {{ ucfirst($usuario->estatus) }}
                </td>
                <td style="color: #64748b;">
                    {{ $usuario->created_at->format('d/m/Y') }}
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
