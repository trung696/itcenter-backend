<?php

namespace App\Http\Controllers;

use App\DonVi;
use App\Http\Requests\TeacherRequest;
use App\TaiSan;
use Illuminate\Database\Eloquent\Model;
use App\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\DanhMucTaiSan;
use App\TaiSanCon;


class TeacherController extends Controller
{
    //
    private $v;

    public function __construct()
    {
        //        $this->middleware('auth');
        $this->v = [];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function giaoVien(Request $request)
    {
        //
        $this->v['_title'] = 'Giáo viên';
        $this->v['routeIndexText'] = 'Giáo viên';
        $objTeacher = new Teacher();
        $this->v['extParams'] = $request->all();
        $this->v['list'] = $objTeacher->loadListWithPager($this->v['extParams']);
        // $donVi = DB::table('don_vi as tb1')->get();
        // $arrDonVi = [];
        // foreach ($donVi as $key => $value) {
        //     $arrDonVi[$value->id] = $value->ten_don_vi;
        // }
        // $this->v['arrDonVi'] = $arrDonVi;
        return view('giangvien.admin.danh-sach-giang-vien', $this->v);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param $ten_danh_muc
     * @return \Illuminate\Http\Response
     */
    public function themGiaoVien(TeacherRequest $request)
    {
        //
        //
        $this->v['routeIndexText'] = 'Thêm Giáo Viên';
        $method_route = 'route_BackEnd_Teacher_Add';
        //        $this->v['request'] = Session::pull('post_form_data')[0];
        //        SpxGetSsMessage($this->v);
        $this->v['_action'] = 'Add';
        $this->v['_title'] = 'Thêm giáo viên';
        //        $this->v['ten_danh_muc'] = $ten_danh_muc;
        //        $this->v['trang_thai'] = [0=>"Khóa",1=>"Mở"];
        //        $this->v['trang_thai'] = config('app.status_user');
        if ($request->isMethod('post')) {

            if (Session::has($method_route)) {
                return redirect()->route($method_route); // không cho F5, chỉ có thể post 1 lần
            } else
                Session::push($method_route, 1); // bỏ vào session để chống F5

            $params = [
                'user_add' => Auth::user()->id
            ];
            $params['cols'] = array_map(function ($item) {
                if ($item == '')
                    $item = null;
                if (is_string($item))
                    $item = trim($item);
                return $item;
            }, $request->post());
            unset($params['cols']['_token']);
            //check Data trùng
            //            $countTaiSan = DB::table('danh_muc_tai_san')
            //                ->where('ten_danh_muc',$params['cols']['ten_danh_muc'])
            //                ->where('trang_thai',$params['cols']['trang_thai'])->count();
            //            if($countTaiSan>0)
            //            {
            //                Session::push('errors', 'Đã tồn tại danh mục tài sản này');
            //                Session::push('post_form_data', $this->v['request']);
            //                return redirect()->route($method_route);
            //            }
            $objTeacher = new  Teacher();
            $res = $objTeacher->saveNew($params); // hàm trả về ID mới nếu insert thành công
            if ($res == null) // chuyển trang vì trong session đã có sẵn câu thông báo lỗi rồi
            {
                Session::push('post_form_data', $this->v['request']);
                return redirect()->route($method_route);
            } elseif ($res > 0) {
                $this->v['request'] = [];
                $request->session()->forget('post_form_data'); // xóa data post
                Session::flash('success', 'Thêm mới thành công giáo viên !');
                return redirect()->route('route_BackEnd_Teacher_index');
            } else {
                Session::push('errors', 'Lỗi thêm mới: ' . $res);
                Session::push('post_form_data', $this->v['request']);
                return redirect()->route($method_route);
            }
        } else {
            // không phải post
            $request->session()->forget($method_route); // hủy session nếu vào bằng sự kiện get
        }
        // $objDonVi = new  DonVi();
        // $this->v['don_vi'] = $objDonVi->loadListIdAndName(['trang_thai', 1]);
        // return view('bienban.them-bien-ban', $this->v);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function chiTietBienBan($id)
    {
        $this->v['routeIndexText'] = 'Chi tiết biên bản';
        $this->v['_action'] = 'Edit';
        $this->v['_title'] = 'Chi tiết biên bản';
        //        $this->v['request'] = Session::pull('post_form_data')[0];
        $objTeacher = new Teacher();
        $objItem = $objTeacher->loadOne($id);
        $this->v['objItem'] = $objItem;
        if (empty($objItem)) {
            Session::push('errors', 'Không tồn tại danh mục này ' . $id);
            return redirect()->back();
        }
        $objDonVi = new DonVi();
        $this->v['don_vi'] = $objDonVi->loadListIdAndName(['trang_thai', 1]);

        return view('bienban.chi-tiet-bien-ban', $this->v);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateThongTinGiaoVien($id, TeacherRequest $request)
    {
        //
        $method_route = 'route_BackEnd_BienBan_Detail';
        $primary_table = 'bien_ban_ban_giao_ts';
        $objTeacher = new Teacher();
        //Xử lý request
        $params = [
            'user_edit' => Auth::user()->id
        ];
        $params['cols'] = array_map(function ($item) {
            if ($item == '')
                $item = null;
            if (is_string($item))
                $item = trim($item);
            return $item;
        }, $request->post());
        unset($params['cols']['_token']);
        $objItem = $objTeacher->loadOne($id);
        if (empty($objItem)) {
            Session::push('errors', 'Không tồn tại danh mục này ' . $id);
            return redirect()->route('route_BackEnd_BienBan_index');
        }
        $params['cols']['id'] = $id;
        $res = $objTeacher->saveUpdate($params);

        if ($res == null) // chuyển trang vì trong session đã có sẵn câu thông báo lỗi rồi
        {
            Session::flash('success', 'Cập nhật bản ghi: ' . $objItem->id . ' thành công!');
            return redirect()->route('route_BackEnd_BienBan_index');
        } elseif ($res == 1) {
            //            SpxLogUserActivity(Auth::user()->id, 'edit', $primary_table, $id, 'edit');
            $request->session()->forget('post_form_data'); // xóa data post
            Session::flash('success', 'Cập nhật bản ghi: ' . $objItem->id . ' thành công!');

            return redirect()->route('route_BackEnd_BienBan_index');
        } else {

            Session::push('errors', 'Lỗi cập nhật cho bản ghi: ' . $res);
            Session::push('post_form_data', $this->v['request']);
            return redirect()->route($method_route, ['id' => $id]);
        }
    }
    public function deleteGiaoVien(Request $request, $id)
    {
        $deleteData = DB::table('bien_ban_ban_giao_ts')->where('id', '=', $id)->delete();
        if ($deleteData) {
            Session::flash('success', 'Xóa dữ liệu thành công');
            return redirect(route('route_BackEnd_BienBan_index'));
        }
        //        $method_route = 'route_BackEnd_BienBan_Delete';
        //        $primary_table = 'bien_ban_ban_giao_ts';
        //        $objTeacher = new Teacher();
        //        $objItem = $objTeacher->loadOne($id);
        //        if (empty($objItem)) {
        //            Session::push('errors', 'Không tồn tại danh mục này ' . $id);
        //            return redirect()->route('route_BackEnd_BienBan_index');
        //        }
        //        //Kiểm tra xem sử dụng chưa
        //        //
        //        $params = [
        //            'user_edit' => Auth::user()->id];
        //        $res = $objTeacher->saveDelete($params);
        //
        //        if ($res == null) // chuyển trang vì trong session đã có sẵn câu thông báo lỗi rồi
        //        {
        //            Session::flash('success', 'Cập nhật bản ghi: ' . $objItem->id . ' thành công!');
        //            return redirect()->route('route_BackEnd_BienBan_index');
        //        } elseif ($res == 1) {
        //            $request->session()->forget('post_form_data'); // xóa data post
        //            Session::flash('success', 'Cập nhật bản ghi: ' . $objItem->id . ' thành công!');
        //
        //            return redirect()->route('route_BackEnd_BienBan_index');
        //        } else {
        //
        //            Session::push('errors', 'Lỗi cập nhật cho bản ghi: ' . $res);
        //            Session::push('post_form_data', $this->v['request']);
        //            return redirect()->route($method_route, ['id' => $id]);
        //        }
    }
    public function bienBanKiemKe(Request $request)
    {
        //
        $this->v['_title'] = 'Biên Bản Kiểm Kê';
        $this->v['routeIndexText'] = 'Biên Bản Kiểm Kê';
        $objTeacher = new Teacher();
        $this->v['extParams'] = $request->all();
        $this->v['list'] = $objTeacher->loadListWithPager($this->v['extParams']);
        $donVi = DB::table('don_vi as tb1')->get();
        $arrDonVi = [];
        foreach ($donVi as $key => $value) {
            $arrDonVi[$value->id] = $value->ten_don_vi;
        }
        $this->v['arrDonVi'] = $arrDonVi;
        $objDonVi = new  DonVi();
        $this->v['don_vi'] = $objDonVi->loadListIdAndName(['trang_thai', 1]);
        return view('bienban.bien-ban-kiem-ke', $this->v);
    }
    public function bienBanThanhLi(Request $request)
    {
        //
        $this->v['routeIndexText'] = 'Biên Bản Thanh Lí';
        $this->v['_action'] = 'Edit';
        $this->v['_title'] = 'Biên Bản Thanh Lí';
        //        $this->v['request'] = Session::pull('post_form_data')[0];

        $this->v['trang_thai'] = config('app.status_user');
        $this->v['trang_thais'] = config('app.status_asset_baby');
        $this->v['nguon_kinh_phi'] = config('app.expense');
        $objDanhMucTaiSan = new DanhMucTaiSan();
        $this->v['danh_muc_tai_san'] = $objDanhMucTaiSan->loadListIdAndName(['trang_thai', 1]);

        $objTaiSanCon = new TaiSanCon();

        $this->v['extParams'] = $request->all();
        if (isset($this->v['extParams']['search_nam_su_dung'])) {
            $ngaythem = explode(' - ', $this->v['extParams']['search_nam_su_dung']);
            if (count($ngaythem) != 2) {
                Session::flash('error', 'Năm sử dụng nhập không hợp lệ');
                return redirect()->route($this->routeIndex);
            }
            $datetime = array_map('convertDateToSql', $ngaythem);
            $datetime[0] = $datetime[0] . ' 00:00:00';
            $datetime[1] = $datetime[1] . ' 23:59:59';
            $this->v['extParams']['search_nam_su_dung_array'] = $datetime;
        }
        $this->v['lists'] = $objTaiSanCon->loadListWithPagerTL($this->v['extParams']);
        //        $objItemDV = $objTaiSanCon->loadOne($id);
        //        $this->v['objItemDV'] = $objItemDV;
        $objDonVi = new DonVi();
        $this->v['don_vi'] = $objDonVi->loadListIdAndName(['trang_thai', 1]);
        $donVis = $this->v['don_vi'];
        $arrDonVi = [];
        foreach ($donVis as $index => $item) {
            $arrDonVi[$item->id] = $item->ten_don_vi;
        }
        $this->v['arrDonVi'] = $arrDonVi;
        $objTaiSan = new TaiSan();
        $this->v['tai_san'] = $objTaiSan->loadListIdAndName(['trang_thai', 1]);
        $taiSans = $this->v['tai_san'];
        $arrTaiSan = [];
        foreach ($taiSans as $index => $item) {
            $arrTaiSan[$item->id] = $item->ten_tai_san;
        }
        $this->v['arrTaiSan'] = $arrTaiSan;
        $objTaiSanCon = new TaiSanCon();
        //        $objItemTSC = $objTaiSanCon->loadOne($id);

        //        $this->v['objItemTSC'] = $objItemTSC;
        return view('bienban.bien-ban-thanh-li', $this->v);
    }
}
