<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChienDich extends Model
{
    protected $table = 'chien_dich';
    protected $fillable = ['tb1.id','tb1.ten_chien_dich','tb1.phan_tram_giam','tb1.ngay_bat_dau','tb1.ngay_ket_thuc','tb1.trang_thai','tb1.created_at','tb1.updated_at'];
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
        if(isset($params['search_chien_dich']) && strlen($params['search_chien_dich'])>0){
            $query->where('tb1.ten_chien_dich', 'like', '%' .$params['search_chien_dich'].'%');
        }
        $list = $query->where('tb1.trang_thai', '=', 1)->paginate(10, ['tb1.id']);
        return $list;
    }
    public function saveNew($params){
        if (empty($params['chiendich_add'])){
            Log::warning(__METHOD__ . 'Không xác định thông tin người cập nhập');
            Session::push('errors', 'Không xác định thông tin người cập nhập');
            return null;
        }
        $data = array_merge($params['cols'],['ten_chien_dich' => $params['cols']['ten_chien_dich'],
            'phan_tram_giam' => $params['cols']['phan_tram_giam'],
            'ngay_bat_dau' => $params['cols']['ngay_bat_dau'],
            'ngay_ket_thuc' => $params['cols']['ngay_ket_thuc'],
            'trang_thai' => 1,
            'created_at'=> date('Y-m-d H:i:s'),
            'updated_at'=> date('Y-m-d H:i:s'),
        ]);
        $res = DB::table($this->table)->insertGetId($data);
        return $res;
    }
    public function loadOne($id, $params = null){
        $query = DB::table($this->table.' as tb1')
            ->select( $this->fillable)
            ->where('tb1.id', '=', $id);
        $obj = $query->first();
        return $obj;
    }
    public function saveUpdate($params)
    {
        if (empty($params['chiendich_edit'])) {
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
        }
        $res = DB::table($this->table)
            ->where('id', $params['cols']['id'])
            ->limit(1)
            ->update($dataUpdate);
        return $res;
    }
    public function saveDelete($params)
    {
        if (empty($params['chiendich_delete'])) {
            Log::warning(__METHOD__ . ' Không xác định thông tin người cập nhật');
            Session::push('errors', 'Không xác định thông tin người cập nhật');
            return null;
        }
        if (empty($params['cols']['id'])) {
            Session::push('errors', 'Không xác bản ghi cần cập nhật');
            return null;
        }

        $res = DB::table($this->table)
            ->where('id', $params['cols']['id'])
            ->limit(1)
            ->update(['trang_thai'=>0]);
        return $res;
    }
}