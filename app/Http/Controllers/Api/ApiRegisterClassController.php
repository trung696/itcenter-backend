<?php

namespace App\Http\Controllers\Api;

use App\Ca;
use App\ClassModel;
use App\DangKy;
use App\HocVien;
use App\Http\Controllers\Controller;
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
                        'heading' => "Bạn đã đang kí lớp học này rồi"
                    ], 400);
                } else {
                    //có tài khoản rồi chỉ đang kí lớp học thôi
                    //lưu thông tin thanh toán bảng momo
                    //check xem thanh toán phương thức gì
                    if (isset($request->payment_method_id) && isset($request->payment_date) && isset($request->price) && isset($request->description) && isset($request->status)) {
                        // dd('có pay men momo');
                        $payment = Payment::create([
                            'payment_method_id' => $request->payment_method_id,
                            'payment_date' => date("Y-m-d h:i:s"),
                            'price' => $request->price,
                            'description' => $request->description,
                            'status' => 1,
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
                            ]);
                            if ($addDangKiIssetStudent->trang_thai == 1) {
                                $classOfDangKi = $addDangKiIssetStudent->class;
                                ClassModel::whereId($classOfDangKi->id)->update([
                                    'slot' =>  $classOfDangKi->slot - 1
                                ]);
                            }
                        }
                        $classDk = ClassModel::whereId($addDangKiIssetStudent->id_lop_hoc)->first();
                        // gửi mail thông báo
                        Mail::send('emailThongBaoDangKiLopHoc', compact('classDk', 'payment'), function ($email) use ($infoHocVien) {
                            $email->subject("Hệ thống thông báo bạn đã đăng kí và đóng học phí và yêu cầu bạn xác nhận giao dịch ");
                            $email->to($infoHocVien->email, $infoHocVien->name, $infoHocVien);
                        });
                        return response()->json([
                            'status' => true,
                            'heading' => 'đang kí thành công và đã chuyển tiền thành công qua momo',
                            'data' => $addDangKiIssetStudent,
                            'data_payment' => $addDangKiIssetStudent->payment
                        ], 200);
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
                    ]);
                    $classDk = ClassModel::whereId($addDangKiIssetStudent->id_lop_hoc)->first();

                    Mail::send('emailThongBaoDangKiLopHoc', compact('classDk'), function ($email) use ($infoHocVien) {
                        $email->subject("Hệ thống thông báo bạn đã đăng kí lớp học");
                        $email->to($infoHocVien->email, $infoHocVien->name, $infoHocVien);
                    });
                    return response()->json([
                        'status' => true,
                        'heading' => 'đang kí thành công chờ hệ thống kiểm tra xem bạn đã thanh toán hay chưa',
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
            'password' => Str::random(6),
            'tokenActive' => Str::random(20),
        ]);
        if ($hoc_vien = $addNewStudent) {
            Mail::send('emailSendPassword', compact('hoc_vien'), function ($email) use ($hoc_vien) {
                // mail nhận thư, tên người dùng
                $email->subject("Hệ thống gửi password đến bạn");
                $email->to($hoc_vien->email, $hoc_vien->ho_ten, $hoc_vien);
            });
          
        }

        if ($addNewStudent) {
            if (isset($request->payment_method_id) && isset($request->payment_date) && isset($request->price) && isset($request->description) && isset($request->status)) {
                // dd('có pay men momo');
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
                    ]);
                    if ($addDangKiIssetStudent->trang_thai == 1) {
                        $classOfDangKi = $addDangKiIssetStudent->class;
                        ClassModel::whereId($classOfDangKi->id)->update([
                            'slot' =>  $classOfDangKi->slot - 1
                        ]);
                    }
                }
                Mail::send('emailThongBaoDangKiLopHoc', compact('classDk','payment'), function ($email) use ($addNewStudent) {
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
            ]);
            $classDk = ClassModel::whereId($addDangKiIssetStudent->id_lop_hoc)->first();
            Mail::send('emailThongBaoDangKiLopHoc', compact('classDk'), function ($email) use ($addNewStudent) {
                $email->subject("Hệ thống thông báo bạn đã đăng kí lớp học");
                $email->to($addNewStudent->email, $addNewStudent->name, $addNewStudent);
            });
            return response()->json([
                'status' => true,
                'heading' => 'đang kí thành công tài khoản và thêm được vào bảng đang kí, chờ hệ thống kiểm tra xem bạn đã thanh toán hay chưa',
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
                $paymentUpdate = [
                    'payment_date' => date("Y-m-d h:i:s"),
                    'price' => $request->price,
                    'description' => "(đóng thêm)",
                    'status' => 1,
                ];
                // dd($paymentUpdate);
                $dangKy = DangKy::find($request->idDangKy);
                //cập nhập ở đăng kí
                $dangKy['trang_thai'] = 1;
                $dangKy['so_tien_da_dong'] = null;
                $dangKy['du_no'] = 0;
                $dangKy->update();
                
                //cập nhập slots
                $class =  ClassModel::find($dangKy->id_lop_hoc);
                $class['slot'] = $class['slot'] - 1 ;
                $class->update();
                
                //cập nhập ở payment
                $payment = Payment::find($dangKy->id_payment);
                $payment['payment_date'] = $paymentUpdate['payment_date'];
                $payment['price'] = $payment['price'] + $paymentUpdate['price'];
                $payment['description'] = $payment->description . $paymentUpdate['description'];
                $payment->update();
                // dd($dangKy,$payment);

                $hoc_vien = HocVien::where('id',$dangKy->id_hoc_vien)->first();
                //gửi email là đóng thêm học phí thành công
                Mail::send('emailThongBaoDongThemThanhCong', compact('paymentUpdate', 'class'), function ($email) use ($hoc_vien) {
                    $email->subject("Hệ thống gửi thông báo bạn đã đóng đủ học phí");
                    $email->to($hoc_vien->email, $hoc_vien->name, $hoc_vien);
                });
                return response()->json([
                    'status' => true,
                    'heading' => 'Thanh toán thành công, vui lòng kiểm tra email',
                    'data' => $dangKy,
                    'data_2' => $payment,
                ],200);
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
}
