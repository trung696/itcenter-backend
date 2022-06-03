@extends('templates_frontend.layout')
@section('content')
<!-- Header Area End Here -->
<!-- Slider 1 Area Start Here -->
<div class="slider1-area overlay-default index1">
    <div class="bend niceties preview-1">
        <div id="ensign-nivoslider-3" class="slides">

            <img src="{{ asset("front/img/slider/1-1.jpg") }}" alt="slider" title="#slider-direction-1" />
            <img src="{{ asset("front/img/slider/1-2.jpg") }}" alt="slider" title="#slider-direction-2" />
            <img src="{{ asset("front/img/slider/1-3.jpg") }}" alt="slider" title="#slider-direction-3" />
        </div>
    </div>
</div>
<!-- Slider 1 Area End Here -->
<!-- About 1 Area Start Here -->
<div class="about1-area">
    <div class="container">
        <h1 class="about-title wow fadeIn" data-wow-duration="1s" data-wow-delay=".2s">Welcome To Thanh Trung Academics</h1>
        <p class="about-sub-title wow fadeIn" data-wow-duration="1s" data-wow-delay=".2s">PHƯƠNG CHÂM ĐÀO TẠO – HỌC VÀ LÀM THEO DỰ ÁN THỰC TẾ</p>
    </div>
</div>
<!-- About 1 Area End Here -->
<!-- Courses 1 Area Start Here -->
<div class="courses1-area">
    <div class="container">
        <h2 class="title-default-left">Các Khoá Học Tiêu Biểu</h2>
    </div>
    <div id="shadow-carousel" class="container">
        <div class="rc-carousel" data-loop="true" data-items="4" data-margin="20" data-autoplay="false" data-autoplay-timeout="10000" data-smart-speed="2000" data-dots="false" data-nav="true" data-nav-speed="false" data-r-x-small="1" data-r-x-small-nav="true" data-r-x-small-dots="false" data-r-x-medium="2" data-r-x-medium-nav="true" data-r-x-medium-dots="false" data-r-small="2" data-r-small-nav="true" data-r-small-dots="false" data-r-medium="3" data-r-medium-nav="true" data-r-medium-dots="false" data-r-large="4" data-r-large-nav="true" data-r-large-dots="false">
            @foreach($listKhoaHoc as  $item)
            <div class="courses-box1">
                <div class="single-item-wrapper">
                    <div class="courses-img-wrapper hvr-bounce-to-bottom">
                        <img class="img-responsive" src="{{ $item->hinh_anh?Storage::url($item->hinh_anh):'http://placehold.it/100x100' }}" style="width: 600px;height: 300px;object-fit: cover; " alt="courses">
                        <a href="{{ route('route_BackEnd_UserKhoaHoc_Detail',['id'=> $item->id]) }}"><i class="fa fa-link" aria-hidden="true"></i></a>
                    </div>
                    <div class="courses-content-wrapper">
                        <h3 class="item-title"><a href="#">{{$item->ten_khoa_hoc}}</a></h3>
                        <ul class="courses-info">
                            <li>{{$item->thoi_gian}} tháng </li>
                            <li>{{$item->hoc_phi}} VND</li>
                        </ul>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
