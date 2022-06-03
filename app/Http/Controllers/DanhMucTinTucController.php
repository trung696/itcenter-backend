<?php
namespace App\Http\Controllers;

use App\DanhMucTinTuc;
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
class DanhMucTinTucController extends Controller
{
    private $v;

    public function __construct()
    {
        $this->v = [];
    }

    public function danhMucTinTuc(Request $request){
        $this->v['_title'] = 'Danh mục tin tức';
        $this->v['routeIndexText'] = 'Danh mục tin tức';
        $objDanhMucTinTuc = new DanhMucTinTuc();
        //Nhận dữ liệu lọc từ view
        $this->v['extParams'] = $request->all();
        $this->v['list'] = $objDanhMucTinTuc->loadListWithPager($this->v['extParams']);

        return view('tintuc.danh-muc-tin-tuc', $this->v);
    }
}