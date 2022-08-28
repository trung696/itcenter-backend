<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
//trang chủ
Route::get('/home', 'UserController@home');

//thống kê
Route::get('/thongke', 'ThongKeController@thongKeCung');
//export class
Route::get('/class/in-danh-sach/{id}', 'ClassController@inDanhSachLop')
    ->where(
        'id',
        '[0-9]+'
    )
    ->name('route_danhsachlop');
//mail xác nhận 
Route::get('/accept-payment/{id}/{token}', 'DangKyController@acceptDangKy')
    ->name('route_accept');

// fontend khoá học
Route::get('/khoa-hoc/detail/{id}', 'KhoaHocController@fontendDanhSachKhoaHoc')
    ->where('id', '[0-9]+')
    ->name('route_BackEnd_UserKhoaHoc_Detail');

// frontend lớp học và đăng ký
Route::get('/lop-hoc/detail/{id}', 'LopHocController@frontendDanhSachLopHoc')
    ->where('id', '[0-9]+')
    ->name('route_BackEnd_UserLopHoc_Detail');
Route::get('/dangky/lop/{id}', 'LopHocController@frDangKyLopHoc')
    ->where('id', '[0-9]+')
    ->name('route_BackEnd_UserDangKyLopHoc');
Route::get('/dang-ky-thanh-cong', 'LopHocController@frontendDangKyThanhCong')->name('route_BackEnd_UserDangKyLopHocThanhCong');
Route::get('/dang-ky-khong-thanh-cong', 'LopHocController@frontendDangKyKhongThanhCong')->name('route_BackEnd_UserDangKyLopHocKhongThanhCong');

//back-end đăng kí lớp
Route::get('/registerlist', 'DangKyController@danhSachDangKy')
    ->name('route_BackEnd_DanhSachDangKy_index')->middleware(['can:dang-ky-list']);
Route::match(['get', 'post'], '/register/add', 'DangKyController@themDangKy')
    ->name('route_BackEnd_DangKyAdmin_Add')->middleware(['can:dang-ky-add']);
Route::get('/list-lop/{id}', 'DangKyController@getListLop')->where('id', '[0-9]+')->name('route_BackEnd_admin_getListLop'); //->middleware(['can:BackEnd_Admin_getListHuyen']);
Route::get('/register/detail/{id}', 'DangKyController@chiTietDangKy')
    ->where('id', '[0-9]+')
    ->name('route_BackEnd_AdminDangKy_Detail')->middleware(['can:dang-ky-edit']);
Route::post('/register/update/{id}/{email}/{oldClass}', 'DangKyController@update')
    ->where('id', '[0-9]+')
    ->name('route_BackEnd_AdminDangKy_Update')->middleware(['can:dang-ky-edit']);
Route::get('/dang-ky/in-hoa-don/{id}', 'DangKyController@inHoaDon')
    ->where('id', '[0-9]+')
    ->name('route_BackEnd_AdminDangKyIn_Detail');


//ca học 
//role(doanh)   
Route::get('/ca', 'CaController@index')->name('route_BackEnd_Ca_List')->middleware(['can:ca-list']);
Route::match(['get', 'post'], '/ca/add', 'CaController@addCa')
    ->name('route_BackEnd_Ca_Add')->middleware(['can:ca-add']);
Route::get('/ca/detail/{id}', 'CaController@editCa')
    ->where('id', '[0-9]+')
    ->name('route_BackEnd_Ca_Edit')->middleware(['can:ca-edit']);
Route::post('/Ca/update/{id}', 'CaController@updateCa')
    ->where('id', '[0-9]+')
    ->name('route_BackEnd_Ca_Update')->middleware(['can:ca-edit']);
Route::get('/Ca/delete/{id}', 'CaController@destroy')
    ->where('id', '[0-9]+')
    ->name('route_BackEnd_Ca_Delete')->middleware(['can:ca-delete']);
// Route::get('/edit_role/{id}', 'RoleController@edit')->name('route_BackEnd_role_edit');
// Route::post('/edit_role/{id}', 'RoleController@update')->name('route_BackEnd_role_update');
// Route::get('/delete_role/{id}', 'RoleController@delete')->name('route_BackEnd_role_delete');

//xếp lớp

Route::get('/xeplop/{id}', 'ClassController@xepLop')
    ->where('id', '[0-9]+')
    ->name('route_BackEnd_XepLop');
