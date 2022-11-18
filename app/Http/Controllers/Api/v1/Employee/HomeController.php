<?php

namespace App\Http\Controllers\Api\v1\Employee;

use App\Events\DriverAcceptOrderEvent;
use App\Events\DriverCanceledOrderEvent;
use App\Events\DriverCompletedOrderEvent;
use App\Events\DriverOnWayOrderEvent;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\v1\Driver\DeliveryResource;
use App\Http\Resources\Api\v1\User\ProfileResource;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home(Request $request)
    {
        $user = apiUser();
        return apiSuccess([
            'today_orders' => Order::currentBranch($user->branch_id)->count(),
            'this_week_orders' => Order::currentBranch($user->branch_id)->where('pick_up_time', '>=', now()->startOfWeek())->where('pick_up_time', '<=', now()->endOfWeek())->count(),
            'working_orders' => Order::currentBranch($user->branch_id)->working()->count(),
            'ready_orders' => Order::currentBranch($user->branch_id)->ready()->count(),
            'late_orders' => Order::currentBranch($user->branch_id)->late()->count(),
            'complete_orders' => Order::currentBranch($user->branch_id)->completed()->count(),
        ]);
    }
}
