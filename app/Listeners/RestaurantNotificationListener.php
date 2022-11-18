<?php

namespace App\Listeners;

use App\Events\RestaurantNotificationEvent;
use App\Notifications\GeneralNotification;
use Illuminate\Support\Facades\Notification;

class RestaurantNotificationListener
{
    public function handle(RestaurantNotificationEvent $event)
    {
        Notification::send($event->user, new GeneralNotification($event->user,$event->title, $event->body));
    }
}
