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
    <x-contrato-legal :inmueble="$contrato->inmueble" :contrato="$contrato" />

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
