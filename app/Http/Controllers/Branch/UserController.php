<?php

namespace App\Http\Controllers\Branch;

use App\Events\AddNewBalanceEvent;
use App\Events\UserNotificationEvent;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use App\Models\Wallet;
use App\Rules\IntroMobile;
use App\Rules\StartWith;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
        $this->validationRules["city_id"] = 'required|exists:cities,id';
        $this->validationRules["image"] = 'nullable|image';
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
                return $row->getTypeName(optional($row->order)->uuid);
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
            't_type' => Wallet::ADMIN_CHARGING,
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
                    return  $row->body;
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
