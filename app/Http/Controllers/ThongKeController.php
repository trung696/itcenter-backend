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
    public function thongKeCung()
    {
        //thống kê số lớp active
        $now = date('Y-m-d');
        $objClass = new ClassModel();
        $objCourse = new Course();
        $objHS = new HocVien();
        $objTeacher = new User();
        $objPayment = new Payment();
        $activeCourse = $objCourse->loadListIdAndName(['status', 1])->count(); //số khóa học đang hoạt động
        $activeHS = $objHS->loadCountHV(); //tổng số học sinh
        //tổng số giảng viên
        $teacher = $objTeacher->loadActive();
        //số giảng viên đang có lớp
        $teacherInClass = $objTeacher->loadInClass();
        //tổng học phí đã thu
        $tong_hoc_phi = $objPayment->sumPay();

        //THỐNG KÊ MỀM
        $bat_dau = "2022/08/23 - 2022/08/27";
        $time = explode(
            ' - ',
            $bat_dau
        );
        $a = $objPayment->loadpayDay($time);
        $b = $objTeacher->loadDay($time);
        dd($b);


        return view('thongke', $this->v);
    }
}
