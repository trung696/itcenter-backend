<?php

namespace App\Http\Controllers;

use App\giang_vien;
use App\Role;
use App\Teacher;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class TeacherController extends Controller
{
   public function index()
   {
      $this->v['routeIndexText'] = 'Danh sách giảng viên';
      // $method_route = 'route_BackEnd_NguoiDung_Add';
      // $this->v['request'] = Session::pull('post_form_data')[0];
      $this->v['_action'] = 'Danh sách';
      $this->v['_title'] = 'Thêm Người dùng';
      $teachers = Teacher::all();
      return view('giangvien.admin.index', $this->v, compact('teachers'));
   }

   public function edit($id)
   {
      $this->v['routeIndexText'] = 'Cập nhập thông tin giảng viên';
      // $this->v['request'] = Session::pull('post_form_data')[0];
      $this->v['_action'] = 'Edit';
      $this->v['_title'] = 'Sửa Giảng Viên';
      $teacherEdit = Teacher::find($id);
      // dd($teacherEdit);
      return view('giangvien.admin.edit', $this->v, compact('teacherEdit'));
   }



   public function update(Request $request, $id)
   {
      try {
         // dd($request->all());
         DB::beginTransaction();
         Teacher::find($id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'address' => $request->address,
            'sex' => $request->sex,
            'phone' => $request->phone,
            'avatar' => 'https://pngimg.com/image/62425'
         ]);

         DB::commit();
         session()->flash('success', 'Sửa thành công tài khoản giảng viên ');
         return redirect()->route('route_BackEnd_teacher_list');
      } catch (\Exception $exception) {
         DB::rollBack();
         Log::error('message: ' . $exception->getMessage() . 'line:' . $exception->getLine());
      }
   }
}
