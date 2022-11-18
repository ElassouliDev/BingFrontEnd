<?php


Route::group(['prefix' => 'v1', 'namespace' => ROOT_NAMESPACE, "middleware" => ['localization']], function () {

    Route::get('privacy_policy', 'HomeController@privacy_policy');
    Route::get('cities', 'HomeController@cities');
    Route::get('banks', 'HomeController@banks');
    Route::get('getAllHours/{id}', 'OrderController@getAllHours');
    Route::get('get_hours', 'HomeController@getHours');
    Route::get('branches', 'HomeController@branches');
    Route::get('branch/{id}', 'HomeController@branch');
    Route::get('branch_meals/{id}', 'HomeController@branch_meals');
    Route::get('meal/{id}', 'HomeController@meal');
    Route::get('branch/{id}/{key}', 'HomeController@branchInfo');
    Route::get('settings', 'HomeController@settings');
    Route::post('contact_us', 'HomeController@contactUs');


    Route::group(["middleware" => ["auth:api", "CheckIsVerified"]], function () {
        Route::group(['prefix' => 'user',], function () {
            Route::group(["middleware" => ["CheckIsClient"]], function () {
                Route::get('home', 'HomeController@home');
                Route::get('offers', 'HomeController@offers');
                Route::get('offer/{id}', 'HomeController@offer');
                Route::get('branch/{branch_id}/reward/{reward_id}/collect', 'HomeController@collect_reward');
                Route::get('category_details', 'HomeController@category_details');
                Route::get('nearByMerchants', 'HomeController@nearByMerchants');
                Route::get('branch/{id}/{key?}', 'HomeController@branch');
                Route::apiResource('address', 'User\AddressController');
                Route::get('packages', 'User\PackagesController@packages');
                Route::post('buy_package', 'User\PackagesController@buy_package');

//              Orders
                Route::get('orders/{key?}', 'OrderController@orders');
                Route::post('order/{id}/rate', 'OrderController@rate');
                Route::get('order/{id}', 'OrderController@order');
            });


            Route::get('notifications', 'User\UserController@notifications');
            Route::get('notification/{id}', 'User\UserController@notification');
            Route::get('profile', 'User\UserController@profile');
            Route::post('update_profile', 'User\UserController@updateProfile');
            Route::post('update_mobile', 'User\UserController@updateMobile');
            Route::post('update_language', 'User\UserController@updateLanguage');
            Route::post('update_notification', 'User\UserController@updateNotification');
            Route::post('update_image', 'User\UserController@updateImage');
            Route::post('hide_vs_show/{key?}', 'User\UserController@hide_vs_show');
        });
        Route::group(['prefix' => 'employee',], function () {
            Route::group(["middleware" => ["CheckIsEmployee"], 'namespace' => 'Employee'], function () {
                Route::get('home', 'HomeController@home');
                //              Orders
                Route::get('orders/{key?}', 'OrderController@orders');
                Route::post('order/{id}/rate', 'OrderController@rate');
                Route::get('order/{id}', 'OrderController@order');
                Route::post('order/check', 'OrderController@check_order');
                Route::post('order/{id}/checkout', 'OrderController@checkout_order');

            });
        });
    });

//     All API Auth Routes here
    Route::group(['namespace' => 'Auth'], function () {
        Route::post('login', 'AuthController@login');
        Route::post('employee_login', 'AuthController@employee_login');
        Route::post('register', 'AuthController@register')->middleware('auth:api');
        Route::post('signup_delivery_build_account', 'AuthController@signup_delivery_build_account')->middleware(['api', 'CheckIsDriver']);
        Route::post('logoutAllAuthUsers', 'AuthController@logoutAllAuthUsers');
        Route::post('resendCode', 'AuthController@resendCode');
        Route::post('verified_code', 'AuthController@verified_code');
        Route::group(['middleware' => 'auth:api'], function () {
            Route::post('logout', 'AuthController@logout');
        });
    });
    Route::group(['namespace' => 'notifications', 'prefix' => 'notifications'], function () {
        Route::post('saveFcmToken', 'NotificationsController@SaveFCMToken');
        Route::post('sendNotificationForAllUsers', 'NotificationsController@sendNotificationForAllUsers');


    });
});


