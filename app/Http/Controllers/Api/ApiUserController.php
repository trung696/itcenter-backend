<?php

namespace App\Http\Controllers\Api;

use App\HocVien;
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
            'ho_ten' => $request->ho_ten,
            'ngay_sinh' => $request->ngay_sinh,
            'gioi_tinh' => $request->gioi_tinh,
            'so_dien_thoai' =>$request->so_dien_thoai,
            'email' => $request->email,
            'hinh_anh'=> $request->hinh_anh,
            'trang_thai' => 0,
            'password' => Str::random(6),
            'tokenActive' => Str::random(20),
        ];
       if( $user = HocVien::create($userAdd) ){
        Mail::send('emailActiveUser',compact('user'), function ($email) use($user){
            // mail nhận thư, tên người dùng
            $email->subject("Xác thực tài khoản");
            $email->to($user->email,$user->name);
        });
       }
        return response()->json([
            'heading' => 'Thêm thành công tài khoản',
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
    public function show(Request $request)
    {
        $tokenUp = $request->bearerToken();
        $checkToken = SessionUser::where('token',$tokenUp)->first();
    //    dd($checkToken->hocVien);

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
                'status' => true,
                'heading' => "Thông tin của user",
                'data' => $checkToken->hocVien
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
        $userUpdate = HocVien::find($id);
        // $userAfterUpdate = HocVien::whereId($userUpdate->id);
       $userUpdate->update(
            $request->all()
        );
        // dd($userUpdate);
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
