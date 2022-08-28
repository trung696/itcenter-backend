<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class Teacher extends Model
{
    protected $table = 'teachers';
    protected $fillable = ['user_id', 'name', 'email', 'password', 'address', 'phone', 'sex', 'status', 'avatar', 'detail'];

    public function createStdClass()
    {
        $objItem = new \stdClass();
        foreach ($this->fillable as $field) {
            $field = substr($field, 4);
            $objItem->$field = null;
        }
        return $objItem;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

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
        $now = date('Y-m-d');
        $query = DB::table('class as tb1')
            ->select('tb1.name as Tên lớp', 'tb2.name as Tên giáo viên')
            ->leftJoin('teachers as tb2', 'tb2.id', '=', 'tb1.lecturer_id')
            ->where('tb1.start_date', '<=', $now)
            ->where('tb1.end_date', '>=', $now)
            ->groupBy('tb1.lecturer_id')->get();
        // ->where('tb1.status', '=', 1)->get();
        return $query;
    }
    public function loadDay($time)
    {
        $query = DB::table('class as tb1')
            ->select('tb1.name as Tên lớp', 'tb2.name as Tên giáo viên', 'tb1.start_date', 'tb1.end_date')
            ->leftJoin('teachers as tb2', 'tb2.id', '=', 'tb1.lecturer_id')->get();
        // 
        return $query->all();
    }
}
