<?php

namespace App\Http\Controllers\Api;

use App\ClassModel;
use App\Course;
use App\CourseCategory;
use App\DangKy;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Middleware\checkTokenSend;
use App\SessionUser;

class ApiGetKhoaHocOfUser extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request  $request)
    {
        $tokenUp = $request->bearerToken();
        $id_user = SessionUser::where('token', $tokenUp)->first()->user_id;
        $listDangKiOfUser = DangKy::where('id_hoc_vien', $id_user)->get()->toArray();

        $data = [];
        if ($listDangKiOfUser) {            
            foreach ($listDangKiOfUser as $listDangKiOfUserItem){
                $listDangKiOfUserItem['id_lop_hoc'] = ClassModel::where('id', $listDangKiOfUserItem['id_lop_hoc'])->first()->name;
                array_push($data, $listDangKiOfUserItem);
            }
            return response()->json([
                'status' => true,
                'heading' => "Danh sách khóa học đăng kí",
                'data' => $data
            ], 200);
        }
        return response()->json([
            'status' => true,
            'heading' => "Bạn chua đang kí lớp học nào",
        ], 200);
        // }
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
