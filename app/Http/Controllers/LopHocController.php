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
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\TaiSanRequest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Spipu\Html2Pdf\Html2Pdf;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Mail\OrderShipped;
use Stripe\Charge;

class LopHocController extends  Controller
{
    private $v;
    public function __construct()
    {
        $this->v = [];
    }

    public function themLopHoc(Request $request)
    {
        $validator = \Validator::make($request->all(), $this->ruleLopHoc(), $this->messageLopHoc());

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        $arrLopHoc = [];
        $latestLopHoc = DB::table('lop_hoc')->orderBy('id', 'DESC')->first();
        $latestID = $latestLopHoc ? $latestLopHoc->id : 0;
        $arrLopHoc['id'] = null;
        $arrLopHoc['ten_lop_hoc'] = $request->ten_lop_hoc;
        $arrLopHoc['ca_hoc'] = $request->ca_hoc;
        $arrLopHoc['thoi_giang_khai_giang'] = $request->thoi_giang_khai_giang;
        $arrLopHoc['id_dia_diem'] = $request->id_dia_diem;
        $arrLopHoc['so_cho'] = $request->so_cho;
        $arrLopHoc['id_khoa_hoc'] = $request->id_khoa_hoc;
        $arrLopHoc['id_giang_vien'] = $request->id_giang_vien;
        $arrLopHoc['trang_thai'] = $request->trang_thai;
        $result = DB::table('lop_hoc')->insert($arrLopHoc);
        if (!$result) {
            return response()->json(['errors' => Session::exists('errors') ? Session::pull('errors') : 'Lỗi thêm mới'], 500);
        } else {
            Session::flash('success', 'Tạo tài sản con tự động thành công');
            return redirect()->back();
        }
    }

    public function chiTietLopHoc($id, Request $request)
    {

        dd(123);
        $this->v['routeIndexText'] = 'Chi tiết lớp học';
        $this->v['_action'] = 'Edit';
        $this->v['_title'] = 'Chi tiết lớp học';
        $this->v['extParams'] = $request->all();
        $objLopHoc = new LopHoc();
        $objItem = $objLopHoc->loadOne($id);
        $this->v['objItem'] = $objItem;
        $objDangKy = new DangKy();
        $this->v['lists'] = $objDangKy->loadListWithPager($this->v['extParams'], $id);
        return view('khoahoc.admin.chi-tiet-lop-hoc', $this->v);
    }

    private function ruleLopHoc()
    {
        return [
            'ten_lop_hoc' => "required",
            'ca_hoc' => "required",
            'thoi_giang_khai_giang' => "required",
            'id_dia_diem' => "required",
            'id_khoa_hoc' => "required",
            'id_giang_vien' => "required",
            'so_cho' => "required",
            'trang_thai' => "required",
        ];
    }

    private function messageLopHoc()
    {
        return [
            "ten_lop_hoc.required" =>  "Không được để trống tên lớp học",
            "ca_hoc.required" =>  "Không được để trống ca học",
            "thoi_giang_khai_giang.required" =>  "Không được để trống thời gian khai giang",
            "id_dia_diem.required" =>  "Không được để trống địa điểm",
            "id_khoa_hoc.required" =>  "Không được để trống khoá học",
            "id_giang_vien.required" =>  "Không được để trống giảng viên",
            "so_cho.required" =>  "Không được để trống số chỗ",
            "trang_thai.required" =>  "Không được để trống trạng thái",
        ];
    }

    public function frontendDanhSachLopHoc($id, Request $request)
    {
        $this->v['extParams'] = $request->all();
        $objKhoaHoc = new KhoaHoc();
        $this->v['objItemKhoaHoc'] = $objKhoaHoc->loadOne($id);
        $objLopHoc = new LopHoc();
        $this->v['lists'] = $objLopHoc->loadListWithPager($this->v['extParams'], $id);
        return view('khoahoc.client.fr-chi-tiet-khoa-hoc', $this->v);
    }

    public function inDanhSachLopHoc($id)
    {
        $dataNhans = DB::table('dang_ky as tb1')
            ->select('tb2.id', 'tb2.ho_ten', 'tb2.ngay_sinh', 'tb1.ngay_dang_ky', 'tb2.so_dien_thoai', 'tb2.email', 'tb1.trang_thai')
            ->leftJoin('hoc_vien as tb2', 'tb2.id', '=', 'tb1.id_user')
            ->where('tb1.id_lop_hoc', $id)->get();

        $pdf = PDF::setOptions([
            'logOutputFile' => storage_path('logs/log.htm'),
            'tempDir' => storage_path('logs/')
        ])
            ->loadView('print.danhsachsinhvien', compact('dataNhans'))->setPaper('a4');
        return $pdf->stream();
    }

