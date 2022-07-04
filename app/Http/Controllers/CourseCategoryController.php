<?php

namespace App\Http\Controllers;

use App\User;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\DanhMucKhoaHocRequest;
use App\Http\Requests\CourseCateGoryRequest;
use App\CourseCategory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Spipu\Html2Pdf\Html2Pdf;
use Dompdf\Dompdf;
use Dompdf\Options;
// use App\CourseCategory;
class CourseCategoryController extends Controller
{
    private $v;
    
    public function __construct()
    {
        $this->v = [];
    }
    
    public function courseCategory(Request $request){
        $this->v['_title'] = 'Danh mục khoá học';
        $this->v['routeIndexText'] = 'Danh mục khoá học';
        $objDanhMucKhoaHoc = new CourseCategory();
        //Nhận dữ liệu lọc từ view
        $this->v['extParams'] = $request->all();
        // dd($this->v['extParams']);
        $this->v['id_user'] = Auth::id();
        $this->v['list'] = $objDanhMucKhoaHoc->loadListWithPager($this->v['extParams']);
        // dd($this->v['list']);

        return view('khoahoc.admin.category', $this->v);
    }
    //

    public function AddCourseCategory(CourseCategoryRequest $request){
        $this->v['routeIndexText'] = 'Danh mục khoa học';
        $method_route = 'route_BackEnd_CourseCategory_Add';
        $this->v['_action'] = 'Add';
        $this->v['_title'] = 'Thêm danh mục khoá học';
        $this->v['trang_thai'] = config('app.status_danh_muc');
        if($request->isMethod('post')){
            $params = [
                'danhmuc_add' => Auth::user()->id
            ];
            $params['cols'] = array_map(function ($item) {
                if ($item == '')
                    $item = null;
                if (is_string($item))
                    $item = trim($item);
                return $item;
            }, $request->post());
            // dd($params['cols']);
            unset($params['cols']['_token']);
            $objDanhMucKhoaHoc = new CourseCategory();
            $res = $objDanhMucKhoaHoc->saveNew($params);
            // dd($res);

            if($res == null){
                Session::push('post_form_data', $this->v['request']);
                return redirect()->route($method_route);
            }elseif ($res >0){
                $this->v['request'] = [];
                $request->session()->forget('post_from_data');
                Session::flash('success', 'Thêm mới thành công danh mục khoá học');
                return redirect()->route('route_BackEnd_CourseCategory_List');
            }else{
                Session::push('errors', 'Lỗi thêm mới' .$res);
                Session::push('post_form_data', $this->v['request']);
                return redirect()->route($method_route);
            }
        }

        return view('khoahoc.admin.add-category', $this->v);
    }

    public function  courseCategoryDetail($id, Request $request){
        $this->v['routeIndexText'] = 'Chi tiết danh mục khoá học';
        $this->v['_action'] = 'Edit';
        $this->v['_title'] = 'Chi tiết danh mục khoá học';
        $objDanhMucKhoaHoc = new CourseCategory();
        $objItem = $objDanhMucKhoaHoc->loadOne($id);
        $this->v['extParams'] = $request->all();
        $this->v['objItem'] = $objItem;
        if (empty($objItem)) {
            Session::push('errors', 'Không tồn tại danh mục này ' . $id);
            return redirect()->back();
        }
        return view('khoahoc.admin.update-course-category', $this->v);

    }

    public function updateCourseCategory($id, CourseCategoryRequest $request){

        $method_route = 'route_BackEnd_CourseCategory_Detail';
        $modelDanhMuc = new CourseCategory();
        $params = [
            'danhmuc_edit' => Auth::user()->id
        ];
        $params['cols'] = array_map(function ($item) {
            if($item == '')
                $item = null;
            if(is_string($item))
                $item = trim($item);
            return $item;
        }, $request->post());

        // dd($params['cols']);
        unset($params['cols']['_token']);
        $objItem = $modelDanhMuc->loadOne($id);
        if (empty($objItem)) {
            Session::push('errors', 'Không tồn tại người dùng này ' . $id);
            return redirect()->route('route_BackEnd_NguoiDung_index');
        }
        $params['cols']['id'] = $id;
        $res = $modelDanhMuc->saveUpdate($params);
        if ($res == null) // chuyển trang vì trong session đã có sẵn câu thông báo lỗi rồi
        {
            Session::push('post_form_data', $this->v['request']);
            return redirect()->route($method_route, ['id' => $id]);
        } elseif ($res == 1) {
//            SpxLogUserActivity(Auth::user()->id, 'edit', $primary_table, $id, 'edit');
            $request->session()->forget('post_form_data'); // xóa data post
            Session::flash('success', 'Cập nhật thành công danh mục khoá học');

            return redirect()->route('route_BackEnd_CourseCategory_List');
        } else {

            Session::push('errors', 'Lỗi cập nhật cho bản ghi: ' . $res);
            Session::push('post_form_data', $this->v['request']);
            return redirect()->route($method_route, ['id' => $id]);
        }
    }

    public function destroy($id)
{
	//Xoa hoc sinh
	//Thực hiện câu lệnh xóa với giá trị id = $id trả về
	$deleteData = DB::table('course_categories')->where('id', '=', $id)->delete();
	
	//Kiểm tra lệnh delete để trả về một thông báo
	if ($deleteData) {
		Session::flash('success', 'Xóa học sinh thành công!');
	}else {                        
		Session::flash('error', 'Xóa thất bại!');
	}
	
	//Thực hiện chuyển trang
	return redirect()->route('route_BackEnd_CourseCategory_List');
}
}
