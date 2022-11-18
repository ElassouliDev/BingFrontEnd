<?php

namespace App\Http\Controllers\Restaurant;

use App\Events\BranchNotificationEvent;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\BranchHour;
use App\Models\Item;
use App\Models\Notification;
use App\Models\Order;
use App\Models\User;
use App\Rules\EmailRule;
use App\Rules\IntroMobile;
use App\Rules\StartWith;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use Yajra\DataTables\DataTables;

class BranchController extends Controller
{
    private $_model;

    public function __construct(Branch $branch)
    {
        parent::__construct();
        $this->_model = $branch;
        $this->middleware('permission:Branches', ['only' => ['create', 'edit', 'destroy']]);
        foreach (config('translatable.locales') as $local) {
            $this->validationRules["name.$local"] = 'required';
        }
        $this->validationRules["phone"] = ['required', 'unique:branches,phone,NULL,id,deleted_at,NULL', 'min:13', 'max:13', new StartWith('+9665'), new IntroMobile()];
        $this->validationRules["email"] = ['required', 'unique:branches,email,NULL,id,deleted_at,NULL', new EmailRule()];
//        $this->validationRules["category_id"] = 'required|exists:categories,id';
        $this->validationRules["image"] = 'nullable|image';
        $this->validationRules["cover"] = 'nullable|image';
        $this->validationRules["dob"] = 'nullable|date_format:Y-m-d';
        $this->validationRules["gender"] = 'nullable|in:male,female';
        $this->validationRules["lat"] = 'nullable';
        $this->validationRules["lng"] = 'nullable';
        $this->validationRules["password"] = 'required|min:6';
        $this->validationRules["max_orders"] = 'required';
    }

