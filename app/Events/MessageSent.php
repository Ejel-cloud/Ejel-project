<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow; // مهم للبث الفوري
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $username;

    public function __construct($message, $username)
    {
        $this->message = $message;
        $this->username = $username;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('public-chat'); // اسم القناة
    }

    public function broadcastAs(): string
    {
        return 'message.sent'; // اسم الحدث في JS
    }
}
