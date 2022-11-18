<?php

namespace App\Providers;

use App\Events\AcceptOrderEvent;
use App\Events\AddNewBalanceEvent;
use App\Events\BranchNotificationEvent;
use App\Events\CancelOrderEvent;
use App\Events\CompleteOrderEvent;
use App\Events\DriverAcceptOrderEvent;
use App\Events\DriverCompletedOrderEvent;
use App\Events\DriverOnWayOrderEvent;
use App\Events\NewBranchEvent;
use App\Events\NewOrderEvent;
use App\Events\OnProgressOrderEvent;
use App\Events\ReadyOrderEvent;
use App\Events\RestaurantNotificationEvent;
use App\Events\SendSMSEvent;
use App\Events\UserCancelOrderEvent;
use App\Events\UserNotificationEvent;
use App\Listeners\AcceptOrderListener;
use App\Listeners\AddNewBalanceListener;
use App\Listeners\BranchNotificationListener;
use App\Listeners\CancelOrderListener;
use App\Listeners\CompleteOrderListener;
use App\Listeners\DriverAcceptOrderListener;
use App\Listeners\DriverCompletedOrderListener;
use App\Listeners\DriverOnWayDoneOrderListener;
use App\Listeners\DriverOnWayOrderListener;
use App\Listeners\NewBranchListener;
use App\Listeners\NewOrderListener;
use App\Listeners\OnProgressOrderListener;
use App\Listeners\ReadyOrderListener;
use App\Listeners\RestaurantNotificationListener;
use App\Listeners\SendSMSListener;
use App\Listeners\UserCancelOrderListener;
use App\Listeners\UsersNotificationListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        SendSMSEvent::class => [
            SendSMSListener::class,
        ],
        RestaurantNotificationEvent::class => [
            RestaurantNotificationListener::class,
        ],
        BranchNotificationEvent::class => [
            BranchNotificationListener::class,
        ],
        UserNotificationEvent::class => [
            UsersNotificationListener::class,
        ],
        NewOrderEvent::class => [
            NewOrderListener::class,
        ],
        AcceptOrderEvent::class => [
            AcceptOrderListener::class,
        ],

        DriverAcceptOrderEvent::class => [
            DriverAcceptOrderListener::class,
        ],
        DriverOnWayOrderEvent::class => [
            DriverOnWayOrderListener::class,
        ],

        DriverCompletedOrderEvent::class => [
            DriverCompletedOrderListener::class,
        ],
        OnProgressOrderEvent::class => [
            OnProgressOrderListener::class,
        ],
        ReadyOrderEvent::class => [
            ReadyOrderListener::class,
        ],
        CompleteOrderEvent::class => [
            CompleteOrderListener::class,
        ],
        CancelOrderEvent::class => [
            CancelOrderListener::class,
        ],
        UserCancelOrderEvent::class => [
            UserCancelOrderListener::class,
        ],
        AddNewBalanceEvent::class => [
            AddNewBalanceListener::class,
        ],
        NewBranchEvent::class => [
            NewBranchListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
