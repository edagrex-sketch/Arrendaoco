<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mensaje extends Model
{
    use HasFactory;

    protected $table = 'mensajes';

    protected $fillable = [
        'chat_id',
        'sender_id',
        'contenido',
        'leido',
        'parent_id',
        'tipo',
        'metadata',
    ];

    protected $casts = [
        'leido' => 'boolean',
        'metadata' => 'array',
    ];

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    public function sender()
    {
        return $this->belongsTo(Usuario::class, 'sender_id');
    }

    public function parent()
    {
        return $this->belongsTo(Mensaje::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(Mensaje::class, 'parent_id');
    }
}
