<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\ThongTinChuyenLop;
use App\HocVien;
use App\ClassModel;
use App\Course;
use Illuminate\Http\Request;
use App\SessionUser;
use App\DangKy;
use App\User;

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

        foreach ($classes as $classItem) {
            if ($classItem->course_id) {
                $course = Course::find($classItem->course_id);
                $classItem->price_of_class = optional($course)->price;
                $classItem->course_name = optional($course)->name;
            }
        }
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

        $classDetail->lecturer_name = User::where('id', $classDetail->lecturer_id)->first()->name;

        $moi = [];
        $listDangKiOfClass = DangKy::where('id_lop_hoc', $id)->where('trang_thai', '=', 1)->get();
        // dd($listDangKiOfClass);
        foreach ($listDangKiOfClass as $listDangKiOfClassItem) {
            $listDangKiOfClassItem['hoc_vien'] = $listDangKiOfClassItem->hocVien;
            // echo "<pre>";
            // printf($listDangKiOfClassItem);
        }
        $moi = $listDangKiOfClass;
        return response()->json([
            'status' => true,
            'heading' => "Chi tiết lớp học",
            'data' =>  $moi,
            'dataLop' => $classDetail
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
