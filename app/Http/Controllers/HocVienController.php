<?php

namespace App\Http\Controllers;

use App\CentralFacility;
use App\ChienDich;
use App\ClassModel;
use App\HocVien;
use App\MaChienDich;
use App\DangKy;
use App\Mail\OrderShipped;
use App\Mail\SendMaKhuyenMai;
use App\User;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\DanhMucKhoaHocRequest;
use App\Http\Requests\HocVienRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Spipu\Html2Pdf\Html2Pdf;
use Dompdf\Dompdf;
use Carbon\Carbon;
use Dompdf\Options;

class HocVienController extends Controller
{
    private $v;

    public function __construct()
    {
        $this->v = [];
    }

    public function danhSachHocVien(Request $request)
    {
        $this->v['_title'] = 'Danh sách học viên';
        $this->v['routeIndexText'] = 'Danh sách học viên';

        $objDanhSachHocVien = new HocVien();
        //Nhận dữ liệu lọc từ view
        $this->v['extParams'] = $request->all();
        // dd($request->all());
        $list = $objDanhSachHocVien->loadListWithPager($this->v['extParams']);
        $this->v['list'] = $list;
        $danhSachGuiGmai[] =  $this->v['list'];
        foreach ($danhSachGuiGmai as $value) {
            $emailGui[] = $value->items();
        }
        $objDanhSachChienDich = new ChienDich();
        $this->v['chien_dich'] = $objDanhSachChienDich->loadListWithPager($this->v['extParams']);
        // dd($request->search_ngay_hoc);


        if (isset($_GET['btnGuiMa'])) {
            if ($request->id_khuyen_mai == '') {
                return redirect()->route('route_BackEnd_DanhSachHocVien_index');
            } else {
                $objMaKhuyenMai = new MaChienDich();
                $ma = $objMaKhuyenMai->loadMa($request->id_khuyen_mai);
                foreach ($ma as $value) {

                    $maGui[] = $value->ma_khuyen_mai;
                }
                if (count($maGui) == 0) {
                    Session::flash('errors', 'Mã Khuyến Mãi Đã Hết');
                    return redirect()->route('route_BackEnd_DanhSachHocVien_index');
                } else {

                    if (count($emailGui[0]) <= count($ma)) {

                        foreach ($emailGui[0] as $key => $value) {
                            $arrMa = ["ma" => $maGui[count($maGui) - 1]];
                            $isSent = Mail::to($value->email)->send(new SendMaKhuyenMai($arrMa));
                            if ($isSent) {
                                $updatesend = $objMaKhuyenMai->saveUpdateSend($arrMa);
                                unset($maGui[count($maGui) - 1]);
                            }
                        }
                    } else {
                        $thieu = count($emailGui[0]) - count($ma);
                        Session::flash('success', 'Mã khuyến mại bị thiếu ' . $thieu . ' mã vui lòng tạo thêm mã');
                        return redirect()->route('route_BackEnd_DanhSachHocVien_index');
                    }
                }
            }
        }

        // $objHocVien = new HocVien();
        // $objLop = new ClassModel();
        // $objDangky = new DangKy();

        // $input_time = isset($request['search_ngay_khai_giang']) ? $request['search_ngay_khai_giang'] : '';
        // // dd($input_time);
        
        // $time = explode(
        //     ' - ',
        //     $input_time
        // );
        
        
       
        // dd($time);
        //lấy học viên theo ngày
       
        // $hocvien= $objHocVien->loadDate();
        // dd($hocvien);
        // $batdau = Carbon::createFromFormat('d/m/Y', isset($time[0]) ? $time[0] : '' );
        // $ketthuc = Carbon::createFromFormat('d/m/Y', isset($time[1]) ? $time[1] : '');
        // // dd($batdau.'-'.$ketthuc);
        // $i = -1;
        // $hocVienArr = [];
        // foreach ($hocvien as $key => $item) {
        //     // dd($item);
        //     $x = $item->start_date;
        //     // dd($x);
        //     $y = $item->end_date;
        //     $lopBD = Carbon::createFromFormat('Y-m-d', $x);
        //     $lopKT = Carbon::createFromFormat('Y-m-d', $y);
        //     if ($batdau->gt($lopBD) && $lopKT->gt($batdau)) {
        //         $i++;
        //         $hocVienArr[$i] = $item;
        //     } elseif ($lopBD->gt($batdau) && $ketthuc->gt($lopBD)) {
        //         $i++;
        //         $hocVienArr[$i] = $item;
        //     };
        // }

        // dd($hocVienArr);


        return view('hocvien.danh-sach-hoc-vien', $this->v);
    }
    public function chiTietHocVien($id, Request $request)
    {
        $this->v['routeIndexText'] = 'Chi tiết thông tin học viên';
        $this->v['_action'] = 'Edit';
        $this->v['_title'] = 'Chi tiết thông tin học viên';
        $this->v['extParams'] = $request->all();
        $this->v['trang_thai'] = config('app.status_dang_ky');
        $objHocVien = new HocVien();
        $objItem = $objHocVien->loadOne($id);
        $this->v['objItem'] = $objItem;
        if (isset($this->v['extParams']['search_ngay_khai_giang'])) {
            $ngaythem = explode(' - ', $this->v['extParams']['search_ngay_khai_giang']);
            if (count($ngaythem) != 2) {
                Session::flash('error', 'Ngày khai giảng không hợp lệ');
                return redirect()->route('route_BackEnd_DanhSachHocVien_Detail', ['id' => $id]);
            }
            $datetime = array_map('convertDateToSql', $ngaythem);
            $datetime[0] = $datetime[0] . ' 00:00:00';
            $datetime[1] = $datetime[1] . ' 23:59:59';
            $this->v['extParams']['search_ngay_khai_giang_array'] = $datetime;
        }
        $objLopHoc = new ClassModel();
        $this->v['list'] = $objLopHoc->loadOneIDHV($id, $this->v['extParams']);
        $objDiaDiem = new CentralFacility();
        $diaDiems = $objDiaDiem->loadListWithPager($this->v['extParams']);
        $arrDiaDiem = [];
        foreach ($diaDiems as $index => $item) {
            $arrDiaDiem[$item->id] = $item->name;
        }
        $this->v['arrDiaDiem'] = $arrDiaDiem;
        return view('hocvien.chi-tiet-hoc-vien', $this->v);
    }
    private function uploadFile($file)
    {
        $fileName = time() . '_' . $file->getClientOriginalName();
        return $file->storeAs('hinh_anh_hoc_vien', $fileName, 'public');
    }
    public function updateThongTin($id, HocVienRequest $request)
    {
        $method_route = 'route_BackEnd_DanhSachHocVien_Detail';
        $modelHocVien = new HocVien();
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
        if (!preg_match("/^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i", $request->email)) {
            Session::flash('success', 'Email không chính xác');
            return redirect()->route('route_BackEnd_DanhSachHocVien_Detail', ['id' => $id]);
        } elseif (!preg_match("/(84|0[3|5|7|8|9])+([0-9]{8})\b/", $request->so_dien_thoai)) {

            Session::flash('success', 'Số điện thoại không chính xác');
            return redirect()->route('route_BackEnd_DanhSachHocVien_Detail', ['id' => $id]);
        } else {
            if ($request->hasFile('hinh_anh_hoc_vien') && $request->file('hinh_anh_hoc_vien')->isValid()) {
                $params['cols']['hinh_anh'] = $this->uploadFile($request->file('hinh_anh_hoc_vien'));
            }
            unset($params['cols']['_token']);

            $objItem = $modelHocVien->loadOne($id);
            if (empty($objItem)) {
                Session::push('errors', 'Không tồn tại người dùng này ' . $id);
                return redirect()->route('route_BackEnd_NguoiDung_index');
            }
            $params['cols']['id'] = $id;
            $res = $modelHocVien->saveUpdate($params);
            if ($res == null) // chuyển trang vì trong session đã có sẵn câu thông báo lỗi rồi
            {
                //            Session::push('post_form_data', $this->v['request']);
                return redirect()->route($method_route, ['id' => $id]);
            } elseif ($res == 1) {
                //            SpxLogUserActivity(Auth::user()->id, 'edit', $primary_table, $id, 'edit');
                $request->session()->forget('post_form_data'); // xóa data post
                Session::flash('success', 'Cập nhật thành công thông tin học viên!');
                return redirect()->route('route_BackEnd_DanhSachHocVien_index');
            } else {

                Session::push('errors', 'Lỗi cập nhật cho bản ghi: ' . $res);
                Session::push('post_form_data', $this->v['request']);
                return redirect()->route($method_route, ['id' => $id]);
            }
        }
    }
}
