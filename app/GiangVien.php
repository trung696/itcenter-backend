<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class GiangVien extends Model
{
    protected $table = 'giang_vien';
    protected $fillable = ['tb1.id', 'tb1.ten_giang_vien', 'tb1.hinh_anh_giang_vien', 'tb1.thong_tin_giang_vien', 'tb1.trang_thai', 'tb1.created_at', 'tb1.updated_at'];
    public $timestamps = false;

    public function createStdClass()
    {
        $objItem = new \stdClass();
        foreach ($this->fillable as $field) {
            $field = substr($field, 4);
            $objItem->$field = null;
        }
        return $objItem;
    }

    /** Hàm lấy danh sách có phân trang
     * @param array $params
     * @return mixed
     */
    public function loadListWithPager($params = array())
    {
        $query = DB::table($this->table . ' as tb1')
            ->select('tb1.id', 'tb1.ten_giang_vien', 'tb1.hinh_anh_giang_vien', 'tb1.thong_tin_giang_vien', 'tb1.trang_thai');
        if (isset($params['search_ten_giang_vien']) && strlen($params['search_ten_giang_vien']) > 0) {
            $query->where('tb1.ten_giang_vien', 'like', '%' . $params['search_ten_giang_vien'] . '%');
        }
        $list = $query->where('tb1.trang_thai', '=', 1)->paginate(10, ['tb1.id']);
        return $list;
    }
    public function loadListID($id, $params = null)
    {
        $query = DB::table($this->table . ' as tb1')
            ->select('tb1.id', 'tb1.ten_giang_vien', 'tb1.hinh_anh_giang_vien', 'tb1.thong_tin_giang_vien', 'tb1.trang_thai')
            ->leftJoin('chuyen_mon_day as tb2', 'tb2.id_giang_vien', '=', 'tb1.id')
            ->where('tb2.id_khoa_hoc', $id);

        $list = $query->where('tb1.trang_thai', '=', 1)->paginate(10, ['tb1.id']);
        return $list;
    }
    public function saveNew($params)
    {
        unset($params['cols']['id_khoa_hoc']);
        if (empty($params['user_add'])) {
            Log::warning(__METHOD__ . ' Không xác định thông tin người cập nhật');
            Session::push('errors', 'Không xác định thông tin người cập nhật');
            return null;
        }
        $data =  array_merge($params['cols'], [
            'ten_giang_vien' => $params['cols']['ten_giang_vien'],
            'hinh_anh_giang_vien' => $params['cols']['hinh_anh_giang_vien'],
            'thong_tin_giang_vien' => $params['cols']['thong_tin_giang_vien'],
            'trang_thai' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        $res = DB::table($this->table)->insertGetId($data);
        return $res;
    }
    public function loadCountGV()
    {
        $query = DB::table($this->table . ' as tb1')
            ->select($this->fillable);
        $list = $query->where('tb1.trang_thai', '=', 1)->count();
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
    public function saveUpdate($params)
    {
        unset($params['cols']['id_khoa_hoc']);
        if (empty($params['user_edit'])) {
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
