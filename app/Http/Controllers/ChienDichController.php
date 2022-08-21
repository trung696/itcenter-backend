<?php

namespace App\Http\Controllers;

use App\ChienDich;
use App\Course;
use App\Http\Requests\ChienDichRequest;
use App\MaChienDich;
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

class ChienDichController extends Controller
{
    private $v;

    public function __construct()
    {
        $this->v = [];
    }

    public function listChienDich(Request $request)
    {
        $this->v['_title'] = 'Chiến Dịch Khuyến Mại';
        $this->v['routeIndexText'] = 'Chiến Dịch Khuyến Mại';
        $objChienDich = new ChienDich();
        //Nhận dữ liệu lọc từ view
        $this->v['extParams'] = $request->all();
        $this->v['list'] = $objChienDich->loadListWithPager($this->v['extParams']);
        $objCourse = new Course();
        $this->v['course'] = $objCourse->loadListIdAndName(['status', 1]);
        $course = $this->v['course'];
        // dd($course);
        $arrCourse = [0,];
        foreach ($course as $index => $item) {

            $arrCourse[$item->id] = $item->name;
            // dd($arrCourse[$item->id]);
        }
        foreach ($arrCourse as $index => $item) {
            $arrCourse[0] = "Tất cả khóa học";
        }
        $this->v['arrCourse'] = $arrCourse;
        // dd($arrCourse);
        return view('khuyenmai.chien-dich', $this->v);
    }
    public function themChienDich(ChienDichRequest $request)
    {
        $this->v['routeIndexText'] = 'Chiến Dịch Khuyến Mại ';
        $method_route = 'route_BackEnd_ChienDich_Add';
        $this->v['_action'] = 'Add';
        $this->v['_title'] = 'Thêm Chiến Dịch Khuyến Mại';
        if ($request->isMethod('post')) {
            $params = [
                'chiendich_add' => Auth::user()->id
            ];
            $params['cols'] = array_map(function ($item) {
                if ($item == '')
                    $item = null;
                if (is_string($item))
                    $item = trim($item);
                return $item;
            }, $request->post());
            unset($params['cols']['_token']);
            $objChienDich = new ChienDich();
            $res = $objChienDich->saveNew($params);


            if ($res == null) {
                Session::push('post_form_data', $this->v['request']);
                return redirect()->route($method_route);
            } elseif ($res > 0) {
                $this->v['request'] = [];
                $request->session()->forget('post_from_data');
                Session::flash('success', 'Thêm mới thành công chiến dịch khuyến mại');
                return redirect()->route('route_BackEnd_ChienDich_index');
            } else {
                Session::push('errors', 'Lỗi thêm mới' . $res);
                Session::push('post_form_data', $this->v['request']);
                return redirect()->route($method_route);
            }
        }
        $objCourse = new  Course();
        $this->v['course'] = $objCourse->loadListIdAndName(['status', 1]);


        return view('khuyenmai.them-chien-dich', $this->v);
    }
    public function  chitetChienDich($id, Request $request)
    {
        $this->v['routeIndexText'] = 'Chi tiết chiến dịch khuyến mại';
        $this->v['_action'] = 'Edit';
        $this->v['_title'] = 'Chi tiết chiến dịch khuyến mại';
        $objChienDich = new ChienDich();
        $this->v['trang_thai'] = config('app.status_chien_dich');
        // dd(config('app.status_chien_dich'));
        $objItem = $objChienDich->loadOne($id);
        $this->v['extParams'] = $request->all();
        $this->v['objItem'] = $objItem;
        $objMaChienDich = new MaChienDich();
        $this->v['objItemMaChienDich'] = $objMaChienDich->loadListWithPager($this->v['extParams'], $id);
        if (empty($objItem)) {
            Session::push('errors', 'Không tồn tại danh mục này ' . $id);
            return redirect()->back();
        }
        return view('khuyenmai.chi-tiet-chien-dich', $this->v);
    }

    public function updateChienDich($id, Request $request)
    {

        $method_route = 'route_BackEnd_ChienDich_index';
        $objChienDich = new ChienDich();
        $params = [
            'chiendich_edit' => Auth::user()->id
        ];
        $params['cols'] = array_map(function ($item) {
            if ($item == '')
                $item = null;
            if (is_string($item))
                $item = trim($item);
            return $item;
        }, $request->post());

        unset($params['cols']['_token']);
        $objItem = $objChienDich->loadOne($id);
        if (empty($objItem)) {
            Session::push('errors', 'Không tồn tại người dùng này ' . $id);
            return redirect()->route('route_BackEnd_NguoiDung_index');
        }
        $params['cols']['id'] = $id;
        $res = $objChienDich->saveUpdate($params);
        if ($res == null) // chuyển trang vì trong session đã có sẵn câu thông báo lỗi rồi
        {
            Session::push('post_form_data', $this->v['request']);
            return redirect()->route($method_route, ['id' => $id]);
        } elseif ($res == 1) {
            //            SpxLogUserActivity(Auth::user()->id, 'edit', $primary_table, $id, 'edit');
            $request->session()->forget('post_form_data'); // xóa data post
            Session::flash('success', 'Cập nhật thành công thông tin chien dịch');

            return redirect()->route('route_BackEnd_ChienDich_index');
        } else {

            Session::push('errors', 'Lỗi cập nhật cho bản ghi: ' . $res);
            Session::push('post_form_data', $this->v['request']);
            return redirect()->route($method_route, ['id' => $id]);
        }
    }
    public function dungChiendich($id, Request $request)
    {
        $method_route = 'route_BackEnd_ChienDich_index';
        $objChienDich = new ChienDich();
        $params = [
            'chiendich_delete' => Auth::user()->id
        ];
        $params['cols'] = array_map(function ($item) {
            if ($item == '')
                $item = null;
            if (is_string($item))
                $item = trim($item);
            return $item;
        }, $request->post());

        unset($params['cols']['_token']);
        $objItem = $objChienDich->loadOne($id);
        if (empty($objItem)) {
            Session::push('errors', 'Không tồn tại người dùng này ' . $id);
            return redirect()->route('route_BackEnd_NguoiDung_index');
        }
        $params['cols']['id'] = $id;
        $res = $objChienDich->saveDelete($params);
        if ($res == null) // chuyển trang vì trong session đã có sẵn câu thông báo lỗi rồi
        {
            Session::push('post_form_data', $this->v['request']);
            return redirect()->route($method_route, ['id' => $id]);
        } elseif ($res == 1) {
            //            SpxLogUserActivity(Auth::user()->id, 'edit', $primary_table, $id, 'edit');
            $request->session()->forget('post_form_data'); // xóa data post
            Session::flash('success', 'Xoá thành công thông tin chien dịch');

            return redirect()->route('route_BackEnd_ChienDich_index');
        } else {

            Session::push('errors', 'Lỗi cập nhật cho bản ghi: ' . $res);
            Session::push('post_form_data', $this->v['request']);
            return redirect()->route($method_route, ['id' => $id]);
        }
    }
}
