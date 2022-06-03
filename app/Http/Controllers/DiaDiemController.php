<?php
namespace App\Http\Controllers;

use App\DiaDiem;
use App\User;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\DiaDiemRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Spipu\Html2Pdf\Html2Pdf;
use Dompdf\Dompdf;
use Dompdf\Options;
class DiaDiemController extends Controller{
    private $v;

    public function __construct()
    {
        $this->v = [];
    }

    public function danhSachDiaDiem(Request $request){
        $this->v['_title'] = 'Danh sách địa điểm';
        $this->v['routeIndexText'] = 'Danh sách địa điểm';
        $objDanhSachDiaDiem = new DiaDiem();
        //Nhận dữ liệu lọc từ view
        $this->v['extParams'] = $request->all();
        $this->v['list'] = $objDanhSachDiaDiem->loadListWithPager($this->v['extParams']);

        return view('diadiem.danh-sach-dia-diem', $this->v);
    }
    public function themDiaDiem(DiaDiemRequest $request){
        $this->v['routeIndexText'] = 'Thêm Đia Điểm';
        $method_route = 'route_BackEnd_DiaDiem_Add';
        $this->v['_action'] = 'Add';
        $this->v['_title'] = 'Thêm Đia Điểm';
        $this->v['trang_thai'] = config('app.status_danh_muc');
        if($request->isMethod('post')){
            $params = [
                'diadiem_add' => Auth::user()->id
            ];
            $params['cols'] = array_map(function ($item) {
                if ($item == '')
                    $item = null;
                if (is_string($item))
                    $item = trim($item);
                return $item;
            }, $request->post());
            unset($params['cols']['_token']);
            $objDiaDiem = new DiaDiem();
            $res = $objDiaDiem->saveNew($params);

            if($res == null){
                Session::push('post_form_data', $this->v['request']);
                return redirect()->route($method_route);
            }elseif ($res >0){
                $this->v['request'] = [];
                $request->session()->forget('post_from_data');
                Session::flash('success', 'Thêm mới thành địa điểm');
                return redirect()->route('route_BackEnd_DanhSachDiaDiem_index');
            }else{
                Session::push('errors', 'Lỗi thêm mới' .$res);
                Session::push('post_form_data', $this->v['request']);
                return redirect()->route($method_route);
            }
        }

        return view('diadiem.them-dia-diem', $this->v);
    }
    public function  chitetDiaDiem($id, Request $request){
        $this->v['routeIndexText'] = 'Chi tiết địa điểm';
        $this->v['_action'] = 'Edit';
        $this->v['_title'] = 'Chi tiết địa điểm';
        $objDiaDiem = new DiaDiem();
        $objItem = $objDiaDiem->loadOne($id);
        $this->v['extParams'] = $request->all();
        $this->v['trang_thai'] = config('app.status_danh_muc');
        $this->v['objItem'] = $objItem;
        if (empty($objItem)) {
            Session::push('errors', 'Không tồn tại danh mục này ' . $id);
            return redirect()->back();
        }
        return view('diadiem.chi-tiet-dia-diem', $this->v);

    }
    public function updateDiaDiem($id, DiaDiemRequest $request){

        $method_route = 'route_BackEnd_DiaDiem_Detail';
        $modelDiaDiem = new DiaDiem();
        $params = [
            'diadiem_edit' => Auth::user()->id
        ];
        $params['cols'] = array_map(function ($item) {
            if($item == '')
                $item = null;
            if(is_string($item))
                $item = trim($item);
            return $item;
        }, $request->post());

        unset($params['cols']['_token']);
        $objItem = $modelDiaDiem->loadOne($id);
        if (empty($objItem)) {
            Session::push('errors', 'Không tồn tại người dùng này ' . $id);
            return redirect()->route('route_BackEnd_NguoiDung_index');
        }
        $params['cols']['id'] = $id;
        $res = $modelDiaDiem->saveUpdate($params);
        if ($res == null) // chuyển trang vì trong session đã có sẵn câu thông báo lỗi rồi
        {
            Session::push('post_form_data', $this->v['request']);
            return redirect()->route($method_route, ['id' => $id]);
        } elseif ($res == 1) {
//            SpxLogUserActivity(Auth::user()->id, 'edit', $primary_table, $id, 'edit');
            $request->session()->forget('post_form_data'); // xóa data post
            Session::flash('success', 'Cập nhật thành công địa điểm');
            return redirect()->route('route_BackEnd_DanhSachDiaDiem_index');
        } else {

            Session::push('errors', 'Lỗi cập nhật cho bản ghi: ' . $res);
            Session::push('post_form_data', $this->v['request']);
            return redirect()->route($method_route, ['id' => $id]);
        }
    }
}