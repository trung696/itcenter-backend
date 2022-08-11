<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Validator;
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
        Validator::extend('greater_than', function ($attribute, $value, $params, $validator) {


            $other = Request::get($params[0]);
            return $value > $other;
        });

        Validator::replacer('greater_than', function ($message, $attribute, $rule, $params) {
            return str_replace('_', ' ', 'Không được phép nhập ' . $attribute . ' nhỏ hơn ' . $params[0]);
        });

        // Schema::defaultStringLength(191); // add: default varchar(191)
    }
}
