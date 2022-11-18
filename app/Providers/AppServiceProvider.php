<?php

namespace App\Providers;

use App\Models\Profession;
use App\Models\Setting;
use App\Models\TopFooter;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {


        if (Schema::hasTable('settings')) {

            App::singleton('settings', function () {
                return Setting::getSettings();
            });


            View::share('settings', app('settings'));
        }

//        view()->share('notifications',\App\Models\ContactUs::where('seen',0)->latest()->get());
    }
}
