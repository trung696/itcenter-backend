<?php

namespace App\Http\Controllers;

use App\Ca;
use App\ChienDich;
use App\ClassModel;
use App\Course;
use App\DangKy;
use App\HocVien;
use App\Http\Requests\DangKyRequest;
use App\LopHoc;
use App\MaChienDich;
use App\Mail\OrderShipped;
use App\User;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\DanhMucKhoaHocRequest;
use App\Payment;
use App\ThongTinChuyenLop;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Spipu\Html2Pdf\Html2Pdf;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class DangKyController extends Controller
{
    private $v;

    public function __construct()
    {
        $this->v = [];
    }
    public function danhSachDangKy(Request $request)
    {
        $this->v['_title'] = 'Danh đăng ký';
        $this->v['routeIndexText'] = 'Danh đăng ký';
        $objDangKy = new DangKy();
        //Nhận dữ liệu lọc từ view
        $this->v['extParams'] = $request->all();
        $this->v['list'] = $objDangKy->loadListWithPagers($this->v['extParams']);
        return view('dangky.dang-ky', $this->v);
    }
    public function themDangKy(DangKyRequest $request)
    {
        $this->v['routeIndexText'] = 'Danh mục khoa học';
        $this->v['_action'] = 'Add';
        $this->v['_title'] = 'Thêm danh mục khoá học';
        $objKhoaHoc = new Course();
        $objLopHoc = new ClassModel();
        if ($request->isMethod('post')) {
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
            if (!preg_match("/^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i", $request->email)) {
                Session::flash('success', 'Email không chính xác');
                return redirect()->route('route_BackEnd_DangKyAdmin_Add');
            } elseif (!preg_match("/(84|0[3|5|7|8|9])+([0-9]{8})\b/", $request->so_dien_thoai)) {

                Session::flash('success', 'Số điện thoại không chính xác');
                return redirect()->route('route_BackEnd_DangKyAdmin_Add');
            } else {
                unset($params['cols']['_token']);
                if ($request->hasFile('hinh_anh') && $request->file('hinh_anh')->isValid()) {
                    $params['cols']['hinh_anh'] = $this->uploadFile($request->file('hinh_anh'));
                }

                $objDangKy = new DangKy();
                $objHocVien = new HocVien();
                $checkEmail = $objHocVien->loadCheckHocVien($request->email);
                //email chưa tôàn tại trên hệ thống 
                // dd($params);
                if (!isset($checkEmail)) {
                    // dd($checkEmail);
                    $resHocVien = $objHocVien->saveNewAdmin($params);
                    //email tồn tại trên hệ thống 
                } else {
                    $checkHV = $objDangKy->loadCheckName($request->id_lop_hoc, $checkEmail->id);
                    if (!isset($checkHV)) {
                        $resHocVien = $checkEmail->id;
                    }
                }
                if (isset($resHocVien)) {
                    $gia = $objKhoaHoc->loadOne($request->id_khoa_hoc);
                    // dd($gia);
                    $arrDangKy = [];
                    $arrDangKy['id_lop_hoc'] = $request->id_lop_hoc;
                    $arrDangKy['id_hoc_vien'] = $resHocVien;
                    $arrDangKy['gia_tien'] = $gia->price - ($gia->price * $request->pham_tram_giam / 100);
                    $arrDangKy['trang_thai'] = $request->trang_thai;
                    // dd($objLopHoc->slot);
                    // dd($request->trang_thai);
                    if ($request->trang_thai == 1) {
                        $res = $objDangKy->saveNewOnline($arrDangKy);
                        if ($res) {
                            // $objLopHoc = new  LopHoc();
                            $socho = $objLopHoc->loadOneID($request->id_lop_hoc);
                            // dd($socho);
                            $updateSoCho = [];
                            $updateSoCho['id'] = $request->id_lop_hoc;
                            $updateSoCho['so_cho'] = $socho->slot - 1;
                            $update = $objLopHoc->saveUpdateSoCho($updateSoCho);
                        }
                    } else {
                        $res = $objDangKy->saveNew($arrDangKy);
                    }
                    $email = $request->email;
                    $objGuiGmail = DB::table('dang_ky', 'tb1')
                        ->select('tb1.id', 'tb1.gia_tien', 'tb2.ho_ten', 'tb3.name', 'tb4.price', 'tb4.name as course_name', 'tb2.so_dien_thoai', 'tb1.trang_thai')
                        ->leftJoin('hoc_vien as tb2', 'tb2.id', '=', 'tb1.id_hoc_vien')
                        ->leftJoin('class as tb3', 'tb3.course_id', '=', 'tb1.id_lop_hoc')
                        ->leftJoin('course as tb4', 'tb3.course_id', '=', 'tb4.id')
                        ->where('tb1.id', $res)->first();
                    if (isset($request->pham_tram_giam)) {
                        $objGuiGmail->so_dien_thoai = $request->pham_tram_giam;
                    }

                    // dd($objGuiGmail);
                    Mail::to($email)->send(new OrderShipped($objGuiGmail));

                    $method_route = 'route_BackEnd_DangKyAdmin_Add';
                    if ($res == null) {
                        Session::push('post_form_data', $this->v['request']);
                        return redirect()->route($method_route);
                    } elseif ($res > 0) {
                        $this->v['request'] = [];
                        $request->session()->forget('post_from_data');
                        Session::flash('success', 'Đăng ký thành công');
                        return redirect()->route('route_BackEnd_DanhSachDangKy_index');
                    } else {
                        Session::push('errors', 'Lỗi thêm mới');
                        Session::push('post_form_data', $this->v['request']);
                        return redirect()->route($method_route);
                    }
                } else {
                    Session::flash('success', 'Học viên đã đang ký khoá nay. Không thể đăng ký lại');
                    return redirect()->route('route_BackEnd_DanhSachDangKy_index');
                }
            }
        }

        $this->v['objKhoaHoc'] = $objKhoaHoc->loadListWithPager();
        $this->v['objLopHoc'] = $objLopHoc->loadListWithPager();
        // dd($objKhoaHoc);
        // dd($trang_thai);

        return view('dangky.them-dang-ky', $this->v);
    }
    private function uploadFile($file)
    {
        $fileName = time() . '_' . $file->getClientOriginalName();
        return $file->storeAs('hinh_anh_hoc_vien', $fileName, 'public');
    }
    public function getListLop($course_id)
    {
        $now = date('Y-m-d');
        $list_lop_hoc = DB::table('class')->select('id', 'name', 'course_id')
            ->where('course_id', '=', $course_id)
            ->where('start_date', '>', $now)
            ->orderBy('name', 'ASC')->get();
        return response()->json($list_lop_hoc, 200);
    }
    public function chiTietDangKy($id, Request $request)
    {
        $now = date('Y-m-d');
        $this->v['routeIndexText'] = 'Chi tiết đăng ký';
        $this->v['_action'] = 'Edit';
        $this->v['_title'] = 'Chi tiết đăng ký';
        $this->v['trang_thai'] = config('app.status_dang_ky');

        $objDangKy = new DangKy();
        $itemDK = $objDangKy->loadOne($id);
        $this->v['itemDK'] = $itemDK->id_lop_hoc;
        $this->v['itemTT'] = $itemDK->trang_thai;
        $this->v['itemDKTT'] = $itemDK->trang_thai;
        $this->v['itemGia'] = $itemDK->gia_tien;
        $objHocVien = new  HocVien();
        $this->v['itemHV'] = $objHocVien->loadOne($itemDK->id_hoc_vien);
        $this->v['checkIssetPayment'] = DangKy::where('id', $id)->first()->id_payment;
        $this->v['trangThai'] = DangKy::where('id', $id)->first()->trang_thai;
        $this->v['dki'] = DangKy::where('id', $id)->first();
        $objLopHoc = new ClassModel();
        $itemLH = $objLopHoc->loadOne($this->v['itemDK']);
        $objKhoaHoc = new Course();
        $this->v['itemKH'] = $objKhoaHoc->loadOne($itemLH->course_id);
        $list_lop_hoc = DB::table('class')->select('id', 'name')
            ->where('course_id', '=', $itemLH->course_id)
            ->where('start_date', '>', $now)->get();
        $this->v['listLH'] = $list_lop_hoc;
        $listClass = ClassModel::all();
        $listCourse = Course::all();
        $getDuNo = DangKy::whereId($id)->first()->du_no;
        // dd($this->v);
        return view('dangky.sua-thong-tin', $this->v, compact('listClass', 'getDuNo', 'listCourse'));
    }


    public function update(Request $request, $id, $email, $oldClass)
    {
        // dd($request->all(), $id, $email, $oldClass);
        //trường hợp đóng tiền(đăng kí nhưng chưa thanh toán)
        if ($request->dong_hoc_phi) {
            return $this->dongHocPhi($request, $id, $email);
        }
        //nếu nộp thêm tiền thì gọi function updateDongThemTien
        elseif (isset($request->dong_them)) {
            return $this->updateDongThemTien($request, $id, $email);
        }
        $hocVien = HocVien::where('email', '=', $email)->first();
        $id_hoc_vien = $hocVien->id;
        $dangKy = DangKy::where('id_hoc_vien', '=', $id_hoc_vien)->where('id_lop_hoc', '=', $oldClass)->first();
        //lớp cũ
        $checkCourseClassOld = ClassModel::where('id', $oldClass)->first()->course;
        //lớp mới
        $checkCourseClassNew = ClassModel::where('id', $request->id_lop_hoc_moi)->first()->course;
        //kiểm tra xem lớp học cũ và lớp muốn chuyển có cùng 1 khóa học Không
        if ($checkCourseClassOld->name === $checkCourseClassNew->name) {
            $checkClass = ClassModel::where('id', $request->id_lop_hoc_moi)->first();
            //check con slot khong
            if ($checkClass->slot > 0) {
                $dangKyOld = DangKy::where('id', $dangKy->id)->first();
                $updateDangKy =  $dangKy->update([
                    'id_lop_hoc' => $request->id_lop_hoc_moi,
                ]);

                $dangKyBeforeUpdate = DangKy::where('id', $dangKy->id)->first();
                //nếu trạng thái là đã thanh toán khi chuyển đi rồi thì phải cộng thêm 1 slot
                if ($dangKyBeforeUpdate->trang_thai == 1) {
                    if ($updateDangKy) {
                        ClassModel::whereId($dangKyOld->class->id)->update([
                            'slot' =>  $dangKyOld->class->slot + 1
                        ]);
                    }
                }
                //check chỗ lớp mới chuyển sang và trừ đi 1 slot
                $listClass = ClassModel::all();
                $dangKyAfterUpdate = DangKy::where('id', $dangKy->id)->first();

                $ca = Ca::all();
                if ($dangKyAfterUpdate->trang_thai == 1) {
                    $classOfChuyenLop = $dangKyAfterUpdate->class;
                    ClassModel::whereId($classOfChuyenLop->id)->update([
                        'slot' =>  $classOfChuyenLop->slot - 1
                    ]);
                    Mail::send('emailThongBaoChuyenLop', compact('hocVien', 'dangKyAfterUpdate', 'listClass', 'classOfChuyenLop','ca'), function ($email) use ($hocVien) {
                        // mail nhận thư, tên người dùng
                        $email->subject("Hệ thống thông báo chuyển lớp thành công đến bạn");
                        $email->to($hocVien->email, $hocVien->ho_ten);
                    });
                    // return 'Chuyển lớp thành công số chỗ của lớp mới đã trừ đi 1';
                    return Redirect::back()->withErrors(['msg' => 'Chuyển lớp thành công số chỗ của lớp mới đã trừ đi 1']);
                } else {
                    return Redirect::back()->withErrors(['msg' => 'Chuyển lớp thành công chờ trường check thanh toán']);
                }
                return Redirect::back()->withErrors(['msg' => 'Chuyển lớp thành công']);
            } else {
                return Redirect::back()->withErrors(['msg' => 'lớp đã đầy không thể chuyển lớp']);
            }
        }
        //nếu khác khóa học thì gọi function doiKhoaHoc()
        return  $this->doiKhoaHoc($request, $dangKy,  $checkCourseClassNew, $request->id_lop_hoc_moi, $dangKy,  $oldClass);
    }

    public function updateDongThemTien($request, $id, $email)
    {
        // dd($request, $id,$email);
        $dkiEdit = DangKy::where('id', $id)->first();

        if (abs($dkiEdit->du_no) == $request->dong_them) {
            try {
                DB::beginTransaction();
                $dkiEdit['trang_thai'] = 1;
                $dkiEdit['so_tien_da_dong'] = null;
                $dkiEdit['du_no'] = 0;
                $dkiEdit->update();

                $payMentUpdate = Payment::where('id', $dkiEdit->id_payment)->first();
                $payMentUpdate['payment_date'] = date("Y-m-d H:i:s");
                $payMentUpdate['price'] = $payMentUpdate->price + $request->dong_them;
                $payMentUpdate['description'] =  "$payMentUpdate->description (đóng thêm ) ";
                $payMentUpdate->update();

                $classOld = ClassModel::whereId($dkiEdit->id_lop_hoc)->first();
                $classOld['slot'] = $classOld->slot + 1;
                $classOld->update();
                DB::commit();
                return Redirect::back()->withErrors(['msg' => 'Nộp tiền thành công']);
                // Mail::send('emailThongBaoDaNopHocPhi', compact('hocVien', 'dangKyAfterUpdate', 'listClass', 'classOfChuyenLop','ca'), function ($email) use ($hocVien) {
                //     // mail nhận thư, tên người dùng
                //     $email->subject("Hệ thống thông báo chuyển lớp thành công đến bạn");
                //     $email->to($hocVien->email, $hocVien->ho_ten);
                // });
            } catch (\Exception $exception) {
                DB::rollback();
                Log::error('message: ' . $exception->getMessage() . 'line:' . $exception->getLine());
            }
        } else {
            return Redirect::back()->withErrors(['msg' => 'Nộp tiền thừa hoặc thiếu so với số tiền phải đóng']);
        }
    }

    public function dongHocPhi($request, $id, $email)
    {
        dd('hàm đónng học phí khi đăng kí chưa nộp tiền');
        // dd($request->all(), $id, $email);
        try {
            $dkiEdit = DangKy::where('id', $id)->first();
            if ($request->dong_hoc_phi != $dkiEdit->gia_tien) {
                return Redirect::back()->withErrors(['msg' => 'Vui lòng nhập đúng số tiền là :' . $dkiEdit->gia_tien]);
            } else {
                DB::beginTransaction();
                $dangKy = DangKy::where('id', $id)->first();
                // kiểm tra xem lớp đấy còn slot không và đã khai giảng chưa
                $checkClass = ClassModel::where('id', $dangKy->id_lop_hoc)->first();
                // kiểm tra xem lớp đấy còn slot không và đã khai giảng chưa
                if ($checkClass->start_date > date('Y-m-d')  && $checkClass->slot > 0) {
                    $hocVienDangKi = HocVien::where('id', $dangKy->id_hoc_vien)->first();
                    // tạo payment phương thức = 1 là đsong tiền trực tiếp ở trường
                    $createPayment = Payment::create([
                        'payment_method_id' => 1,
                        'payment_date' => date("Y-m-d h:i:s"),
                        'price' => $request->dong_hoc_phi,
                        'description' => $hocVienDangKi->ho_ten . ' đóng học phí trực tiếp tại trường',
                        'status' => 1,
                    ]);

                    //cập nhập lại đăng kí
                    $dangKy['trang_thai'] = 1;
                    $dangKy['id_payment'] = $createPayment->id;
                    $dangKy['paid_date'] = date("Y-m-d H:i:s");;
                    $dangKy->update();

                    //thành công rồi thì trừ slot của lớp đi 
                    $checkClass['slot'] = $checkClass->slot - 1;
                    $checkClass->update();
                    DB::commit();
                    return Redirect::back()->withErrors(['msg' => 'Cập nhập thành công']);
                } else {
                    return Redirect::back()->withErrors(['msg' => 'Lớp này đã khai giảng rồi hoặc đã hết slot']);
                }
            }
        } catch (\Exception $exception) {
            DB::rollback();
            Log::error('message: ' . $exception->getMessage() . 'line:' . $exception->getLine());
        }
    }

    public function doiKhoaHoc($request, $newDangKy, $newCourse, $idNewClass, $dangKyOld, $oldClass)
    {
        /// check xem số tiền nộp thêm == abs(du_no)
        if (isset($request->dong_them) &&  $request->dong_them != 0  && abs($newDangKy->du_no) == $request->dong_them) {
            try {
                dd('đóng thêm tiền');

                DB::beginTransaction();
                // dd($newDangKy->id_payment);
                $payMentOfDangKy = Payment::where('id', $newDangKy->id_payment)->first();
                $payMentOfDangKy['price'] = $payMentOfDangKy['price'] + $request->dong_them;
                $payMentOfDangKy['description'] = 'Sinh viên đóng thêm';
                $payMentOfDangKy->update();
                //cập nhật bảng dang_ky
                $newDangKy['trang_thai'] = 1;
                $newDangKy['paid_date'] = date("Y-m-d");
                $newDangKy['so_tien_da_dong'] = null;
                $newDangKy['du_no'] = 0;
                $newDangKy->update();

                //cập nhập lại slot trong lớp
                $classOfDangKy = ClassModel::whereId($newDangKy->id_lop_hoc)->first();
                $classOfDangKy['slot'] = $classOfDangKy->slot - 1;
                $classOfDangKy->update();
                DB::commit();
                return Redirect::back()->withErrors(['msg' => 'Chuyển lớp thành công chuyển trạng thái về ban đầu']);
            } catch (\Exception $exception) {
                DB::rollback();
                Log::error('message: ' . $exception->getMessage() . 'line:' . $exception->getLine());
            }
        } else {
            // trường hợp không nhập số tiền còn thiếu
            $checkClass = ClassModel::where('id', $idNewClass)->first();
            if ($checkClass->slot > 0) {
                $getPayMentOfOldDangKy = DangKy::where('id', $newDangKy->id_payment)->first();
                //Số tiền đã nộp
                $priceDaNop = $getPayMentOfOldDangKy->gia_tien;
                //cập nhập lại giá cho cái đang kí đấy nếu dư nợ = 0 thì trạng thái = 1 còn có dư nợ thì trạng thái = 0
                //giá tiền của lớp muốn chuyển sang
                $priceClassNew = ClassModel::where('id', $idNewClass)->first()->course->price;
                //lưu thoong tin chuyển lớp mới
                $idClassOld = $dangKyOld['id_lop_hoc'];
                $dangKyOld['id_lop_hoc'] =  $idNewClass;
                $dangKyOld['gia_tien'] =  $priceClassNew;
                $dangKyOld['so_tien_da_dong'] =  $priceDaNop;
                $dangKyOld['du_no'] =  $priceDaNop - $priceClassNew;
                //nếu có dư nợ
                if ($dangKyOld->du_no != 0) {
                    //nếu dư nợ nhỏ hơn 0 thì trạng thái  là 0, cộng slot ở lớp cũ
                    if ($dangKyOld->du_no  < 0) {
                        $dangKyOld['trang_thai'] =  0;
                        $dangKyOld->update();
                        // dd($dangKyOld->class->slot);
                        $classOld = ClassModel::whereId($idClassOld)->first();
                        $classOld['slot'] = $classOld->slot + 1;
                        $classOld->update();
                        return "Bạn đã chuyển lớp thành công và nợ   . $dangKyOld->du_no. vui lòng đóng tiền để học";
                        //nếu dư nợ lớn hơn 0 thì trạng thái vẫn là 1, cộng slot ở lớp cũ và  trừ 1 slot ở lớp mới
                    } elseif ($dangKyOld->du_no  > 0) {
                        //update xong ở bảng đăng kí thì phải +1 vào slot ở course vừa chuyển đi
                        $dangKyOld->update();
                        //cộng 1 slot vào lớp cũ
                        $classOld = ClassModel::whereId($idClassOld)->first();
                        $classOld['slot'] = $classOld->slot + 1;
                        $classOld->update();
                        //trừ 1 slot ở lớp mới (phải lấy lại cái đăng kí mới đã)
                        $classNew = ClassModel::whereId($dangKyOld->id_lop_hoc)->first();
                        $classNew['slot'] = $classNew->slot - 1;
                        $classNew->update();
                        return Redirect::back()->withErrors(['msg' => 'Bạn đã chuyển lớp thành công và thừa ' . $dangKyOld->du_no]);
                    }
                }
            }
            // hết slot thì không dc đki
            return Redirect::back()->withErrors(['msg' => 'Lớp này đã đủ sinh viên không thể đăng kí']);
        }
    }

    // trường hợp chuyển lớp thiếu tiền sau đó đến nộp trực tiếp thì sẽ vào hàm này


    // public function updateDangKy($id, Request $request)
    // {
    //     $now = date('Y-m-d');
    //     $objDangKy = new DangKy();
    //     $dangKy = $objDangKy->loadOne($id);
    //     $objLopHoc = new ClassModel();
    //     $lopHoc = $objLopHoc->loadOne($dangKy->id_lop_hoc);
    //     if ($dangKy->trang_thai == 1) {
    //         Session::flash('success', 'Đăng Ký Này Đã Thanh Toán Không Thể Thay Đổi');
    //         return redirect()->route('route_BackEnd_AdminDangKy_Detail', ['id' => $id]);
    //     } elseif ($lopHoc->start_date < $now) {
    //         Session::flash('success', 'Lớp Học Đã Khai Giảng Không Thay đổi');
    //         return redirect()->route('route_BackEnd_AdminDangKy_Detail', ['id' => $id]);
    //     } else {
    //         $arrDangKy = [];
    //         $arrDangKy['id'] = $id;
    //         $arrDangKy['id_lop_hoc'] = $request->id_lop_hoc;
    //         $arrDangKy['trang_thai'] = $request->trang_thai;
    //         $res = $objDangKy->updateDangKy($arrDangKy);
    //         if ($request->trang_thai == 1) {
    //             $objGuiGmail = DB::table('dang_ky', 'tb1')
    //                 ->select('tb1.id', 'tb1.gia_tien', 'tb2.ho_ten', 'tb2.email', 'tb3.name', 'tb4.price', 'tb4.ten_khoa_hoc', 'tb2.so_dien_thoai', 'tb1.trang_thai')
    //                 ->leftJoin('hoc_vien as tb2', 'tb2.id', '=', 'tb1.id_hoc_vien')
    //                 ->leftJoin('class as tb3', 'tb3.id', '=', 'tb1.id_lop_hoc')
    //                 ->leftJoin('khoa_hoc as tb4', 'tb3.course_id', '=', 'tb4.id')
    //                 ->where('tb1.id', $id)->first();
    //             $email = $objGuiGmail->email;
    //             // Mail::to($email)->send(new OrderShipped($objGuiGmail));
    //             $objLopHoc = new  ClassModel();
    //             $socho = $objLopHoc->loadOneID($request->id_lop_hoc);
    //             $udateSoCho = [];
    //             $udateSoCho['id'] = $request->id_lop_hoc;
    //             $udateSoCho['so_cho'] = $socho->slot - 1;
    //             $update = $objLopHoc->saveUpdateSoCho($udateSoCho);
    //         }
    //         if ($res == null) // chuyển trang vì trong session đã có sẵn câu thông báo lỗi rồi
    //         {
    //             return redirect()->route('route_BackEnd_AdminDangKy_Detail', ['id' => $id]);
    //         } elseif ($res == 1) {
    //             $request->session()->forget('post_form_data'); // xóa data post
    //             Session::flash('success', 'Cập nhật thành công!');

    //             return redirect()->route('route_BackEnd_AdminDangKy_Detail', ['id' => $id]);
    //         } else {

    //             Session::push('errors', 'Lỗi cập nhật cho bản ghi: ' . $res);
    //             Session::push('post_form_data', $this->v['request']);
    //             return redirect()->route('route_BackEnd_AdminDangKy_Detail', ['id' => $id]);
    //         }
    //     }
    // }
    // public function inHoaDon($id, Request $request)
    // {
    //     $emails = DB::table('dang_ky', 'tb1')
    //         ->select('tb1.id', 'tb1.gia_tien', 'tb2.ho_ten', 'tb3.name', 'tb4.price', 'tb4.name as course_name', 'tb2.so_dien_thoai', 'tb1.trang_thai')
    //         ->leftJoin('hoc_vien as tb2', 'tb2.id', '=', 'tb1.id_hoc_vien')
    //         ->leftJoin('class as tb3', 'tb3.course_id', '=', 'tb1.id_lop_hoc')
    //         ->leftJoin('course as tb4', 'tb3.course_id', '=', 'tb4.id')
    //         ->where('tb1.id', $id)->first();
    //     // dd($emails);
    //     $pdf = PDF::setOptions([
    //         'logOutputFile' => storage_path('logs/log.htm'),
    //         'tempDir' => storage_path('logs/')
    //     ])
    //         ->loadView('print.inhoadon', compact('emails'))->setPaper('a4');
    //     return $pdf->stream();
    // }
}
