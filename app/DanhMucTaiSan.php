<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DanhMucTaiSan extends Model
{
    //
    protected $table = 'danh_muc_tai_san';
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
    public function loadListWithPager($params = array())
    {
        $query = DB::table($this->table.' as tb1')
            ->select($this->fillable);
        if (isset($params['search_ten_danh_muc_tai_san']) && strlen($params['search_ten_danh_muc_tai_san']) > 0) {
            $query->where('tb1.ten_danh_muc', 'like', '%' . $params['search_ten_danh_muc_tai_san'] . '%');
        }
        $list = $query->where('trang_thai','=',1)->paginate(10, ['tb1.id']);
        return $list;
    }
    public function loadOne($id, $params = null)
    {

        $query = DB::table($this->table.' as tb1')
//            ->select( array_merge($this->fillable,['tb2.username as uName', 'tb2.fullname as hoten_tuvan']))
            ->where('tb1.id', '=', $id);

        $obj = $query->first();
        return $obj;
    }

    public function saveNew($params)
    {
        if (empty($params['user_add'])) {
            Log::warning(__METHOD__ . ' Không xác định thông tin người cập nhật');
            Session::push('errors', 'Không xác định thông tin người cập nhật');
            return null;
        }
        $data =  array_merge($params['cols'],[
            'ten_danh_muc' => $params['cols']['ten_danh_muc'],
//            'trang_thai' => $params['cols']['trang_thai'],
            'trang_thai' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $res = DB::table($this->table)->insertGetId($data);

        if (empty($res) || !is_numeric($res)) {
            Log::error(__METHOD__ . ':: ' . $res . '-->' . json_encode($data));
        }

        return $res;
    }

    public function saveUpdate($params)
    {
//        $params = ['cols'=>['col'=>'new_value'],'user_edit'=>'id'];

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

        if (empty($res) || !is_numeric($res)) {
//            Log::error(__METHOD__ . ':: ' . $res . '-->' . json_encode($dataUpdate));
        }
        else {//nếu thằng ảnh update thì update bên phía đt luôn//có time thì viết thành event ngon hơn
        }
        return $res;
    }

    public function saveDelete($params)
    {

//        $params = ['cols'=>['col'=>'new_value'],'user_edit'=>'id'];

        if (empty($params['user_edit'])) {
            Log::warning(__METHOD__ . ' Không xác định thông tin người cập nhật');
            Session::push('errors', 'Không xác định thông tin người cập nhật');
            return null;
        }

        if (empty($params['cols']['id'])) {
            Session::push('errors', 'Không xác định bản ghi cần cập nhật');
            return null;
        }


        if ($params['event'] == 'restore') {
            $dataUpdate = ['d_id' => 0,
                'd_time' => null
            ];
        } elseif ($params['event'] == 'delete') {
            $dataUpdate = ['d_id' => $params['asset_edit'],
                'd_time' => date('Y-m-d H:i:s')
            ];
        } else {
            Session::push('errors', 'Không xác định thao tác!');
            return null;
        }


        $res = DB::table($this->table)
            ->where('id', $params['cols']['id'])
            ->limit(1)
            ->update($dataUpdate);

        if (empty($res) || !is_numeric($res)) {
            Log::error(__METHOD__ . ':: ' . $res . '-->' . json_encode($dataUpdate));
        }

        return $res;
    }
    public function loadListIdAndName($where = null)
    {
        $list = DB::table($this->table)->select('id', 'ten_danh_muc','trang_thai');
        if($where != null)
            $list->where([$where]);
        return $list->get();
    }
    public function loadListIdAndNameInListId($mon = [])
    {
        $list = DB::table($this->table)
            ->select('id', 'ma_mh','ten_mh')
            ->where('d_id',0)->whereIn('id', $mon);
        return $list->get();
    }
    public function loadListIdAndNameByLop($id_lop)
    {

        $list = DB::table($this->table .' as tb1')
            ->select('tb1.id', 'tb1.ma_mh','tb1.ten_mh')
            ->join('tb_mon_khoa as tb2','tb1.id','=','tb2.id_mh')
            ->join('tb_lop_khoa as tb3','tb2.id_kh','=','tb3.id_khoa_hoc')
            ->where('tb3.id_lop','=',$id_lop)
            ->get();
        return $list;
    }
    public function checkPhoneNumber($phone){
        $rs = DB::table($this->table)
            ->leftJoin(DB::raw("(select id_ts,max(c_time) as ls_time from tb_ls_cham_soc GROUP by id_ts) as lshv"),"lshv.id_ts",'=',$this->table.".id")
            ->where(function ($query){
                $query->where(function ($query){
                    $query->where(DB::raw("DATEDIFF(NOW(), ls_time)"), ">=",0);
                    $query->where(DB::raw("DATEDIFF(NOW(), ls_time)"), "<=",30);
                });
                $query->orWhere(function ($query){
                    $query->where(DB::raw("DATEDIFF(NOW(), m_time)"), ">=",0);
                    $query->where(DB::raw("DATEDIFF(NOW(), m_time)"), "<=",30);
                });
            })
            ->where('sodienthoai',$phone)
            ->count();
        return $rs;
    }
    public function checkPhoneNumberAjax($phone){
        $rs = DB::table($this->table)->select(DB::raw("concat(hodem, ' ', ten) as hoten"), 'fullname')
            ->leftJoin('tb_user', 'tb_user.id', '=', $this->table.'.id_tuvan')
            ->leftJoin(DB::raw("(select id_ts,max(c_time) as ls_time from tb_ls_cham_soc GROUP by id_ts) as lshv"),"lshv.id_ts",'=',$this->table.".id")
            ->where(function ($query){
                $query->where(function ($query){
                    $query->where(DB::raw("DATEDIFF(NOW(), ls_time)"), ">=",0);
                    $query->where(DB::raw("DATEDIFF(NOW(), ls_time)"), "<=",30);
                });
                $query->orWhere(function ($query){
                    $query->where(DB::raw("DATEDIFF(NOW(), {$this->table}.m_time)"), ">=",0);
                    $query->where(DB::raw("DATEDIFF(NOW(), {$this->table}.m_time)"), "<=",30);
                });
            })
            ->where('sodienthoai',$phone)
            ->first();
        return $rs;
    }
    public function getLichSuChamSoc($id){
        $list = DB::table($this->table.'_repos as tb1')
            ->select('tb1.version_id','tb1.version_time','tb1.content','version_by','tb2.username', 'tb2.fullname')
            ->leftJoin($this->table_user.' as tb2', 'tb2.id', '=', 'tb1.version_by')
            ->where('id_master',$id)
            ->orderBy('version_time','desc')->get();
        return $list;
    }
    public function chuyenGiaoNhieu($params = array()){
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
        $dataUpdate['m_id'] = $params['user_edit'];
        $dataUpdate['m_time'] = date('Y-m-d H:i:s');

        $res = DB::table($this->table)
            ->whereIn('id', $params['cols']['id'])
//            ->where('d_id', '=', 0)
            ->limit(1)
            ->update($dataUpdate);

        if (empty($res) || !is_numeric($res)) {
            Log::error(__METHOD__ . ':: ' . $res . '-->' . json_encode($dataUpdate));
        }

        return $res;
    }
    public function getLopByHocVien($mahv){
        $list = DB::table('hoc_vien_lop_ts as tb1')
            ->select('tb1.id_lophoc','tb2.ten_lop')
            ->leftJoin('lop_ts as tb2','tb2.id','=','tb1.id_lophoc')
            ->where('tb1.id_hocvien',$mahv)
            ->distinct()->get();
        return $list;

    }
    public function countHocVienByTuVan($id_tuvan){
        $count = DB::table($this->table)->where('id_tuvan', $id_tuvan)->count();
        return $count;
    }
    public function exchangeData($tu_van_chuyen, $tu_van_nhan){
        $rs = DB::table($this->table)
            ->where('id_tuvan',$tu_van_chuyen)
            ->update(['id_tuvan'=>$tu_van_nhan, 'log_chuyen_tuvan'=>time().'_'.Auth::id().'_'.$tu_van_chuyen.'=>'.$tu_van_nhan]);
        return $rs;
    }
    public function getByListId($params = []){
        $list = DB::table($this->table)
            ->select('id', DB::raw("(concat(hodem, ' ',ten)) as hoten"))
            ->whereIn('id', $params)
            ->get();
        return $list;
    }
    public function getByTuVanNumber($id_tuvan, $id_hocvien){
        $list = DB::table($this->table)
            ->select('id', DB::raw("(concat(hodem, ' ',ten)) as hoten"), 'sodienthoai')
            ->where('id_tuvan', $id_tuvan)
            ->where('id', 'like', $id_hocvien.'%')
            ->paginate(config('app.backend_row_per_page'));
//            ->get();
        return $list;
    }
    public function checkUseEvent($id_event){
        $count = DB::table($this->table)
            ->where('su_kien', 'like', '%|'.$id_event.'|%')
            ->count();
        return $count;
    }
    public function getByTuVan($id_tuvan){
        $list = DB::table($this->table)
            ->where('id_tuvan', $id_tuvan)
            ->get();
        return $list;
    }
    public function checkUnique($phone, $email){
        $count = DB::table($this->table)
            ->where('sodienthoai', $phone)
            ->orWhere('email', $email)
            ->count();
        return $count;
    }

}
