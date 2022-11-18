<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Notification;
use App\Notifications\GeneralNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:Notification', ['only' => ['show', 'store', 'destroy']]);
    }

    public function index()
    {
        $title = t('Show Notifications');
        if (request()->ajax()) {
            $notifications = Notification::query()->latest();

            return DataTables::make($notifications)
                ->escapeColumns([])
                ->addColumn('created_at', function ($notification) {
                    return Carbon::parse($notification->created_at)->toDayDateTimeString();
                })
                ->addColumn('title', function ($notification) {
                    return $notification->title;
                })
                ->addColumn('status', function ($notification) {
                    return is_null($notification->read_at) ? t('Unseen') : t('Seen');
                })
                ->addColumn('type', function ($notification) {
                    return $notification->type_name;
                })
                ->addColumn('actions', function ($notification) {
                    return $notification->action_buttons;
                })
                ->make();
        }
        return view('restaurant.notification.index', compact('title'));
    }

    public function show($id)
    {
        $title = t('Show Notification');
        $notification = Notification::findOrFail($id);
        $notification->update([
            'read_at' => now(),
        ]);
        return view('restaurant.notification.show', compact('title', 'notification'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'recipients' => 'required',
            'title' => 'required',
            'content' => 'required',
        ]);
//        $message = [
//            "title" => $request->get('title'),
//            "body" => $request->get('content'),
//            "others" => [
//                "type" => "AppNotification",
//            ]
//        ];
        $branches = Branch::currentMerchant(user('merchant')->id)->get();
        Notification::send($branches, new GeneralNotification(null, $request->title, $request->content));
        return redirect()->back()->with('message', t('Notification Sent Successfully'))->with('m-class', 'success');
    }

    public function destroy($id)
    {
        $notification = Notification::query()->find($id);
        if ($notification->notifiable_id == 0) {
            return redirect()->back()->with('message', t('Can Not Delete General Notification'))->with('m-class', 'error');
        }
        $notification->delete();
        return redirect()->back()->with('message', t('Successfully Deleted'))->with('m-class', 'success');
    }
}
