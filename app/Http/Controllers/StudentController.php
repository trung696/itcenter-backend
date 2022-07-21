<?php

namespace App\Http\Controllers;

use App\Role;
use App\Student;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class TeacherController extends Controller
{
    public function index()
    {
        $this->v['routeIndexText'] = 'Danh sách học sinh';
        // $method_route = 'route_BackEnd_NguoiDung_Add';
        // $this->v['request'] = Session::pull('post_form_data')[0];
        $this->v['_action'] = 'Danh sách';
        $this->v['_title'] = 'Thêm Người dùng';
        $teachers = Student::all();
        return view('hocsinh.admin.index', $this->v, compact('students'));
    }

    public function edit($id)
    {
        $this->v['routeIndexText'] = 'Cập nhập thông tin học sinh';
        // $this->v['request'] = Session::pull('post_form_data')[0];
        $this->v['_action'] = 'Edit';
        $this->v['_title'] = 'Sửa Học sinh';
        $teacherEdit = Student::find($id);
        // dd($teacherEdit);
        return view('hocsinh.admin.edit', $this->v, compact('studentEdit'));
    }

    public function update(Request $request, $id)
    {
        try {
            // dd($request->all());
            DB::beginTransaction();
            Student::find($id)->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'address' => $request->address,
                'sex' => $request->sex,
                'phone' => $request->phone,
                'avatar' => 'https://pngimg.com/image/62425'
            ]);

            DB::commit();
            session()->flash('success', 'Sửa thành công tài khoản học sinh ');
            return redirect()->route('route_BackEnd_teacher_list');
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('message: ' . $exception->getMessage() . 'line:' . $exception->getLine());
        }
    }
}
