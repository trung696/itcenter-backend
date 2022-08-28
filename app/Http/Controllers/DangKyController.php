<?php

namespace App\Http\Controllers;

use App\Ca;
use App\ChienDich;
use App\ClassModel;
use App\Course;
use App\DangKy;
use App\HocVien;
use App\Http\Requests\DangKyRequest;
use App\LopHoc;
use App\MaChienDich;
use App\Mail\OrderShipped;
use App\Mail\PaymentCheck;
use App\User;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\DanhMucKhoaHocRequest;
use App\Payment;
use App\ThongTinChuyenLop;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Spipu\Html2Pdf\Html2Pdf;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

class DangKyController extends Controller
{
    private $v;

    public function __construct()
    {
        $this->v = [];
    }
    public function danhSachDangKy(Request $request)
    {
        $this->v['_title'] = 'Danh đăng ký';
        $this->v['routeIndexText'] = 'Danh đăng ký';
        $objDangKy = new DangKy();
        //Nhận dữ liệu lọc từ view
        $this->v['extParams'] = $request->all();
        // dd($request->all());
        $this->v['list'] = $objDangKy->loadListWithPagers($this->v['extParams']);
        return view('dangky.dang-ky', $this->v);
    }
    public function themDangKy(DangKyRequest $request)
    {
        $this->v['routeIndexText'] = 'Đăng ký';
        $this->v['_action'] = 'Add';
        $this->v['_title'] = 'Thêm Đăng Ký';
        $objKhoaHoc = new Course();
        $objLopHoc = new ClassModel();
        if ($request->isMethod('post')) {
            $params = [
                'danhmuc_add' => Auth::user()->id
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
                return redirect()->route('route_BackEnd_DangKyAdmin_Add');
            } elseif (!preg_match("/(84|0[3|5|7|8|9])+([0-9]{8})\b/", $request->so_dien_thoai)) {
                Session::flash('success', 'Số điện thoại không chính xác');
                return redirect()->route('route_BackEnd_DangKyAdmin_Add');
            } elseif (!preg_match("/(1|0+([0-9]{8,11}))\b/", $request->cccd)) {
                Session::flash('success', 'Căn cước công dân không chính xác');
                return redirect()->route('route_BackEnd_DangKyAdmin_Add');
            } else {
                unset($params['cols']['_token']);
                if ($request->hasFile('hinh_anh') && $request->file('hinh_anh')->isValid()) {
                    $params['cols']['hinh_anh'] = $this->uploadFile($request->file('hinh_anh'));
                }


                $objDangKy = new DangKy();
                $objHocVien = new HocVien();
                unset($params['cols']['ma_khuyen_mai']);
                unset($params['cols']['hocphi']);
                $checkEmail = $objHocVien->loadCheckHocVien($request->email);
                if (!isset($checkEmail)) {
                    $resHocVien = $objHocVien->saveNewAdmin($params);
                } else {
                    $checkHV = $objDangKy->loadCheckName($request->id_lop_hoc, $checkEmail->id);
                    if (!isset($checkHV)) {
                        $resHocVien = $checkEmail->id;
                    }
                }
                if (isset($resHocVien)) {
                    $gia = $objKhoaHoc->loadOne($request->id_khoa_hoc);
                    $arrDangKy = [];

                    $arrDangKy['id_lop_hoc'] = $request->id_lop_hoc;
                    //check coupon
                    $ma_khuyen_mai = $request->ma_khuyen_mai;
                    // dd($ma_khuyen_mai);
                    if (isset($ma_khuyen_mai)) {
                        $objCheckMa = new MaChienDich();
                        $checkMa = $objCheckMa->loadCheckName($ma_khuyen_mai);
                        // dd($checkMa);
                        if (isset($checkMa)) {
                            $objChienDich = new ChienDich();
                            $checkGiam = $objChienDich->loadOne($checkMa->id_chien_dich);
                        } else {
                            return Redirect::back()->withErrors(['msg' => 'Không tồn tại mã giảm giá này']);
                        }
                        if ($checkMa->trang_thai == 0) {
                            $trang_thai = 0;
                        } else {
                            $trang_thai = 1;
                        }
                        if ($checkGiam->trang_thai == 0) {
                            $hoat_dong = 0;
                        } else {
                            $hoat_dong = 1;
                        }
                        if ($checkGiam->course_id == 0 || $checkGiam->course_id == $request->id_khoa_hoc) {
                            $dung_khoa = 1;
                        } else {
                            $dung_khoa = 0;
                        }
                        $now = date('Y-m-d');
                        $startDate = date('Y-m-d', strtotime($checkGiam->ngay_bat_dau));
                        $endDate = date('Y-m-d', strtotime($checkGiam->ngay_ket_thuc));
                        if (($now >= $startDate) && ($now <= $endDate)) {
                            $flag = 1;
                        } else {
                            $flag = 2;
                        }
                        if ($flag == 1 && $hoat_dong == 1 && $trang_thai == 0 && $dung_khoa == 1) {
                            $arrDangKy['gia_tien'] = $gia->price - ($gia->price * $checkGiam->phan_tram_giam / 100);

                            $apma = $checkGiam->phan_tram_giam;
                        } elseif ($dung_khoa == 0) {
                            return Redirect::back()->withErrors(['msg' => 'Mã giảm giá không dành cho khóa này'])->withInput();
                        } else {
                            return Redirect::back()->withErrors(['msg' => 'Mã giảm giá không hợp lệ']);
                        }
                    } else {

                        $arrDangKy['gia_tien'] = $gia->price;
                        $apma = 0;
                    }
                    $arrDangKy['id_hoc_vien'] = $resHocVien;
                    $arrDangKy['trang_thai'] = $request->trang_thai;
                    // dd((int)$request->hocphi, $arrDangKy['so_tien_da_dong'], $gia->price);


                    if ($request->trang_thai == 3) {

                        $random = Str::random(10);
                        // dd($random);
                        $arrDangKy['token'] = $random;
                        //thêm payment
                        $objPayment = new Payment();
                        $arrPay = [];
                        $arrPay['payment_method_id'] = 1;
                        $arrPay['price'] = $arrDangKy['gia_tien'];
                        $arrPay['description'] = "Học viên đã đóng đủ học phí trực tiếp";
                        $payment = $objPayment->saveNewAdmin($arrPay);
                        $arrDangKy['id_payment'] = $payment;
                        //end
                        // dd($arrDangKy);
                        $res = $objDangKy->saveNewOnline($arrDangKy);
                        // if ($res) {
                        //     $socho = $objLopHoc->loadOneID($request->id_lop_hoc);
                        //     $updateSoCho = [];
                        //     $updateSoCho['id'] = $request->id_lop_hoc;
                        //     $updateSoCho['so_cho'] = $socho->slot - 1;
                        //     $update = $objLopHoc->saveUpdateSoCho($updateSoCho);
                        // }
                    } else {
                        $arrDangKy['gia_tien'] = $gia->price;
                        $res = $objDangKy->saveNew($arrDangKy);
                    }
                    // dd($arrDangKy);
                    //gửi mail xác nhận
                    $email = $request->email;
                    $objGuiGmail = DB::table('dang_ky', 'tb1')
                        ->select('tb1.id', 'tb1.du_no', 'tb1.gia_tien', 'tb2.ho_ten', 'tb3.name', 'tb4.price', 'tb4.name as course_name', 'tb2.so_dien_thoai', 'tb1.trang_thai', 'tb1.token')
                        ->leftJoin('hoc_vien as tb2', 'tb2.id', '=', 'tb1.id_hoc_vien')
                        ->leftJoin('class as tb3', 'tb3.id', '=', 'tb1.id_lop_hoc')
                        ->leftJoin('course as tb4', 'tb3.course_id', '=', 'tb4.id')
                        ->where('tb1.id', $res)->first();
                    $objGuiGmail->so_dien_thoai = $apma;
                    Mail::to($email)->send(new OrderShipped($objGuiGmail));
                    if (!empty($request->ma_khuyen_mai)) {
                        $objCheckMa = new MaChienDich();
                        $updatett = $objCheckMa->saveUpdateTT($request->ma_khuyen_mai);
                    }
                    $method_route = 'route_BackEnd_DangKyAdmin_Add';
                    if ($res == null) {
                        Session::push('post_form_data', $this->v['request']);
                        return redirect()->route($method_route);
                    } elseif ($res > 0) {
                        $this->v['request'] = [];
                        $request->session()->forget('post_from_data');
                        Session::flash('success', 'Đăng ký thành công');
                        return redirect()->route('route_BackEnd_DanhSachDangKy_index');
                    } else {
                        Session::push('errors', 'Lỗi thêm mới');
                        Session::push('post_form_data', $this->v['request']);
                        return redirect()->route($method_route);
                    }
                } else {
                    Session::flash('success', 'Học viên đã đang ký khoá nay. Không thể đăng ký lại');
                    return redirect()->route('route_BackEnd_DanhSachDangKy_index');
                }
            }
        }
        $this->v['objKhoaHoc'] = $objKhoaHoc->loadListWithPager();
        $this->v['objLopHoc'] = $objLopHoc->loadListWithPager();
        return view('dangky.them-dang-ky', $this->v);
    }
    public function acceptDangKy($id, $token)
    {
        //kiểm tra xem token có trong đăng ký nào ko?
        $objDangKy = new DangKy();
        $objLopHoc = new ClassModel();
        $objHocVien = new HocVien();
        $arrDangKy = [];
        $ttDangKy = $objDangKy->loadOne($id);
        $lopHoc = $objLopHoc->loadOne($ttDangKy->id_lop_hoc);
        if ($ttDangKy->token === $token) {
            $now = date('Y-m-d');
            if ($now > $lopHoc->start_date) {
                dd("đã hết hạn xác nhận");
            } else {
                $ttDangKy->trang_thai = 3;
                $arrDangKy['id'] = $id;
                $arrDangKy['trang_thai'] = 3;
                $res = $objDangKy->updateHocPhi($arrDangKy);

                $objHV = $objHocVien->loadOne($ttDangKy->id_hoc_vien);

                $email = $objHV->email;
                $objGuiGmail = DB::table('dang_ky', 'tb1')
                    ->select('tb1.id', 'tb1.du_no', 'tb1.gia_tien', 'tb2.ho_ten', 'tb3.name', 'tb4.price', 'tb4.name as course_name', 'tb2.so_dien_thoai', 'tb1.trang_thai', 'tb1.token', 'tb1.so_tien_da_dong')
                    ->leftJoin('hoc_vien as tb2', 'tb2.id', '=', 'tb1.id_hoc_vien')
                    ->leftJoin('class as tb3', 'tb3.id', '=', 'tb1.id_lop_hoc')
                    ->leftJoin('course as tb4', 'tb3.course_id', '=', 'tb4.id')
                    ->where('tb1.id', $id)->first();
                $price = (int)$objGuiGmail->price;
                $gia = (int)$objGuiGmail->gia_tien;
                $giam_gia = (($price - $gia) / $price) * 100;
                // dd($objGuiGmail);
                $objGuiGmail->so_dien_thoai = $giam_gia;
                //trừ chỗ
                $socho = $objLopHoc->loadOneID($lopHoc->id);
                $updateSoCho = [];
                $updateSoCho['id'] = $lopHoc->id;
                $updateSoCho['so_cho'] = $socho->slot - 1;
                $update = $objLopHoc->saveUpdateSoCho($updateSoCho);
                Mail::to($email)->send(new PaymentCheck($objGuiGmail));
                return view('dangky.thong-bao');
            }
        } else
            dd('Đường dẫn không hợp lệ');
        // ktra token còn hạn
        //còn hạn -> cập nhật trạng thái

    }
    private function uploadFile($file)
    {
        $fileName = time() . '_' . $file->getClientOriginalName();
        return $file->storeAs('hinh_anh_hoc_vien', $fileName, 'public');
    }
    public function getListLop($course_id)
    {
        $now = date('Y-m-d');
        $list_lop_hoc = DB::table('class')->select('id', 'name', 'course_id')
            ->where('course_id', '=', $course_id)
            ->where('start_date', '>', $now)
            ->orderBy('name', 'ASC')->get();
        return response()->json($list_lop_hoc, 200);
    }
    public function chiTietDangKy($id, Request $request)
    {
        $now = date('Y-m-d');
        $this->v['routeIndexText'] = 'Chi tiết đăng ký';
        $this->v['_action'] = 'Edit';
        $this->v['_title'] = 'Chi tiết đăng ký';
        $this->v['trang_thai'] = config('app.status_dang_ky');

        $objDangKy = new DangKy();
        $itemDK = $objDangKy->loadOne($id);
        $this->v['itemDK'] = $itemDK->id_lop_hoc;
        $this->v['itemTT'] = $itemDK->trang_thai;
        $this->v['itemDKTT'] = $itemDK->trang_thai;
        $this->v['itemGia'] = $itemDK->gia_tien;
        $objHocVien = new  HocVien();
        $this->v['itemHV'] = $objHocVien->loadOne($itemDK->id_hoc_vien);
        $this->v['checkIssetPayment'] = DangKy::where('id', $id)->first()->id_payment;
        $this->v['trangThai'] = DangKy::where('id', $id)->first()->trang_thai;
        $this->v['dki'] = DangKy::where('id', $id)->first();
        $objLopHoc = new ClassModel();
        $itemLH = $objLopHoc->loadOne($this->v['itemDK']);
        $objKhoaHoc = new Course();
        $this->v['itemKH'] = $objKhoaHoc->loadOne($itemLH->course_id);
        $list_lop_hoc = DB::table('class')->select('id', 'name')
            ->where('course_id', '=', $itemLH->course_id)
            ->where('start_date', '>', $now)->get();
        $this->v['listLH'] = $list_lop_hoc;
        $listClass = ClassModel::all();
        $listCourse = Course::all();
        $getDuNo = DangKy::whereId($id)->first()->du_no;
        // dd($this->v);
        return view('dangky.sua-thong-tin', $this->v, compact('listClass', 'getDuNo', 'listCourse'));
    }


