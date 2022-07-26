<?php

namespace App\Http\Controllers;

use App\ChienDich;
use App\HocVien;
use App\MaChienDich;
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
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Spipu\Html2Pdf\Html2Pdf;
use Dompdf\Dompdf;
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
        $this->v['list'] = $objDanhSachHocVien->loadListWithPager($this->v['extParams']);
        // dd($objDanhSachHocVien->loadListWithPager($this->v['extParams']));
        $danhSachGuiGmai[] =  $this->v['list'];
        foreach ($danhSachGuiGmai as $value) {
            $emailGui[] = $value->items();
        }

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
                        // Session::flash('success', 'Mã khuyến mại bị thiếu ' . $thieu . ' mã vui lòng tạo thêm mã');
                        return redirect()->route('route_BackEnd_DanhSachHocVien_index');
                    }
                }
            }
        }
        return view('hocvien.danh-sach-hoc-vien', $this->v);
    }
}
