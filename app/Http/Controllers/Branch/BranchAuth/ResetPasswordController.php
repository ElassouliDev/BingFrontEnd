<?php

namespace App\Http\Controllers\Restaurant\RestaurantAuth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Website\MainController;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;


    public $redirectTo = '/restaurant/home';



    public function showResetForm(Request $request, $token = null)
    {
        $validator = JsValidator::make($this->rules(), $this->validationMessages);
        return view('restaurant.auth.passwords.reset',compact('validator'))->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    public function broker()
    {
        return Password::broker('users');
    }

    protected function guard()
    {
        return Auth::guard('web');
    }

    protected function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ];
    }
}
