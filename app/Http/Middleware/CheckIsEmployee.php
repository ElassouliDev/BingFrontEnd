<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;

class CheckIsEmployee
{

    public function handle($request, Closure $next)
    {


        if (auth('api')->user()->type != User::type['EMPLOYEE'])
            return apiError(api('you ضhave no permission'));
        return $next($request);
    }
}

