<?php
use Illuminate\Support\Facades\Route;


Route::group(['namespace' => 'Manager'], function () {
    Route::get('/home', 'SettingController@home')->name('home');
    //Set Local
    Route::get('lang/{local}', 'SettingController@lang')->name('switch-language');

    //Settings Management
    Route::get('settings', 'SettingController@settings')->name('settings.general');
    Route::post('settings', 'SettingController@updateSettings')->name('settings.updateSettings');
    Route::get('profile', 'SettingController@view_profile')->name('profile.show');
    Route::post('profile', 'SettingController@profile')->name('profile.update');

    //Merchant Types
    Route::resource('merchant_type', 'MerchantTypeController');
    //Testimonial Routes
    Route::resource('testimonial', 'TestimonialController');
    //Slider Routes
    Route::resource('slider', 'SliderController');
    //City Routes
    Route::resource('city', 'CityController');

    //Nationalities Routes
    Route::resource('nationality', 'NationalityController');

    //Transporter Routes
    Route::resource('transporter', 'TransporterController');
    //Bank Routes
    Route::resource('bank', 'BankController');
    //User Routes
    Route::resource('user', 'UserController');
    //Drivers Routes
    Route::resource('driver', 'DriverController');
    Route::post('deliveries', 'DriverController@deliveries')->name('deliveries');



    Route::get('user_notifications', 'UserController@notifications')->name('user.notifications');
    //Restaurant Routes
    Route::resource('restaurant', 'RestaurantController');
    Route::post('activate_restaurant/{id}', 'RestaurantController@activateRestaurant')->name('restaurant.activate');

    Route::get('restaurant_notifications', 'RestaurantController@notifications')->name('restaurant.notifications');
    Route::get('go_restaurant/{id}', 'RestaurantController@goRestaurant')->name('go_restaurant');
    //Branch Routes
    Route::resource('branch', 'BranchController');
    Route::get('branches', 'BranchController@branches')->name('branches');
    Route::get('orderBranches', 'BranchController@orderBranches')->name('orderBranches');
    Route::get('go_branch/{id}', 'BranchController@goBranch')->name('go_branch');
    Route::get('change_drivers_code/{id}', 'BranchController@change_drivers_code')->name('change_drivers_code');
    Route::get('branch_notifications', 'BranchController@notifications')->name('branch.notifications');
    //Classification Routes
    Route::resource('classification', 'ClassificationController');
    Route::get('classifications', 'ClassificationController@classifications')->name('classifications');
    Route::get('branchClassifications', 'ClassificationController@branchClassifications')->name('branchClassifications');

    //Package Routes
    Route::resource('package', 'PackageController');



    //User Packages Routes
    Route::resource('user_packages', 'UserPackagesController');
    Route::post('reactive/{id}', 'UserPackagesController@reactive')->name('user_packages.reactive');
    Route::post('active/{id}', 'UserPackagesController@active')->name('user_packages.active');
    Route::post('expire/{id}', 'UserPackagesController@expire')->name('user_packages.expire');


    //Meal Routes
    Route::resource('meal', 'ItemController');
    Route::delete('delete_addon/{id}', 'ItemController@deleteAddon')->name('addon.delete');
    Route::delete('delete_price/{id}', 'ItemController@deletePrice')->name('price.delete');
    //Coupon
    Route::resource('coupon', 'CouponController');

    Route::get('order', 'OrderController@index')->name('order.index');
    Route::get('order/{id}', 'OrderController@show')->name('order.show');
    Route::post('order/{id}', 'OrderController@changeStatus')->name('order.changeStatus');
    Route::delete('order/{id}', 'OrderController@destroy')->name('order.destroy');
    Route::post('sendNotification/{id}', 'OrderController@sendNotification')->name('order.sendNotification');

    //Payment Route
    Route::resource('payment', 'PaymentController');


    //Ratings Branch Route
    Route::resource('client_order_rate', 'ClientOrderRateController');

    //Ratings Driver Route
    Route::resource('client_driver_rate', 'ClientDriverRateController');

    //Ratings Client Route
    Route::resource('driver_client_rate', 'DriverClientRateController');



    //Client Ratings Route
    Route::resource('client_order_ratings', 'ClientOrderRateController');

    //Address Routes
    Route::resource('address', 'AddressController');

    //Wallet Route
    Route::get('user_wallet/{id}', 'UserController@userWallet')->name('wallet.index');
    Route::post('user_wallet/{id}', 'UserController@addWalletTransaction')->name('wallet.store');
    Route::delete('user_wallet/{id}', 'UserController@deleteWalletTransaction')->name('wallet.destroy');

    //Contact us Management
    Route::get('contact_us', 'ContactUsController@index')->name('contact_us.index');
    Route::get('contact_us/{id}', 'ContactUsController@show')->name('contact_us.show');
    Route::delete('contact_us/{id}', 'ContactUsController@destroy')->name('contact_us.destroy');
    //Join us Management
    Route::get('join_us', 'JoinUsController@index')->name('join_us.index');
    Route::get('join_us/{id}/edit', 'JoinUsController@show')->name('join_us.edit');
    Route::post('join_us', 'JoinUsController@store')->name('join_us.store');
    Route::delete('join_us/{id}', 'JoinUsController@destroy')->name('join_us.destroy');

    //Pages Management
    Route::get('page', 'PageController@index')->name('page.index');
    Route::get('page/{id}/edit', 'PageController@edit')->name('page.edit');
    Route::patch('page/{id}/update', 'PageController@update')->name('page.update');

    //Notifications Routes
    Route::post('send_user_notification', 'UserController@sendNotification')->name('user_notification');
    Route::post('send_restaurant_notification', 'RestaurantController@sendNotification')->name('restaurant_notification');
    Route::post('send_branch_notification', 'BranchController@sendNotification')->name('branch_notification');

    //Notification Route
    Route::get('notification', 'NotificationController@index')->name('notification.index');
    Route::get('notification/{id}', 'NotificationController@show')->name('notification.show');
    Route::delete('notification/{id}', 'NotificationController@destroy')->name('notification.destroy');
    Route::post('notification', 'NotificationController@store')->name('notification.store');


    Route::resource('manager','ManagerController');
    //Roles
    Route::resource('manager_roles','RoleController');

    //Drivers Routes
    Route::resource('driver', 'DriverController');
    Route::get('deliveries', 'DriverController@deliveries')->name('deliveries');
    Route::get('deliveries/dashboard', 'DriverController@deliveries_dashboard')->name('deliveries_dashboard');


});
