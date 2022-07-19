<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class LopHoc extends Model
{
    protected $table = 'lop_hoc';
    protected $fillable = ['tb1.id', 'tb1.ten_lop_hoc', 'tb1.thoi_gian_bat_dau', 'tb1.thoi_gian_ket_thuc', 'tb1.lich_hoc', 'tb1.id_dia_diem', 'tb1.id_khoa_hoc', 'tb1.id_giang_vien', 'tb1.so_cho', 'tb1.trang_thai', 'tb1.created_at', 'tb1.updated_at'];
    public function loadOne($id, $params = null)
    {

        $query = DB::table($this->table . ' as tb1')
            ->select($this->fillable)
            ->where('tb1.id', '=', $id);

        $obj = $query->first();
        return $obj;
    }
    public function loadIdKhoaHoc($id, $id_giangvien, $params = null)
    {

        $query = DB::table($this->table . ' as tb1')
            ->select($this->fillable)
            ->where('tb1.id_khoa_hoc', '=', $id)
            ->where('tb1.id_giang_vien', '=', '0')
            ->orWhere('tb1.id_giang_vien', '=', $id_giangvien);
        if (isset($params['search_ten_lop_hoc']) && strlen($params['search_ten_lop_hoc']) > 0) {
            $query->where('tb1.ten_lop_hoc', 'like', '%' . $params['search_ten_lop_hoc'] . '%');
        }
        $obj = $query->paginate(10, ['tb1.id']);
        return $obj;
    }
    public function loadOneID($id, $params = null)
    {

        $query = DB::table($this->table . ' as tb1')
            ->select('tb1.id', 'tb1.ten_lop_hoc', 'tb1.thoi_gian_bat_dau', 'tb1.thoi_gian_ket_thuc', 'tb1.lich_hoc', 'tb1.id_dia_diem', 'tb1.id_khoa_hoc', 'tb1.id_giang_vien', 'tb1.so_cho')
            ->where('tb1.id', '=', $id);

        $obj = $query->first();
        return $obj;
    }
    public function loadOneIDHV($id, $params = null)
    {

        $query = DB::table($this->table . ' as tb1')
            ->select('tb1.id', 'tb1.ten_lop_hoc', 'tb1.thoi_gian_bat_dau', 'tb1.thoi_gian_ket_thuc', 'tb1.lich_hoc', 'tb1.id_dia_diem', 'tb1.id_khoa_hoc', 'tb1.id_giang_vien', 'tb1.so_cho', 'tb2.trang_thai')
            ->leftJoin('dang_ky as tb2', 'tb2.id_lop_hoc', '=', 'tb1.id')
            ->where('tb2.id_hoc_vien', $id);
        if (isset($params['search_ten_lop_hoc']) && strlen($params['search_ten_lop_hoc']) > 0) {
            $query->where('tb1.ten_lop_hoc', 'like', '%' . $params['search_ten_lop_hoc'] . '%');
        }
        if (isset($params['search_ngay_khai_giang_array']) && count($params['search_ngay_khai_giang_array']) == 2) {
            $query->whereBetween('tb1.thoi_gian_bat_dau', $params['search_ngay_khai_giang_array']);
        }
        if (isset($params['trang_thai']) && strlen($params['trang_thai']) > 0) {
            $query->where('tb2.trang_thai', $params['trang_thai']);
        }
        $obj = $query->paginate(10, ['tb1.id']);
        return $obj;
    }
    public function loadListWithPager($params = array(), $id = null)
    {
        $query = DB::table($this->table . ' as tb1')
            ->select($this->fillable)
            ->where('tb1.id_khoa_hoc', $id);
        if (isset($params['search_ten_lop']) && strlen($params['search_ten_lop']) > 0) {
            $query->where('tb1.ten_lop_hoc', 'like', '%' . $params['search_ten_lop'] . '%');
        }

        if (isset($params['search_ngay_khai_giang_array']) && count($params['search_ngay_khai_giang_array']) == 2) {
            $query->whereBetween('tb1.thoi_gian_bat_dau', $params['search_ngay_khai_giang_array']);
        }

        if (isset($params['trang_thai']) && $params['trang_thai']) {
            $query->where('tb1.trang_thai', $params['trang_thai']);
        }
        $lists = $query->paginate(10, ['tb1.id']);
        return $lists;
    }
    public function loadListWithPagerLP($params = array())
    {
        $now = date('Y-m-d');
        $query = DB::table($this->table . ' as tb1')
            ->select($this->fillable);
        $lists = $query->get();
        return $lists;
    }
    public function loadCountLH()
    {
        $query = DB::table($this->table . ' as tb1')
            ->select($this->fillable);
        $list = $query->where('tb1.trang_thai', '=', 1)->count();
        return $list;
    }
    public function saveNew($params)
    {
        if (empty($params['user_add'])) {
            Log::warning(__METHOD__ . ' Không xác định thông tin người cập nhật');
            Session::push('errors', 'Không xác định thông tin người cập nhật');
            return null;
        }

        $data =  array_merge($params['cols'], [
            'ten_lop_hoc' => $params['cols']['ten_lop_hoc'],
            'ca_hoc' => $params['cols']['ca_hoc'],
            'thoi_giang_khai_giang' => $params['cols']['thoi_giang_khai_giang'],
            'id_dia_diem' => $params['cols']['id_dia_diem'],
            'id_khoa_hoc' => $params['cols']['id_khoa_hoc'],
            'id_giang_vien' => $params['cols']['id_giang_vien'],
            'trang_thai' => 1,
            'so_cho' => $params['cols']['so_cho'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $res = DB::table($this->table)->insertGetId($data);

        return $res;
    }
    public function saveUpdateSoCho($udateSoCho)
    {

        $res = DB::table($this->table)
            ->where('id', $udateSoCho['id'])
            ->limit(1)
            ->update(['so_cho' => $udateSoCho['so_cho']]);
        return $res;
    }
    public function saveUpdateXepLop($udateGiangVien)
    {

        $res = DB::table($this->table)
            ->where('id', $udateGiangVien['id'])
            ->limit(1)
            ->update(['id_giang_vien' => $udateGiangVien['id_giang_vien']]);
        return $res;
    }
    public function saveUpdate($params)
    {
        if (empty($params['lophoc_edit'])) {
            Log::warning(__METHOD__ . ' Không xác định thông tin người cập nhật');
            Session::push('errors', 'Không xác định thông tin người cập nhật');
            return null;
        }

        if (empty($params['cols']['id'])) {
            Session::push('errors', 'Không xác bản ghi cần cập nhật');
            return null;
        }

        $dataUpdate = [];
        foreach ($params['cols'] as $colName => $val) {
            if ($colName == 'id') continue;

            if (in_array('tb1.' . $colName, $this->fillable))
                $dataUpdate[$colName] = (strlen($val) == 0) ? null : $val;
        }
        $res = DB::table($this->table)
            ->where('id', $params['cols']['id'])
            ->limit(1)
            ->update($dataUpdate);
        return $res;
    }
}
