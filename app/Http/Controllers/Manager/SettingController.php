<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Item;
use App\Models\JoinUs;
use App\Models\Merchant;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Setting;
use App\Models\User;
use App\Rules\EmailRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:General Settings', ['only' => ['settings', 'updateSettings']]);
    }

    public function home()
    {
        $order = Order::query();
        $total_orders = $order->where('status', '<>', Order::RECEIVING)->sum('total_cost');
        $total_payments =0;// Payment::query()->sum('amount');
        $orders = $order->where('status', '<>', Order::RECEIVING)->count();
        $new_orders = $order->where('status', Order::RECEIVING)->count();
        $prepare_orders = $order->where('status', Order::ON_PROGRESS)->count();
        $delivery_orders = $order->where('status', Order::ON_PROGRESS)->count();
        $complete_orders = $order->where('status', Order::COMPLETED)->count();
        $cancel_orders = $order->where('status', Order::CANCELED)->count();

        $users = User::client()->count();
        $restaurants = Merchant::query()->count();
        $branches = Branch::query()->count();
        $branches_items = Item::query()->count();
        $joinUs = JoinUs::query()->count();
        $orders_date = Order::query()->groupBy('date')->orderBy('date', 'DESC')->whereMonth('created_at', now())
            ->get(array(
                DB::raw('Date(created_at) as date'),
                DB::raw('COUNT(*) as counts')
            ));


        return view('manager.home', compact('total_orders', 'total_payments', 'orders',
            'new_orders', 'prepare_orders', 'delivery_orders', 'complete_orders', 'cancel_orders',
            'users', 'restaurants', 'branches', 'branches_items', 'joinUs', 'orders_date'));


    }

    public function settings()
    {
        $title = t('Show Settings');
        return view('manager.setting.general', compact('title'));
    }


    public function updateSettings(Request $request)
    {
        $data = $request->except(['_token', 'logo', 'logo_min']);
        if ($request->hasFile('logo')) {
            if ($request->file('logo')->isValid()) {
                setting(['logo' => $this->uploadImage($request->file('logo'), 'logos')])->save();
            }
        }
        if ($request->hasFile('logo_min')) {
            if ($request->file('logo_min')->isValid())
                setting(['logo_min' => $this->uploadImage($request->file('logo_min'), 'logo_min')])->save();
        }
        foreach ($data as $index => $datum) {
            if (is_array($datum) && sizeof($datum) > 0) {
                foreach ($datum as $index2 => $item) {
                    if (is_null($item)) {
                        $datum[$index2] = '';
                    }
                }
            }
            if (is_null($datum)) $datum = '';
            setting([$index => $datum])->save();

        }
        Artisan::call('cache:clear');
        return redirect()->back()->with('message', t('Successfully Updated'))->with('m-class', 'success');

        $data = $request->all();
        $setting = Setting::query()->findOrNew('1');
        if ($request->hasFile('logo')) {
            $data['logo'] = $this->uploadImage($request->file('logo'), 'logos');
        }
        if ($request->hasFile('logo_min')) {
            $data['logo_min'] = $this->uploadImage($request->file('logo_min'), 'logos');
        }
        $setting->update($data);
        Artisan::call('cache:clear');
        return redirect()->back()->with('message', t('Successfully Updated'))->with('m-class', 'success');
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
        return view('manager.setting.profile', compact('title'));
    }

    public function profile(Request $request)
    {
        $user = Auth::guard('manager')->user();
        $this->validationRules['password'] = 'nullable';
        $this->validationRules['email'] = ['required', 'unique:managers,email,' . $user->id, new EmailRule()];
        $request->validate($this->validationRules);

        $data = $request->all();
        if ($request->has(['password', 'password_confirmation']) && !empty($request->get('password'))) {
            $request->validate([
                'password' => 'min:6|confirmed'
            ]);
            $data['password'] = bcrypt($data['password']);
        } else {
            $data['password'] = $user->password;
        }
        $user->update($data);
        return redirect()->back()->with('message', t('Successfully Updated'))->with('m-class', 'success');
    }
}