    public function update(Request $request, $id, $email, $oldClass)
    {
        // dd($request->all(), $id, $email, $oldClass);
        //trường hợp đóng tiền(đăng kí nhưng chưa thanh toán)
        if (isset($request->dong_hoc_phi)) {
            return $this->dongHocPhi($request, $id, $email);
        }
        //nếu nộp thêm tiền thì gọi function updateDongThemTien
        elseif (isset($request->dong_them)) {
            return $this->updateDongThemTien($request, $id, $email);
        }
        $hocVien = HocVien::where('email', '=', $email)->first();
        $id_hoc_vien = $hocVien->id;
        $dangKy = DangKy::where('id_hoc_vien', '=', $id_hoc_vien)->where('id_lop_hoc', '=', $oldClass)->first();
        //lớp cũ
        $checkCourseClassOld = ClassModel::where('id', $oldClass)->first()->course;
        //lớp mới
        $checkCourseClassNew = ClassModel::where('id', $request->id_lop_hoc_moi)->first()->course;
        //kiểm tra xem lớp học cũ và lớp muốn chuyển có cùng 1 khóa học Không
        if ($checkCourseClassOld->name === $checkCourseClassNew->name) {
            $checkClass = ClassModel::where('id', $request->id_lop_hoc_moi)->first();
            //check con slot khong
            if ($checkClass->slot > 0) {
                $dangKyOld = DangKy::where('id', $dangKy->id)->first();
                $updateDangKy =  $dangKy->update([
                    'id_lop_hoc' => $request->id_lop_hoc_moi,
                ]);

                $dangKyBeforeUpdate = DangKy::where('id', $dangKy->id)->first();
                //nếu trạng thái là đã thanh toán khi chuyển đi rồi thì phải cộng thêm 1 slot
                if ($dangKyBeforeUpdate->trang_thai == 1) {
                    if ($updateDangKy) {
                        ClassModel::whereId($dangKyOld->class->id)->update([
                            'slot' =>  $dangKyOld->class->slot + 1
                        ]);
                    }
                }
                $listClass = ClassModel::all();
                $ca = Ca::all();
                //check chỗ lớp mới chuyển sang và trừ đi 1 slot
                $dangKyAfterUpdate = DangKy::where('id', $dangKy->id)->first();
                if ($dangKyAfterUpdate->trang_thai == 1) {
                    $classOfChuyenLop = $dangKyAfterUpdate->class;
                    ClassModel::whereId($classOfChuyenLop->id)->update([
                        'slot' =>  $classOfChuyenLop->slot - 1
                    ]);
                    Mail::send('emailThongBaoChuyenLop', compact('classOfChuyenLop', 'ca'), function ($email) use ($hocVien) {
                        // mail nhận thư, tên người dùng
                        $email->subject("Hệ thống thông báo chuyển lớp thành công đến bạn");
                        $email->to($hocVien->email, $hocVien->ho_ten);
                    });
                    // return 'Chuyển lớp thành công số chỗ của lớp mới đã trừ đi 1';
                    return Redirect::back()->withErrors(['msg' => 'Chuyển lớp thành công (đã thanh toán) ']);
                } else {
                    return Redirect::back()->withErrors(['msg' => 'Chuyển lớp thành công (chưa thanh toán)']);
                }
                return Redirect::back()->withErrors(['msg' => 'Chuyển lớp thành công']);
            } else {
                return Redirect::back()->withErrors(['msg' => 'Lớp đã đầy không thể chuyển lớp']);
            }
        }
        //nếu khác khóa học thì gọi function doiKhoaHoc()
        return  $this->doiKhoaHoc($request, $dangKy,  $checkCourseClassNew, $request->id_lop_hoc_moi, $dangKy,  $oldClass);
    }

