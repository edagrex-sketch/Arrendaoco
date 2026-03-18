<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'usuario_1',
        'usuario_2',
        'inmueble_id',
        'last_message',
        'last_message_at',
        'activo'
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
        'activo' => 'boolean',
    ];

    public function usuario1()
    {
        return $this->belongsTo(Usuario::class, 'usuario_1');
    }

    public function usuario2()
    {
        return $this->belongsTo(Usuario::class, 'usuario_2');
    }

    public function inmueble()
    {
        return $this->belongsTo(Inmueble::class);
    }

    public function mensajes()
    {
        return $this->hasMany(Mensaje::class);
    }

    /**
     * Obtener el otro usuario de la conversación.
     */
    public function getOtroUsuario($userId)
    {
        return $this->usuario_1 == $userId ? $this->usuario2 : $this->usuario1;
    }
}
