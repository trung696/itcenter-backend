<?php

namespace App\Http\Controllers\Api;

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
            'email' => 'required|email',
            'password' => 'required|min:1'
        ];
        $messages = [
            'email.required' => 'Email là trường bắt buộc',
            'email.email' => 'Email không đúng định dạng',
            'password.required' => 'Mật khẩu là trường bắt buộc',
            'password.min' => 'Mật khẩu phải chứa ít nhất 2 ký tự',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);


        if ($validator->fails()) {
            // Điều kiện dữ liệu không hợp lệ sẽ chuyển về trang đăng nhập và thông báo lỗi
            return redirect('login')->withErrors($validator)->withInput();
        } else {
            // Nếu dữ liệu hợp lệ sẽ kiểm tra trong csdl
            // dd($request->all());
            $email = $request->input('email');
            $password = $request->input('password');

            if (Auth::attempt(['email' => $email, 'password' => $password])) {
                // return redirect('user');
                $checkTokenExit = SessionUser::where('user_id', auth()->id())->first();
                if (empty($checkTokenExit)) {
                    $addToken = SessionUser::create([
                        'token' => Str::random(40),
                        'refresh_token' => Str::random(40),
                        'expired_token' => date('Y-m-d H:i:s', strtotime('+30 day')),
                        'refresh_expired_token' => date('Y-m-d H:i:s', strtotime('+360 day')),
                        'user_id' => auth()->id()
                    ]);
                } else {
                    $addToken =  $checkTokenExit;
                }
                return response()->json([
                    'status' => true,
                    'heading' => 'ok',
                    'data' =>  $addToken
                ],200);
            } else {
                return response()->json([
                    'status' => false,
                    'heading' => 'lunrg',
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
}
