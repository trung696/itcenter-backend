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
use App\Ca;
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
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use App\Exports\ClassesExport;
use App\Exports\StudentExport;
use Maatwebsite\Excel\Facades\Excel;

class ClassController extends  Controller
{
    private $v;
    public function __construct()
    {
        $this->v = [];
    }
    public function export()
    {
        return Excel::download(new StudentExport, 'class.xlsx');
    }
    public function classList(Request $request)
    {
        
        $this->v['routeIndexText'] = 'Danh sách lớp học';
        $this->v['_action'] = 'List';
        $this->v['_title'] = 'danh sách lớp học';

        $objClassModel = new ClassModel();
        ;
        // dd($objClassModel);
        $this->v['extParams'] = $request->all();
        $this->v['lists'] = $objClassModel->loadListWithPager($this->v['extParams']);
        // dd($this->v['lists']);
        $this->v['objItemClass'] = $objClassModel;
        $objHocVien = new HocVien();
        $objClass = new ClassModel();
        $objUser = new User();
        $this->v['user'] = $objUser->loadListIdAndName(['status', 1]);
        $user = $this->v['user'];
        $this->v['lecturer'] = $this->v['user'];
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
        $arrCoursePrice = [];
        foreach ($course as $index => $item) {
            // dd($item);
            $arrCourse[$item->id] = $item->name;
        }

        foreach ($course as $index => $item) {
            // dd($item);
            $arrCoursePrice[$item->id] = $item->price;
        }
        // dd($arrCoursePrice);
        $this->v['arrCourse'] = $arrCourse;
        $this->v['arrCoursePrice'] = $arrCoursePrice;
        // dd($this->v['arrCourse']);
        // if (empty($objItem)) {
        //     Session::push('errors', 'Không tồn tại danh mục này ' . $id);
        //     return redirect()->back();
        // }
        $this->v['extParams'] = $request->all();
        $this->v['status'] = config('app.status_user');

        // if (isset($this->v['extParams']['search_ngay_khai_giang'])) {
        //     $ngaythem = explode(' - ', $this->v['extParams']['search_ngay_khai_giang']);
        //     if (count($ngaythem) != 2) {
        //         Session::flash('error', 'Ngày khai giảng không hợp lệ');
        //         return redirect()->route($this->routeIndex);
        //     }
        //     $datetime = array_map('convertDateToSql', $ngaythem);
        //     $datetime[0] = $datetime[0] . ' 00:00:00';
        //     $datetime[1] = $datetime[1] . ' 23:59:59';
        //     $this->v['extParams']['search_ngay_khai_giang_array'] = $datetime;
        // }

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

        $objCa = new Ca();
        $this->v['ca'] = $objCa->loadListIdAndName();
        $ca = $this->v['ca'];
        // dd($user);
        $arrCa = [];
        foreach ($ca as $index => $item) {
            // dd($item);
            $arrCa[$item->id] = $item->ca_hoc;
        }
        $this->v['arrCaHoc'] = $arrCa;
        $objCourse = new  Course();
        $this->v['course'] = $objCourse->loadListIdAndName(['status', 1]);
        // dd( $this->v['arrCaHoc']);

        
        
        return view('class.list-class', $this->v);
    }

    public function addClass(ClassRequest $request)
    {
        $this->v['_title'] = 'Khoá học';
        $method_route = 'route_BackEnd_Class_Add';
        $this->v['_action'] = 'Add';
        $this->v['_title'] = 'Thêm lớp học';
        $this->v['request'] = $request;
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
            $modelClass = new ClassModel();
            $objCa = new Ca();
            $idCa = $request->id_ca;
            $caname = $objCa->loadOne($idCa);
            $objGV = new User();
            $idGV = $request->lecturer_id;
            $gvname = $objGV->loadOne($idGV);


            unset($params['cols']['_token']);
            $objClass = new ClassModel();
            $idGV = [];
            $idGV['id'] = $request->lecturer_id;
            $idGV['id_ca'] = $request->id_ca;
            $idGV['id_lop'] = $request->id;
            $countrep = $objClass->checkCa($idGV);
            $check = $modelClass->getDate($idGV);
            $newst = strtotime($request->start_date);
            $newend = strtotime($request->end_date);
            $trungngay = 0;
            foreach ($check as $key => $val) {
                $soloptrung = $key + 1;
                $start = strtotime($val->start_date);
                $end = strtotime($val->end_date);
                $time = ($newst >= $start && $newst <= $end) || ($newend >= $start && $newend <= $end);
                // dd($time);
                // var_dump($time);
                if ($time) {
                    $trungngay = 1;
                    // var_dump($trungngay);
                }
            }
            // dd($countrep);
            if ($trungngay == 1) {
                // Session::push('post_form_data', $this->v['request']);
                return Redirect::back()->withErrors(['msg' => 'Giảng viên ' . $gvname->name . ' đã dạy giờ này']);

                return redirect()->route($method_route);
            } else {
                $res = $objClass->saveNew($params);
                $request->session()->forget($method_route);
            }


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

        $objCaHoc = new Ca();
        $this->v['Ca'] = $objCaHoc->loadListIdAndName(['trang_thai', 1]);

        return view('class.add-class', $this->v);
    }

