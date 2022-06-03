<?php
$danh_muc_khoa_hoc = new \App\DanhMucKhoaHoc();
$danh_muc=$danh_muc_khoa_hoc->loadListWithPager([]);

?>
<!doctype html>
<html class="no-js" lang="">


<!-- Mirrored from www.radiustheme.com/demo/html/academics/academics/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 19 Sep 2018 14:20:19 GMT -->
<!-- Added by HTTrack --><meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Academics | Home 1</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="img/favicon.png">
    <!-- Normalize CSS -->

    <link rel="stylesheet" href="{{ asset('front/css/normalize.css')}}">
    <!-- Main CSS -->

    <link rel="stylesheet" href="{{ asset('front/css/main.css')}}">
    <!-- Bootstrap CSS -->

    <link rel="stylesheet" href="{{ asset('front/css/bootstrap.min.css')}}">
    <!-- Animate CSS -->

    <link rel="stylesheet" href="{{ asset('front/css/animate.min.css')}}">
    <!-- Font-awesome CSS-->

    <link rel="stylesheet" href="{{ asset('front/css/font-awesome.min.css')}}">
    <!-- Owl Caousel CSS -->

    <link rel="stylesheet" href="{{ asset('front/vendor/OwlCarousel/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{ asset('front/vendor/OwlCarousel/owl.theme.default.min.css')}}">
    <!-- Main Menu CSS -->

    <link rel="stylesheet" href="{{ asset('front/css/meanmenu.min.css') }}">
    <!-- nivo slider CSS -->

    <link rel="stylesheet" href="{{ asset('front/vendor/slider/css/nivo-slider.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('front/vendor/slider/css/preview.css') }}" type="text/css" media="screen" />
    <!-- Datetime Picker Style CSS -->

    <link rel="stylesheet" href="{{ asset('front/css/jquery.datetimepicker.css') }}">
    <!-- Magic popup CSS -->

    <link rel="stylesheet" href="{{ asset('front/css/magnific-popup.css') }}">
    <!-- Switch Style CSS -->

    <link rel="stylesheet" href="{{ asset('front/css/hover-min.css') }}">
    <!-- ReImageGrid CSS -->

    <link rel="stylesheet" href="{{ asset('front/css/reImageGrid.css') }}">
    <!-- Custom CSS -->

    <link rel="stylesheet" href="{{ asset('front/style.css') }}">
    <!-- Modernizr Js -->

    <script src="{{ asset('front/js/modernizr-2.8.3.min.js') }}"></script>
</head>

<body>
<!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->
<!-- Add your site or application content here -->
<!-- Preloader Start Here -->
<div id="preloader"></div>
<!-- Preloader End Here -->

