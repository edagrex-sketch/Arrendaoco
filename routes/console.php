<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Pagos
\Illuminate\Support\Facades\Schedule::command('pagos:marcar-vencidos')->daily();
\Illuminate\Support\Facades\Schedule::command('pagos:calcular-recargos')->daily();

// Estados de cuenta (mensual, día 1 a las 02:00)
\Illuminate\Support\Facades\Schedule::command('estados-cuenta:generar')->monthlyOn(1, '02:00');

// Recordatorio a propietarios con contratos pdf_descargado sin subir (diario a las 09:00)
// Envía el recordatorio cuando el PDF lleva más de 2 días descargado sin confirmación.
\Illuminate\Support\Facades\Schedule::command('contratos:recordar-firmado --dias=2')->dailyAt('09:00');

