<?php

namespace App\Http\Controllers;

use App\DeThi;
use App\Http\Requests\AdminBoDeThiRequest;
use App\Http\Requests\UserRequest;
use App\KhoaHoc;
use App\MonHoc;
use App\NguoiDung;
use App\TuyenSinh\HocVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{

    private $v;

    public function __construct()
    {
//        $this->middleware('auth');
        $this->v = [];
    }

    public function index(Request $request)
    {
        $this->v['_title'] = 'Danh sách Người dùng';
        $this->v['routeIndexText'] = 'Danh sách người dùng';
        $objNguoiDung = new NguoiDung();
        $this->v['extParams'] = $request->all();
        $this->v['list'] = $objNguoiDung->loadListWithPager($this->v['extParams']);
        $this->v['quyens'] = config('app.roles');
        return view('user.index', $this->v);
    }

    public function home(Request $request) {
        $objKhoaHoc = new KhoaHoc();
        $this->v['extParams'] = $request->all();
        $this->v['listKhoaHoc'] = $objKhoaHoc->loadListWithPager($this->v['extParams']);
        return view('trangchu.index', $this->v);
    }

    public function add(UserRequest $request)
    {
        $this->v['routeIndexText'] = 'Danh sách người dùng';
        $method_route = 'route_BackEnd_NguoiDung_Add';
        $this->v['request'] = Session::pull('post_form_data')[0];
        $this->v['_action'] = 'Add';
        $this->v['_title'] = 'Thêm Người dùng';
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
            $modelNguoiDung = new  NguoiDung();
            $res = $modelNguoiDung->saveNew($params); // hàm trả về ID mới nếu insert thành công
            if ($res == null) // chuyển trang vì trong session đã có sẵn câu thông báo lỗi rồi
            {
                Session::push('post_form_data', $this->v['request']);
                return redirect()->route($method_route);
            } elseif ($res > 0) {
                $this->v['request'] = [];
                $request->session()->forget('post_form_data'); // xóa data post
                Session::flash('success', 'Thêm mới thành công người dùng !');
            } else {
                Session::push('errors', 'Lỗi thêm mới: ' . $res);
                Session::push('post_form_data', $this->v['request']);
                return redirect()->route($method_route);
            }

        } else {
            // không phải post
            $request->session()->forget($method_route); // hủy session nếu vào bằng sự kiện get
        }

        $this->v['quyens'] = config('app.roles');
        return view('user.add', $this->v);
    }

    public function detail($id, UserRequest $request)
    {
        $this->v['routeIndexText'] = 'Danh sách người dùng';
        $this->v['_action'] = 'Edit';
        $this->v['_title'] = 'Chi tiết người dùng';
        $this->v['request'] = Session::pull('post_form_data')[0];
        $modelNguoiDung = new NguoiDung();
        $objItem = $modelNguoiDung->loadOne($id);

        if (empty($objItem)) {
            Session::push('errors', 'Không tồn tại đề thi này ' . $id);
            return redirect()->route($this->routeIndex);
        }
        $this->v['quyens'] = config('app.roles');
        $this->v['status_user'] = config('app.status_user');
        $this->v['objItem'] = $objItem;
        return view('user.detail', $this->v);
    }

    public function update($id, UserRequest $request)
    {
        $method_route = 'route_BackEnd_NguoiDung_Detail';

        $modelNguoiDung = new NguoiDung();
        //Xử lý request
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
        $objItem = $modelNguoiDung->loadOne($id);
        if (empty($objItem)) {
            Session::push('errors', 'Không tồn tại người dùng này ' . $id);
            return redirect()->route('route_BackEnd_NguoiDung_index');
        }

        $params['cols']['id'] = $id;
        if (!is_null($params['cols']['password']))
        {
            $params['cols']['password'] = Hash::make($params['cols']['id']);
        }

        $res = $modelNguoiDung->saveUpdate($params);

        if ($res == null) // chuyển trang vì trong session đã có sẵn câu thông báo lỗi rồi
        {
            Session::push('post_form_data', $this->v['request']);
            return redirect()->route($method_route, ['id' => $id]);
        } elseif ($res == 1) {
//            SpxLogUserActivity(Auth::user()->id, 'edit', $primary_table, $id, 'edit');
            $request->session()->forget('post_form_data'); // xóa data post
            Session::flash('success', 'Cập nhật bản ghi: ' . $objItem->id . ' thành công!');

            return redirect()->route($method_route, ['id' => $id]);
        } else {

            Session::push('errors', 'Lỗi cập nhật cho bản ghi: ' . $res);
            Session::push('post_form_data', $this->v['request']);
            return redirect()->route($method_route, ['id' => $id]);
        }
    }
}
