<?php

namespace App\Http\Controllers;

use App\Course;
use App\CourseCategory;
use App\CentralFacility;
use App\ClassModel;
use App\User;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\CourseRequest;
use Illuminate\Support\Facades\Session;
use Spipu\Html2Pdf\Html2Pdf;
use Dompdf\Dompdf;
use Dompdf\Options;

require_once __DIR__ . '/../../SLib/functions.php';

class CourseController extends Controller
{
    private $v;

    public function __construct()
    {
        $this->v = [];
    }

    public function course(Request $request)
    {
        $this->v['_title'] = 'Khoá học';
        $this->v['routeIndexText'] = 'Khoá học';
        $objCourse = new Course();
        //Nhận dữ liệu lọc từ view
        $this->v['extParams'] = $request->all();
        $this->v['list'] = $objCourse->loadListWithPager($this->v['extParams']);
        $objCourseCategory = new CourseCategory();
        $this->v['course_category'] = $objCourseCategory->loadListIdAndName(['status', 1]);
        $categories = $this->v['course_category'];
        // dd($categories);
        $arrCategory = [];
        foreach ($categories as $index => $item) {
            $arrCategory[$item->id] = $item->name;
            // dd($arrCategory[$item->id]);
        }
        $this->v['arrCategory'] = $arrCategory;
        // dd($this->v['arrCategory']);
        return view('khoahoc.admin.course', $this->v);
    }

    public function AddCourse(CourseRequest $request)
    {
        $this->v['_title'] = 'Khoá học';
        $method_route = 'route_BackEnd_Course_Add';
        $this->v['_action'] = 'Add';
        $this->v['_title'] = 'Thêm khoá học';
        $this->v['trang_thai'] = config('app.status_user');
        
        if ($request->isMethod('post')) {
            if (Session::has($method_route)) {
                return redirect()->route($method_route); // không cho F5, chỉ có thể post 1 lần
            } else
                Session::push($method_route, 1); // bỏ vào session để chống F5

            $params = [
                'user_add' => Auth::user()->id
            ];
            $params['cols'] = array_map(function ($item) {
                if ($item == '')
                    $item = null;
                if (is_string($item))
                    $item = trim($item);
                return $item;
            }, $request->post());
            if($request->hasFile('image') && $request->file('image')->isValid()){
                $params['cols']['image'] = $this->uploadFile($request->file('image'));
            }

            // dd($params['cols']);
            unset($params['cols']['_token']);
            $objKhoaHoc = new Course();
            $res = $objKhoaHoc->saveNew($params);
            // dd($res);
            if ($res == null) // chuyển trang vì trong session đã có sẵn câu thông báo lỗi rồi
            {
                Session::push('post_form_data', $this->v['request']);
                return redirect()->route($method_route);
            } elseif ($res > 0) {
                $this->v['request'] = [];
                $request->session()->forget('post_form_data'); // xóa data post
                Session::flash('success', 'Thêm mới thành công khoá học !');
                return redirect()->route('route_BackEnd_Course_List');

            } else {
                Session::push('errors', 'Lỗi thêm mới: ' . $res);
                Session::push('post_form_data', $this->v['request']);
                return redirect()->route($method_route);
            }

        } else {
            // không phải post
            $request->session()->forget($method_route); // hủy session nếu vào bằng sự kiện get
        }

        $objCourseCategory = new  CourseCategory();
        $this->v['course_categories'] = $objCourseCategory->loadListIdAndName(['status', 1]);
        return view('khoahoc.admin.add-course', $this->v);

    }

