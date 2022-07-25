<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class HocVien extends Model
{
    protected $table = 'hoc_vien';
    protected $fillable = ['ho_ten', 'ngay_sinh','gioi_tinh', 'so_dien_thoai', 'email', 'hinh_anh', 'trang_thai','password','tokenActive','created_at','updated_at'];
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

    public function loadListWithPager($params = array())
    {
        $query = DB::table($this->table . ' as tb1')
            ->select($this->fillable);

        if (isset($params['search_sdt_gmail']) && strlen($params['search_sdt_gmail']) > 0) {
            $query->where('tb1.so_dien_thoai', 'like', '%' . $params['search_sdt_gmail'] . '%')
                ->orWhere('tb1.email', 'like', '%' . $params['search_sdt_gmail'] . '%')
                ->orWhere('tb1.ho_ten', 'like', '%' . $params['search_sdt_gmail'] . '%');
        }
        $list = $query->paginate(10, ['tb1.id']);
        return $list;
    }
    public function loadCountHV()
    {
        $query = DB::table($this->table . ' as tb1')
            ->select($this->fillable);
        $list = $query->where('tb1.trang_thai', '=', 1)->count();
        return $list;
    }
    public function saveNew($params)
    {
        unset($params['cols']['stripeToken']);
        unset($params['cols']['stripeEmail']);
        unset($params['cols']['amountInCents']);
        $data = array_merge($params['cols'], [
            'ho_ten' => $params['cols']['ho_ten'],
            'ngay_sinh' => $params['cols']['ngay_sinh'],
            'so_dien_thoai' => $params['cols']['so_dien_thoai'],
            'email' => $params['cols']['email'],
            'trang_thai' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        $res = DB::table('hoc_vien')->insertGetId($data);
        return $res;
    }
    public function saveNewAdmin($params)
    {
        if (!empty($params['cols']['id_lop_hoc'])) {
            unset($params['cols']['id_khoa_hoc']);
            unset($params['cols']['id_lop_hoc']);
        }
        if (isset($params['cols']['hinh_anh'])) {
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
    public function loadCheckHocVien($name, $params = null)
    {
        $query = DB::table($this->table . ' as tb1')
            ->select($this->fillable)
            ->where('tb1.email', '=', $name);
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
    public function saveUpdate($params)
    {
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
