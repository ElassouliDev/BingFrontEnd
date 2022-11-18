<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Api\v1\Controller;

use App\Http\Resources\Api\v1\User\ProfileResource;
use App\Models\Branch;
use App\Models\User;
use App\Rules\IntroMobile;
use App\Rules\StartWith;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function login(Request $request)
    {
        //        validations
        $request->validate(['phone' => ['required', 'min:13', 'max:13', new StartWith('+9665'), new IntroMobile()],]);
        $user = $this->model->where('phone', $request->get('phone'))->first();
        //        Generate code
        $SMS_code = generateCode();
        //$SMS_code = CODE_FIXED;
        $message = 'Ping APP verif. Code: ' . $SMS_code;
        if (!isset($user)) {
            $user = User::create([
                'type' => User::type['CUSTOMER'],
                'verified' => false,
                'phone' => $request->phone,
                'generatedCode' => $SMS_code,
            ]);
        } else {
            $user->update([
                'verified' => false,
                'generatedCode' => $SMS_code,
            ]);
        }
       send_sms_message($user->phone, $message);
        return apiSuccess(new ProfileResource($user));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|max:100',
            'city_id' => 'nullable|exists:cities,id',
            'account_type' => 'required|in:' . User::DRIVER . ',' . User::CLIENT,
            'nationality_id' => 'requiredIf:account_type,' . User::DRIVER . '|exists:nationality,id',
            'transporter_type_id' => 'requiredIf:account_type,' . User::DRIVER . '|exists:transporter_type,id',

            'image' => 'nullable|image',
            'id_card' => 'nullable|image',
            'driving_license' => 'nullable|image',
//            'i_ban' => 'requiredIf:driver_type,' . User::FREELANCER_DRIVER,
            'bank_id' => 'nullable|exists:banks,id',
//            "driver_type" => 'requiredIf:driver_type,' . User::DRIVER . 'in:' . User::FOLLOWED_TO_RESTAURANT_DRIVER . ',' . User::FREELANCER_DRIVER,
            'branch_code' => 'nullable|min:5|max:5|exists:branches,drivers_code',
        ]);
        if ($request->account_type == User::DRIVER && $request->driver_type == User::FREELANCER_DRIVER) {
            if (!isset($request->i_ban)) return apiError(api('iban field is required if your account is freelancer'));
            if (!isset($request->bank_id)) return apiError(api('bank field is required if your account is freelancer'));
        }

        $user = apiUser();

        $user->name = [
            'ar' => $request->name,
            'en' => $request->name,
        ];
//        $user->email = $request->email;
        $user->city_id = $request->city_id;
        $user->type = $request->account_type;
        if ($request->hasFile('image')) $user->image = uploadImage($request->file('image'), User::DIR_IMAGE_UPLOADS);
        if ($request->hasFile('id_card')) $user->id_card = uploadImage($request->file('id_card'), User::DIR_IMAGE_UPLOADS);
        if ($request->hasFile('driving_license')) $user->driving_license = uploadImage($request->file('driving_license'), User::DIR_IMAGE_UPLOADS);

        $user->nationality_id = $request->nationality_id;
        $user->transporter_type_id = $request->transporter_type_id;
        if ($request->account_type == User::DRIVER) {
            $user->status = User::NEW_ACCOUNT;
            if (isset($request->branch_code)) {
                $user->driver_type = User::FOLLOWED_TO_RESTAURANT_DRIVER;
                $branch = Branch::where('drivers_code', $request->branch_code)->first();
                $user->branch_id = $branch->id;
                $merchant = $branch->merchant;
                $user->merchant_id = $merchant->id;
                $user->i_ban = $merchant->i_ban;
                $user->bank_id = $merchant->bank_id;
            } else {
                $user->driver_type = User::FREELANCER_DRIVER;
                $user->bank_id = $request->bank_id;
                $user->i_ban = $request->i_ban;
            }
        } else {
            $user->status = User::ACTIVE;
        }
        $user->save();
        $user['access_token'] = $user->createToken(API_ACCESS_TOKEN_NAME)->accessToken;
//        $user['access_token'] = $request->header('Authorization');
        return apiSuccess(new ProfileResource($user));
    }

    public function resendCode(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'min:13', 'max:13', new StartWith('+9665'), new IntroMobile()],

        ]);
        $user = $this->model->where('phone', request('phone'))->first();
        if (!isset($user)) return apiError(apiTrans('wrong phone number'));
        //        Generate code
        $SMS_code = generateCode();
        $message = 'Ping App verif. Code: ' . $SMS_code;


        $user->update([
            'verified' => true,
            'generatedCode' => $SMS_code,
        ]);
        send_sms_message($user->phone, $message);
        return apiSuccess(new ProfileResource($user));

    }

    public function verified_code(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'min:13', 'max:13', new StartWith('+9665'), new IntroMobile()],
            'code' => 'required|min:4|max:4',
        ]);
        $user = $this->model->where('phone', $request->get('phone'))->first();
        if ($user) {
            if ($user->generatedCode != $request->get('code')) return apiError(apiTrans('You cannot login verified code invalid'));
            $user->update([
                'generatedCode' => null,
                'verified' => true,
            ]);
            $user['access_token'] = $user->createToken(API_ACCESS_TOKEN_NAME)->accessToken;
            return apiSuccess(new ProfileResource($user), apiTrans('Successfully verified'));
        } else {
            return apiError(apiTrans('The code entered is not valid'));
        }
    }

    public function logoutAllAuthUsers()
    {
        return apiSuccess(logoutAllAuthUsers());
    }

    public function logout(Request $request)
    {

        $user = apiUser();
        if (!isset($user)) return apiSuccess(null, apiTrans('please login'));
        $user->tokens()->delete();
        $user->update([
            'fcm_token' => null
        ]);
        return apiSuccess(null, apiTrans('Successful Logout'));

    }
}
