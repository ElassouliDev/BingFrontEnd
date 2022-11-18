<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;

class CheckIsActive
{

    public function handle($request, Closure $next)
    {
        if (auth('api')->user()->status != User::ACTIVE)
            return apiError(api('The account is not activated'));
        return $next($request);
    }
}
