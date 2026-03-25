<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZonaComun extends Model
{
    use HasFactory;

    protected $table = 'zonas_comunes';

    protected $fillable = [
        'nombre',
        'slug',
    ];

    public function inmuebles()
    {
        return $this->belongsToMany(Inmueble::class, 'inmueble_zona_comun');
    }
}
