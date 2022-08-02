<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Course extends Model
{
    protected $table = 'course';
    protected $fillable = ['tb1.id','tb1.name','tb1.description','tb1.status','tb1.image','tb1.category_id','tb1.created_at','tb1.updated_at'];
    public $timestamps = false;

    public function classRoom(){
        return $this->hasMany(ClassModel::class);
    }

  


    public function Document() {
        return $this->hasMany(Document::class,'course_id','id');
    }
    public function courseCategory() {
        return $this->belongsTo(CourseCategory::class,'category_id','id');
    }
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
        if(isset($params['search_ten_khoa_hoc']) && strlen($params['search_ten_khoa_hoc'])>0){
            $query->where('tb1.ten_khoa_hoc', 'like', '%' .$params['search_ten_khoa_hoc'].'%');
        }
        if(isset($params['search_danh_muc_khoa_hoc'])&& $params['search_danh_muc_khoa_hoc']){
            $query->where('tb1.category_id', $params['search_danh_muc_khoa_hoc']);
        }
        $list = $query->where('tb1.status', '=', 1)->paginate(10, ['tb1.id']);
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
        $list = DB::table($this->table)->select('id', 'name','status','price');
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
            'description' => $params['cols']['description'],
            'image' => $params['cols']['image'],
            'status' => 1,
            'category_id' => $params['cols']['category_id'],
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
}