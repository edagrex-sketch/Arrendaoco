<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Pagos
Schedule::command('pagos:marcar-vencidos')->daily();
Schedule::command('pagos:calcular-recargos')->daily();

// Estados de cuenta (mensual, día 1 a las 02:00)
Schedule::command('estados-cuenta:generar')->monthlyOn(1, '02:00');

// Recordatorio a propietarios con contratos pdf_descargado sin subir (diario a las 09:00)
Schedule::command('contratos:recordar-firmado --dias=2')->dailyAt('09:00');

// Respaldos automáticos
Schedule::command('respaldos:ejecutar-automatico')->hourly();