<?php

namespace App\Notifications;

use App\Channels\FcmChannel;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewOrderNotification extends Notification
{
    use Queueable;

    public $order;
    private $message;

    public function __construct(Order $order)
    {
        $this->order = $order;
        $date = Carbon::now();
        $date_formatted = $date->format(DATE_FORMAT_DOTTED);
        $time_formatted = $date->format(TIME_FORMAT_WITHOUT_SECONDS);
        $this->message = [
            "order_id" => $this->order->id,
            'title' => [
                'ar' => notification_trans("New order", [
                    'uuid' => $this->order->uuid
                ], 'ar'),
                'en' => notification_trans("New order", [
                    'uuid' => $this->order->uuid
                ], 'en'),
            ],
            'body' => [
                'ar' => notification_trans("New order", [], 'ar'),
                'en' => notification_trans("New order", [], 'en'),
            ],
            'click_action' => "order_details_activity",
            'others' => [
                'type' => READY_ORDER_NOTIFICATION,
                'date' => /*days(getDayNumber(Carbon::now()->dayOfWeek)) . ' | ' .*/
                    $date_formatted . ' | ' . $time_formatted,
                "order_id" => $this->order->id,
                "uuid" => $this->order->uuid,
            ],
        ];
    }

    public function via($notifiable)
    {
        return ['database', FcmChannel::class];
    }


    public function toDatabase($notifiable)
    {
        return $this->message;
    }

    public function toFcm($notifiable)
    {
        if ($notifiable instanceof User) {
            $notifiable->setLanguage();
            send_to_topic('user_' . $notifiable->id, $this->message);
        }
    }
}
