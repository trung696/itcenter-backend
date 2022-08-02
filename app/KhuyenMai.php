<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class KhuyenMai extends Model
{
    protected $table = 'khuyen_mai';
    protected $fillable = ['tb1.id', 'tb1.ma_khuyen_mai', 'tb1.ten_khuyen_mai', 'tb1.phan_tram_khuyen_mai', 'tb1.ngay_bat_dau', 'tb1.ngay_ket_thuc', 'tb1.hinh_anh_khuyen_mai', 'tb1.trang_thai', 'tb1.created_at', 'tb1.updated_at'];
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
        if (isset($params['search_khuyen_mai']) && strlen($params['search_khuyen_mai']) > 0) {
            $query->where('tb1.ma_khuyen_mai', 'like', '%' . $params['search_khuyen_mai'] . '%')
                ->orWhere('tb1.ten_khuyen_mai', 'like', '%' . $params['search_khuyen_mai'] . '%');
        }
        $list = $query->where('tb1.trang_thai', '=', 1)->paginate(10, ['tb1.id']);
        return $list;
    }
    public function loadCheckName($name, $params = null)
    {
        $query = DB::table($this->table . ' as tb1')
            ->select($this->fillable)
            ->where('tb1.ma_khuyen_mai', '=', $name);
        $list = $query->where('tb1.trang_thai', '=', 1)->first();
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
            'ma_khuyen_mai' => $params['cols']['ma_khuyen_mai'],
            'ten_khuyen_mai' => $params['cols']['ten_khuyen_mai'],
            'phan_tram_khuyen_mai' => $params['cols']['phan_tram_khuyen_mai'],
            'ngay_bat_dau' => $params['cols']['ngay_bat_dau'],
            'ngay_ket_thuc' => $params['cols']['ngay_ket_thuc'],
            'hinh_anh_khuyen_mai' => $params['cols']['hinh_anh_khuyen_mai'],
            'trang_thai' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $res = DB::table($this->table)->insertGetId($data);

        return $res;
    }
}
