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

        .table > tbody > tr.success > td {
            background-color: #009688;
            color: white !important;
        }

        .table > tbody > tr.success > td span {
            color: white !important;
        }

        .table > tbody > tr.success > td span.button__csentity {
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
                            <input type="text" name="search_sdt_gmail" class="form-control" placeholder="Nhâp số điện thoại/gmail sinh viên"
                                   value="@isset($extParams['search_sdt_gmail']){{$extParams['search_sdt_gmail']}}@endisset">
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                            <div class="form-group">
                                <select name="id_khuyen_mai" class="form-control select1"
                                        data-placeholder="Chọn trạng thái">
                                    <option value=""> == Chọn Chiến Dịch ==</option>
                                    @if(count($chien_dich)>0)
                                        @foreach($chien_dich as $key => $item)
                                            <option value="{{ $item->id }}"
                                                    @isset($request['id_chien_dich']) @endisset>
                                                {{ $item->ten_chien_dich }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="form-group">
                            <button type="submit" name="btnGuiMa" class="btn btn-primary btn-sm "> Gửi mã khuyến mãi
                            </button>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-xs-12" style="text-align:center;">
                        <div class="form-group">
                            <button type="submit" name="btnSearch" class="btn btn-primary btn-sm "><i
                                        class="fa fa-search" style="color:white;"></i> Search
                            </button>
                            <a href="{{ url('/danh-muc-khoa-hoc') }}" class="btn btn-default btn-sm "><i class="fa fa-remove"></i>
                                Clear </a>
                            <a href="{{ route('route_BackEnd_DanhMucKhoaHoc_Add') }}" class="btn btn-info btn-sm"><i class="fa fa-user-plus" style="color:white;"></i>
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
            <?php //Hiển thị thông báo thành công?>
            @if ( Session::has('success') )
                <div class="alert alert-success alert-dismissible" role="alert">
                    <strong>{{ Session::get('success') }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span>
                    </button>
                </div>
            @endif
            <?php //Hiển thị thông báo lỗi?>
            @if ( Session::has('error') )
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <strong>{{ Session::get('error') }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span>
                    </button>
                </div>
            @endif
        </div>
        @if(count($list)<=0)
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
                            <th class="text-center">STT</th>
                            <th class="text-center">Tên học viên</th>
                            <th class="text-center">Ngày sinh</th>
                            <th class="text-center">Số điện thoại</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Công cụ</th>
                        </tr>
                        @php($i=1)

                        @foreach($list as  $item)
                            <tr>
                                <td class="text-center">{{$i++}}</td>
                                <td class="text-center">{{$item->ho_ten}}</td>
                                <td class="text-center">{{$item->ngay_sinh}}</td>
                                <td class="text-center">{{$item->so_dien_thoai}}</td>
                                <td class="text-center">{{$item->email}}</td>
                                <td class="text-center"><a href="" title="Sửa"><i class="fa fa-edit"></i></a></td>
                            </tr>
                        @endforeach

                    </table>
                </div>
            </form>
        </div>
        <br>
        <div class="text-center">
            {{  $list->appends($extParams)->links() }}
        </div>
        <index-cs ref="index_cs"></index-cs>
    </section>

@endsection


