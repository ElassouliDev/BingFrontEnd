<?php

namespace App\Listeners;

use App\Events\AcceptOrderEvent;
use App\Events\CancelOrderEvent;
use App\Events\DriverAcceptOrderEvent;
use App\Models\Branch;
use App\Models\Delivery;
use App\Models\DeliveryStatusTimeLine;
use App\Models\Order;
use App\Models\User;
use App\Notifications\AcceptOrderNotification;
use App\Notifications\DriverAcceptOrderNotification;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class DriverAcceptOrderListener
{
    public function handle(DriverAcceptOrderEvent $event)
    {
        $order = $event->order;
        if ($order instanceof Order) {
            $branch = Branch::query()->where('id', $order->branch_id)->first();
            if ($branch) {
                Notification::send($branch, new DriverAcceptOrderNotification($order));
                $others = $order->deliveries_new()->where('id', '!=', $order->id)->get();
                //                Cancel other delivries
                foreach ($others as $index => $other) {
                    $other->update([
                        'status' => Delivery::CANCELED,
                    ]);
                    $statusTimeLine = DeliveryStatusTimeLine::create([
                        'delivery_id' => $other->id,
                        'key' => Delivery::CANCELED,
                        'key_name' => 'MERCHANT_REJECT',
                        'value' => Carbon::now(),
                        'details' => api('Delivery MERCHANT_REJECT'),
                    ]);

                }
            }
        }
    }
}