    public function courseDetail($id, Request $request){
        $this->v['routeIndexText'] = 'Chi tiết khoá học';
        $this->v['_action'] = 'Edit';
        $this->v['_title'] = 'Chi tiết khoá học';
        $objCourse = new Course();
        $objItem = $objCourse->loadOne($id);
        $this->v['course_id'] = $objCourse->loadListIdAndName(['status', 1]);
        $this->v['objItem'] = $objItem;
        $course = $this->v['course_id'];
        // dd($user);
        $arrCourse = [];
        foreach ($course as $index => $item) {
            // dd($item);
            $arrCourse[$item->id] = $item->name;
            
        }
        $this->v['arrCourse'] = $arrCourse;
        if (empty($objItem)) {
            Session::push('errors', 'Không tồn tại danh mục này ' . $id);
            return redirect()->back();
        }
        $this->v['extParams'] = $request->all();
        $this->v['status'] = config('app.status_user');
        $objCourseCategory = new  CourseCategory();
        $this->v['course_category'] = $objCourseCategory->loadListIdAndName(['status', 1]);

        if (isset($this->v['extParams']['search_ngay_khai_giang'])) {
            $ngaythem = explode(' - ', $this->v['extParams']['search_ngay_khai_giang']);
            if (count($ngaythem) != 2) {
                Session::flash('error', 'Ngày khai giảng không hợp lệ');
                return redirect()->route($this->routeIndex);
            }
            $datetime = array_map('convertDateToSql', $ngaythem);
            $datetime[0] = $datetime[0] . ' 00:00:00';
            $datetime[1] = $datetime[1] . ' 23:59:59';
            $this->v['extParams']['search_ngay_khai_giang_array'] = $datetime;
        }
        $objClassModel = new ClassModel();
        $this->v['lists'] = $objClassModel->loadListWithPager($this->v['extParams'], $id);
        $objItemClass = $objClassModel->loadOne($id);
        $this->v['objItemClass'] = $objClassModel;
        $objUser = new User();
        $this->v['user'] = $objUser->loadListIdAndName(['status', 1]);
        $user = $this->v['user'];
        // dd($user);
        $arrUser = [];
        foreach ($user as $index => $item) {
            // dd($item);
            $arrUser[$item->id] = $item->name;
            
        }
        $this->v['arrUser'] = $arrUser;
        // dd( $this->v['arrUser']);

        $objCentralFacility = new CentralFacility();
        $this->v['centralFacility'] = $objCentralFacility->loadListIdAndName();
        $centralFacility = $this->v['centralFacility'];
        // dd($user);
        $arrFacility = [];
        foreach ($centralFacility as $index => $item) {
            // dd($item);
            $arrFacility[$item->id] = $item->name;
            
        }
        $this->v['arrFacility'] = $arrFacility;
        // dd( $this->v['arrUser']);
        
        return view('khoahoc.admin.course-detail',$this->v);
    }
    private function uploadFile($file)
    {
        $fileName = time().'_'.$file->getClientOriginalName();
        return $file->storeAs('hinh_anh_khoa_hoc', $fileName, 'public');
    }

    public function updateCourse($id, CourseRequest $request){

        $method_route = 'route_BackEnd_Course_Detail';
        $modelCourse = new Course();
        $params = [
            'user_edit' => Auth::user()->id
        ];
        $params['cols'] = array_map(function ($item) {
            if($item == '')
                $item = null;
            if(is_string($item))
                $item = trim($item);
            return $item;
        }, $request->post());
        if($request->hasFile('image') && $request->file('image')->isValid()){
            $params['cols']['image'] = $this->uploadFile($request->file('image'));
        }
        unset($params['cols']['_token']);

        $objItem = $modelCourse->loadOne($id);
        if (empty($objItem)) {
            Session::push('errors', 'Không tồn tại người dùng này ' . $id);
            return redirect()->route('route_BackEnd_NguoiDung_index');
        }
        $params['cols']['id'] = $id;
        $res = $modelCourse->saveUpdate($params);
        if ($res == null) // chuyển trang vì trong session đã có sẵn câu thông báo lỗi rồi
        {
            Session::push('post_form_data', $this->v['request']);
            return redirect()->route($method_route, ['id' => $id]);
        } elseif ($res == 1) {
//            SpxLogUserActivity(Auth::user()->id, 'edit', $primary_table, $id, 'edit');
            $request->session()->forget('post_form_data'); // xóa data post
            Session::flash('success', 'Cập nhật thành công thông tin khoá học!');
            return redirect()->route('route_BackEnd_Course_List');
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
	$deleteData = DB::table('course')->where('id', '=', $id)->delete();
	
	//Kiểm tra lệnh delete để trả về một thông báo
	if ($deleteData) {
		Session::flash('success', 'Xóa học sinh thành công!');
	}else {                        
		Session::flash('error', 'Xóa thất bại!');
	}
	
	//Thực hiện chuyển trang
	return redirect()->route('route_BackEnd_Course_List');
}
    // public function fontendDanhSachKhoaHoc($id, Request $request){
    //     $this->v['extParams'] = $request->all();
    //     $objKhoaHoc = new KhoaHoc();
    //     $this->v['lists'] = $objKhoaHoc->loadListIdWithPager($this->v['extParams'], $id);
    //     $objDanhMuc = new DanhMucKhoaHoc();
    //     $this->v['listCategory'] = $objCategory->loadOne($id);
    //     return view('khoahoc.client.fr-khoa-hoc', $this->v);
    // }


}
//