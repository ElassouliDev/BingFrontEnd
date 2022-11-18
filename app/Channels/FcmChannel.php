<?php
/*  Dev Omar Shaheen
    Devomar095@gmail.com
    WhatsApp +972592554320
    2/5/2020 helpingHand
*/

namespace App\Channels;


use Illuminate\Notifications\Notification;

class FcmChannel
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
        $message = $notification->toFcm($notifiable);
    }
}
