<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $fillable = [
        'contrato_id',
        'mes',
        'anio',
        'monto',
        'estatus',
        'fecha_pago',
        'dias_atraso',
        'recargo',
        'total_con_recargo',
    ];

    public function contrato()
    {
        return $this->belongsTo(Contrato::class);
    }
}
