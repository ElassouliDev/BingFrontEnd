<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\v1\PackageResource;
use App\Http\Resources\Api\v1\User\NotificationReasource;
use App\Http\Resources\Api\v1\User\NotificationResource;
use App\Http\Resources\Api\v1\User\ProfileResource;
use App\Http\Resources\Api\v1\User\WalletResource;
use App\Models\Delivery;
use App\Models\Notification;
use App\Models\Order;
use App\Models\User;
use App\Rules\EmailRule;
use App\Rules\IntroMobile;
use App\Rules\StartWith;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UserController extends Controller
{

    public function wallet(Request $request)
    {
        $user = apiUser();
        $package = $user->packages()->where('expired', false)->first();
        if ($user->type == User::DRIVER) {
            $walD = [
                'completed_deliveries' => $user->deliveries()->where('status', Delivery::ON_WAY)->count(),
                'total_rewards' => $user->deliveries()->where('status', Delivery::COMPLETED)->with(['order'])->get()->pluck('order')->sum('driver_slice'),
                'wallet_balance' => $user->user_wallet,
            ];
        } else {
            $walD = [
                'package' => isset($package) ? new PackageResource($user->packages()->where('expired', false)->first()) : null,
                'deliveries_balance' => isset($package) ? $package->delivery_number : 0,
                'deliveries_remaining' => isset($package) ? $package->delivery_number - $user->orders()->where('status', Order::ON_WAY)->count() : 0,
                'package_cost' => isset($package) ? $package->price : 0,
            ];
        }
        return apiSuccess([
            'wallet_details' => $walD,
            'wallet_transactions' => WalletResource::collection(apiUser()->wallets),
        ]);
    }

    public function notifications(Request $request)
    {
        if (isset($request->page)) {
            $notifications = Notification::where('notifiable_id', apiUser()->id)
                ->latest();
            $result = pagingResult($request, $notifications);
            $response['items'] = NotificationResource::collection($result['items']);
            $response['pagination'] = $result['pagination'];
        } else {
            $response = NotificationResource::collection(apiUser()->notifications);
        }
        return apiSuccess($response);
    }


    public function notification($id)
    {

        $user = apiUser();
        $notification = Notification::query()
            ->where(function ($query) use ($user) {
                $query->where('notifiable_id', $user->id)->orWhere('notifiable_id', 0);
            })
            ->find($id);

        if (!$notification) {
            return apiError(api('Notification Not Found'));
        }
        if (!$notification->seen && $notification->notifiable_id != 0) {
            $notification->update([
                'read_at' => now(),
            ]);
        }
        return apiSuccess([
            'item' => new NotificationReasource($notification),
            'unread_notifications' => $user->unread_notifications,
        ]);
    }

    public function profile()
    {
        $user = apiUser();
        $user['access_token'] = Str::substr(request()->header('Authorization'), 7);
        return $this->sendResponse(new ProfileResource($user));
    }

    public function updateProfile(Request $request)
    {
        $user = apiUser();
        $request->validate([
            'name' => 'required',
            'image' => 'sometimes|image',
            'phone' => ['sometimes', 'min:13', 'max:13', new StartWith('+9665'), new IntroMobile(), 'unique:users,phone,' . apiUser()->id . ',id,deleted_at,NULL'],
            'email' => ['sometimes', 'required', 'unique:users,email,' . $user->id . ',id,deleted_at,NULL', new EmailRule()],
            'gender' => 'required|in:' . MALE . ',' . FEMALE,
        ]);
        $old_phone = $user->phone;
        $new_phone = $request->get('phone');
        if ($request->hasFile('image')) $user->image = $this->uploadImage($request->file('image'), 'users');
        if (isset($request->phone) && ($old_phone != $new_phone)) {
            $SMS_code = generateCode();
            $SMS_code = CODE_FIXED;
//            TODO send verification code SMS
            $this->send_sms_message($new_phone, $SMS_code);
            $user->verified = false;
            $user->generatedCode = $SMS_code;
        }
        if (isset($request->email)) $user->email = $request->email;
        $user->gender = $request->gender;
        $user->save();
        $user['access_token'] = Str::substr(request()->header('Authorization'), 7);
        return apiSuccess(new ProfileResource($user), api('Profile Updated Successfully'));
    }

    public function updateLanguage(Request $request)
    {
        $request->validate([
            'language' => 'required|in:ar,en'
        ]);
        $user = apiUser();
        $user->update(['local' => $request->get('language')]);
        $user['access_token'] = Str::substr(request()->header('Authorization'), 7);
        return apiSuccess(new ProfileResource($user));
    }

    public function updateNotification(Request $request)
    {
        $request->validate([
            'notification' => 'required|in:1,0',
        ]);
        $user = apiUser();
        $user->update(['notification' => $request->get('notification')]);
        return apiSuccess(new ProfileResource($user));
    }

    public function updateMobile(Request $request)
    {
        $user = apiUser();
        $request->validate([
            'phone' => ['required', 'min:13', 'max:13', new StartWith('+9665'), new IntroMobile(), 'unique:users,phone,' . $user->id . ',id,deleted_at,NULL'],
        ]);
        $old_phone = $user->phone;
        $new_phone = $request->get('phone');
        if ($old_phone !== $new_phone) {
            $SMS_code = generateCode();
            $SMS_code = 1234;
//            TODO send verification code SMS
//            event(new SendSMSEvent($user->mobile, $SMS_code));
            $user->update([
                'phone' => $request->get('phone'),
                'verified' => false,
                'generatedCode' => $SMS_code,
            ]);
        } else {
            $user->update(['phone' => $request->get('phone')]);
        }

        return apiSuccess(new ProfileResource($user));
    }

    public function updateImage(Request $request)
    {
        $request->validate(['image' => 'required|image']);
        $user = apiUser();
        if ($request->hasFile('image')) $user->update(['image' => $this->uploadImage($request->file('image'), 'users')]);
        return apiSuccess(new ProfileResource($user), api('Image Updated Successfully'));
    }

    public function hide_vs_show(Request $request, $key = 'mobile')
    {
        $user = apiUser();
        if ($key == 'email') $user->update(['hide_email' => !$user->hide_email]);
        else $user->update(['hide_mobile' => !$user->hide_mobile]);
        return apiSuccess(new ProfileResource($user));
    }
}
