<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\ThongTinChuyenLop;
use App\HocVien;
use Illuminate\Http\Request;
use App\SessionUser;
use App\DangKy;


class ApiLichSuDoiLopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $tokenUp = $request->bearerToken();
        $id_user = SessionUser::where('token', $tokenUp)->first()->user_id;
        $email_user = HocVien::where('id', $id_user)->first()->email;

        $data = [];
        $data_doi_lop = ThongTinChuyenLop::all()->toArray();

        for ($i = 0; $i < count($data_doi_lop); $i++) {
            $item = $data_doi_lop[$i];

            if ($item['email'] === $email_user) {
                array_push($data, $item);
            }
        }

        return response()->json([
            'status' => true,
            'heading' => "History of changing classes",
            'data' => $data
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
