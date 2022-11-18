<?php

namespace App\Listeners;

use App\Events\UserCancelOrderEvent;
use App\Models\Order;
use App\Models\User;
use App\Notifications\UserCancelOrderNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class UserCancelOrderListener
{
    public function handle(UserCancelOrderEvent $event)
    {
        if ($event->order instanceof Order)
        {
            $user = User::query()->where('id',optional( $event->order->branch)->user_id)->first();
            if ($user){
                Notification::send($user, new UserCancelOrderNotification($event->order));
                $owner = User::query()->where('id',optional( $event->order->branch)->owner_id)->first();
                if ($owner)
                    Notification::send($owner, new UserCancelOrderNotification($event->order));
            }
        }
    }
}
