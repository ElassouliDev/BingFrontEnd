<?php

namespace App\Listeners;

use App\Events\AddNewBalanceEvent;
use App\Events\CancelOrderEvent;
use App\Models\Order;
use App\Models\OrderStatusTimeLine;
use App\Models\User;
use App\Models\Wallet;
use App\Notifications\AcceptOrderNotification;
use App\Notifications\CancelOrderNotification;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class CancelOrderListener
{
    private $payment_id;
    private $res_amount;
    private $app_amount;
    private $res_id;

    public function handle(CancelOrderEvent $event)
    {
        $order = $event->order;
        if ($order instanceof Order) {
            $user = User::query()->where('id', $order->user_id)->first();
            if ($user) {
                Notification::send($user, new CancelOrderNotification($order));

                $statusTimeLine = OrderStatusTimeLine::create([
                    'order_id' => $order->id,
                    'key' => Order::CANCELED,
                    'key_name' => [
                        'ar' => 'CANCELED',
                        'en' => 'CANCELED',
                    ],
                    'value' => Carbon::now()->format(DATE_FORMAT_FULL),
                    'details' => [
                        'ar' => 'CANCELED',
                        'en' => 'CANCELED',
                    ],
                ]);
            }
        }
    }
}
