<?php

namespace App\Http\Controllers\Api;

use App\ClassModel;
use App\Course;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiCourceController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $listClass = Course::find($id)->classRoom;
        if (isset($listClass) &&  count($listClass)) {
            foreach ($listClass as $listClassItem) {
                //lấy danh sách các đăng kí đã thanh toán tiền để cập nhập số chỗ trong lớp
                $countStudentInClass = count($listClassItem->dangKi->where('trang_thai', '=', 1));
            }
            return response()->json([
                'status' => true,
                'heading' => 'Lấy thành công danh sách class của course',
                'data' => $listClass,
            ], 200);
        }
        return response()->json([
            'status' => true,
            'heading' => 'Course này chưa có class nào',
            'data' => $listClass = Course::find($id),
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
