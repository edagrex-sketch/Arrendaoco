<h2>Estado de Cuenta</h2>

<p><strong>Contrato:</strong> {{ $contrato->id }}</p>
<p><strong>Inmueble:</strong> {{ $contrato->inmueble->titulo ?? '' }}</p>

<table width="100%" border="1" cellspacing="0" cellpadding="5">
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
        @foreach($contrato->pagos as $pago)
        <tr>
            <td>{{ $pago->mes }}</td>
            <td>{{ $pago->anio }}</td>
            <td>{{ strtoupper($pago->estatus) }}</td>
            <td>${{ number_format($pago->monto, 2) }}</td>
            <td>${{ number_format($pago->recargo, 2) }}</td>
            <td>
                ${{ number_format(
                    $pago->estatus === 'pagado'
                        ? $pago->monto
                        : $pago->total_con_recargo,
                    2
                ) }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