<!-- Courses 1 Area End Here -->
<!-- Video Area Start Here -->
<!-- Video Area End Here -->
<!-- Lecturers Area Start Here -->
<div class="lecturers-area">
    <div class="container">
        <h2 class="title-default-left">Giảng viên tiêu biểu</h2>
    </div>
    <div class="container">
        <div class="rc-carousel" data-loop="true" data-items="4" data-margin="30" data-autoplay="false" data-autoplay-timeout="10000" data-smart-speed="2000" data-dots="false" data-nav="true" data-nav-speed="false" data-r-x-small="1" data-r-x-small-nav="true" data-r-x-small-dots="false" data-r-x-medium="2" data-r-x-medium-nav="true" data-r-x-medium-dots="false" data-r-small="3" data-r-small-nav="true" data-r-small-dots="false" data-r-medium="4" data-r-medium-nav="true" data-r-medium-dots="false" data-r-large="4" data-r-large-nav="true" data-r-large-dots="false">
            <div class="single-item">
                <div class="lecturers1-item-wrapper">
                    <div class="lecturers-img-wrapper">
                        <a href="#"><img class="img-responsive" src="img/team/1.jpg" alt="team"></a>
                    </div>
                    <div class="lecturers-content-wrapper">
                        <h3 class="item-title"><a href="#">Rosy Janner</a></h3>
                        <span class="item-designation">Senior Finance Lecturer</span>
                        <ul class="lecturers-social">
                            <li><a href="#"><i class="fa fa-envelope-o" aria-hidden="true"></i></a></li>
                            <li><a href="#"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
                            <li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                            <li><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="single-item">
                <div class="lecturers1-item-wrapper">
                    <div class="lecturers-img-wrapper">
                        <a href="#"><img class="img-responsive" src="img/team/2.jpg" alt="team"></a>
                    </div>
                    <div class="lecturers-content-wrapper">
                        <h3 class="item-title"><a href="#">Mike Hussy</a></h3>
                        <span class="item-designation">Senior Finance Lecturer</span>
                        <ul class="lecturers-social">
                            <li><a href="#"><i class="fa fa-envelope-o" aria-hidden="true"></i></a></li>
                            <li><a href="#"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
                            <li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                            <li><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="single-item">
                <div class="lecturers1-item-wrapper">
                    <div class="lecturers-img-wrapper">
                        <a href="#"><img class="img-responsive" src="img/team/3.jpg" alt="team"></a>
                    </div>
                    <div class="lecturers-content-wrapper">
                        <h3 class="item-title"><a href="#">Daziy Millar</a></h3>
                        <span class="item-designation">Senior Finance Lecturer</span>
                        <ul class="lecturers-social">
                            <li><a href="#"><i class="fa fa-envelope-o" aria-hidden="true"></i></a></li>
                            <li><a href="#"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
                            <li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                            <li><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="single-item">
                <div class="lecturers1-item-wrapper">
                    <div class="lecturers-img-wrapper">
                        <a href="#"><img class="img-responsive" src="img/team/4.jpg" alt="team"></a>
                    </div>
                    <div class="lecturers-content-wrapper">
                        <h3 class="item-title"><a href="#">Kazi Fahim</a></h3>
                        <span class="item-designation">Senior Finance Lecturer</span>
                        <ul class="lecturers-social">
                            <li><a href="#"><i class="fa fa-envelope-o" aria-hidden="true"></i></a></li>
                            <li><a href="#"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
                            <li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                            <li><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="single-item">
                <div class="lecturers1-item-wrapper">
                    <div class="lecturers-img-wrapper">
                        <a href="#"><img class="img-responsive" src="img/team/1.jpg" alt="team"></a>
                    </div>
                    <div class="lecturers-content-wrapper">
                        <h3 class="item-title"><a href="#">Rosy Janner</a></h3>
                        <span class="item-designation">Senior Finance Lecturer</span>
                        <ul class="lecturers-social">
                            <li><a href="#"><i class="fa fa-envelope-o" aria-hidden="true"></i></a></li>
                            <li><a href="#"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
                            <li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                            <li><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="single-item">
                <div class="lecturers1-item-wrapper">
                    <div class="lecturers-img-wrapper">
                        <a href="#"><img class="img-responsive" src="img/team/2.jpg" alt="team"></a>
                    </div>
                    <div class="lecturers-content-wrapper">
                        <h3 class="item-title"><a href="#">Mike Hussy</a></h3>
                        <span class="item-designation">Senior Finance Lecturer</span>
                        <ul class="lecturers-social">
                            <li><a href="#"><i class="fa fa-envelope-o" aria-hidden="true"></i></a></li>
                            <li><a href="#"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
                            <li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                            <li><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Lecturers Area End Here -->
<!-- News and Event Area Start Here -->
<!-- News and Event Area End Here -->
<!-- Counter Area Start Here -->
<div class="counter-area bg-primary-deep" style="background-image: url('img/banner/4.jpg');">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 counter1-box wow fadeInUp" data-wow-duration=".5s" data-wow-delay=".20s">
                <h2 class="about-counter title-bar-counter" data-num="80">80</h2>
                <p>Tổng Số Giảng Viên</p>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 counter1-box wow fadeInUp" data-wow-duration=".5s" data-wow-delay=".40s">
                <h2 class="about-counter title-bar-counter" data-num="20">20</h2>
                <p>Tổng Số Khoá Học</p>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 counter1-box wow fadeInUp" data-wow-duration=".5s" data-wow-delay=".60s">
                <h2 class="about-counter title-bar-counter" data-num="56">56</h2>
                <p>Tổng Số Lớp Học</p>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 counter1-box wow fadeInUp" data-wow-duration=".5s" data-wow-delay=".80s">
                <h2 class="about-counter title-bar-counter" data-num="77">77</h2>
                <p>Tổng Số Học Viên</p>
            </div>
        </div>
    </div>
