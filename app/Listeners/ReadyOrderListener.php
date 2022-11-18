<?php

namespace App\Listeners;

use App\Events\CancelOrderEvent;
use App\Events\ReadyOrderEvent;
use App\Models\Delivery;
use App\Models\Order;
use App\Models\User;
use App\Notifications\RateOrderNotification;
use App\Notifications\ReadyOrderNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class ReadyOrderListener
{
    public function handle(ReadyOrderEvent $event)
    {
        $order = $event->order;
        if ($order instanceof Order) {
            $merchants_range = optional(getSettings('merchants_range'))->value;
            $merchants_range = (isset($merchants_range)) ? $merchants_range : 3;
            $branch = $order->branch;
            $lat = $branch->lat;
            $lng = $branch->lng;

            $drivers = User::driver()->when($branch, function ($query) use ($lat, $lng, $merchants_range) {
                $query->selectRaw("users.*,ROUND(6371 * acos( cos( radians({$lat}) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians({$lng}) ) + sin( radians({$lat}) ) * sin(radians(lat)) ) ) AS distance")
                    ->having("distance", "<", $merchants_range)
                    ->orderBy('distance', "ASC");
            });
            $user = apiUser();
            if (isset($user) && $user->type == User::DRIVER) {
                $drivers = $drivers->where('id', '!=', $user->id);
            }
            $drivers = $drivers->get();
            $branch_drivers = $branch->drivers;
            if (count($branch_drivers) > 0) {
                $drivers = $drivers->merge($branch_drivers);
                $drivers = $drivers->unique();
            }


            if (count($drivers) > 0) {
                foreach ($drivers as $index => $driver) {
                    Delivery::create([
                        'driver_id' => $driver->id,
                        'order_id' => $order->id,
                        'status' => Delivery::NEW_DELIVERY,
                        'distance' => $order->distance,
                        'counter' => 60,//$time_min ,
                    ]);
                }
                Notification::send($drivers, new ReadyOrderNotification($order));
            }
        }


    }
}
