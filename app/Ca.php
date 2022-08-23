<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class Ca extends Model
{
    protected $table = 'cas';
    protected $fillable =[ 'tb1.id','tb1.ca_hoc','tb1.trang_thai','tb1.key_ca','tb1.created_at','tb1.updated_at'];

    public function createStdClass(){
        $objItem = new \stdClass();
        foreach ($this->fillable as $field){
            $field = substr($field,4);
            $objItem->$field = null;
        }
        return $objItem;
    }

    /** Hàm lấy danh sách có phân trang
     * @param array $params
     * @return mixed
     */
    public function loadListWithPager($params = array()){
        $query = DB::table($this->table.' as tb1')
            ->select($this->fillable);
        if(isset($params['search_ten_ca_hoc']) && strlen($params['search_ten_ca_hoc'])>0){
            $query->where('tb1.ca_hoc', 'like', '%' .$params['search_ten_ca_hoc'].'%');
        }
        $list = $query->where('tb1.trang_thai', '=', 1)->paginate(10, ['tb1.id']);
        return $list;
    }


    public function loadListIdAndName($where = null){
        $list = DB::table($this->table)->select('id', 'ca_hoc','trang_thai');
        if($where != null)
            $list->where([$where]);
        return $list->get();
    }

    public function saveNew($params)
    {
        if (empty($params['user_add'])) {
            Log::warning(__METHOD__ . ' Không xác định thông tin người cập nhật');
            Session::push('errors', 'Không xác định thông tin người cập nhật');
            return null;
        }
        $data =  array_merge($params['cols'],[
            'ca_hoc' => $params['cols']['ca_hoc'],
            'trang_thai' => 1,
            'key_ca' => $params['cols']['key_ca'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        $res = DB::table($this->table)->insertGetId($data);
        return $res;
    }

    public function loadOne($id, $params = null){
        $query = DB::table($this->table.' as tb1')
            ->select($this->fillable)
            ->where('tb1.id', '=', $id);

        $obj = $query->first();
        return $obj;
    }

    public function loadOneID($id, $params = null){
        $query = DB::table($this->table.' as tb1')
            ->select( 'tb1.id', 'tb1.name')
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
        // dd($params['cols']);
        foreach ($params['cols'] as $colName => $val) {
            if ($colName == 'id') continue;
            // dd($colName);
            if (in_array('tb1.'.$colName, $this->fillable))
            // dd('123');
                $dataUpdate[$colName] = (strlen($val)==0)?null:$val;
        }
        // dd($this->fillable);
        $res = DB::table($this->table)
            ->where('id', $params['cols']['id'])
            ->limit(1)
            ->update($dataUpdate);
            // dd($res);
        return $res;
    }
}