    public function updateDongThemTien($request, $id, $email)
    {
        // dd($request, $id,$email);
        $dkiEdit = DangKy::where('id', $id)->first();
        if (abs($dkiEdit->du_no) == $request->dong_them) {
            // try {
            //     DB::beginTransaction();
            $soTienDaDongThem = $request->dong_them;
            $dkiEdit['trang_thai'] = 1;
            $dkiEdit['so_tien_da_dong'] = null;
            $dkiEdit['du_no'] = 0;
            $dkiEdit->update();

            $payMentUpdate = Payment::where('id', $dkiEdit->id_payment)->first();
            $payMentUpdate['payment_date'] = date("Y-m-d H:i:s");
            $payMentUpdate['price'] = $payMentUpdate->price + $request->dong_them;
            $payMentUpdate['description'] =  "$payMentUpdate->description (đóng thêm ) ";
            $payMentUpdate->update();

            $classOld = ClassModel::whereId($dkiEdit->id_lop_hoc)->first();
            $classOld['slot'] = $classOld->slot - 1;
            $classOld->update();

            DB::commit();

            $hoc_vien = HocVien::where('id', $dkiEdit->id_hoc_vien)->first();
            Mail::send('emailThongBaoDongThemThanhCong', compact('soTienDaDongThem', 'classOld'), function ($email) use ($hoc_vien) {
                $email->subject("Hệ thống gửi thông báo bạn đã đóng số tiền còn thiếu");
                $email->to($hoc_vien->email, $hoc_vien->name, $hoc_vien);
            });
            return Redirect::back()->withErrors(['msg' => 'Nộp tiền thành công']);
            // } catch (\Exception $exception) {
            //     DB::rollback();
            //     Log::error('message: ' . $exception->getMessage() . 'line:' . $exception->getLine());
            // }
        } else {
            return Redirect::back()->withErrors(['msg' => 'Nộp tiền thừa hoặc thiếu so với số tiền phải đóng']);
        }
    }

