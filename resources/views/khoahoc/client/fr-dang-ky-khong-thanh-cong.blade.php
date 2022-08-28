@extends('templates_frontend.layout')
@section('content')
    <div class="inner-page-banner-area" style="background-image: url({{ asset("front/img/banner/5.jpg") }});">
        <div class="container">
            <div class="pagination-area">
                <h1>404 Error</h1>
                <ul>
                    <li><a href="#">Home</a> -</li>
                    <li>Error</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Inner Page Banner Area End Here -->
    <!-- Error Page Area Start Here -->
    <div class="error-page-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="error-top">
                        <img src="{{ asset("front/img/banner/error.jpg") }}" class="img-responsive">
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="error-bottom">
                        <h2>Bạn đã đăng ký lớp học này.</h2>
                        <p>Vui lòng không đăng ký lại. Mọi thắc mắc xin liên hệ anh Lê hoàng Hải - 0898555917</p>
                        <a href="{{ url('/home') }}" class="default-white-btn">Trở lại trang chủ</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection