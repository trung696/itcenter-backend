<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Student extends Model
{
    protected $table = 'students';
    protected $fillable = ['users_id', 'name', 'email', 'address', 'phone_number', 'sex', 'status', 'avatar'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }
    public function loadCheckHocVien($name, $params = null)
    {
        $query = DB::table($this->table . ' as tb1')
            ->select($this->fillable)
            ->where('tb1.email', '=', $name);
        $list = $query->first();
        return $list;
    }
    public function saveNewAdmin($params)
    {
        if (!empty($params['cols']['id_khoa_hoc'])) {
            unset($params['cols']['id_khoa_hoc']);
            unset($params['cols']['pham_tram_giam']);
            unset($params['cols']['id_lop_hoc']);
        }
        if (isset($params['cols']['hinh_anh'])) {
            $data = array_merge($params['cols'], [
                'ho_ten' => $params['cols']['name'],
                'ngay_sinh' => $params['cols']['ngay_sinh'],
                'so_dien_thoai' => $params['cols']['so_dien_thoai'],
                'email' => $params['cols']['email'],
                'hinh_anh' => $params['cols']['hinh_anh'],
                'trang_thai' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        } else {
            $data = array_merge($params['cols'], [
                'ho_ten' => $params['cols']['ho_ten'],
                'ngay_sinh' => $params['cols']['ngay_sinh'],
                'so_dien_thoai' => $params['cols']['so_dien_thoai'],
                'email' => $params['cols']['email'],
                'hinh_anh' => null,
                'trang_thai' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
        $res = DB::table('hoc_vien')->insertGetId($data);
        return $res;
    }
}
