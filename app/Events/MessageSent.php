<?php

namespace App\Events;

use App\Models\Mensaje;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $mensaje;

    /**
     * Create a new event instance.
     */
    public function __construct(Mensaje $mensaje)
    {
        $this->mensaje = $mensaje;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chat.' . $this->mensaje->chat_id),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'mensaje' => [
                'id' => $this->mensaje->id,
                'contenido' => $this->mensaje->contenido,
                'sender_id' => $this->mensaje->sender_id,
                'chat_id' => $this->mensaje->chat_id,
                'created_at' => $this->mensaje->created_at->toDateTimeString(),
                'tipo' => $this->mensaje->tipo,
                'metadata' => $this->mensaje->metadata,
                'parent' => $this->mensaje->parent ? [
                    'contenido' => $this->mensaje->parent->contenido,
                    'sender_nombre' => $this->mensaje->parent->sender->nombre ?? 'Usuario'
                ] : null
            ]
        ];
    }

    public function broadcastAs(): string
    {
        return 'MessageSent';
    }
}

