<?php

namespace App\Listeners;

use App\Events\CancelOrderEvent;
use App\Events\RateOrderEvent;
use App\Models\Order;
use App\Models\User;
use App\Notifications\RateOrderNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class RateOrderListener
{
    public function handle(RateOrderEvent $event)
    {
        if ($event->order instanceof Order)
        {
            $user = User::query()->where('id',optional( $event->order->branch)->user_id)->first();
            if ($user){
                Notification::send($user, new RateOrderNotification($event->order));
                $owner = User::query()->where('id',optional( $event->order->branch)->owner_id)->first();
                Notification::send($owner, new RateOrderNotification($event->order));
            }
        }
    }
}
