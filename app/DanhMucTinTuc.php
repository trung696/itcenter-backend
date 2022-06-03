<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class DanhMucTinTuc extends Model
{
    protected $table = 'danh_muc_tin_tuc';
    protected $fillable = ['tb1.id','tb1.ten_danh_muc','tb1.trang_thai','tb1.created_at','tb1.updated_at'];
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
        if(isset($params['search_ten_danh_muc_tin_tuc']) && strlen($params['search_ten_danh_muc_tin_tuc'])>0){
            $query->where('tb1.ten_danh_muc', 'like', '%' .$params['search_ten_danh_muc_tin_tuc'].'%');
        }
        $list = $query->where('tb1.trang_thai', '=', 1)->paginate(10, ['tb1.id']);
        return $list;
    }
}