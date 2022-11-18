<?php

namespace App\Http\Controllers\Branch;

use App\Events\AcceptOrderEvent;
use App\Events\CancelOrderEvent;
use App\Events\OnProgressOrderEvent;
use App\Events\ReadyOrderEvent;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Order;
use App\Models\User;
use App\Notifications\ReadyOrderNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class OrderController extends Controller
{
    private $_model;

    public function __construct(Order $order)
    {
        parent::__construct();
        $this->_model = $order;
        $this->middleware('permission:Orders', ['only' => ['show', 'changeStatus', 'destroy']]);
    }

    public function index(Request $request)
    {
        $title = t('Show Orders');
        $uuid = $request->get('uuid', false);
        $username = $request->get('username', false);
        $user_mobile = $request->get('user_mobile', false);
        $user = $request->get('user', false);
        $status = $request->get('status', false);
        $date_start = $request->get('date_start', false);
        $date_end = $request->get('date_end', false);
        $price_start = $request->get('price_start', false);
        $price_end = $request->get('price_end', false);

        $orders = $this->_model
            ->where('branch_id', user('branch')->id)
            ->when($uuid, function ($query) use ($uuid) {
                $query->where('uuid', $uuid);
            })
            ->when($username, function ($query) use ($username) {
                $query->whereHas('user', function ($query) use ($username) {
                    $query->where('name->' . lang(), 'like', '%' . $username . '%');
                });
            })
            ->when($user_mobile, function ($query) use ($user_mobile) {
                $query->whereHas('user', function ($query) use ($user_mobile) {
                    $query->where('phone', 'like', '%' . $user_mobile . '%');
                });
            })
            ->when($user, function ($query) use ($user) {
                $query->where('user_id', $user);
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($date_start, function ($query) use ($date_start) {
                $query->whereDate('created_at', '>=', Carbon::parse($date_start));
            })
            ->when($date_end, function ($query) use ($date_end) {
                $query->whereDate('created_at', '<=', Carbon::parse($date_end));
            })
            ->when($price_start, function ($query) use ($price_start) {
                $query->where('total_cost', '>=', $price_start);
            })
            ->when($price_end, function ($query) use ($price_end) {
                $query->where('total_cost', '<=', $price_end);
            });
        if (request()->ajax()) {
            return DataTables::make($orders)
                ->escapeColumns([])
                ->addColumn('created_at', function ($order) {
                    return Carbon::parse($order->created_at)->toDateTimeString();
                })
                ->addColumn('pick_up_time', function ($order) {
                    return Carbon::parse($order->pick_up_time)->toDateTimeString();
                })
                ->addColumn('user', function ($order) {
                    return $order->user->name;
                })
                ->addColumn('merchant', function ($order) {
                    return optional(optional($order->branch)->merchant)->name;
                })
                ->addColumn('branch', function ($order) {
                    return $order->branch->name;
                })
                ->addColumn('status_name', function ($order) {
                    return $order->status_name . ' ' . ($order->user_cancel ? t(' User Cancel') : '');
                })
                ->addColumn('paid_type', function ($order) {
                    return $order->paid_type_name;
                })
                ->addColumn('total', function ($order) {
                    return $order->total_cost;
                })
                ->addColumn('actions', function ($order) {
                    return $order->action_buttons;
                })
                ->addColumn('show_action', function ($order) {
                    return '<a href="' . route('branch.order.show', $order->id) . '" class="btn btn-icon btn-danger "><i class="la la-eye"></i></a> ';
                })
                ->make();
        }
        $total = $orders->sum('total_cost');
        return view('branch.order.index', compact('title', 'total'));
    }

    public function show($id)
    {
        $title = t('Show Order') . ' #' . $id;
        $order = Order::query()->findOrFail($id);
        return view('branch.order.show', compact('order', 'title'));
    }

    public function changeStatus(Request $request, $id)
    {
        $order = Order::with(['branch'])->findOrFail($id);
        $request->validate([
            'status' => 'required|in:' . Order::ACCEPTED . ',' . Order::ON_PROGRESS . ',' . Order::READY . ',' . Order::CANCELED,
        ]);
        $order->update([
            'status' => $request->get('status'),
        ]);
//        TODO send notification to user and drivers
        switch ($order->status) {
            case Order::ACCEPTED:
                event(new AcceptOrderEvent($order));
                break;
            case Order::ON_PROGRESS:
                event(new OnProgressOrderEvent($order));
                break;
            case Order::READY:
                event(new ReadyOrderEvent($order));

                break;
            case Order::CANCELED:
                event(new CancelOrderEvent($order));
                break;
        }
        return redirect()->back()->with('message', t('Successfully Updated'))->with('m-class', 'success');
    }

    public function destroy($id)
    {
        $order = Order::query()->findOrFail($id);
//        $order->delete();
        return redirect()->route('branch.order.index')->with('message', t('Successfully Deleted'))->with('m-class', 'success');
    }

    public function sendNotification(Request $request, $id)
    {
        $order = Order::where('branch_id', user('branch')->id)->findOrFail($id);
        $branch = user('branch');
        $request->validate([
            'recipients' => 'required|in:' . ALL_DRIVERS . ',' . DRIVERS_FOLLOWED_TO_RESTAURANT,
        ]);
        $items = null;
        switch ($request->recipients) {
            case DRIVERS_FOLLOWED_TO_RESTAURANT:
                $items = User::driver()->currentBranch($branch->id)->get();
                break;
            case ALL_DRIVERS:
                $items = User::driver()->get();
                break;
            default:
                $items = User::driver()->get();
                break;
        }
        \Illuminate\Support\Facades\Notification::send($items, new ReadyOrderNotification($order));
        return redirect()->back()->with('message', t('Notification Sent Successfully'))->with('m-class', 'success');
    }
}
