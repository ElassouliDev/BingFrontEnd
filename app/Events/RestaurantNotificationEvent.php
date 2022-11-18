<?php

namespace App\Events;

use App\Models\Merchant;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RestaurantNotificationEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $title;
    public $body;
    public function __construct(Merchant $user, $title, $body)
    {
        $this->user = $user;
        $this->title = $title;
        $this->body = $body;
    }
}