    public function classDetail($id, Request $request)
    {

        $this->v['routeIndexText'] = 'Chi tiết Lớp học';
        $this->v['_action'] = 'Edit';
        $this->v['_title'] = 'Chi tiết Lớp học';
        $this->v['id'] = $id;
        // dd($id);
        // $objUser = new User();
        // $objGV = $objUser->loadOne($id);
        // dd($objGV);
        $objClassModel = new ClassModel();
        // $this->v['lists'] = $objClassModel->loadListWithPager($this->v['extParams'], $id);
        $objItemClass = $objClassModel->loadOne($id);

        $this->v['class'] = $objClassModel;
        $this->v['objItemClass'] = $objItemClass;
        // dd($objItemClass->start_date->format('d/m/Y'));
        $objUser = new User();
        $objGV = $objUser->loadOne($objItemClass->lecturer_id);
        // dd($objGV);
        $this->v['GV_id'] = $objItemClass->lecturer_id;
        $objUser = new User();
        $this->v['user'] = $objUser->loadListIdAndName(['status', 1]);
        // dd($this->v['arrCourse']);
        // dd($objUser->loadListIdAndName(['status', 1]));
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
        // dd($this->v['arrUser']);

        $objCourse = new Course();
        $objItem = $objCourse->loadOne($id);

        $this->v['course_id'] = $objCourse->loadListIdAndName(['status', 1]);

        $course = $this->v['course_id'];
        // dd($user);
        $arrCourse = [];
        $arrCoursePrice = [];
        foreach ($course as $index => $item) {
            // dd($item);
            $arrCourse[$item->id] = $item->name;
        }

        foreach ($course as $index => $item) {
            // dd($item);
            $arrCoursePrice[$item->id] = $item->price;
        }
        // dd($arrCoursePrice);
        $this->v['arrCourse'] = $arrCourse;
        $this->v['arrCoursePrice'] = $arrCoursePrice;
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

        $objCa = new Ca();
        $objItem = $objCa->loadOne($id);

        $this->v['ca_id'] = $objCa->loadListIdAndName();

        $course = $this->v['ca_id'];
        // dd($objCa);
        return view('class.update-class', $this->v);
    }

