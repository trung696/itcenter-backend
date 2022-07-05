<?php
namespace App\Http\Controllers;

use App\CentralFacility;
use App\User;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\CentralFacilityRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Spipu\Html2Pdf\Html2Pdf;
use Dompdf\Dompdf;
use Dompdf\Options;
class CentralFacilityController extends Controller{
    private $v;

    public function __construct()
    {
        $this->v = [];
    }

    public function listCentralFacility(Request $request){
        $this->v['_title'] = 'Danh sách địa điểm';
        $this->v['routeIndexText'] = 'Danh sách địa điểm';
        $objListCentralFacility = new CentralFacility();
        //Nhận dữ liệu lọc từ view
        $this->v['extParams'] = $request->all();
        $this->v['list'] = $objListCentralFacility->loadListWithPager($this->v['extParams']);

        return view('diadiem.central-facility', $this->v);
    }
    public function AddCentralFacility(CentralFacilityRequest $request){
        $this->v['routeIndexText'] = 'Thêm Đia Điểm';
        $method_route = 'route_BackEnd_CentralFacility_Add';
        $this->v['_action'] = 'Add';
        $this->v['_title'] = 'Thêm Đia Điểm';
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
            // dd($params['cols']);
            unset($params['cols']['_token']);
            $objCentralFacility = new CentralFacility();
            $res = $objCentralFacility->saveNew($params);

            if($res == null){
                Session::push('post_form_data', $this->v['request']);
                return redirect()->route($method_route);
            }elseif ($res >0){
                $this->v['request'] = [];
                $request->session()->forget('post_from_data');
                Session::flash('success', 'Thêm mới thành địa điểm');
                return redirect()->route('route_BackEnd_CentralFacility_List');
            }else{
                Session::push('errors', 'Lỗi thêm mới' .$res);
                Session::push('post_form_data', $this->v['request']);
                return redirect()->route($method_route);
            }
        }

        return view('diadiem.add-central-facility', $this->v);
    }
    public function  centralFacilityDetail($id, Request $request){
        $this->v['routeIndexText'] = 'Chi tiết địa điểm';
        $this->v['_action'] = 'Edit';
        $this->v['_title'] = 'Chi tiết địa điểm';
        $objCentralFacility = new CentralFacility();
        $objItem = $objCentralFacility->loadOne($id);
        // dd($objItem);
        $this->v['extParams'] = $request->all();
        $this->v['objItem'] = $objItem;
        if (empty($objItem)) {
            Session::push('errors', 'Không tồn tại danh mục này ' . $id);
            return redirect()->back();
        }
        return view('diadiem.central-facility-detail', $this->v);

    }
    public function updateCentralFacility($id, CentralFacilityRequest $request){

        $method_route = 'route_BackEnd_CentralFacility_Detail';
        $modelCentralFacility = new CentralFacility();
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

        // dd($params['cols']);
        unset($params['cols']['_token']);
        $objItem = $modelCentralFacility->loadOne($id);
        if (empty($objItem)) {
            Session::push('errors', 'Không tồn tại người dùng này ' . $id);
            return redirect()->route('route_BackEnd_NguoiDung_index');
        }
        $params['cols']['id'] = $id;
        $res = $modelCentralFacility->saveUpdate($params);
        if ($res == null) // chuyển trang vì trong session đã có sẵn câu thông báo lỗi rồi
        {
            // dd($this->v['request']);
            Session::push('post_form_data', @isset($this->v['request']));
            return redirect()->route($method_route, ['id' => $id]);
        } elseif ($res == 1) {
//            SpxLogUserActivity(Auth::user()->id, 'edit', $primary_table, $id, 'edit');
            $request->session()->forget('post_form_data'); // xóa data post
            Session::flash('success', 'Cập nhật thành công địa điểm');
            return redirect()->route('route_BackEnd_CentralFacility_List');
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
	$deleteData = DB::table('central_facility')->where('id', '=', $id)->delete();
	
	//Kiểm tra lệnh delete để trả về một thông báo
	if ($deleteData) {
		Session::flash('success', 'Xóa học sinh thành công!');
	}else {                        
		Session::flash('error', 'Xóa thất bại!');
	}
	
	//Thực hiện chuyển trang
	return redirect()->route('route_BackEnd_CentralFacility_List');
}
}