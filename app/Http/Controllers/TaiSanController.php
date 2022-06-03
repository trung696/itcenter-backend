<?php

namespace App\Http\Controllers;

use App\DanhMucTaiSan;
use App\DonVi;
use App\TaiSan;
use App\TaiSanCon;
use App\User;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\TaiSanRequest;
use Illuminate\Support\Facades\Session;
use Spipu\Html2Pdf\Html2Pdf;
use Dompdf\Dompdf;
use Dompdf\Options;

require_once __DIR__ . '/../../SLib/functions.php';

class TaiSanController extends Controller
{
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
    public function danhMucTaiSan(Request $request)
    {
        //
        $this->v['_title'] = 'Danh mục tài sản';
        $this->v['routeIndexText'] = 'Danh mục tài sản';
        $objDanhMucTaiSan = new DanhMucTaiSan();
        $this->v['extParams'] = $request->all();
        $this->v['list'] = $objDanhMucTaiSan->loadListWithPager($this->v['extParams']);
        return view('taisan.danh-muc-tai-san', $this->v);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param $ten_danh_muc
     * @return \Illuminate\Http\Response
     */
    public function themDanhMucTaiSan(TaiSanRequest $request)
    {
        //
        $this->v['routeIndexText'] = 'Danh mục tài sản';
        $method_route = 'route_BackEnd_DanhMucTaiSan_Add';
//        $this->v['request'] = Session::pull('post_form_data')[0];
//        SpxGetSsMessage($this->v);
        $this->v['_action'] = 'Add';
        $this->v['_title'] = 'Thêm danh mục';
//        $this->v['ten_danh_muc'] = $ten_danh_muc;
//        $this->v['trang_thai'] = [0=>"Khóa",1=>"Mở"];
        $this->v['trang_thai'] = config('app.status_user');
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
            $objDanhMucTaiSan = new  DanhMucTaiSan();
            $res = $objDanhMucTaiSan->saveNew($params); // hàm trả về ID mới nếu insert thành công
            if ($res == null) // chuyển trang vì trong session đã có sẵn câu thông báo lỗi rồi
            {
                Session::push('post_form_data', $this->v['request']);
                return redirect()->route($method_route);
            } elseif ($res > 0) {
                $this->v['request'] = [];
                $request->session()->forget('post_form_data'); // xóa data post
                Session::flash('success', 'Thêm mới thành công danh mục tài sản !');
                return redirect()->route('route_BackEnd_DanhMucTaiSan_index');

            } else {
                Session::push('errors', 'Lỗi thêm mới: ' . $res);
                Session::push('post_form_data', $this->v['request']);
                return redirect()->route($method_route);
            }

        } else {
            // không phải post
            $request->session()->forget($method_route); // hủy session nếu vào bằng sự kiện get
        }

//        $this->v['quyens'] = config('app.roles');
        return view('taisan.them-danh-muc-tai-san', $this->v);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function chiTietDanhMucTaiSan($id)
    {
        //
        $this->v['routeIndexText'] = 'Danh sách người dùng';
        $this->v['_action'] = 'Edit';
        $this->v['_title'] = 'Chi tiết anh mục tài sản';
//        $this->v['request'] = Session::pull('post_form_data')[0];
        $objDanhMucTaiSan = new DanhMucTaiSan();
        $objItem = $objDanhMucTaiSan->loadOne($id);

        if (empty($objItem)) {
            Session::push('errors', 'Không tồn tại danh mục này ' . $id);
            return redirect()->route($this->routeIndex);
        }
//        $this->v['trang_thai'] = [0=>"Khóa",1=>"Mở"];
        $this->v['trang_thai'] = config('app.status_user');
        $this->v['objItem'] = $objItem;
        return view('taisan.chi-tiet-danh-muc-tai-san', $this->v);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function updateChiTietDanhMucTaiSan($id, TaiSanRequest $request)
    {
        //
        $method_route = 'route_BackEnd_DanhMucTaiSan_Detail';
        $primary_table = 'danh_muc_tai_san';
        $objDanhMucTaiSan = new DanhMucTaiSan();
        //Xử lý request
        $params = [
            'user_edit' => Auth::user()->id];
        $params['cols'] = array_map(function ($item) {
            if ($item == '')
                $item = null;
            if (is_string($item))
                $item = trim($item);
            return $item;
        }, $request->post());
        unset($params['cols']['_token']);
        $objItem = $objDanhMucTaiSan->loadOne($id);
        if (empty($objItem)) {
            Session::push('errors', 'Không tồn tại danh mục này ' . $id);
            return redirect()->route('route_BackEnd_DanhMucTaiSan_index');
        }
        $params['cols']['id'] = $id;
        $res = $objDanhMucTaiSan->saveUpdate($params);

        if ($res == null) // chuyển trang vì trong session đã có sẵn câu thông báo lỗi rồi
        {
            Session::flash('success', 'Cập nhật bản ghi: ' . $objItem->id . ' thành công!');
            return redirect()->route('route_BackEnd_DanhMucTaiSan_index');
        } elseif ($res == 1) {
//            SpxLogUserActivity(Auth::user()->id, 'edit', $primary_table, $id, 'edit');
            $request->session()->forget('post_form_data'); // xóa data post
            Session::flash('success', 'Cập nhật bản ghi: ' . $objItem->id . ' thành công!');

            return redirect()->route('route_BackEnd_DanhMucTaiSan_index');
        } else {

            Session::push('errors', 'Lỗi cập nhật cho bản ghi: ' . $res);
            Session::push('post_form_data', $this->v['request']);
            return redirect()->route($method_route, ['id' => $id]);
        }
    }

    public function taiSan(TaiSanRequest $request)
    {
        $this->v['_title'] = 'tài sản';
        $this->v['routeIndexText'] = 'tài sản';
        $objTaiSan = new TaiSan();
        $this->v['extParams'] = $request->all();
        $this->v['list'] = $objTaiSan->loadListWithPager($this->v['extParams']);
        $objDanhMucTaiSan = new  DanhMucTaiSan();
        $this->v['danh_muc_tai_san'] = $objDanhMucTaiSan->loadListIdAndName(['trang_thai', 1]);
        $danhMucs = $this->v['danh_muc_tai_san'];
        $arrDanhMuc = [];
        foreach ($danhMucs as $index => $item) {
            $arrDanhMuc[$item->id] = $item->ten_danh_muc;
        }
        $this->v['arrDanhMuc'] = $arrDanhMuc;
        return view('taisan.tai-san', $this->v);
    }

    public function themTaiSan(TaiSanRequest $request)
    {
        //
        $this->v['routeIndexText'] = 'Tài sản';
        $method_route = 'route_BackEnd_TaiSan_Add';
//        $this->v['request'] = Session::pull('post_form_data')[0];
//        SpxGetSsMessage($this->v);
        $this->v['_action'] = 'Add';
        $this->v['_title'] = 'Thêm tài sản';
//        $this->v['ten_danh_muc'] = $ten_danh_muc;
//        $this->v['trang_thai'] = [0=>"Khóa",1=>"Mở"];
        $this->v['trang_thai'] = config('app.status_user');
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
            $objTaiSan = new  TaiSan();
            $res = $objTaiSan->saveNew($params); // hàm trả về ID mới nếu insert thành công
            if ($res == null) // chuyển trang vì trong session đã có sẵn câu thông báo lỗi rồi
            {
                Session::push('post_form_data', $this->v['request']);
                return redirect()->route($method_route);
            } elseif ($res > 0) {
                $this->v['request'] = [];
                $request->session()->forget('post_form_data'); // xóa data post
                Session::flash('success', 'Thêm mới thành công danh mục tài sản !');
                return redirect()->route('route_BackEnd_TaiSan_index');

            } else {
                Session::push('errors', 'Lỗi thêm mới: ' . $res);
                Session::push('post_form_data', $this->v['request']);
                return redirect()->route($method_route);
            }

        } else {
            // không phải post
            $request->session()->forget($method_route); // hủy session nếu vào bằng sự kiện get
        }
        $objDanhMucTaiSan = new  DanhMucTaiSan();
        $this->v['danh_muc_tai_san'] = $objDanhMucTaiSan->loadListIdAndName(['trang_thai', 1]);
        return view('taisan.them-tai-san', $this->v);
    }
    public function themSoLuongTaiSan(Request $request)
    {
        $validator = \Validator::make($request->all(),$this->ruleTaiSanCon(),$this->messageTaiSanCon());

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }
//        if ($request->so_luong_tai_san < 0) {
//            Session::flash('error', 'Số lượng tài sản phải lớn hơn 0');
//            return redirect()->back();
//        }
        $arrTaiSanCon = [];
        $latestTaiSan = DB::table('tai_san_con')->orderBy('id','DESC')->first();
        $latestID = $latestTaiSan ? $latestTaiSan->id : 0;
//        $maTaiSanCon = 'TSC'.str_pad($latestID + 1, 5, "0", STR_PAD_LEFT);

        for ($i = 0 ;$i < $request->so_luong_tai_san;$i ++) {
            $maTaiSanCon = 'TSC'.str_pad($latestID + 1, 5, "0", STR_PAD_LEFT);
            $arrTaiSanCon[$i]['id'] = null;
            $arrTaiSanCon[$i]['ma_tai_san_con'] = $maTaiSanCon;
            $arrTaiSanCon[$i]['id_tai_san'] = $request->id_tai_san;
            $arrTaiSanCon[$i]['id_don_vi'] = $request->id_don_vi;
            $arrTaiSanCon[$i]['nguon_kinh_phi'] = $request->nguon_kinh_phi;
            $arrTaiSanCon[$i]['nam_su_dung'] = $request->nam_su_dung;
            $arrTaiSanCon[$i]['thong_so_ky_thuat'] = $request->thong_so_ky_thuat;
            $arrTaiSanCon[$i]['xuat_xu'] = $request->xuat_xu;
            $arrTaiSanCon[$i]['nguyen_gia'] = $request->nguyen_gia;
            $arrTaiSanCon[$i]['thoi_gian_khau_hao'] = $request->thoi_gian_khau_hao;
            $arrTaiSanCon[$i]['gia_tri_con_lai'] = $request->gia_tri_con_lai;
            $arrTaiSanCon[$i]['thoi_han_bao_hanh'] = $request->thoi_han_bao_hanh;
            $arrTaiSanCon[$i]['trang_thai'] = $request->trang_thai;
            $latestID++;

        }
        $result = DB::table('tai_san_con')->insert($arrTaiSanCon) ;
        if(!$result){
            return response()->json(['errors'=>Session::exists('errors')?Session::pull('errors'):'Lỗi thêm mới'],500);
        }else{
            Session::flash('success', 'Tạo tài sản con tự động thành công');
            return redirect()->back();
        }
        //
    }
    public function chiTietTaiSan($id, Request $request)
    {
        //
        $this->v['routeIndexText'] = 'Chi tiết tài sản';
        $this->v['_action'] = 'Edit';
        $this->v['_title'] = 'Chi tiết tài sản';
//        $this->v['request'] = Session::pull('post_form_data')[0];
        $objTaiSan = new TaiSan();
        $objItem = $objTaiSan->loadOne($id);
        $this->v['objItem'] = $objItem;
        if (empty($objItem)) {
            Session::push('errors', 'Không tồn tại danh mục này ' . $id);
            return redirect()->back();
        }
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

        $this->v['lists'] = $objTaiSanCon->loadListWithPager($this->v['extParams'], $id);
        $objItemDV = $objTaiSanCon->loadOne($id);
        $this->v['objItemDV'] = $objItemDV;
        $objDonVi = new DonVi();
        $this->v['don_vi'] = $objDonVi->loadListIdAndName(['trang_thai', 1]);
        $donVis = $this->v['don_vi'];
        $arrDonVi = [];
        foreach ($donVis as $index => $item) {
            $arrDonVi[$item->id] = $item->ten_don_vi;
        }
        $this->v['arrDonVi'] = $arrDonVi;
        $objTaiSanCon = new TaiSanCon();
        $objItemTSC = $objTaiSanCon->loadOne($id);

        $this->v['objItemTSC'] = $objItemTSC;
        return view('taisan.chi-tiet-tai-san', $this->v);
    }