//list_đổi lớp
Route::get('/doiLop', 'DoiLopController@index')->name('route_BackEnd_list_doi_lop')->middleware(['can:danh-sach-doi-lop-list']);
Route::get('/doiLop/{id}/{email}/{oldClass}/{newClass}', 'DoiLopController@doiLop')->name('route_BackEnd_doi_lop')->middleware(['can:danh-sach-doi-lop-edit']);
Route::get('/doiLopEr/{id}/{email}/{oldClass}/{newClass}', 'DoiLopController@doiLopEr')->name('route_BackEnd_doi_lop_er')->middleware(['can:danh-sach-doi-lop-edit']);


//list những đăng ký thừa tiền
Route::get('/hoanTien','HoanTienController@index')->name('route_BackEnd_list_hoan_tien')->middleware(['can:hoan-tien-list']);
Route::get('/hoanTienDu/{id}', 'HoanTienController@hoanTienDu')->name('route_BackEnd_edit_thua_tien_hoan_tien')->middleware(['can:hoan-tien-edit']);
Route::get('/hoanTien/{id}', 'HoanTienController@edit')->name('route_BackEnd_edit_hoan_tien')->middleware(['hoan-tien-edit']);
Route::post('/hoanTien', 'HoanTienController@search')->name('route_BackEnd_edit_search')->middleware(['can:hoan-tien-list']);


// thêm thông tin sinh mới đăng ký
Route::match(['get', 'post'], '/dangky-thongtinsinhvien', 'LopHocController@themDangKy')->name('route_BackEnd_DangKyLopHoc_Add');

// check mã khuyến mãi để giảm học phí
Route::match(['get', 'post'], '/check-coupon', 'MaChienDichController@checkcoupon')->name('route_BackEnd_CheckCoupon_Check');
Route::middleware(['auth'])->group(function () {
    // Sửa đường dẫn trang chủ mặc định

    Route::get('/', 'HocsinhController@index');
    Route::get('/admin/home', 'HocsinhController@index');

    // Route::get('/user', 'UserController@index')->name('route_BackEnd_NguoiDung_index');

    // Route::match(['get', 'post'], '/user/add', 'UserController@add')->name('route_BackEnd_NguoiDung_Add');

    Route::get('/user/detail/{id}', 'UserController@detail')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_NguoiDung_Detail');

    Route::post('/user/update/{id}', 'UserController@update')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_NguoiDung_Update');
});
//    ->middleware(['can:BackEnd_QuanLyDaoTao_taoDanhSachThi']);
//Route::match(['get', 'post'], '/user/add', 'BackEnd\BoDeThiController@add')->name('route_BackEnd_DeThi_Add');
//    ->middleware(['can:BackEnd_QuanLyDaoTao_taoDanhSachThi']);
// Đăng ký thành viên
// Route::get('register', 'Auth\RegisterController@getRegister');
// Route::post('register', 'Auth\RegisterController@postRegister');

// Đăng nhập và xử lý đăng nhập
Route::get('login', ['as' => 'login', 'uses' => 'Auth\LoginController@getLogin']);
Route::post('login', ['as' => 'login', 'uses' => 'Auth\LoginController@postLogin']);

// Đăng xuất
Route::get('logout', ['as' => 'logout', 'uses' => 'Auth\LogoutController@getLogout']);

