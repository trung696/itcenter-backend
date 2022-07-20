@extends('templates.layout')
@section('title', '1233')
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
                            <input type="text" name="search_ten_danh_muc_khoa_hoc" class="form-control"
                                placeholder="Tên danh mục khoá học" value="">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-xs-12" style="text-align:center;">
                        <div class="form-group">
                            <button type="submit" name="btnSearch" class="btn btn-primary btn-sm "><i class="fa fa-search"
                                    style="color:white;"></i> Search
                            </button>
                            <a href="" class="btn btn-default btn-sm "><i class="fa fa-remove"></i>
                                Clear </a>

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
        <p class="alert alert-warning">
            Không có dữ liệu phù hợp
        </p>
        <div class="box-body table-responsive no-padding">
            <form action="" method="post">
                @csrf
                <span class="pull-right">Tổng số bản ghi tìm thấy: <span
                        style="font-size: 15px;font-weight: bold;"></span></span>
                <div class="clearfix"></div>
                <div class="double-scroll">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 50px" class="text-center">
                                STT
                            </th>
                            <th class="text-center">Tên Bộ môn</th>
                            <th class="text-center">Tên khóa học</th>
                            <th class="text-center">Số lượng học sinh</th>
                            <th width="50px" class="text-center">Trạng thái</th>
                            <th width="50px" class="text-center">Hành động</th>
                        </tr>

                        @foreach ($class as $item)
                            <tr>
                                <td><input type="checkbox" name="chk_hv[]" class="chk_hv" id="" value="">
                                </td>
                                <td class="text-center">{{ $item->email }}</td>
                                <td class="text-center">{{ $item->name }}</td>
                                <td class="text-center">{{ $item->password }}</td>
                                <td class="text-center">{{ $item->phone_number }}</td>
                                <td class="text-center">{{ $item->address }}</td>
                                <td class="text-center">
                                    @if ($student->sex == config('gioi_tinh.sex.0'))
                                        Nam
                                    @else
                                        Nữ
                                    @endif
                                </td>
                                <td class="image-clean">
                                    @if (isset($student->avatar) && $student->avatar)
                                        <img src="{{ $student->avatar }}" style="max-width: 50px">
                                    @elseif (isset($student->avatar))
                                        Update di
                                    @endif
                                </td>
                                <td class="text-center"
                                    style="background-color:
                                @if ($student->status == config('trang_thai.status.0')) red
                                @else
                                    green @endif;
                                    color: white">
                                    @if ($student->status == config('trang_thai.status.0'))
                                        Chưa kích hoạt
                                    @else
                                        Kích hoạt
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('route_BackEnd_student_edit', ['id' => $student->id]) }}"
                                        title="Sửa"><i class="fa fa-edit"></i></a>
                                    <a href="" title="Xóa"><i class="fa fa-remove"></i></a>
                                    <a href="{{ route('route_BackEnd_register_add', ['id' => $student->id]) }}">Đăng kí
                                        học</a>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </form>
        </div>
        <br>
        <div class="text-center">

        </div>
        <index-cs ref="index_cs"></index-cs>
    </section>

@endsection
