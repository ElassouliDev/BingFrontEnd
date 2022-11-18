<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Branch;
use App\Models\Item;
use App\Models\JoinUs;
use App\Models\Merchant;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Rules\EmailRule;
use App\Rules\IntroMobile;
use App\Rules\StartWith;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:General Settings', ['only' => ['settings', 'updateSettings']]);

        foreach (config('translatable.locales') as $local) {
            $this->validationRules["name.$local"] = 'required';
        }
        $this->validationRules["phone"] = ['required', 'min:13', 'max:13', new StartWith('+966'), new IntroMobile(), 'unique:providers,phone,{$id},id,deleted_at,NULL'];
        $this->validationRules["email"] = ['required', 'unique:providers,email,{$id},id,deleted_at,NULL', new EmailRule()];
        $this->validationRules["merchant_type_id"] = 'required|exists:provider_types,id';
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

    public function home()
    {
        $order = Order::currentMerchant(user('merchant')->id);
        $total_orders = $order->where('status', '<>', Order::PENDING)->sum('total_cost');
        $total_payments = Payment::query()->sum('amount');
        $orders = $order->where('status', '<>', Order::PENDING)->count();
        $new_orders = $order->where('status', Order::PENDING)->count();
        $prepare_orders = $order->where('status', Order::ON_PROGRESS)->count();
        $delivery_orders = $order->where('status', Order::ON_PROGRESS)->count();
        $complete_orders = $order->where('status', Order::COMPLETED)->count();
        $cancel_orders = $order->where('status', Order::CANCELED)->count();

        $users = User::client()->count();
        $restaurants = Merchant::query()->count();
        $branches = Branch::query()->count();
        $branches_items = Item::query()->count();
        $joinUs = JoinUs::query()->count();
        $orders_date = $order->groupBy('date')->orderBy('date', 'DESC')->whereMonth('created_at', now())
            ->get(array(
                DB::raw('Date(created_at) as date'),
                DB::raw('COUNT(*) as counts')
            ));
        return view('restaurant.home', compact('total_orders', 'total_payments', 'orders',
            'new_orders', 'prepare_orders', 'delivery_orders', 'complete_orders', 'cancel_orders',
            'users', 'restaurants', 'branches', 'branches_items', 'joinUs', 'orders_date'));


    }

    public function lang($local)
    {
        session(['lang' => $local]);
        if (Auth::guard('manager')->check()) {
            $user = Auth::guard('manager')->user();
            $user->update([
                'local' => $local,
            ]);
        }
        app()->setLocale($local);
        return back();
    }

    public function view_profile()
    {
        $title = t('Show Profile');
        $merchant = user('provider');
        $this->validationRules["phone"] = ['required', 'min:13', 'max:13', new StartWith('+966'), new IntroMobile(), 'unique:providers,phone,' . $merchant->id . ',id,deleted_at,NULL'];
        $this->validationRules["email"] = ['required', 'unique:providers,email,' . $merchant->id . ',id,deleted_at,NULL', new EmailRule()];
        $this->validationRules["password"] = 'nullable|min:6';
        $this->validationRules["image"] = 'nullable|image';
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        $merchant_types = ProviderType::query()->get();
        $banks = Bank::query()->get();
        $categories = Category::query()->get();

        $branchDays = ProviderHour::where('provider_id', user('provider')->id)->get();
        $branchDaysId = $branchDays->pluck('day')->all();

        $days = [];
        foreach (work_hours() as $day) {
            $day['selected'] = in_array($day['num'], $branchDaysId) ? true : false;
            $day['from'] = in_array($day['num'], $branchDaysId) ? $branchDays->where('day', $day['num'])->first()->from : null;
            $day['to'] = in_array($day['num'], $branchDaysId) ? $branchDays->where('day', $day['num'])->first()->to : null;
            array_push($days, $day);
        }
        return view('restaurant.profile', compact('title', 'merchant', 'validator', 'merchant_types', 'banks', 'categories', 'days'));

        return view('restaurant.profile', compact('title'));
    }

    public function profile(Request $request)
    {
        $store = user('provider');
        $this->validationRules["phone"] = ['required', 'min:13', 'max:13', new StartWith('+966'), new IntroMobile(), 'unique:providers,phone,' . $store->id . ',id,deleted_at,NULL'];
        $this->validationRules["email"] = ['required', 'unique:providers,email,' . $store->id . ',id,deleted_at,NULL', new EmailRule()];
        $this->validationRules["password"] = 'nullable|min:6';
        $this->validationRules["image"] = 'nullable|image';

        $request->validate($this->validationRules);
//        dd(checkRequestIsWorkingOrNot());
        $store->name = $request->name;
        $store->max_orders = $request->max_orders;
        $store->provider_type_id = $request->merchant_type_id;
        $store->lat = $request->lat;
        $store->lng = $request->lng;
        $store->category_id = $request->category_id;
        $store->phone = $request->phone;
        $store->email = $request->email;
        $store->password = !is_null($request->get('password')) ? bcrypt($request->get('password', 123456)) : $store->password;
        $active = $request->get('active', 0);
        $store->status = ($active) ? Provider::ACTIVE : Provider::NOT_ACTIVE;
        $store->draft = $request->get('draft', 0);
        $store->busy = $request->get('busy', 0);
        $store->id_no = $request->id_no;
        $store->bank_id = $request->bank_id;
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
        if ($request->has('day') && is_array($request->get('day')) && count($request->get('day')) > 0) {
            $store->hours()->forcedelete();
            foreach ($request->get('day') as $day) {
                ProviderHour::create([
                    'provider_id' => $store->id,
                    'day' => $day,
                    'from' => isset($request->get('from')[$day]) ? $request->get('from')[$day] : '0:00:00',
                    'to' => isset($request->get('to')[$day]) ? $request->get('to')[$day] : '0:00:00',
                ]);
            }
        }
        return redirect()->back()->with('message', t('Successfully Updated'))->with('m-class', 'success');
    }
}
