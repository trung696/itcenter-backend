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
                            <input type="text" name="search_ten_khoa_hoc" class="form-control" placeholder="Tên khoá học"
                                   value="@isset($extParams['search_ten_khoa_hoc']){{$extParams['search_ten_khoa_hoc']}}@endisset">
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="form-group">
                            <select name="search_danh_muc_khoa_hoc" class="form-control select1"
                                    data-placeholder="Chọn trạng thái">
                                <option value=""> == Chọn Danh Mục Khoá Học ==</option>
                                @if(count($course_category)>0)
                                    @foreach($course_category as $key => $item)
                                        <option value="{{ $item->id }}"
                                                @isset($extParams['search_danh_muc_khoa_hoc']) @if($extParams['search_danh_muc_khoa_hoc'] )  @endif @endisset>{{$item->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-xs-12" style="text-align:center;">
                        <div class="form-group">
                            <button type="submit" name="btnSearch" class="btn btn-primary btn-sm "><i
                                        class="fa fa-search" style="color:white;"></i> Search
                            </button>
                            <a href="{{ url('/course') }}" class="btn btn-default btn-sm "><i class="fa fa-remove"></i>
                                Clear </a>
                            <a href="{{ route('route_BackEnd_Course_Add') }}" class="btn btn-info btn-sm"><i class="fa fa-user-plus" style="color:white;"></i>
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
                            <th class="text-center">Tên Khoá Học</th>
                        <th class="text-center">Giá</th>
                            <th class="text-center">Thông tin khoá học</th>
                            <th class="text-center">Hình ảnh</th>
                            <th class="text-center">Danh mục</th>
                            <th width="50px" class="text-center">Trạng thái</th>
                            <th width="50px" class="text-center">Công Cụ</th>
                        </tr>
                        @php($i=1)

                        @foreach($list as  $item)
                        {{-- {{ dd(asset("storage/hinh_anh_khoa_hoc/$item->image")) }} --}}
                        {{-- {{dd(Storage::url("app/public/hinh_anh_khoa_hoc/$item->image"))}} --}}
                            <tr>
                                {{--                                <td><input type="checkbox" name="chk_hv[]" class="chk_hv" id="chk_hv_{{$item->id}}" value="{{$item->id}}"> </td>--}}
                                <td class="text-center">{{$i++}}</td>
                                <td class="text-center">{{$item->name}}</td>
                                <td class="text-center">{{ $item->price }}</td>
                                <td class="text-center">{{$item->description}}</td>
                                <td class="image-clean"><img src="{{ $item->image?$item->image:'http://placehold.it/100x100' }}" style="max-width: 50px"></td>
                                <td class="text-center">{{$arrCategory[$item->category_id]}}</td>
                                <td class="text-center" style="width:180px;background-color:
                                @if($item->status == 0)
                                        red
                                @else
                                        green
                                @endif;
                                        color: white">
                                    @if($item->status == 0)
                                        Dừng Hoạt Động
                                    @else
                                        Đang Hoạt Động
                                    @endif
                                </td>

                                <td class="text-center">
                                    <a href="{{ route('route_BackEnd_Course_Detail',['id'=> $item->id]) }}"title="Chi tiết"><i class="fa fa-edit"></i></a>
                                    <td class="text-center"><a onclick="return confirm('Bạn có muốn xóa?')" href="{{ route('route_BackEnd_Course_Delete',['id'=> $item->id ]) }}" title="Xóa"><i class="fa fa-trash" aria-hidden="true"></i></a></td>
                                </td>
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


