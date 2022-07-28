@extends('templates.layout')
@section('title', $_title)
@section('content')
    <section class="content-header">
        @include('templates.header-action')
    </section>

    <!-- Main content -->
    <section class="content appTuyenSinh">
        <link rel="stylesheet" href="{{ asset('default/bower_components/select2/dist/css/select2.min.css')}} ">
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
            .select2-container--default .select2-selection--multiple{
                margin-top:10px;
                border-radius: 0;
            }
            .select2-container--default .select2-results__group{
                background-color: #eeeeee;
            }
        </style>

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

    <!-- Phần nội dung riêng của action  -->
        <form class="form-horizontal " action="" method="post" >
            @csrf
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        {{--                        <div class="form-group">--}}
                        {{--                            <label for="ma_tai_san" class="col-md-3 col-sm-4 control-label">Mã tài sản <span class="text-danger">(*)</span></label>--}}

                        {{--                            <div class="col-md-9 col-sm-8">--}}
                        {{--                                <input type="text" name="ma_tai_san" id="ma_tai_san" class="form-control" value="@isset($request['ma_tai_san']){{ $request['ma_tai_san'] }}@endisset">--}}
                        {{--                                <span id="mes_sdt"></span>--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}
                        <div class="form-group">
                            <label for="name" class="col-md-3 col-sm-4 control-label">Tên lớp học <span class="text-danger">(*)</span></label>

                            <div class="col-md-9 col-sm-8">
                                <input type="text" name="name" id="ten_khoa_hoc" class="form-control" value="@isset($request['name']){{ $request['name'] }}@endisset">
                                <span id="mes_sdt"></span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="slot" class="col-md-3 col-sm-4 control-label">số chỗ <span class="text-danger">(*)</span></label>

                            <div class="col-md-9 col-sm-8">
                                <input type="text" name="slot" id="ten_khoa_hoc" class="form-control" value="@isset($request['slot']){{ $request['slot'] }}@endisset">
                                <span id="mes_sdt"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="start_date" class="col-md-3 col-sm-4 control-label">Thời gian bắt đầu <span class="text-danger">(*)</span></label>

                            <div class="col-md-9 col-sm-8">
                                <input type="date" name="start_date" id="ten_khoa_hoc" class="form-control" value="@isset($request['name']){{ $request['name'] }}@endisset">
                                <span id="mes_sdt"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="end_date" class="col-md-3 col-sm-4 control-label">Thời gian kết thúc <span class="text-danger">(*)</span></label>

                            <div class="col-md-9 col-sm-8">
                                <input type="date" name="end_date" id="ten_khoa_hoc" class="form-control" value="@isset($request['name']){{ $request['name'] }}@endisset">
                                <span id="mes_sdt"></span>
                            </div>
                        </div>
                </div>

                                    {{--                    tab2--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="lecturer_id" class="col-md-3 col-sm-4 control-label">Giảng viên</label>
                                            <div class="col-md-9 col-sm-8">
                                                <select name="lecturer_id" id="id_danh_muc" class="form-control select2" data-placeholder="Chọn giảng viên">
                                                    <option value="">== Giảng viên ==</option>
                                                    @foreach($user as $item)
                                                        <option value="{{ $item->id }}" @isset($request['lecturer_id']) @if($request['lecturer_id'] == $item->id) selected @endif @endisset>{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="location_id" class="col-md-3 col-sm-4 control-label">Địa điểm</label>
                                            <div class="col-md-9 col-sm-8">
                                                <select name="location_id" id="id_danh_muc" class="form-control select2" data-placeholder="Chọn địa điểm">
                                                    <option value="">== Địa điểm ==</option>
                                                    @foreach($centralFacility as $item)
                                                        <option value="{{ $item->id }}" @isset($request['location_id']) @if($request['location_id'] == $item->id) selected @endif @endisset>{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                        
                                        <div class="form-group">
                                            <label for="course_id" class="col-md-3 col-sm-4 control-label">Khoá Học</label>
                                            <div class="col-md-9 col-sm-8">
                                                <select name="course_id" id="id_danh_muc" class="form-control select2" data-placeholder="Chọn khoá học">
                                                    <option value="">== khoá học==</option>
                                                    @foreach($course as $item)
                                                        <option value="{{ $item->id }}" @isset($request['course_id']) @if($request['course_id'] == $item->id) selected @endif @endisset>{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                </div>
                    </div>
                </div>

            </div>
            <!-- /.box-body -->
            <div class="text-center">
                <button type="submit" class="btn btn-primary"> Save</button>
                <a href="{{ route('route_BackEnd_Class_List') }}" class="btn btn-default">Cancel</a>
            </div>
            <!-- /.box-footer -->
        </form>

    </section>
@endsection
@section('script')
    <script src="{{ asset('default/plugins/input-mask/jquery.inputmask.js') }}"></script>
    <script src="{{ asset('default/plugins/input-mask/jquery.inputmask.date.extensions.js') }}"></script>
    <script src="{{ asset('js/khoahoc.js') }} "></script>
    {{--    <script src="public/default/plugins/input-mask/jquery.inputmask.extensions.js"></script>--}}
    {{--    <script src="public/js/taisan.js"></script>--}}

@endsection