    public function dongHocPhi($request, $id, $email)
    {
        // dd($request->all(), $id, $email);
        // try {
        $dkiEdit = DangKy::where('id', $id)->first();
        if ($request->dong_hoc_phi != $dkiEdit->gia_tien) {
            return Redirect::back()->withErrors(['msg' => 'Vui lòng nhập đúng số tiền là :' . $dkiEdit->gia_tien]);
        } else {
            // DB::beginTransaction();
            $dangKy = DangKy::where('id', $id)->first();
            // kiểm tra xem lớp đấy còn slot không và đã khai giảng chưa
            $checkClass = ClassModel::where('id', $dangKy->id_lop_hoc)->first();
            // kiểm tra xem lớp đấy còn slot không và đã khai giảng chưa
            if ($checkClass->start_date > date('Y-m-d')  && $checkClass->slot > 0) {
                $hocVienDangKi = HocVien::where('id', $dangKy->id_hoc_vien)->first();

                $soTienDaDongThem = $request->dong_hoc_phi;
                // tạo payment phương thức = 1 là đsong tiền trực tiếp ở trường
                $createPayment = Payment::create([
                    'payment_method_id' => 1,
                    'payment_date' => date("Y-m-d h:i:s"),
                    'price' => $request->dong_hoc_phi,
                    'description' => $hocVienDangKi->ho_ten . ' đóng học phí trực tiếp tại trường',
                    'status' => 1,
                ]);

                //cập nhập lại đăng kí
                $dangKy['trang_thai'] = 1;
                $dangKy['id_payment'] = $createPayment->id;
                $dangKy['paid_date'] = date("Y-m-d H:i:s");;
                $dangKy->update();

                //thành công rồi thì trừ slot của lớp đi 
                $checkClass['slot'] = $checkClass->slot - 1;
                $checkClass->update();
                $classOld = ClassModel::where('id', $dangKy->id_lop_hoc)->first();
                Mail::send('emailThongBaoDongThemThanhCong', compact('soTienDaDongThem', 'classOld'), function ($email) use ($hocVienDangKi) {
                    $email->subject("Hệ thống gửi thông báo bạn đã đóng số tiền còn thiếu");
                    $email->to($hocVienDangKi->email, $hocVienDangKi->name, $hocVienDangKi);
                });

                return Redirect::back()->withErrors(['msg' => 'Cập nhập thành công']);
            } else {
                return Redirect::back()->withErrors(['msg' => 'Lớp này đã khai giảng rồi hoặc đã hết slot']);
            }
        }
        // } catch (\Exception $exception) {
        //     DB::rollback();
        //     Log::error('message: ' . $exception->getMessage() . 'line:' . $exception->getLine());
        // }
    }

