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
                            <input type="text" name="search_ten_danh_muc_khoa_hoc" class="form-control" placeholder="T??n danh m???c kho?? h???c"
                                   value="@isset($extParams['search_ten_danh_muc_khoa_hoc']){{$extParams['search_ten_danh_muc_khoa_hoc']}}@endisset">
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
            <?php //Hi???n th??? th??ng b??o th??nh c??ng?>
            @if ( Session::has('success') )
                <div class="alert alert-success alert-dismissible" role="alert">
                    <strong>{{ Session::get('success') }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span>
                    </button>
                </div>
            @endif
            <?php //Hi???n th??? th??ng b??o l???i?>
            @if ( Session::has('error') )
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
        @if(count($list)<=0)
            <p class="alert alert-warning">
                Kh??ng c?? d??? li???u ph?? h???p
            </p>
        @endif
        <div class="box-body table-responsive no-padding">
            <form action="" method="post">
                @csrf
                <span class="pull-right">T???ng s??? b???n ghi t??m th???y: <span
                            style="font-size: 15px;font-weight: bold;">{{ $list->count() }}</span></span>
                <div class="clearfix"></div>
                <div class="double-scroll">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 50px" class="text-center">
                                STT
                            </th>
                            <th class="text-center">T??n Gi???ng Vi??n</th>
                            <th class="text-center">Th??ng tin gi???ng vi??n</th>
                            <th class="text-center">H??nh ???nh gi???ng vi??n</th>
                            <th width="50px" class="text-center">Tr???ng th??i</th>
                            <th width="50px" class="text-center">C??ng C???</th>
                        </tr>
                        @php($i=1)

                        @foreach($list as  $item)

                            <tr>
                                {{--                                <td><input type="checkbox" name="chk_hv[]" class="chk_hv" id="chk_hv_{{$item->id}}" value="{{$item->id}}"> </td>--}}
                                <td class="text-center">{{$i++}}</td>
                                <td class="text-center">{{$item->ten_giang_vien}}</td>
                                <td class="text-center">{{$item->thong_tin_giang_vien}}</td>
                                <td class="image-clean"><img src="{{ $item->hinh_anh_giang_vien?Storage::url($item->hinh_anh_giang_vien):'http://placehold.it/100x100' }}" style="max-width: 50px"></td>
                                <td class="text-center" style="width:180px; background-color:
                                @if($item->trang_thai == 0)
                                        red
                                @else
                                        green
                                @endif;
                                        color: white">
                                    @if($item->trang_thai == 0)
                                        D???ng Ho???t ?????ng
                                    @else
                                        ??ang Ho???t ?????ng
                                    @endif
                                </td>
                                <td class="text-center"><a href="{{ route('route_BackEnd_DanhMucKhoaHoc_Detail',['id'=> $item->id ]) }}" title="S???a"><i class="fa fa-edit"></i></a></td>
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


