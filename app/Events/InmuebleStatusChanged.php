<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InmuebleStatusChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $inmuebleId;
    public $nuevoEstatus;

    /**
     * Create a new event instance.
     */
    public function __construct($inmuebleId, $nuevoEstatus)
    {
        $this->inmuebleId = $inmuebleId;
        $this->nuevoEstatus = $nuevoEstatus;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('inmuebles'),
        ];
    }

    public function broadcastAs()
    {
        return 'status.changed';
    }
}
