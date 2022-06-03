<?php

namespace App\Http\Controllers;

use App\DanhMucKhoaHoc;
use App\User;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\DanhMucKhoaHocRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Spipu\Html2Pdf\Html2Pdf;
use Dompdf\Dompdf;
use Dompdf\Options;

//require_once __DIR__ . '/../../SLib/functions.php';

class DanhMucKhoaHocController extends Controller
{
    private $v;
    
    public function __construct()
    {
        $this->v = [];
    }
    
    public function danhMucKhoaHoc(Request $request){
        $this->v['_title'] = 'Danh mục khoá học';
        $this->v['routeIndexText'] = 'Danh mục khoá học';
        $objDanhMucKhoaHoc = new DanhMucKhoaHoc();
        //Nhận dữ liệu lọc từ view
        $this->v['extParams'] = $request->all();
        $this->v['id_user'] = Auth::id();
        $this->v['list'] = $objDanhMucKhoaHoc->loadListWithPager($this->v['extParams']);

        return view('khoahoc.admin.danh-muc', $this->v);
    }
    public function themDanhMucKhoaHoc(DanhMucKhoaHocRequest $request){
        $this->v['routeIndexText'] = 'Danh mục khoa học';
        $method_route = 'route_BackEnd_DanhMucKhoaHoc_Add';
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
            unset($params['cols']['_token']);
            $objDanhMucKhoaHoc = new DanhMucKhoaHoc();
            $res = $objDanhMucKhoaHoc->saveNew($params);

            if($res == null){
                Session::push('post_form_data', $this->v['request']);
                return redirect()->route($method_route);
            }elseif ($res >0){
                $this->v['request'] = [];
                $request->session()->forget('post_from_data');
                Session::flash('success', 'Thêm mới thành công danh mục khoá học');
                return redirect()->route('route_BackEnd_DanhMucKhoaHoc_List');
            }else{
                Session::push('errors', 'Lỗi thêm mới' .$res);
                Session::push('post_form_data', $this->v['request']);
                return redirect()->route($method_route);
            }
        }

        return view('khoahoc.admin.them-danh-muc', $this->v);
    }
    public function  chitetDanhMucKhoaHoc($id, Request $request){
        $this->v['routeIndexText'] = 'Chi tiết danh mục khoá học';
        $this->v['_action'] = 'Edit';
        $this->v['_title'] = 'Chi tiết danh mục khoá học';
        $objDanhMucKhoaHoc = new DanhMucKhoaHoc();
        $objItem = $objDanhMucKhoaHoc->loadOne($id);
        $this->v['extParams'] = $request->all();
        $this->v['objItem'] = $objItem;
        if (empty($objItem)) {
            Session::push('errors', 'Không tồn tại danh mục này ' . $id);
            return redirect()->back();
        }
        return view('khoahoc.admin.update-danh-muc', $this->v);

    }

    public function updateDanhMucKhoc($id, DanhMucKhoaHocRequest $request){

        $method_route = 'route_BackEnd_DanhMucKhoaHoc_Detail';
        $modelDanhMuc = new DanhMucKhoaHoc();
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

            return redirect()->route('route_BackEnd_DanhMucKhoaHoc_List');
        } else {

            Session::push('errors', 'Lỗi cập nhật cho bản ghi: ' . $res);
            Session::push('post_form_data', $this->v['request']);
            return redirect()->route($method_route, ['id' => $id]);
        }
    }

}
//