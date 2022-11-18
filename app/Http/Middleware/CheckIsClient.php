<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;

class CheckIsClient
{

    public function handle($request, Closure $next)
    {
        if (auth('api')->user()->type != User::type['CUSTOMER'])
            return apiError(api('you have no permission'));
        return $next($request);
    }
}
