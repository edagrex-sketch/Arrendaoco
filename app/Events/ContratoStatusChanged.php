<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ContratoStatusChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $contratoId;
    public $nuevoEstatus;
    public $usuarioId;

    /**
     * Create a new event instance.
     */
    public function __construct($contratoId, $nuevoEstatus, $usuarioId)
    {
        $this->contratoId = $contratoId;
        $this->nuevoEstatus = $nuevoEstatus;
        $this->usuarioId = $usuarioId;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->usuarioId),
        ];
    }

    public function broadcastAs()
    {
        return 'rental.updated';
    }
}
