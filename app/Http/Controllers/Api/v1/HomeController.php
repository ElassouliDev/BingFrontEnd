<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\v1\BranchPointsPrivacyResource;
use App\Http\Resources\Api\v1\BranchResource;
use App\Http\Resources\Api\v1\BranchWithWorkHrsResource;
use App\Http\Resources\Api\v1\OfferResource;
use App\Http\Resources\Api\v1\OrderResource;
use App\Http\Resources\Api\v1\RewardResource;
use App\Http\Resources\Api\v1\User\MerchantTypeResource;
use App\Models\Branch;
use App\Models\ContactUs;
use App\Models\MerchantType;
use App\Models\Offer;
use App\Models\Order;
use App\Models\Reward;
use App\Models\User;
use App\Rules\EmailRule;
use App\Rules\IntroMobile;
use App\Rules\StartWith;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function privacy_policy(Request $request)
    {
        return apiSuccess([
            'privacy_policy' => optional(setting('privacy_policy'))[lang()],
        ]);
    }

    public function contactUs(Request $request)
    {
        $request->validate([
            'name' => 'required|max:250',
            'email' => ['required', 'email', 'max:50', new EmailRule()],
            'mobile' => ['required', 'min:13', 'max:13', new StartWith('+966'), new IntroMobile()],
            'message' => 'required',
        ]);

        $data = $request->only(['name', 'email', 'mobile', 'message']);
        $data['source'] = 'Mobile App';
        $contact = ContactUs::create($data);
//        TODO send notification to manager
//        Notification::send(Manager::query()->get(), new ContactUsNotification($contact));
        return apiSuccess(null, api('Message Sent Successfully'));
    }

    public function settings()
    {

        return apiSuccess([
            'order_status' => Order::status,
            'user_types' => User::type,
            'merchants_range' => (integer)setting('merchants_range'),
            'name' => optional(setting('name'))[lang()],
            'email' => setting('email'),
            'mobile' => setting('mobile'),
            'logo' => setting('logo'),
            'whatsApp' => setting('whatsApp'),
            'facebook' => setting('facebook'),
            'twitter' => setting('twitter'),
            'instagram' => setting('instagram'),
            'android_url' => setting('android_url'),
            'ios_url' => setting('ios_url'),
            'about_us' => optional(setting('about_us'))[lang()],
        ]);
    }

    public function offers(Request $request)
    {
        $near_by_offers = Offer::whereHas('branch', function ($query) {
//            $query->nearest();
        })->get();
        return apiSuccess(OfferResource::collection($near_by_offers));
    }

    public function nearByMerchants(Request $request)
    {
        $near_by_merchants = Branch::
//        merchantType($request->merchant_type_id)
//            ->nearest()
//            ->
        get();
        return apiSuccess(BranchResource::collection($near_by_merchants));
    }

    public function category_details(Request $request)
    {
        $request->validate([
            'merchant_type_id' => 'required|exists:merchant_types,id'
        ]);
        $my_orders = Order::currentUser(apiUser()->id)->merchantType($request->merchant_type_id)->workingOrder()->get();
        $near_by_merchants = Branch::merchantType($request->merchant_type_id)->nearest()->get();
        $my_rewards = Reward::merchantType($request->merchant_type_id)->get();
        return apiSuccess([
            'my_orders' => OrderResource::collection($my_orders),
            'my_rewards' => RewardResource::collection($my_rewards),
            'near_by_merchants' => BranchResource::collection($near_by_merchants),
        ]);
    }

    public function home(Request $request)
    {
        $request['except_arr_resource'] = ['items'];
        $my_orders = Order::currentUser(apiUser()->id)->workingOrder()->get();
        $merchant_types = MerchantType::whereHas('merchants')->get();
        $my_rewards = Reward::get();
        $near_by_offers = Offer::get();
        $near_by_merchants = Branch::nearest()->get();
        return apiSuccess([
            'merchant_types' => MerchantTypeResource::collection($merchant_types),
            'my_orders' => OrderResource::collection($my_orders),
            'my_rewards' => RewardResource::collection($my_rewards),
            'near_by_offers' => OfferResource::collection($near_by_offers),
            'near_by_merchants' => BranchResource::collection($near_by_merchants),
        ]);
    }

    public function offer(Request $request, $id)
    {
        $offer = Offer::findOrFail($id);
        return apiSuccess(new OfferResource($offer));
    }

    public function collect_reward(Request $request, $branch_id, $reward_id)
    {
//        TODO this will done from merchant dashboard
        $reward = Reward::where('branch_id', $branch_id)->findOrFail($reward_id);
        if (isset($reward->user_id)) return apiError(api('This reward was collected'));
        $user_points_obj = optional(apiUser()->getPoint($branch_id));
        $user_points = optional($user_points_obj)->points;
        if ($reward->points >= $user_points) return apiError(api('There is no enough points'));
        $reward->update(['user_id' => apiUser()->id]);
        optional($user_points_obj)->update(['points' => ($user_points - $reward->points)]);
        return apiSuccess(new RewardResource($reward), api('Reward Collected Successfully'));
    }

    public function branch(Request $request, $id, $key = 'details')
    {
        $branch = Branch::findOrFail($id);
        switch ($key) {
            case'rewards':
                return apiSuccess(RewardResource::collection($branch->rewards()
                    ->whereNull('user_id')
                    ->where('points', '<=', optional($branch->point)->points)->get()));
            case'orders':
                return apiSuccess(OrderResource::collection(Order::where('branch_id', $branch->id)->where('user_id', apiUser()->id)->get()));
            case'offers':
                return apiSuccess(OfferResource::collection($branch->offers));
            case'points_privacy':
                $item = $branch->pointsPrivacy;
                return apiSuccess(isset($item) ? new BranchPointsPrivacyResource($item) : null);
            default:
                return apiSuccess(new BranchWithWorkHrsResource($branch));

        }
    }
}
