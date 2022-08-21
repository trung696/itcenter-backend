<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\ThongTinChuyenLop;
use App\HocVien;
use App\ClassModel;
use Illuminate\Http\Request;
use App\SessionUser;
use App\DangKy;


class ApiLopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $classes = ClassModel::all();

        return response()->json([
            'status' => true,
            'heading' => "Classes",
            'data' => $classes
        ], 200);
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
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $classDetail = ClassModel::find($id);
        $moi = [];
        $listDangKiOfClass = DangKy::where('id_lop_hoc',$id)->where('trang_thai','=',1)->get();
        // dd($listDangKiOfClass);
        foreach ($listDangKiOfClass as $listDangKiOfClassItem){
            $listDangKiOfClassItem['hoc_vien'] = $listDangKiOfClassItem->hocVien;
            // echo "<pre>";
            // printf($listDangKiOfClassItem);
        }
        $moi =  $listDangKiOfClass;
        return response()->json([
            'status' => true,
            'heading' => "Chi tiết lớp học",
            'data' =>  $moi
        ], 200);
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
