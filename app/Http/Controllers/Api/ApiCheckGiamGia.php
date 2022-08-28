<?php

namespace App\Http\Controllers\Api;

use App\ChienDich;
use App\Http\Controllers\Controller;
use App\MaChienDich;
use Illuminate\Http\Request;

class ApiCheckGiamGia extends Controller
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
        $ma_khuyen_mai = $request->ma_khuyen_mai;
        // dd($ma_khuyen_mai);
        if (isset($ma_khuyen_mai)) {
            $objCheckMa = new MaChienDich();
            $checkMa = $objCheckMa->loadCheckName($ma_khuyen_mai);
            // dd($checkMa);
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
            if ($checkGiam->course_id == 0 || $checkGiam->course_id == $request->id_khoa_hoc) {
                $dung_khoa = 1;
            } else {
                $dung_khoa = 0;
            }

            $now = date('Y-m-d');
            $startDate = date('Y-m-d', strtotime($checkGiam->ngay_bat_dau));
            $endDate = date('Y-m-d', strtotime($checkGiam->ngay_ket_thuc));
            if (($now >= $startDate) && ($now <= $endDate)) {
                $flag = 1;
            } else {
                $flag = 2;
            }
            // dd($flag,$hoat_dong,$trang_thai , $dung_khoa );

            if ($flag == 1 && $hoat_dong == 1 && $trang_thai == 0 && $dung_khoa == 1) {
                // $arrDangKy['gia_tien'] = $gia->price - ($gia->price * $checkGiam->phan_tram_giam / 100);
                return response()->json([
                    'status' => true,
                    'heading' => 'Đúng mã giảm giá cho khóa này, mã này chưa dử dụng',
                    'data'=> $checkGiam 
                ], 200);
                // $apma = $checkGiam->phan_tram_giam;
            } elseif ($dung_khoa == 0) {
                return response()->json([
                    'status' => false,
                    'heading' => 'Mã giảm giá không dành cho khóa này',
                    'data'=> null 
                ], 404);
            } else {
                return response()->json([
                    'status' => false,
                    'heading' => 'Mã giảm giá không hợp lệ',
                    'data'=> null 
                ], 404);
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
