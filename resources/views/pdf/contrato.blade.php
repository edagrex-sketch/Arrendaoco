<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Contrato de Arrendamiento</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 1.5; color: #333; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .mb-4 { margin-bottom: 1rem; }
        .mb-2 { margin-bottom: 0.5rem; }
        .mt-4 { margin-top: 1rem; }
        .mt-8 { margin-top: 2rem; }
        .text-justify { text-align: justify; }
        .signature-block { width: 45%; display: inline-block; vertical-align: bottom; text-align: center; margin-top: 50px; }
        .signature-line { border-top: 1px solid #000; padding-top: 5px; margin-top: 5px; }
        img.firma { max-height: 80px; width: auto; max-width: 100%; }
        h3 { font-size: 16px; margin-bottom: 20px; text-transform: uppercase; }
        h4 { font-size: 14px; margin-top: 15px; border-bottom: 1px solid #ccc; padding-bottom: 3px; }
    </style>
</head>
<body>
    <h3 class="text-center font-bold">CONTRATO DE ARRENDAMIENTO TEMPORAL</h3>
    
    <p class="text-justify">El presente contrato de arrendamiento es celebrado en {{ $contrato->inmueble->ciudad ?? 'Territorio ArrendaOco' }} en fecha {{ \Carbon\Carbon::parse($contrato->created_at)->translatedFormat('d \d\e F \d\e\l Y') }} (en adelante, el "Contrato").</p>
    
    <h4 class="font-bold">ENTRE</h4>
    <p class="text-justify"><strong>{{ optional($contrato->inmueble->propietario)->nombre ?? 'El Arrendador' }}</strong>, actuando en su propio nombre y derecho. De aquí en adelante el “Arrendador”.</p>
    <p class="text-center">- Y -</p>
    <p class="text-justify"><strong>{{ optional($contrato->inquilino)->nombre ?? 'El Inquilino' }}</strong>, actuando en su propio nombre y derecho. De aquí en adelante el “Inquilino”.</p>
    <p class="text-justify mt-4">Estos serán considerados individualmente como la “Parte” y conjuntamente como las “Partes”.</p>

    <h4 class="font-bold">DECLARACIONES</h4>
    <p class="font-bold mb-2">EL ARRENDADOR DECLARA:</p>
    <ul class="text-justify">
        <li>Que es de su voluntad rentar al Inquilino el inmueble descrito en la cláusula primera de este Contrato.</li>
        <li>Que dispone de poder y capacidad legal suficiente para celebrar el presente Contrato.</li>
    </ul>

    <p class="font-bold mt-4 mb-2">EL INQUILINO DECLARA:</p>
    <ul class="text-justify">
        <li>Que está interesado en rentar el inmueble para su uso habitacional temporal.</li>
        <li>Que tiene capacidad legal suficiente y adecuada para celebrar este Contrato.</li>
    </ul>

    <h4 class="font-bold text-center mt-8">CLÁUSULAS</h4>
    <p class="font-bold mt-4 mb-2">1. OBJETO DEL CONTRATO Y FINALIDAD DE USO</p>
    <p class="text-justify">Mediante este Contrato, el Arrendador acepta alquilar al Inquilino la propiedad <strong>{{ $contrato->inmueble->titulo }}</strong> localizada en <strong>{{ $contrato->inmueble->direccion ?? 'la dirección acordada' }}</strong>. La propiedad se destinará única y exclusivamente con fines habitacionales.</p>

    <p class="font-bold mt-4 mb-2">2. DURACIÓN Y RENTA</p>
    <p class="text-justify">Este Contrato tendrá un plazo acordado de <strong>{{ $contrato->plazo }}</strong> a contar desde el {{ \Carbon\Carbon::parse($contrato->fecha_inicio)->translatedFormat('d \d\e F \d\e\l Y') }}.<br>
    El Inquilino deberá pagar al Arrendador <strong>${{ number_format($contrato->renta_mensual, 2) }} MXN mensuales</strong> por concepto de Renta.<br>
    A la firma de este Contrato se reconoce el pago y depósito de garantía de <strong>${{ number_format($contrato->deposito ?? 0, 2) }} MXN</strong>.</p>

    <p class="font-bold mt-4 mb-2">3. GENERALES</p>
    <p class="text-justify">El Inquilino se compromete a respetar las normas de convivencia aplicables a la propiedad. El presente resumen incluye las condiciones comerciales esenciales. Todos los demás términos legales siguen lo pactado integralmente en los Términos de ArrendaOco.</p>

    <p class="mt-8 mb-4 text-center">Firman de conformidad todas las Partes por medio del registro digital de ArrendaOco.</p>

    <div style="width: 100%; margin-top: 50px; page-break-inside: avoid;">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                {{-- Firma del Arrendador --}}
                <td style="width: 45%; text-align: center; vertical-align: bottom;">
                    <div style="height: 80px; margin-bottom: 10px;">
                        @if($contrato->firma_propietario)
                            <img src="{{ $contrato->firma_propietario }}" alt="Firma Arrendador" style="max-height: 80px; max-width: 200px;">
                        @else
                            <div style="border: 1px dashed #ccc; padding: 10px; height: 60px;">
                                <p style="font-size: 8px; color: #666;">Pendiente de firma</p>
                            </div>
                        @endif
                    </div>
                    <div style="border-top: 1px solid #000; padding-top: 5px;">
                        <p class="font-bold" style="font-size: 10px; margin: 0; text-transform: uppercase;">Firma del Arrendador</p>
                        <p style="font-size: 10px; margin: 2px 0;">{{ optional($contrato->inmueble->propietario)->nombre }}</p>
                    </div>
                </td>

                <td style="width: 10%;"></td> {{-- Espacio --}}

                {{-- Firma del Inquilino --}}
                <td style="width: 45%; text-align: center; vertical-align: bottom;">
                    <div style="height: 80px; margin-bottom: 10px;">
                        @if($contrato->firma_digital)
                            <img src="{{ $contrato->firma_digital }}" alt="Firma Inquilino" style="max-height: 80px; max-width: 200px;">
                        @endif
                    </div>
                    <div style="border-top: 1px solid #000; padding-top: 5px;">
                        <p class="font-bold" style="font-size: 10px; margin: 0; text-transform: uppercase;">Firma del Inquilino</p>
                        <p style="font-size: 10px; margin: 2px 0;">{{ optional($contrato->inquilino)->nombre }}</p>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
