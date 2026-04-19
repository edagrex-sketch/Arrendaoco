<?php

namespace App\Events;

use App\Models\Contrato;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NuevoContratoIniciado implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $contrato;

    /**
     * Create a new event instance.
     */
    public function __construct(Contrato $contrato)
    {
        $this->contrato = $contrato;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('admin-updates'),
            new PrivateChannel('user.' . $this->contrato->propietario_id),
        ];
    }

    public function broadcastAs()
    {
        return 'nuevo-contrato';
    }
}