    public function doiKhoaHoc($request, $newDangKy, $newCourse, $idNewClass, $dangKyOld, $oldClass)
    {
        /// check xem số tiền nộp thêm == abs(du_no)
        if (isset($request->dong_them) &&  $request->dong_them != 0  && abs($newDangKy->du_no) == $request->dong_them) {
            try {
                // dd('đóng thêm tiền');
                DB::beginTransaction();
                // dd($newDangKy->id_payment);
                $payMentOfDangKy = Payment::where('id', $newDangKy->id_payment)->first();
                $payMentOfDangKy['price'] = $payMentOfDangKy['price'] + $request->dong_them;
                $payMentOfDangKy['description'] = 'Sinh viên đóng thêm';
                $payMentOfDangKy->update();
                //cập nhật bảng dang_ky
                $newDangKy['trang_thai'] = 1;
                $newDangKy['paid_date'] = date("Y-m-d");
                $newDangKy['so_tien_da_dong'] = null;
                $newDangKy['du_no'] = 0;
                $newDangKy->update();

                //cập nhập lại slot trong lớp
                $classOfDangKy = ClassModel::whereId($newDangKy->id_lop_hoc)->first();
                $classOfDangKy['slot'] = $classOfDangKy->slot - 1;
                $classOfDangKy->update();
                DB::commit();
                // $hoc_vien = HocVien::where('id',$newDangKy->id_hoc_vien)->first();
                // Mail::send('emailThongBaoDongThemThanhCong', compact('payMentOfDangKy','newDangKy'), function ($email) use ($hoc_vien) {
                //     $email->subject("Hệ thống gửi thông báo bạn đã đóng số tiền còn thiếu");
                //     $email->to($hoc_vien->email, $hoc_vien->name, $hoc_vien);
                // });
                return Redirect::back()->withErrors(['msg' => 'Chuyển lớp thành công ']);
            } catch (\Exception $exception) {
                DB::rollback();
                Log::error('message: ' . $exception->getMessage() . 'line:' . $exception->getLine());
            }
        } else {
            // chuyển lớp sang khóa đắt tiền hơn
            $checkClass = ClassModel::where('id', $idNewClass)->first();
            if ($checkClass->slot > 0) {
                $getPayMentOfOldDangKy = DangKy::where('id', $dangKyOld->id)->first();
                //Số tiền đã nộp
                // $priceDaNop = $getPayMentOfOldDangKy->gia_tien;
                $priceDaNop = ClassModel::where('id', $getPayMentOfOldDangKy->id_lop_hoc)->first()->course->price;
                //cập nhập lại giá cho cái đang kí đấy nếu dư nợ = 0 thì trạng thái = 1 còn có dư nợ thì trạng thái = 0
                //giá tiền của lớp muốn chuyển sang
                $priceClassNew = ClassModel::where('id', $idNewClass)->first()->course->price;
                //lưu thoong tin chuyển lớp mới
                $idClassOld = $dangKyOld['id_lop_hoc'];
                $dangKyOld['id_lop_hoc'] =  $idNewClass;
                $dangKyOld['gia_tien'] =  $priceClassNew;
                $dangKyOld['so_tien_da_dong'] =  $priceDaNop;
                $dangKyOld['du_no'] =  $priceDaNop - $priceClassNew;
                //nếu có dư nợ
                if ($dangKyOld->du_no != 0) {
                    //nếu dư nợ nhỏ hơn 0 thì trạng thái  là 0, cộng slot ở lớp cũ
                    if ($dangKyOld->du_no  < 0) {
                        $dangKyOld['trang_thai'] =  0;
                        $dangKyOld->update();
                        // dd($dangKyOld->class->slot);
                        $classOld = ClassModel::whereId($idClassOld)->first();
                        $classOld['slot'] = $classOld->slot + 1;
                        $classOld->update();
                        $classNew = ClassModel::whereId($dangKyOld->id_lop_hoc)->first();
                        $hoc_vien = HocVien::where('id', $getPayMentOfOldDangKy->id_hoc_vien)->first();

                        Mail::send('emailChuyenLop', compact('classOld', 'classNew', 'dangKyOld'), function ($email) use ($hoc_vien) {
                            $email->subject("Hệ thống gửi thông báo bạn đã chuyển lớp học");
                            $email->to($hoc_vien->email, $hoc_vien->name, $hoc_vien);
                        });
                        return Redirect::back()->withErrors(['msg' => "Bạn đã chuyển lớp thành công và nợ   . $dangKyOld->du_no. vui lòng đóng tiền để họ"]);
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
                        $hoc_vien = HocVien::where('id', $getPayMentOfOldDangKy->id_hoc_vien)->first();
                        Mail::send('emailChuyenLop', compact('classOld', 'classNew', 'dangKyOld'), function ($email) use ($hoc_vien) {
                            $email->subject("Hệ thống gửi thông báo bạn đã chuyển lớp học");
                            $email->to($hoc_vien->email, $hoc_vien->name, $hoc_vien);
                        });
                        return Redirect::back()->withErrors(['msg' => 'Bạn đã chuyển lớp thành công và thừa ' . $dangKyOld->du_no]);
                    }
                }
            }
            // hết slot thì không dc đki
            return Redirect::back()->withErrors(['msg' => 'Lớp này đã đủ sinh viên không thể đăng kí']);
        }
    }

