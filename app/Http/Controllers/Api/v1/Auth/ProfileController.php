<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Api\v1\Controller;

use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function updateProfile(Request $request)
    {
        $respones = getFirstError($request, [
            'name' => 'required|string|max:255',
            'country_id' => 'required|numeric',
            'phone' => 'required|' . mobile_regex() . '|unique:users,phone,' . apiUser()->id,
        ]);
        if ($respones[IS_ERROR] == true) {
            return apiError($respones[ERROR]);
        }
        $user = apiUser();
        $user->name = $request->name;
        $user->country_id = $request->country_id;
        if ($user->phone != $request->phone) $type = 1; else $type = 2;
        $user->phone = $request->phone;
        $code = generateCode();
//        TODO send code using sms
        $user->generatedCode = $code;
//        TODO check if the sms message is sanded successfully or not
        $user->save();
        return apiSuccess([
            'code' => $user->generatedCode,
            'type' => $type,
        ]);


    }



}
