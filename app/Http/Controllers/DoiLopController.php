<?php

namespace App\Http\Controllers;

use App\ClassModel;
use App\DangKy;
use App\HocVien;
use App\ThongTinChuyenLop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;

class DoiLopController extends Controller
{
    public function index()
    {
        $lists = ThongTinChuyenLop::all();
        // dd($lists);
        $listClass = ClassModel::all();
        return view('chuyenLop.list', compact('lists', 'listClass'));
    }
    public function doiLop(Request $request,$id, $email, $oldClass, $newClass)
    {
        //update trạng thái thành 1 (đã check)
        $updateThongTinDoiLop = ThongTinChuyenLop::where('id',$id)->first();
        $updateThongTinDoiLop['trang_thai'] = 1;
        $updateThongTinDoiLop->update();
        $hocVien = HocVien::where('email', '=', $email)->first();
        $id_hoc_vien = $hocVien->id;
        $dangKy = DangKy::where('id_hoc_vien', '=', $id_hoc_vien)->where('id_lop_hoc', '=', $oldClass)->first();
        //kiểm tra xem lớp học cũ và lớp muốn chuyển có cùng 1 khóa học Không
        //lớp cũ
        $checkCourseClassOld = ClassModel::where('id', $oldClass)->first()->course;
        //lớp mới
        $checkCourseClassNew = ClassModel::where('id', $newClass)->first()->course;
        // dd($checkCourseClassOld, $checkCourseClassNew);
        if ($checkCourseClassOld->name === $checkCourseClassNew->name) {
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
                    // return 'Chuyển lớp thành công số chỗ của lớp mới đã trừ đi 1';
                    return Redirect::back()->withErrors(['msg' => 'Chuyển lớp thành công số chỗ của lớp mới đã trừ đi 1']);
                } else {
                    return Redirect::back()->withErrors(['msg' => 'Chuyển lớp thành công chờ trường check thanh toán']);
                }
                return Redirect::back()->withErrors(['msg' => 'Chuyển lớp thành công']);
            } else {
                return Redirect::back()->withErrors(['msg' => 'lớp đã đầy không thể chuyển lớp']);
            }
        }
        //nếu khác khóa học thì gọi function doiKhoaHoc()
        return  $this->doiKhoaHoc($dangKy,  $checkCourseClassNew, $newClass, $dangKy,  $oldClass, $hocVien);
    }

    public function doiKhoaHoc($oldDangKy, $newCourse, $idNewClass, $dangKyOld, $oldClass, $hocVien)
    {
        $checkClass = ClassModel::where('id', $idNewClass)->first();
        if ($checkClass->slot > 0) {
            $getPayMentOfOldDangKy = DangKy::where('id', $oldDangKy->id_payment)->first();
            //Số tiền đã nộp
            // dd($getPayMentOfOldDangKy);
            $priceDaNop = $getPayMentOfOldDangKy->gia_tien;
            //cập nhập lại giá cho cái đang kí đấy nếu dư nợ = 0 thì trạng thái = 1 còn có dư nợ thì trạng thái = 0
            //giá tiền của lớp muốn chuyển sang
            $priceClassNew = ClassModel::where('id', $idNewClass)->first()->course->price;
            //lưu thoong tin chuyển lớp mới
            $idClassOld = $dangKyOld['id_lop_hoc'];
            $dangKyOld['id_lop_hoc'] =  $idNewClass;
            $dangKyOld['gia_tien'] =  $priceClassNew;
            $dangKyOld['so_tien_da_dong'] =  $priceDaNop;
            $dangKyOld['du_no'] =  $priceDaNop - $priceClassNew;
            // dd($dangKyOld);
            //nếu có dư nợ
            if ($dangKyOld->du_no != 0) {
                //nếu dư nợ nhỏ hơn 0 thì trạng thái  là 0, cộng slot ở lớp cũ ,gửi mail báo thiếu học phí và link đóng tiền
                if ($dangKyOld->du_no  < 0) {
                    $dangKyOld['trang_thai'] =  0;
                    $dangKyOld->update();
                    // dd($dangKyOld->class->slot);
                    $classOld = ClassModel::whereId($idClassOld)->first();
                    $classOld['slot'] = $classOld->slot + 1;
                    $classOld->update();
                    $classNews= ClassModel::whereId($dangKyOld->id_lop_hoc)->first();
                    // if ($hoc_vien = $addNewStudent) {
                    Mail::send('emailThanhToan', compact('classOld','dangKyOld','classNews'), function ($email) use ($hocVien) {
                        // mail nhận thư, tên người dùng
                        $email->subject("Hệ thống gửi thông tin chuyển lớp đến bạn");
                        $email->to($hocVien->email, $hocVien->name, $hocVien);
                    });
                    // }
                    return "Bạn đã chuyển lớp thành công và nợ   . $dangKyOld->du_no. vui lòng đóng tiền để học";
                //nếu dư nợ lớn hơn 0 thì trạng thái vẫn là 1, cộng slot ở lớp cũ và  trừ 1 slot ở lớp mới
                } elseif ($dangKyOld->du_no  > 0) {
                    //update xong ở bảng đăng kí thì phải +1 vào slot ở course vừa chuyển đi
                    $dangKyOld->update();
                    //cộng 1 slot vào lớp cũ
                    $classOld = ClassModel::whereId($idClassOld)->first();
                    $classOld['slot'] = $classOld->slot + 1;
                    $classOld->update();
                   
                    //trừ 1 slot ở lớp mới (phải lấy lại cái đăng kí mới đã)
                    $classNew = ClassModel::whereId($dangKyOld->id_lop_hoc)->first();
                    $classNew['slot'] = $classNew->slot - 1;
                    $classNew->update();
                    return Redirect::back()->withErrors(['msg' => 'Bạn đã chuyển lớp thành công và thừa ' .$dangKyOld->du_no]);
                }
            }
        }
        // hết slot thì không dc đki
        return Redirect::back()->withErrors(['msg' => 'Lớp này đã đủ sinh viên']);
    }
}
