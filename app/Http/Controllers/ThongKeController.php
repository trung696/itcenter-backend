<?php

namespace App\Http\Controllers;

use App\ClassModel;
use App\User;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\DanhMucKhoaHocRequest;
use App\Http\Requests\CourseCateGoryRequest;
// use Illuminate\Support\Facades\App\CourseCategory;
use App\CourseCategory;
use App\Course;
use App\HocVien;
use App\Payment;
use App\Thongke;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Spipu\Html2Pdf\Html2Pdf;
use Dompdf\Dompdf;
use Carbon\Carbon;
use Dompdf\Options;


class ThongKeController extends Controller
{
    private $v;

    public function __construct()
    {
        $this->v = [];
    }
    public function thongKeCung(Request $request)
    {
        //thống kê số lớp active
        $now = date('Y-m-d');
        $objClass = new ClassModel();
        $objCourse = new Course();
        $objHS = new HocVien();
        $objTeacher = new User();
        $objPayment = new Payment();
        // dd($request->search_ngay_khai_giang);
        //số khóa học đang hoạt động
        $activeCourse = $objCourse->loadListIdAndName(['status', 1])->count();
        $this->v['khoahoc_danghoatdong'] = $activeCourse;
        // số lớp đang học
        $lopdanghoc = $objClass->loadActiveClass()->count();
        $this->v['lop_dang_hoc'] = $lopdanghoc;
        //tổng số lớp
        $lophoc = $objClass->loadListIdAndName();
        $this->v['lop_hoc'] = $lophoc->count();
        //tổng số học sinh
        $activeHS = $objHS->loadCountHV();
        $this->v['tong_so_hoc_vien'] = $activeHS;
        //số học viên đang học:
        $hvdanghoc = $objClass->loadActiveHvien()->count();
        $this->v['hoc_vien_dang_hoc'] = $hvdanghoc;
        //tổng số giảng viên
        $teacher = $objTeacher->loadActive()->count();
        $this->v['tong_so_giang_vien'] = $teacher;
        //số giảng viên đang có lớp
        $teacherInClass = $objTeacher->loadInClass()->count();
        $this->v['so_giang_vien_dang_trong_lop'] = $teacherInClass;
        // dd($teacherInClass);
        //tổng học phí
        $hoc_phi = $objPayment->sumAllPay();
        $this->v['hoc_phi'] = number_format($hoc_phi);
        // dd($hoc_phi);
        //tổng học phí đã thu
        $tong_hoc_phi = $objPayment->sumPay();
        $this->v['tong_hoc_phi'] = number_format($tong_hoc_phi);

        // *************************************************************************************************************************
        //THỐNG KÊ MỀM
        if (isset($request->search_ngay)) {


            $input = $request->search_ngay;
            $explo = explode(
                ' - ',
                $input
            );
            $time[0] = Carbon::createFromFormat('d/m/Y', $explo[0])->format('Y/m/d');
            $time[1] = Carbon::createFromFormat('d/m/Y', $explo[1])->format('Y/m/d');
            //số học phí đã thu
            $a = $objPayment->loadpayDay($time);
            $this->v['so_hoc_phi'] = number_format($a);
            //tổng học phí
            $timehocphi = $objPayment->loadAllPayDay($time);
            $this->v['so_hoc_phi_all'] = number_format($timehocphi);
            //giảng viên đã dạy trong quãng thời gian
            $b = $objTeacher->loadDay($time);
            // số học sinh đã đăng kí lớp trong tgian đó
            $c = $objPayment->loadstd($time)->count();
            $this->v['hs_dk_moi'] = $c;
            // dd($b, $c);
            $batdau = Carbon::createFromFormat('Y/m/d', $time[0]);
            $ketthuc = Carbon::createFromFormat('Y/m/d', $time[1]);
            $i = -1;
            $loparr = [];
            foreach ($b as $key => $item) {
                $x = $item->start_date;
                $y = $item->end_date;
                $lopBD = Carbon::createFromFormat('Y-m-d', $x);
                $lopKT = Carbon::createFromFormat('Y-m-d', $y);
                if ($batdau->gt($lopBD) && $lopKT->gt($batdau)) {
                    $i++;
                    $loparr[$i] = $item;
                } elseif ($lopBD->gt($batdau) && $ketthuc->gt($lopBD)) {
                    $i++;
                    $loparr[$i] = $item;
                };
            }
            // echo ('<pre>');
            // var_dump($loparr);
        }

        return view('thongke', $this->v);
    }
    public function thongke()
    {
        return view('thongke.index');
    }
}
