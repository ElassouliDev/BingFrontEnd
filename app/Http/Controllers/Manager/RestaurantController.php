<?php

namespace App\Http\Controllers\Manager;

use App\Events\RestaurantNotificationEvent;
use App\Http\Controllers\Controller;
use App\Models\AgentTapFile;
use App\Models\Bank;
use App\Models\Branch;
use App\Models\City;
use App\Models\Merchant;
use App\Models\MerchantType;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Provider;
use App\Models\User;
use App\Notifications\BranchNotification;
use App\Notifications\RestaurantNotification;
use App\Notifications\UsersNotification;
use App\Rules\EmailRule;
use App\Rules\IntroMobile;
use App\Rules\StartWith;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use Yajra\DataTables\DataTables;

class RestaurantController extends Controller
{
    private $_model;

    public function __construct(Merchant $merchant)
    {
        parent::__construct();
        $this->_model = $merchant;
        $this->middleware('permission:Restaurants', ['only' => ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']]);
        foreach (config('translatable.locales') as $local) {
            $this->validationRules["name.$local"] = 'required';
        }
        $this->validationRules["phone"] = ['required', 'min:13', 'max:13', new StartWith('+966'), new IntroMobile(), 'unique:merchants,phone,{$id},id,deleted_at,NULL'];
        $this->validationRules["email"] = ['required', 'unique:merchants,email,{$id},id,deleted_at,NULL', new EmailRule()];
        $this->validationRules["merchant_type_id"] = 'required|exists:merchant_types,id';
        $this->validationRules["image"] = 'nullable|image';
        $this->validationRules["lat"] = 'nullable';
        $this->validationRules["lng"] = 'nullable';
        $this->validationRules["password"] = 'required|min:6';
        $this->validationRules['id_no'] = 'required|numeric|digits:10';
        $this->validationRules['id_file'] = 'nullable';
        $this->validationRules['comm_registration_no'] = 'required';
        $this->validationRules['comm_registration_file'] = 'nullable';
        $this->validationRules['bank_id'] = 'required|exists:banks,id';
        $this->validationRules['i_ban'] = ['required', new StartWith('SA'), 'min:24', 'max:24'];

    }

    public function index()
    {
        $title = t('Show Restaurants');
        if (request()->ajax()) {
            $merchants = $this->_model->with(['merchantType']);
            $search = request()->get('search', false);
            $name = request()->get('name', false);
            $merchant_type = request()->get('merchant_type', false);
            $merchants = $merchants
                ->when($search, function ($query) use ($search) {
                    $query->where('name->' . lang(), 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhere('phone', 'like', '%' . $search . '%');
                })
                ->when($name, function ($query) use ($name) {
                    $query->where('name->' . lang(), 'like', '%' . $name . '%')
                        ->orWhere('email', 'like', '%' . $name . '%')
                        ->orWhere('phone', 'like', '%' . $name . '%');
                })
                ->when($merchant_type, function ($query) use ($merchant_type) {
                    $query->where('merchant_type_id', $merchant_type);
                });
            return DataTables::make($merchants)
                ->escapeColumns([])
                ->addColumn('name', function ($merchant) {
                    return $merchant->name;
                })
                ->addColumn('mobile', function ($merchant) {
                    return $merchant->phone;
                })
                ->addColumn('email', function ($merchant) {
                    return $merchant->email;
                })
                ->addColumn('merchant_type', function ($merchant) {
                    return optional($merchant->merchantType)->name;
                })
                ->addColumn('active', function ($merchant) {
                    return $merchant->status_name;
                })
                ->addColumn('created_at', function ($merchant) {
                    return Carbon::parse($merchant->created_at)->toDateTimeString();
                })
                ->addColumn('actions', function ($merchant) {
                    if (!$merchant->tap_activate) {
                        $actions = $merchant->action_buttons . ' <button type="button" data-id="' . $merchant->id . '" data-toggle="modal" data-target="#activateModel" class="activateRecord btn  btn-danger">' . t('Activate Payments') . '</button>';
                    } else {
                        $actions = $merchant->action_buttons;
                    }
                    return $actions;
                })
                ->make();

        }
        $merchant_types = MerchantType::get();
        return view('manager.restaurant.index', compact('title', 'merchant_types'));
    }


    public function create()
    {
        $title = t('Add Restaurant');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        $merchant_types = MerchantType::query()->get();
        $banks = Bank::query()->get();
        $citites = City::query()->get();
        return view('manager.restaurant.edit', compact('title', 'validator', 'merchant_types', 'banks', 'citites'));
    }

    public function edit($id)
    {
        $title = t('Edit User');
        $merchant = $this->_model->findOrFail($id);
//        dd($merchant);
        $this->validationRules["phone"] = ['required', 'min:13', 'max:13', new StartWith('+966'), new IntroMobile(), 'unique:merchants,phone,' . $merchant->id . ',id,deleted_at,NULL'];
        $this->validationRules["email"] = ['required', 'unique:merchants,email,' . $merchant->id . ',id,deleted_at,NULL', new EmailRule()];
        $this->validationRules["password"] = 'nullable|min:6';
        $this->validationRules["image"] = 'nullable|image';
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        $merchant_types = MerchantType::query()->get();
        $banks = Bank::query()->get();
        $citites = City::query()->get();

        return view('manager.restaurant.edit', compact('title', 'merchant', 'validator', 'merchant_types', 'banks', 'citites'));
    }


    public function store(Request $request)
    {
        if (isset($request->merchant_id)) {
            $store = $this->_model->findOrFail($request->merchant_id);
            $this->validationRules["phone"] = ['required', 'min:13', 'max:13', new StartWith('+966'), new IntroMobile(), 'unique:merchants,phone,' . $store->id . ',id,deleted_at,NULL'];
            $this->validationRules["email"] = ['required', 'unique:merchants,email,' . $store->id . ',id,deleted_at,NULL', new EmailRule()];
            $this->validationRules["password"] = 'nullable|min:6';
            $this->validationRules["image"] = 'nullable|image';
        } else {
            $store = new $this->_model();
        }

        $request->validate($this->validationRules);


        $store->owner_name = $request->owner_name;
        $store->name = $request->name;
        $store->merchant_type_id = $request->merchant_type_id;
        $store->phone = $request->phone;
        $store->email = $request->email;

        if (isset($request->merchant_id)) {
            $store->password = !is_null($request->get('password')) ? bcrypt($request->get('password', 123456)) : $store->password;
        } else {
            $store->password = Hash::make($request->password);
        }
        $active = $request->get('active', 0);
        $store->status = ($active) ? Merchant::ACTIVE : Merchant::NOT_ACTIVE;
        $store->draft = $request->get('draft', 0);
        $store->id_no = $request->id_no;
//        send notification to restaurant if accepted
        $store->accepted = true;// $request->get('accepted', 0);
        $store->bank_id = $request->bank_id;
        $store->city_id = $request->city_id;
        $store->i_ban = $request->i_ban;
        $store->swift_code = $request->swift_code;
        $store->comm_registration_no = $request->comm_registration_no;

        if ($request->hasFile('image')) {
            $store->image = $this->uploadImage($request->file('image'), 'restaurants');
        }
        if ($request->hasFile('id_file')) {
            $store->id_file = $this->uploadImage($request->file('id_file'), 'restaurants');
        }
        if ($request->hasFile('comm_registration_file')) {
            $store->comm_registration_file = $this->uploadImage($request->file('comm_registration_file'), 'restaurants');
        }
        $store->save();
        if ($request->hasFile('id_file')) {
            $this->uploadAgentFileFromRequest($store, $store->id_file, "identity_document");
        }
        if ($request->hasFile('comm_registration_file')) {
            $this->uploadAgentFileFromRequest($store, $store->comm_registration_file, 'tax_document_user_upload');
        }

        $branch_permissions = MERCHANT_PERMISSIONS;
        foreach ($branch_permissions as $permission) {
            if (!$store->can($permission)) $store->givePermissionTo($permission);
        }
        if (isset($request->merchant_id)) {
            return redirect()->route('manager.restaurant.index')->with('m-class', 'success')->with('message', t('Successfully Updated'));
        } else {

            return redirect()->route('manager.restaurant.index')->with('m-class', 'success')->with('message', t('Successfully Created'));
        }
    }

    public function show($id)
    {
        $title = t('Show Restaurant');
        $merchant = $this->_model->findOrFail($id);
        $orderQuery = new Order();
        $orderQuery = $orderQuery->currentMerchant($merchant->id)->notPendingOrder()->notCompletedOrder();

        $total_orders_commission = $orderQuery->sum('commission_cost');
        $total_orders_profits = $orderQuery->sum('total_cost');

        $total_orders_wallet = $orderQuery->where('paid_type', 'wallet')->sum('total_cost');

        $total_payments = Payment::query()->where('merchant_id', $merchant->id)->sum('amount');
//dd($total_orders_commission,$total_orders_profits,$total_orders_wallet);

        $data['balance'] = $total_payments - $total_orders_wallet;
        $data['total_orders'] = $total_orders_profits;
        $data['total_profits'] = $total_orders_profits - $total_orders_commission;
        $data['total_commission'] = $total_orders_commission;

        $orders = $orderQuery->count();
        $data['orders'] = $orders;

        $branchQuery = new Branch();
        $branchQuery = $branchQuery->currentMerchant($merchant->id);
        $rate = $branchQuery->avg('rate');
        $branches = $branchQuery->count();
        $data['rate'] = $rate;
        $data['branches'] = $branches;

        $this->validationRules["title"] = 'required';
        $this->validationRules["content"] = 'required';
        $this->validationRules["user_id"] = 'required';
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);

        return view('manager.restaurant.show', compact('merchant', 'title', 'data', 'validator'));
    }

    public function destroy($id)
    {
        $merchant = $this->_model->findOrFail($id);
        if ($merchant->payments()->count()) {
            return redirect()->back()->with('m-class', 'error')->with('message', t('cannot delete restaurant it has payments'));
        }
        if ($merchant->branches()->count()) {
            return redirect()->back()->with('m-class', 'error')->with('message', t('cannot delete restaurant it has branches'));
        }
        $merchant->delete();
        return redirect()->route('manager.restaurant.index')->with('m-class', 'success')->with('message', t('Successfully Deleted'));
    }

    public function goRestaurant($id)
    {
        $restaurant = Merchant::findOrFail($id);
        Auth::guard('merchant')->login($restaurant);
        return redirect()->to('/restaurant/home');
    }

    public function sendNotification(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'user_id' => 'required',
        ]);
        $merchant = Merchant::findOrFail($request->get('user_id'));
        if ($merchant) {
            event(new RestaurantNotificationEvent($merchant, $request->get('title'), $request->get('content')));
            return redirect()->back()->with('m-class', 'success')->with('message', t('Notification Successfully Sent'));
        }
        return redirect()->back()->with('m-class', 'error')->with('message', t('Restaurant Not Found'));

    }

