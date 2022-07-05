<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CentralFacility extends Model
{
    protected $table = 'central_facility';
    protected $fillable = ['tb1.id','tb1.name','tb1.address','tb1.description','tb1.created_at','tb1.updated_at'];
    public $timestamps = false;
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
        if(isset($params['search_ten_dia_diem']) && strlen($params['search_ten_dia_diem'])>0){
            $query->where('tb1.ten_dia_diem', 'like', '%' .$params['search_ten_dia_diem'].'%');
        }
        $list = $query->paginate(10, ['tb1.id']);
        return $list;
    }
    public function loadListIdAndName($where = null){
        $list = DB::table($this->table)->select('id', 'name','status');
        if($where != null)
            $list->where([$where]);
        return $list->get();
    }
    public function saveNew($params){
        if (empty($params['diadiem_add'])){
            Log::warning(__METHOD__ . 'Không xác định thông tin người cập nhập');
            Session::push('errors', 'Không xác định thông tin người cập nhập');
            return null;
        }
        $data = array_merge($params['cols'],['name' => $params['cols']['name'],
            'created_at'=> date('Y-m-d H:i:s'),
            'updated_at'=> date('Y-m-d H:i:s'),
        ]);
        $res = DB::table($this->table)->insertGetId($data);
        return $res;
    }
    public function saveUpdate($params)
    {
        if (empty($params['diadiem_edit'])) {
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

            if (in_array('tb1.'.$colName, $this->fillable))
                $dataUpdate[$colName] = (strlen($val)==0)?null:$val;
                // dd($dataUpdate[$colName]);
        }

        $res = DB::table($this->table)
            ->where('id', $params['cols']['id'])
            ->limit(1)
            ->update($dataUpdate);
            // dd($res);
        return $res;
    }
    public function loadOne($id, $params = null){
        $query = DB::table($this->table.' as tb1')
            ->select( $this->fillable)
            ->where('tb1.id', '=', $id);
        $obj = $query->first();
        return $obj;
    }
}