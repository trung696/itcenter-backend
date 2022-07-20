<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ClassModel extends Model
{
    protected $table = 'class';
    protected $fillable = ['tb1.id','tb1.name','tb1.price','tb1.slot','tb1.start_date','tb1.end_date','tb1.lecturer_id','tb1.location_id','tb1.course_id','tb1.created_at','tb1.updated_at'];
    public $timestamps = false;
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    
    public function createStdClass(){
        $objItem = new \stdClass();
        foreach ($this->fillable as $field){
            $field = substr($field,4);
            $objItem->$field = null;
        }
        return $objItem;
    }

    public function loadOne($id, $params = null)
    {

        $query = DB::table($this->table.' as tb1')
       ->select( $this->fillable)
            ->where('tb1.id', '=', $id);

        $obj = $query->first();
        return $obj;
    }
    public function loadOneID($id, $params = null)
    {

        $query = DB::table($this->table.' as tb1')
            ->select( 'tb1.id','tb1.name','tb1.price','tb1.slot','tb1.start_date','tb1.end_date','tb1.lecturer_id','tb1.location_id','tb1.course_id')
            ->where('tb1.id', '=', $id);

        $obj = $query->first();
        return $obj;
    }
    /** Hàm lấy danh sách có phân trang
     * @param array $params
     * @return mixed
     */
    public function loadListWithPager($params = array()){
        $query = DB::table($this->table.' as tb1')
            ->select($this->fillable);
        if(isset($params['search_name_class']) && strlen($params['search_name_class'])>0){
            $query->where('tb1.name', 'like', '%' .$params['search_name_class'].'%');
        }
        if(isset($params['search_danh_muc_khoa_hoc'])&& $params['search_danh_muc_khoa_hoc']){
            $query->where('tb1.category_id', $params['search_danh_muc_khoa_hoc']);
        }
        $list = $query->paginate(10, ['tb1.id']);
        return $list;
    }

    public function loadListIdWithPager($params = array(),$id = null){
        $query = DB::table($this->table.' as tb1')
            ->select($this->fillable)
            ->where('tb1.category_id',$id);
        if(isset($params['search_ten_khoa_hoc']) && strlen($params['search_ten_khoa_hoc'])>0){
            $query->where('tb1.ten_khoa_hoc', 'like', '%' .$params['search_ten_khoa_hoc'].'%');
        }
        $lists = $query->paginate(6, ['tb1.id']);
        return $lists;
    }

    public function loadListIdAndName($where = null){
        $list = DB::table($this->table)->select('id', 'name','status');
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
            'name' => $params['cols']['name'],
            'price' => $params['cols']['price'],
            'slot' => $params['cols']['slot'],
            'start_date' => $params['cols']['start_date'],
            'end_date' => $params['cols']['end_date'],
            'lecturer_id' => $params['cols']['lecturer_id'],
            'location_id' => $params['cols']['location_id'],
            'course_id' => $params['cols']['course_id'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        $res = DB::table($this->table)->insertGetId($data);
        return $res;
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

            if (in_array('tb1.'.$colName, $this->fillable))
                $dataUpdate[$colName] = (strlen($val)==0)?null:$val;
        }
        $res = DB::table($this->table)
            ->where('id', $params['cols']['id'])
            ->limit(1)
            ->update($dataUpdate);
            // dd($res);
        return $res;
    }
}