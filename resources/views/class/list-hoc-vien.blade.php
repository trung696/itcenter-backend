@extends('templates.layout')
<!-- @section('title', '1233') -->
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


<!-- Main content -->
<section class="content appTuyenSinh">
    <div class="box-body table-responsive no-padding">
        <form action="" method="post">
            @csrf
            <span class="pull-right">Tổng số bản ghi tìm thấy: <span style="font-size: 15px;font-weight: bold;"></span></span>
            <div class="clearfix"></div>
            <div class="double-scroll">
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 50px" class="text-center">
                            STT
                        </th>
                        <th class="text-center">Tên Học viên</th>
                        <th class="text-center">Email Học viên</th>
                        <th class="text-center">SĐT</th>
                        <th class="text-center">Số CMT/CCCD</th>
                        <th class="text-center">Địa chỉ</th>
                        <th class="text-center">Giới tính</th>
                        <th class="text-center">Hình ảnh Học viên</th>
                    </tr>

                    @foreach ($hocViens as $hocVien => $value)
                    <tr>
                        <td> {{$hocVien + 1}}</td>
                        <td class="text-center">{{$value->ho_ten}}</td>
                        <td class="text-center">{{$value->email}}</td>
                        <td class="text-center">{{$value->so_dien_thoai}}</td>
                        <td class="text-center">{{$value->cccd}}</td>

                        <td class="text-center">{{$value->address}}</td>
                        <td class="text-center">
                            @if($value->gioi_tinh == config('gioi_tinh.sex.0') )
                            Nam
                            @else
                            Nữ
                            @endif
                        </td>
                        <td class="image-clean"> @if(isset($value->hinh_anh) && $value->hinh_anh)
                            <img src="{{$value->avatar}}" style="max-width: 50px">
                            @elseif (isset($value->hinh_anh))
                            Update di
                            @endif
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