<!-- Main Body Area Start Here -->
<div id="wrapper">
    <!-- Header Area Start Here -->
    <header>
        <div id="header1" class="header1-area">
            <div class="main-menu-area bg-primary" id="sticker">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-2 col-md-2 col-sm-3">
                            <div class="logo-area">
                                <a href="{{ url('/home') }}"><img class="img-responsive" src="/img/logott01.png" alt="logo"></a>
                            </div>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-9">
                            <nav id="desktop-nav">
                                <ul>
                                    <li ><a href="{{ url('/home') }}">Trang Chủ</a></li>
                                    <li><a href="#">Danh Mục Khoá Học</a>
                                        <ul>
                                            @foreach($danh_muc as $item)
                                            <li><a href="{{ route('route_BackEnd_UserKhoaHoc_Detail',['id'=> $item->id]) }}">{{$item->ten_danh_muc}} </a></li>
                                            @endforeach
                                        </ul>
                                    </li>
                                    <li><a href="#">Giảng Viên</a></li>
                                    <li><a href="#">Liên Hệ</a></li>
                                </ul>
                            </nav>
                        </div>
                        <div class="col-lg-2 col-md-2 hidden-sm">
                            <div class="apply-btn-area">
                                <a href="#" class="apply-now-btn">Đăng Nhập</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    @yield('content')
    <footer>
        <div class="footer-area-top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="footer-box">
                            <a href="#"><img class="img-responsive" src="/img/logott01.png" style="width: 220px;" alt="logo"></a>
                            <div class="footer-about">
{{--                                <p>Praesent vel rutrum purus. Nam vel dui eu sus duis dignissim dignissim. Suspenetey disse at ros tecongueconsequat.Fusce sit amet rna feugiat.</p>--}}
                            </div>
                            <ul class="footer-social">
                                <li><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                                <li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                                <li><a href="#"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
                                <li><a href="#"><i class="fa fa-pinterest" aria-hidden="true"></i></a></li>
                                <li><a href="#"><i class="fa fa-rss" aria-hidden="true"></i></a></li>
                                <li><a href="#"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="footer-box">
                            <h3>Địa Chỉ</h3>
                            <p>Cơ sở 2: Tầng 1, Nhà A2, Trường Đại học Sân Khấu - Điện Ảnh Hà Nội. Đường Hồ Tùng Mậu, Phường Mai Dịch, Quận Cầu Giấy, Hà Nội</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="footer-box">
                            <h3>Liên Hệ</h3>
                            <ul class="corporate-address">
                                <li><i class="fa fa-phone" aria-hidden="true"></i> 0898555917 - Mr.Nguyễn Thành Trung</li>
                                <li><i class="fa fa-envelope-o" aria-hidden="true"></i>1811060665@gmail.com</li>
                            </ul>
                        </div>
                    </div>
{{--                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">--}}
{{--                        <div class="footer-box">--}}
{{--                            <h3>Địa chỉ</h3>--}}
{{--                            <ul class="flickr-photos">--}}
{{--                                <li>--}}
{{--                                    <a href="#"><img class="img-responsive" src="img/footer/1.jpg" alt="flickr"></a>--}}
{{--                                </li>--}}
{{--                                <li>--}}
{{--                                    <a href="#"><img class="img-responsive" src="img/footer/2.jpg" alt="flickr"></a>--}}
{{--                                </li>--}}
{{--                                <li>--}}
{{--                                    <a href="#"><img class="img-responsive" src="img/footer/3.jpg" alt="flickr"></a>--}}
{{--                                </li>--}}
{{--                                <li>--}}
{{--                                    <a href="#"><img class="img-responsive" src="img/footer/4.jpg" alt="flickr"></a>--}}
{{--                                </li>--}}
{{--                                <li>--}}
{{--                                    <a href="#"><img class="img-responsive" src="img/footer/5.jpg" alt="flickr"></a>--}}
{{--                                </li>--}}
{{--                                <li>--}}
{{--                                    <a href="#"><img class="img-responsive" src="img/footer/6.jpg" alt="flickr"></a>--}}
{{--                                </li>--}}
{{--                            </ul>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                </div>
            </div>
        </div>
        <div class="footer-area-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                        <p>Thanh Trung Academics by Nguyễn Thành Trung</p>
                    </div>
{{--                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">--}}
{{--                        <ul class="payment-method">--}}
{{--                            <li>--}}
{{--                                <a href="#"><img alt="payment-method" src="img/payment-method1.jpg"></a>--}}
{{--                            </li>--}}
{{--                            <li>--}}
{{--                                <a href="#"><img alt="payment-method" src="img/payment-method2.jpg"></a>--}}
{{--                            </li>--}}
{{--                            <li>--}}
{{--                                <a href="#"><img alt="payment-method" src="img/payment-method3.jpg"></a>--}}
{{--                            </li>--}}
{{--                            <li>--}}
{{--                                <a href="#"><img alt="payment-method" src="img/payment-method4.jpg"></a>--}}
{{--                            </li>--}}
{{--                        </ul>--}}
{{--                    </div>--}}
                </div>
            </div>
        </div>
    </footer>
    <!-- Footer Area End Here -->
</div>
<!-- Main Body Area End Here -->
<!-- jquery-->

<script src="{{ asset('front/js/jquery-2.2.4.min.js') }}" type="text/javascript"></script>
<!-- Plugins js -->

<script src="{{ asset('front/js/plugins.js') }}" type="text/javascript"></script>
<!-- Bootstrap js -->

<script src="{{ asset('front/js/bootstrap.min.js') }}" type="text/javascript"></script>
<!-- WOW JS -->

<script src="{{ asset('front/js/wow.min.js') }}"></script>
<!-- Nivo slider js -->

<script src="{{ asset('front/vendor/slider/js/jquery.nivo.slider.js') }}" type="text/javascript"></script>
<script src="{{ asset('front/vendor/slider/home.js') }}" type="text/javascript"></script>
<!-- Owl Cauosel JS -->

<script src="{{ asset('front/vendor/OwlCarousel/owl.carousel.min.js') }}" type="text/javascript"></script>
<!-- Meanmenu Js -->
<script src="{{ asset('front/js/jquery.meanmenu.min.js') }}" type="text/javascript"></script>
<!-- Srollup js -->

<script src="{{ asset('front/js/jquery.scrollUp.min.js') }}" type="text/javascript"></script>
<!-- jquery.counterup js -->

<script src="{{ asset('front/js/jquery.counterup.min.js') }}"></script>

<script src="{{ asset('front/js/waypoints.min.js') }}"></script>
<!-- Countdown js -->

<script src="{{ asset('front/js/jquery.countdown.min.js') }}" type="text/javascript"></script>
<!-- Isotope js -->

<script src="{{ asset('front/js/isotope.pkgd.min.js') }}" type="text/javascript"></script>
<!-- Magic Popup js -->

<script src="{{ asset('front/js/jquery.magnific-popup.min.js') }}" type="text/javascript"></script>
<!-- Gridrotator js -->

<script src="{{ asset('front/js/jquery.gridrotator.js') }}" type="text/javascript"></script>
<!-- Custom Js -->
<script src="https://checkout.stripe.com/checkout.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="{{ asset('front/js/main.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/khoahoc.js') }} "></script>
<script src="{{ asset('js/dangky.js') }} "></script>
</body>


<!-- Mirrored from www.radiustheme.com/demo/html/academics/academics/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 19 Sep 2018 14:20:49 GMT -->
</html>
