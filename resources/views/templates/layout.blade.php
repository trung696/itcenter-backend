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
    {{-- Dành cho vuejs --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta id="_token" name="_token" content="{!! csrf_token() !!}" />
    {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('default/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('default/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('default/bower_components/Ionicons/css/ionicons.min.css') }}">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="{{ asset('default/plugins/iCheck/all.css') }}">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('default/dist/css/AdminLTE.min.css') }}">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{ asset('default/dist/css/skins/_all-skins.min.css') }}">
    <link rel="stylesheet" href="{{ asset('default/dist/css/spx.css') }}">
    <link rel="stylesheet" href="{{ asset('css/SpxApp.css') }}?b={{ config('app.build_version') }}">
    <link rel="stylesheet" href="{{ asset('css/backend.css') }}?b={{ config('app.build_version') }}">

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
    <link rel="stylesheet" href="{{ asset('default/bower_components/select2/dist/css/select2.min.css') }}">
    @yield('css')
    <style>
        .select2-container--default .select2-selection--single,
        .select2-selection .select2-selection--single {
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
    {{-- <script type="text/javascript" src="/public/js/app.js?b={{config('app.build_version')}}"></script> --}}
    <script src="{{ asset('default/bower_components/jquery/dist/jquery.min.js') }}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- <script src="{{ taisan('/public/default/dist/js/spx.js')}}"></script> --}}

    <script>
        var sapp = new Object();
    </script>
</head>

<body class="hold-transition skin-blue sidebar-mini fixed">
    <script>
        (function() {
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
                    {{-- <img src="/public/img/logo-itplus-black.png" width="50"> --}}
                    <img src="/img/logo.png" style="width: 100%; padding: 5px;">
                    {{-- <img src="/public/img/lg-tet.png" width="50"> --}}
                </span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg">
                    {{-- <img src="/public/img/logo-itplus-black.png" height="40"> --}}
                    <img src="/img/logo.png" style="width: 100%;">
                    {{-- <img src="/public/img/lg-tet.png" height="45"> --}}
                    {{-- <b class="visible-lg">Quản lý Đào tạo</b> --}}
                </span>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            {{-- @inject('siteInfo', 'App\Services\SiteInformation') --}}

            <nav class="navbar navbar-static-top">
                {{-- //noel moi dung      <span class="santa"></span> --}}
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>

                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">

                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="/img/no-avartar.png" class="user-image" alt="User Image">
                                <span class="hidden-xs"> {{ $objUser->name }} </span>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <p style="padding: 10px;">
                                        <i class="fa fa-user"></i> Email: {{ $objUser->email }}<br>
                                        {{-- <i class="fa fa-envelope"></i> Email: {{ $objUser->email }} --}}
                                    </p>
                                </li>
                                <!-- <li>
                <a href="{{ route('logout') }}" class="btn btn-default btn-flat">Sign out</a>
              </li> -->
                                <!-- User image -->

                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        {{-- <a href="{{ route('route_BackEnd_User_changePassword',['id'=>Auth::id()]) }}" class="btn btn-default btn-flat">Change password</a> --}}
                                    </div>
                                    <div class="pull-right">
                                        <a href="{{ route('logout') }}" class="btn btn-default btn-flat">Sign out</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <!-- Control Sidebar Toggle Button -->
                        {{-- <li> --}}
                        {{-- <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a> --}}
                        {{-- </li> --}}
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

                <ul class="sidebar-menu" data-widget="tree">
                    <li class=" active menu-open ">
                        <a href="{{ route('route_BackEnd_NguoiDung_index') }}"><i class="fa fa-user"></i> <span>Người
                                dùng</span></a>
                        <ul class="treeview-menu">
                            <li><a href=""><i class="fa fa-circle-o"></i> Người dùng</a></li>
                        </ul>
                    </li>

                    <li class=" active menu-open ">
                        <a href="{{ route('route_BackEnd_teacher_list') }}"><i class="fa fa-dollar"></i> <span>Giảng
                                Viên</span></a>
                        <ul class="treeview-menu">
                            <li><a href="{{ route('route_BackEnd_teacher_list') }}"><i class="fa fa-circle-o"></i>
                                    Danh
                                    sách giảng viên</a></li>
                        </ul>
                    </li>

                    <li class=" active menu-open ">
                        <a href="#"><i class="fa fa-users"></i> <span>Học viên</span></a>
                        <ul class="treeview-menu">
                            <li><a href="{{ route('route_BackEnd_DanhSachHocVien_index') }}"><i
                                        class="fa fa-circle-o"></i>Danh sách học viên</a></li>
                            <li><a href="{{ route('route_BackEnd_DanhSachDangKy_index') }}"><i
                                        class="fa fa-circle-o"></i>Danh sách đăng ký</a></li>
                        </ul>
                    </li>

                    <li class=" active menu-open ">
                        <a href="#"><i class="fa fa-dollar"></i> <span>Khóa học</span></a>
                        <ul class="treeview-menu">
                            <li><a href="{{ route('route_BackEnd_CourseCategory_List') }}"><i
                                        class="fa fa-circle-o"></i> Danh mục khóa học</a></li>
                            <li><a href="{{ route('route_BackEnd_Course_List') }}"><i class="fa fa-circle-o"></i>
                                    Khóa học</a></li>
                        </ul>
                    </li>
                    <li class=" active menu-open ">
                        <a href="#"><i class="fa fa-dollar"></i> <span>Lớp học</span></a>
                        <ul class="treeview-menu">
                            <li><a href="{{ route('route_BackEnd_Class_List') }}"><i class="fa fa-circle-o"></i>
                                    Danh sách lớp học</a></li>
                        </ul>
                    </li>

                    <li class=" active menu-open ">
                        <a href="#"><i class="fa fa-dollar"></i> <span>Ca học</span></a>
                        <ul class="treeview-menu">
                            <li><a href="{{ route('route_BackEnd_Ca_List') }}"><i class="fa fa-circle-o"></i>
                                    Danh sách ca học</a></li>
                        </ul>
                    </li>

                    <li class=" active menu-open ">
                        <a href="#"><i class="fa fa-dollar"></i> <span>Chuyển lớp</span></a>
                        <ul class="treeview-menu">
                            <li><a href="{{ route('route_BackEnd_list_doi_lop') }}"><i class="fa fa-circle-o"></i>
                                    Danh sách chuyển lớp</a></li>
                        </ul>
                    </li>

                    <li class=" active menu-open ">
                        <a href="#"><i class="fa fa-dollar"></i> <span>Danh sách hoàn tiền</span></a>
                        <ul class="treeview-menu">
                            <li><a href="{{ route('route_BackEnd_list_hoan_tien') }}"><i class="fa fa-circle-o"></i>
                                    Danh sách sinh viên thừa tiền</a></li>
                        </ul>
                    </li>

                    <li class=" active menu-open ">
                        <a href="#"><i class="fa fa-dollar"></i> <span>Cơ sở trung tâm</span></a>
                        <ul class="treeview-menu">
                            <li><a href="{{ route('route_BackEnd_CentralFacility_List') }}"><i
                                        class="fa fa-circle-o"></i> Danh sách cơ sở trung tâm</a></li>
                        </ul>
                    </li>

                    <li class=" active menu-open ">
                        <a href="#"><i class="fa fa-dollar"></i> <span>Tài Liệu</span></a>
                        <ul class="treeview-menu">
                            <li><a href="{{ route('route_BackEnd_Document_List') }}"><i class="fa fa-circle-o"></i>
                                    Danh sách tài liệu</a></li>
                        </ul>
                    </li>
                    <li class=" active menu-open ">
                        <a href="#"><i class="fa fa-dollar"></i> <span>Phương thức thanh toán</span></a>
                        <ul class="treeview-menu">
                            <li><a href="{{ route('route_BackEnd_PaymentMethod_List') }}"><i
                                        class="fa fa-circle-o"></i> Danh sách phương thức thanh toán</a></li>
                        </ul>
                    </li>
                    <li class=" active menu-open ">
                        <a href="#"><i class="fa fa-users"></i> <span>Khuyến mãi</span></a>
                        <ul class="treeview-menu">
                            <li><a href="{{ route('route_BackEnd_ChienDich_index') }}"><i
                                        class="fa fa-circle-o"></i>Danh
                                    sách Chiến dịch </a>
                            </li>
                        </ul>
                        {{-- <ul class="treeview-menu">
                            <li><a href="{{ route('route_BackEnd_DanhSachKhhuyenMai_index') }}"><i class="fa fa-circle-o"></i>Danh sách khuyến mãi</a>
                            </li>
                        </ul> --}}

                    </li>
                    </li>


                    {{-- <li class=" active menu-open ">
                        <a href="#"><i class="fa fa-users"></i> <span>Role</span></a>
                        <ul class="treeview-menu">
                            <li><a href="{{ route('route_BackEnd_role_list') }}"><i class="fa fa-circle-o"></i>Danh
                    sách Role</a></li>
                </ul>
                </li>

                <li class=" active menu-open ">
                    <a href="#"><i class="fa fa-users"></i> <span>Permission</span></a>
                    <ul class="treeview-menu">
                        <li><a href="{{ route('route_BackEnd_permission_add') }}"><i class="fa fa-circle-o"></i>Thêm Permission</a></li>
                    </ul>
                </li> --}}




                    {{-- <li class=" active menu-open "> --}}
                    {{-- </a> --}}
                    {{-- </li> --}}
                    {{-- <li class=" active menu-open "> --}}
                    {{-- <a href="#"><i class="fa fa-paperclip"></i> <span>Biên Bản</span></a> --}}
                    {{-- <ul class="treeview-menu"> --}}
                    {{-- <li><a href="{{ route('route_BackEnd_BienBan_index') }}"><i --}} {{-- class="fa fa-circle-o"></i>Biên Bản Bàn Giao</a></li> --}} {{-- </ul> --}} {{-- <ul class="treeview-menu"> --}}
                    {{-- <li><a href="{{ route('route_BackEnd_BienBanKiemKe_index') }}"><i --}} {{-- class="fa fa-circle-o"></i>Biên Bản Kiểm Kê</a></li> --}} {{-- </ul> --}} {{-- <ul class="treeview-menu"> --}}
                    {{-- <li><a href="{{ route('route_BackEnd_BienBanThanhLi_index') }}"><i --}} {{-- class="fa fa-circle-o"></i>Biên Bản Thanh Lí</a></li> --}} {{-- </ul> --}} {{-- </li> --}}
                    {{-- <li class=" active menu-open "> --}} {{-- </a> --}} {{-- </li> --}}
                </ul>
            </section>
            <!-- /.sidebar -->
        </aside>


        <div id="app" class="content-wrapper" style="background-color: #ecf0f5;">

            <div class="clearfix"></div>
            </ul>
            </section>
            <!-- /.sidebar -->
            </aside>

            @yield('content')
            <div class="clearfix"></div>

        </div>
        <!-- /.content-wrapper -->
        <footer class="main-footer">
            <div class="pull-right hidden-xs">
                {{-- <b>Version</b> {{ config('app.app_version') }} by MRS --}}
            </div>
            <strong>Copyright &copy; 2018-<?php echo date('Y'); ?>
        </footer>

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
    <script type="text/x-template" id="modal-template"><transition name="modal">
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
                </transition></script>

    <!-- jQuery 3 -->

    <!-- Bootstrap 3.3.7 -->
    <script src="{{ asset('default/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <!-- SlimScroll -->
    <script src="{{ asset('default/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
    <!-- FastClick -->
    <script src="{{ asset('default/bower_components/fastclick/lib/fastclick.js') }}"></script>
    <!-- iCheck 1.0.1 -->
    <script src="{{ asset('default/plugins/iCheck/icheck.min.js') }}"></script>
    <!-- jquery cookie -->
    <script src="{{ asset('default/plugins/jquery-cookie/jquery.cookie.js') }}"></script>
    {{-- <script src="/public/default/plugins/iCheck/icheck.min.js"></script> --}}
    <script src="{{ asset('default/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

    <!-- AdminLTE App -->
    <script src="{{ asset('default/dist/js/adminlte.min.js') }}"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{ asset('default/dist/js/demo.js') }}"></script>
    <script src="{{ asset('js/jquery.doubleScroll.js') }}?b=1 "></script>
    <script src="{{ asset('js/SpxApp.js') }}?b=1"></script>

    {{-- <script src="{{ taisan('/public/js/backend.js')}}?b={{config('app.build_version')}}"></script> --}}

    {{-- @yield('script') --}}
    @isset($include_file)
        @include($include_file)
    @endisset


    <script>
        $(document).ready(function() {
            $('.select2').select2();
            $('.sidebar-menu').tree();

            // //iCheck for checkbox and radio inputs
            $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass: 'iradio_minimal-blue'
            });

            $('.sidebar-toggle').click(function(event) {
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
                    .done(function(data, status) {
                        if (status == 'success') {
                            // console.log(data);
                            $('#getMessage').html('(<span class="text-bold" style="color: #f1b351;">' + data +
                                '</span>)');
                        }
                    })
                    .fail(function(err) {
                        // console.log(err)
                    });
                // setTimeout(getMessage,10000);
            }

            getMessage();


        })
    </script>
    <script>
        $(function() {
            $('.btnCloseAllNotify').click(function() {
                if ($('.btnCloseNotification').length > 0) {
                    $('.btnCloseNotification').each(function(item) {
                        $(this).trigger('click');
                    });
                    $(this).parent().hide();
                }
            });
            $('.btnCloseNotification').click(function(e) {
                e.preventDefault();
                let id = $(this).attr('data-id');
                $.post('/apps/alert/readed-alert/' + id, {
                        _token: $('meta[name=_token]').attr('content')
                    })
                    .done(data => {
                        if (data.status == 1) {
                            $(this).parent().hide();
                        } else {
                            if (data.errors.length > 0)
                                alert(data.errors.join(', '));
                        }
                    })
                    .fail(function(err) {
                        console.log(err);
                    })
            });
        });
    </script>
    @yield('script')
</body>

</html>
