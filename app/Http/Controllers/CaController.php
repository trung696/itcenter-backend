<?php

namespace App\Http\Controllers;

use App\Ca;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests\CaHocRequest;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Spipu\Html2Pdf\Html2Pdf;
use Dompdf\Dompdf;
use Dompdf\Options;

require_once __DIR__ . '/../../SLib/functions.php';
class CaController extends Controller
{
    public function index(Request $request){
        $this->v['_title'] = 'Ca học';
        $this->v['routeIndexText'] = 'Ca học';
        $objCa = new Ca();
        //Nhận dữ liệu lọc từ view
        $this->v['extParams'] = $request->all();
        // dd($request->all());
        $this->v['list'] = $objCa->loadListWithPager($this->v['extParams']);
        // dd($this->v['list']);
        return view('ca.index', $this->v);
    }

    public function addCa(CaHocRequest $request){
        $this->v['_title'] = 'Thêm ca học';
        $method_route = 'route_BackEnd_Ca_Add';
        $this->v['_action'] = 'Add';
        $this->v['_title'] = 'Thêm ca học';
        $this->v['trang_thai'] = config('app.status_user');
        // dd(123);
        if ($request->isMethod('post')) {
            // dd(456);
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

            // dd($params['cols']);
            unset($params['cols']['_token']);
            $objCa = new Ca();
            $res = $objCa->saveNew($params);
            // dd($res);
            if ($res == null) // chuyển trang vì trong session đã có sẵn câu thông báo lỗi rồi
            {
                Session::push('post_form_data', $this->v['request']);
                return redirect()->route($method_route);
            } elseif ($res > 0) {
                $this->v['request'] = [];
                $request->session()->forget('post_form_data'); // xóa data post
                Session::flash('success', 'Thêm mới thành công ca học !');
                return redirect()->route('route_BackEnd_Ca_List');

            } else {
                Session::push('errors', 'Lỗi thêm mới: ' . $res);
                Session::push('post_form_data', $this->v['request']);
                return redirect()->route($method_route);
            }

        } else {
            // không phải post
            $request->session()->forget($method_route); // hủy session nếu vào bằng sự kiện get
        }

        return view('ca.add', $this->v);
    }

    public function editCa($id, Request $request){
        $this->v['routeIndexText'] = 'chỉnh sửa ca học';
        $this->v['_action'] = 'Edit';
        $this->v['_title'] = 'chỉnh sửa ca học';
        $objCa = new Ca();
        $objItem = $objCa->loadOne($id);
        $this->v['objItem'] = $objItem;

        $this->v['extParams'] = $request->all();
        $this->v['trang_thai'] = config('app.status_user');
        
        return view('ca.edit',$this->v);
    }

    public function updateCa($id, CaHocRequest $request){

        $method_route = 'route_BackEnd_Ca_Edit';
        $modelCa = new Ca();
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
        unset($params['cols']['_token']);
        // dd($params['cols']);
        $objItem = $modelCa->loadOne($id);
        if (empty($objItem)) {
            Session::push('errors', 'Không tồn tại người dùng này ' . $id);
            return redirect()->route('route_BackEnd_NguoiDung_index');
        }
        $params['cols']['id'] = $id;
        // dd($params);
        $res = $modelCa->saveUpdate($params);
        // dd($res);

        if ($res == null) // chuyển trang vì trong session đã có sẵn câu thông báo lỗi rồi
        {
            Session::push('post_form_data', $this->v['request']);
            return redirect()->route($method_route, ['id' => $id]);
        } elseif ($res == 1) {
            // dd('đã vào đây');
//            SpxLogUserActivity(Auth::user()->id, 'edit', $primary_table, $id, 'edit');
            $request->session()->forget('post_form_data'); // xóa data post
            Session::flash('success', 'Cập nhật thành công thông tin ca học!');
            // dd('vào lần 2');
            return redirect()->route('route_BackEnd_Ca_List');
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
	$deleteData = DB::table('cas')->where('id', '=', $id)->delete();
	
	//Kiểm tra lệnh delete để trả về một thông báo
	if ($deleteData) {
		Session::flash('success', 'Xóa ca học thành công!');
	}else {                        
		Session::flash('error', 'Xóa thất bại!');
	}
	
	//Thực hiện chuyển trang
	return redirect()->route('route_BackEnd_Ca_List');
}
}
