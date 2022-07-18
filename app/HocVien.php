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
    protected $fillable = ['tb1.id', 'tb1.ho_ten', 'tb1.ngay_sinh', 'tb1.so_dien_thoai', 'tb1.email', 'tb1.hinh_anh', 'tb1.trang_thai', 'tb1.created_at', 'tb1.updated_at'];
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
                ->orWhere('tb1.email', 'like', '%' . $params['search_sdt_gmail'] . '%');
        }
        $list = $query->paginate(10, ['tb1.id']);
        return $list;
    }
    public function saveNew($params)
    {
        if (!empty($params['cols']['stripeToken'])) {
            unset($params['cols']['stripeToken']);
            unset($params['cols']['stripeEmail']);
            unset($params['cols']['amountInCents']);
        }
        $data = array_merge($params['cols'], [
            'ho_ten' => $params['cols']['ho_ten'],
            'ngay_sinh' => $params['cols']['ngay_sinh'],
            'so_dien_thoai' => $params['cols']['so_dien_thoai'],
            'email' => $params['cols']['email'],
            'trang_thai' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
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
}
