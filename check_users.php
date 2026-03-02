<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$usuarios = \App\Models\Usuario::all();
foreach ($usuarios as $u) {
    echo "Nombre: " . $u->nombre . "\n";
    echo "Foto Perfil: " . $u->foto_perfil . "\n";
    echo "------------------\n";
}
