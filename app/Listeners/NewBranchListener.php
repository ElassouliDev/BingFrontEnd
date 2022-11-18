<?php

namespace App\Listeners;

use App\Events\NewBranchEvent;
use App\Models\Manager;
use App\Notifications\NewBranchNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class NewBranchListener
{
    public function handle(NewBranchEvent $event)
    {
        $managers = Manager::query()->get();
        Notification::send($managers, new NewBranchNotification($event->branch));
    }
}
