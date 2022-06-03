<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaiSanConRequest;
use App\TaiSanCon;
use App\User;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\DonVi;
use App\LichSuSuaChua;

class TaiSanConController extends Controller
{
    //
    private $v;

    public function __construct()
    {
//        $this->middleware('auth');
        $this->v = [];
    }
    public function themTaiSanCon(Request $request)
    {
        $validator = \Validator::make($request->all(),$this->ruleTaiSanCon(),$this->messageTaiSanCon());

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }
//        dd($request->all());
        $method_route = 'route_BackEnd_TaiSan_Detail';
//       $this->validate($request, $this->ruleTaiSanCon(), $this->messageTaiSanCon());

        //
        $objTaiSanCon = new TaiSanCon();
        $params = $request->post();
        $rs = $objTaiSanCon->saveNew([
            'cols' => $params,
            'user_add' => Auth::id()
        ]);
        if(!$rs){
            return response()->json(['errors'=>Session::exists('errors')?Session::pull('errors'):'Lỗi thêm mới'],500);
        }
//        return response()->json(['success'=>['Thêm mới thành công tài sản con']],200);
        return redirect()->back();
    }
    public function deleteTaiSanCon(Request $request , $id)
    {
        $detele = DB::table('tai_san_con')->where('id','=',$id)->delete();
        if ($detele){
            Session::flash('success', 'Xóa bản ghi: ' . $id . ' thành công!');
            return redirect()->back();
        }else{
            Session::flash('error', 'Xóa bản ghi: ' . $id . ' thất bại!');
            return redirect()->back();
        }
    }
    public function chiTietTaiSanCon(Request $request,$id,$idTaiSan)
    {

        $this->v['routeIndexText'] = 'Chi Tiết Tài Sản Con';
        $this->v['_action'] = 'Edit';
        $this->v['_title'] = 'Chi tiết tài sản con';

        $objDonVi = new DonVi();
        $this->v['don_vi'] = $objDonVi->loadListIdAndName(['trang_thai', 1]);
        $donVis = $this->v['don_vi'];
        $arrDonVi = [];
        foreach ($donVis as $index=> $item)
        {
            $arrDonVi[$item->id] = $item->ten_don_vi;
        }
        $this->v['arrDonVi'] = $arrDonVi;
        $objTaiSanCon = new TaiSanCon();
        $objItem = $objTaiSanCon->loadOne($id);

        if (empty($objItem)) {
            Session::flash('errors', 'Không tồn tại tài sản con này ' . $id);
            return redirect()->route($this->routeIndex);
        }
        $this->v['trang_thais'] = config('app.status_asset_baby');
//        $this->v['check_kiem_ke'] = config('app.status_check');
        $this->v['check_kiem_ke'] = config('app.status_check');
        $this->v['nguon_kinh_phi'] = config('app.expense');
        $this->v['objItem'] = $objItem;
        $objLichSuSuaChua = new LichSuSuaChua();
        $this->v['extParams'] = $request->all();
        $this->v['idTaiSan'] = $idTaiSan;
        $this->v['lists'] = $objLichSuSuaChua->loadListWithPager($this->v['extParams'],$id);
        return view('taisan.chi-tiet-tai-san-con', $this->v);
    }
    public function updateChiTietTaiSanCon($id, TaiSanConRequest $request)
    {
        //
        $method_route = 'route_BackEnd_TaiSanCon_Detail';
        $primary_table = 'tai_san_con';
        $objTaiSanCon = new TaiSanCon();
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
        $objItem = $objTaiSanCon->loadOne($id);
        if (empty($objItem)) {
            Session::push('errors', 'Không tồn tại danh mục này ' . $id);
            return redirect()->route('route_BackEnd_DanhMucTaiSan_index');
        }
        $params['cols']['id'] = $id;
//        dd(213123);
        $res = $objTaiSanCon->saveUpdate($params);

        if ($res == null) // chuyển trang vì trong session đã có sẵn câu thông báo lỗi rồi
        {
//            Session::push('post_form_data', $this->v['request']);
            return redirect()->route($method_route, ['id' => $id,'idTaiSan'=>$objItem->id_tai_san]);
        } elseif ($res == 1) {
//            SpxLogUserActivity(Auth::user()->id, 'edit', $primary_table, $id, 'edit');

            $request->session()->forget('post_form_data'); // xóa data post
            Session::flash('success', 'Cập nhật bản ghi: ' . $objItem->id . ' thành công!');

            return redirect()->route($method_route, ['id' => $id,'idTaiSan'=>$objItem->id_tai_san]);
        } else {

            Session::push('errors', 'Lỗi cập nhật cho bản ghi: ' . $res);
            Session::push('post_form_data', $this->v['request']);
            return redirect()->route($method_route, ['id' => $id,'idTaiSan'=>$objItem->id_tai_san]);
        }
    }
    private function ruleTaiSanCon(){
        return [
//            'ma_tai_san_con' => "required",
            'nam_su_dung' => "required",
            'thong_so_ky_thuat' => "required",
            'xuat_xu' => "required",
//            'id_don_vi' => "required",
            'nguon_kinh_phi' => "required",
            'nguyen_gia' => "required",
            'thoi_gian_khau_hao' => "required",
            'gia_tri_con_lai' => "required",
            'thoi_han_bao_hanh' => "required",
            'trang_thai' => "required",
        ];
    }
    private function messageTaiSanCon(){
        return [
//            "ma_tai_san_con.required" =>  "Vui lòng nhập mã hóa đơn",
            "nam_su_dung.required" =>  "Không được để trống năm sử dụng",
            "thong_so_ky_thuat.required" =>  "Không được để trống thông số kĩ thuật",
            "xuat_xu.required" =>  "Không được để trống xuất xứ",
//            "id_don_vi.required" =>  "Không được để trống đơn vị cung cấp",
            "nguon_kinh_phi.required" =>  "Không được để trống nguồn kinh phí",
            "nguyen_gia.required" =>  "Không được để trống nguyên giá",
            "thoi_gian_khau_hao.required" =>  "Không được để trống thời gian khấu hao",
            "gia_tri_con_lai.required" =>  "Không được để trống giá trị còn lại",
            "thoi_han_bao_hanh.required" =>  "Không được để trống thời gian bảo hành",
            "trang_thai.required" =>  "Không được để trống trạng thái",
        ];
    }

    public function inNhanTaiSanCon($id)
    {
        $users = User::all();
        $dataNhans = DB::table('tai_san_con as tb1')
                    ->select('tb1.ma_tai_san_con','tb1.nam_su_dung','tb2.ten_tai_san')
                    ->leftJoin('tai_san as tb2', 'tb2.id', '=', 'tb1.id_tai_san')
                    ->where('tb1.id_tai_san',$id)->get();

//        PDF::setOptions(['logOutputFile'=>storage_path('logs/pdf.log')]);
       // PDF::setOptions(['logOutputFile'=>storage_path('logs/pdf.log'),'tempDir'=>storage_path('logs/')]);

        $pdf = PDF::setOptions([
            'logOutputFile' => storage_path('logs/log.htm'),
            'tempDir' => storage_path('logs/')
        ])
            ->loadView('print.nhantaisan', compact('dataNhans'))->setPaper('a4');
//        $pdf = PDF::loadView('print.nhantaisan', compact('dataNhans'))->setPaper('a4');

        return $pdf->stream();
    }

    public function inBienBanBanGiao($id)
    {
        $users =[];
        $bienbanDetail = DB::table('bien_ban_ban_giao_ts')
                        ->where('id',$id)->first();
        if (!empty($bienbanDetail))
        {
           $detailTaiSan =  DB::table('tai_san as tb1')
                ->select(DB::raw('COUNT(*) as so_luong,tb1.ma_tai_san,tb1.ten_tai_san,tb2.tinh_trang_kk,tb2.id_don_vi'))
                ->leftJoin('tai_san_con as tb2', 'tb1.id', '=', 'tb2.id_tai_san')
                ->where('tb2.id_don_vi',$bienbanDetail->id_don_vi)
                ->groupBy('tb1.ma_tai_san', 'tb2.tinh_trang_kk')
                ->get();
           $donVi = DB::table('don_vi as tb1')->get();
           $arrDonVi = [];
           foreach ($donVi as $key => $value)
           {
               $arrDonVi[$value->id] = $value->ten_don_vi;
           }
            $arrTrangThaiKK = config('app.status_check');
            $pdf = PDF::setOptions([
                    'logOutputFile' => storage_path('logs/log.htm'),
                    'tempDir' => storage_path('logs/')
                ])->loadView('print.bienbanbangiao', compact('detailTaiSan','bienbanDetail','arrDonVi','arrTrangThaiKK'))->setPaper('a4');

        }

        return $pdf->stream();
    }

    public function inBienBanThanhLi()
    {
        $users = User::all();
        $dataTLs = DB::table('tai_san_con as tb1')
            ->select('tb1.id','tb1.ma_tai_san_con','tb1.nam_su_dung','tb1.thong_so_ky_thuat',
                'tb1.xuat_xu','tb1.id_don_vi','tb1.id_tai_san' ,'tb1.id_tai_san','tb1.nguon_kinh_phi','tb1.nguyen_gia','tb1.thoi_gian_khau_hao',
                'tb1.gia_tri_con_lai','tb1.thoi_han_bao_hanh', 'tb1.trang_thai','tb1.tinh_trang_kk','tb1.created_at','tb1.updated_at')
            ->leftJoin('tai_san as tb2', 'tb2.id', '=', 'tb1.id_tai_san')
            ->where('tb1.trang_thai','=',1)
            ->get();
        $donVi = DB::table('don_vi as tb1')->get();
        $arrDonVi = [];
        foreach ($donVi as $key => $value)
        {
            $arrDonVi[$value->id] = $value->ten_don_vi;
        }
        $taiSan = DB::table('tai_san as tb1')->get();
        $arrTaiSan = [];
        foreach ($taiSan as $key => $value)
        {
            $arrTaiSan[$value->id] = $value->ten_tai_san;
        }
        $arrNguonKP = config('app.expense');
//        PDF::setOptions(['logOutputFile'=>storage_path('logs/pdf.log')]);
        // PDF::setOptions(['logOutputFile'=>storage_path('logs/pdf.log'),'tempDir'=>storage_path('logs/')]);

        $pdf = PDF::setOptions([
            'logOutputFile' => storage_path('logs/log.htm'),
            'tempDir' => storage_path('logs/')
        ])
            ->loadView('print.bienbanthanhli', compact('dataTLs','arrDonVi','arrTaiSan','arrNguonKP'))->setPaper('a4');
//        $pdf = PDF::loadView('print.taisanthanhli', compact('dataTLs'))->setPaper('a4');

        return $pdf->stream();
    }
    public function inBienBanKiemKe($id,Request $request)
    {
      $requestData = $request->all();
        $donVi = DB::table('don_vi')
            ->where('id',$requestData['id_don_vi'])
            ->first();
        $detailTaiSan =  DB::table('tai_san as tb1')
            ->select(DB::raw('COUNT(*) as so_luong,tb1.ma_tai_san,tb1.ten_tai_san,tb2.tinh_trang_kk,tb2.thong_so_ky_thuat,tb2.xuat_xu,tb2.tinh_trang_kk,YEAR(tb2.nam_su_dung) as nam_su_dung'))
            ->leftJoin('tai_san_con as tb2', 'tb1.id', '=', 'tb2.id_tai_san')
            ->where('tb2.id_don_vi',$requestData['id_don_vi'])
            ->groupBy('tb1.ma_tai_san', 'tb2.tinh_trang_kk',DB::raw('YEAR(tb2.nam_su_dung)'))
            ->get();
        $arrKiemKe = config('app.status_check');
        $pdf = PDF::setOptions([
            'logOutputFile' => storage_path('logs/log.htm'),
            'tempDir' => storage_path('logs/')
        ])->loadView('print.bienbankiemke',compact('donVi','detailTaiSan','arrKiemKe'))->setPaper('a4');

        return $pdf->stream();
    }
}
