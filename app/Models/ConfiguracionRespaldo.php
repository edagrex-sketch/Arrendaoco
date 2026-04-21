<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracionRespaldo extends Model
{
    protected $table = 'configuracion_respaldos';
    protected $fillable = ['automatico', 'frecuencia', 'ultimo_respaldo_auto'];
}
