<?php

namespace App\Http\Middleware;

use Closure;
use App;
use Illuminate\Support\Facades\Auth;

class SetLocalLanguageWeb
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
        $locale =  session('lang') ?  session('lang'): 'ar' ;
        if(Auth::guard('web')->check() ){
            $locale = isAPI()? request()->header('Accept-Language') : Auth::guard('web')->user()->local ;
        }
        if(!$locale || !in_array($locale,['ar','en'])) $locale = 'ar';
        app()->setLocale($locale);
        return $next($request);
    }
}
