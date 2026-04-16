<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mobiliario extends Model
{
    use HasFactory;

    protected $table = 'mobiliarios';

    protected $fillable = [
        'nombre',
        'slug',
    ];

    public function inmuebles()
    {
        return $this->belongsToMany(Inmueble::class, 'inmueble_mobiliario');
    }
}
