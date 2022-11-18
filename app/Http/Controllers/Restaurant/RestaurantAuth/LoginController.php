<?php

namespace App\Http\Controllers\Restaurant\RestaurantAuth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Hesto\MultiAuth\Traits\LogsoutGuard;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers, LogsoutGuard {
        LogsoutGuard::logout insteadof AuthenticatesUsers;
    }
    public $redirectTo = '/restaurant/home';

    public function showLoginForm()
    {
        return view('restaurant.auth.login');
    }
    public function username()
    {
        return 'email';
    }
    protected function authenticated(Request $request, $user)
    {
        session(['lang' => $user->local]);
        app()->setLocale($user->local);
//        dd(checkRequestIsWorkingOrNot(),$user);
    }
    protected function guard()
    {
        return Auth::guard('merchant');
    }

}
