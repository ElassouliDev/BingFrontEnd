<?php

namespace App\Http\Controllers\Api\v1\Employee;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\v1\OrderResource;
use App\Http\Resources\Api\v1\User\ProfileResource;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderStatusTimeLine;
use App\Models\User;
use App\Models\RateOrder;
use App\Notifications\RateOrderNotification;
use App\Rules\IntroMobile;
use App\Rules\StartWith;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{

    public function orders(Request $request, $key = 'current')
    {
        $request['except_arr_resource'] = ['items'];
        $user = apiUser();
        $search = $request->get('search', false);
        $orders = Order::query()->currentBranch($user->id);
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

    public function check_order(Request $request)
    {
        //        validations
        $request->validate(['phone' => ['required', 'min:13', 'max:13', new StartWith('+9665'), new IntroMobile()],]);
        $user = User::where('phone', $request->phone)->first();
        if (!isset($user)) return apiError(api('Wrong phone number'));
        $order = Order::where('all_order_object_filled_out', false)->first();
        if (!isset($order)) {
            $last_id = Order::withoutGlobalScope('orderedBy')->get()->last();//because they are ordered by updated at
            $last_id = isset($last_id) ? ($last_id->id + 1) : 1;
            $order = Order::create([
                'uuid' => apiUser()->branch_id . '-' . date('Y') . date('m') . $last_id,
                'branch_id' => apiUser()->branch_id,
                'user_id' => $user->id,
                'employee_id' => apiUser()->id,
                'status' => Order::status['WORKING'],
                'all_order_object_filled_out' => false,
            ]);
            $statusTimeLine = OrderStatusTimeLine::create([
                'order_id' => $order->id,
                'key' => Order::status['WORKING'],
                'key_name' => [
                    'ar' => api('Order Working', [], 'ar'),
                    'en' => api('Order Working', [], 'en'),
                ],
                'date' => Carbon::now()
            ]);
        }
        return apiSuccess([
            'id' => $order->id,
            'order_uuid' => '#' . $order->uuid,
            'user' => new ProfileResource($user),
        ]);
    }

    public function checkout_order(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        if (isset($request->attach_invoice)) $order->attach_invoice = $this->uploadImage($request->attach_invoice, 'orders');
        $order->pick_up_time = $request->pick_up_time;
        $order->note = $request->note;
        $order->all_order_object_filled_out = true;
        $total = 0;
        foreach ($request->meal as $index => $item) {
            $meal = Item::where('branch_id', $order->branch_id)->find($index);
            if (!isset($meal)) return apiError(api('Wrong meal id'));
            $order_item = $order->order_items()->create([
                'item_id' => $meal->id,
                'quantity' => $item['quantity'],
                'amount' => ($meal->price * $item['quantity']),
            ]);
            $total += $order_item->amount;
        }
        $order->total_cost = $total;
        $order->meals_cost = $total;
        $order->save();
        return apiSuccess(new OrderResource($order));
    }
}
