<?php

namespace App\Http\Controllers\Api\v1;

use App\Events\NewOrderEvent;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\v1\CouponResource;
use App\Http\Resources\Api\v1\ItemReOrderResource;
use App\Http\Resources\Api\v1\OrderResource;
use App\Models\Branch;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusTimeLine;
use App\Models\RateOrder;
use App\Models\Wallet;
use App\Notifications\RateOrderNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{

    public function orders(Request $request, $key = 'current')
    {
        $request['except_arr_resource'] = ['items'];
        $user = apiUser();
        $search = $request->get('search', false);
        $orders = Order::query()->where('user_id', $user->id);
        switch ($key) {
            case 'history':
                $orders = $orders->completed()->when($search, function ($query) use ($search) {
                    $query->where('uuid', 'like', '%' . $search . '%');
                })->paginate($this->perPage);
                return apiSuccess([
                    'items' => OrderResource::collection($orders->items()),
                    'paginate' => paginate($orders),
                ]);
                break;
            case 'archived':
                $orders = $orders->completed()->where('updated_at', '<=', now()->subWeek()->endOfWeek())->when($search, function ($query) use ($search) {
                    $query->where('uuid', 'like', '%' . $search . '%');
                })->paginate($this->perPage);
                return apiSuccess([
                    'items' => OrderResource::collection($orders->items()),
                    'paginate' => paginate($orders),
                ]);
                break;
            default:
                $orders = $orders->notCompleted()->when($search, function ($query) use ($search) {
                    $query->where('uuid', 'like', '%' . $search . '%');
                });
                switch ($request->filter) {
                    case 'closest_order':
                        $orders = $orders->where('pick_up_time', Carbon::today());
                        break;
                    case 'this_week_orders':
                        $orders = $orders->where('pick_up_time', '>', now()->startOfWeek())
                            ->where('pick_up_time', '<', now()->endOfWeek());
                        break;
                    case 'this_month_orders':
                        $orders = $orders->where('pick_up_time', '>', now()->startOfMonth())
                            ->where('pick_up_time', '<', now()->endOfMonth());
                        break;
                    case 'ready_orders':
                        $orders = $orders->ready();
                        break;
                }
                $orders = $orders->paginate($this->perPage);
                return apiSuccess([
                    'items' => OrderResource::collection($orders->items()),
                    'paginate' => paginate($orders),
                ]);
        }
    }

    public function order(Request $request, $id)
    {
        $request['except_arr_resource'] = ['items'];
        $user = apiUser();
        $order = Order::query()->where('user_id', $user->id)->findOrFail($id);
        return apiSuccess(new OrderResource($order));
    }

    public function rate(Request $request, $id)
    {
        $request['except_arr_resource'] = ['items'];
        $request->validate([
            'rate' => 'required|in:1,2,3,4,5',
            'comment' => Rule::requiredIf(function () use ($request) {
                return in_array($request->get('rate'), [1, 2, 3]);
            }),
        ]);
        $user = apiUser();
        $order = Order::findOrFail($id);
        if ($order->isRated) return apiError(api('Order Rated Previously'), 422);
        if ($order->status != Order::status['COMPLETED']) return apiError(api('The status of the request does not allow it to be evaluated'), 422);
        $store = new RateOrder();
        $store->user_id = apiUser()->id;
        $store->order_id = $order->id;
        $store->branch_id = $order->branch_id;
        $store->stars_number = $request->get('rate');
        $store->content_rating = $request->get('comment');
        $store->save();
        $rates = RateOrder::query()->where('order_id', $order->id)->avg('stars_number');
        optional($order)->branch->update(['rate' => (float)$rates,]);
        $order->update(['isRated' => true]);
        Notification::send($order->branch, new RateOrderNotification($order));
        return apiSuccess(new OrderResource($order), api('User Rated Successfully'));

    }
}
