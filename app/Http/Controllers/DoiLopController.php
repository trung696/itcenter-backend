<?php

namespace App\Http\Controllers;

use App\ClassModel;
use App\DangKy;
use App\HocVien;
use App\ThongTinChuyenLop;
use Illuminate\Http\Request;

class DoiLopController extends Controller
{
    public function index()
    {
        $lists = ThongTinChuyenLop::all();
        // dd($lists);
        $listClass = ClassModel::all();
        return view('chuyenLop.list', compact('lists','listClass'));
    }
    public function doiLop(Request $request, $email, $oldClass, $newClass)
    {
        $hocVien = HocVien::where('email', '=', $email)->first();
        $id_hoc_vien = $hocVien->id;
        $dangKy = DangKy::where('id_hoc_vien', '=', $id_hoc_vien)->where('id_lop_hoc', '=', $oldClass)->first();
        $checkClass = ClassModel::where('id', $newClass)->first();
        //check con slot khong
        if ($checkClass->slot > 0) {
            $dangKyOld = DangKy::where('id', $dangKy->id)->first();
            $updateDangKy =  $dangKy->update([
                'id_lop_hoc' => $newClass,
            ]);
            $dangKyAfterUpdate = DangKy::where('id', $dangKy->id)->first();
            // dd($dangKyAfterUpdate);
            //nếu trạng thái là đã thanh toán khi chuyển đi rồi thì phải cộng thêm 1 slot
            if ($dangKyAfterUpdate->trang_thai == 1) {
                if ($updateDangKy) {
                    ClassModel::whereId($dangKyOld->class->id)->update([
                        'slot' =>  $dangKyOld->class->slot + 1
                    ]);
                }
            }
            //check chỗ lớp mới chuyển sang và trừ đi 1 slot
            $dangKyAfterUpdate = DangKy::where('id', $dangKy->id)->first();
            if ($dangKyAfterUpdate->trang_thai == 1) {
                $classOfChuyenLop = $dangKyAfterUpdate->class;
                ClassModel::whereId($classOfChuyenLop->id)->update([
                    'slot' =>  $classOfChuyenLop->slot - 1
                ]);
                return 'Chuyển lớp thành công số chỗ của lớp mới đã trừ đi 1';
            } else {
                'Chuyển lớp thành công chờ trường check thanh toán';
            }
            return 'Chuyển lớp thành công';
        
        } else {
            'lớp đã đầy không thể chuyển lớp';
        }
    }
}
