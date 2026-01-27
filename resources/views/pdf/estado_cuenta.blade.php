<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Estado de Cuenta</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #000000;
            background-color: #FFFFFF;
        }

        h1 {
            margin: 0;
            color: #003049;
            letter-spacing: 1px;
        }

        .header {
            width: 100%;
            margin-bottom: 20px;
        }

        .info-box {
            border-left: 5px solid #003049;
            padding-left: 10px;
            margin-bottom: 15px;
        }

        .info-box p {
            margin: 3px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #003049;
            padding: 6px;
            font-size: 11px;
        }

        th {
            background-color: #003049;
            color: #FFFFFF;
            text-align: center;
        }

        td {
            text-align: center;
        }

        tbody tr:nth-child(even) {
            background-color: #FDF0D5;
        }

        .right {
            text-align: right;
        }

        /* ESTATUS */
        .status-pagado {
            color: #003049;
            font-weight: bold;
        }

        .status-pendiente {
            color: #C1121F;
            font-weight: bold;
        }

        .status-vencido {
            color: #780000;
            font-weight: bold;
        }

        /* RESUMEN */
        .resumen {
            width: 60%;
            margin-top: 25px;
            border: 2px solid #003049;
        }

        .resumen th {
            background-color: #669BBC;
            color: #FFFFFF;
            text-align: left;
        }

        .footer {
            margin-top: 30px;
            font-size: 10px;
            text-align: center;
            color: #003049;
            border-top: 1px solid #669BBC;
            padding-top: 8px;
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <div class="header">
        <table width="100%">
            <tr>
                <td width="30%" align="left">
                    <img src="{{ public_path('logo.png') }}" width="120">
                </td>
                <td width="70%" align="right">
                    <h1>ESTADO DE CUENTA</h1>   
                    <p>ArrendaOco</p>
                    <p>Fecha de emisión: {{ now()->format('d/m/Y') }}</p>
                </td>
            </tr>
        </table>
    </div>

    <!-- INFO CONTRATO -->
    <div class="info-box">
        <p><strong>Contrato:</strong> {{ $contrato->id }}</p>
        <p><strong>Inmueble:</strong> {{ $contrato->inmueble->titulo ?? 'N/A' }}</p>
        <p><strong>Estatus del contrato:</strong> {{ strtoupper($contrato->estatus) }}</p>
    </div>

    <!-- TABLA PAGOS -->
    <table>
        <thead>
            <tr>
                <th>Mes</th>
                <th>Año</th>
                <th>Estatus</th>
                <th>Monto</th>
                <th>Recargo</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pagos as $pago)
                <tr>
                    <td>{{ $pago->mes }}</td>
                    <td>{{ $pago->anio }}</td>
                    <td class="status-{{ $pago->estatus }}">
                        {{ strtoupper($pago->estatus) }}
                    </td>
                    <td class="right">
                        ${{ number_format((float) $pago->monto, 2) }}
                    </td>
                    <td class="right">
                        ${{ number_format((float) ($pago->recargo ?? 0), 2) }}
                    </td>
                    <td class="right">
                        ${{ number_format((float) ($pago->total_con_recargo ?? $pago->monto), 2) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- RESUMEN -->
    <table class="resumen">
        <tr>
            <th>Total pagado</th>
            <td class="right">
                ${{ number_format((float) ($resumen['total_pagado'] ?? 0), 2) }}
            </td>
        </tr>
        <tr>
            <th>Total pendiente</th>
            <td class="right">
                ${{ number_format((float) ($resumen['total_pendiente'] ?? 0), 2) }}
            </td>
        </tr>
        <tr>
            <th>Pagos vencidos</th>
            <td>
                {{ $resumen['vencidos'] ?? 0 }}
            </td>
        </tr>
    </table>

    <!-- FOOTER -->
    <div class="footer">
        Documento generado automáticamente. No requiere firma.
    </div>

</body>
</html>
