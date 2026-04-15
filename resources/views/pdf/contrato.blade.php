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
        h3 { font-size: 16px; margin-bottom: 20px; text-transform: uppercase; }
        h4 { font-size: 14px; margin-top: 15px; border-bottom: 1px solid #ccc; padding-bottom: 3px; }
        .firma-bloque { width: 45%; display: inline-block; vertical-align: bottom; text-align: center; }
        .firma-linea { border-top: 1px solid #000; padding-top: 6px; margin-top: 0; }
        .firma-espacio { height: 75px; border-bottom: 1px solid #000; margin-bottom: 8px; }
    </style>
</head>
<body>
    <x-contrato-legal :inmueble="$contrato->inmueble" :contrato="$contrato" />

    {{-- ═══════════════════════════════════════════════════════════
         BLOQUE DE FIRMAS FÍSICAS — Sin imágenes digitales.
         Ambas partes deben firmar de puño y letra sobre esta línea
         tras imprimir el documento.
    ═══════════════════════════════════════════════════════════ --}}
    <div style="width: 100%; margin-top: 70px; page-break-inside: avoid;">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                {{-- Firma del Arrendador --}}
                <td style="width: 45%; text-align: center; vertical-align: bottom; padding: 0 10px;">
                    <div class="firma-espacio"></div>
                    <div class="firma-linea">
                        <p style="font-size: 10px; font-weight: bold; margin: 4px 0 0; text-transform: uppercase; letter-spacing: 1px;">
                            Firma del Arrendador
                        </p>
                        <p style="font-size: 10px; margin: 3px 0;">
                            {{ optional($contrato->inmueble->propietario)->nombre }}
                        </p>
                        <p style="font-size: 8px; color: #888; margin: 2px 0;">Nombre, firma y fecha</p>
                    </div>
                </td>

                <td style="width: 10%;"></td>

                {{-- Firma del Inquilino --}}
                <td style="width: 45%; text-align: center; vertical-align: bottom; padding: 0 10px;">
                    <div class="firma-espacio"></div>
                    <div class="firma-linea">
                        <p style="font-size: 10px; font-weight: bold; margin: 4px 0 0; text-transform: uppercase; letter-spacing: 1px;">
                            Firma del Inquilino
                        </p>
                        <p style="font-size: 10px; margin: 3px 0;">
                            {{ optional($contrato->inquilino)->nombre }}
                        </p>
                        <p style="font-size: 8px; color: #888; margin: 2px 0;">Nombre, firma y fecha</p>
                    </div>
                </td>
            </tr>
        </table>

        {{-- Instrucción de doble copia (pie de página legal) --}}
        <p style="margin-top: 40px; font-size: 8px; color: #999; text-align: center; border-top: 1px solid #eee; padding-top: 10px;">
            El Arrendatario declara haber recibido copia duplicada de este Contrato, firmada por ambas partes, en la fecha indicada arriba.
            Cada parte conservará un ejemplar como respaldo legal físico. &nbsp;|&nbsp; ©{{ date('Y') }} ArrendaOco
        </p>
    </div>
</body>
</html>
