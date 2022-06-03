@php
    $objUser = \Illuminate\Support\Facades\Auth::user();
@endphp
    <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Thành Trung Academy::@yield('title')</title>

    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    {{--  Dành cho vuejs--}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta id="_token" name="_token" content="{!! csrf_token() !!}"/>
    {{--  <meta name="csrf-token" content="{{ csrf_token() }}">--}}
<!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('default/bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('default/bower_components/font-awesome/css/font-awesome.min.css')}}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('default/bower_components/Ionicons/css/ionicons.min.css')}}">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="{{ asset('default/plugins/iCheck/all.css')}}">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('default/dist/css/AdminLTE.min.css')}}">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{ asset('default/dist/css/skins/_all-skins.min.css')}}">
    <link rel="stylesheet" href="{{ asset('default/dist/css/spx.css')}}">
    <link rel="stylesheet" href="{{ asset('css/SpxApp.css')}}?b={{config('app.build_version')}}">
    <link rel="stylesheet" href="{{ asset('css/backend.css')}}?b={{config('app.build_version')}}">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <link rel="icon" type="image/png" sizes="16x16" href="/public/img/favicon.png">
    <link rel="stylesheet" href="{{asset('default/bower_components/select2/dist/css/select2.min.css')}}">
    @yield('css')
    <style>
        .select2-container--default .select2-selection--single, .select2-selection .select2-selection--single {
            padding: 3px 0px;
            height: 30px;
        }

        .select2-container {
            margin-top: -5px;
        }

        option {
            white-space: nowrap;
        }

        .select2-container--default .select2-selection--single {
            background-color: #fff;
            border: 1px solid #aaa;
            border-radius: 0px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            color: #216992;
        }

        .select_role {
            outline: none;
            /*background: #f2f2f2;*/
            border: none;
            width: 100%;
            text-align: left;
            border-bottom: 1px solid #999;
        }

        .blink5 {
            animation: blink-animation 1s steps(5, start) infinite;
            -webkit-animation: blink-animation 1s steps(5, start) infinite;
        }

        @keyframes blink-animation {
            to {

                visibility: hidden;
            }
        }

        @-webkit-keyframes blink-animation {
            to {
                visibility: hidden;
            }
        }

        .callout-fix {
            position: relative;
            text-align: justify;
            padding: 20px;
            margin: 5px 15px;
        }

        .callout-fix span.fa.fa-times-circle.pull-right {
            position: absolute;
            right: 8px;
            top: 8px;
        }
    </style>
    {{--<script type="text/javascript" src="/public/js/app.js?b={{config('app.build_version')}}"></script>--}}
    <script src="{{ asset('default/bower_components/jquery/dist/jquery.min.js')}}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{--<script src="{{ taisan('/public/default/dist/js/spx.js')}}"></script>--}}

    <script>
        var sapp = new Object();
    </script>
</head>
<body class="hold-transition skin-blue sidebar-mini fixed">
<script>
    (function () {
        if (Boolean(localStorage.getItem('sidebar-toggle-collapsed'))) {
            var body = document.getElementsByTagName('body')[0];
            body.className = body.className + ' sidebar-collapse';
        }
    })();
</script>
<!-- Site wrapper class body:  sidebar-collapse -->
<div class="wrapper">

    <header class="main-header">
        <!-- Logo -->
        <a href="/apps" class="logo">

            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini">
{{--        <img src="/public/img/logo-itplus-black.png" width="50">--}}
        <img src="/img/logott01.png" width="50">
{{--        <img src="/public/img/lg-tet.png" width="50">--}}
      </span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg">
{{--      <img src="/public/img/logo-itplus-black.png" height="40">--}}
      <img src="/img/logott01.png" height="40">
{{--      <img src="/public/img/lg-tet.png" height="45">--}}
                {{--<b class="visible-lg">Quản lý Đào tạo</b>--}}
      </span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        {{--@inject('siteInfo', 'App\Services\SiteInformation')--}}

        <nav class="navbar navbar-static-top">
        {{--//noel moi dung      <span class="santa"></span>--}}
        <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- Messages: style can be found in dropdown.less-->
                {{--<li class="dropdown messages-menu">--}}
                {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown">--}}
                {{--<i class="fa fa-envelope-o"></i>--}}
                {{--<span class="label label-success">4</span>--}}
                {{--</a>--}}
                {{--<ul class="dropdown-menu">--}}
                {{--<li class="header">You have 4 messages</li>--}}
                {{--<li>--}}
                {{--<!-- inner menu: contains the actual data -->--}}
                {{--<ul class="menu">--}}
                {{--<li><!-- start message -->--}}
                {{--<a href="#">--}}
                {{--<div class="pull-left">--}}
                {{--<img src="/public/default/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">--}}
                {{--</div>--}}
                {{--<h4>--}}
                {{--Support Team--}}
                {{--<small><i class="fa fa-clock-o"></i> 5 mins</small>--}}
                {{--</h4>--}}
                {{--<p>Why not buy a new awesome theme?</p>--}}
                {{--</a>--}}
                {{--</li>--}}
                {{--<!-- end message -->--}}
                {{--</ul>--}}
                {{--</li>--}}
                {{--<li class="footer"><a href="#">See All Messages</a></li>--}}
                {{--</ul>--}}
                {{--</li>--}}
                {{--<!-- Notifications: style can be found in dropdown.less -->--}}
                {{--<!-- Tasks: style can be found in dropdown.less -->--}}
                {{--<li class="dropdown tasks-menu">--}}
                {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown">--}}
                {{--<i class="fa fa-flag-o"></i>--}}
                {{--<span class="label label-danger">9</span>--}}
                {{--</a>--}}
                {{--<ul class="dropdown-menu">--}}
                {{--<li class="header">You have 9 tasks</li>--}}
                {{--<li>--}}
                {{--<!-- inner menu: contains the actual data -->--}}
                {{--<ul class="menu">--}}
                {{--<li><!-- Task item -->--}}
                {{--<a href="#">--}}
                {{--<h3>--}}
                {{--Design some buttons--}}
                {{--<small class="pull-right">20%</small>--}}
                {{--</h3>--}}
                {{--<div class="progress xs">--}}
                {{--<div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar"--}}
                {{--aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">--}}
                {{--<span class="sr-only">20% Complete</span>--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--</a>--}}
                {{--</li>--}}
                {{--<!-- end task item -->--}}
                {{--</ul>--}}
                {{--</li>--}}
                {{--<li class="footer">--}}
                {{--<a href="#">View all tasks</a>--}}
                {{--</li>--}}
                {{--</ul>--}}
                {{--</li>--}}
                <!-- User Account: style can be found in dropdown.less -->

                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="/img/no-avartar.png" class="user-image" alt="User Image">
                            <span class="hidden-xs"> {{ $objUser->name }} </span>
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <p style="padding: 10px;">
                                    <i class="fa fa-user"></i> Email: {{ $objUser->email }}<br>
                                    {{--                                    <i class="fa fa-envelope"></i> Email: {{ $objUser->email }}--}}
                                </p>
                            </li>
                        <!-- <li>
                <a href="{{route('logout')}}" class="btn btn-default btn-flat">Sign out</a>
              </li> -->
                            <!-- User image -->
                        {{--<li class="user-header">--}}

                        {{--<img src="/public/img/no-avartar.png" class="img-circle" alt="User Image">--}}

                        {{--<p>--}}
                        {{--Username: {{ $objUser->username }}<br>--}}
                        {{--Email: {{ $objUser->email }}--}}
                        {{--<small>Member since Nov. 2012</small>--}}
                        {{--</p>--}}
                        {{--</li>--}}
                        <!-- Menu Body -->
                        {{--<li class="user-body">--}}
                        {{--<div class="row">--}}
                        {{--<div class="col-xs-4 text-center">--}}
                        {{--<a href="#">Followers</a>--}}
                        {{--</div>--}}
                        {{--<div class="col-xs-4 text-center">--}}
                        {{--<a href="#">Sales</a>--}}
                        {{--</div>--}}
                        {{--<div class="col-xs-4 text-center">--}}
                        {{--<a href="#">Friends</a>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        {{--<!-- /.row -->--}}
                        {{--</li>--}}
                        <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left">
                                    {{--                                    <a href="{{ route('route_BackEnd_User_changePassword',['id'=>Auth::id()]) }}" class="btn btn-default btn-flat">Change password</a>--}}
                                </div>
                                <div class="pull-right">
                                    <a href="{{route('logout')}}" class="btn btn-default btn-flat">Sign out</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <!-- Control Sidebar Toggle Button -->
                    {{--<li>--}}
                    {{--<a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>--}}
                    {{--</li>--}}
                </ul>
            </div>
        </nav>
    </header>

    <!-- =============================================== -->

    <!-- Left side column. contains the sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel -->
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="/img/no-avartar.png" class="img-circle" alt="User Image">
                </div>
                <div class="pull-left info">
                    <p> {{ $objUser->name }} </p>

                </div>
                <div class="clearfix">
                    <p class="text-info">{{ $objUser->email }}</p>
                </div>

            </div>
        {{--<!-- search form -->--}}
        {{--<form action="#" method="get" class="sidebar-form">--}}
        {{--<div class="input-group">--}}
        {{--<input type="text" name="q" class="form-control" placeholder="Search...">--}}
        {{--<span class="input-group-btn">--}}
        {{--<button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>--}}
        {{--</button>--}}
        {{--</span>--}}
        {{--</div>--}}
        {{--</form>--}}
        <!-- /.search form -->
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu" data-widget="tree">
                <li class=" active menu-open ">
                    <a href="#"><i class="fa fa-user"></i> <span>Người dùng</span></a>
                    <ul class="treeview-menu">
                        <li><a href="{{ route('route_BackEnd_NguoiDung_index') }}"><i
                                    class="fa fa-circle-o"></i> Người dùng</a></li>
                    </ul>
                </li>

                <li class=" active menu-open ">
                    <a href="#"><i class="fa fa-users"></i> <span>Quản lý học viên</span></a>
                    <ul class="treeview-menu">
                        <li><a href="{{ route('route_BackEnd_DanhSachHocVien_index') }}"><i class="fa fa-circle-o"></i>Danh sách học viên</a></li>
                        <li><a href=""><i class="fa fa-circle-o"></i>Danh sách đăng ký</a></li>
                    </ul>
                </li>
                <li class=" active menu-open ">
                    <a href="#"><i class="fa fa-users"></i> <span>Khuyến Mại</span></a>
                    <ul class="treeview-menu">
                        <li><a href="{{ route('route_BackEnd_ChienDich_index') }}"><i
                                        class="fa fa-circle-o"></i>Chiến Dịch</a></li>
                    </ul>
                </li>

                <li class=" active menu-open ">
                    <a href="#"><i class="fa fa-dollar"></i> <span>Khoá hoc</span></a>
                    <ul class="treeview-menu">
                        <li><a href="{{ route('route_BackEnd_DanhMucKhoaHoc_List') }}"><i
                                        class="fa fa-circle-o"></i> Danh mục khoá học</a></li>
                        <li><a href="{{ route('route_BackEnd_KhoaHoc_index') }}"><i
                                        class="fa fa-circle-o"></i> Khoá học</a></li>
                    </ul>
                </li>
                <li class=" active menu-open ">
                    <a href="#"><i class="fa fa-dollar"></i> <span>Địa Điểm Học</span></a>
                    <ul class="treeview-menu">
                        <li><a href="{{ route('route_BackEnd_DanhSachDiaDiem_index') }}"><i
                                        class="fa fa-circle-o"></i> Danh sách địa điểm</a></li>
                    </ul>
                </li>
                <li class=" active menu-open ">
                    <a href="#"><i class="fa fa-dollar"></i> <span>Giảng Viên</span></a>
                    <ul class="treeview-menu">
                        <li><a href=""><i
                                        class="fa fa-circle-o"></i> Danh sách giảng viên</a></li>
                    </ul>
                </li>


{{--                <li class=" active menu-open ">--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--                <li class=" active menu-open ">--}}
{{--                    <a href="#"><i class="fa fa-paperclip"></i> <span>Biên Bản</span></a>--}}
{{--                    <ul class="treeview-menu">--}}
{{--                        <li><a href="{{ route('route_BackEnd_BienBan_index') }}"><i--}}
{{--                                    class="fa fa-circle-o"></i>Biên Bản Bàn Giao</a></li>--}}
{{--                    </ul>--}}
{{--                    <ul class="treeview-menu">--}}
{{--                        <li><a href="{{ route('route_BackEnd_BienBanKiemKe_index') }}"><i--}}
{{--                                    class="fa fa-circle-o"></i>Biên Bản Kiểm Kê</a></li>--}}
{{--                    </ul>--}}
{{--                    <ul class="treeview-menu">--}}
{{--                        <li><a href="{{ route('route_BackEnd_BienBanThanhLi_index') }}"><i--}}
{{--                                    class="fa fa-circle-o"></i>Biên Bản Thanh Lí</a></li>--}}
{{--                    </ul>--}}
{{--                </li>--}}
{{--                <li class=" active menu-open ">--}}
{{--                    </a>--}}
{{--                </li>--}}
            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- =============================================== -->

    <!-- Content Wrapper. Contains page content -->
    <div id="app" class="content-wrapper" style="background-color: #ecf0f5;">
        {{--        @if(!empty($alert_msg))--}}
        {{--            <section>--}}
        {{--                @php($check = false)--}}
        {{--                @foreach($alert_msg as $row_alert)--}}
        {{--                    @if($loop->iteration>3&&$check==false)--}}
        {{--                        <span id="view-more-all" class="more" style="display: none;">--}}
        {{--         @endif--}}
        {{--                            @if($row_alert->priority ==1)--}}
        {{--                                <p class="callout callout-warning callout-fix">--}}
        {{--          <span class=" blink5 fa  fa-hand-o-right" style="color: yellow; font-size: 18px;"></span>--}}
        {{--          {!!  $row_alert->content  !!}--}}
        {{--                                    {!! strlen($row_alert->content)>1000?mb_substr($row_alert->content,0,1000).'<span id="dots-tb-'.$row_alert->id.'">...</span><span id="view-more-tb-'.$row_alert->id.'" class="more">'.mb_substr($row_alert->content,1000).'</span><button type="button" class="btn btn-link btnViewMore" style="padding:0;margin:0;outline:none;text-decoration:none;" data-stt="tb-'.$row_alert->id.'" id="myBtn-tb-'.$row_alert->id.'"><i class="fa fa-angle-down"></i> Xem thêm</button>':$row_alert->content !!}--}}
        {{--          <button class="btn btn-link btnCloseNotification" data-id="{{ $row_alert->id }}" style="padding: 0;margin: 0;outline: none;">--}}
        {{--            <span class="fa fa-times-circle pull-right" style="color: white;"></span>--}}
        {{--          </button>--}}
        {{--        </p>--}}
        {{--                            @elseif($row_alert->priority ==2)--}}
        {{--                                <p class="callout callout-danger callout-fix">--}}
        {{--          <span class=" blink5 fa  fa-hand-o-right"  style="color: yellow; font-size: 18px;"></span>--}}
        {{--          {!! strlen($row_alert->content)>1000?mb_substr($row_alert->content,0,1000).'<span id="dots-tb-'.$row_alert->id.'">...</span><span id="view-more-tb-'.$row_alert->id.'" class="more">'.mb_substr($row_alert->content,1000).'</span><button type="button" class="btn btn-link btnViewMore" style="padding:0;margin:0;outline:none;text-decoration:none;" data-stt="tb-'.$row_alert->id.'" id="myBtn-tb-'.$row_alert->id.'"><i class="fa fa-angle-down"></i> Xem thêm</button>':$row_alert->content !!}--}}
        {{--          <button class="btn btn-link btnCloseNotification" data-id="{{ $row_alert->id }}" style="padding: 0;margin: 0;outline: none;">--}}
        {{--            <span class="fa fa-times-circle pull-right" style="color: white;"></span>--}}
        {{--          </button>--}}
        {{--        </p>--}}

        {{--                            @else--}}
        {{--                                <p class="callout callout-success callout-fix">--}}
        {{--          <span class=" blink5 fa fa-hand-o-right"  style="color: yellow; font-size: 18px;"></span>--}}
        {{--          {!! strlen($row_alert->content)>1000?mb_substr($row_alert->content,0,1000).'<span id="dots-tb-'.$row_alert->id.'">...</span><span id="view-more-tb-'.$row_alert->id.'" class="more">'.mb_substr($row_alert->content,1000).'</span><button type="button" class="btn btn-link btnViewMore" style="padding:0;margin:0;outline:none;text-decoration:none;" data-stt="tb-'.$row_alert->id.'" id="myBtn-tb-'.$row_alert->id.'"><i class="fa fa-angle-down"></i> Xem thêm</button>':$row_alert->content !!}--}}
        {{--          <button class="btn btn-link btnCloseNotification" data-id="{{ $row_alert->id }}" style="padding: 0;margin: 0;outline: none;">--}}
        {{--            <span class="fa fa-times-circle pull-right" style="color: white;"></span>--}}
        {{--          </button>--}}
        {{--        </p>--}}
        {{--                            @endif--}}
        {{--                            @if($loop->iteration>3&&$check == false)--}}
        {{--                                @php($check = true)--}}
        {{--                            @endif--}}
        {{--                            @if($loop->iteration>3&&$loop->iteration == $alert_msg->count())--}}
        {{--         </span>--}}
        {{--                        <div style="text-align: center;">--}}
        {{--                            <button type="button" class="btn btn-link btnViewMore" style="padding:0;margin:0;outline:none;text-decoration:none;" data-stt="all" id="myBtn-all"><i class="fa fa-angle-down config-icon"></i></button>--}}
        {{--                            <button type="button" class="btn btn-link btnCloseAllNotify" style="padding:0;margin:0;outline:none;text-decoration:none;" title="Đóng tất cả"><i class="fa fa-times" style="font-size: 18px;font-weight: normal;"></i> Đóng tất cả</button>--}}
        {{--                        </div>--}}

        {{--                    @endif--}}
        {{--                @endforeach--}}
        {{--            </section>--}}
        {{--        @endif--}}
        @yield('content')
        <div class="clearfix"></div>

    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            {{--            <b>Version</b> {{ config('app.app_version') }} by MRS--}}
        </div>
        <strong>Copyright &copy; 2018-<?php echo date('Y');?>
    </footer>
<?php /*
  {{--<!-- Control Sidebar -->--}}
  {{--<aside class="control-sidebar control-sidebar-dark">--}}
    {{--<!-- Create the tabs -->--}}
    {{--<ul class="nav nav-tabs nav-justified control-sidebar-tabs">--}}
      {{--<li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>--}}

      {{--<li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>--}}
    {{--</ul>--}}
    {{--<!-- Tab panes -->--}}
    {{--<div class="tab-content">--}}
      {{--<!-- Home tab content -->--}}
      {{--<div class="tab-pane" id="control-sidebar-home-tab">--}}
        {{--<h3 class="control-sidebar-heading">Recent Activity</h3>--}}
        {{--<ul class="control-sidebar-menu">--}}
          {{--<li>--}}
            {{--<a href="javascript:void(0)">--}}
              {{--<i class="menu-icon fa fa-birthday-cake bg-red"></i>--}}

              {{--<div class="menu-info">--}}
                {{--<h4 class="control-sidebar-subheading">Langdon's Birthday</h4>--}}

                {{--<p>Will be 23 on April 24th</p>--}}
              {{--</div>--}}
            {{--</a>--}}
          {{--</li>--}}
          {{--<li>--}}
            {{--<a href="javascript:void(0)">--}}
              {{--<i class="menu-icon fa fa-user bg-yellow"></i>--}}

              {{--<div class="menu-info">--}}
                {{--<h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>--}}

                {{--<p>New phone +1(800)555-1234</p>--}}
              {{--</div>--}}
            {{--</a>--}}
          {{--</li>--}}
          {{--<li>--}}
            {{--<a href="javascript:void(0)">--}}
              {{--<i class="menu-icon fa fa-envelope-o bg-light-blue"></i>--}}

              {{--<div class="menu-info">--}}
                {{--<h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>--}}

                {{--<p>nora@example.com</p>--}}
              {{--</div>--}}
            {{--</a>--}}
          {{--</li>--}}
          {{--<li>--}}
            {{--<a href="javascript:void(0)">--}}
              {{--<i class="menu-icon fa fa-file-code-o bg-green"></i>--}}

              {{--<div class="menu-info">--}}
                {{--<h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>--}}

                {{--<p>Execution time 5 seconds</p>--}}
              {{--</div>--}}
            {{--</a>--}}
          {{--</li>--}}
        {{--</ul>--}}
        {{--<!-- /.control-sidebar-menu -->--}}

        {{--<h3 class="control-sidebar-heading">Tasks Progress</h3>--}}
        {{--<ul class="control-sidebar-menu">--}}
          {{--<li>--}}
            {{--<a href="javascript:void(0)">--}}
              {{--<h4 class="control-sidebar-subheading">--}}
                {{--Custom Template Design--}}
                {{--<span class="label label-danger pull-right">70%</span>--}}
              {{--</h4>--}}

              {{--<div class="progress progress-xxs">--}}
                {{--<div class="progress-bar progress-bar-danger" style="width: 70%"></div>--}}
              {{--</div>--}}
            {{--</a>--}}
          {{--</li>--}}
          {{--<li>--}}
            {{--<a href="javascript:void(0)">--}}
              {{--<h4 class="control-sidebar-subheading">--}}
                {{--Update Resume--}}
                {{--<span class="label label-success pull-right">95%</span>--}}
              {{--</h4>--}}

              {{--<div class="progress progress-xxs">--}}
                {{--<div class="progress-bar progress-bar-success" style="width: 95%"></div>--}}
              {{--</div>--}}
            {{--</a>--}}
          {{--</li>--}}
          {{--<li>--}}
            {{--<a href="javascript:void(0)">--}}
              {{--<h4 class="control-sidebar-subheading">--}}
                {{--Laravel Integration--}}
                {{--<span class="label label-warning pull-right">50%</span>--}}
              {{--</h4>--}}

              {{--<div class="progress progress-xxs">--}}
                {{--<div class="progress-bar progress-bar-warning" style="width: 50%"></div>--}}
              {{--</div>--}}
            {{--</a>--}}
          {{--</li>--}}
          {{--<li>--}}
            {{--<a href="javascript:void(0)">--}}
              {{--<h4 class="control-sidebar-subheading">--}}
                {{--Back End Framework--}}
                {{--<span class="label label-primary pull-right">68%</span>--}}
              {{--</h4>--}}

              {{--<div class="progress progress-xxs">--}}
                {{--<div class="progress-bar progress-bar-primary" style="width: 68%"></div>--}}
              {{--</div>--}}
            {{--</a>--}}
          {{--</li>--}}
        {{--</ul>--}}
        {{--<!-- /.control-sidebar-menu -->--}}

      {{--</div>--}}
      {{--<!-- /.tab-pane -->--}}
      {{--<!-- Stats tab content -->--}}
      {{--<div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>--}}
      {{--<!-- /.tab-pane -->--}}
      {{--<!-- Settings tab content -->--}}
      {{--<div class="tab-pane" id="control-sidebar-settings-tab">--}}
        {{--<form method="post">--}}
          {{--<h3 class="control-sidebar-heading">General Settings</h3>--}}

          {{--<div class="form-group">--}}
            {{--<label class="control-sidebar-subheading">--}}
              {{--Report panel usage--}}
              {{--<input type="checkbox" class="pull-right" checked>--}}
            {{--</label>--}}

            {{--<p>--}}
              {{--Some information about this general settings option--}}
            {{--</p>--}}
          {{--</div>--}}
          {{--<!-- /.form-group -->--}}

          {{--<div class="form-group">--}}
            {{--<label class="control-sidebar-subheading">--}}
              {{--Allow mail redirect--}}
              {{--<input type="checkbox" class="pull-right" checked>--}}
            {{--</label>--}}

            {{--<p>--}}
              {{--Other sets of options are available--}}
            {{--</p>--}}
          {{--</div>--}}
          {{--<!-- /.form-group -->--}}

          {{--<div class="form-group">--}}
            {{--<label class="control-sidebar-subheading">--}}
              {{--Expose author name in posts--}}
              {{--<input type="checkbox" class="pull-right" checked>--}}
            {{--</label>--}}

            {{--<p>--}}
              {{--Allow the user to show his name in blog posts--}}
            {{--</p>--}}
          {{--</div>--}}
          {{--<!-- /.form-group -->--}}

          {{--<h3 class="control-sidebar-heading">Chat Settings</h3>--}}

          {{--<div class="form-group">--}}
            {{--<label class="control-sidebar-subheading">--}}
              {{--Show me as online--}}
              {{--<input type="checkbox" class="pull-right" checked>--}}
            {{--</label>--}}
          {{--</div>--}}
          {{--<!-- /.form-group -->--}}

          {{--<div class="form-group">--}}
            {{--<label class="control-sidebar-subheading">--}}
              {{--Turn off notifications--}}
              {{--<input type="checkbox" class="pull-right">--}}
            {{--</label>--}}
          {{--</div>--}}
          {{--<!-- /.form-group -->--}}

          {{--<div class="form-group">--}}
            {{--<label class="control-sidebar-subheading">--}}
              {{--Delete chat history--}}
              {{--<a href="javascript:void(0)" class="text-red pull-right"><i class="fa fa-trash-o"></i></a>--}}
            {{--</label>--}}
          {{--</div>--}}
          {{--<!-- /.form-group -->--}}
        {{--</form>--}}
      {{--</div>--}}
      {{--<!-- /.tab-pane -->--}}
    {{--</div>--}}
  {{--</aside>--}}
  {{--<!-- /.control-sidebar -->--}}
 */?>
<!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>


</div>
<!-- ./wrapper -->
<div class="modal fade" id="app-modal-dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="padding-bottom: 0px;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" style="font-size: 24px; color:red; margin-top: -10px"
                          class="fa fa-close"></span></button>
                <h4 class="modal-title" id="app-modal-dialog-title">Default Modal</h4>
            </div>
            <div class="modal-body" id="app-modal-dialog-body">
                <p>Loading...</p>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- template for the modal component -->
<script type="text/x-template" id="modal-template">
    <transition name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
                <div class="modal-container">
                    <div class="modal-header">
                        <slot name="header">
                            default header
                        </slot>
                    </div>
                    <div class="modal-body">
                        <slot name="body">
                            default body
                        </slot>
                    </div>

                    <div class="modal-footer text-center">
                        <slot name="footer">
                            <button class="modal-default-button" @click="$emit('close')"> OK</button>
                        </slot>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</script>

<!-- jQuery 3 -->

<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('default/bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<!-- SlimScroll -->
<script src="{{ asset('default/bower_components/jquery-slimscroll/jquery.slimscroll.min.js')}}"></script>
<!-- FastClick -->
<script src="{{ asset('default/bower_components/fastclick/lib/fastclick.js')}}"></script>
<!-- iCheck 1.0.1 -->
<script src="{{ asset('default/plugins/iCheck/icheck.min.js')}}"></script>
<!-- jquery cookie -->
<script src="{{ asset('default/plugins/jquery-cookie/jquery.cookie.js')}}"></script>
{{--<script src="/public/default/plugins/iCheck/icheck.min.js"></script>--}}
<script src="{{ asset('default/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

<!-- AdminLTE App -->
<script src="{{ asset('default/dist/js/adminlte.min.js')}}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset('default/dist/js/demo.js')}}"></script>
<script src="{{ asset('js/jquery.doubleScroll.js')}}?b=1 "></script>
<script src="{{ asset('js/SpxApp.js')}}?b=1"></script>

{{--<script src="{{ taisan('/public/js/backend.js')}}?b={{config('app.build_version')}}"></script>--}}

{{--@yield('script')--}}
@isset($include_file)
    @include($include_file)
@endisset


<script>
    $(document).ready(function () {
        $('.select2').select2();
        $('.sidebar-menu').tree();

        // //iCheck for checkbox and radio inputs
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue'
        });

        $('.sidebar-toggle').click(function (event) {
            event.preventDefault();
            console.log('toggle');
            if (Boolean(localStorage.getItem('sidebar-toggle-collapsed'))) {
                localStorage.setItem('sidebar-toggle-collapsed', '');
            } else {
                localStorage.setItem('sidebar-toggle-collapsed', '1');
            }
        });



        $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
            checkboxClass: 'icheckbox_flat-green',
            radioClass: 'iradio_flat-green'
        });

        function getMessage() {

            $.get('/apps/ajax-get-help-desk')
                .done(function (data, status) {
                    if (status == 'success') {
                        // console.log(data);
                        $('#getMessage').html('(<span class="text-bold" style="color: #f1b351;">' + data + '</span>)');
                    }
                })
                .fail(function (err) {
                    console.log(err)
                });
            // setTimeout(getMessage,10000);
        }

        getMessage();


    })
</script>
<script>
    $(function () {
        $('.btnCloseAllNotify').click(function () {
            if ($('.btnCloseNotification').length > 0) {
                $('.btnCloseNotification').each(function (item) {
                    $(this).trigger('click');
                });
                $(this).parent().hide();
            }
        });
        $('.btnCloseNotification').click(function (e) {
            e.preventDefault();
            let id = $(this).attr('data-id');
            $.post('/apps/alert/readed-alert/' + id, {_token: $('meta[name=_token]').attr('content')})
                .done(data => {
                    if (data.status == 1) {
                        $(this).parent().hide();
                    } else {
                        if (data.errors.length > 0)
                            alert(data.errors.join(', '));
                    }
                })
                .fail(function (err) {
                    console.log(err);
                })
        });
    });
</script>
@yield('script')
</body>
</html>
