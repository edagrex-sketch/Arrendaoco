<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadoCuenta extends Model
{
    protected $table = 'estados_cuenta';

    protected $fillable = [
        'contrato_id',
        'mes',
        'anio',
        'ruta_pdf',
        'generado_por',
    ];

    public function contrato()
    {
        return $this->belongsTo(Contrato::class);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'generado_por');
    }
}
