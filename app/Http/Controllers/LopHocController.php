<?php

namespace App\Http\Controllers;

use App\ChienDich;
use App\Course;
use App\DangKy;
use App\DiaDiem;
use App\GiangVien;
use App\HocVien;
use App\Http\Requests\LopHocRequest;
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
use App\Teacher;
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
        // $validator = \Validator::make($request->all(), $this->ruleLopHoc(), $this->messageLopHoc());

        // if ($validator->fails()) {

        //     return response()->json(['errors' => $validator->errors()->all()]);
        // }
        $arrLopHoc = [];
        $latestLopHoc = DB::table('lop_hoc')->orderBy('id', 'DESC')->first();


        $arrLopHoc['id'] = null;
        $arrLopHoc['ten_lop_hoc'] = $request->ten_lop_hoc;
        $arrLopHoc['thoi_gian_bat_dau'] = $request->thoi_gian_bat_dau;
        $arrLopHoc['thoi_gian_ket_thuc'] = $request->thoi_gian_ket_thuc;
        $arrLopHoc['so_cho'] = $request->so_cho;
        $arrLopHoc['lich_hoc'] = $request->lich_hoc;
        $arrLopHoc['id_dia_diem'] = $request->id_dia_diem;
        $arrLopHoc['id_khoa_hoc'] = $request->id_khoa_hoc;
        $arrLopHoc['id_giang_vien'] = 0;
        $arrLopHoc['trang_thai'] = 1;

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
        $this->v['routeIndexText'] = 'Chi tiết lớp học';
        $this->v['_action'] = 'Edit';
        $this->v['_title'] = 'Chi tiết lớp học';
        $this->v['extParams'] = $request->all();
        $objLopHoc = new LopHoc();
        $objItem = $objLopHoc->loadOne($id);
        $this->v['objItem'] = $objItem;
        $objDiaDiem = new DiaDiem();
        $this->v['diaDiem'] = $objDiaDiem->loadListWithPager($this->v['extParams']);
        $objKhoaHoc = new KhoaHoc();
        $this->v['khoaHoc'] = $objKhoaHoc->loadListWithPager($this->v['extParams']);
        $objGiangVien = new Teacher();
        $this->v['giangVien'] = $objGiangVien->loadListWithPager($this->v['extParams']);
        $objDangKy = new DangKy();
        $this->v['lists'] = $objDangKy->loadListWithPager($this->v['extParams'], $id);
        return view('khoahoc.admin.chi-tiet-lop-hoc', $this->v);
    }
    public function updateLopHoc($id, LopHocRequest $request)
    {

        $method_route = 'route_BackEnd_LopHoc_Detail';
        $modelLopHoc = new LopHoc();
        $params = [
            'lophoc_edit' => Auth::user()->id
        ];
        $params['cols'] = array_map(function ($item) {
            if ($item == '')
                $item = null;
            if (is_string($item))
                $item = trim($item);
            return $item;
        }, $request->post());

        unset($params['cols']['_token']);
        $objItem = $modelLopHoc->loadOne($id);
        if (empty($objItem)) {
            Session::push('errors', 'Không tồn tại người dùng này ' . $id);
            return redirect()->route('route_BackEnd_NguoiDung_index');
        }
        $params['cols']['id'] = $id;
        $res = $modelLopHoc->saveUpdate($params);
        if ($res == null) // chuyển trang vì trong session đã có sẵn câu thông báo lỗi rồi
        {
            //            Session::push('post_form_data', $this->v['request']);
            return redirect()->route($method_route, ['id' => $id]);
        } elseif ($res == 1) {
            //            SpxLogUserActivity(Auth::user()->id, 'edit', $primary_table, $id, 'edit');
            $request->session()->forget('post_form_data'); // xóa data post
            Session::flash('success', 'Cập nhật thành công thông tin lớp học');

            return redirect()->route('route_BackEnd_LopHoc_Detail', ['id' => $id]);
        } else {

            Session::push('errors', 'Lỗi cập nhật cho bản ghi: ' . $res);
            Session::push('post_form_data', $this->v['request']);
            return redirect()->route($method_route, ['id' => $id]);
        }
    }

    public function danhLopHocChuaXep($id, $id_giangvien, Request $request)
    {
        $objGiangVien = new Teacher();
        $this->v['id_giang_vien'] = $id_giangvien;
        $this->v['routeIndexText'] = 'Danh sách lớp có thể xếp cho giảng viên ';
        $this->v['_action'] = 'Edit';
        $this->v['_title'] = 'Danh sách lớp có thể xếp cho giảng viên';
        $this->v['extParams'] = $request->all();
        $objLopHoc = new LopHoc();
        $this->v['list'] = $objLopHoc->loadIdKhoaHoc($id, $id_giangvien, $this->v['extParams']);
        $objDiaDiem = new DiaDiem();
        $this->v['dia_diem'] = $objDiaDiem->loadListIdAndName(['trang_thai', 1]);
        $diaDiems = $this->v['dia_diem'];
        $arrDiaDiem = [];
        foreach ($diaDiems as $index => $item) {
            $arrDiaDiem[$item->id] = $item->ten_dia_diem;
        }
        $this->v['arrDiaDiem'] = $arrDiaDiem;
        $this->v['ten_giang_vien'] = $objGiangVien->loadOne($id_giangvien);
        return view('khoahoc.admin.danh-sach-lop', $this->v);
    }

    private function ruleLopHoc()
    {
        return [
            'ten_lop_hoc' => "required",
            'lich_hoc' => "required",
            'thoi_gian_bat_dau' => "required",
            'thoi_gian_ket_thuc' => "required|greater_than:thoi_gian_bat_dau",
            'id_dia_diem' => "required",
            'so_cho' => "required",
        ];
    }

    private function messageLopHoc()
    {
        return [
            "ten_lop_hoc.required" =>  "Không được để trống tên lớp học",
            "lich_hoc.required" =>  "Không được để trống ca học",
            "thoi_gian_bat_dau.required" =>  "Không được để trống thời gian khai giảng",
            "thoi_gian_ket_thuc.required" =>  "Không được để trống thời gian kết thúc",
            "id_dia_diem.required" =>  "Không được để trống địa điểm",
            "so_cho.required" =>  "Không được để trống số chỗ",
        ];
    }

    public function frontendDanhSachLopHoc($id, Request $request)
    {
        $this->v['extParams'] = $request->all();
        $objKhoaHoc = new KhoaHoc();
        $this->v['objItemKhoaHoc'] = $objKhoaHoc->loadOne($id);
        $objLopHoc = new LopHoc();
        $this->v['lists'] = $objLopHoc->loadListWithPager($this->v['extParams'], $id);
        $arrDiaDiem = [];
        $objDiaDiem = new DiaDiem();
        $itemDiaDiem = $objDiaDiem->loadListWithPager($this->v['extParams']);
        foreach ($itemDiaDiem as $item) {
            $arrDiaDiem[$item->id] = $item->ten_dia_diem;
        }
        $this->v['arrDiaDiem'] = $arrDiaDiem;
        $arrGiangVien = [];
        $objGiangVien = new Teacher();
        $itemGiangVien = $objGiangVien->loadListWithPager($this->v['extParams']);
        foreach ($itemGiangVien as $value) {
            $arrGiangVien[$value->id] = $value->ten_giang_vien;
        }
        $this->v['arrGiangVien'] = $arrGiangVien;
        return view('khoahoc.client.fr-chi-tiet-khoa-hoc', $this->v);
    }

    public function inDanhSachLopHoc($id)
    {
        $dataNhans = DB::table('dang_ky as tb1')
            ->select('tb2.id', 'tb2.ho_ten', 'tb2.ngay_sinh', 'tb1.ngay_dang_ky', 'tb2.so_dien_thoai', 'tb2.email', 'tb1.trang_thai')
            ->leftJoin('hoc_vien as tb2', 'tb2.id', '=', 'tb1.id_hoc_vien')
            ->where('tb1.id_lop_hoc', $id)
            ->where('tb1.trang_thai', '=', 1)->get();
        $dataLop = DB::table('lop_hoc as tb1')->select('tb1.id', 'tb1.ten_lop_hoc')->where('tb1.id', $id)->first();

        $pdf = PDF::setOptions([
            'logOutputFile' => storage_path('logs/log.htm'),
            'tempDir' => storage_path('logs/')
        ])
            ->loadView('print.danhsachsinhvien', compact('dataNhans', 'dataLop'))->setPaper('a4');
        return $pdf->stream();
    }

    public function frDangKyLopHoc($id, Request $request)
    {
        $now = date('Y-m-d');
        $objLopHoc = new LopHoc();
        $objItemLopHoc = $objLopHoc->loadOneID($id);
        // if ($objItemLopHoc->thoi_gian_bat_dau < $now || $objItemLopHoc->so_cho <= 0) {
        //     return redirect()->route('route_BackEnd_UserLopHoc_Detail', ['id' => $objItemLopHoc->id_khoa_hoc]);
        // }
        $this->v['objItemLopHoc'] = $objItemLopHoc;

        $objKhoaHoc = new Course();
        $this->v['objKhoaHoc'] = $objKhoaHoc->loadOneID($this->v['objItemLopHoc']->course_id);
        // dd($objItemLopHoc);
        // dd($objKhoaHoc);
        $objGiangVien = new Teacher();
        $this->v['objItemGiangVien'] = $objGiangVien->loadOne($this->v['objItemLopHoc']->lecturer_id);
        return view('khoahoc.client.fr-dang-ky-khoa-hoc',  $this->v);
    }

    public function themDangKy(Request $request)
    {

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
    public function updateXepLop(Request $request)
    {
        $objLopHoc = new  LopHoc();
        $udateGiangVien = [];
        $udateGiangVien['id'] = $request->id;
        if ($request->trang_thai == 1) {

            $udateGiangVien['id_giang_vien'] =  $request->id_giang_vien;
            $update = $objLopHoc->saveUpdateXepLop($udateGiangVien);
        } elseif ($request->trang_thai == 0) {
            $udateGiangVien['id_giang_vien'] =  0;
            $update = $objLopHoc->saveUpdateXepLop($udateGiangVien);
        }

        if ($update) {
            $dataStatus['status'] = 1;
            return response()->json(array('dataStatus' =>  $dataStatus['status']));
        } else {
            $dataStatus['status'] = 0;
            return response()->json(array('dataStatus' =>  $dataStatus['status']));
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
