<?php

namespace App\Http\Controllers;

use App\ChienDich;
use App\DangKy;
use App\HocVien;
use App\KhoaHoc;
use App\DanhMucKhoaHoc;
use App\KhuyenMai;
use App\LopHoc;
use App\MaChienDich;
use App\User;
use App\CentralFacility;
use App\Course;
use App\ClassModel;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\ClassRequest;
use App\Http\Requests\TaiSanRequest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Spipu\Html2Pdf\Html2Pdf;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Mail\OrderShipped;
use Stripe\Charge;

class ClassController extends  Controller
{
    private $v;
    public function __construct()
    {
        $this->v = [];
    }

    public function classList(Request $request)
    {
        $this->v['routeIndexText'] = 'Danh sách lớp học';
        $this->v['_action'] = 'List';
        $this->v['_title'] = 'danh sách lớp học';

        $objClassModel = new ClassModel();
        // dd($objClassModel);
        $this->v['extParams'] = $request->all();
        $this->v['lists'] = $objClassModel->loadListWithPager($this->v['extParams']);
        // dd($this->v['lists']);
        $this->v['objItemClass'] = $objClassModel;
        $objUser = new User();
        $this->v['user'] = $objUser->loadListIdAndName(['status', 1]);
        $user = $this->v['user'];
        // dd($user);
        $arrUser = [];
        foreach ($user as $index => $item) {
            // dd($item);
            $arrUser[$item->id] = $item->name;
        }
        $this->v['arrUser'] = $arrUser;
        // dd( $this->v['arrUser']);
        $objCourse = new Course();

        // $objItem = $objCourse->loadOne($id);
        $this->v['course_id'] = $objCourse->loadListIdAndName(['status', 1]);
        // $this->v['objItem'] = $objItem;
        $course = $this->v['course_id'];
        $arrCourse = [];
        foreach ($course as $index => $item) {
            // dd($item);
            $arrCourse[$item->id] = $item->name;
        }
        $this->v['arrCourse'] = $arrCourse;
        // dd($this->v['arrCourse']);
        // if (empty($objItem)) {
        //     Session::push('errors', 'Không tồn tại danh mục này ' . $id);
        //     return redirect()->back();
        // }
        $this->v['extParams'] = $request->all();
        $this->v['status'] = config('app.status_user');

        if (isset($this->v['extParams']['search_ngay_khai_giang'])) {
            $ngaythem = explode(' - ', $this->v['extParams']['search_ngay_khai_giang']);
            if (count($ngaythem) != 2) {
                Session::flash('error', 'Ngày khai giảng không hợp lệ');
                return redirect()->route($this->routeIndex);
            }
            $datetime = array_map('convertDateToSql', $ngaythem);
            $datetime[0] = $datetime[0] . ' 00:00:00';
            $datetime[1] = $datetime[1] . ' 23:59:59';
            $this->v['extParams']['search_ngay_khai_giang_array'] = $datetime;
        }

        $objCentralFacility = new CentralFacility();
        $this->v['centralFacility'] = $objCentralFacility->loadListIdAndName();
        $centralFacility = $this->v['centralFacility'];
        // dd($user);
        $arrFacility = [];
        foreach ($centralFacility as $index => $item) {
            // dd($item);
            $arrFacility[$item->id] = $item->name;
        }
        $this->v['arrFacility'] = $arrFacility;
        // dd( $this->v['arrUser']);

        return view('class.list-class', $this->v);
    }

    public function addClass(ClassRequest $request)
    {
        $this->v['_title'] = 'Khoá học';
        $method_route = 'route_BackEnd_Class_Add';
        $this->v['_action'] = 'Add';
        $this->v['_title'] = 'Thêm lớp học';
        // dd($request->input('name'));
        if ($request->isMethod('post')) {

            // dd('có method post');
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

            // dd($params['cols']);
            unset($params['cols']['_token']);
            $objClass = new ClassModel();
            $res = $objClass->saveNew($params);
            // dd($res);
            if ($res == null) // chuyển trang vì trong session đã có sẵn câu thông báo lỗi rồi
            {
                dd('thêm mới thất bại');
                Session::push('post_form_data', $this->v['request']);
                return redirect()->route($method_route);
            } elseif ($res > 0) {
                $this->v['request'] = [];
                $request->session()->forget('post_form_data'); // xóa data post
                Session::flash('success', 'Thêm mới thành công lớp học !');
                return redirect()->route('route_BackEnd_Class_List');
            } else {
                Session::push('errors', 'Lỗi thêm mới: ' . $res);
                Session::push('post_form_data', $this->v['request']);
                return redirect()->route($method_route);
            }
        } else {
            // dd('không có method post');
            // không phải post
            $request->session()->forget($method_route); // hủy session nếu vào bằng sự kiện get
        }

        $objUser = new  User();
        $this->v['user'] = $objUser->loadListIdAndName(['status', 1]);

        $objCentralFacility = new  CentralFacility();
        $this->v['centralFacility'] = $objCentralFacility->loadListIdAndName();

        $objCourse = new  Course();
        $this->v['course'] = $objCourse->loadListIdAndName(['status', 1]);
        return view('class.add-class', $this->v);
    }