// danh mục tài sản
Route::middleware(['auth'])->group(function () {

    Route::get('/taisan-category', 'TaiSanController@danhMucTaiSan')->name('route_BackEnd_DanhMucTaiSan_index');

    Route::match(['get', 'post'], '/taisan-category/add', 'TaiSanController@themDanhMucTaiSan')->name('route_BackEnd_DanhMucTaiSan_Add');

    Route::get('/taisan-category/detail/{id}', 'TaiSanController@chiTietDanhMucTaiSan')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_DanhMucTaiSan_Detail');

    Route::post('/taisan-category/update/{id}', 'TaiSanController@updateChiTietDanhMucTaiSan')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_DanhMucTaiSan_Update');



    // tài sản
    Route::get('/taisan', 'TaiSanController@TaiSan')->name('route_BackEnd_TaiSan_index');

    Route::match(['get', 'post'], '/taisan/add', 'TaiSanController@themTaiSan')->name('route_BackEnd_TaiSan_Add');

    Route::get('/taisan/detail/{id}', 'TaiSanController@chiTietTaiSan')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_TaiSan_Detail');

    Route::post('/taisan/update/{id}', 'TaiSanController@updateChiTietTaiSan')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_TaiSan_Update');

    // đơn vị/soluongtaisan/add
    Route::get('/donvi', 'DonViController@donVi')->name('route_BackEnd_DonVi_index');

    Route::match(['get', 'post'], '/donvi/add', 'DonViController@themDonVi')->name('route_BackEnd_DonVi_Add');

    Route::get('/donvi/detail/{id}', 'DonViController@chiTietDonVi')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_DonVi_Detail');

    Route::post('/donvi/update/{id}', 'DonViController@updateChiTietDonVi')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_DonVi_Update');

    // thêm tài sản con
    Route::match(['get', 'post'], '/taisancon/add', 'TaiSanConController@themTaiSanCon')->name('route_BackEnd_TaiSanCon_Add');
    Route::match(['get', 'post'], '/soluongtaisan/add', 'TaiSanController@themSoLuongTaiSan')->name('route_BackEnd_addSoLuongTaiSan_Add');
    Route::get('/taisancon/detail/{id}/{idTaiSan}', 'TaiSanConController@chiTietTaiSanCon')
        //        ->where('id', '[0-9]+')
        ->name('route_BackEnd_TaiSanCon_Detail');
    Route::post('/taisancon-category/update/{id}', 'TaiSanConController@updateChiTietTaiSanCon')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_TaiSanCon_Update');

    Route::get('/taisancon/print/{id}', 'TaiSanConController@inNhanTaiSanCon')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_TaiSanCon_InNhanTaiSan_Update');


    Route::get('/taisancon/delete/{id}', 'TaiSanConController@deleteTaiSanCon')
        ->name('route_BackEnd_TaiSanCon_Delete');
    //bienbanbangiao
    Route::get('/bienbanbangiao/print/{id}', 'TaiSanConController@inBienBanBanGiao')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_TaiSanCon_InBienBanBanGiao_Update');

    Route::get('/bienbankiemke/print/{id}', 'TaiSanConController@inBienBanKiemKe')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_TaiSanCon_InBienBanKiemKe_Update');

    // thêm lịch sử sửa chữa
    Route::match(['get', 'post'], '/lichsusuachua/add', 'LichSuSuaChuaController@themLichSuSuaChua')->name('route_BackEnd_LichSuSuaChua_Add');

    // thêm biên bản
    Route::get('/bienban', 'BienBanController@bienBan')->name('route_BackEnd_BienBan_index');
    Route::match(['get', 'post'], '/bienban/add', 'BienBanController@themBienBan')->name('route_BackEnd_BienBan_Add');
    Route::get('/bienban/detail/{id}', 'BienBanController@chiTietBienBan')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_BienBan_Detail');
    Route::post('/bienban/update/{id}', 'BienBanController@updateChiTietBienBan')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_BienBan_Update');
    Route::get('/bienban/delete/{id}', 'BienBanController@deleteBienBan')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_BienBan_Delete');
    // biên bản kiểm kê
    Route::get('/bienbankiemke', 'BienBanController@bienBanKiemKe')->name('route_BackEnd_BienBanKiemKe_index');
    // biên bản thanh lí
    Route::get('/bienbanthanhli', 'BienBanController@bienBanThanhLi')->name('route_BackEnd_BienBanThanhLi_index');
    Route::get('/bienbanthanhli/print', 'TaiSanConController@inBienBanThanhLi')
        //        ->where('id', '[0-9]+')
        ->name('route_BackEnd_TaiSanCon_InBienBanThanhLi_Update');
    //danh mục khoá học
    Route::get('/danh-muc-khoa-hoc', 'DanhMucKhoaHocController@danhMucKhoaHoc')
        ->name('route_BackEnd_DanhMucKhoaHoc_List');
    Route::match(['get', 'post'], '/danh-muc-khoa-hoc/them', 'DanhMucKhoaHocController@themDanhMucKhoaHoc')
        ->name('route_BackEnd_DanhMucKhoaHoc_Add');
    Route::get('/danh-muc-khoa-hoc/chi-tiet/{id}', 'DanhMucKhoaHocController@chitetDanhMucKhoaHoc')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_DanhMucKhoaHoc_Detail');
    Route::post('/danh-muc-khoa-hoc/sua/{id}', 'DanhMucKhoaHocController@updateDanhMucKhoc')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_DanhMucKhoaHoc_Update');
    //khoá học
    Route::get('/khoahoc-list.html', 'KhoaHocController@khoaHoc')
        ->name('route_BackEnd_KhoaHoc_index');
    Route::match(['get', 'post'], '/khoa-hoc/add', 'KhoaHocController@themKhoaHoc')
        ->name('route_BackEnd_KhoaHoc_Add');
    Route::get('/khoahoc-khoa-hoc/detail/{id}', 'KhoaHocController@chiTietKhoaHoc')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_KhoaHoc_Detail');
    Route::post('/khoahoc/update/{id}', 'KhoaHocController@updateKhoaHoc')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_KhoaHoc_Update');
    //lớp học
    Route::match(['get', 'post'], '/lop-hoc/add', 'LopHocController@themLopHoc')->name('route_BackEnd_addLopHoc_Add');
    Route::get('/khoahoc-lop-hoc/detail/{id}', 'LopHocController@chiTietLopHoc')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_LopHoc_Detail');
    Route::get('/lophoc/print/{id}', 'LopHocController@inDanhSachLopHoc')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_LopHoc_InLopHoc_Update');
    //Khuyến mãi
    Route::get('/khuyenmai-lists', 'KhuyenMaiController@danhSachKhuyenMai')
        ->name('route_BackEnd_DanhSachKhhuyenMai_index');
    Route::match(['get', 'post'], '/khuyen-mai/add', 'KhuyenMaiController@themKhuyenMai')
        ->name('route_BackEnd_DanhSachKhhuyenMai_Add');
    //chiến dịch
    Route::get('/chien-dich', 'ChienDichController@listChienDich')
        ->name('route_BackEnd_ChienDich_index');
    Route::match(['get', 'post'], '/chien-dich/add', 'ChienDichController@themChienDich')
        ->name('route_BackEnd_ChienDich_Add');
    Route::get('/chien-dich/chi-tiet/{id}', 'ChienDichController@chitetChienDich')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_ChienDich_Detail');
    Route::post('/chien-dich/update/{id}', 'ChienDichController@updateChienDich')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_ChienDich_Update');
    Route::get('/chien-dich/delete/{id}', 'ChienDichController@dungChiendich')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_ChienDich_Delete');
    //mã chiến dịch
    Route::match(['get', 'post'], '/ma-chien-dich/add', 'MaChienDichController@taoMaChienDich')
        ->name('route_BackEnd_MaChienDich_Add');
    //học viên
    Route::get('/danh-sach-hoc-vien', 'HocVienController@danhSachHocVien')
        ->name('route_BackEnd_DanhSachHocVien_index')->middleware(['can:student-list']);
    Route::get('/danh-sach-hoc-vien/chi-tiet/{id}', 'HocVienController@chiTietHocVien')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_DanhSachHocVien_Detail')->middleware(['can:student-edit']);
    Route::post('/danh-sach-hoc-vien/update/{id}', 'HocVienController@updateThongTin')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_DanhSachHocVien_Update')->middleware(['can:can:student-edit']);

    //địa điểm
    Route::get('/danh-sach-dia-diem', 'DiaDiemController@danhSachDiaDiem')
        ->name('route_BackEnd_DanhSachDiaDiem_index');
    Route::match(['get', 'post'], '/dia-diem/add', 'DiaDiemController@themDiaDiem')
        ->name('route_BackEnd_DiaDiem_Add');
    Route::get('/dia-diem/chi-tiet/{id}', 'DiaDiemController@chitetDiaDiem')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_DiaDiem_Detail');
    Route::post('/dia-diem/update/{id}', 'DiaDiemController@updateDiaDiem')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_DiaDiem_Update');

    //permission 
    Route::get('/add_permission', 'PermissionController@add')
        ->name('route_BackEnd_permission_add');
    Route::post('/add_permission', 'PermissionController@store')
        ->name('route_BackEnd_permission_store');


    //role(doanh)   
    Route::get('/list_role', 'RoleController@index')->name('route_BackEnd_role_list');
    Route::get('/add_role', 'RoleController@add')->name('route_BackEnd_role_add');
    Route::post('/create_role', 'RoleController@store')->name('route_BackEnd_role_store');
    Route::get('/edit_role/{id}', 'RoleController@edit')->name('route_BackEnd_role_edit');
    Route::post('/edit_role/{id}', 'RoleController@update')->name('route_BackEnd_role_update');
    Route::get('/delete_role/{id}', 'RoleController@delete')->name('route_BackEnd_role_delete');

    //end role(doanh)

    //user(doanh)
    Route::get('/user/add', 'UserController@formAdd')->name('route_BackEnd_user_add')->middleware(['can:user-add']);
    Route::get('/user', 'UserController@index')->name('route_BackEnd_NguoiDung_index')->middleware(['can:user-list']);
    Route::post('/user/search', 'UserController@search')->name('route_BackEnd_user_search');


    Route::post('/user/add', 'UserController@store')->name('route_BackEnd_user_store')->middleware(['can:user-add']);
    Route::get('/user/edit/{id}', 'UserController@edit')->name('route_BackEnd_user_edit')->middleware(['can:user-edit']);
    Route::post('/user/edit/{id}', 'UserController@update')->name('route_BackEnd_user_update')->middleware(['can:user-edit']);
    Route::get('/user/delete/{id}', 'UserController@delete')->name('route_BackEnd_user_delete')->middleware(['can:user-delete']);
    Route::get('/user/deleteSelect', 'UserController@deleteCheckbox')->name('route_BackEnd_user_delete_checkbox')->middleware(['can:user-delete']);

    Route::get('api/user/{user}/{token}', [App\Http\Controllers\ActiveUserController::class, 'active'])->where(['id' => '[0-9]+,[a-z]+'])->name('active.user');

    //end user

    //teacher (doanh)
    Route::get('/teacher', 'TeacherController@index')->name('route_BackEnd_teacher_list')->middleware(['can:teacher-list']);
    Route::get('/teacher/edit/{id}', 'TeacherController@edit')->name('route_BackEnd_teacher_edit')->middleware(['can:teacher-edit']);
    Route::post('/teacher/edit/{id}', 'TeacherController@update')->name('route_BackEnd_teacher_update')->middleware(['can:teacher-edit']);
    //end student(doanh)
    Route::get('/student', 'StudentController@index')->name('route_BackEnd_student_list');
    Route::get('/student/edit/{id}', 'StudentController@edit')->name('route_BackEnd_student_edit');
    Route::post('/student/edit/{id}', 'StudentController@update')->name('route_BackEnd_student_update');

    //register

    //đăng klí lớp học
    Route::get('/lop-hoc/detail/{id}', 'LopHocController@frontendDanhSachLopHoc')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_UserLopHoc_Detail');

    Route::get('/dangky/lop/{id}', 'LopHocController@frDangKyLopHoc')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_UserDangKyLopHoc');
    Route::get('/dang-ky-thanh-cong', 'LopHocController@frontendDangKyThanhCong')->name('route_BackEnd_UserDangKyLopHocThanhCong');
    Route::get('/dang-ky-khong-thanh-cong', 'LopHocController@frontendDangKyKhongThanhCong')->name('route_BackEnd_UserDangKyLopHocKhongThanhCong');



    // Trang Client 
    Route::prefix('client')->group(function () {
        Route::get('/form', 'Client\FormContactController@add')->name('route_frontend_add');
        Route::post('/form', 'Client\FormContactController@store')->name('route_frontend_store');
    });
    //End  Trang Client 


    Route::match(['get', 'post'], '/chien-dich/add', 'ChienDichController@themChienDich')
        ->name('route_BackEnd_ChienDich_Add');
    Route::get('/chien-dich/chi-tiet/{id}', 'ChienDichController@chitetChienDich')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_ChienDich_Detail');
    Route::post('/chien-dich/update/{id}', 'ChienDichController@updateChienDich')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_ChienDich_Update');
    Route::get('/chien-dich/delete/{id}', 'ChienDichController@dungChiendich')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_ChienDich_Delete');

    //course category(dai)
    Route::get('/course-category', 'CourseCategoryController@courseCategory')
        ->name('route_BackEnd_CourseCategory_List')->middleware(['can:course-category-list']);
    Route::match(['get', 'post'], '/course-category/add', 'CourseCategoryController@AddCourseCategory')
        ->name('route_BackEnd_CourseCategory_Add')->middleware(['can:course-category-add']);
    Route::get('/course-category/detail/{id}', 'CourseCategoryController@courseCategoryDetail')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_CourseCategory_Detail')->middleware(['can:course-category-edit']);;
    Route::post('/course-category/edit/{id}', 'CourseCategoryController@updateCourseCategory')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_CourseCategory_Update')->middleware(['can:course-category-edit']);
    Route::get('/course-category/delete/{id}', 'CourseCategoryController@destroy')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_CourseCategory_Delete')->middleware(['can:course-category-delete']);
    //course
    Route::get('/course', 'CourseController@course')
        ->name('route_BackEnd_Course_List')->middleware(['can:course-list']);
    Route::match(['get', 'post'], '/course/add', 'CourseController@AddCourse')
        ->name('route_BackEnd_Course_Add')->middleware(['can:course-add']);
    Route::get('/Course-Class/detail/{id}', 'CourseController@CourseDetail')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_Course_Detail')->middleware(['can:course-edit']);
    Route::post('/Course/update/{id}', 'CourseController@updateCourse')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_Course_Update')->middleware(['can:course-edit']);
    Route::get('/Course/delete/{id}', 'CourseController@destroy')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_Course_Delete')->middleware(['can:course-delete']);

    //class 
    Route::get('/class', 'ClassController@classList')
        ->name('route_BackEnd_Class_List')->middleware(['can:class-list']);
    Route::match(['get', 'post'], '/class/add', 'ClassController@addClass')
        ->name('route_BackEnd_Class_Add')->middleware(['can:class-add']);
    Route::get('/class/detail/{id}', 'ClassController@classDetail')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_Class_Detail')->middleware(['can:class-edit']);
    Route::post('/class/update/{id}', 'ClassController@updateClass')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_Class_Update')->middleware(['can:class-edit']);
    Route::get('/class/delete/{id}', 'ClassController@destroy')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_Class_Delete')->middleware(['can:class-delete']);

    Route::get('/class/danhsach/{id}', 'ClassController@showDanhSachLop')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_Class_danh_sach');

    //central facility
    Route::get('/central-facility', 'CentralFacilityController@listCentralFacility')
        ->name('route_BackEnd_CentralFacility_List')->middleware(['can:co-so-list']);
    Route::match(['get', 'post'], '/central-facility/add', 'CentralFacilityController@AddCentralFacility')
        ->name('route_BackEnd_CentralFacility_Add')->middleware(['can:co-so-add']);
    Route::get('/central-facility/detail/{id}', 'CentralFacilityController@centralFacilityDetail')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_CentralFacility_Detail')->middleware(['can:co-so-edit']);
    Route::post('/central-facility/update/{id}', 'CentralFacilityController@updateCentralFacility')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_CentralFacility_Update')->middleware(['can:co-so-edit']);
    Route::get('/central-facility/delete/{id}', 'CentralFacilityController@destroy')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_CentralFacility_Delete')->middleware(['can:co-so-delete']);

    //document
    Route::get('/document', 'DocumentController@document')
        ->name('route_BackEnd_Document_List');
    Route::match(['get', 'post'], '/document/add', 'DocumentController@AddDocument')
        ->name('route_BackEnd_Document_Add');
    Route::get('/document/detail/{id}', 'DocumentController@documentDetail')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_Document_Detail');
    Route::post('/document/update/{id}', 'DocumentController@updateDocument')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_Document_Update');
    Route::get('/document/delete/{id}', 'DocumentController@destroy')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_Document_Delete');

    //payment method
    Route::get('/payment-method', 'PaymentMethodController@paymentMethod')
        ->name('route_BackEnd_PaymentMethod_List');
    Route::match(['get', 'post'], '/payment-method/add', 'PaymentMethodController@AddPaymentMethod')
        ->name('route_BackEnd_PaymentMethod_Add');
    Route::get('/payment-method/detail/{id}', 'PaymentMethodController@paymentMethodDetail')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_PaymentMethod__Detail');
    Route::post('/payment-method/update/{id}', 'PaymentMethodController@updatePaymentMethod')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_PaymentMethod__Update');
    Route::get('/payment-method/delete/{id}', 'PaymentMethodController@destroy')
        ->where('id', '[0-9]+')
        ->name('route_BackEnd_PaymentMethod_Delete');

        //thong ke
        Route::get('/thong-ke', 'ThongkeController@thongke')
        ->name('route_BackEnd_ThongKe');
});
