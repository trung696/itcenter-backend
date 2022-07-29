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
use App\Http\Requests\PaymentMethodRequest;
// use Illuminate\Support\Facades\App\CourseCategory;
use App\PaymentMethod;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Spipu\Html2Pdf\Html2Pdf;
use Dompdf\Dompdf;
use Dompdf\Options;
// use App\CourseCategory;
class PaymentMethodController extends Controller
{
    private $v;
    
    public function __construct()
    {
        $this->v = [];
    }
    
    public function PaymentMethod(Request $request){
        $this->v['_title'] = 'Phương thức thanh toán';
        $this->v['routeIndexText'] = 'Phương thức thanh toán';
        $objPayMethod = new PaymentMethod();
        //Nhận dữ liệu lọc từ view
        $this->v['extParams'] = $request->all();
        // dd($this->v['extParams']);
        $this->v['id_user'] = Auth::id();
        $this->v['list'] = $objPayMethod->loadListWithPager($this->v['extParams']);
        // dd($this->v['list']);
        // $data = CourseCategory::find(2)->course->toArray();
        // dd($data);

        return view('paymentMethod.list-payment-method', $this->v);
    }
    //

    public function AddPaymentMethod(PaymentMethodRequest $request){
        $this->v['routeIndexText'] = 'Phương thức thanh toán';
        $method_route = 'route_BackEnd_PaymentMethod_Add';
        $this->v['_action'] = 'Add';
        $this->v['_title'] = 'Phương thức thanh toán';
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
            $objpayMethod = new PaymentMethod();
            // dd($objpayMethod);
            $res = $objpayMethod->saveNew($params);
            

            if($res == null){
                Session::push('post_form_data', $this->v['request']);
                return redirect()->route($method_route);
            }elseif ($res >0){
                $this->v['request'] = [];
                $request->session()->forget('post_from_data');
                Session::flash('success', 'Thêm mới thành công phương thức thanh toàn');
                return redirect()->route('route_BackEnd_PaymentMethod_List');
            }else{
                Session::push('errors', 'Lỗi thêm mới' .$res);
                Session::push('post_form_data', $this->v['request']);
                return redirect()->route($method_route);
            }
        }

        return view('paymentMethod.add-payment-method', $this->v);
    }

    public function paymentMethodDetail($id, Request $request){
        $this->v['routeIndexText'] = 'Chi tiết phương thức thanh toán';
        $this->v['_action'] = 'Edit';
        $this->v['_title'] = 'Chi tiết phương thức thanh toán';
        $objPayMethod = new PaymentMethod();
        $objItem = $objPayMethod->loadOne($id);
        $this->v['extParams'] = $request->all();
        $this->v['objItem'] = $objItem;
        if (empty($objItem)) {
            Session::push('errors', 'Không tồn tại phương thức thanh toán này ' . $id);
            return redirect()->back();
        }
        return view('paymentMethod.detail-payment-method', $this->v);

    }

    public function updatePaymentMethod($id, PaymentMethodRequest $request){

        $method_route = 'route_BackEnd_PaymentMethod_Detail';
        $modelPaymentMethod = new PaymentMethod();
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
        $objItem = $modelPaymentMethod->loadOne($id);
        if (empty($objItem)) {
            Session::push('errors', 'Không tồn tại người dùng này ' . $id);
            return redirect()->route('route_BackEnd_NguoiDung_index');
        }
        $params['cols']['id'] = $id;
        $res = $modelPaymentMethod->saveUpdate($params);
        if ($res == null) // chuyển trang vì trong session đã có sẵn câu thông báo lỗi rồi
        {
            Session::push('post_form_data', $this->v['request']);
            return redirect()->route($method_route, ['id' => $id]);
        } elseif ($res == 1) {
//            SpxLogUserActivity(Auth::user()->id, 'edit', $primary_table, $id, 'edit');
            $request->session()->forget('post_form_data'); // xóa data post
            Session::flash('success', 'Cập nhật thành công phương thức thanh toán');

            return redirect()->route('route_BackEnd_PaymentMethod_List');
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
	$deleteData = DB::table('payment_method')->where('id', '=', $id)->delete();
	
	//Kiểm tra lệnh delete để trả về một thông báo
	if ($deleteData) {
		Session::flash('success', 'Xóa danh phương thức thanh toán thành công!');
	}else {                        
		Session::flash('error', 'Xóa thất bại!');
	}
	
	//Thực hiện chuyển trang
	return redirect()->route('route_BackEnd_PaymentMethod_List');
}
}
