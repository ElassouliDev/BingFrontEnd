<?php
/*  Dev Omar Shaheen
    Devomar095@gmail.com
    WhatsApp +972592554320
    2/5/2020 helpingHand
*/

namespace App\Channels;


use Illuminate\Notifications\Notification;
use LaravelFCM\Facades\FCM;
use LaravelFCM\Message\Topics;

class PusherChannel
{
    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toPusher($notifiable);
    }
}
