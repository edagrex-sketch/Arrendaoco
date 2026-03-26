<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Pago</title>
    <style>
        @page {
            margin: 40px;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #000;
            background-color: #fcfbf8; /* Light cream */
            margin: 0;
            padding: 20px;
        }
        .header-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .title {
            font-size: 54px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: -1px;
            line-height: 1;
            margin: 0;
            padding: 0;
            margin-bottom: 5px;
        }
        .logo-circle {
            width: 70px;
            height: 70px;
            border: 3px solid #000;
            border-radius: 50%;
            text-align: center;
            line-height: 70px;
            font-size: 32px;
            font-weight: bold;
            float: right;
        }
        .invoice-no {
            border: 2px solid #000;
            border-radius: 20px;
            padding: 5px 20px;
            display: inline-block;
            font-weight: bold;
            font-size: 16px;
        }
        /* Top Box */
        .info-box {
            border: 2px solid #000;
            border-radius: 15px;
            margin-bottom: 30px;
            overflow: hidden;
            width: 100%;
            border-spacing: 0;
            background-color: #fcfbf8;
        }
        .info-box td {
            vertical-align: top;
            padding: 20px;
            width: 50%;
        }
        .info-box td.right-border {
            border-right: 2px solid #000;
        }
        .info-title {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 13px;
            margin-bottom: 10px;
        }
        .info-text {
            font-size: 13px;
            line-height: 1.5;
        }
        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-bottom: 30px;
        }
        .items-table th {
            background-color: #000;
            color: #fff;
            padding: 12px 20px;
            font-size: 13px;
            font-weight: bold;
            text-align: left;
        }
        .items-table th.first {
            border-top-left-radius: 20px;
            border-bottom-left-radius: 20px;
        }
        .items-table th.last {
            border-top-right-radius: 20px;
            border-bottom-right-radius: 20px;
            text-align: right;
        }
        .items-table th.center {
            text-align: center;
        }
        .items-table td {
            padding: 15px 20px;
            font-size: 14px;
            border-bottom: 1px dashed #ccc;
        }
        .items-table td.center {
            text-align: center;
        }
        .items-table td.right {
            text-align: right;
        }
        /* Totals */
        .totals-table {
            width: 45%;
            float: right;
            border-collapse: collapse;
            font-size: 14px;
            margin-bottom: 40px;
        }
        .totals-table td {
            padding: 10px 20px;
        }
        .totals-table td.label {
            text-align: left;
        }
        .totals-table td.value {
            text-align: right;
        }
        .total-row {
            background-color: #000;
            color: #fff;
        }
        .total-row td {
            font-weight: bold;
            font-size: 16px;
        }
        .total-row td.first {
            border-top-left-radius: 20px;
            border-bottom-left-radius: 20px;
        }
        .total-row td.last {
            border-top-right-radius: 20px;
            border-bottom-right-radius: 20px;
        }
        .clear { clear: both; }

        /* Footer */
        .footer-table {
            width: 100%;
            margin-top: 40px;
        }
        .footer-table td {
            vertical-align: bottom;
        }
        .payment-box {
            border: 2px solid #000;
            border-radius: 15px;
            padding: 20px;
            width: 300px;
            background-color: #fcfbf8;
        }
        .payment-box p {
            margin: 0;
            font-size: 12px;
            line-height: 1.5;
        }
        .thanks-box {
            background-color: #000;
            color: #fff;
            border-radius: 20px;
            padding: 10px 30px;
            font-weight: bold;
            text-align: center;
            display: inline-block;
            margin-bottom: 10px;
            letter-spacing: 1px;
            font-size: 14px;
        }
        .website-box {
            border: 2px solid #000;
            border-radius: 20px;
            padding: 10px 30px;
            font-weight: bold;
            text-align: center;
            letter-spacing: 1px;
            font-size: 13px;
        }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td style="vertical-align: top;">
                <h1 class="title">RECIBO</h1>
                <div class="invoice-no">Nº: {{ str_pad($pago->id, 4, '0', STR_PAD_LEFT) }}-{{ date('y') }}</div>
            </td>
            <td style="vertical-align: top; text-align: right;">
                <img src="{{ public_path('logo2.png') }}" alt="Logo ArrendaOco" style="height: 70px; width: auto; float: right;">
            </td>
        </tr>
    </table>

    <table class="info-box">
        <tr>
            <td class="right-border">
                <div class="info-title">DATOS DEL ARRENDATARIO</div>
                <div class="info-text">
                    <strong>{{ optional($pago->contrato->inquilino)->nombre ?? 'Inquilino de ArrendaOco' }}</strong><br>
                    {{ optional($pago->contrato->inquilino)->email ?? 'Correo no registrado' }}<br>
                    Propiedad: {{ optional($pago->contrato->inmueble)->titulo }}<br>
                    Fecha: {{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d / m / Y') }}
                </div>
            </td>
            <td style="text-align: right;">
                <div class="info-title">DATOS DEL ARRENDADOR</div>
                <div class="info-text">
                    <strong>{{ optional($pago->contrato->inmueble->propietario)->nombre ?? 'Propietario de ArrendaOco' }}</strong><br>
                    {{ optional($pago->contrato->inmueble->propietario)->email ?? 'Correo no registrado' }}<br>
                    Plataforma ArrendaOco<br>
                    Av. Central, Ocosingo, Chiapas.
                </div>
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th class="first">Detalle</th>
                <th class="center">Cantidad</th>
                <th class="center">Precio</th>
                <th class="last">Total</th>
            </tr>
        </thead>
        <tbody>
            @if($pago->concepto == 'Renta 1er Mes + Depósito')
                <tr>
                    <td>Mensualidad - {{ optional($pago->contrato->inmueble)->titulo }}</td>
                    <td class="center">01</td>
                    <td class="center">${{ number_format($pago->contrato->renta_mensual, 2) }}</td>
                    <td class="right">${{ number_format($pago->contrato->renta_mensual, 2) }}</td>
                </tr>
                <tr>
                    <td>Depósito de Garantía reembolsable</td>
                    <td class="center">01</td>
                    <td class="center">${{ number_format($pago->contrato->deposito ?? 0, 2) }}</td>
                    <td class="right">${{ number_format($pago->contrato->deposito ?? 0, 2) }}</td>
                </tr>
            @else
                <tr>
                    <td>{{ $pago->concepto }}</td>
                    <td class="center">01</td>
                    <td class="center">${{ number_format($pago->monto, 2) }}</td>
                    <td class="right">${{ number_format($pago->monto, 2) }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    <table class="totals-table">
        <tr>
            <td class="label">Comisiones</td>
            <td class="value">0 %</td>
            <td class="value">$0.00</td>
        </tr>
        <tr class="total-row">
            <td class="first" colspan="2" style="padding-left: 20px;">TOTAL</td>
            <td class="last value" style="padding-right: 20px;">${{ number_format($pago->monto, 2) }} MXN</td>
        </tr>
    </table>
    
    <div class="clear"></div>

    <table class="footer-table">
        <tr>
            <td>
                <div class="payment-box">
                    <div class="info-title">INFORMACIÓN DE PAGO</div>
                    <p>
                        Pago: <strong>Aprobado</strong><br>
                        Transacción Segura de ArrendaOco<br>
                        Fecha Ref: {{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}<br>
                        Documento válido fiscalmente solo al conciliar.
                    </p>
                </div>
            </td>
            <td style="text-align: right;">
                <div class="thanks-box">GRACIAS</div><br>
                <div class="website-box">WWW.ARRENDAOCO.COM</div>
            </td>
        </tr>
    </table>

</body>
</html>
