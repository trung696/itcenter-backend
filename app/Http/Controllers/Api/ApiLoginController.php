<?php

namespace App\Http\Controllers\Api;

use App\HocVien;
use App\Http\Controllers\Controller;
use App\SessionUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class ApiLoginController extends Controller
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
            'email' => 'bail|required|email',
            'password' => 'required'
        ];
        $messages = [
            'email.required' => 'Email là trường bắt buộc',
            'email.unique' => 'Email đã tồn tại',
            'password.required' => 'Mật khẩu là trường bắt buộc',
            // 'password.min' => 'Mật khẩu phải chứa ít nhất 2 ký tự',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'heading' => 'Chưa qua được validate',
                'error' => $validator->errors()
            ],500);
        } else {
            // Nếu dữ liệu hợp lệ sẽ kiểm tra trong csdl
            $email = $request->input('email');
            $password = $request->input('password');
            $userCheck = HocVien::where('email','=',$email)->where('password','=',$password)->first();

            if($userCheck){         
                $checkTokenExit = SessionUser::where('user_id', $userCheck->id)->first();
                if (empty($checkTokenExit)) {
                    $addToken = SessionUser::create([
                        'token' => Str::random(40),
                        'refresh_token' => Str::random(40),
                        'expired_token' => date('Y-m-d H:i:s', strtotime('+30 day')),
                        'refresh_expired_token' => date('Y-m-d H:i:s', strtotime('+360 day')),
                        'user_id' => $userCheck->id
                    ]);
                } else {
                    $addToken =  $checkTokenExit;
                }
                return response()->json([
                    'status' => true,
                    'heading' => 'Tạo thành công token cho user hoc_vien đang login',
                    'data' =>  $addToken
                ],200);

            } else {
                return response()->json([
                    'status' => false,
                    'heading' => 'sai user hoac pass',
                ],500);
            }
        }
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
    public function deleteToken(Request  $request){
        $tokenUp = $request->header('token');
        // $tokenUp = $request->bearerToken();
        $checkToken = SessionUser::where('token',$tokenUp)->first();
        if(empty($tokenUp)){
            return response()->json([
                'status' => false,
                'heading' => 'Không gửi token lên',
            ], 401);
        }elseif(!empty($checkToken)){
            $checkToken->delete();
            return response()->json([
                'status' => true,
                'heading' => 'Xóa thành công token',
            ], 200);
        }
    }
}
