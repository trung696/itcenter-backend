<?php
namespace App\Http\Controllers;

use App\ChienDich;
use App\MaChienDich;
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
    public function generateRandomString($length = 8) {
        $characters = '0123456789abcdefghijklmnopqrs092u3tuvwxyzaskdhfhf9882323ABCDEFGHIJKLMNksadf9044OPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    public function taoMaChienDich(Request $request){

        $validator = \Validator::make($request->all(),$this->ruleMaKhuyenMai(),$this->messageMaKhuyenMai());

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }
        $arrMaChienDich = [];
        $latestMaChienDich = DB::table('ma_khuyen_mai')->orderBy('id','DESC')->first();
        $latestID = $latestMaChienDich ? $latestMaChienDich->id : 0;

        for ($i = 0 ;$i < $request->so_luong;$i ++) {
            $maKhuyenMai = $this->generateRandomString(4).time();
            $arrMaChienDich[$i]['id'] = null;
            $arrMaChienDich[$i]['ma_khuyen_mai'] = $maKhuyenMai;
            $arrMaChienDich[$i]['id_chien_dich'] = $request->id_chien_dich;
            $arrMaChienDich[$i]['created_at']= date('Y-m-d H:i:s');
            $arrMaChienDich[$i]['trang_thai'] = 0;
            $latestID++;

        }
        $result = DB::table('ma_khuyen_mai')->insert($arrMaChienDich) ;
        if(!$result){
            return response()->json(['errors'=>Session::exists('errors')?Session::pull('errors'):'Lỗi thêm mới'],500);
        }else{
            Session::flash('success', 'Tạo tự động thành công '.$request->so_luong.' mã khuyến mại');
            return redirect()->back();
        }
    }
    private function ruleMaKhuyenMai(){
        return [
            'so_luong' => "required",
        ];
    }
    private function messageMaKhuyenMai(){
        return [
            "so_luong.required" =>  "Không được để trống số lượng",
        ];
    }
    public function checkcoupon(Request $request){

        $ma_khuyen_mai = $request->ma_khuyen_mai;
        $objCheckMa = new MaChienDich();
        $checkMa = $objCheckMa->loadCheckName($ma_khuyen_mai);
        $objChienDich = new ChienDich();
        $checkGiam = $objChienDich->loadOne($checkMa->id_chien_dich);
        if (isset($checkMa)){
            $data = 1;
        }else{
            $data = 0;
        }
        if ($checkMa->trang_thai ==0){
            $trang_thai = 0;
        }else{
            $trang_thai = 1;
        }
        $now = date('Y-m-d');
        $startDate = date('Y-m-d', strtotime($checkGiam->ngay_bat_dau));
        $endDate = date('Y-m-d', strtotime($checkGiam->ngay_ket_thuc));
        if(($now >= $startDate) && ($now <= $endDate))
        {
            $flag = 1;
        }
        else
        {
            $flag = 2;
        }
        return response()->json(array('data' => $data,'giamgia' => $checkGiam->phan_tram_giam,'date' => $flag,'trang_thai'=>$trang_thai));

    }
}