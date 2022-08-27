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
        $this->defineGateCourseCategory();
        $this->defineGateCourse();
        $this->defineGateHocVien();
        $this->defineGateGiangVien();
        $this->defineGateUser();
        $this->defineGateClass();
        $this->defineGateDangKy();
        $this->defineGateCa();
        $this->defineGateCoSo();
        //
    }

    //Course
    public function defineGateCourse()
    {
        Gate::define('course-list','App\Policies\CoursePolicy@view') ;
        Gate::define('course-add','App\Policies\CoursePolicy@create') ;
        Gate::define('course-edit','App\Policies\CoursePolicy@update');
        Gate::define('course-delete','App\Policies\CoursePolicy@delete') ;
    }  
    //End Course

    //CourseCategory
    public function defineGateCourseCategory()
    {
        Gate::define('course-category-list','App\Policies\CourceCategoryPolicy@view') ;
        Gate::define('course-category-add','App\Policies\CourceCategoryPolicy@create') ;
        Gate::define('course-category-edit','App\Policies\CourceCategoryPolicy@update');
        Gate::define('course-category-delete','App\Policies\CourceCategoryPolicy@delete') ;
    }  
    //End CourseCategory

    //Student
    public function defineGateHocVien()
    {
        Gate::define('student-list','App\Policies\HocVienPolicy@view') ;
        Gate::define('student-add','App\Policies\HocVienPolicy@create') ;
        Gate::define('student-edit','App\Policies\HocVienPolicy@update');
        Gate::define('student-delete','App\Policies\HocVienPolicy@delete') ;
    }  
    //End Student

    //giangvien
    public function defineGateGiangVien()
    {
        Gate::define('teacher-list','App\Policies\GiaoVienPolicy@view') ;
        Gate::define('teacher-add','App\Policies\GiaoVienPolicy@create') ;
        Gate::define('teacher-edit','App\Policies\GiaoVienPolicy@update');
        Gate::define('teacher-delete','App\Policies\GiaoVienPolicy@delete') ;
    }  
    //End giangvien

     //user
     public function defineGateUser()
     {
         Gate::define('user-list','App\Policies\UserPolicy@view') ;
         Gate::define('user-add','App\Policies\UserPolicy@create') ;
         Gate::define('user-edit','App\Policies\UserPolicy@update');
         Gate::define('user-delete','App\Policies\UserPolicy@delete') ;
     }  
     //End user

      //Class
      public function defineGateClass()
      {
          Gate::define('class-list','App\Policies\ClassPolicy@view') ;
          Gate::define('class-add','App\Policies\ClassPolicy@create') ;
          Gate::define('class-edit','App\Policies\ClassPolicy@update');
          Gate::define('class-delete','App\Policies\ClassPolicy@delete') ;
      }  
      //End Class

    //dang ky
      public function defineGateDangKy()
      {
          Gate::define('dang-ky-list','App\Policies\DangKyPolicy@view') ;
          Gate::define('dang-ky-add','App\Policies\DangKyPolicy@create') ;
          Gate::define('dang-ky-edit','App\Policies\DangKyPolicy@update');
          Gate::define('dang-ky-delete','App\Policies\DangKyPolicy@delete') ;
      }  
      //End dang ky

      //ca
      public function defineGateCa()
      {
          Gate::define('ca-list','App\Policies\CaPolicy@view') ;
          Gate::define('ca-add','App\Policies\CaPolicy@create') ;
          Gate::define('ca-edit','App\Policies\CaPolicy@update');
          Gate::define('ca-delete','App\Policies\CaPolicy@delete') ;
      }  
      //End ca

      //cơ sở trung tâm
      public function defineGateCoSo()
      {
          Gate::define('co-so-list','App\Policies\CoSoPolicy@view') ;
          Gate::define('co-so-add','App\Policies\CoSoPolicy@create') ;
          Gate::define('co-so-edit','App\Policies\CoSoPolicy@update');
          Gate::define('co-so-delete','App\Policies\CoSoPolicy@delete') ;
      }  
      //End cơ sở trung tâm

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
