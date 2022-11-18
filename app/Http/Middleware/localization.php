<?php

namespace App\Http\Middleware;

use Closure;

class localization
{

    public function handle($request, Closure $next)
    {
        $local = ($request->hasHeader('Content-Language')) ? $request->header('Content-Language') : 'en';
        app()->setLocale($local);
        return $next($request);
    }
    /*
     *
     *         $users = User::where('user_type', UserType::ADMIN)->get();
                $notification = new CreateProjectNotification($store);

                $tokens = $users->pluck('device_token')->toArray();
                send_notification_fcm($tokens, $notification->toArray());
                Notification::send($users, $notification);

     */
}
