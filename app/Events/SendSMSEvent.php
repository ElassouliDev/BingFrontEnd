<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendSMSEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $code;
    public function __construct(string $user, string $code)
    {
        $this->user = $user;
        $this->code = $code;
    }

}
