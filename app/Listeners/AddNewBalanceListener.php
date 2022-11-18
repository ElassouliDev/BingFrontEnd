<?php

namespace App\Listeners;

use App\Events\AddNewBalanceEvent;
use App\Models\User;
use App\Models\Wallet;
use App\Notifications\AddNewBalanceNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class AddNewBalanceListener
{
    public function handle(AddNewBalanceEvent $event)
    {
        if ($event->user instanceof User && $event->wallet instanceof Wallet)
        {
            Notification::send($event->user, new AddNewBalanceNotification($event->user, $event->wallet));
        }
    }
}
