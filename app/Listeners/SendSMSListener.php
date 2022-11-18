<?php

namespace App\Listeners;

use App\Events\SendSMSEvent;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendSMSListener
{

    public function handle(SendSMSEvent $event)
    {
        if ($event->user instanceof User)
        {

            //Send SMS Code Getaway
        }
    }
}
