<?php

namespace App\Listeners;

use App\Events\AcceptOrderEvent;
use App\Events\CancelOrderEvent;
use App\Events\DriverAcceptOrderEvent;
use App\Events\DriverCanceledOrderEvent;
use App\Events\DriverOnWayOrderEvent;
use App\Events\ReadyOrderEvent;
use App\Models\Branch;
use App\Models\Delivery;
use App\Models\Order;
use App\Models\User;
use App\Notifications\AcceptOrderNotification;
use App\Notifications\DriverAcceptOrderNotification;
use App\Notifications\DriverCanceledOrderNotification;
use App\Notifications\DriverOnWayOrderNotification;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class DriverCanceledOrderListener
{
    public function handle(DriverCanceledOrderEvent $event)
    {
        $order = $event->order;
        if ($order instanceof Order) {
            $branch = Branch::query()->where('id', $order->branch_id)->first();
            $user = User::query()->where('id', $order->user_id)->first();
            $delivery_accept = $order->delivery_accept;
            if (isset($delivery_accept)) {
                \App\Models\Wallet::create([
                    'user_id' => $delivery_accept->driver->id,
                    't_type' => \App\Models\Wallet::CANCEL_ORDER,
                    'note' => 'test',
                    'amount' => Setting('commission_cancel_delivery'),
                    'order_id' => $order->id,
                ]);
            }
            if ($branch) Notification::send($branch, new DriverCanceledOrderNotification($order));
            if ($user) Notification::send($user, new DriverCanceledOrderNotification($order));
            event(new ReadyOrderEvent($order));
        }
    }
}
