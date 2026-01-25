<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Inmueble;
use App\Models\Usuario;
use App\Models\Pago;

class Contrato extends Model
{
    use HasFactory;

    protected $table = 'contratos';

    protected $fillable = [
        'inmueble_id',
        'propietario_id',
        'inquilino_id',
        'fecha_inicio',
        'fecha_fin',
        'renta_mensual',
        'deposito',
        'estatus',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    // Inmueble que se renta
    public function inmueble()
    {
        return $this->belongsTo(Inmueble::class);
    }

    // Usuario que es propietario en este contrato
    public function propietario()
    {
        return $this->belongsTo(Usuario::class, 'propietario_id');
    }

    // Usuario que es inquilino en este contrato
    public function inquilino()
    {
        return $this->belongsTo(Usuario::class, 'inquilino_id');
    }
    public function pagos()
{
    return $this->hasMany(Pago::class);
}
}
