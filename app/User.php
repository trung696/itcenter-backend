<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;


class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password','repassword', 'address', 'phone', 'status','tokenActive'];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    //check quyen
    public function checkPermissionAccess($permissionCheck)
    {
        // dd($permissionCheck);
        //bc1: lấy được quyền của user đang login
        $roles = auth()->user()->roles;
        foreach ($roles as $role) {
            //lấy các permission của role
            $permissions = $role->permission;
            // dd($permissions);
            //kiểm tra các permission có trường 'key_code' trùng với $permissionCheck(key_code) truyền từ policy sang hay không
            if ($permissions->contains('key_code', $permissionCheck)) {
                //trùng thì cho phép truy cập màn hình
                return true;
            }
        }
        //không trùng thì không được phép truy cập màn hình
        return false;
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