    public function frDangKyLopHoc($id, Request $request)
    {

        $objLopHoc = new LopHoc();
        $this->v['objItemLopHoc'] = $objLopHoc->loadOneID($id);
        $objKhoaHoc = new KhoaHoc();
        $this->v['objKhoaHoc'] = $objKhoaHoc->loadOneID($this->v['objItemLopHoc']->id_khoa_hoc);
        return view('khoahoc.client.fr-dang-ky-khoa-hoc',  $this->v);
    }
    //    public function thanhToanOnline(Request $request){
    //       dd(21312312);
    //    }
    public function themDangKy(Request $request)
    {
        //        dd($request->amountInCents);
        if ($request->isMethod('post')) {
            $params['cols'] = array_map(function ($item) {
                if ($item == '')
                    $item = null;
                if (is_string($item))
                    $item = trim($item);
                return $item;
            }, $request->post());
            if (!empty($request->stripeToken)) {
                $stripe = [
                    "secret_key"      => "sk_test_YuH8iOLZlo6r314XALggpFV8",
                    "publishable_key" => "pk_test_RktRYcffDgayxWK6b7Gho9Ol",
                ];
                $token  = $request->stripeToken;
                $email  = $request->stripeEmail;
                \Stripe\Stripe::setApiKey($stripe['secret_key']);
                $customer = \Stripe\Customer::create([
                    'email' => $email,
                    'source'  => $token,
                ]);

                $charge = \Stripe\Charge::create([
                    'customer' => $customer->id,
                    'amount'   => 5000,
                    'currency' => 'usd',
                ]);
            }
            unset($params['cols']['_token']);
            unset($params['cols']['id_lop_hoc']);
            unset($params['cols']['gia_tien']);
            unset($params['cols']['txtMoney']);
            unset($params['cols']['ma_khuyen_mai']);
            unset($params['cols']['txtDiscount']);


            $objDangKy = new DangKy();
            $objHocVien = new HocVien();
            $checkEmail = $objHocVien->loadCheckHocVien($request->email);
            if (!isset($checkEmail)) {
                $resHocVien = $objHocVien->saveNew($params);
            } else {
                $checkHV = $objDangKy->loadCheckName($request->id_lop_hoc, $checkEmail->id);
                if (!isset($checkHV)) {
                    $resHocVien = $checkEmail->id;
                }
            }
            if (isset($resHocVien)) {

                $arrDangKy = [];


                $arrDangKy['id_lop_hoc'] = $request->id_lop_hoc;
                if ($request->txtDiscount == null) {
                    $arrDangKy['gia_tien'] = $request->gia_tien;
                    $uudai = 0;
                } else {
                    if (!empty($request->ma_khuyen_mai)) {
                        $resKhuyenMai = new MaChienDich();
                        $checkma = $resKhuyenMai->loadCheckName($request->ma_khuyen_mai);
                        $resChienDich = new ChienDich();
                        $checkGiam = $resChienDich->loadOne($checkma->id_chien_dich);
                    }
                    if ($checkma->trang_thai == 1) {
                        $arrDangKy['gia_tien'] = $request->gia_tien;
                        $uudai = 0;
                    } else {
                        $arrDangKy['gia_tien'] = $request->txtDiscount;
                        $uudai = $checkGiam->phan_tram_giam;
                    }
                }
                $arrDangKy['id_hoc_vien'] = $resHocVien;
                if (!empty($request->amountInCents)) {
                    $res = $objDangKy->saveNewOnline($arrDangKy);
                    if ($res) {
                        $objLopHoc = new  LopHoc();
                        $socho = $objLopHoc->loadOneID($request->id_lop_hoc);
                        $udateSoCho = [];
                        $udateSoCho['id'] = $request->id_lop_hoc;
                        $udateSoCho['so_cho'] =  $socho->so_cho - 1;
                        $update = $objLopHoc->saveUpdateSoCho($udateSoCho);
                    }
                } else {
                    $res = $objDangKy->saveNew($arrDangKy);
                }

                $email = $request->email;
                $objGuiGmail = DB::table('dang_ky', 'tb1')
                    ->select('tb1.id', 'tb1.gia_tien', 'tb2.ho_ten', 'tb3.ten_lop_hoc', 'tb4.hoc_phi', 'tb4.ten_khoa_hoc', 'tb2.so_dien_thoai', 'tb1.trang_thai')
                    ->leftJoin('hoc_vien as tb2', 'tb2.id', '=', 'tb1.id_hoc_vien')
                    ->leftJoin('lop_hoc as tb3', 'tb3.id', '=', 'tb1.id_lop_hoc')
                    ->leftJoin('khoa_hoc as tb4', 'tb3.id_khoa_hoc', '=', 'tb4.id')
                    ->where('tb1.id', $res)->first();
                $objGuiGmail->so_dien_thoai = $uudai;

                Mail::to($email)->send(new OrderShipped($objGuiGmail));
                if (!empty($request->ma_khuyen_mai)) {
                    $updatett = $resKhuyenMai->saveUpdateTT($request->ma_khuyen_mai);
                }

                $method_route = 'route_BackEnd_UserDangKyLopHoc';
                if ($res == null) {
                    Session::push('post_form_data', $this->v['request']);
                    return redirect()->route($method_route);
                } elseif ($res > 0) {
                    $this->v['request'] = [];
                    $request->session()->forget('post_from_data');
                    Session::flash('success', 'Thêm mới thành công danh mục khoá học');
                    return redirect()->route('route_BackEnd_UserDangKyLopHocThanhCong');
                } else {
                    Session::push('errors', 'Lỗi thêm mới');
                    Session::push('post_form_data', $this->v['request']);
                    return redirect()->route($method_route);
                }
            } else {
                return redirect()->route('route_BackEnd_UserDangKyLopHocKhongThanhCong');
            }
        }
    }
    public function frontendDangKyKhongThanhCong()
    {
        return view('khoahoc.client.fr-dang-ky-khong-thanh-cong');
    }
    public function frontendDangKyThanhCong()
    {
        return view('khoahoc.client.fr-dang-ky-thanh-cong');
    }
}
