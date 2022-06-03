<?php
namespace App\Http\Controllers;

use App\KhuyenMai;
use App\User;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\DanhMucKhoaHocRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Spipu\Html2Pdf\Html2Pdf;
use Dompdf\Dompdf;
use Dompdf\Options;
 class KhuyenMaiController extends  Controller
 {
     private $v;

     public function __construct()
     {
         $this->v = [];
     }
     public function danhSachKhuyenMai(Request $request){
         $this->v['_title'] = 'Danh sách khuyến mại';
         $this->v['routeIndexText'] = 'Danh sách khuyến mại';
         $objDanhSachKhuyenMai = new KhuyenMai();
         //Nhận dữ liệu lọc từ view
         $this->v['extParams'] = $request->all();
         $this->v['list'] = $objDanhSachKhuyenMai->loadListWithPager($this->v['extParams']);

         return view('khuyenmai.danh-sach-khuyen-mai', $this->v);
     }
     public function themKhuyenMai(Request $request)
     {
         $this->v['_title'] = 'Khuyến Mại';
         $method_route = 'route_BackEnd_DanhSachKhhuyenMai_Add';
         $this->v['_action'] = 'Add';
         $this->v['_title'] = 'Thêm Khuyến Mại';
         $this->v['trang_thai'] = config('app.status_user');
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
             if($request->hasFile('hinh_anh_khuyen_mai') && $request->file('hinh_anh_khuyen_mai')->isValid()){
                 $params['cols']['hinh_anh_khuyen_mai'] = $this->uploadFile($request->file('hinh_anh_khuyen_mai'));
             }

             unset($params['cols']['_token']);
             $objKhuyenMai= new KhuyenMai();
             $res = $objKhuyenMai->saveNew($params);
             if ($res == null) // chuyển trang vì trong session đã có sẵn câu thông báo lỗi rồi
             {
                 Session::push('post_form_data', $this->v['request']);
                 return redirect()->route($method_route);
             } elseif ($res > 0) {
                 $this->v['request'] = [];
                 $request->session()->forget('post_form_data'); // xóa data post
                 Session::flash('success', 'Thêm mới thành công khoá học !');
                 return redirect()->route('route_BackEnd_DanhSachKhhuyenMai_index');

             } else {
                 Session::push('errors', 'Lỗi thêm mới: ' . $res);
                 Session::push('post_form_data', $this->v['request']);
                 return redirect()->route($method_route);
             }

         } else {
             // không phải post
             $request->session()->forget($method_route); // hủy session nếu vào bằng sự kiện get
         }

         return view('khuyenmai.them-danh-sach-khuyen-mai', $this->v);

     }
     private function uploadFile($file)
     {
         $fileName = time().'_'.$file->getClientOriginalName();
         return $file->storeAs('img-khuyenmai', $fileName, 'public');
     }
     public function checkcoupon(Request $request){

         $ten_khuyen_mai = $request->ma_khuyen_mai;
         $objDanhSachKhuyenMai = new KhuyenMai();
         $checkMaKhuyenMai = $objDanhSachKhuyenMai->loadCheckName($ten_khuyen_mai);
         if (isset($checkMaKhuyenMai)){
             $data = 1;
         }else{
             $data = 0;
         }
         $now = date('Y-m-d');
         $startDate = date('Y-m-d', strtotime($checkMaKhuyenMai->ngay_bat_dau));
         $endDate = date('Y-m-d', strtotime($checkMaKhuyenMai->ngay_ket_thuc));
         if(($now >= $startDate) && ($now <= $endDate))
         {
             $flag = 1;
         }
         else
         {
             $flag = 2;
         }
         return response()->json(array('data' => $data,'giamgia' => $checkMaKhuyenMai->phan_tram_khuyen_mai,'date' => $flag));

     }
 }