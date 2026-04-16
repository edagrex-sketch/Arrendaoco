<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InmuebleServicio extends Model
{
    use HasFactory;

    protected $table = 'inmueble_servicio';
    public $timestamps = false; // No timestamps requested

    protected $fillable = [
        'inmueble_id',
        'servicio',
        'paga'
    ];

    public function inmueble()
    {
        return $this->belongsTo(Inmueble::class, 'inmueble_id');
    }
}
