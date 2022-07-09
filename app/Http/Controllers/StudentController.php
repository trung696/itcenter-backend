<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeacherRequest;
use Illuminate\Database\Eloquent\Model;
use App\Student;
use App\User;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;



class TeacherController extends Controller
{
    //
    private $v;

    public function __construct()
    {
        //        $this->middleware('auth');
        $this->v = [];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function hocVien(Request $request)
    {
        //
        $this->v['_title'] = 'Học viên';
        $this->v['routeIndexText'] = 'Học viên';
        $objStudent = new Student();
        $this->v['extParams'] = $request->all();
        $this->v['list'] = $objStudent->loadListWithPager($this->v['extParams']);
        $role = DB::table('roles as tb1')->get();
        $arrRole = [];
        foreach ($role as $key => $value) {
            $arrRole[$value->id] = $value->name;
        }
        $this->v['arrRole'] = $arrRole;
        return view('hocvien.danh-sach-hoc-vien', $this->v);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function thongTinHocVien($id)
    {
        $this->v['routeIndexText'] = 'Thông tin  viên';
        $this->v['_action'] = 'Edit';
        $this->v['_title'] = 'Thông tin học viên';
        //        $this->v['request'] = Session::pull('post_form_data')[0];
        $objStudent = new Student();
        $objItem = $objStudent->loadOne($id);
        $this->v['objItem'] = $objItem;
        if (empty($objItem)) {
            Session::push('errors', 'Không tồn tại danh mục này ' . $id);
            return redirect()->back();
        }
        // $objDonVi = new DonVi();
        // $this->v['don_vi'] = $objDonVi->loadListIdAndName(['trang_thai', 1]);

        return view('hocvien.danh-sach-hoc-vien', $this->v);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateThongTinHocVien($id, TeacherRequest $request)
    {
        //
        $method_route = 'route_BackEnd_Student_Detail';
        // $primary_table = 'bien_ban_ban_giao_ts';
        $objStudent = new Student();
        //Xử lý request
        $params = [
            'user_edit' => Auth::user()->id
        ];
        $params['cols'] = array_map(function ($item) {
            if ($item == '')
                $item = null;
            if (is_string($item))
                $item = trim($item);
            return $item;
        }, $request->post());
        unset($params['cols']['_token']);
        $objItem = $objStudent->loadOne($id);
        if (empty($objItem)) {
            Session::push('errors', 'Không tồn tại danh mục này ' . $id);
            return redirect()->route('route_BackEnd_Student_index');
        }
        $params['cols']['id'] = $id;
        $res = $objStudent->saveUpdate($params);

        if ($res == null) // chuyển trang vì trong session đã có sẵn câu thông báo lỗi rồi
        {
            Session::flash('success', 'Cập nhật bản ghi: ' . $objItem->id . ' thành công!');
            return redirect()->route('route_BackEnd_Student_index');
        } elseif ($res == 1) {
            //            SpxLogUserActivity(Auth::user()->id, 'edit', $primary_table, $id, 'edit');
            $request->session()->forget('post_form_data'); // xóa data post
            Session::flash('success', 'Cập nhật bản ghi: ' . $objItem->id . ' thành công!');

            return redirect()->route('route_BackEnd_Student_index');
        } else {

            Session::push('errors', 'Lỗi cập nhật cho bản ghi: ' . $res);
            Session::push('post_form_data', $this->v['request']);
            return redirect()->route($method_route, ['id' => $id]);
        }
    }
    public function deleteGiaoVien(Request $request, $id)
    {
        $deleteData = DB::table('teacher')->where('id', '=', $id)->delete();
        if ($deleteData) {
            Session::flash('success', 'Xóa dữ liệu thành công');
            return redirect(route('route_BackEnd_Student_index'));
        }
    }
}
