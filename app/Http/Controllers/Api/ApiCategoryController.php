<?php

namespace App\Http\Controllers\Api;

use App\CourseCategory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PHPUnit\Framework\Constraint\Count;

class ApiCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allCate=CourseCategory::all();
        return response()->json([
            'status' => true,
            'heading' => "Tất cả danh mục khóa học",
            'data' => $allCate
        ],200);
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
    public function show(CourseCategory $courseCategory, $id)
    {
        $courseCategoryById = CourseCategory::where('id',$id)->first();
        dd($courseCategoryById->course);
        // $lopInCategory = $courseCategory->course;
        // return response()->json([
        //     'status' => true,
        //     'heading' => "full lop hoc cua 1 khoa hoc",
        //     'data' => $lopInCategory
        // ]);
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