    // trường hợp chuyển lớp thiếu tiền sau đó đến nộp trực tiếp thì sẽ vào hàm này


    // public function updateDangKy($id, Request $request)
    // {
    //     $now = date('Y-m-d');
    //     $objDangKy = new DangKy();
    //     $dangKy = $objDangKy->loadOne($id);
    //     $objLopHoc = new ClassModel();
    //     $lopHoc = $objLopHoc->loadOne($dangKy->id_lop_hoc);
    //     if ($dangKy->trang_thai == 1) {
    //         Session::flash('success', 'Đăng Ký Này Đã Thanh Toán Không Thể Thay Đổi');
    //         return redirect()->route('route_BackEnd_AdminDangKy_Detail', ['id' => $id]);
    //     } elseif ($lopHoc->start_date < $now) {
    //         Session::flash('success', 'Lớp Học Đã Khai Giảng Không Thay đổi');
    //         return redirect()->route('route_BackEnd_AdminDangKy_Detail', ['id' => $id]);
    //     } else {
    //         $arrDangKy = [];
    //         $arrDangKy['id'] = $id;
    //         $arrDangKy['id_lop_hoc'] = $request->id_lop_hoc;
    //         $arrDangKy['trang_thai'] = $request->trang_thai;
    //         $res = $objDangKy->updateDangKy($arrDangKy);
    //         if ($request->trang_thai == 1) {
    //             $objGuiGmail = DB::table('dang_ky', 'tb1')
    //                 ->select('tb1.id', 'tb1.gia_tien', 'tb2.ho_ten', 'tb2.email', 'tb3.name', 'tb4.price', 'tb4.ten_khoa_hoc', 'tb2.so_dien_thoai', 'tb1.trang_thai')
    //                 ->leftJoin('hoc_vien as tb2', 'tb2.id', '=', 'tb1.id_hoc_vien')
    //                 ->leftJoin('class as tb3', 'tb3.id', '=', 'tb1.id_lop_hoc')
    //                 ->leftJoin('khoa_hoc as tb4', 'tb3.course_id', '=', 'tb4.id')
    //                 ->where('tb1.id', $id)->first();
    //             $email = $objGuiGmail->email;
    //             // Mail::to($email)->send(new OrderShipped($objGuiGmail));
    //             $objLopHoc = new  ClassModel();
    //             $socho = $objLopHoc->loadOneID($request->id_lop_hoc);
    //             $udateSoCho = [];
    //             $udateSoCho['id'] = $request->id_lop_hoc;
    //             $udateSoCho['so_cho'] = $socho->slot - 1;
    //             $update = $objLopHoc->saveUpdateSoCho($udateSoCho);
    //         }
    //         if ($res == null) // chuyển trang vì trong session đã có sẵn câu thông báo lỗi rồi
    //         {
    //             return redirect()->route('route_BackEnd_AdminDangKy_Detail', ['id' => $id]);
    //         } elseif ($res == 1) {
    //             $request->session()->forget('post_form_data'); // xóa data post
    //             Session::flash('success', 'Cập nhật thành công!');