    public function classDetail($id, Request $request)
    {

        $this->v['routeIndexText'] = 'Chi tiết Lớp học';
        $this->v['_action'] = 'Edit';
        $this->v['_title'] = 'Chi tiết Lớp học';

        $objClassModel = new ClassModel();
        // $this->v['lists'] = $objClassModel->loadListWithPager($this->v['extParams'], $id);
        $objItemClass = $objClassModel->loadOne($id);

        $this->v['class'] = $objClassModel;
        $this->v['objItemClass'] = $objItemClass;
        $objUser = new User();
        $this->v['user'] = $objUser->loadListIdAndName(['status', 1]);
        // dd($this->v['arrCourse']);
        if (empty($objItemClass)) {
            Session::push('errors', 'Không tồn tại class này ' . $id);
            return redirect()->back();
        }
        $this->v['extParams'] = $request->all();
        $user = $this->v['user'];
        // dd($user);
        $arrUser = [];
        foreach ($user as $index => $item) {
            // dd($item);
            $arrUser[$item->id] = $item->name;
        }

        $this->v['arrUser'] = $arrUser;
        // dd( $this->v['arrUser']);

        $objCourse = new Course();
        $objItem = $objCourse->loadOne($id);

        $this->v['course_id'] = $objCourse->loadListIdAndName(['status', 1]);

        $course = $this->v['course_id'];
        // dd($user);
        $arrCourse = [];
        foreach ($course as $index => $item) {
            // dd($item);
            $arrCourse[$item->id] = $item->name;
        }
        $this->v['arrCourse'] = $arrCourse;

        $this->v['extParams'] = $request->all();
        $this->v['status'] = config('app.status_user');

        if (isset($this->v['extParams']['search_ngay_khai_giang'])) {
            $ngaythem = explode(' - ', $this->v['extParams']['search_ngay_khai_giang']);
            if (count($ngaythem) != 2) {
                Session::flash('error', 'Ngày khai giảng không hợp lệ');
                return redirect()->route($this->routeIndex);
            }
            $datetime = array_map('convertDateToSql', $ngaythem);
            $datetime[0] = $datetime[0] . ' 00:00:00';
            $datetime[1] = $datetime[1] . ' 23:59:59';
            $this->v['extParams']['search_ngay_khai_giang_array'] = $datetime;
        }

        $objCentralFacility = new CentralFacility();
        $this->v['centralFacility'] = $objCentralFacility->loadListIdAndName();
        $centralFacility = $this->v['centralFacility'];
        // dd($user);
        $arrFacility = [];
        foreach ($centralFacility as $index => $item) {
            // dd($item);
            $arrFacility[$item->id] = $item->name;
        }
        $this->v['arrFacility'] = $arrFacility;
        // dd( $this->v['arrUser']);

        return view('class.update-class', $this->v);
    }

    public function updateClass($id, ClassRequest $request)
    {
        // dd('abc');
        $method_route = 'route_BackEnd_Class_Detail';
        $modelClass = new ClassModel();
        // dd($method_route);
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

        $objItem = $modelClass->loadOne($id);
        if (empty($objItem)) {
            Session::push('errors', 'Không tồn tại người dùng này ' . $id);
            return redirect()->route('route_BackEnd_NguoiDung_index');
        }
        $params['cols']['id'] = $id;
        // dd($params['cols']);
        $res = $modelClass->saveUpdate($params);
        if ($res == null) // chuyển trang vì trong session đã có sẵn câu thông báo lỗi rồi
        {
            Session::push('post_form_data', $this->v['request']);
            return redirect()->route($method_route, ['id' => $id]);
        } elseif ($res == 1) {
            //            SpxLogUserActivity(Auth::user()->id, 'edit', $primary_table, $id, 'edit');
            $request->session()->forget('post_form_data'); // xóa data post
            Session::flash('success', 'Cập nhật thành công thông tin lớp học!');
            return redirect()->route('route_BackEnd_Class_List');
        } else {

            Session::push('errors', 'Lỗi cập nhật cho bản ghi: ' . $res);
            Session::push('post_form_data', $this->v['request']);
            return redirect()->route($method_route, ['id' => $id]);
        }
    }

