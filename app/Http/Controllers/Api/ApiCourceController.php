<?php

namespace App\Http\Controllers\Api;

use App\ClassModel;
use App\Course;
use App\Http\Controllers\Controller;
use App\User;
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
        $course = Course::all();
        return response()->json([
            'status' => true,
            'heading' => 'success',
            'data' => $course,
        ], 200);
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
        $listClassNew = [];
        $today = date("Y-m-d");

        if (isset($listClass) &&  count($listClass)) {
            foreach ($listClass as $key => $listClassItem) {
                if(strtotime($today) < strtotime($listClassItem->end_date)){
                    //lấy danh sách các đăng kí đã thanh toán tiền để cập nhập số chỗ trong lớp
                    $countStudentInClass = count($listClassItem->dangKi->where('trang_thai', '=', 1));
                    $listClassNew[] = $listClassItem;
                }                
               $listClassItem->lecturer_id = User::where('id', $listClassItem->lecturer_id,)->first()->name;
            }
            
            // return response()->json([
            //     'status' => true,
            //     'heading' => 'Lấy thành công danh sách class của course',
            //     'data' => $listClass,
            // ], 200);
            return response()->json([
                'status' => true,
                'heading' => 'Lấy thành công danh sách class của course',
                'data' => $listClassNew,
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

    public function searchCourse($key){
        $result = Course::where('name', 'LIKE', '%'. $key. '%')->get();
        if(count($result)){
            return response()->json([
                'status' => true,
                'heading' => 'Bản ghi tìm thấy',
                'data' => $result
            ],200);
        }
        return response()->json([
            'status' => true,
            'heading' => 'Không tìm thấy bản ghi nào',
        ],200);
    }
}
