<?php

namespace App\Http\Controllers;

use App\giang_vien;
use App\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
   public function index(){
    $this->v['routeIndexText'] = 'Danh sách giangr vieen';
    // $method_route = 'route_BackEnd_NguoiDung_Add';
    // $this->v['request'] = Session::pull('post_form_data')[0];
    $this->v['_action'] = 'Add';
    $this->v['_title'] = 'Thêm Người dùng';
    $teachers = Teacher::all();
    return view('giangvien.admin.index',$this->v,compact('teachers'));
   }
}
