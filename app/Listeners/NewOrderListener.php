<?php

namespace App\Listeners;

use App\Events\CancelOrderEvent;
use App\Events\NewOrderEvent;
use App\Models\Order;
use App\Models\User;
use App\Notifications\NewOrderNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class NewOrderListener
{
    public function handle(NewOrderEvent $event)
    {
        $user = User::query()->where('id',optional($event->order->branch)->user_id)->first();
        if ($user){
            Notification::send($user, new NewOrderNotification($event->order));
            Log::notice('notify sent to branch');
            $owner = User::query()->where('id',optional( $event->order->branch)->owner_id)->first();
            if ($owner)
            {
                Notification::send($owner, new NewOrderNotification($event->order));
                Log::notice('notify sent to resturant');
            }
        }else{
            Log::notice('no user Found');
        }
        Log::notice('notify sent');
    }
}
