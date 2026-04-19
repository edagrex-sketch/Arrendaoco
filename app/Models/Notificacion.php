<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    use HasFactory;

    protected $table = 'notificaciones';

    protected $fillable = [
        'usuario_id',
        'titulo',
        'mensaje',
        'tipo',
        'leida',
        'referencia_id',
    ];

    protected $casts = [
        'leida' => 'boolean',
    ];

    protected $appends = ['url'];

    public function getUrlAttribute()
    {
        if (!$this->referencia_id) return '#';

        try {
            switch ($this->tipo) {
                case 'mensaje':
                    return route('chats.show', $this->referencia_id);
                case 'renta':
                    return '#'; // Expandible a rutas de contratos
                case 'sistema':
                    // Si la referencia es un inmueble
                    return route('inmuebles.show', $this->referencia_id);
                default:
                    return '#';
            }
        } catch (\Exception $e) {
            return '#';
        }
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
