<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('contacts', 'Api\ApiContactController');
Route::get('/user/{user}/{token}', [App\Http\Controllers\UserController::class, 'active'])->where(['id' => '[0-9]+,[a-z]+'])->name('active.user');
Route::apiResource('category', 'Api\ApiCategoryController');


//add new route 

// khoa hoc ( post : /api/user -- thêm user
//            patch :  /api/user/{id} -- sửa thông tin user đó
//            get :  /api/user/{id} -- lấy thông tin user đó )
Route::apiResource('user', 'Api\ApiUserController');
Route::patch('user/update/{id}','Api\ApiUserController@update')->middleware('checkTokenUp');
// Route::patch('user/update/{id}','App\Http\Controllers\Api\ApiUserController@update')->middleware('checkTokenUp');


// khoa hoc ( get : /api/categories -- lấy tất cả khóa học
//            get : /api/categories/$id -- lấy tất cả các lớp học của khóa học  đó )
Route::apiResource('categories','Api\ApiCategoryController');

// danh muc khoa hoc ( get : /api/cource -- lấy tất cả khóa học
//            get : /api/cource/$id -- lấy tất cả các lớp học của khóa học  đó )
Route::apiResource('course','Api\ApiCourceController');

// app\Http\Controllers\Api\ApiGetKhoaHocOfUser.php
// Login ( post :    /api/login -- login hệ thống
//         delete : /api/logout  -- logout hệ thống)
Route::apiResource('login','Api\ApiLoginController');
Route::delete('logout','Api\ApiLoginController@deleteToken');


//xem danh muc khoa hoc ma user da dang ki
// danhMucOfUser ( get :    /api/danhMucOfUser -- lấy danh mục khó học mà user đã đăng kí
Route::apiResource('danhMucOfUser','Api\ApiGetKhoaHocOfUser')->middleware('checkTokenUp');


Route::apiResource('registerClass','Api\ApiRegisterClassController');

// // Lấy thông tin sản phẩm theo id
// Route::get('products/{id}', 'Api\ProductController@show')->name('products.show');

// // Thêm sản phẩm mới
// Route::post('products', 'Api\ProductController@store')->name('products.store');

// // Cập nhật thông tin sản phẩm theo id
// # Sử dụng put nếu cập nhật toàn bộ các trường
// Route::put('products/{id}', 'Api\ProductController@update')->name('products.update');
// # Sử dụng patch nếu cập nhật 1 vài trường
// Route::patch('products/{id}', 'Api\ProductController@update')->name('products.update');

// // Xóa sản phẩm theo id
// Route::delete('products/{id}', 'Api\ProductController@destroy')->name('products.destroy');