    public function notifications()
    {
        $title = t('Show Notifications');
        if (request()->ajax()) {

            $user = request()->get('user', false);

            $notifications = Notification::query()->when($user, function ($query) use ($user) {
                $query->where('notifiable_id', $user)->orWhere('notifiable_id', 0);
            });

            return DataTables::make($notifications)
                ->escapeColumns([])
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->toDateTimeString();
                })
                ->addColumn('content', function ($row) {
                    return $row->body;
                })
                ->addColumn('title', function ($row) {
                    return $row->title;
                })
                ->addColumn('actions', function ($row) {
                    return $row->action_buttons;
                })
                ->make();
        }
        //return view('manager.notification.index', compact('title'));
    }

    public function activateRestaurant($id)
    {
        $merchant = Merchant::query()->findOrFail($id);

        if ($merchant->tap_activate)
            return redirect()->back()->with('message', t('Account Activated Previously'))->with('m-class', 'error');

        if (is_null($merchant->bank_id) || is_null($merchant->i_ban))
            return redirect()->back()->with('message', t('Please add bank information'))->with('m-class', 'error');

        $tap_file_identify = AgentTapFile::query()->where('merchant_id', $id)->where('file_type', 'identity_document')->latest('id')->first();
        $tap_file_tax_document = AgentTapFile::query()->where('merchant_id', $id)->where('file_type', 'tax_document_user_upload')->latest('id')->first();

        if (!$tap_file_identify || !$tap_file_tax_document)
            return redirect()->back()->with('message', t('Please adjust the ID card and CR file settings'))->with('m-class', 'error');

        $this->createAgentBusinessAccount($merchant, $tap_file_identify, $tap_file_tax_document);

        if ($merchant->tap_activate == 0) {
            return redirect()->back()->with('message', t('Error When Activate Account Please Try Again'))->with('m-class', 'success');
        }

        return redirect()->back()->with('message', t('Account Activated Successfully'))->with('m-class', 'success');
    }
}
