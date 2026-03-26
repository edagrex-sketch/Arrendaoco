<?php

declare(strict_types=1);

use Dompdf\Dompdf;
use Dompdf\Options;

require __DIR__ . '/../vendor/autoload.php';

$input = __DIR__ . '/../docs/awos_u3_investigacion_arrendaoco.html';
$output = __DIR__ . '/../docs/awos_u3_investigacion_arrendaoco.pdf';

if (!is_file($input)) {
    fwrite(STDERR, "No se encontro el archivo HTML de entrada.\n");
    exit(1);
}

$html = file_get_contents($input);

if ($html === false) {
    fwrite(STDERR, "No se pudo leer el archivo HTML.\n");
    exit(1);
}

$options = new Options();
$options->set('isRemoteEnabled', false);
$options->set('isHtml5ParserEnabled', true);
$options->setDefaultFont('DejaVu Sans');

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html, 'UTF-8');
$dompdf->setPaper('letter');
$dompdf->render();

file_put_contents($output, $dompdf->output());

fwrite(STDOUT, "PDF generado en: {$output}\n");
