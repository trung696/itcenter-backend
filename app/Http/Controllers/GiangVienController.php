<?php

namespace App\Http\Controllers;

use App\ChuyenMonDay;
use App\GiangVien;
use App\KhoaHoc;
use App\User;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\DanhMucKhoaHocRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Spipu\Html2Pdf\Html2Pdf;
use Dompdf\Dompdf;
use Dompdf\Options;

//require_once __DIR__ . '/../../SLib/functions.php';

class GiangVienController extends Controller
{
    private $v;

    public function __construct()
    {
        $this->v = [];
    }

    public function danhSachGiangVien(Request $request)
    {
        $this->v['_title'] = 'Danh mục khoá học';
        $this->v['routeIndexText'] = 'Danh mục khoá học';
        $objGiangVien = new GiangVien();
        //Nhận dữ liệu lọc từ view
        $this->v['extParams'] = $request->all();
        $this->v['list'] = $objGiangVien->loadListWithPager($this->v['extParams']);
        // $objChuyenMon = new ChuyenMonDay();
        // $this->v['listChuyenMon'] = $objChuyenMon->loadListWithPager($this->v['extParams']);



        return view('giangvien.admin.danh-sach-giang-vien', $this->v);
    }
    public function themGiangVien(Request $request)
    {
        $this->v['_title'] = 'Giảng Viên';
        $method_route = 'route_BackEnd_GiangVien_Add';
        $this->v['_action'] = 'Add';
        $this->v['_title'] = 'Thêm Giảng Viên';
        $this->v['trang_thai'] = config('app.status_giang_vien');
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
            if ($request->hasFile('hinh_anh_giang_vien') && $request->file('hinh_anh_giang_vien')->isValid()) {
                $params['cols']['hinh_anh_giang_vien'] = $this->uploadFile($request->file('hinh_anh_giang_vien'));
            }

            unset($params['cols']['_token']);


            $objGiangVien = new GiangVien();
            $res = $objGiangVien->saveNew($params);

            $arrChuyenMon = [];
            $i = 0;
            foreach ($request->id_khoa_hoc as $item) {
                $arrChuyenMon['id'] = null;
                $arrChuyenMon['id_giang_vien'] = $res;
                $arrChuyenMon['id_khoa_hoc'] = $item;
                $arrChuyenMon['trang_thai'] = 1;
                $result = DB::table('chuyen_mon_day')->insert($arrChuyenMon);
                $i++;
            }
            if ($res == null) // chuyển trang vì trong session đã có sẵn câu thông báo lỗi rồi
            {
                Session::push('post_form_data', $this->v['request']);
                return redirect()->route($method_route);
            } elseif ($res > 0) {
                $this->v['request'] = [];
                $request->session()->forget('post_form_data'); // xóa data post
                Session::flash('success', 'Thêm mới thành công khoá học !');
                return redirect()->route('route_BackEnd_DanhSachGiangVien_index');
            } else {
                Session::push('errors', 'Lỗi thêm mới: ' . $res);
                Session::push('post_form_data', $this->v['request']);
                return redirect()->route($method_route);
            }
        } else {
            // không phải post
            $request->session()->forget($method_route); // hủy session nếu vào bằng sự kiện get
        }
        $objKhoaHoc = new KhoaHoc();
        $this->v['extParams'] = $request->all();
        $this->v['listKhoaHoc'] = $objKhoaHoc->loadListWithPager($this->v['extParams']);
        return view('giangvien.admin.them-giang-vien', $this->v);
    }
    private function uploadFile($file)
    {
        $fileName = time() . '_' . $file->getClientOriginalName();
        return $file->storeAs('hinh_anh_giang_vien', $fileName, 'public');
    }
    public function chiTietGiangVien($id, Request $request)
    {
        $this->v['routeIndexText'] = 'Chi tiết giảng viên';
        $this->v['_action'] = 'Edit';
        $this->v['_title'] = 'Chi tiết giảng viên';
        $this->v['extParams'] = $request->all();
        $objGiangVien = new GiangVien();
        $this->v['objItem']  = $objGiangVien->loadOne($id);
        $objKhoaHoc = new KhoaHoc();
        $this->v['listKhoaHoc'] = $objKhoaHoc->loadListWithPager($this->v['extParams']);
        // $objChuyenMon = new ChuyenMonDay();
        // $this->v['listChuyenMon'] = $objChuyenMon->loadChuyenMon($id);
        return view('giangvien.admin.chi-tiet-giang-vien', $this->v);
    }
    public function updateGiangVien($id, Request $request)
    {

        $method_route = 'route_BackEnd_AdminGiangVien_Detail';
        $objGiangVien = new GiangVien();
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
        if ($request->hasFile('hinh_anh_giang_vien') && $request->file('hinh_anh_giang_vien')->isValid()) {
            $params['cols']['hinh_anh_giang_vien'] = $this->uploadFile($request->file('hinh_anh_giang_vien'));
        }
        unset($params['cols']['_token']);

        $objItem = $objGiangVien->loadOne($id);
        if (empty($objItem)) {
            Session::push('errors', 'Không tồn tại người dùng này ' . $id);
            return redirect()->route('route_BackEnd_NguoiDung_index');
        }
        $params['cols']['id'] = $id;
        $res = $objGiangVien->saveUpdate($params);
        $objChuyenMon = DB::table('chuyen_mon_day')->where('id_giang_vien', $id)->delete();
        $arrChuyenMon = [];
        $i = 0;
        foreach ($request->id_khoa_hoc as $item) {
            $arrChuyenMon['id'] = null;
            $arrChuyenMon['id_giang_vien'] = $id;
            $arrChuyenMon['id_khoa_hoc'] = $item;
            $arrChuyenMon['trang_thai'] = 1;
            $result = DB::table('chuyen_mon_day')->insert($arrChuyenMon);
            $i++;
        }
        if ($res == null) // chuyển trang vì trong session đã có sẵn câu thông báo lỗi rồi
        {
            Session::push('post_form_data', $this->v['request']);
            return redirect()->route($method_route, ['id' => $id]);
        } elseif ($res == 1) {
            //            SpxLogUserActivity(Auth::user()->id, 'edit', $primary_table, $id, 'edit');
            $request->session()->forget('post_form_data'); // xóa data post
            Session::flash('success', 'Cập nhật thành công thông tin khoá học!');
            return redirect()->route('route_BackEnd_DanhSachGiangVien_index');
        } else {

            Session::push('errors', 'Lỗi cập nhật cho bản ghi: ' . $res);
            Session::push('post_form_data', $this->v['request']);
            return redirect()->route($method_route, ['id' => $id]);
        }
    }
    public function frThongTinGiangVien(Request $request)
    {
        $this->v['extParams'] = $request->all();
        $objGiangVien = new  GiangVien();
        $this->v['list'] = $objGiangVien->loadListWithPager($this->v['extParams']);
        return view('giangvien.client.fr-giang-vien', $this->v);
    }
    public function frLienHe()
    {
        return view('khoahoc.client.fr-lien-he', $this->v);
    }
}
