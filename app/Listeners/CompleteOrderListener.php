<?php

namespace App\Listeners;

use App\Events\CancelOrderEvent;
use App\Events\CompleteOrderEvent;
use App\Models\Order;
use App\Models\User;
use App\Notifications\CompleteOrderNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class CompleteOrderListener
{
    public function handle(CompleteOrderEvent $event)
    {
        if ($event->order instanceof Order)
        {
            $user = User::query()->where('id', $event->order->user_id)->first();
            if ($user)
                Notification::send($user, new CompleteOrderNotification($event->order));
        }
    }
}
