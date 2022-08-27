<?php

namespace App\Http\Controllers\Api;

use App\HocVien;
use App\Http\Controllers\Controller;
use App\SessionUser;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class ApiUserController extends Controller
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
        $rules = [
            'ho_ten' => 'bail|required|string',
            'ngay_sinh' => 'bail|required|date',
            'gioi_tinh' => 'bail|required|numeric',
            'so_dien_thoai' => 'bail|required|unique:hoc_vien|numeric',
            'email' => 'bail|required|email|unique:hoc_vien',
            'hinh_anh' => 'nullable',
        ];
        $messages = [
            'ho_ten.required' => 'Họ tên không để trống',
            'ho_ten.string' => 'Họ tên không để kí tự và số',

            'ngay_sinh.required' => 'Ngày sinh không để trống',
            'ngay_sinh.date' => 'Định dạng ngày sinh Y-m-d',

            'gioi_tinh.required' => 'Giới tính không để trống',
            'gioi_tinh.numeric' => 'Chọn 1 hoăc 2',

            'so_dien_thoai.required' => 'Số điện thoại không để trống',
            'so_dien_thoai.numeric' => 'Số điện thoại đã tồn tại',
            'so_dien_thoai.numeric' => 'Số điện thoại phải nhập là số',

            'email.required' => 'Email là trường bắt buộc',
            'email.unique' => 'Email đã tồn tại',
            'email.email' => 'Email nhập phải đúng định dạng',
            // 'password.min' => 'Mật khẩu phải chứa ít nhất 2 ký tự',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'heading' => 'Chưa qua được validate',
                'error' => $validator->errors()
            ], 500);
        }
        $userAdd = [
            'ho_ten' => $request->ho_ten,
            'ngay_sinh' => $request->ngay_sinh,
            'gioi_tinh' => $request->gioi_tinh,
            'so_dien_thoai' => $request->so_dien_thoai,
            'email' => $request->email,
            'hinh_anh' => $request->hinh_anh,
            'trang_thai' => 0,
            'password' => Str::random(6),
            'tokenActive' => Str::random(20),
        ];
        if ($user = HocVien::create($userAdd)) {
            Mail::send('emailSendPassword', compact('user'), function ($email) use ($user) {
                // mail nhận thư, tên người dùng
                $email->subject("Hệ thống gửi password đến bạn");
                $email->to($user->email, $user->name, $user);
            });
        }
        return response()->json([
            'heading' => 'Thêm thành công tài khoản',
            'data' => $user,
            'status' => true,
        ], 200);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $tokenUp = $request->bearerToken();
        $checkToken = SessionUser::where('token', $tokenUp)->first();
        //    dd($checkToken->hocVien);

        if (empty($tokenUp)) {
            return response()->json([
                'status' => false,
                'heading' => "Không gửi token lên",
            ], 401);
        } elseif (empty($checkToken)) {
            return response()->json([
                'status' => false,
                'heading' => "Không tồn tại token trên database",
            ], 401);
        } else {
            return response()->json([
                'status' => true,
                'heading' => "Thông tin của user",
                'data' => $checkToken->hocVien
            ], 200);
        }
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
        $rules = [
            'ho_ten' => 'bail|required|string',
            'ngay_sinh' => 'bail|required|date',
            'gioi_tinh' => 'bail|required|numeric',
            'hinh_anh' => 'nullable',
        ];


        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'heading' => 'Chưa qua được validate',
                'error' => $validator->errors()
            ], 400);
        }

        $userUpdate = HocVien::find($id);
        $userUpdate->update(
            $request->all()
        );
        // dd($userUpdate);
        return response()->json([
            'heading' => 'cập nhập thành công',
            'status' => true,
            'data' => $userUpdate
        ]);
    }

    public function changePassword(Request $request)
    {

        $tokenUp = $request->bearerToken();
        $checkToken = SessionUser::where('token', $tokenUp)->first();


        if (empty($tokenUp)) {
            return response()->json([
                'status' => 401,
                'heading' => "Không gửi token lên",
            ], 401);
        }

        $rules = [
            'oldPassword' => 'required',
            'newPassword' => 'required',
        ];


        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'heading' => 'Chưa qua được validate',
                'error' => $validator->errors()
            ], 400);
        }

        $userUpdate = HocVien::find($checkToken->user_id);

        if ($request->oldPassword != $userUpdate->password) {
            return response()->json([
                'status' => 400,
                'heading' => 'Mật khẩu cũ không đúng',
                'data' => false
            ], 400);
        }

        $userUpdate['password'] = $request->newPassword;
        $userUpdate->update();

        return response()->json([
            'status' => 200,
            'heading' => 'Đổi mật khẩu thành công',
            'data' => true
        ], 200);
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