    public function updateChiTietTaiSan($id, TaiSanRequest $request)
    {
        //
        $method_route = 'route_BackEnd_TaiSan_Detail';
        $primary_table = 'tai_san';
        $objTaiSan = new TaiSan();
        //Xử lý request
        $params = [
            'user_edit' => Auth::user()->id];
        $params['cols'] = array_map(function ($item) {
            if ($item == '')
                $item = null;
            if (is_string($item))
                $item = trim($item);
            return $item;
        }, $request->post());
        unset($params['cols']['_token']);
        $objItem = $objTaiSan->loadOne($id);
        if (empty($objItem)) {
            Session::push('errors', 'Không tồn tại danh mục này ' . $id);
            return redirect()->route('route_BackEnd_DanhMucTaiSan_index');
        }
        $params['cols']['id'] = $id;
        $res = $objTaiSan->saveUpdate($params);

        if ($res == null) // chuyển trang vì trong session đã có sẵn câu thông báo lỗi rồi
        {
            Session::flash('success', 'Cập nhật bản ghi: ' . $objItem->id . ' thành công!');
            return redirect()->route('route_BackEnd_TaiSan_index');
        } elseif ($res == 1) {
//            SpxLogUserActivity(Auth::user()->id, 'edit', $primary_table, $id, 'edit');
            $request->session()->forget('post_form_data'); // xóa data post
            Session::flash('success', 'Cập nhật bản ghi: ' . $objItem->id . ' thành công!');

            return redirect()->route('route_BackEnd_TaiSan_index');
        } else {

            Session::push('errors', 'Lỗi cập nhật cho bản ghi: ' . $res);
            Session::push('post_form_data', $this->v['request']);
            return redirect()->route($method_route, ['id' => $id]);
        }
    }
    private function ruleTaiSanCon(){
        return [
//            'ma_tai_san_con' => "required",
            'nam_su_dung' => "required",
            'thong_so_ky_thuat' => "required",
            'xuat_xu' => "required",
            'id_don_vi' => "required",
            'nguon_kinh_phi' => "required",
            'nguyen_gia' => "required",
            'thoi_gian_khau_hao' => "required",
            'gia_tri_con_lai' => "required",
            'thoi_han_bao_hanh' => "required",
            'so_luong_tai_san' => "required",
            'trang_thai' => "required",
        ];
    }
    private function messageTaiSanCon(){
        return [
//            "ma_tai_san_con.required" =>  "Vui lòng nhập mã hóa đơn",
            "nam_su_dung.required" =>  "Không được để trống năm sử dụng",
            "thong_so_ky_thuat.required" =>  "Không được để trống thông số kĩ thuật",
            "xuat_xu.required" =>  "Không được để trống xuất xứ",
            "id_don_vi.required" =>  "Không được để trống đơn vị cung cấp",
            "nguon_kinh_phi.required" =>  "Không được để trống nguồn kinh phí",
            "nguyen_gia.required" =>  "Không được để trống nguyên giá",
            "thoi_gian_khau_hao.required" =>  "Không được để trống thời gian khấu hao",
            "gia_tri_con_lai.required" =>  "Không được để trống giá trị còn lại",
            "thoi_han_bao_hanh.required" =>  "Không được để trống thời gian bảo hành",
            "so_luong_tai_san.required" =>  "Không được để trống số lượng tài sản con",
            "trang_thai.required" =>  "Không được để trống trạng thái",
        ];
    }
}
