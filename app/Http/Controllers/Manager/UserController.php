<?php

namespace App\Http\Controllers\Manager;

use App\Events\AddNewBalanceEvent;
use App\Events\UserNotificationEvent;
use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Bank;
use App\Models\Branch;
use App\Models\City;
use App\Models\Item;
use App\Models\Merchant;
use App\Models\Nationality;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Rate;
use App\Models\TransporterType;
use App\Models\User;
use App\Models\Wallet;
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
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    private $_model;

    public function __construct(User $user)
    {
        parent::__construct();
        $this->_model = $user;
        $this->middleware('permission:Users', ['only' => ['index', 'create', 'edit', 'show', 'destroy']]);

        foreach (config('translatable.locales') as $local) {
            $this->validationRules["name.$local"] = 'required';
        }
        $this->validationRules["phone"] = ['required', 'min:13', 'max:13', new StartWith('+966'), new IntroMobile(), 'unique:users,phone,{$id},id,deleted_at,NULL'];
//        $this->validationRules["password"] = password_rules(true, 6);

        $this->validationRules["city_id"] = 'required|exists:cities,id';
        $this->validationRules["image"] = 'nullable|image';
    }

    public function index()
    {
        $title = t('Show Users');
        if (request()->ajax()) {
            $name = request()->get('name', false);
            $status = request()->get('status', false);
            $source = request()->get('source', false);

            $items = $this->_model->client()->when($name, function ($query) use ($name) {
                $query->where('name', 'like', '%' . $name . '%');
            })
                ->when($status != null, function ($query) use ($status) {
                    $query->where('status', $status);
                })
                ->when($source != null, function ($query) use ($source) {
                    $query->where('source', $source);
                })
//                ->withoutGlobalScope('notDraft')
                ->latest();

            return DataTables::make($items)
                ->escapeColumns([])
                ->addColumn('name', function ($item) {
                    return $item->name;
                })
                ->addColumn('mobile', function ($item) {
                    return $item->phone;
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
        return view('manager.user.index', compact('title'));
    }

    public function create()
    {
        $title = t('Add Client');
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        $cities = City::get();
        return view('manager.user.edit', compact('title', 'validator', 'cities'));
    }

    public function edit($id)
    {
        $title = t('Edit Client');
        $client = $this->_model->client()->findOrFail($id);

        $this->validationRules["phone"] = ['required', 'min:13', 'max:13', new StartWith('+966'), new IntroMobile(), 'unique:users,phone,' . $client->id . ',id,deleted_at,NULL'];
//        $this->validationRules["password"] = 'nullable|min:6';
        $this->validationRules["image"] = 'nullable|image';
        $validator = JsValidator::make($this->validationRules, $this->validationMessages);
        $cities = City::get();
        return view('manager.user.edit', compact('title', 'validator', 'cities', 'client'));
    }


    public function store(Request $request)
    {
        if (isset($request->client_id)) {
            $store = $this->_model->client()->findOrFail($request->client_id);
            $this->validationRules["phone"] = ['required', 'min:13', 'max:13', new StartWith('+966'), new IntroMobile(), 'unique:users,phone,' . $store->id . ',id,deleted_at,NULL'];
//            $this->validationRules["password"] = 'nullable|min:6';
            $this->validationRules["image"] = 'nullable|image';
        } else {
            $store = new $this->_model();
        }
        $request->validate($this->validationRules);
        $store->name = $request->name;
        $store->phone = $request->phone;
//        if (isset($request->password)) {
//            $store->password = Hash::make($request->password);
//        }
        $store->city_id = $request->city_id;
        $store->type = User::CLIENT;
        $store->source = User::DASHBOARD;
        $store->draft = $request->get('draft', 0);
        $store->status = $request->get('active', User::NOT_ACTIVE);
        if ($request->hasFile('image')) {
            $store->image = $this->uploadImage($request->file('image'), 'Drivers');
        }
        $store->save();

        if (isset($request->client_id)) {
            return redirect()->route('manager.user.index')->with('m-class', 'success')->with('message', t('Successfully Updated'));
        } else {
            return redirect()->route('manager.user.index')->with('m-class', 'success')->with('message', t('Successfully Created'));
        }
    }


    public function show($id)
    {
        $title = t('Show Client');
        $user = $this->_model->client()->findOrFail($id);
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
        return view('manager.user.show', compact('user', 'title', 'data', 'notify_validator', 'validator'));
    }


    public function destroy($id)
    {
        $item = $this->_model->client()->findOrFail($id);
        if ($item->orders()->count() > 0) return redirect()->back()->with('message', t('Can not Delete Client, Client Related With Orders'))->with('m-class', 'error');
        $item->delete();
        return redirect()->back()->with('m-class', 'success')->with('message', t('Successfully Deleted'));
    }


    public function userWallet($id)
    {
        $users = Wallet::query()->where('user_id', $id)->latest();
        $search = request()->get('search', false);
        return DataTables::make($users)
            ->escapeColumns([])
            ->addColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->toDateTimeString();
            })
            ->addColumn('actions', function ($row) {
                return $row->action_buttons;
            })
            ->addColumn('uuid', function ($row) {
                return optional($row->order)->uuid;
            })
            ->addColumn('type_name', function ($row) {
                return $row->type_name;
            })
            ->make();
    }

    public function addWalletTransaction(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|gt:0',
            'note' => 'nullable',
        ]);
        $user = User::query()->findOrFail($id);
        $wallet = Wallet::query()->create([
            'user_id' => $id,
            'order_id' => null,
            'amount' => abs($request->get('amount')),
            'note' => $request->get('note', null),
            't_type' => '1',
        ]);
        event(new AddNewBalanceEvent($user, $wallet));

        return redirect()->back()->with('m-class', 'success')->with('message', t('Successfully Added To Wallet'));
    }

    public function deleteWalletTransaction($id)
    {
        $wallet = Wallet::query()->findOrFail($id);
        $wallet->delete();
        return redirect()->back()->with('m-class', 'success')->with('message', t('Successfully Deleted'));
    }

    public function sendNotification(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'user_id' => 'required',
        ]);

        $user = User::query()->find($request->get('user_id'));
        if ($user) {
            event(new UserNotificationEvent($user, $request->get('title'), $request->get('content')));
            return redirect()->back()->with('m-class', 'success')->with('message', t('Notification Successfully Sent'));
        }
        return redirect()->back()->with('m-class', 'error')->with('message', t('Branch Not Found'));

    }

    public function notifications()
    {
        $title = t('Show Users');
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

}
