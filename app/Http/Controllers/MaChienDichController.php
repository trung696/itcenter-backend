<?php

namespace App\Http\Controllers;

use App\ChienDich;
use App\Http\Requests\MaChienDichRequest;
use App\MaChienDich;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\KhoaHocRequest;
use Illuminate\Support\Facades\Session;
use Spipu\Html2Pdf\Html2Pdf;
use Dompdf\Dompdf;
use Dompdf\Options;

class MaChienDichController extends Controller
{
    public function generateRandomString($length = 8)
    {
        $characters = '0123456789abcdefghijklmnopqrs092u3tuvwxyzaskdhfhf9882323ABCDEFGHIJKLMNksadf9044OPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    public function taoMaChienDich(Request $request)
    {

        $validator = Validator::make($request->all(), $this->ruleMaKhuyenMai(), $this->messageMaKhuyenMai());

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        $arrMaChienDich = [];
        $latestMaChienDich = DB::table('ma_khuyen_mai')->orderBy('id', 'DESC')->first();
        $latestID = $latestMaChienDich ? $latestMaChienDich->id : 0;

        for ($i = 0; $i < $request->so_luong; $i++) {
            $maKhuyenMai = $this->generateRandomString(4) . time();
            $arrMaChienDich[$i]['id'] = null;
            $arrMaChienDich[$i]['ma_khuyen_mai'] = $maKhuyenMai;
            $arrMaChienDich[$i]['id_chien_dich'] = $request->id_chien_dich;
            $arrMaChienDich[$i]['created_at'] = date('Y-m-d H:i:s');
            $arrMaChienDich[$i]['trang_thai'] = 0;
            $latestID++;
        }
        $result = DB::table('ma_khuyen_mai')->insert($arrMaChienDich);
        if (!$result) {
            return response()->json(['errors' => Session::exists('errors') ? Session::pull('errors') : 'Lỗi thêm mới'], 500);
        } else {
            Session::flash('success', 'Tạo tự động thành công ' . $request->so_luong . ' mã khuyến mại');
            return redirect()->back();
        }
    }
    private function ruleMaKhuyenMai()
    {
        return [
            'so_luong' => "required",
        ];
    }
    private function messageMaKhuyenMai()
    {
        return [
            "so_luong.required" =>  "Không được để trống số lượng",
        ];
    }
    public function checkcoupon(Request $request)
    {

        $ma_khuyen_mai = $request->ma_khuyen_mai;
        $objCheckMa = new MaChienDich();
        $checkMa = $objCheckMa->loadCheckName($ma_khuyen_mai);
        $objChienDich = new ChienDich();
        $checkGiam = $objChienDich->loadOne($checkMa->id_chien_dich);
        if (isset($checkMa)) {
            $data = 1;
        } else {
            $data = 0;
        }
        if ($checkMa->trang_thai == 0) {
            $trang_thai = 0;
        } else {
            $trang_thai = 1;
        }
        if ($checkGiam->trang_thai == 0) {
            $hoat_dong = 0;
        } else {
            $hoat_dong = 1;
        }
        $now = date('Y-m-d');
        $startDate = date('Y-m-d', strtotime($checkGiam->ngay_bat_dau));
        $endDate = date('Y-m-d', strtotime($checkGiam->ngay_ket_thuc));
        if (($now >= $startDate) && ($now <= $endDate)) {
            $flag = 1;
        } else {
            $flag = 2;
        }
        return response()->json(array('hoat_dong' => $hoat_dong, 'data' => $data, 'giamgia' => $checkGiam->phan_tram_giam, 'date' => $flag, 'trang_thai' => $trang_thai));
    }
    public function deleteMaKhuyMai($id, Request $request)
    {
        $objMaChienDich = new MaChienDich();
        $idChienDich =  $objMaChienDich->loadOne($id);
        $deleteData = DB::table('ma_khuyen_mai')->where('id', '=', $id)->delete();
        if ($deleteData) {
            Session::flash('success', 'Xóa dữ liệu thành công');
            return redirect(route('route_BackEnd_ChienDich_Detail', ['id' => $idChienDich->id_chien_dich]));
        }
    }
    public function  chitetMaKhuyenMai($id, Request $request)
    {
        $this->v['routeIndexText'] = 'Chi tiết mã khuyến mãi';
        $this->v['_action'] = 'Edit';
        $this->v['_title'] = 'Chi tiết mã khuyến mãi';
        $this->v['trang_thai'] = config('app.status_chien_dich');
        $objMaChienDich = new MaChienDich();
        $objItem =  $objMaChienDich->loadOne($id);
        $this->v['objItem'] = $objItem;
        if (empty($objItem)) {
            Session::push('errors', 'Không tồn tại danh mục này ' . $id);
            return redirect()->back();
        }
        return view('khuyenmai.update-ma-khuyen-mai', $this->v);
    }
    public function updateMaChienDich($id, MaChienDichRequest $request)
    {

        $method_route = 'route_BackEnd_ChienDich_Detail';
        $modelMaKhuyenMai = new MaChienDich();
        $params = [
            'danhmuc_edit' => Auth::user()->id
        ];
        $params['cols'] = array_map(function ($item) {
            if ($item == '')
                $item = null;
            if (is_string($item))
                $item = trim($item);
            return $item;
        }, $request->post());

        unset($params['cols']['_token']);
        $objItem = $modelMaKhuyenMai->loadOne($id);
        if (empty($objItem)) {
            Session::push('errors', 'Không tồn tại người dùng này ' . $id);
            return redirect()->route('route_BackEnd_NguoiDung_index');
        }
        $params['cols']['id'] = $id;
        $res = $modelMaKhuyenMai->saveUpdate($params);
        if ($res == null) // chuyển trang vì trong session đã có sẵn câu thông báo lỗi rồi
        {
            Session::push('post_form_data', $this->v['request']);
            return redirect()->route($method_route, ['id' => $objItem->id_chien_dich]);
        } elseif ($res == 1) {
            //            SpxLogUserActivity(Auth::user()->id, 'edit', $primary_table, $id, 'edit');
            $request->session()->forget('post_form_data'); // xóa data post
            Session::flash('success', 'Cập nhật thành công mã chiến dịch');

            return redirect()->route('route_BackEnd_ChienDich_Detail', ['id' => $objItem->id_chien_dich]);
        } else {

            Session::push('errors', 'Lỗi cập nhật cho bản ghi: ' . $res);
            Session::push('post_form_data', $this->v['request']);
            return redirect()->route($method_route, ['id' => $objItem->id_chien_dich]);
        }
    }
}
