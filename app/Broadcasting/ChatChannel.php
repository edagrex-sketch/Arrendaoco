<?php

namespace App\Broadcasting;

use App\Models\Usuario;
use App\Models\Chat;

class ChatChannel
{
    /**
     * Create a new channel instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     */
    public function join(Usuario $user, $chatId): bool
    {
        $chat = Chat::find($chatId);
        
        if (!$chat) {
            return false;
        }

        return $user->id === $chat->usuario_1 || $user->id === $chat->usuario_2;
    }
}
