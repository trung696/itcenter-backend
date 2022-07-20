@extends('templates.layout')
@section('title', '1233')

@section('script')
    <link rel="stylesheet" href="{{ asset('default/bower_components/select2/dist/css/select2.min.css') }} ">
    <link rel="stylesheet" href="{{ asset('default/bower_components/bootstrap-daterangepicker/daterangepicker.css') }} ">
@section('css')
    <style>
        body {
            /*-webkit-touch-callout: none;
                    -webkit-user-select: none;
                    -moz-user-select: none;
                    -ms-user-select: none;
                    -o-user-select: none;*/
            user-select: none;
        }

        .toolbar-box form .btn {
            /*margin-top: -3px!important;*/
        }

        .select2-container {
            margin-top: 0;
        }

        .select2-container--default .select2-selection--multiple {
            border-radius: 0;
        }

        .select2-container .select2-selection--multiple {
            min-height: 30px;
        }

        .select2-container .select2-search--inline .select2-search__field {
            margin-top: 3px;
        }

        .table>tbody>tr.success>td {
            background-color: #009688;
            color: white !important;
        }

        .table>tbody>tr.success>td span {
            color: white !important;
        }

        .table>tbody>tr.success>td span.button__csentity {
            color: #333 !important;
        }

        /*.table>tbody>tr.success>td i{*/
        /*    color: white !important;*/
        /*}*/
        .text-silver {
            color: #f4f4f4;
        }

        .btn-silver {
            background-color: #f4f4f4;
            color: #333;
        }

        .select2-container--default .select2-results__group {
            background-color: #eeeeee;
        }

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

        .select2-container--default .select2-selection--multiple {
            margin-top: 10px;
            border-radius: 0;
        }

        .select2-container--default .select2-results__group {
            background-color: #eeeeee;
        }
    </style>
@endsection
@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        @include('templates.header-action')

        <div class="clearfix"></div>
        <div style="border: 1px solid #ccc;margin-top: 10px;padding: 5px;">
            <form action="" method="get">
                <div class="row">
                    <div class="col-md-3 col-sm-6">
                        <div class="form-group">
                            <input type="text" name="search_ten_hoc_vien" class="form-control"
                                placeholder="Tên học viên/Số điện thoại/Gmail"
                                value="@isset($extParams['search_ten_hoc_vien']) {{ $extParams['search_ten_hoc_vien'] }} @endisset">
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="form-group">
                            <input type="text" name="search_ngay_dang_ky" class="form-control daterangepicker-click"
                                placeholder="Chọn ngày đăng ký"
                                value="@isset($extParams['search_ngay_dang_ky']) {{ $extParams['search_ngay_dang_ky'] }} @endisset"
                                autocomplete="off">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-xs-12" style="text-align:center;">
                        <div class="form-group">
                            <button type="submit" name="btnSearch" class="btn btn-primary btn-sm "><i class="fa fa-search"
                                    style="color:white;"></i> Search
                            </button>
                            <a href="{{ url('/danh-sach-dia-diem') }}" class="btn btn-default btn-sm "><i
                                    class="fa fa-remove"></i>
                                Clear </a>
                            <a href="{{ route('route_BackEnd_DangKyAdmin_Add') }}" class="btn btn-info btn-sm"><i
                                    class="fa fa-user-plus" style="color:white;"></i>
                                Add new</a>
                        </div>
                    </div>
                </div>

            </form>
            <div class="clearfix"></div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content appTuyenSinh">
        <div id="msg-box">
            <?php //Hiển thị thông báo thành công
            ?>
            @if (Session::has('success'))
                <div class="alert alert-success alert-dismissible" role="alert">
                    <strong>{{ Session::get('success') }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span>
                    </button>
                </div>
            @endif
            <?php //Hiển thị thông báo lỗi
            ?>
            @if (Session::has('error'))
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <strong>{{ Session::get('error') }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span>
                    </button>
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span>
                    </button>
                </div>
            @endif
        </div>
        @if (count($list) <= 0)
            <p class="alert alert-warning">
                Không có dữ liệu phù hợp
            </p>
        @endif
        <div class="box-body table-responsive no-padding">
            <form action="" method="post">
                @csrf
                <span class="pull-right">Tổng số bản ghi tìm thấy: <span
                        style="font-size: 15px;font-weight: bold;">{{ $list->count() }}</span></span>
                <div class="clearfix"></div>
                <div class="double-scroll">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 50px" class="text-center">
                                STT
                            </th>
                            <th class="text-center">Tên học viên</th>
                            <th class="text-center">Số điện thoại</th>
                            <th class="text-center">Gmail</th>
                            <th class="text-center">Lớp đăng ký</th>
                            <th class="text-center">Ngày đăng ký</th>
                            <th width="50px" class="text-center">Trạng thái</th>
                            <th width="50px" class="text-center">Công Cụ</th>
                        </tr>
                        @php($i = 1)

                        @foreach ($list as $item)
                            <tr>
                                {{-- <td><input type="checkbox" name="chk_hv[]" class="chk_hv" id="chk_hv_{{$item->id}}" value="{{$item->id}}"> </td> --}}
                                <td class="text-center">{{ $i++ }}</td>
                                <td class="text-center">{{ $item->ho_ten }}</td>
                                <td class="text-center">{{ $item->so_dien_thoai }}</td>
                                <td class="text-center">{{ $item->email }}</td>
                                <td class="text-center">{{ $item->name }}</td>
                                <td class="text-center">{{ $item->ngay_dang_ky }}</td>
                                <td class="text-center"
                                    style="width:180px; background-color:
                                @if ($item->trang_thai == 0) red
                                @else
                                        green @endif;
                                        color: white">
                                    @if ($item->trang_thai == 0)
                                        Chưa Thanh Toán
                                    @else
                                        Đã Thanh Toán
                                    @endif
                                </td>
                                <td class="text-center"><a
                                        href="{{ route('route_BackEnd_AdminDangKy_Detail', ['id' => $item->id]) }}"
                                        title="Sửa"><i class="fa fa-edit"></i></a></td>
                            </tr>
                        @endforeach

                    </table>
                </div>
            </form>
        </div>
        <br>
        <div class="text-center">
            {{ $list->appends($extParams)->links() }}
        </div>
        <index-cs ref="index_cs"></index-cs>
    </section>

@endsection

@section('script')
    <script src="{{ asset('default/plugins/input-mask/jquery.inputmask.js') }}"></script>
    <script src="{{ asset('default/plugins/input-mask/jquery.inputmask.date.extensions.js') }}"></script>
    {{-- <script src="public/default/plugins/input-mask/jquery.inputmask.extensions.js"></script> --}}
    <script src="{{ asset('default/bower_components/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('default/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>


    <script src="{{ asset('js/taisan.js') }} "></script>


@endsection