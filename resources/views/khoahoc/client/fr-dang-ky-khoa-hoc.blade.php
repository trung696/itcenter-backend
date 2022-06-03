@extends('templates_frontend.layout')
@section('content')
    <!-- Inner Page Banner Area Start Here -->
<div class="inner-page-banner-area" style="background-image: url('img/banner/5.jpg');">
    <div class="container">
        <div class="pagination-area">
            <h1>Contact Us 01</h1>
            <ul>
                <li><a href="#">Home</a> -</li>
                <li>Contact</li>
            </ul>
        </div>
    </div>
</div>
<!-- Inner Page Banner Area End Here -->
<!-- Contact Us Page 1 Area Start Here -->

<div class="contact-us-page1-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-8 col-sm-8 col-xs-12">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <h2 class="title-default-center">Đăng Ký Lớp Học</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <h5 class="title-default-center">Thông Tin Đăng Ký Sẽ Được Gửi Về Gmail Của Bạn</h5>
                                <p class="text-center">Tên Khoá Học: {{$objKhoaHoc->ten_khoa_hoc}}</p>
                                <p class="text-center">Tên Lớp Học: {{$objItemLopHoc->ten_lop_hoc}}</p>
{{--                                <p class="text-center">Ca Học: {{$objItemLopHoc->ca_hoc}}</p>--}}
                                <p class="text-center">Ngày Khai Giảng: {{$objItemLopHoc->thoi_gian_khai_giang}}</p>
                                <p class="text-center" style="font-size: 15px; color: red">Học Phí: {{$objKhoaHoc->hoc_phi}}</p>

                    </div>
                </div>
                <div class="row">
                    <div class="contact-form1">
                        <form id="contact-form" method="POST" action="{{ route('route_BackEnd_DangKyLopHoc_Add') }}">
                            @csrf
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <fieldset>
                                <input type="text" placeholder="Phone*" class="form-control hidden" name="gia_tien" value="{{$objKhoaHoc->hoc_phi}}" >
                                <input type="text" placeholder="Phone*" class="form-control hidden" name="ten_khoa_hoc" value="{{$objKhoaHoc->ten_khoa_hoc}}" disabled>
                                <input type="hidden"  class="form-control" name="id_lop_hoc" value="{{$objItemLopHoc->id}}" >
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Họ và tên</label>
                                        <input type="text" placeholder="Họ và tên*" class="form-control" name="ho_ten" id="form-name" data-error="Name field is required">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Ngày sinh</label>
                                        <input type="date" placeholder="Ngày sinh*" class="form-control" name="ngay_sinh" id="form-date" data-error="Email field is required">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" placeholder="Email*" class="form-control" name="email" id="form-email" data-error="Email field is required" >
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Số điện Thoại</label>
                                        <input  type="text" placeholder="Phone*" class="form-control" name="so_dien_thoai" id="form-email" minlength="10" maxlength="10" data-error="Email field is required" >
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Mã Khuyến Mại</label>
                                        <input type="hidden" name="txtMoney" id="txtMoney" value="{{$objKhoaHoc->hoc_phi}}"/></br>
                                        <input type="hidden" name="txtDiscount" class="txtDiscount" value=""/>
                                        <input  type="text" placeholder="Nhâp Mã Khuyến Mại" class="form-control" name="ma_khuyen_mai" id="txtCoupon" data-error="Email field is required" >
                                        <div class="help-block with-errors"></div>
                                        <p id="result"></p>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-6 col-sm-12" style="margin-top: 28px">
                                    <div class="form-group margin-bottom-none">
                                        <button type="button" class="default-big-btn" id="btnKhuyenMai" name="btnKhuyenMai">Áp Dụng</button>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-6 col-sm-12" style="margin-top: 30px; margin-left: -190px; ">
                                    <div class="form-group margin-bottom-none">
                                        <button type="button" class="default-big-btn"  id="Save">Đăng Ký</button>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-6 col-sm-12" style="margin-top: 30px; margin-left: -780px; ">
                                    <div class="form-group margin-bottom-none">
                                            <input type="hidden" id="stripeToken" name="stripeToken" />
                                            <input type="hidden" id="stripeEmail" name="stripeEmail" />
                                            <input type="hidden" id="amountInCents" name="amountInCents" />
                                            <input type="hidden" name="txtDiscount" class="txtDiscount" value=""/>
                                            <button type="button" class="default-big-btn" id="customButton">Thanh toán Pay</button>
                                    </div>
                                </div>
                            </fieldset>

                        </form>
{{--                        <form id="myForm" action="{{ route('route_BackEnd_DangKyLopHoc_Add') }}" method="POST">--}}
{{--                            <input type="hidden" id="stripeToken" name="stripeToken" />--}}
{{--                            <input type="hidden" id="stripeEmail" name="stripeEmail" />--}}
{{--                            <input type="hidden" id="amountInCents" name="amountInCents" />--}}
{{--                            <input type="hidden" name="txtDiscount" class="txtDiscount" value=""/></br>--}}
{{--                        </form>--}}

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="google-map-area">
                <div id="googleMap" style="width:100%; height:395px;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Contact Us Page 1 Area End Here -->
<!-- Footer Area Start Here -->
@endsection