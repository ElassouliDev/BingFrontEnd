<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\v1\PackageResource;
use App\Http\Resources\Api\v1\User\AddressResource;
use App\Models\Address;
use App\Models\Order;
use App\Models\Package;
use App\Models\User;
use App\Models\UserPackage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PackagesController extends Controller
{
    public function packages(Request $request)
    {
//        $user = apiUser();
//        TODO check this request is valid or not
        $user = User::client()
            ->where('id', apiUser()->id)
            ->with(['orders' => function ($query) {
                return $query->where('status', Order::ACCEPTED);
            }, 'packages' => function ($query) {
                return $query->where('expired', false);
            }])->first();
        $package = $user->packages->first();
        $res = [];
        if (isset($package)) {
            $package['get_remaining_number'] = true;
            $package['get_user'] = $user;
            $res ['user_package'] = new PackageResource($package);
        } else {
            $res ['user_package'] = null;
        }
        $res ['packages'] = PackageResource::collection(Package::get());
        return apiSuccess($res);
    }

    public function buy_package(Request $request)
    {
        $request->validate([
            'package_id' => 'required|exists:packages,id',
        ]);
        $user = apiUser();
        if ($user->packages()->where('expired', false)->count() > 0) return apiError(t('can not buy package you have another one'));
        $package = Package::findOrFail($request->package_id);
        $newExpireDate = Carbon::now()->addMonths($package->months)->format(DATE_FORMAT);
        UserPackage::create([
            'user_id' => $user->id,
            'package_id' => $request->package_id,
            'expired' => false,
            'expire_date' => $newExpireDate,
        ]);
        return apiSuccess(new PackageResource($package), t('Package Bought Successfully'));
    }
}
