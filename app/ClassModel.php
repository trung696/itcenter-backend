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
    protected $fillable = ['tb1.id', 'tb1.name', 'tb1.slot', 'tb1.start_date', 'tb1.end_date', 'tb1.lecturer_id', 'tb1.location_id', 'tb1.course_id', 'tb1.id_ca', 'tb1.created_at', 'tb1.updated_at'];
    public $timestamps = false;
    public function course()
    {
        return $this->belongsTo(Course::class);
    }


    public function dangKi()
    {
        return $this->hasMany(DangKy::class, 'id_lop_hoc', 'id');
    }

    public function createStdClass()
    {
        $objItem = new \stdClass();
        foreach ($this->fillable as $field) {
            $field = substr($field, 4);
            $objItem->$field = null;
        }
        return $objItem;
    }

    public function loadOne($id, $params = null)
    {

        $query = DB::table($this->table . ' as tb1')
            ->select($this->fillable)
            ->where('tb1.id', '=', $id);

        $obj = $query->first();
        return $obj;
    }
    public function loadOneID($id, $params = null)
    {

        $query = DB::table($this->table . ' as tb1')
            ->select('tb1.id', 'tb1.name', 'tb1.slot', 'tb1.start_date', 'tb1.end_date', 'tb1.lecturer_id', 'tb1.location_id', 'tb1.course_id', 'tb1.id_ca')
            ->where('tb1.id', '=', $id);

        $obj = $query->first();
        return $obj;
    }
    public function loadOneIDHV($id, $params = null)
    {

        $query = DB::table($this->table . ' as tb1')
            ->select('tb1.id', 'tb1.name', 'tb1.start_date', 'tb1.end_date', 'tb1.location_id', 'tb1.course_id', 'tb1.lecturer_id', 'tb1.slot', 'tb2.trang_thai')
            ->leftJoin('dang_ky as tb2', 'tb2.id_lop_hoc', '=', 'tb1.id')
            ->where('tb2.id_hoc_vien', $id);
        if (isset($params['search_ten_lop_hoc']) && strlen($params['search_ten_lop_hoc']) > 0) {
            $query->where('tb1.name', 'like', '%' . $params['search_ten_lop_hoc'] . '%');
        }
        if (isset($params['search_ngay_khai_giang_array']) && count($params['search_ngay_khai_giang_array']) == 2) {
            $query->whereBetween('tb1.start_date', $params['search_ngay_khai_giang_array']);
        }
        if (isset($params['trang_thai']) && strlen($params['trang_thai']) > 0) {
            $query->where('tb2.trang_thai', $params['trang_thai']);
        }
        $obj = $query->paginate(10, ['tb1.id']);
        return $obj;
    }

    /** Hàm lấy danh sách có phân trang
     * @param array $params
     * @return mixed
     */
    public function loadListWithPager($params = array())
    {
        $query = DB::table($this->table . ' as tb1')
            ->select($this->fillable);
        if (isset($params['search_name_class']) && strlen($params['search_name_class']) > 0) {
            $query->where('tb1.name', 'like', '%' . $params['search_name_class'] . '%');
        }
        if (isset($params['search_danh_muc_khoa_hoc']) && $params['search_danh_muc_khoa_hoc']) {
            $query->where('tb1.category_id', $params['search_danh_muc_khoa_hoc']);
        }
        $list = $query->paginate(10, ['tb1.id']);
        return $list;
    }

    public function loadListIdWithPager($params = array(), $id = null)
    {
        $query = DB::table($this->table . ' as tb1')
            ->select($this->fillable)
            ->where('tb1.category_id', $id);
        if (isset($params['search_ten_khoa_hoc']) && strlen($params['search_ten_khoa_hoc']) > 0) {
            $query->where('tb1.ten_khoa_hoc', 'like', '%' . $params['search_ten_khoa_hoc'] . '%');
        }
        $lists = $query->paginate(6, ['tb1.id']);
        return $lists;
    }
    // public function loadActiveClass($where = null)
    // {
    //     $list = DB::table($this->table)->select('id', 'name', 'status');
    //     if ($where != null)
    //         $list->where([$where]);
    //     return $list->get();
    // }
    public function loadListIdAndName($where = null)
    {
        // dd('đã vào đây');
        $list = DB::table($this->table)->select('id', 'name');
        if ($where != null)
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
        $data =  array_merge($params['cols'], [
            'name' => $params['cols']['name'],
            'slot' => $params['cols']['slot'],
            'start_date' => $params['cols']['start_date'],
            'end_date' => $params['cols']['end_date'],
            'lecturer_id' => $params['cols']['lecturer_id'],
            'location_id' => $params['cols']['location_id'],
            'course_id' => $params['cols']['course_id'],
            'id_ca' => $params['cols']['id_ca'],
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

            if (in_array('tb1.' . $colName, $this->fillable))
                $dataUpdate[$colName] = (strlen($val) == 0) ? null : $val;
        }
        $res = DB::table($this->table)
            ->where('id', $params['cols']['id'])
            ->limit(1)
            ->update($dataUpdate);
        // dd($res);
        return $res;
    }
    public function saveUpdateSoCho($udateSoCho)
    {

        $res = DB::table($this->table)
            ->where('id', $udateSoCho['id'])
            ->limit(1)
            ->update(['slot' => $udateSoCho['so_cho']]);
        return $res;
    }
    public function checkCa($idGV)
    {
        $res = DB::table('users as tb2')
            ->select('tb1.name', 'tb1.start_date', 'tb1.end_date', 'tb2.name', 'tb1.id_ca', 'tb1.lecturer_id')
            ->leftJoin($this->table . ' as tb1', 'tb2.id', '=', 'tb1.lecturer_id')
            ->where('tb2.id', $idGV['id'])
            ->where('tb1.id_ca', $idGV['id_ca'])
            ->where('tb1.id', '!=', $idGV['id_lop'])
            ->count();
        return $res;
    }
    public function getDate($idGV)
    {
        $check = DB::table($this->table . ' as tb1')
            ->select('tb1.name as Classname', 'tb1.start_date', 'tb1.end_date', 'tb2.name as Ten_giang_vien', 'tb1.id_ca', 'tb1.lecturer_id')
            ->leftJoin('users as tb2', 'tb1.lecturer_id', '=', 'tb2.id')
            ->where('tb2.id', $idGV['id'])
            ->where('tb1.id_ca', $idGV['id_ca'])
            ->where('tb1.id', '!=', $idGV['id_lop']);

        return $check->get()->all();
    }
    public function loadActiveClass()
    {
        $now = date('Y-m-d');
        $query = DB::table('class as tb1')
            ->select('tb1.name as Tên lớp')
            ->where('tb1.start_date', '<=', $now)
            ->where('tb1.end_date', '>=', $now)
            ->groupBy('tb1.lecturer_id')->get();
        // ->where('tb1.status', '=', 1)->get();
        return $query;
    }
    // public function checkThoiGian($idGV)
    // {

    //     $res = DB::table('users as tb2')
    //         ->select('tb1.name', 'tb1.start_date', 'tb1.end_date', 'tb2.name', 'tb1.id_ca', 'tb1.lecturer_id')
    //         ->leftJoin($this->table . ' as tb1', 'tb2.id', '=', 'tb1.lecturer_id')
    //         ->where('tb2.id', $idGV['id'])
    //         ->where('tb1.id_ca', $idGV['id_ca'])
    //         ->where('tb1.id', '!=', $idGV['id_lop'])
    //         ->count();
    //     // dd($res);
    //     return $res;
    // }
}
