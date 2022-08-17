<?php

namespace App\Http\Controllers;

use App\ClassModel;
use App\DangKy;
use App\HocVien;
use App\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

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


    //trường hợp hoàn trả học phí cho sinh viên chuyển lớp nhưng thừa tiền
    public function hoanTienDu($id)
    {
        $itemEdit = DangKy::where('id', $id)->first();
        if ($itemEdit->trang_thai == 1) {
            try {
                DB::beginTransaction();
                $payMentEdit = Payment::where('id', $itemEdit->id_payment)->first();
                $payMentEdit['price'] = $payMentEdit->price - $itemEdit->du_no;
                $payMentEdit->update();
                //sau đó cập nhập bảng dki
                $itemEdit['so_tien_da_dong']  = null;
                $itemEdit['du_no']  = 0;
                //trạng thái = 1 là đăng kí 
                $itemEdit['trang_thai']  = 1;
                $itemEdit->update();

                //lưu thông tin vào 1 bảng mới (suy nghĩ thêm)
                DB::commit();
                return redirect()->back()->with('msg', 'Đã hoàn trả cho sinh viên số tiền thừa');
            } catch (\Exception $exception) {
                DB::rollback();
                Log::error('message: ' . $exception->getMessage() . 'line:' . $exception->getLine());
            }
        }
    }


    //trường hợp hoàn trả học phí cho sinh viên không đóng đủ học phí khi chuyển lớp
    public function edit($id)
    {
        $itemEdit = DangKy::where('id', $id)->first();
        if ($itemEdit->trang_thai == 0) {
            try {
                DB::beginTransaction();
                $payMentEdit = Payment::where('id', $itemEdit->id_payment)->first();
                // treạng thái = 2 là đã hoàn tiền
                $payMentEdit['status'] = 2;
                $payMentEdit['description']  = "Hoàn tiền cho sinh viên do không nộp đủ tiền cho khóa học mới ";
                $payMentEdit->update();

                //sau đó cập nhập bảng dki
                $itemEdit['so_tien_da_dong']  = null;
                $itemEdit['du_no']  = 0;
                //trạng thái = 2 là hoàn trả tiền khi khóa học đã bắt dầu
                $itemEdit['trang_thai']  = 2;
                $itemEdit->update();
                //lưu thông tin vào 1 bảng mới (suy nghĩ thêm)
                DB::commit();

                return Redirect::back()->withErrors(['msgs' => 'Đã hoàn trả tiền cho sinh viên (chuyển khóa nhưng không nộp đủ học phí)']);
            } catch (\Exception $exception) {
                DB::rollback();
                Log::error('message: ' . $exception->getMessage() . 'line:' . $exception->getLine());
            }
        }
    }

    public function search(Request $request){
        $result = HocVien::where('email', 'LIKE', '%'. $request->email. '%')->get();
        foreach ($result as $resultItem){
            $dangKyThuaTienOfHocVien = DangKy::where('id_hoc_vien',$resultItem->id)->where('du_no','<',0)->get();
        }
        // echo '<pre>';
        //     print($dangKyThuaTienOfHocVien);
        // return redirect('hoanTien')->with(compact('dangKyThuaTienOfHocVien'));
        return redirect()->route('route_BackEnd_list_hoan_tien')->with( ['searchs' => $dangKyThuaTienOfHocVien] );

        // return  view('hoanTien.index', compact('dangKyThuaTienOfHocVien'));

    }
    
}
