<?php

namespace App\Http\Controllers;

use App\ClassModel;
use App\DangKy;
use App\HocVien;
use Illuminate\Http\Request;

class HoanTienController extends Controller
{
    public function index()
    {
        $listDangKyThuaTienKhiChuyenLop = DangKy::where('du_no', '>', 0)->get();
        $listHocVien = HocVien::all();
        // hoàn tiền lại cho sinh viên không nộp nốt học phí khi lớp đó đã khai giảng
        $listClassDaKhaiGiang = ClassModel::where('start_date', '<', date('Y-m-d'))->get();
        $listDangKyThuaTien = array();
        foreach ($listClassDaKhaiGiang as $listClassDaKhaiGiangItem) {
            $dangKiOfListClassDaKhaiGiang = $listClassDaKhaiGiangItem->dangKi;
            foreach ($dangKiOfListClassDaKhaiGiang as $dangKiOfListClassDaKhaiGiangItem) {
                if ($dangKiOfListClassDaKhaiGiangItem->du_no < 0) {
                    // gán những đăng kí < 0 vào mảng listDangKyThuaTien
                    $listDangKyThuaTien[] = $dangKiOfListClassDaKhaiGiangItem;
                }
            }
        }
        return view('hoanTien.index', compact('listDangKyThuaTienKhiChuyenLop', 'listHocVien', 'listDangKyThuaTien'));
    }
}