</div>
<!-- Counter Area End Here -->
<!-- Students Say Area Start Here -->
<div class="students-say-area">
    <h2 class="title-default-center">Học Viên Nghĩ Gì Về Các Khoá Học</h2>
    <div class="container">
        <div class="rc-carousel" data-loop="true" data-items="2" data-margin="30" data-autoplay="false" data-autoplay-timeout="10000" data-smart-speed="2000" data-dots="true" data-nav="false" data-nav-speed="false" data-r-x-small="1" data-r-x-small-nav="false" data-r-x-small-dots="true" data-r-x-medium="2" data-r-x-medium-nav="false" data-r-x-medium-dots="true" data-r-small="2" data-r-small-nav="false" data-r-small-dots="true" data-r-medium="2" data-r-medium-nav="false" data-r-medium-dots="true" data-r-large="2" data-r-large-nav="false" data-r-large-dots="true">
            <div class="single-item">
                <div class="single-item-wrapper">
                    <div class="tlp-tm-content-wrapper">
                        <h3 class="item-title"><a href="#">Nguyễn Thành Trung</a></h3>
                        <span class="item-designation">Lập Trình Website - Ngôn Ngữ PHP</span>
                        <ul class="rating-wrapper">
                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                        </ul>
                        <div class="item-content">Khoá học rất hay. Em tiếp thu được rất nhiều kiến thức mới</div>
                    </div>
                </div>
            </div>
            <div class="single-item">
                <div class="single-item-wrapper">
                    <div class="tlp-tm-content-wrapper">
                        <h3 class="item-title"><a href="#">Phùng Thị Hạnh</a></h3>
                        <span class="item-designation">Lập Trình Website - Ngôn Ngữ PHP</span>
                        <ul class="rating-wrapper">
                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                        </ul>
                        <div class="item-content">Khoá học rất hay. Em tiếp thu được rất nhiều kiến thức mới</div>
                    </div>
                </div>
            </div>
            <div class="single-item">
                <div class="single-item-wrapper">
                    <div class="tlp-tm-content-wrapper">
                        <h3 class="item-title"><a href="#">Đặng Vũ Lưu</a></h3>
                        <span class="item-designation">Lập Trình Website - Ngôn Ngữ PHP</span>
                        <ul class="rating-wrapper">
                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                        </ul>
                        <div class="item-content">Khoá học rất hay. Em tiếp thu được rất nhiều kiến thức mới</div>
                    </div>
                </div>
            </div>
            <div class="single-item">
                <div class="single-item-wrapper">
                    <div class="tlp-tm-content-wrapper">
                        <h3 class="item-title"><a href="#">Nguyễn Phương Thuận</a></h3>
                        <span class="item-designation">Lập Trình Game Mobi - Ngôn Ngữ Python</span>
                        <ul class="rating-wrapper">
                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                        </ul>
                        <div class="item-content">Khoá học rất hay. Em tiếp thu được rất nhiều kiến thức mới</div>
                    </div>
                </div>
            </div>
            <div class="single-item">
                <div class="single-item-wrapper">
                    <div class="tlp-tm-content-wrapper">
                        <h3 class="item-title"><a href="#">Nguyễn Thị Liên</a></h3>
                        <span class="item-designation">Lập Trình Game Mobi - Ngôn Ngữ Python</span>
                        <ul class="rating-wrapper">
                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                        </ul>
                        <div class="item-content">Khoá học rất hay. Em tiếp thu được rất nhiều kiến thức mới</div>
                    </div>
                </div>
            </div>
            <div class="single-item">
                <div class="single-item-wrapper">
                    <div class="tlp-tm-content-wrapper">
                        <h3 class="item-title"><a href="#">Lương Thị Thịnh</a></h3>
                        <span class="item-designation">Lập Trình Website - Ngôn Ngữ Node Js</span>
                        <ul class="rating-wrapper">
                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                        </ul>
                        <div class="item-content">Khoá học rất hay. Em tiếp thu được rất nhiều kiến thức mới</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Students Say Area End Here -->
<!-- Students Join 1 Area Start Here -->
<!-- Students Join 1 Area End Here -->
<!-- Footer Area Start Here -->
@endsection