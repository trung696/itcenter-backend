<?php

namespace App\Http\Controllers;
use App\Http\Requests\LichSuSuaChuaRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\LichSuSuaChua;

class LichSuSuaChuaController extends Controller
{
    //
    public function themLichSuSuaChua(Request $request)
    {
        $validator = \Validator::make($request->all(),$this->ruleLichSuSuaChua(),$this->messageLichSuSuaChua());
        //
        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }
        $objLichSuSuaChua = new LichSuSuaChua();
        $params = $request->post();
        $rs = $objLichSuSuaChua->saveNew([
            'cols' => $params,
            'user_add' => Auth::id()
        ]);
        if(!$rs){
            return response()->json(['errors'=>Session::exists('errors')?Session::pull('errors'):'Lỗi thêm mới'],500);
        }else{
            Session::flash('success', 'Thêm mới lịch sử sửa chữa thành công!');
        }
//        return response()->json(['success'=>['Thêm mới thành công tài sản con']],200);
        return redirect()->back();
    }
    private function ruleLichSuSuaChua(){
        return [
            'ngay_sua_chua' => "required",
            'noi_dung' => "required",
            'nguyen_nhan' => "required",
            'chi_phi' => "required",
            'nguon_chi' => "required",
            'id_tai_san_con' => "required",
        ];
    }
    private function messageLichSuSuaChua(){
        return [
            "ngay_sua_chua.required" =>  "Không được để trống ngày sửa chữa",
            "noi_dung.required" =>  "Không được để trống nội dung sửa chữa",
            "nguyen_nhan.required" =>  "Không được để trống nguyên nhân sửa chữa",
            "chi_phi.required" =>  "Không được để trống chi phí sửa chữa",
            "nguon_chi.required" =>  "Không được để trống nguồn chi",
            "id_tai_san_con.required" =>  "Không được để trống tài sản con",
        ];
    }
}
