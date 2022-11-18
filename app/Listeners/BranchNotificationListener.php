<?php

namespace App\Listeners;

use App\Events\BranchNotificationEvent;
use App\Notifications\GeneralNotification;
use Illuminate\Support\Facades\Notification;

class BranchNotificationListener
{
    public function handle(BranchNotificationEvent $event)
    {
        Notification::send($event->user, new GeneralNotification($event->user,$event->title, $event->body));
    }
}
