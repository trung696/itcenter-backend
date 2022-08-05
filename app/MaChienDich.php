<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MaChienDich extends Model
{
    protected $table = 'ma_khuyen_mai';
    protected $fillable = ['tb1.id', 'tb1.ma_khuyen_mai', 'tb1.id_chien_dich', 'tb1.senduser', 'tb1.trang_thai', 'tb1.created_at', 'tb1.updated_at'];
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
    public function loadListWithPager($params = array(), $id = null)
    {
        $query = DB::table($this->table . ' as tb1')
            ->select($this->fillable)
            ->where('tb1.id_chien_dich', $id)
            ->orderBy('tb1.created_at', 'DESC');
        if (isset($params['search_ma']) && strlen($params['search_ma']) > 0) {
            $query->where('tb1.ma_khuyen_mai', 'like', '%' . $params['search_ma'] . '%');
        }
        if (isset($params['trang_thai']) && $params['trang_thai']) {
            $query->where('tb1.trang_thai', $params['trang_thai']);
        }
        $list = $query->paginate(10, ['tb1.id']);
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
    public function saveNew($params)
    {
        if (empty($params['ma_add'])) {
            Log::warning(__METHOD__ . ' Không xác định thông tin người cập nhật');
            Session::push('errors', 'Không xác định thông tin người cập nhật');
            return null;
        }
        $data =  array_merge($params['cols'], [
            'ma_khuyen_mai' => $params['cols']['ma_khuyen_mai'],
            'id_chien_dich' => $params['cols']['id_chien_dich'],
            'senduser' => 0,
            'trang_thai' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        $res = DB::table($this->table)->insertGetId($data);
        return $res;
    }

    public function loadCheckName($name, $params = null)
    {
        $query = DB::table($this->table . ' as tb1')
            ->select($this->fillable)
            ->where('tb1.ma_khuyen_mai', '=', $name);
        $list = $query->first();
        return $list;
    }
    public function loadMa($id_chien_dich)
    {
        $query = DB::table($this->table . ' as tb1')
            ->select($this->fillable)
            ->where('tb1.id_chien_dich', $id_chien_dich)
            ->where('tb1.trang_thai', '=', 0)
            ->where('tb1.senduser', '=', 0);
        return $query->get();
    }
    public function saveUpdateTT($ma_khuyen_mai)
    {
        $res = DB::table($this->table)
            ->where('ma_khuyen_mai', $ma_khuyen_mai)
            ->limit(1)
            ->update(['trang_thai' => 1]);
        return $res;
    }
    public function saveUpdateSend($ma_khuyen_mai)
    {
        $res = DB::table($this->table)
            ->where('ma_khuyen_mai', $ma_khuyen_mai)
            ->limit(1)
            ->update(['senduser' => 1]);
        return $res;
    }
    public function saveUpdate($params)
    {
        if (empty($params['danhmuc_edit'])) {
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
