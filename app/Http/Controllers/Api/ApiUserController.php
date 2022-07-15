<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\SessionUser;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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
        $userAdd = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' =>Hash::make($request->password),
            'address' => $request->address,
            'birthday' => $request->birthday,
            'gender' => $request->gender,
            'status' => 0,
            'tokenActive' => Str::random(20),
        ];
        // dd($userAdd);
       if( $user = User::create($userAdd) ){
        Mail::send('emailActiveUser',compact('user'), function ($email) use($user){
            // mail nhận thư, tên người dùng
            $email->subject("Xác thực tài khoản");
            $email->to($user->email,$user->name);
        });
       }
        return response()->json([
            'heading' => 'được của nó rồi',
            'data' => $user,
            'status' => true    ,
        ],200);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, User $user)
    {
        $tokenUp = $request->header('token');
        $checkToken = SessionUser::where('token',$tokenUp)->first();
        if(empty($tokenUp)){
            return response()->json([
                'status' => false,
                'heading' => "Không gửi token lên",
            ],401);
        }elseif(empty($checkToken)){
            return response()->json([
                'status' => false,
                'heading' => "Không tồn tại token trên database",
            ],401);
        }else{
            return response()->json([
                'status' => false,
                'heading' => "Thông tin của user",
                'data' => $user
            ],200);
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
        $userUpdate = User::find($id);
        $userUpdate->update($request->all());
        return response()->json([
            'heading'=>'cập nhập thành công',
            'status'=>true,
            'data' => $userUpdate
        ]);
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
