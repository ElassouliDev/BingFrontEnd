<?php

namespace App\Http\Middleware;

use Closure;
use App;
use Illuminate\Support\Facades\Auth;

class SetLocalLanguage
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
        if(Auth::guard('manager')->check()){
            $locale = isAPI()? request()->header('Accept-Language') : Auth::guard('manager')->user()->local ;
        }else{
            $locale = isAPI()? request()->header('Accept-Language') : (session('lang') ?  session('lang'): 'en') ;
        }
        if(!$locale || !in_array($locale,['ar','en'])) $locale = 'ar';
        app()->setLocale($locale);
        //dd($locale);
        return $next($request);
    }
}
