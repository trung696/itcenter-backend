<?php

namespace App\Http\Controllers\Api;

use App\DangKy;
use App\HocVien;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


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
        // dd($checkIssetPhone);
        //hoc vien da ton tai tren db
        if ($checkIssetEmail != null || $checkIssetPhone != null) {
            //check xem đã đang kí khóa học đấy chưa
            $addDangKiIssetStudent = DangKy::create([
                'ngay_dang_ki' => date('d-m-Y'),
                'id_lop_hoc' => '1',
                'id_hoc_vien' => $checkIssetEmail->id,
                'gia_tien' => $request->gia_tien,
                'trang_thai' => '1'
            ]);
            return response()->json([
                'data' =>$addDangKiIssetStudent,
                'status' => true,
                'heading' => 'thêm vào đang kí thành công khi đã có tài khoản'
            ],200);
        }
        //check validate khi chuan bi them moi hoc vien
        $validated = Validator::make($request->all(), [
            'ho_ten' => 'required',
            'ngay_sinh' => 'required',
            'gioi_tinh' => 'required',
            'so_dien_thoai' => 'required',
            'email' => 'required',
            'hinh_anh' => 'required',
        ]);
        if ($validated->fails()) {
            return response()->json([
                'heading' => 'lỗi validate',
                'log' => $validated->errors(),
            ], 404);
        }
        // validate
        $addNewStudent = HocVien::create([
            'ho_ten' => $request->ho_ten,
            'ngay_sinh' => $request->ngay_sinh,
            'gioi_tinh' => $request->gioi_tinh,
            'so_dien_thoai' => $request->so_dien_thoai,
            'email' => $request->email,
            'hinh_anh' => $request->hinh_anh,
            'trang_thai' => $request->trang_thai
        ]);

        if ($addNewStudent) {
            //thêm xong sinh viên thì cần add ngày đang kí, id lớp học,id sinh viên, giá tiền lớp học  vào bảng dang_ki
            $addDangKi = DangKy::create([
                'ngay_dang_ki' => date('d-m-Y'),
                'id_lop_hoc' => '1',
                'id_hoc_vien' => $addNewStudent->id,
                'gia_tien' => $request->gia_tien,
                'trang_thai' => '1'
            ]);
        }
        return response()->json([
            'heading' => 'Thêm mới sinh vien',
            'status' => true,
            'data' => $addNewStudent,
            'data_dang_ki' => $addDangKi
        ],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
