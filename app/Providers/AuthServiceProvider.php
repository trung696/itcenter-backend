<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    
        //gọi và đây để hoạt động được
        $this->defineGateRole();

        //
    }
     //Role
     public function defineGateRole()
     {
         Gate::define('role-list','App\Policies\RolePolicy@view') ;
         Gate::define('role-add','App\Policies\RolePolicy@create') ;
         Gate::define('role-edit','App\Policies\RolePolicy@update');
         Gate::define('role-delete','App\Policies\RolePolicy@delete') ;
     }
     //End Role
}
