<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;

class CheckClientPackage
{

    public function handle($request, Closure $next)
    {
        if (apiUser()->packages()->where('expired', false)->count() == 0) return apiError(api('You have to buy package'));
        return $next($request);
    }
}
