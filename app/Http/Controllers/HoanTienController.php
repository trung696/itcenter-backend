<?php

namespace App\Http\Controllers;

use App\DangKy;
use App\HocVien;
use Illuminate\Http\Request;

class HoanTienController extends Controller
{
    public function index()
    {
        $data = '';
        $listDangKyThuaTien = DangKy::where('du_no', '>', 0)->get();
        $listHocVien = HocVien::all();
        // dd($listHocVien);
        return view('hoanTien.index', compact('listDangKyThuaTien','listHocVien'));
    }
}
