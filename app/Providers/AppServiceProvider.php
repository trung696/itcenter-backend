<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Request;

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
        //
    }
}
