<?php

namespace App\Http\Controllers\Api;

use App\Ca;
use App\ChienDich;
use App\ClassModel;
use App\Course;
use App\DangKy;
use App\HocVien;
use App\Http\Controllers\Controller;
use App\MaChienDich;
use App\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class ApiRegisterClassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $checkIssetEmail = HocVien::where('email', $request->email)->first();
        $checkIssetPhone = HocVien::where('so_dien_thoai', $request->so_dien_thoai)->first();
        //hoc vien da ton tai tren db
        if ($checkIssetEmail != null || $checkIssetPhone != null) {
            $infoHocVien = '';
            if ($checkIssetEmail == null) {
                $infoHocVien = $checkIssetPhone;
            } elseif ($checkIssetPhone == null) {
                $infoHocVien = $checkIssetEmail;
            } elseif ($checkIssetEmail != null && $checkIssetPhone != null) {
                $infoHocVien = $checkIssetEmail;
            }
            //kiểm tra xem đã đăng kí lớp học Chưa
            if (isset($infoHocVien->id) && isset($request->id_lop_hoc)) {
                //đã đang kí thì thông báo là đăng kí rồi
                $checkDk = DangKy::where('id_lop_hoc', '=', $request->id_lop_hoc)->where('id_hoc_vien', '=', $infoHocVien->id)->first();
                if ($checkDk) {
                    return response()->json([
                        'status' => true,
                        'heading' => "Bạn đã đăng kí lớp học này rồi"
                    ], 400);
                } else {
                    //có tài khoản rồi chỉ đang kí lớp học thôi
                    //lưu thông tin thanh toán bảng momo
                    //check xem thanh toán phương thức gì
                    if (isset($request->payment_method_id) && isset($request->payment_date) && isset($request->price) && isset($request->description) && isset($request->status)) {
                        $courseOfClass = ClassModel::find($request->id_lop_hoc)->course;
                        $ma_khuyen_mai = $request->ma_khuyen_mai;
                        if (isset($ma_khuyen_mai)) {
                            $objCheckMa = new MaChienDich();
                            $checkMa = $objCheckMa->loadCheckName($ma_khuyen_mai);
                            if (isset($checkMa)) {
                                $objChienDich = new ChienDich();
                                $checkGiam = $objChienDich->loadOne($checkMa->id_chien_dich);
                            } else {
                                return response()->json([
                                    'status' => false,
                                    'heading' => 'Không tồn tại mã giảm giá này',
                                ], 404);
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
                            if ($checkGiam->course_id == 0 || $checkGiam->course_id == $courseOfClass->id) {
                                $dung_khoa = 1;
                            } else {
                                $dung_khoa = 0;
                            }
                            // dd($checkMa, $objChienDich, $checkGiam,$checkGiam->course_id);

                            $now = date('Y-m-d');
                            $startDate = date('Y-m-d', strtotime($checkGiam->ngay_bat_dau));
                            $endDate = date('Y-m-d', strtotime($checkGiam->ngay_ket_thuc));
                            if (($now >= $startDate) && ($now <= $endDate)) {
                                $flag = 1;
                            } else {
                                $flag = 2;
                            }
                            if ($flag == 1 && $hoat_dong == 1 && $trang_thai == 0 && $dung_khoa == 1) {
                                // dd($request->price - (($request->price * $checkGiam->phan_tram_giam)/100));
                                $payment = Payment::create([
                                    'payment_method_id' => $request->payment_method_id,
                                    'payment_date' => date("Y-m-d h:i:s"),
                                    'price' => $request->price - (($request->price * $checkGiam->phan_tram_giam) / 100),
                                    'description' => $request->description,
                                    'status' => 1,
                                    'id_don_hang' => $request->id_don_hang,
                                    'id_giao_dich' => $request->id_giao_dich,
                                ]);
                                //nếu thêm thành công thanh toán momo vào bảng  payment
                                if ($payment) {
                                    $addDangKiIssetStudent = DangKy::create([
                                        'ngay_dang_ky' => date("Y-m-d"),
                                        'id_lop_hoc' => $request->id_lop_hoc,
                                        'id_hoc_vien' => $infoHocVien->id,
                                        'gia_tien' => ($request->price - (($request->price * $checkGiam->phan_tram_giam) / 100)),
                                        'trang_thai' => $payment->status,
                                        'id_payment' => $payment->id,
                                        'paid_date' => $payment->payment_date,
                                        'token' => Str::random(10),
                                    ]);
                                    if ($addDangKiIssetStudent->trang_thai == 1) {
                                        $classOfDangKi = $addDangKiIssetStudent->class;
                                        ClassModel::whereId($classOfDangKi->id)->update([
                                            'slot' =>  $classOfDangKi->slot - 1
                                        ]);
                                    }
                                }
                                $classDk = ClassModel::whereId($addDangKiIssetStudent->id_lop_hoc)->first();
                                Mail::send('emailThongBaoDangKyLopHocTwo', compact('classDk', 'payment', 'infoHocVien'), function ($email) use ($infoHocVien) {
                                    $email->subject("Hệ thống thông báo bạn đã đăng kí");
                                    $email->to($infoHocVien->email, $infoHocVien->name, $infoHocVien);
                                });
                                $objCheckMa = new MaChienDich();
                                $updatett = $objCheckMa->saveUpdateTT($request->ma_khuyen_mai);
                                return response()->json([
                                    'status' => true,
                                    'heading' => 'Đăng kí thành công và đã chuyển tiền thành công',
                                    'data' => $addDangKiIssetStudent,
                                    'data_payment' => $addDangKiIssetStudent->payment
                                ], 200);
                                // $arrDangKy['gia_tien'] = $gia->price - ($gia->price * $checkGiam->phan_tram_giam / 100);
                                // $apma = $checkGiam->phan_tram_giam;
                            } elseif ($dung_khoa == 0) {
                                return response()->json([
                                    'status' => false,
                                    'heading' => 'Mã giảm giá không dành cho khóa này',
                                ], 404);
                            } else {
                                return response()->json([
                                    'status' => false,
                                    'heading' => 'Mã giảm giá không hợp lệ',
                                ], 404);
                            }
                        } else {
                            $payment = Payment::create([
                                'payment_method_id' => $request->payment_method_id,
                                'payment_date' => date("Y-m-d h:i:s"),
                                'price' => $request->price,
                                'description' => $request->description,
                                'status' => 1,
                                'id_don_hang' => $request->id_don_hang,
                                'id_giao_dich' => $request->id_giao_dich,
                            ]);
                            //nếu thêm thành công thanh toán momo vào bảng  payment
                            if ($payment) {
                                $addDangKiIssetStudent = DangKy::create([
                                    'ngay_dang_ky' => date("Y-m-d"),
                                    'id_lop_hoc' => $request->id_lop_hoc,
                                    'id_hoc_vien' => $infoHocVien->id,
                                    'gia_tien' => $request->gia_tien,
                                    'trang_thai' => $payment->status,
                                    'id_payment' => $payment->id,
                                    'paid_date' => $payment->payment_date,
                                    'token' => Str::random(10),
                                ]);
                                if ($addDangKiIssetStudent->trang_thai == 1) {
                                    $classOfDangKi = $addDangKiIssetStudent->class;
                                    ClassModel::whereId($classOfDangKi->id)->update([
                                        'slot' =>  $classOfDangKi->slot - 1
                                    ]);
                                }
                            }
                            $classDk = ClassModel::whereId($addDangKiIssetStudent->id_lop_hoc)->first();
                            Mail::send('emailThongBaoDangKyLopHocTwo', compact('classDk', 'payment', 'infoHocVien'), function ($email) use ($infoHocVien) {
                                $email->subject("Hệ thống thông báo bạn đã đăng kí");
                                $email->to($infoHocVien->email, $infoHocVien->name, $infoHocVien);
                            });
                            return response()->json([
                                'status' => true,
                                'heading' => 'đang kí thành công và đã chuyển tiền thành công',
                                'data' => $addDangKiIssetStudent,
                                'data_payment' => $addDangKiIssetStudent->payment
                            ], 200);
                        }
                    }
                    //không có payment momo
                    $addDangKiIssetStudent = DangKy::create([
                        'ngay_dang_ky' => date("Y-m-d"),
                        'id_lop_hoc' => $request->id_lop_hoc,
                        'id_hoc_vien' => $infoHocVien->id,
                        'gia_tien' => $request->gia_tien,
                        'trang_thai' => 0,
                        'id_payment' => null,
                        'paid_date' => null,
                        'token' => Str::random(10),

                    ]);
                    $classDk = ClassModel::whereId($addDangKiIssetStudent->id_lop_hoc)->first();
                    Mail::send('emailThongBaoDangKyLopHocChuaNop', compact('classDk', 'infoHocVien'), function ($email) use ($infoHocVien) {
                        $email->subject("Hệ thống thông báo bạn đã đăng kí lớp học");
                        $email->to($infoHocVien->email, $infoHocVien->name, $infoHocVien);
                    });
                    return response()->json([
                        'status' => true,
                        'heading' => 'Đăng kí thành công. Bạn vui lòng đóng tiền trước thời gian khai giảng để tham gia lớp học',
                        'data' => $addDangKiIssetStudent,
                    ], 200);
                }
            }
        }
        //check validate khi chuan bi them moi hoc vien
        $validated = Validator::make($request->all(), [
            'ho_ten' => 'required',
            'ngay_sinh' => 'required',
            'gioi_tinh' => 'required',
            'so_dien_thoai' => 'required',
            'email' => 'required',
            // 'hinh_anh' => 'required',
        ]);
        if ($validated->fails()) {
            return response()->json([
                'heading' => 'lỗi validate',
                'log' => $validated->errors(),
            ], 400);
        }
        //không lỗi validate nào thì thêm thông tin vào bảng học viên và lưu đang kí, thanh toán
        return $this->add_table_hoc_vien_and_register_class($request);
    }

    public function add_table_hoc_vien_and_register_class(Request $request)
    {
        $addNewStudent = HocVien::create([
            'ho_ten' => $request->ho_ten,
            'ngay_sinh' => $request->ngay_sinh,
            'gioi_tinh' => $request->gioi_tinh,
            'so_dien_thoai' => $request->so_dien_thoai,
            'email' => $request->email,
            // 'hinh_anh' => $request->hinh_anh,
            'trang_thai' => 1,
            'address' => $request->address,
            'cccd' => $request->cccd,
            'password' => Str::random(6),
            'tokenActive' => Str::random(20),
        ]);
        if ($addNewStudent) {
            if (isset($request->payment_method_id) && isset($request->payment_date) && isset($request->price) && isset($request->description) && isset($request->status)) {
                // dd('có pay men momo');
                $ma_khuyen_mai = $request->ma_khuyen_mai;
                if (isset($ma_khuyen_mai)) {
                    $courseOfClass = ClassModel::find($request->id_lop_hoc)->course;
                    $objCheckMa = new MaChienDich();
                    $checkMa = $objCheckMa->loadCheckName($ma_khuyen_mai);
                    if (isset($checkMa)) {
                        $objChienDich = new ChienDich();
                        $checkGiam = $objChienDich->loadOne($checkMa->id_chien_dich);
                    } else {
                        return response()->json([
                            'status' => false,
                            'heading' => 'Không tồn tại mã giảm giá này',
                        ], 404);
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
                    if ($checkGiam->course_id == 0 || $checkGiam->course_id == $courseOfClass->id) {
                        $dung_khoa = 1;
                    } else {
                        $dung_khoa = 0;
                    }
                    // dd($checkMa, $objChienDich, $checkGiam,$checkGiam->course_id);

                    $now = date('Y-m-d');
                    $startDate = date('Y-m-d', strtotime($checkGiam->ngay_bat_dau));
                    $endDate = date('Y-m-d', strtotime($checkGiam->ngay_ket_thuc));
                    if (($now >= $startDate) && ($now <= $endDate)) {
                        $flag = 1;
                    } else {
                        $flag = 2;
                    }
                    if ($flag == 1 && $hoat_dong == 1 && $trang_thai == 0 && $dung_khoa == 1) {
                        $payment = Payment::create([
                            'payment_method_id' => $request->payment_method_id,
                            'payment_date' => date("Y-m-d h:i:s"),
                            'price' => $request->price,
                            'description' => $request->description,
                            'status' => 1,
                            'id_don_hang' => $request->id_don_hang,
                            'id_giao_dich' => $request->id_giao_dich,
                        ]);
                        //nếu thêm thành công thanh toán momo vào bảng  payment
                        if ($payment) {
                            $addDangKiIssetStudent = DangKy::create([
                                'ngay_dang_ky' => date("Y-m-d"),
                                'id_lop_hoc' => $request->id_lop_hoc,
                                'id_hoc_vien' => $addNewStudent->id,
                                'gia_tien' => $request->gia_tien,
                                'trang_thai' => $payment->status,
                                'id_payment' => $payment->id,
                                'paid_date' => $payment->payment_date,
                                'token' => Str::random(10),
        
                            ]);
                            if ($addDangKiIssetStudent->trang_thai == 1) {
                                $classOfDangKi = $addDangKiIssetStudent->class;
                                ClassModel::whereId($classOfDangKi->id)->update([
                                    'slot' =>  $classOfDangKi->slot - 1
                                ]);
                            }
                        }
                        $classDk = ClassModel::whereId($addDangKiIssetStudent->id_lop_hoc)->first();
                        Mail::send('emailThongBaoDangKiLopHoc', compact('classDk', 'payment', 'addNewStudent'), function ($email) use ($addNewStudent) {
                            $email->subject("Hệ thống thông báo bạn đã đăng kí");
                            $email->to($addNewStudent->email, $addNewStudent->name, $addNewStudent);
                        });
                        return response()->json([
                            'status' => true,
                            'heading' => 'tạo thành công tài koản, đang kí thành công và đã chuyển tiền thành công',
                            'data' => $addDangKiIssetStudent,
                            'data_payment' => $addDangKiIssetStudent->payment
                        ], 200);
                    } elseif ($dung_khoa == 0) {
                        return response()->json([
                            'status' => false,
                            'heading' => 'Mã giảm giá không dành cho khóa này',
                        ], 404);
                    } else {
                        return response()->json([
                            'status' => false,
                            'heading' => 'Mã giảm giá không hợp lệ',
                        ], 404);
                    }
                }
                // Nếu không có mã giảm giá
                $payment = Payment::create([
                    'payment_method_id' => $request->payment_method_id,
                    'payment_date' => date("Y-m-d h:i:s"),
                    'price' => $request->price,
                    'description' => $request->description,
                    'status' => 1,
                    'id_don_hang' => $request->id_don_hang,
                    'id_giao_dich' => $request->id_giao_dich,
                ]);
                //nếu thêm thành công thanh toán momo vào bảng  payment
                if ($payment) {
                    $addDangKiIssetStudent = DangKy::create([
                        'ngay_dang_ky' => date("Y-m-d"),
                        'id_lop_hoc' => $request->id_lop_hoc,
                        'id_hoc_vien' => $addNewStudent->id,
                        'gia_tien' => $request->gia_tien,
                        'trang_thai' => $payment->status,
                        'id_payment' => $payment->id,
                        'paid_date' => $payment->payment_date,
                        'token' => Str::random(10),

                    ]);
                    if ($addDangKiIssetStudent->trang_thai == 1) {
                        $classOfDangKi = $addDangKiIssetStudent->class;
                        ClassModel::whereId($classOfDangKi->id)->update([
                            'slot' =>  $classOfDangKi->slot - 1
                        ]);
                    }
                }
                $classDk = ClassModel::whereId($addDangKiIssetStudent->id_lop_hoc)->first();
                Mail::send('emailThongBaoDangKiLopHoc', compact('classDk', 'payment', 'addNewStudent'), function ($email) use ($addNewStudent) {
                    $email->subject("Hệ thống thông báo bạn đã đăng kí");
                    $email->to($addNewStudent->email, $addNewStudent->name, $addNewStudent);
                });
                return response()->json([
                    'status' => true,
                    'heading' => 'tạo thành công tài koản, đang kí thành công và đã chuyển tiền thành công',
                    'data' => $addDangKiIssetStudent,
                    'data_payment' => $addDangKiIssetStudent->payment
                ], 200);
            }
            //không có payment momo
            $addDangKiIssetStudent = DangKy::create([
                'ngay_dang_ky' => date("Y-m-d"),
                'id_lop_hoc' => $request->id_lop_hoc,
                'id_hoc_vien' => $addNewStudent->id,
                'gia_tien' => $request->gia_tien,
                'trang_thai' => 0,
                'id_payment' => null,
                'paid_date' => null,
                'token' => Str::random(10),
            ]);
            $classDk = ClassModel::whereId($addDangKiIssetStudent->id_lop_hoc)->first();
            Mail::send('emailThongBaoDangKyLopHocChuaNopTwo', compact('classDk', 'addNewStudent'), function ($email) use ($addNewStudent) {
                $email->subject("Hệ thống thông báo bạn đã đăng kí lớp học");
                $email->to($addNewStudent->email, $addNewStudent->name, $addNewStudent);
            });
            return response()->json([
                'status' => true,
                'heading' => 'Đang kí thành công tài khoản và đăng kí thành công. Vui lòng thanh toán để tham gia lớp học',
                'data' => $addDangKiIssetStudent,
            ], 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function registerCheck(Request $request)
    {

        $validated = Validator::make($request->all(), [
            'ho_ten' => 'required',
            'so_dien_thoai' => 'required',
            'email' => 'required',
            'id_lop_hoc' => 'required',
        ]);
        if ($validated->fails()) {
            return response()->json([
                'heading' => 'lỗi validate',
                'log' => $validated->errors(),
            ], 400);
        }

        $checkIssetEmail = HocVien::where('email', $request->email)->first();
        $checkIssetPhone = HocVien::where('so_dien_thoai', $request->so_dien_thoai)->first();
        $checkIdlopHoc = ClassModel::where('id', $request->id_lop_hoc)->first();

        if ($checkIdlopHoc == null) {
            return response()->json([
                'status' => 404,
                'heading' => "Lớp không tồn tại",
                'data' => true,
            ], 400);
        }

        if ($checkIssetEmail != null || $checkIssetPhone != null) {
            $infoHocVien = '';

            if ($checkIssetEmail == null) {
                $infoHocVien = $checkIssetPhone;
            }
            if ($checkIssetPhone == null) {
                $infoHocVien = $checkIssetEmail;
            }
            if ($checkIssetEmail != null && $checkIssetPhone != null) {
                $infoHocVien = $checkIssetEmail;
            }

            if (isset($infoHocVien->id) && isset($request->id_lop_hoc)) {
                $checkDk = DangKy::where('id_lop_hoc', '=', $request->id_lop_hoc)->where('id_hoc_vien', '=', $infoHocVien->id)->first();

                if ($checkDk) {
                    return response()->json([
                        'status' => 400,
                        'heading' => "Thông tin này đã được đăng ký",
                        'data' => false,
                    ], 400);
                }

                return response()->json([
                    'status' => 200,
                    'heading' => "Thông tin này chưa được đăng ký",
                    'data' => true,
                ], 200);
            }
            // return;
        }

        return response()->json([
            'status' => 200,
            'heading' => "Thông tin này chưa được đăng ký",
            'data' => true,
        ], 200);
    }


    public function show($id)
    {
        $dki = DangKy::find($id)->payment;
        dd($dki);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $dangKy = DangKy::where('id', $id)->first();
        $checkClass = ClassModel::where('id', $request->id_lop_hoc)->first();
        //check con slot khong
        if ($checkClass->slot > 0) {
            $dangKyOld = DangKy::where('id', $id)->first();
            $updateDangKy =  $dangKy->update([
                'id_lop_hoc' => $request->id_lop_hoc,
            ]);
            $dangKyAfterUpdate = DangKy::where('id', $id)->first();
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
            $dangKyAfterUpdate = DangKy::where('id', $id)->first();
            if ($dangKyAfterUpdate->trang_thai == 1) {
                $classOfChuyenLop = $dangKyAfterUpdate->class;
                ClassModel::whereId($classOfChuyenLop->id)->update([
                    'slot' =>  $classOfChuyenLop->slot - 1
                ]);
                return response()->json([
                    'status' => 200,
                    'heading' => 'Chuyển lớp thành công số chỗ của lớp mới đã trừ đi 1'
                ], 200);
            } else {
                return response()->json([
                    'status' => 200,
                    'heading' => 'Chuyển lớp thành công chờ trường check thanh toán'
                ], 200);
            }

            return response()->json([
                'status' => true,
                'heading' => 'Chuyển lớp thành công',
                'data' => $dangKyAfterUpdate
            ]);
        } else {
            return response()->json([
                'status' => true,
                'heading' => 'lớp đã đầy không thể chuyển lớp'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function dongThem(Request $request)
    {
        // dd(123);
        $validated = Validator::make($request->all(), [
            'payment_date' => 'required',
            'price' => 'required',
            'status' => 'required',
            'idDangKy' => 'required'
        ]);

        if ($validated->fails()) {
            return response()->json([
                'heading' => 'lỗi validate',
                'log' => $validated->errors(),
            ], 400);
        }

        // try {
        //     DB::beginTransaction();
        if (isset($request->payment_date) && isset($request->price) && isset($request->status) && isset($request->idDangKy)) {
            // dd('có pay men momo');
            $sott = $request->price;
            $paymentUpdate = [
                'payment_date' => date("Y-m-d h:i:s"),
                'price' => $request->price,
                'description' => "(đóng thêm)",
                'status' => 1,
            ];
            // dd($paymentUpdate);
            $dangKy = DangKy::find($request->idDangKy);

            if ($dangKy->du_no == 0) {
                return response()->json([
                    'status' => 400,
                    'heading' => 'Giao dịch này đã hiện thực hiện vui lòng check lại Email',
                    'data' => $dangKy->du_no,
                ], 400);
            }

            //cập nhập ở đăng kí
            $dangKy['trang_thai'] = 1;
            $dangKy['so_tien_da_dong'] = null;
            $dangKy['du_no'] = 0;
            $dangKy->update();

            //cập nhập slots
            $class =  ClassModel::find($dangKy->id_lop_hoc);
            $class['slot'] = $class['slot'] - 1;
            $class->update();

            //cập nhập ở payment
            $payment = Payment::find($dangKy->id_payment);
            $payment['payment_date'] = $paymentUpdate['payment_date'];
            $payment['price'] = $payment['price'] + $paymentUpdate['price'];
            $payment['description'] = $payment->description . $paymentUpdate['description'];
            $payment->update();
            // dd($dangKy,$payment);

            $hoc_vien = HocVien::where('id', $dangKy->id_hoc_vien)->first();
            //gửi email là đóng thêm học phí thành công
            Mail::send('emailThongBaoDongThemHocPhiFe', compact('payment', 'paymentUpdate', 'hoc_vien', 'class', 'sott'), function ($email) use ($hoc_vien) {
                $email->subject("Hệ thống gửi thông báo bạn đã đóng đủ học phí");
                $email->to($hoc_vien->email, $hoc_vien->name, $hoc_vien);
            });
            return response()->json([
                'status' => true,
                'heading' => 'Thanh toán thành công, vui lòng kiểm tra email',
                'data' => $dangKy,
                'data_2' => $payment,
            ], 200);
        }
    }

    // khi đăng kí mà không đóng tiền sau đó có thể đóng tiền online
    public function dongHocPhiOnline(Request $request)
    {
        // dd(123);
        $validated = Validator::make($request->all(), [
            'payment_date' => 'required',
            'price' => 'required',
            'id_giao_dich' => 'required',
            'id_don_hang' => 'required',
            'idDangKy' => 'required'
        ]);

        if ($validated->fails()) {
            return response()->json([
                'heading' => 'lỗi validate',
                'log' => $validated->errors(),
            ], 400);
        }

        // try {
        //     DB::beginTransaction();
        if (isset($request->payment_date) && isset($request->price)  && isset($request->idDangKy) && isset($request->id_giao_dich) && isset($request->id_don_hang)) {
            // dd('có pay men momo');
            $paymenCreate = [
                'payment_method_id' => 2,
                'payment_date' => date("Y-m-d h:i:s"),
                'price' => $request->price,
                'description' => "Đóng tiền online",
                'status' => 1,
                'id_giao_dich' => $request->id_giao_dich,
                'id_don_hang' => $request->id_don_hang,
            ];
            $paymentAdd = Payment::create($paymenCreate);

            if ($paymentAdd) {
                $dangKy = DangKy::find($request->idDangKy);
                //cập nhập ở đăng kí
                $dangKy['trang_thai'] = 1;
                $dangKy['so_tien_da_dong'] = null;
                $dangKy['du_no'] = 0;
                $dangKy['id_payment'] = $paymentAdd->id;
                $dangKy->update();

                //cập nhập slots
                if ($dangKy->trang_thai == 1) {
                    $class =  ClassModel::find($dangKy->id_lop_hoc);
                    $class['slot'] = $class['slot'] - 1;
                    $class->update();
                }

                $hoc_vien = HocVien::where('id', $dangKy->id_hoc_vien)->first();
                //gửi email là đóng thêm học phí thành công
                Mail::send('emailThongBaoDongHocPhiOnline', compact('paymentAdd',  'hoc_vien',  'class'), function ($email) use ($hoc_vien) {
                    $email->subject("Hệ thống gửi thông báo bạn đã đóng đủ học phí");
                    $email->to($hoc_vien->email, $hoc_vien->name, $hoc_vien);
                });
                return response()->json([
                    'status' => true,
                    'heading' => 'Thanh toán thành công, vui lòng kiểm tra email',
                ], 200);
            }
        }
        //     DB::commit();
        // } catch (\Exception $exception) {
        //     DB::rollback();
        //     return response()->json([
        //         'status' => false,
        //         'heading' => 'Lỗi'
        //     ],500);
        // }
    }

    public function checkGiaoDichDongThem($id)
    {
        if (!isset($id)) {
            return response()->json([
                'status' => 400,
                'heading' => 'Không tìm thấy mã giao dịch!',
                'data' => false,
            ], 400);
        }

        $dangKy = DangKy::find($id);

        if (!isset($dangKy)) {
            return response()->json([
                'status' => 400,
                'heading' => 'Không tìm thấy giao dịch!',
                'data' => false,
            ], 400);
        }

        if ($dangKy->du_no == 0) {
            return response()->json([
                'status' => 400,
                'heading' => 'Giao dịch này đã hiện thực hiện vui lòng check lại Email hoặc liên lạc với trung tâm đào tạo',
                'data' => false,
            ], 400);
        }

        return response()->json([
            'status' => 200,
            'heading' => 'Giao dich chưa thực hiện.',
            'data' => true,
        ], 200);
    }
}
