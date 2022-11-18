<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\ClientOrderRate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ClientOrderRateController extends Controller
{
    private $_model;

    public function __construct(ClientOrderRate $clientOrderRate)
    {
        parent::__construct();
        $this->_model = $clientOrderRate;
        $this->middleware('permission:Ratings', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $title = t('Stores Rates');
        if (request()->ajax()) {
            $uuid = $request->get('uuid', false);
            $username = $request->get('username', false);
            $user_mobile = $request->get('user_mobile', false);
            $branch = $request->get('branch', false);
            $date_start = $request->get('date_start', false);
            $date_end = $request->get('date_end', false);
            $user = $request->get('user', false);
//            $rates = $this->_model->get();
            $rates = $this->_model->whereHas('branch', function ($query) {
                        $query->whereHas('merchant', function ($query) {
                            $query->where('id', user('merchant')->id);
                        });
                    })//->dd()
                ->whereHas('order', function ($query) use ($uuid, $username, $user_mobile, $branch, $user) {
                    $query->when($uuid, function ($query) use ($uuid) {
                        $query->where('uuid', $uuid);
                    });
                    $query->when($username, function ($query) use ($username) {
                        $query->whereHas('user', function ($query) use ($username) {
                            $query->where('name->' . lang(), 'like', '%' . $username . '%');
                        });
                    });
                    $query->when($user_mobile, function ($query) use ($user_mobile) {
                        $query->whereHas('user', function ($query) use ($user_mobile) {
                            $query->where('phone', 'like', '%' . $user_mobile . '%');
                        });
                    });
                    $query->when($branch, function ($query) use ($branch) {
                        $query->where('branch_id', $branch);
                    });
                    $query->when($user, function ($query) use ($user) {
                        $query->where('user_id', $user);
                    });
                })
                ->when($date_start, function ($query) use ($date_start) {
                    $query->whereDate('created_at', '>=', Carbon::parse($date_start));
                })
                ->when($date_end, function ($query) use ($date_end) {
                    $query->whereDate('created_at', '<=', Carbon::parse($date_end));
                })
                ->latest('updated_at');

            return DataTables::make($rates)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->toDateTimeString();
                })
                ->addColumn('user', function ($row) {
                    return $row->order->user->name;
                })
                ->addColumn('branch', function ($row) {
                    return optional(optional($row->order)->branch)->name;
                })
                ->addColumn('actions', function ($row) {
                    return $row->action_buttons;
                })
                ->addColumn('uuid', function ($row) {
                    return optional($row->order)->uuid;
                })
                ->addColumn('comment', function ($row) {
                    return $row->content_rating;
                })
                ->addColumn('rate', function ($row) {
                    return $row->stars_number . '/5';
                })
                ->make();
        }
        $branches = Branch::currentMerchant(user('merchant')->id)->get();
        return view('restaurant.rate.client_order_rate', compact('title', 'branches'));
    }

    public function destroy($id)
    {
        $rate = ClientOrderRate::query()->findOrFail($id);
        $rate->delete();
        return redirect()->route('restaurant.client_order_rate.index')->with('m-class', 'success')->with('message', t('Successfully Deleted'));
    }
}
