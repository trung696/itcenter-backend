<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;


class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "users";
    protected $fillable = ['name', 'email', 'password', 'repassword', 'address', 'phone', 'status', 'avatar', 'tokenActive'];

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

    public function loadListIdAndName($where = null)
    {
        $list = DB::table($this->table)->select('id', 'name', 'status');
        if ($where != null)
            $list->where([$where]);
        return $list->get();
    }
    public function loadOne($id, $params = null)
    {

        $query = DB::table($this->table . ' as tb1')
            ->select($this->fillable)
            ->where('tb1.id', '=', $id);

        $obj = $query->first();
        return $obj;
    }
    public function loadActive()
    {
        $query = DB::table($this->table . ' as tb1')
            ->select($this->fillable)
            ->where('tb1.status', '=', 1)->get();
        return $query;
    }
    public function loadInClass()
    {

        $query = DB::table('class as tb1')
            ->select('tb1.name as Tên lớp', 'tb2.name as Tên giáo viên')
            ->leftJoin('users as tb2', 'tb2.id', '=', 'tb1.lecturer_id')->groupBy('tb1.lecturer_id')->get();
        // ->where('tb1.status', '=', 1)->get();
        return $query;
    }
    public function loadDay($time)
    {
        $query = DB::table('class as tb1')
            ->select('tb1.name as Tên lớp', 'tb2.name as Tên giáo viên', 'tb1.start_date', 'tb1.end_date')
            ->leftJoin('users as tb2', 'tb2.id', '=', 'tb1.lecturer_id')->get();
        // 
        return $query->all();
    }
}
