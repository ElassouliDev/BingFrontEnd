<?php

namespace App\Listeners;

use App\Events\AcceptOrderEvent;
use App\Events\CancelOrderEvent;
use App\Events\OnProgressOrderEvent;
use App\Models\Order;
use App\Models\OrderStatusTimeLine;
use App\Models\User;
use App\Notifications\AcceptOrderNotification;
use App\Notifications\RateOrderNotification;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class OnProgressOrderListener
{
    public function handle(OnProgressOrderEvent $event)
    {
        $order = $event->order;
        if ($order instanceof Order) {
            $user = User::query()->where('id', $order->user_id)->first();
            if ($user) {
                Notification::send($user, new RateOrderNotification($order));
                $statusTimeLine = OrderStatusTimeLine::create([
                    'order_id' => $order->id,
                    'key' => Order::ON_PROGRESS,
                    'key_name' => [
                        'ar' => 'جاري التجهيز',
                        'en' => 'On Progress',
                    ],
                    'value' => Carbon::now()->format(DATE_FORMAT_FULL),
                    'details' => [
                        'ar' => 'تجهيز الطلب وتغليفه قبل التوصيل',
                        'en' => 'On Progress',
                    ],
                ]);
            }
        }
    }
}