    public function index()
    {
        $title = t('Show Branches');
        if (request()->ajax()) {
            $search = request()->get('search', false);
            $branches = $this->_model->where('merchant_id', user('merchant')->id)
                ->when($search, function ($query) use ($search) {
                    $query->where('phone', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhere('name->' . lang(), 'like', '%' . $search . '%');
                });

            return DataTables::make($branches)
                ->escapeColumns([])
                ->addColumn('name', function ($branch) {
                    return $branch->name;
                })
                ->addColumn('created_at', function ($branch) {
                    return Carbon::parse($branch->created_at)->toDateTimeString();
                })
                ->addColumn('actions', function ($branch) {
                    return $branch->action_buttons;
                })
                ->addColumn('restaurant', function ($branch) {
                    return optional($branch->merchant)->name;
                })
                ->addColumn('merchant_type', function ($branch) {
                    return optional(optional($branch->merchant)->merchantType)->name;
                })
                ->addColumn('mobile', function ($branch) {
                    return $branch->phone;
                })
                ->addColumn('email', function ($branch) {
                    return $branch->email;
                })
                ->addColumn('active', function ($branch) {
                    return $branch->status_name;
                })
                ->make();
        }
        return view('restaurant.branch.index', compact('title'));
    }


    public function change_drivers_code($id)
    {
        $merchant = Branch::findOrFail($id);
        $code = generateRandomString(5);
        while (Branch::where('drivers_code', $code)->count() > 0) {
            $code = generateRandomString(5);
        }
        $merchant->update([
            'drivers_code' => $code
        ]);
        return redirect()->route('restaurant.branch.show', $id)->with('m-class', 'success')->with('message', t('Successfully Updated'));
    }

    public function create()
    {
        $title = t('Add Branch');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        $days = [];
        foreach (work_hours() as $day) {
            $day['selected'] = false;
            array_push($days, $day);
        }
        return view('restaurant.branch.edit', compact('title', 'validator', 'days'));
    }


    public function edit($id)
    {
        $title = t('Edit Branch');
        $branch = $this->_model->findOrFail($id);

        $this->validationRules["phone"] = ['required', 'min:13', 'max:13', new StartWith('+966'), new IntroMobile(), 'unique:branches,phone,' . $branch->id . ',id,deleted_at,NULL'];
        $this->validationRules["email"] = ['required', 'unique:branches,email,' . $branch->id . ',id,deleted_at,NULL', new EmailRule()];
        $this->validationRules["password"] = 'nullable|min:6';
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        $branchDays = BranchHour::currentBranch($branch->id)->get();
        $branchDaysId = $branchDays->pluck('day')->all();
//        dd($branch,$branchDays,$branchDaysId);
        $days = [];
        foreach (work_hours() as $day) {
            $day['selected'] = in_array($day['num'], $branchDaysId) ? true : false;
            $day['from'] = in_array($day['num'], $branchDaysId) ? $branchDays->where('day', $day['num'])->first()->from : null;
            $day['to'] = in_array($day['num'], $branchDaysId) ? $branchDays->where('day', $day['num'])->first()->to : null;
            array_push($days, $day);
        }

        return view('restaurant.branch.edit', compact('title', 'branch', 'validator', 'days'));
    }


    public function store(Request $request)
    {

        if (isset($request->branch_id)) {
            $store = $this->_model->findOrFail($request->branch_id);
            $this->validationRules["phone"] = ['required', 'min:13', 'max:13', new StartWith('+966'), new IntroMobile(), 'unique:branches,phone,' . $store->id . ',id,deleted_at,NULL'];
            $this->validationRules["email"] = ['required', 'unique:branches,email,' . $store->id . ',id,deleted_at,NULL', new EmailRule()];
            $this->validationRules["password"] = 'nullable|min:6';
            $this->validationRules["image"] = 'nullable|image';
        } else {
            $store = new $this->_model();
            $code = generateRandomString(5);
            while (Branch::where('drivers_code', $code)->count() > 0) {
                $code = generateRandomString(5);
            }
            $store->drivers_code = $code;
        }


        $request->validate($this->validationRules);
//        dd($this->validationRules);

        $store->name = $request->name;
        if ($request->hasFile('image')) {
            $store->image = $this->uploadImage($request->file('image'), 'branches');
        }

        if ($request->hasFile('cover')) {
            $store->cover = $this->uploadImage($request->file('cover'), 'branches');
        }

//        dd(checkRequestIsWorkingOrNot());
        $active = $request->get('active', 0);
        $store->status = ($active) ? Branch::ACTIVE : Branch::NOT_ACTIVE;
        $store->name = $request->name;
        $store->address = $request->address;
        $store->merchant_id = user('merchant')->id;
        $store->phone = $request->phone;
        $store->email = $request->email;
        $store->lat = $request->lat;
        $store->lng = $request->lng;
        if (isset($request->password)) $store->password = Hash::make($request->password);

        $store->draft = $request->get('draft', 0);
        $store->busy = $request->get('busy', 0);
        $store->accepted = $request->get('accepted', 0);
        $store->save();
        $branch_permissions = BRANCH_PERMISSIONS;
        foreach ($branch_permissions as $permission) {
            if (!$store->can($permission)) $store->givePermissionTo($permission);
        }

        if ($request->has('day') && is_array($request->get('day')) && count($request->get('day')) > 0) {
            if (isset($request->branch_id)) $store->hours()->forcedelete();
            foreach ($request->get('day') as $day) {
                BranchHour::create([
                    'branch_id' => $store->id,
                    'day' => $day,
                    'from' => isset($request->get('from')[$day]) ? $request->get('from')[$day] : '0:00:00',
                    'to' => isset($request->get('to')[$day]) ? $request->get('to')[$day] : '0:00:00',
                ]);
            }
        }


        if (isset($request->branch_id)) {
            return redirect()->route('restaurant.branch.index')->with('m-class', 'success')->with('message', t('Successfully Updated'));
        } else {
            return redirect()->route('restaurant.branch.index')->with('m-class', 'success')->with('message', t('Successfully Created'));

        }
    }

    public function show($id)
    {
        $title = t('Show Branch');
        $branch = Branch::currentMerchant(user('merchant')->id)->findOrFail($id);
        $orderQuery = Order::query()->where('branch_id', $branch->id)->notPendingOrder()->notCompletedOrder();
        $total_orders = $orderQuery->sum('total_cost');
        $data['total_orders'] = $total_orders;
        $orders = Order::query()->where('branch_id', $branch->id)->notPendingOrder()->count();;
        $data['orders'] = $orders;

        $items = Item::where('branch_id', $branch->id)->count();
        $data['items'] = $items;
        $total_orders_commission = $orderQuery->sum('commission_cost');
        $total_orders_profits = $total_orders;
        $data['total_profits'] = $total_orders_profits - $total_orders_commission;
        $data['total_commission'] = $total_orders_commission;

        $this->validationRules["title"] = 'required';
        $this->validationRules["content"] = 'required';
        $this->validationRules["user_id"] = 'required';
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);

        return view('restaurant.branch.show', compact('branch', 'title', 'data', 'validator'));
    }