    public function updateClass($id, ClassRequest $request)
    {
        $this->v['request'] = $request;
        // dd('abc');
        $method_route = 'route_BackEnd_Class_Detail';
        $modelClass = new ClassModel();
        $lophientai = $modelClass->loadOne($id);
        $idGV = [];
        $idGV['id'] = $request->lecturer_id;
        $idGV['id_ca'] = $request->id_ca;
        $idGV['id_lop'] = $request->id;
        $countrep = $modelClass->checkCa($idGV);
        $check = $modelClass->getDate($idGV);
        $newst = strtotime($request->start_date);
        $newend = strtotime($request->end_date);
        $trungngay = 0;
        foreach ($check as $key => $val) {
            $soloptrung = $key + 1;
            $start = strtotime($val->start_date);
            $end = strtotime($val->end_date);
            $time = ($newst >= $start && $newst <= $end) || ($newend >= $start && $newend <= $end);
            // dd($time);
            // var_dump($time);
            if ($time) {
                $trungngay = 1;
                // var_dump($trungngay);
            }
        }
        // var_dump($trungngay);
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
        $params['cols']['id'] = $id;
        // dd($params['cols']);
        $res = $modelClass->saveUpdate($params);
        if ($res == null) // chuyển trang vì trong session đã có sẵn câu thông báo lỗi rồi
        {
            Session::push('post_form_data', $this->v['request']);
            return redirect()->route($method_route, ['id' => $id]);
        } elseif ($trungngay == 1) {

            return Redirect::back()->withErrors(['msg' => 'Giảng viên đã dạy giờ này']);
            // Session::push('post_form_data', $this->v['request']);
            return redirect()->route($method_route, ['id' => $id]);
        } elseif ($res == 1 && $trungngay == 0) {
            //            SpxLogUserActivity(Auth::user()->id, 'edit', $primary_table, $id, 'edit');
            $request->session()->forget('post_form_data'); // xóa data post
            Session::flash('success', 'Cập nhật thành công thông tin lớp học!');
            return redirect()->route('route_BackEnd_Class_List');
        }
        //  else {
        //     Session::push('errors', 'Lỗi cập nhật bản ghi ' . $res);
        //     Session::push('post_form_data', $this->v['request']);
        //     return redirect()->route($method_route, ['id' => $id]);
        // }
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
    public function xepLop($id)
    {
        $count = new ClassModel();
        $lop = $count->loadOne($id);
        $idGV = [];
        $idGV['id'] = $lop->lecturer_id;
        $idGV['id_ca'] = $lop->id_ca;
        $idGV['id_lop'] = $lop->id;
        $countrep = $count->checkCa($idGV);
        $check = $count->getDate($idGV);
        $newst = strtotime($lop->start_date);
        $newend = strtotime($lop->end_date);
        // dd($check);
        foreach ($check as $item => $value) {
            var_dump($value->start_date);
        }
        foreach ($check as $key => $val) {
            $soloptrung = $key + 1;
            $start = strtotime($val->start_date);
            $end = strtotime($val->end_date);
            $time = ($newst >= $start && $newst <= $end) || ($newend >= $start && $newend <= $end);
            if ($time) {
                $trungngay = 1;
                dd("trùng ngày");
            } else {
                dd("KHÔNG trùng ngày");
                $trungngay = 0;
            }
        }
    }
    public function inDanhSachLop($id, Request $request)
    {
        // $emails = DB::table('hoc_vien', 'tb1')
        //     ->select('tb1.id as MSV', 'tb1.ho_ten as Họ tên sinh viên', 'tb3.name as Tên Lớp',  'tb4.name as Tên Khóa', 'tb1.so_dien_thoai', 'tb1.email', 'tb1.ngay_sinh', 'tb1.gioi_tinh')
        //     ->leftJoin('dang_ky as tb2', 'tb2.id_hoc_vien', '=', 'tb1.id')
        //     ->leftJoin('class as tb3', 'tb2.id_lop_hoc', '=', 'tb3.id')
        //     ->leftJoin('course as tb4', 'tb3.course_id', '=', 'tb4.id')
        //     ->where('tb3.id', $id)->toSql();


        $emails = DB::table('dang_ky', 'tb2')
            ->select('tb1.id as MSV', 'tb1.ho_ten as sv_name', 'tb3.name as class_name',  'tb4.name as course_name', 'tb1.so_dien_thoai', 'tb1.email', 'tb1.ngay_sinh', 'tb1.gioi_tinh')
            ->leftJoin('hoc_vien as tb1', 'tb1.id', '=', 'tb2.id_hoc_vien')
            ->leftJoin('class as tb3', 'tb3.id', '=', 'tb2.id_lop_hoc')
            ->leftJoin('course as tb4', 'tb3.course_id', '=', 'tb4.id')
            ->where('tb3.id', $id)
            ->where('tb2.trang_thai', 3);
            $test = DB::table('dang_ky', 'tb2')
            ->select('tb1.id as MSV', 'tb1.ho_ten as sv_name', 'tb3.name as class_name',  'tb4.name as course_name', 'tb1.so_dien_thoai', 'tb1.email', 'tb1.ngay_sinh', 'tb1.gioi_tinh')
            ->leftJoin('hoc_vien as tb1', 'tb1.id', '=', 'tb2.id_hoc_vien')
            ->leftJoin('class as tb3', 'tb3.id', '=', 'tb2.id_lop_hoc')
            ->leftJoin('course as tb4', 'tb3.course_id', '=', 'tb4.id')
            ->where('tb3.id', $id)
            ->where('tb2.trang_thai', 3);
        dd($test->toSql());
        $classname = DB::table('class', 'tb3')->leftJoin('course as tb4', 'tb3.course_id', '=', 'tb4.id')->select('tb3.name as className', 'tb4.name as courseName')->where('tb3.id', $id)->first();
        // dd($emails);
        $pdf = PDF::setOptions([
            'logOutputFile' => storage_path('logs/log.htm'),
            'tempDir' => storage_path('logs/')
        ])
            ->loadView('print.indanhsach', compact('emails', 'classname'))->setPaper('a4');
        return $pdf->stream();
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
