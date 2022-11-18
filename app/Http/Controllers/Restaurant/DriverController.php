<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Branch;
use App\Models\City;
use App\Models\Delivery;
use App\Models\Merchant;
use App\Models\Nationality;
use App\Models\TransporterType;
use App\Models\User;
use App\Rules\IntroMobile;
use App\Rules\StartWith;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Kreait\Firebase\Auth\UserRecord;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use Yajra\DataTables\DataTables;

class DriverController extends Controller
{
    private $_model;

    public function __construct(User $user)
    {
        parent::__construct();
        $this->_model = $user;
        $this->middleware('permission:Drivers', ['only' => ['index', 'create', 'edit']]);
        foreach (config('translatable.locales') as $local) {
            $this->validationRules["name.$local"] = 'required';
        }
        $this->validationRules["phone"] = ['required', 'min:13', 'max:13', new StartWith('+9665'), new IntroMobile(), 'unique:users,phone,{$id},id,deleted_at,NULL'];
//        $this->validationRules["password"] = password_rules(true, 6);
        $this->validationRules["city_id"] = 'nullable|exists:cities,id';
        $this->validationRules["nationality_id"] = 'required|exists:nationality,id';
        $this->validationRules["transporter_id"] = 'required|exists:transporter_type,id';
        $this->validationRules["image"] = 'nullable|image';
    }

    public function index()
    {
        $title = t('Show Drivers');
        if (request()->ajax()) {
            $name = request()->get('name', false);
            $status = request()->get('status', false);
            $source = request()->get('source', false);
            $branch = request()->get('branch', false);

            $items = $this->_model->driver()
                ->currentMerchant(user('merchant')->id)
                ->when($name, function ($query) use ($name) {
                    $query->where('name->' . lang(), 'like', '%' . $name . '%');
                })
                ->when($branch, function ($query) use ($branch) {
                    $query->where('branch_id', $branch);
                })
                ->when($status != null, function ($query) use ($status) {
                    $query->where('status', $status);
                })
                ->when($source != null, function ($query) use ($source) {
                    $query->where('source', $source);
                });

            return DataTables::make($items)
                ->escapeColumns([])
                ->addColumn('name', function ($item) {
                    return $item->name;
                })
                ->addColumn('mobile', function ($item) {
                    return $item->phone;
                })
                ->addColumn('merchant', function ($item) {
                    return optional($item->merchant)->name;
                })
                ->addColumn('branch', function ($item) {
                    return optional($item->branch)->name;
                })
                ->addColumn('driver_type_name', function ($item) {
                    return $item->driver_type_name;
                })
                ->addColumn('source_name', function ($item) {
                    return $item->source_name;
                })
                ->addColumn('status_name', function ($item) {
                    return $item->status_name;
                })
                ->addColumn('actions', function ($item) {
                    return $item->action_buttons;
                })
                ->addColumn('created_at', function ($item) {
                    return Carbon::parse($item->created_at)->toDateTimeString();
                })
                ->make();
        }
        $branches = Branch::currentMerchant(\user('merchant')->id)->get();
        return view('restaurant.driver.index', compact('title', 'branches'));
    }


    public function deliveries()
    {
        if (request()->ajax()) {
            $driver = request()->get('driver', false);

            $items = Delivery::with(['order.branch.merchant'])->whereHas('order', function ($query) {
                $query->whereHas('branch', function ($q) {
                    $q->where('merchant_id', \user('merchant')->id);
                });
            })
                ->when($driver, function ($query) use ($driver) {
                    $query->where('driver_id', $driver);
                })
                ->latest();

            return DataTables::make($items)
                ->escapeColumns([])
                ->addColumn('uuid', function ($item) {
                    return optional($item->order)->uuid;
                })
                ->addColumn('user', function ($item) {
                    return optional($item->driver)->name;
                })
                ->addColumn('merchant', function ($item) {
                    return optional(optional(optional($item->order)->branch)->merchant)->name;
                })
                ->addColumn('branch', function ($item) {
                    return optional(optional($item->order)->branch)->name;
                })
                ->addColumn('delivery_cost', function ($item) {
                    return 0;
                })
                ->addColumn('status_name', function ($item) {
                    return $item->status_name;
                })
                ->addColumn('created_at', function ($item) {
                    return Carbon::parse($item->created_at)->toDateTimeString();
                })
                ->addColumn('actions', function ($item) {
                    return '';//$item->action_buttons;
                })
                ->make();
        }
    }

    public function create()
    {
        $title = t('Add Driver');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        $cities = City::get();
        $banks = Bank::get();
        $nationalities = Nationality::get();
        $transporters = TransporterType::get();
        $branches = Branch::currentMerchant(\user('merchant')->id)->get();
        return view('restaurant.driver.edit', compact('title', 'validator', 'cities', 'banks', 'nationalities', 'transporters', 'branches'));
    }


