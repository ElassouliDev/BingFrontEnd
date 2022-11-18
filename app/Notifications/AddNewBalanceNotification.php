<?php

namespace App\Notifications;

use App\Channels\FcmChannel;
use App\Models\User;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AddNewBalanceNotification extends Notification
{
    use Queueable;

    public $user;
    public $wallet;
    private $message;

    public function __construct(User $user, Wallet $wallet)
    {
        $this->user = $user;
        $this->wallet = $wallet;
        $date = Carbon::now();
        $date_formatted = $date->format(DATE_FORMAT_DOTTED);
        $time_formatted = $date->format(TIME_FORMAT_WITHOUT_SECONDS);
        $this->message = [
            'title' => [
                'ar' => notification_trans("Add New Balance", [], 'ar'),
                'en' => notification_trans("Add New Balance", [], 'en'),
            ],
            'body' => [
                'ar' => notification_trans("Admin Charge your wallet with a new balance", [
                    'amount' => $this->wallet->amount
                ], 'ar'),
                'en' => notification_trans("Admin Charge your wallet with a new balance", [
                    'amount' => $this->wallet->amount
                ], 'en'),
            ],
            'others' => [
                'type' => ADMIN_CHARGING_WALLET_NOTIFICATION,
                'date' => days(getDayNumber(Carbon::now()->dayOfWeek)) . ' | ' . $date_formatted . ' | ' . $time_formatted,
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