    public function destroy($id)
    {
        //Xoa hoc sinh
        //Thực hiện câu lệnh xóa với giá trị id = $id trả về
        $deleteData = DB::table('class')->where('id', '=', $id)->delete();

        //Kiểm tra lệnh delete để trả về một thông báo
        if ($deleteData) {
            Session::flash('success', 'Xóa học sinh thành công!');
        } else {
            Session::flash('error', 'Xóa thất bại!');
        }

        //Thực hiện chuyển trang
        return redirect()->route('route_BackEnd_Class_List');
    }
    // private function ruleClass(){
    //     return [
    //         'name' => "required",
    //         'price' => "required",
    //         'slot' => "required",
    //         'start_date' => "required",
    //         'end_date' => "required",
    //         'lecturer_id' => "required",
    //         'location_id' => "required",
    //         'course_id' => "required",
    //     ];
    // }

    // private function messageClass(){
    //     return [
    //         "name.required" =>  "Không được để trống tên lớp học",
    //         "price.required" =>  "Không được để trống Giá",
    //         "slot.required" =>  "Không được để trống số chỗ",
    //         "start_date.required" =>  "Không được để trống ngày bắt đầu",
    //         "end_date.required" =>  "Không được để trống Ngày kết thúc",
    //         "lecturer_id.required" =>  "Không được để trống giảng viên",
    //         "location_id.required" =>  "Không được để trống địa điểm",
    //         "course_id.required" =>  "Không được để trống khóa học",
    //     ];
    // }

    // public function frontendDanhSachLopHoc($id, Request $request){
    //     $this->v['extParams'] = $request->all();
    //     $objKhoaHoc = new KhoaHoc();
    //     $this->v['objItemKhoaHoc'] = $objKhoaHoc->loadOne($id);
    //     $objLopHoc = new LopHoc();
    //     $this->v['lists'] = $objLopHoc->loadListWithPager($this->v['extParams'], $id);
    //     return view('khoahoc.client.fr-chi-tiet-khoa-hoc',$this->v);
    // }

    // public function inDanhSachLopHoc($id)
    // {
    //     $dataNhans = DB::table('dang_ky as tb1')
    //         ->select('tb2.id', 'tb2.ho_ten','tb2.ngay_sinh', 'tb1.ngay_dang_ky', 'tb2.so_dien_thoai', 'tb2.email','tb1.trang_thai')
    //         ->leftJoin('hoc_vien as tb2', 'tb2.id', '=', 'tb1.id_user')
    //         ->where('tb1.id_lop_hoc',$id)->get();

    //     $pdf = PDF::setOptions([
    //         'logOutputFile' => storage_path('logs/log.htm'),
    //         'tempDir' => storage_path('logs/')
    //     ])
    //     ->loadView('print.danhsachsinhvien', compact('dataNhans'))->setPaper('a4');
    //     return $pdf->stream();
    // }

    // public function frDangKyLopHoc($id, Request $request){

    //     $objLopHoc = new LopHoc();
    //     $this->v['objItemLopHoc'] = $objLopHoc->loadOneID($id);
    //     $objKhoaHoc = new KhoaHoc();
    //     $this->v['objKhoaHoc'] = $objKhoaHoc->loadOneID($this->v['objItemLopHoc']->id_khoa_hoc);
    //     return view('khoahoc.client.fr-dang-ky-khoa-hoc',  $this->v);
    // }
    //    public function thanhToanOnline(Request $request){
    //       dd(21312312);
    //    }
    //     public function themDangKy(Request $request){
    // //        dd($request->amountInCents);
    //         if($request->isMethod('post')) {
    //             $params['cols'] = array_map(function ($item) {
    //                 if ($item == '')
    //                     $item = null;
    //                 if (is_string($item))
    //                     $item = trim($item);
    //                 return $item;
    //             }, $request->post());
    //             if (!empty($request->stripeToken)){
    //                 $stripe = [
    //                     "secret_key"      => "sk_test_YuH8iOLZlo6r314XALggpFV8",
    //                     "publishable_key" => "pk_test_RktRYcffDgayxWK6b7Gho9Ol",
    //                 ];
    //                 $token  =$request->stripeToken;
    //                 $email  = $request->stripeEmail;
    //                 \Stripe\Stripe::setApiKey($stripe['secret_key']);
    //                 $customer = \Stripe\Customer::create([
    //                     'email' => $email,
    //                     'source'  => $token,
    //                 ]);

    //                 $charge = \Stripe\Charge::create([
    //                     'customer' => $customer->id,
    //                     'amount'   => 5000,
    //                     'currency' => 'usd',
    //                 ]);
    //             }
    //             unset($params['cols']['_token']);
    //             unset($params['cols']['id_lop_hoc']);
    //             unset($params['cols']['gia_tien']);
    //             unset($params['cols']['txtMoney']);
    //             unset($params['cols']['ma_khuyen_mai']);
    //             unset($params['cols']['txtDiscount']);


