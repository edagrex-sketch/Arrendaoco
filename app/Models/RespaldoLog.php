<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RespaldoLog extends Model
{
    protected $table = 'respaldos_logs';
    protected $fillable = ['tipo', 'nombre_archivo', 'ruta', 'tamano', 'estatus', 'error'];
}
