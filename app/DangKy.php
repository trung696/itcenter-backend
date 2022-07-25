<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class DangKy extends Model
{
    protected $table = 'dang_ky';
    protected $fillable = ['ngay_dang_ky', 'id_lop_hoc', 'gia_tien', 'id_hoc_vien', 'trang_thai', 'created_at', 'updated_at'];
    public function class()
    {
        return $this->belongsTo(ClassModel::class,'id_lop_hoc','id');
    }
    
    public function loadListWithPager($params = array(), $id = null)
    {
        $query = DB::table($this->table . ' as tb1')
            ->select('tb2.id', 'tb2.ho_ten', 'tb2.ngay_sinh', 'tb1.ngay_dang_ky', 'tb2.so_dien_thoai', 'tb2.email', 'tb1.trang_thai')
            ->leftJoin('hoc_vien as tb2', 'tb2.id', '=', 'tb1.id_hoc_vien')
            ->where('tb1.id_lop_hoc', $id);
        $lists = $query->where('tb1.trang_thai', '=', 1)->paginate(10, ['tb1.id']);
        return $lists;
    }
    public function loadListWithPagers($params = array())
    {
        $query = DB::table($this->table . ' as tb1')
            ->select('tb1.id', 'tb2.ho_ten', 'tb2.ngay_sinh', 'tb2.so_dien_thoai', 'tb1.ngay_dang_ky', 'tb2.so_dien_thoai', 'tb2.email', 'tb1.trang_thai', 'tb3.name')
            ->leftJoin('hoc_vien as tb2', 'tb2.id', '=', 'tb1.id_hoc_vien')
            ->leftJoin('class as tb3', 'tb3.id', '=', 'tb1.id_lop_hoc');
        if (isset($params['search_ten_hoc_vien']) && strlen($params['search_ten_hoc_vien']) > 0) {
            $query->where('tb2.ho_ten', 'like', '%' . $params['search_ten_hoc_vien'] . '%');
        }
        if (isset($params['search_ngay_dang_ky']) && strlen($params['search_ngay_dang_ky']) > 0) {
            $query->where('tb1.ngay_dang_ky', $params['search_ngay_dang_ky']);
        }
        $lists = $query->paginate(10, ['tb1.id']);
        return $lists;
    }

    public function saveNew($params)
    {
        $data = array_merge($params, [
            'ngay_dang_ky' => date('Y-m-d'),
            'id_lop_hoc' => $params['id_lop_hoc'],
            'gia_tien' => $params['gia_tien'],
            'id_hoc_vien' => $params['id_hoc_vien'],
            'trang_thai' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        $res = DB::table('dang_ky')->insertGetId($data);
        return $res;
    }
    public function saveNewAdmin($params)
    {
        $data = array_merge($params, [
            'ngay_dang_ky' => date('Y-m-d'),
            'id_lop_hoc' => $params['id_lop_hoc'],
            'gia_tien' => $params['gia_tien'],
            'id_hoc_vien' => $params['id_hoc_vien'],
            'trang_thai' => $params['trang_thai'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        $res = DB::table('dang_ky')->insertGetId($data);
        return $res;
    }
    public function saveNewOnline($params)
    {
        $data = array_merge($params, [
            'ngay_dang_ky' => date('Y-m-d'),
            'id_lop_hoc' => $params['id_lop_hoc'],
            'gia_tien' => $params['gia_tien'],
            'id_hoc_vien' => $params['id_hoc_vien'],
            'trang_thai' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        $res = DB::table('dang_ky')->insertGetId($data);
        return $res;
    }
    public function saveNewHocVien($params)
    {
        $data = array_merge($params['cols'], [
            'ho_ten' => $params['cols']['ho_ten'],
            'ngay_sinh' => $params['cols']['ngay_sinh'],
            'so_dien_thoai' => $params['cols']['so_dien_thoai'],
            'email' => $params['cols']['email'],
            'hinh_anh' => $params['cols']['hinh_anh'],
            'trang_thai' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        $res = DB::table('hoc_vien')->insertGetId($data);
        return $res;
    }
    public function loadCheckName($id_lop_hoc, $id_hoc_vien, $params = null)
    {
        $query = DB::table($this->table . ' as tb1')
            ->select($this->fillable)
            ->where('tb1.id_lop_hoc', '=', $id_lop_hoc)
            ->where('tb1.id_hoc_vien', '=', $id_hoc_vien);
        $list = $query->first();
        return $list;
    }
    public function loadOne($id, $params = null)
    {

        $query = DB::table($this->table . ' as tb1')
            ->select($this->fillable)
            ->where('tb1.id', '=', $id);

        $obj = $query->first();
        return $obj;
    }
    public function updateDangKy($arrDangKy)
    {
        $res = DB::table($this->table)
            ->where('id', $arrDangKy['id'])
            ->limit(1)
            ->update(['id_lop_hoc' => $arrDangKy['id_lop_hoc'], 'trang_thai' => $arrDangKy['trang_thai']]);
        return $res;
    }
}
