<?php

namespace App\Listeners;

use App\Events\UserNotificationEvent;
use App\Notifications\GeneralNotification;
use Illuminate\Support\Facades\Notification;

class UsersNotificationListener
{
    public function handle(UserNotificationEvent $event)
    {
        Notification::send($event->user, new GeneralNotification($event->user,$event->title, $event->body));
    }
}
