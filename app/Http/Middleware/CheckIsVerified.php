<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;

class CheckIsVerified
{

    public function handle($request, Closure $next)
    {
        if (auth('api')->user()->verified != true)
            return apiError(api('The account is not verified'));
        return $next($request);
    }
}
