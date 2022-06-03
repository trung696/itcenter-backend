<?php

namespace App\Http\Controllers;

use App\DonVi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\DonViRequest;
use Illuminate\Support\Facades\Session;

class DonViController extends Controller
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
    public function donVi(Request $request)
    {
        //
        $this->v['_title'] = 'Đơn Vị';
        $this->v['routeIndexText'] = 'Đơn Vị';
        $objDonVi = new DonVi();
        $this->v['extParams'] = $request->all();
        $this->v['list'] = $objDonVi->loadListWithPager($this->v['extParams']);
        return view('donvi.don-vi', $this->v);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param $ten_danh_muc
     * @return \Illuminate\Http\Response
     */
    public function themDonVi(DonViRequest $request)
    {
        //
        $this->v['routeIndexText'] = 'Đơn vị';
        $method_route = 'route_BackEnd_DonVi_Add';
//        $this->v['request'] = Session::pull('post_form_data')[0];
//        SpxGetSsMessage($this->v);
        $this->v['_action'] = 'Add';
        $this->v['_title'] = 'Thêm đơn vị';
//        $this->v['ten_danh_muc'] = $ten_danh_muc;
//        $this->v['trang_thai'] = [0=>"Khóa",1=>"Mở"];
        $this->v['trang_thai'] = config('app.status_user');
        if ($request->isMethod('post'))
        {

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
            $objDonVi = new  DonVi();
            $res = $objDonVi->saveNew($params); // hàm trả về ID mới nếu insert thành công
            if ($res == null) // chuyển trang vì trong session đã có sẵn câu thông báo lỗi rồi
            {
                Session::push('post_form_data', $this->v['request']);
                return redirect()->route($method_route);
            } elseif ($res > 0) {
                $this->v['request'] = [];
                $request->session()->forget('post_form_data'); // xóa data post
                Session::flash('success', 'Thêm mới thành công đơn vị !');
                return redirect()->route('route_BackEnd_DonVi_index');

            } else {
                Session::push('errors', 'Lỗi thêm mới: ' . $res);
                Session::push('post_form_data', $this->v['request']);
                return redirect()->route($method_route);
            }

        } else {
            // không phải post
            $request->session()->forget($method_route); // hủy session nếu vào bằng sự kiện get
        }

//        $this->v['quyens'] = config('app.roles');
        return view('donvi.them-don-vi', $this->v);
    }

/**
* Display the specified resource.
*
* @param  int  $id
* @return \Illuminate\Http\Response
*/
    public function chiTietDonVi($id)
    {
        //
        $this->v['routeIndexText'] = 'Chi tiết đơn vị';
        $this->v['_action'] = 'Edit';
        $this->v['_title'] = 'Chi tiết đơn vị';
//        $this->v['request'] = Session::pull('post_form_data')[0];
        $objDonVi = new DonVi();
        $objItem = $objDonVi->loadOne($id);

        if (empty($objItem)) {
            Session::push('errors', 'Không tồn tại danh mục này ' . $id);
            return redirect()->route($this->routeIndex);
        }
//        $this->v['trang_thai'] = [0=>"Khóa",1=>"Mở"];
        $this->v['trang_thai'] = config('app.status_user');
        $this->v['objItem'] = $objItem;
        return view('donvi.chi-tiet-don-vi', $this->v);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateChiTietDonVi($id, DonViRequest $request)
    {
        //
        $method_route = 'route_BackEnd_DonVi_Detail';
        $primary_table = 'don_vi';
        $objDonVi = new DonVi();
        //Xử lý request
        $params = [
            'user_edit' => Auth::user()->id        ];
        $params['cols'] = array_map(function ($item) {
            if($item == '')
                $item = null;
            if(is_string($item))
                $item = trim($item);
            return $item;
        }, $request->post());
        unset($params['cols']['_token']);
        $objItem = $objDonVi->loadOne($id);
        if (empty($objItem)) {
            Session::push('errors', 'Không tồn tại danh mục này ' . $id);
            return redirect()->route('route_BackEnd_DonVi_index');
        }
        $params['cols']['id'] = $id;
        $res = $objDonVi->saveUpdate($params);

        if ($res == null) // chuyển trang vì trong session đã có sẵn câu thông báo lỗi rồi
        {
            Session::flash('success', 'Cập nhật bản ghi: ' . $objItem->id . ' thành công!');
            return redirect()->route('route_BackEnd_DonVi_index');
        } elseif ($res == 1) {
//            SpxLogUserActivity(Auth::user()->id, 'edit', $primary_table, $id, 'edit');
            $request->session()->forget('post_form_data'); // xóa data post
            Session::flash('success', 'Cập nhật bản ghi: ' . $objItem->id . ' thành công!');

            return redirect()->route('route_BackEnd_DonVi_index');
        } else {

            Session::push('errors', 'Lỗi cập nhật cho bản ghi: ' . $res);
            Session::push('post_form_data', $this->v['request']);
            return redirect()->route($method_route, ['id' => $id]);
        }
    }
}