    //             return redirect()->route('route_BackEnd_AdminDangKy_Detail', ['id' => $id]);
    //         } else {

    //             Session::push('errors', 'Lỗi cập nhật cho bản ghi: ' . $res);
    //             Session::push('post_form_data', $this->v['request']);
    //             return redirect()->route('route_BackEnd_AdminDangKy_Detail', ['id' => $id]);
    //         }
    //     }
    // }
    public function inHoaDon($id, Request $request)
    {
        $emails = DB::table('dang_ky', 'tb1')
            ->select('tb1.id', 'tb1.gia_tien', 'tb2.ho_ten', 'tb3.name', 'tb4.price', 'tb4.name as course_name', 'tb2.so_dien_thoai', 'tb1.trang_thai')
            ->leftJoin('hoc_vien as tb2', 'tb2.id', '=', 'tb1.id_hoc_vien')
            ->leftJoin('class as tb3', 'tb3.id', '=', 'tb1.id_lop_hoc')
            ->leftJoin('course as tb4', 'tb3.course_id', '=', 'tb4.id')
            ->where('tb1.id', $id)->first();
        // dd($emails);
        $pdf = PDF::setOptions([
            'logOutputFile' => storage_path('logs/log.htm'),
            'tempDir' => storage_path('logs/')
        ])
            ->loadView('print.inhoadon', compact('emails'))->setPaper('a4');
        return $pdf->stream();
    }
}
