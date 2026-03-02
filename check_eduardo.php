<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\Usuario::where('nombre', 'like', '%Eduardo Aguilar%')->first();
if ($user) {
    echo "ID: " . $user->id . "\n";
    echo "Nombre: " . $user->nombre . "\n";
    echo "Foto Perfil: '" . $user->foto_perfil . "'\n";
    echo "Length: " . strlen($user->foto_perfil) . "\n";
} else {
    echo "Usuario no encontrado.\n";
}