    public function edit($id)
    {
        $title = t('Edit Driver');
        $driver = $this->_model->driver()->findOrFail($id);
        $this->validationRules["phone"] = ['required', 'min:13', 'max:13', new StartWith('+966'), new IntroMobile(), 'unique:users,phone,' . $driver->id . ',id,deleted_at,NULL'];
//        $this->validationRules["password"] = 'nullable|min:6';
        $this->validationRules["image"] = 'nullable|image';
        $this->validationRules["driving_license"] = 'nullable|image';
        $this->validationRules["id_card"] = 'nullable|image';


        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        $cities = City::get();
        $banks = Bank::get();
        $nationalities = Nationality::get();
        $transporters = TransporterType::get();
        $branches = Branch::where('merchant_id', \user('merchant')->id)->get();
        return view('restaurant.driver.edit', compact('title', 'validator', 'cities', 'banks',
            'nationalities', 'transporters', 'driver', 'branches'));
    }


    public function store(Request $request)
    {
        if (isset($request->driver_id)) {
            $store = $this->_model->driver()->findOrFail($request->driver_id);
            $this->validationRules["phone"] = ['required', 'min:13', 'max:13', new StartWith('+9665'), new IntroMobile(), 'unique:users,phone,' . $store->id . ',id,deleted_at,NULL'];
//            $this->validationRules["password"] = 'nullable|min:6';
            $this->validationRules["image"] = 'nullable|image';
            $this->validationRules["driving_license"] = 'nullable|image';
            $this->validationRules["id_card"] = 'nullable|image';
        } else {
            $store = new $this->_model();
            $store->source = User::DASHBOARD;

        }
        $request->validate($this->validationRules);
        $store->name = $request->name;
        $store->phone = $request->phone;
        $store->type = User::DRIVER;
//        if (isset($request->password)) $store->password = Hash::make($request->password);
        $store->city_id = $request->city_id;
        $store->nationality_id = $request->nationality_id;
        $store->driver_type = User::FOLLOWED_TO_RESTAURANT_DRIVER;
        $store->merchant_id = \user('merchant')->id;
        $store->branch_id = $request->branch_id;
        $store->i_ban = \user('merchant')->i_ban;
        $store->transporter_type_id = $request->transporter_id;
        $store->draft = $request->get('draft', 0);
        $store->status = $request->get('active', User::NOT_ACTIVE);
        if ($request->hasFile('image')) $store->image = $this->uploadImage($request->file('image'), 'Drivers');
        if ($request->hasFile('driving_license')) $store->driving_license = $this->uploadImage($request->file('driving_license'), 'drivers');
        if ($request->hasFile('id_card')) $store->id_card = $this->uploadImage($request->file('id_card'), 'drivers');
        $store->save();

        if (isset($request->driver_id)) {
            return redirect()->route('restaurant.driver.index')->with('m-class', 'success')->with('message', t('Successfully Updated'));
        } else {
            return redirect()->route('restaurant.driver.index')->with('m-class', 'success')->with('message', t('Successfully Created'));
        }
    }


    public function show($id)
    {
        $title = t('Show Driver');
        $user = $this->_model->driver()->findOrFail($id);
        $data['wallet_balance'] = $user->user_wallet;
        $data['orders_count'] = 0;
        $data['addresses_count'] = 0;
        $data['rates_count'] = 0;

        $this->validationRules['amount'] = 'required|gt:0';
        $this->validationRules['amount'] = 'required|gt:0';
        $validator = JsValidator::make($this->validationRules);

        $this->validationRules["title"] = 'required';
        $this->validationRules["content"] = 'required';
        $this->validationRules["user_id"] = 'required';
        $notify_validator = JsValidator::make($this->validationRules, $this->validationMessages);
        return view('restaurant.driver.show', compact('user', 'title', 'data', 'notify_validator', 'validator'));
    }


    public function destroy($id)
    {
        $item = $this->_model->driver()->currentMerchant(\user('merchant')->id)->findOrFail($id);
        if ($item->deliveries()->count() > 0) return redirect()->back()->with('message', t('Can not Delete Driver, Driver Related With deliveries'))->with('m-class', 'error');
        $item->delete();
        return redirect()->back()->with('m-class', 'success')->with('message', t('Successfully Deleted'));
    }


    public function deliveries_dashboard(Request $request)
    {
        if (request()->ajax()) {
            $items = Delivery::where('driver_id', $request->driver_id)->latest();
            return DataTables::make($items)
                ->escapeColumns([])
                ->addColumn('uuid', function ($item) {
                    return optional($item->order)->uuid;
                })
                ->addColumn('provider', function ($item) {
                    return optional(optional($item->order)->branch)->name;
                })
                ->addColumn('client', function ($item) {
                    return optional(optional($item->order)->user)->name;
                })
                ->addColumn('distance', function ($item) {
                    return $item->distance;
                })
                ->addColumn('counter', function ($item) {
                    return $item->counter;
                })
                ->addColumn('actions', function ($item) {
                    return $item->action_buttons;
                })
                ->addColumn('created_at', function ($item) {
                    return Carbon::parse($item->created_at)->toDateTimeString();
                })
                ->make();
        }
    }

}
