<?php

namespace App\Http\Controllers;

use App\Document;
use App\Course;
use App\User;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\DocumentRequest;
use Illuminate\Support\Facades\Session;
use Spipu\Html2Pdf\Html2Pdf;
use Dompdf\Dompdf;
use Dompdf\Options;

require_once __DIR__ . '/../../SLib/functions.php';

class DocumentController extends Controller
{
    private $v;

    public function __construct()
    {
        $this->v = [];
    }

    public function document(Request $request)
    {
        $this->v['_title'] = 'Tài liệu';
        $this->v['routeIndexText'] = 'Tài liệu';
        $objDocument = new Document();
        //Nhận dữ liệu lọc từ view
        $this->v['extParams'] = $request->all();
        $this->v['list'] = $objDocument->loadListWithPager($this->v['extParams']);
        // dd($this->v['list']);
        $objCourse = new Course();
        $this->v['course'] = $objCourse->loadListIdAndName(['status', 1]);
        $course = $this->v['course'];
        $arrCourse = [];
        foreach ($course as $index => $item) {
            $arrCourse[$item->id] = $item->name;
        }
        $this->v['arrCourse'] = $arrCourse;
        return view('tailieu.document', $this->v);
    }

    public function AddDocument(DocumentRequest $request)
    {
        $this->v['_title'] = 'tài liệu';
        $method_route = 'route_BackEnd_Document_Add';
        $this->v['_action'] = 'Add';
        $this->v['_title'] = 'Thêm tài liệu';
        $this->v['status'] = config('app.status_user');
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
            if($request->hasFile('file') && $request->file('file')->isValid()){
                $params['cols']['file'] = $this->uploadFile($request->file('file'));
            }

            // dd($params);
            unset($params['cols']['_token']);
            // dd($params['cols']);
            $objDocument = new Document();
            // dd($objDocument);
            $res = $objDocument->saveNew($params);
            // dd($res);
            if ($res == null) // chuyển trang vì trong session đã có sẵn câu thông báo lỗi rồi
            {
                Session::push('post_form_data', @isset($this->v['request']));
                return redirect()->route($method_route);
            } elseif ($res > 0) {
                $this->v['request'] = [];
                $request->session()->forget('post_form_data'); // xóa data post
                Session::flash('success', 'Thêm mới thành công tài liệu !');
                return redirect()->route('route_BackEnd_Document_List');

            } else {
                Session::push('errors', 'Lỗi thêm mới: ' . $res);
                Session::push('post_form_data', $this->v['request']);
                return redirect()->route($method_route);
            }

        } else {
            // không phải post
            $request->session()->forget($method_route); // hủy session nếu vào bằng sự kiện get
        }

        $objCourse = new Course();
        $this->v['course'] = $objCourse->loadListIdAndName(['status', 1]);
        return view('tailieu.add-document', $this->v);

    }

    public function documentDetail($id, Request $request){
        $this->v['routeIndexText'] = 'Chi tiết tài liệu';
        $this->v['_action'] = 'Edit';
        $this->v['_title'] = 'Chi tiết tài liệu';
        $objDocument = new Document();
        $objItem = $objDocument->loadOne($id);
        $this->v['document_id'] = $objDocument->loadListIdAndName(['status', 1]);
        $this->v['objItem'] = $objItem;
        if (empty($objItem)) {
            Session::push('errors', 'Không tồn tại danh mục này ' . $id);
            return redirect()->back();
        }
        $this->v['extParams'] = $request->all();
        $this->v['status'] = config('app.status_user');
        $objCourse = new  Course();
        $this->v['course'] = $objCourse->loadListIdAndName(['status', 1]);

        return view('tailieu.detail-document',$this->v);
    }
    private function uploadFile($file)
    {
        $fileName = time().'_'.$file->getClientOriginalName();
        return $file->storeAs('hinh_anh_khoa_hoc', $fileName, 'public');
    }

    public function updateDocument($id, DocumentRequest $request){

        $method_route = 'route_BackEnd_Document_Detail';
        $modelDocument = new Document();
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
        if($request->hasFile('file') && $request->file('file')->isValid()){
            $params['cols']['file'] = $this->uploadFile($request->file('file'));
        }
        // dd($params);
        unset($params['cols']['_token']);

        $objItem = $modelDocument->loadOne($id);
        if (empty($objItem)) {
            Session::push('errors', 'Không tồn tại người dùng này ' . $id);
            return redirect()->route('route_BackEnd_NguoiDung_index');
        }
        $params['cols']['id'] = $id;
        $res = $modelDocument->saveUpdate($params);

        // dd($res);
        if ($res == null) // chuyển trang vì trong session đã có sẵn câu thông báo lỗi rồi
        {
            Session::push('post_form_data', @isset($this->v['request']));
            return redirect()->route($method_route, ['id' => $id]);
        } elseif ($res == 1) {
//            SpxLogUserActivity(Auth::user()->id, 'edit', $primary_table, $id, 'edit');
            $request->session()->forget('post_form_data'); // xóa data post
            Session::flash('success', 'Cập nhật thành công thông tin khoá học!');
            return redirect()->route('route_BackEnd_Document_List');
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
	$deleteData = DB::table('document')->where('id', '=', $id)->delete();
	
	//Kiểm tra lệnh delete để trả về một thông báo
	if ($deleteData) {
		Session::flash('success', 'Xóa học sinh thành công!');
	}else {                        
		Session::flash('error', 'Xóa thất bại!');
	}
	
	//Thực hiện chuyển trang
	return redirect()->route('route_BackEnd_Document_List');
}
    


}
//