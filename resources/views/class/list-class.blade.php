@extends('templates.layout')
@section('title', $_title)
@section('content')
    <section class="content-header">
        @include('templates.header-action')
    </section>

    <!-- Main content -->
    <section class="content appTuyenSinh">
        <link rel="stylesheet" href="{{ asset('default/bower_components/select2/dist/css/select2.min.css') }} ">
        <link rel="stylesheet" href="{{ asset('default/bower_components/bootstrap-daterangepicker/daterangepicker.css') }} ">

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

            .select2-container--default .select2-selection--multiple {
                margin-top: 10px;
                border-radius: 0;
            }

            .select2-container--default .select2-results__group {
                background-color: #eeeeee;
            }
        </style>

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

        <!-- Phần nội dung riêng của action  -->
        <div class="box box-primary" style="margin-top: 50px">
            <div class="box-header with-border">
                <div class="box-title">
                    Danh Sách Lớp Học
                </div>
            </div>
            <div class="box-body">

                <a v-if="marketing==0" class="btn btn-primary" href="{{ route('route_BackEnd_Class_Add') }}">Thêm Lớp
                    Học</a>
                {{-- <a href="{{ route('route_BackEnd_TaiSanCon_InNhanTaiSan_Update',['id'=>request()->route('id')]) }}" target="_blank" class="btn btn-info"><i class="fa fa-print" style="color:white;"></i>
                    In Nhãn Tài Sản</a> --}}
                <div style="border: 1px solid #ccc;margin-top: 10px;padding: 5px;">
                    <form action="" method="get">
                        <div class="row">
                            <div class="col-md-3 col-sm-6">
                                <div class="form-group">
                                    <input type="text" name="search_name_class" class="form-control"
                                        placeholder="Tên lớp học"
                                        value="@isset($extParams['search_name_class']) {{ $extParams['search_name_class'] }} @endisset">
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="form-group">
                                    <input type="text" name="search_ngay_khai_giang"
                                        class="form-control daterangepicker-click" placeholder="Ngày khai giảng"
                                        value="@isset($extParams['search_ngay_khai_giang']) {{ $extParams['search_ngay_khai_giang'] }} @endisset"
                                        autocomplete="off">
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-6">
                                <div class="form-group">
                                    <select name="search_khoa_hoc" class="form-control select1" data-placeholder="Chọn khóa học">
                                        <option value=""> == Chọn Khoá Học ==</option>
                                        @if(count($course)>0)
                                        @foreach($course as $key => $item)
                                        <option value="{{ $item->id }}" @isset($extParams['search_khoa_hoc']) @if($extParams['search_khoa_hoc'] ) @endif @endisset>{{$item->name}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-6">
                                <div class="form-group">
                                    <select name="search_giang_vien" class="form-control select1" data-placeholder="Chọn giảng viên">
                                        <option value=""> == Chọn Giảng viên ==</option>
                                        @if(count($lecturer)>0)
                                        @foreach($lecturer as $key => $item)
                                        <option value="{{ $item->id }}" @isset($extParams['search_giang_vien']) @if($extParams['search_giang_vien'] ) @endif @endisset>{{$item->name}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            {{-- <div class="col-md-4 col-sm-6">
                                <div class="form-group" style="margin-top: 5px">
                                    <select name="trang_thai" id="trang_thai" class="form-control select2"
                                            data-placeholder="Chọn trạng thái">
                                        <option value=""> == Chọn trạng thái ==</option>
                                        @if (count($trang_thai) > 0)
                                            @foreach ($trang_thai as $index => $mh)
                                                <option value="{{ $index }}"
                                                        @isset($extParams['trang_thai']) @if ($extParams['trang_thai'] == $index) selected @endif @endisset>{{$mh}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div> --}}
                        </div>

                        <div class="clearfix"></div>
                        <div class="col-xs-12" style="text-align:center;">
                            <div class="form-group">
                                <button type="submit" name="btnSearch" class="btn btn-primary btn-sm "><i
                                        class="fa fa-search" style="color:white;"></i> Search
                                </button>
                                {{-- <a href="{{ route('route_BackEnd_TaiSan_Detail',['id'=>request()->route('id')]) }}" class="btn btn-default btn-sm "><i class="fa fa-remove"></i>
                                        Clear </a> --}}
                            </div>
                        </div>
                    </form>
                    <div class="clearfix"></div>
                </div>

                <div class="clearfix"></div>
                <div v-if="list_hoa_dons.length>0" class="table-responsive">
                    <table class="table table-bordered" style="margin-top:20px;">
                        <tbody>
                            <tr>
                                <th>#ID</th>
                                <th>Tên lớp hoc</th>
                                <th>Giá</th>
                                <th>Số chỗ</th>
                                <th>Ngày bắt đầu</th>
                                <th>Ngày kết thúc</th>
                                <th>Giảng viên</th>
                                <th>Địa điểm</th>
                                <th>Khóa học</th>
                                <th>Ca học</th>
                                <th>Công cụ</th>
                            </tr>
                            @foreach ($lists as $key => $item)
                                <tr>

                                    <td>{{ $item->id }}</td>
                                    <td> <a
                                            href="{{ route('route_danhsachlop', ['id' => $item->id]) }}">{{ $item->name }}</a>
                                    </td>
                                    <td>{{ $arrCoursePrice[$item->course_id] }}</td>
                                    <td>{{ $item->slot }}</td>
                                    <td>{{ $item->start_date }}</td>
                                    <td>{{ $item->end_date }}</td>
                                    <td>{{ $arrUser[$item->lecturer_id] }}</td>
                                    <td>{{ $arrFacility[$item->location_id] }}</td>
                                    <td>{{ $arrCourse[$item->course_id] }}</td>
                                    <td>{{ $arrCaHoc[$item->id_ca] }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('route_BackEnd_Class_Detail', ['id' => $item->id]) }}"
                                            title="Sửa"><i class="fa fa-edit"></i></a>
                                    <td class="text-center"><a onclick="return confirm('Bạn có muốn xóa?')"
                                            href="{{ route('route_BackEnd_Class_Delete', ['id' => $item->id]) }}"
                                            title="Xóa"><i class="fa fa-trash" aria-hidden="true"></i></a></td>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>

            </div>
            <br>
        
        </div>
        <div class="text-center">
            {{ $lists->appends($extParams)->links() }}
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
    <script src="{{ asset('js/khoahoc.js') }} "></script>


@endsection
