@extends('templates_frontend.layout')
@section('content')
  <!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->
<!-- Add your site or application content here -->
    <!-- Header Area Start Here -->
    <!-- Header Area End Here -->
    <!-- Inner Page Banner Area Start Here -->
    <div class="inner-page-banner-area" style="background-image: url({{ asset("front/img/banner/5.jpg") }});">
        <div class="container">
            <div class="pagination-area">
                <h1>Danh mục: {{$listDanhMuc->ten_danh_muc}}</h1>
                <ul>
                    <li><a href="{{url('/home') }}">Trang Chủ</a> -</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Inner Page Banner Area End Here -->
    <!-- Courses Page 1 Area Start Here -->
    <div class="courses-page-area1">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12 col-md-push-3">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="courses-page-top-area">
                                <div class="courses-page-top-left">
                                    <p>Showing 1-10 of 50 results</p>
                                </div>
                                <div class="courses-page-top-right">
                                    <ul>
                                        <li class="active"><a href="#gried-view" data-toggle="tab" aria-expanded="false"><i class="fa fa-th-large"></i></a></li>
                                        <li><a href="#list-view" data-toggle="tab" aria-expanded="true"><i class="fa fa-list"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="gried-view">
                                @foreach($lists as $item)
                                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                    <div class="courses-box1">
                                        <div class="single-item-wrapper">
                                            <div class="courses-img-wrapper hvr-bounce-to-bottom">
                                                <img class="img-responsive" src="{{ $item->hinh_anh?Storage::url($item->hinh_anh):'http://placehold.it/100x100' }}" style="width: 350px; height: 200px" alt="courses">
                                                <a href="#"><i class="fa fa-link" aria-hidden="true"></i></a>
                                            </div>
                                            <div class="courses-content-wrapper">
                                                <h3 class="item-title"><a href="{{ route('route_BackEnd_UserLopHoc_Detail',['id'=> $item->id]) }}">{{$item->ten_khoa_hoc}}</a></h3>
                                                <p class="item-content"></p>
                                                <ul class="courses-info">
                                                    <li>{{$item->thoi_gian}} Months
                                                        <br><span> Course</span></li>
                                                    <li>{{ $lists->count() }}
                                                        <br><span> Classes</span></li>

                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <!-- Listed product show -->
                            <div role="tabpanel" class="tab-pane" id="list-view">
                                @foreach($lists as $item)
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="courses-box3">
                                        <div class="single-item-wrapper">
                                            <div class="courses-img-wrapper hvr-bounce-to-right">
                                                <img class="img-responsive" src="{{ $item->hinh_anh?Storage::url($item->hinh_anh):'http://placehold.it/100x100' }}" style="width: 350px; height: 200px" alt="courses">
                                                <a href="#"><i class="fa fa-link" aria-hidden="true"></i></a>
                                            </div>
                                            <div class="courses-content-wrapper">
                                                <h3 class="item-title"><a href="#">{{$item->ten_khoa_hoc}}</a></h3>
                                                <p class="item-content"></p>
                                                <ul class="courses-info">
                                                    <li>{{$item->thoi_gian}} Months
                                                        <br><span> Course</span></li>
                                                    <li>15
                                                        <br><span> Classes</span></li>
                                                    <li>05 pm - 07 pm
                                                        <br><span> Time</span></li>
                                                </ul>
                                                <div class="courses-fee">{{$item->hoc_phi}}VDN</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                {{  $lists->appends($extParams)->links() }}
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12 col-md-pull-9">
                    <div class="sidebar">
                        <div class="sidebar-box">
                            <div class="sidebar-box-inner">
                                <h3 class="sidebar-title">Tìm Kiếm Khoá Học</h3>
                                <div class="sidebar-find-course">
                                    <form id="checkout-form" action="" method="get">
                                        <div class="form-group course-name">
                                            <input id="first-name" placeholder="Nhập tên khoá học" name="search_ten_khoa_hoc" class="form-control" type="text"
                                                   value="@isset($extParams['search_ten_khoa_hoc']){{$extParams['search_ten_khoa_hoc']}}@endisset"/>
                                        </div>
                                        <div class="form-group">
                                            <button class="sidebar-search-btn disabled" type="submit" value="Login">Tìm Kiếm</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Courses Page 1 Area End Here -->
    <!-- Footer Area Start Here -->
@endsection