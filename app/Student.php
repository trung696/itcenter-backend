<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class Student extends Model
{
    //
    //
    //
    protected $table = 'teacher';
    protected $fillable = ['tb1.id', 'tb1.name', 'tb1.address', 'tb1.email', 'tb1.phone_number', 'tb1.avatar', 'tb1.sex', 'tb1.status', 'tb1.created_at', 'tb1.updated_at'];
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
    public function loadListWithPager($params = array())
    {
        $query = DB::table($this->table . ' as tb1')
            ->select($this->fillable);
        // if (isset($params['search_ngay_lap']) && strlen($params['search_ngay_lap']) > 0) {
        //     $query->where('tb1.ngay_lap', 'like', '%' . $params['search_ngay_lap'] . '%');
        // }
        $list = $query->paginate(10, ['tb1.id']);
        return $list;
    }
    public function loadOne($id, $params = null)
    {

        $query = DB::table($this->table . ' as tb1')
            //            ->select( array_merge($this->fillable,['tb2.username as uName', 'tb2.fullname as hoten_tuvan']))
            ->where('tb1.id', '=', $id);

        $obj = $query->first();
        return $obj;
    }

    // public function saveNew($params)
    // {
    //     if (empty($params['user_add'])) {
    //         // Log::warning(__METHOD__ . ' Không xác định thông tin người cập nhật');
    //         Session::push('errors', 'Không xác định thông tin người cập nhật');
    //         return null;
    //     }
    //     $latestBienBan = DB::table('bien_ban_ban_giao_ts')->orderBy('created_at', 'DESC')->first();
    //     $soLuongBienBan = 1;
    //     $data =  array_merge($params['cols'], [
    //         'users_id' => date('Y-m-d H:i:s'),
    //         'name' => $params['cols']['ho_ten_nguoi_giao'],
    //         'chuc_danh_nguoi_giao' => $params['cols']['chuc_danh_nguoi_giao'],
    //         'bo_phan_nguoi_giao' => $params['cols']['bo_phan_nguoi_giao'],
    //         'ho_ten_nguoi_nhan' => $params['cols']['ho_ten_nguoi_nhan'],
    //         'chuc_danh_nguoi_nhan' => $params['cols']['chuc_danh_nguoi_nhan'],
    //         'bo_phan_nguoi_nhan' => $params['cols']['bo_phan_nguoi_nhan'],
    //         'id_don_vi' => $params['cols']['id_don_vi'],
    //         'so_luong_bien_ban' => $soLuongBienBan,
    //         'created_at' => date('Y-m-d H:i:s'),
    //         'updated_at' => date('Y-m-d H:i:s'),
    //     ]);

    //     $res = DB::table($this->table)->insertGetId($data);

    //     // if (empty($res) || !is_numeric($res)) {
    //     //     Log::error(__METHOD__ . ':: ' . $res . '-->' . json_encode($data));
    //     // }

    //     return $res;
    // }

    public function saveUpdate($params)
    {
        //        $params = ['cols'=>['col'=>'new_value'],'user_edit'=>'id'];

        if (empty($params['user_edit'])) {
            // Log::warning(__METHOD__ . ' Không xác định thông tin người cập nhật');
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

        if (empty($res) || !is_numeric($res)) {
            //            Log::error(__METHOD__ . ':: ' . $res . '-->' . json_encode($dataUpdate));
        } else { //nếu thằng ảnh update thì update bên phía đt luôn//có time thì viết thành event ngon hơn
        }
        return $res;
    }

    public function saveDelete($params)
    {

        //        $params = ['cols'=>['col'=>'new_value'],'user_edit'=>'id'];

        if (empty($params['user_edit'])) {
            // Log::warning(__METHOD__ . ' Không xác định thông tin người cập nhật');
            Session::push('errors', 'Không xác định thông tin người cập nhật');
            return null;
        }

        if (empty($params['cols']['id'])) {
            Session::push('errors', 'Không xác định bản ghi cần cập nhật');
            return null;
        }


        if ($params['event'] == 'restore') {
            $dataUpdate = [
                'd_id' => 0,
                'd_time' => null
            ];
        } elseif ($params['event'] == 'delete') {
            $dataUpdate = [
                'd_id' => $params['asset_edit'],
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

        // if (empty($res) || !is_numeric($res)) {
        //     Log::error(__METHOD__ . ':: ' . $res . '-->' . json_encode($dataUpdate));
        // }

        return $res;
    }
    public function loadListIdAndName($where = null)
    {
        $list = DB::table($this->table)->select('id', 'users_id', 'name', 'trang_thai');
        if ($where != null)
            $list->where([$where]);
        return $list->get();
    }
    // public function getLopByHocVien($mahv)
    // {
    //     $list = DB::table('hoc_vien_lop_ts as tb1')
    //         ->select('tb1.id_lophoc', 'tb2.ten_lop')
    //         ->leftJoin('lop_ts as tb2', 'tb2.id', '=', 'tb1.id_lophoc')
    //         ->where('tb1.id_hocvien', $mahv)
    //         ->distinct()->get();
    //     return $list;
    // }
    // public function countHocVienByTuVan($id_tuvan)
    // {
    //     $count = DB::table($this->table)->where('id_tuvan', $id_tuvan)->count();
    //     return $count;
    // }
    // public function exchangeData($tu_van_chuyen, $tu_van_nhan)
    // {
    //     $rs = DB::table($this->table)
    //         ->where('id_tuvan', $tu_van_chuyen)
    //         ->update(['id_tuvan' => $tu_van_nhan, 'log_chuyen_tuvan' => time() . '_' . Auth::id() . '_' . $tu_van_chuyen . '=>' . $tu_van_nhan]);
    //     return $rs;
    // }
    public function getByListId($params = [])
    {
        $list = DB::table($this->table)
            ->select('id', DB::raw("(concat(hodem, ' ',ten)) as name"))
            ->whereIn('id', $params)
            ->get();
        return $list;
    }
    // public function getByTuVanNumber($id_tuvan, $id_hocvien)
    // {
    //     $list = DB::table($this->table)
    //         ->select('id', DB::raw("(concat(hodem, ' ',ten)) as name"), 'phone_number')
    //         ->where('id_tuvan', $id_tuvan)
    //         ->where('id', 'like', $id_hocvien . '%')
    //         ->paginate(config('app.backend_row_per_page'));
    //     //            ->get();
    //     return $list;
    // }
    // public function checkUseEvent($id_event)
    // {
    //     $count = DB::table($this->table)
    //         ->where('su_kien', 'like', '%|' . $id_event . '|%')
    //         ->count();
    //     return $count;
    // }

    public function checkUnique($phone, $email)
    {
        $count = DB::table($this->table)
            ->where('phone_number', $phone)
            ->orWhere('email', $email)
            ->count();
        return $count;
    }
}
