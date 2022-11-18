<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Api\v1\Controller;
use App\Models\ContactUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class ContactUsController extends Controller
{
    public function contactUs(Request $request)
    {
//        validations
        $respones = getFirstError($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|min:3|max:255',
            'message' => 'required|min:10|max:300',
        ]);
        if ($respones[IS_ERROR] == true) {
            return apiError($respones[ERROR]);
        }
        ContactUs::create($request->all());
        return apiSuccess(apiTrans('data_saved_successfully'));
    }
}