    //             $objDangKy = new DangKy();
    //             $objHocVien = new HocVien();
    //             $checkEmail = $objHocVien->loadCheckHocVien($request->email);
    //             if(!isset($checkEmail)){
    //                 $resHocVien = $objHocVien->saveNew($params);
    //             }else{
    //                 $checkHV = $objDangKy->loadCheckName($request->id_lop_hoc,$checkEmail->id);
    //                 if (!isset($checkHV)){
    //                     $resHocVien = $checkEmail->id;
    //                 }
    //             }
    //             if (isset($resHocVien)) {

    //                 $arrDangKy = [];


    //                 $arrDangKy['id_lop_hoc'] = $request->id_lop_hoc;
    //                 if ($request->txtDiscount == null) {
    //                     $arrDangKy['gia_tien'] = $request->gia_tien;
    //                     $uudai = 0;

    //                 } else {
    //                     if(!empty($request->ma_khuyen_mai)){
    //                         $resKhuyenMai = new MaChienDich();
    //                         $checkma = $resKhuyenMai->loadCheckName($request->ma_khuyen_mai);
    //                         $resChienDich = new ChienDich();
    //                         $checkGiam = $resChienDich->loadOne($checkma->id_chien_dich);
    //                     }
    //                     if ($checkma->trang_thai == 1) {
    //                         $arrDangKy['gia_tien'] = $request->gia_tien;
    //                         $uudai = 0;
    //                     } else {
    //                         $arrDangKy['gia_tien'] = $request->txtDiscount;
    //                         $uudai = $checkGiam->phan_tram_giam;
    //                     }
    //                 }
    //                 $arrDangKy['id_hoc_vien'] = $resHocVien;
    //                 if(!empty($request->amountInCents)){
    //                     $res = $objDangKy->saveNewOnline($arrDangKy);
    //                     if ($res){
    //                         $objLopHoc = new  LopHoc();
    //                         $socho = $objLopHoc->loadOneID($request->id_lop_hoc);
    //                         $udateSoCho= [];
    //                         $udateSoCho['id'] = $request->id_lop_hoc;
    //                         $udateSoCho['so_cho'] =  $socho->so_cho - 1;
    //                         $update = $objLopHoc->saveUpdateSoCho($udateSoCho);

    //                     }

    //                 }else{
    //                     $res = $objDangKy->saveNew($arrDangKy);
    //                 }

    //                 $email = $request->email;
    //                 $objGuiGmail = DB::table('dang_ky', 'tb1')
    //                     ->select('tb1.id', 'tb1.gia_tien', 'tb2.ho_ten', 'tb3.ten_lop_hoc', 'tb4.hoc_phi', 'tb4.ten_khoa_hoc', 'tb2.so_dien_thoai', 'tb1.trang_thai')
    //                     ->leftJoin('hoc_vien as tb2', 'tb2.id', '=', 'tb1.id_hoc_vien')
    //                     ->leftJoin('lop_hoc as tb3', 'tb3.id', '=', 'tb1.id_lop_hoc')
    //                     ->leftJoin('khoa_hoc as tb4', 'tb3.id_khoa_hoc', '=', 'tb4.id')
    //                     ->where('tb1.id', $res)->first();
    //                 $objGuiGmail->so_dien_thoai = $uudai;

    //                 Mail::to($email)->send(new OrderShipped($objGuiGmail));
    //                 if(!empty($request->ma_khuyen_mai)) {
    //                     $updatett = $resKhuyenMai->saveUpdateTT($request->ma_khuyen_mai);
    //                 }

    //                 $method_route = 'route_BackEnd_UserDangKyLopHoc';
    //                 if ($res == null) {
    //                     Session::push('post_form_data', $this->v['request']);
    //                     return redirect()->route($method_route);
    //                 } elseif ($res > 0) {
    //                     $this->v['request'] = [];
    //                     $request->session()->forget('post_from_data');
    //                     Session::flash('success', 'Thêm mới thành công danh mục khoá học');
    //                     return redirect()->route('route_BackEnd_UserDangKyLopHocThanhCong');
    //                 } else {
    //                     Session::push('errors', 'Lỗi thêm mới');
    //                     Session::push('post_form_data', $this->v['request']);
    //                     return redirect()->route($method_route);
    //                 }
    //             }else{
    //                 return redirect()->route('route_BackEnd_UserDangKyLopHocKhongThanhCong');
    //             }
    //         }
    //     }
    //     public function frontendDangKyKhongThanhCong(){
    //         return view('khoahoc.client.fr-dang-ky-khong-thanh-cong');

    //     }
    //     public function frontendDangKyThanhCong(){
    //         return view('khoahoc.client.fr-dang-ky-thanh-cong');
    //     }
}
