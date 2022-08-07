<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\ThongTinChuyenLop;
use Illuminate\Http\Request;

class ApiDoiLopController extends Controller
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

     //cho client gửi thông tin chuyển lớp lên admin
    public function store(Request $request)
    {
        $thongTinChuyenLop =[
            'ho_ten' => $request->ho_ten,
            'email' => $request->email,
            'so_dien_thoai' => $request->so_dien_thoai,
            'oldClass' => $request->oldClass,
            'newClass'=>$request->newClass,
            'liDo' => $request->liDo,
        ] ;

        $t = ThongTinChuyenLop::create($thongTinChuyenLop);
        if($t){
            return response()->json([
                'status' => true,
                'heading' => 'đang kí chuyển lớp thành công'
            ],200);
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
