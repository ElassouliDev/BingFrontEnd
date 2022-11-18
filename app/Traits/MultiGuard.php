<?php
/**
 * Created by PhpStorm.
 * User: Dev Omar Shaheen
 * Date: 9/15/2019
 * Time: 10:41 AM
 */
namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

trait MultiGuard
{
    public function get_guard(){
        if(Auth::guard('manager')->check())
        {
            return "manager";
        }
        if(Auth::guard('web')->check())
        {
            if (Auth::user()->type == 'vendor')
            {
                return "restaurants";
            }
            if(Auth::user()->type == 'branch')
            {
                return "branch";
            }
        }

    }

}