    public function destroy($id)
    {
        $branch = Branch::currentMerchant(user('merchant')->id)->findOrFail($id);
        if ($branch->orders()->count() > 0) {
            return redirect()->back()->with('m-class', 'error')->with('message', t('cannot delete branch it has orders'));
        }
        if ($branch->items()->count() > 0) {
            return redirect()->back()->with('m-class', 'error')->with('message', t('cannot delete branch it has meals'));
        }
        if ($branch->classifications->count() > 0) {
            return redirect()->back()->with('m-class', 'error')->with('message', t('cannot delete branch it has classifications'));
        }
        $branch->hours()->delete();
        $branch->favorites()->delete();
        $branch->delete();
        return redirect()->route('restaurant.branch.index')->with('m-class', 'success')->with('message', t('Successfully Deleted'));
    }

    public function branches(Request $request)
    {
        $branches = $this->_model->notDraft()->whereHas('merchant')->where('merchant_id', user('merchant')->id)->get();
        $html = '<option value="" disabled selected>' . t('Select Branches') . '</option>';
        foreach ($branches as $branch) {
            $html .= '<option value="' . $branch->id . '">' . $branch->name . '</option>';
        }
        return response()->json(['html' => $html]);
    }

    public function orderBranches(Request $request)
    {
        $branches = $this->_model->whereHas('merchant', function ($query) use ($request) {
            $query->where('id', $request->get('id'));
        })->get();
        $html = '<option value="" selected>' . t('Select Branch') . '</option>';
        foreach ($branches as $branch) {
            $html .= '<option value="' . $branch->id . '">' . $branch->name . '</option>';
        }
        return response()->json(['html' => $html]);
    }

    public function goBranch($id)
    {
        $branch = Branch::query()->whereHas('user')->findOrFail($id);
        Auth::guard('web')->login($branch->user);
        return redirect()->to('/branch/home');
    }

    public function sendNotification(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'branch_id' => 'required',
        ]);
        $branch = Branch::currentMerchant(user('merchant')->id)->find($request->get('branch_id'));
        if ($branch) {
            event(new BranchNotificationEvent($branch, $request->get('title'), $request->get('content')));
            return redirect()->back()->with('m-class', 'success')->with('message', t('Notification Successfully Sent'));
        }
        return redirect()->back()->with('m-class', 'error')->with('message', t('Branch Not Found'));

    }

    public function notifications()
    {
        $title = t('Show Notifications');
        if (request()->ajax()) {

            $user = request()->get('user', false);

            $notifications = Notification::query()->when($user, function ($query) use ($user) {
                $query->where('notifiable_id', $user)->orWhere('notifiable_id', 0);
            })->whereNotIn('type', [RestaurantNotification::class, UsersNotification::class])
                ->latest();

            return DataTables::make($notifications)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->toDateTimeString();
                })
                ->addColumn('content', function ($row) {
                    return $row->content;
                })
                ->addColumn('title', function ($row) {
                    return $row->title;
                })
                ->addColumn('actions', function ($row) {
                    return $row->action_buttons;
                })
                ->make();
        }
        //return view('restaurant.notification.index', compact('title'));
    }


}
