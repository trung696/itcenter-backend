<?php

namespace App\Http\Controllers;

use App\ChienDich;
use App\Course;
use App\DangKy;
use App\HocVien;
use App\Http\Requests\DangKyRequest;
use App\KhoaHoc;
use App\LopHoc;
use App\MaChienDich;
use App\Mail\OrderShipped;
use App\User;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\DanhMucKhoaHocRequest;
use App\Student;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Spipu\Html2Pdf\Html2Pdf;
use Dompdf\Dompdf;
use Dompdf\Options;

class DangKyController extends Controller
{
    private $v;

    public function __construct()
    {
        $this->v = [];
    }
    public function danhSachDangKy(Request $request)
    {
        $this->v['_title'] = 'Danh đăng ký';
        $this->v['routeIndexText'] = 'Danh đăng ký';
        $objDangKy = new DangKy();
        //Nhận dữ liệu lọc từ view
        $this->v['extParams'] = $request->all();
        $this->v['list'] = $objDangKy->loadListWithPagers($this->v['extParams']);

        return view('dangky.dang-ky', $this->v);
    }
    public function themDangKy(DangKyRequest $request)
    {
        $this->v['routeIndexText'] = 'Danh mục khoa học';
        $this->v['_action'] = 'Add';
        $this->v['_title'] = 'Thêm danh mục khoá học';
        // $this->v['status'] = config('app.status_giang_vien');
        // dd($this->v['status']);
        $objKhoaHoc = new Course();



        if ($request->isMethod('post')) {
            $params = [
                'danhmuc_add' => Auth::user()->id
            ];
            $params['cols'] = array_map(function ($item) {
                if ($item == '')
                    $item = null;
                if (is_string($item))
                    $item = trim($item);
                return $item;
            }, $request->post());
            if (!preg_match("/^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i", $request->email)) {
                Session::flash('success', 'Email không chính xác');
                return redirect()->route('route_BackEnd_DangKyAdmin_Add');
            } elseif (!preg_match("/(84|0[3|5|7|8|9])+([0-9]{8})\b/", $request->so_dien_thoai)) {

                Session::flash('success', 'Số điện thoại không chính xác');
                return redirect()->route('route_BackEnd_DangKyAdmin_Add');
            } else {
                unset($params['cols']['_token']);
                if ($request->hasFile('hinh_anh') && $request->file('hinh_anh')->isValid()) {
                    $params['cols']['hinh_anh'] = $this->uploadFile($request->file('hinh_anh'));
                }

                $objDangKy = new DangKy();
                $objHocVien = new Student();
                $checkEmail = $objHocVien->loadCheckHocVien($request->email);
                if (!isset($checkEmail)) {
                    $resHocVien = $objHocVien->saveNewAdmin($params);
                } else {
                    $checkHV = $objDangKy->loadCheckName($request->id_lop_hoc, $checkEmail->id);
                    if (!isset($checkHV)) {
                        $resHocVien = $checkEmail->id;
                    }
                }
                if (isset($resHocVien)) {
                    $gia = $objKhoaHoc->loadOne($request->id_khoa_hoc);

                    $arrDangKy = [];
                    $arrDangKy['id_lop_hoc'] = $request->id_lop_hoc;
                    $arrDangKy['id_hoc_vien'] = $resHocVien;
                    $arrDangKy['gia_tien'] = $gia->hoc_phi - ($gia->hoc_phi * $request->pham_tram_giam / 100);
                    $arrDangKy['trang_thai'] = $request->trang_thai;

                    // dd($request->trang_thai);
                    if ($request->trang_thai == 1) {
                        $res = $objDangKy->saveNewOnline($arrDangKy);
                        if ($res) {
                            $objLopHoc = new  LopHoc();
                            $socho = $objLopHoc->loadOneID($request->id_lop_hoc);
                            $updateSoCho = [];
                            $updateSoCho['id'] = $request->id_lop_hoc;
                            $updateSoCho['so_cho'] = $socho->so_cho - 1;
                            $update = $objLopHoc->saveUpdateSoCho($updateSoCho);
                        }
                    } else {
                        $res = $objDangKy->saveNew($arrDangKy);
                    }
                    $email = $request->email;
                    $objGuiGmail = DB::table('dang_ky', 'tb1')
                        ->select('tb1.id', 'tb1.gia_tien', 'tb2.ho_ten', 'tb3.name', 'tb3.price', 'tb4.name', 'tb2.so_dien_thoai', 'tb1.trang_thai')
                        ->leftJoin('hoc_vien as tb2', 'tb2.id', '=', 'tb1.id_hoc_vien')
                        ->leftJoin('lop_hoc as tb3', 'tb3.course_id', '=', 'tb1.id_lop_hoc')
                        ->leftJoin('course as tb4', 'tb3.id_khoa_hoc', '=', 'tb4.id')
                        ->where('tb1.id', $res)->first();
                    if (isset($request->pham_tram_giam)) {
                        $objGuiGmail->so_dien_thoai = $request->pham_tram_giam;
                    }


                    Mail::to($email)->send(new OrderShipped($objGuiGmail));

                    $method_route = 'route_BackEnd_DangKyAdmin_Add';
                    if ($res == null) {
                        Session::push('post_form_data', $this->v['request']);
                        return redirect()->route($method_route);
                    } elseif ($res > 0) {
                        $this->v['request'] = [];
                        $request->session()->forget('post_from_data');
                        Session::flash('success', 'Đăng ký thành công');
                        return redirect()->route('route_BackEnd_DanhSachDangKy_index');
                    } else {
                        Session::push('errors', 'Lỗi thêm mới');
                        Session::push('post_form_data', $this->v['request']);
                        return redirect()->route($method_route);
                    }
                } else {
                    Session::flash('success', 'Học viên đã đang ký khoá nay. Không thể đăng ký lại');
                    return redirect()->route('route_BackEnd_DanhSachDangKy_index');
                }
            }
        }

        $this->v['objKhoaHoc'] = $objKhoaHoc->loadListWithPager();
        // dd($objKhoaHoc);
        // dd($trang_thai);

        return view('dangky.them-dang-ky', $this->v);
    }
    private function uploadFile($file)
    {
        $fileName = time() . '_' . $file->getClientOriginalName();
        return $file->storeAs('hinh_anh_hoc_vien', $fileName, 'public');
    }
    public function getListLop($id_khoa_hoc)
    {
        $now = date('Y-m-d');
        $list_lop_hoc = DB::table('lop_hoc')->select('id', 'ten_lop_hoc', 'id_khoa_hoc')
            ->where('id_khoa_hoc', '=', $id_khoa_hoc)
            ->where('thoi_gian_bat_dau', '>', $now)
            ->orderBy('ten_lop_hoc', 'ASC')->get();
        return response()->json($list_lop_hoc, 200);
    }
    public function chiTietDangKy($id, Request $request)
    {
        $now = date('Y-m-d');
        $this->v['routeIndexText'] = 'Chi tiết đăng ký';
        $this->v['_action'] = 'Edit';
        $this->v['_title'] = 'Chi tiết đăng ký';
        $this->v['trang_thai'] = config('app.status_dang_ky');
        $objDangKy = new DangKy();
        $itemDK = $objDangKy->loadOne($id);
        $this->v['itemDK'] = $itemDK->id_lop_hoc;
        $this->v['itemDKTT'] = $itemDK->trang_thai;
        $this->v['itemGia'] = $itemDK->gia_tien;
        $objHocVien = new  HocVien();
        $this->v['itemHV'] = $objHocVien->loadOne($itemDK->id_hoc_vien);
        $objLopHoc = new LopHoc();
        $itemLH = $objLopHoc->loadOne($this->v['itemDK']);
        $objKhoaHoc = new KhoaHoc();
        $this->v['itemKH'] = $objKhoaHoc->loadOne($itemLH->id_khoa_hoc);

        $list_lop_hoc = DB::table('lop_hoc')->select('id', 'ten_lop_hoc')
            ->where('id_khoa_hoc', '=', $itemLH->id_khoa_hoc)
            ->where('thoi_gian_bat_dau', '>', $now)->get();
        $this->v['listLH'] = $list_lop_hoc;

        return view('dangky.sua-thong-tin', $this->v);
    }
    public function updateDangKy($id, Request $request)
    {
        $now = date('Y-m-d');
        $objDangKy = new DangKy();
        $dangKy = $objDangKy->loadOne($id);
        $objLopHoc = new LopHoc();
        $lopHoc = $objLopHoc->loadOne($dangKy->id_lop_hoc);
        if ($dangKy->trang_thai == 1) {

            Session::flash('success', 'Đăng Ký Này Đã Thanh Toán Không Thể Thay Đổi');
            return redirect()->route('route_BackEnd_AdminDangKy_Detail', ['id' => $id]);
        } elseif ($lopHoc->thoi_gian_bat_dau < $now) {
            Session::flash('success', 'Lớp Học Đã Khai Giảng Không Thay đổi');
            return redirect()->route('route_BackEnd_AdminDangKy_Detail', ['id' => $id]);
        } else {
            $arrDangKy = [];
            $arrDangKy['id'] = $id;
            $arrDangKy['id_lop_hoc'] = $request->id_lop_hoc;
            $arrDangKy['trang_thai'] = $request->trang_thai;
            $res = $objDangKy->updateDangKy($arrDangKy);
            if ($request->trang_thai == 1) {
                $objGuiGmail = DB::table('dang_ky', 'tb1')
                    ->select('tb1.id', 'tb1.gia_tien', 'tb2.ho_ten', 'tb2.email', 'tb3.ten_lop_hoc', 'tb4.hoc_phi', 'tb4.ten_khoa_hoc', 'tb2.so_dien_thoai', 'tb1.trang_thai')
                    ->leftJoin('hoc_vien as tb2', 'tb2.id', '=', 'tb1.id_hoc_vien')
                    ->leftJoin('lop_hoc as tb3', 'tb3.id', '=', 'tb1.id_lop_hoc')
                    ->leftJoin('khoa_hoc as tb4', 'tb3.id_khoa_hoc', '=', 'tb4.id')
                    ->where('tb1.id', $id)->first();
                $email = $objGuiGmail->email;
                Mail::to($email)->send(new OrderShipped($objGuiGmail));
                $objLopHoc = new  LopHoc();
                $socho = $objLopHoc->loadOneID($request->id_lop_hoc);
                $udateSoCho = [];
                $udateSoCho['id'] = $request->id_lop_hoc;
                $udateSoCho['so_cho'] = $socho->so_cho - 1;
                $update = $objLopHoc->saveUpdateSoCho($udateSoCho);
            }
            if ($res == null) // chuyển trang vì trong session đã có sẵn câu thông báo lỗi rồi
            {
                return redirect()->route('route_BackEnd_AdminDangKy_Detail', ['id' => $id]);
            } elseif ($res == 1) {
                $request->session()->forget('post_form_data'); // xóa data post
                Session::flash('success', 'Cập nhật thành công!');

                return redirect()->route('route_BackEnd_AdminDangKy_Detail', ['id' => $id]);
            } else {

                Session::push('errors', 'Lỗi cập nhật cho bản ghi: ' . $res);
                Session::push('post_form_data', $this->v['request']);
                return redirect()->route('route_BackEnd_AdminDangKy_Detail', ['id' => $id]);
            }
        }
    }
    public function inHoaDon($id, Request $request)
    {
        $emails = DB::table('dang_ky', 'tb1')
            ->select('tb1.id', 'tb1.gia_tien', 'tb2.ho_ten', 'tb3.ten_lop_hoc', 'tb4.hoc_phi', 'tb4.ten_khoa_hoc', 'tb2.so_dien_thoai', 'tb1.trang_thai')
            ->leftJoin('hoc_vien as tb2', 'tb2.id', '=', 'tb1.id_hoc_vien')
            ->leftJoin('lop_hoc as tb3', 'tb3.id', '=', 'tb1.id_lop_hoc')
            ->leftJoin('khoa_hoc as tb4', 'tb3.id_khoa_hoc', '=', 'tb4.id')
            ->where('tb1.id', $id)->first();
        $pdf = PDF::setOptions([
            'logOutputFile' => storage_path('logs/log.htm'),
            'tempDir' => storage_path('logs/')
        ])
            ->loadView('print.inhoadon', compact('emails'))->setPaper('a4');
        return $pdf->stream();
    }
}
