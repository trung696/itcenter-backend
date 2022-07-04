<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
class ClassRoom extends Model
{
    protected $table = 'class';
    protected $fillable = ['tb1.id', 'tb1.name', 'tb1.start_date', 'tb1.end_date', 'tb1.lecturer_id', 'tb1.location_id', 'tb1.so_cho', 'tb1.trang_thai', 'tb1.created_at', 'tb1.updated_at'];
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
            ->select( 'tb1.id', 'tb1.ten_lop_hoc', 'tb1.id_khoa_hoc', 'tb1.thoi_gian_khai_giang', 'tb1.so_cho')
            ->where('tb1.id', '=', $id);

        $obj = $query->first();
        return $obj;
    }
    public function loadListWithPager($params = array(),$id = null)
    {
        $query = DB::table($this->table.' as tb1')
            ->select($this->fillable)
            ->where('tb1.id_khoa_hoc',$id);
        if (isset($params['search_ten_lop']) && strlen($params['search_ten_lop']) > 0) {
            $query->where('tb1.ten_lop_hoc', 'like', '%' . $params['search_ten_lop'] . '%');
        }

        if (isset($params['search_ngay_khai_giang_array']) && count($params['search_ngay_khai_giang_array']) == 2) {
            $query->whereBetween('tb1.thoi_giang_khai_giang', $params['search_ngay_khai_giang_array']);
        }

        if (isset($params['trang_thai']) && $params['trang_thai']){
            $query->where('tb1.trang_thai', $params['trang_thai']);
        }
        $lists = $query->paginate(10, ['tb1.id']);
        return $lists;
    }
    public function saveNew($params)
    {
        if (empty($params['user_add'])) {
            Log::warning(__METHOD__ . ' Không xác định thông tin người cập nhật');
            Session::push('errors', 'Không xác định thông tin người cập nhật');
            return null;
        }

//        $latestKhoaHoc = DB::table('khoa_hoc')->orderBy('id','DESC')->first();
//        $latestID = $latestKhoaHoc ? $latestKhoaHoc->id : 0;
//        $makhoa = 'TS'.str_pad($latestID + 1, 5, "0", STR_PAD_LEFT);
        $data =  array_merge($params['cols'],[
            'ten_lop_hoc' => $params['cols']['ten_lop_hoc'],
            'ca_hoc' => $params['cols']['ca_hoc'],
            'thoi_giang_khai_giang' => $params['cols']['thoi_giang_khai_giang'],
            'id_dia_diem' => $params['cols']['id_dia_diem'],
            'id_khoa_hoc' => $params['cols']['id_khoa_hoc'],
            'id_giang_vien' => $params['cols']['id_giang_vien'],
            'trang_thai' => 1,
            'so_cho' => $params['cols']['so_cho'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $res = DB::table($this->table)->insertGetId($data);

        return $res;
    }
    public function saveUpdateSoCho($udateSoCho)
    {

        $res = DB::table($this->table)
            ->where('id', $udateSoCho['id'])
            ->limit(1)
            ->update(['so_cho'=>$udateSoCho['so_cho']]);
        return $res;
    }
}