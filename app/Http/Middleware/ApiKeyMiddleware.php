<?php

namespace App\Http\Middleware;

use Closure;

class ApiKeyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!is_null($request->header('api-key'))  && (config('app.api_key') === $request->header('api-key'))) {
            return $next($request);
        }
        if (strpos($request->url(), '/api/') !== false || strpos($request->url(), '/web/') !== false)
        {
            return response()->json([
                'success' => false,
                'message' => api('Access Denied'),
                'status' => 403,
            ],403);
        }
    }
}
