<?php

namespace App\Listeners;

use App\Events\AcceptOrderEvent;
use App\Events\CancelOrderEvent;
use App\Models\Order;
use App\Models\OrderStatusTimeLine;
use App\Models\User;
use App\Notifications\AcceptOrderNotification;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class AcceptOrderListener
{
    public function handle(AcceptOrderEvent $event)
    {
        $order = $event->order;
        if ($order instanceof Order) {
            $user = User::query()->where('id', $order->user_id)->first();
            if ($user) {
                Notification::send($user, new AcceptOrderNotification($order));
            }
        }
    }
}
