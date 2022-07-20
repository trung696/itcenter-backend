<?php

namespace App\Http\Controllers;

use App\DonVi;
use App\Http\Requests\BienBanRequest;
use App\TaiSan;
use Illuminate\Database\Eloquent\Model;
use App\Register;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\DanhMucTaiSan;
use App\TaiSanCon;


class RegisterController extends Controller
{
    //
    private $v;

    public function __construct()
    {
        //        $this->middleware('auth');
        $this->v = [];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $this->v['_title'] = 'Đăng Ký';
        $this->v['routeIndexText'] = 'Đăng ký';
        $objRegister = new Register();
        $this->v['extParams'] = $request->all();
        $this->v['list'] = $objRegister->loadListWithPager($this->v['extParams']);
        // $donVi = DB::table('don_vi as tb1')->get();
        // $arrDonVi = [];
        // foreach ($donVi as $key => $value) {
        //     $arrDonVi[$value->id] = $value->ten_don_vi;
        // }
        // $this->v['arrDonVi'] = $arrDonVi;
        return view('register.client.register', $this->v);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param $ten_danh_muc
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request)
    {
        //
        //
        $this->v['routeIndexText'] = 'Thêm đăng ký';
        $method_route = 'route_BackEnd_Register_Add';
        //        $this->v['request'] = Session::pull('post_form_data')[0];
        //        SpxGetSsMessage($this->v);
        $this->v['_action'] = 'Add';
        $this->v['_title'] = 'Thêm đăng ký';
        //        $this->v['ten_danh_muc'] = $ten_danh_muc;
        //        $this->v['trang_thai'] = [0=>"Khóa",1=>"Mở"];
        //        $this->v['trang_thai'] = config('app.status_user');
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
            unset($params['cols']['_token']);
            $objRegister = new  Register();
            $res = $objRegister->saveNew($params); // hàm trả về ID mới nếu insert thành công
            if ($res == null) // chuyển trang vì trong session đã có sẵn câu thông báo lỗi rồi
            {
                Session::push('post_form_data', $this->v['request']);
                return redirect()->route($method_route);
            } elseif ($res > 0) {
                $this->v['request'] = [];
                $request->session()->forget('post_form_data'); // xóa data post
                Session::flash('success', 'Đăng ký lớp thành công');
                return redirect()->route('route_BackEnd_BienBan_index');
            } else {
                Session::push('errors', 'Lỗi thêm mới: ' . $res);
                Session::push('post_form_data', $this->v['request']);
                return redirect()->route($method_route);
            }
        } else {
            // không phải post
            $request->session()->forget($method_route); // hủy session nếu vào bằng sự kiện get
        }
        $objDonVi = new  DonVi();
        $this->v['don_vi'] = $objDonVi->loadListIdAndName(['trang_thai', 1]);
        return view('bienban.them-bien-ban', $this->v);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function deleteBienBan(Request $request, $id)
    {
        $deleteData = DB::table('bien_ban_ban_giao_ts')->where('id', '=', $id)->delete();
        if ($deleteData) {
            Session::flash('success', 'Xóa dữ liệu thành công');
            return redirect(route('route_BackEnd_BienBan_index'));
        }
    }
}
