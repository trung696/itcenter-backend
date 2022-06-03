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
        <form class="form-horizontal " action="" method="post" enctype="multipart/form-data">
            @csrf
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="ma_khuyen_mai" class="col-md-3 col-sm-4 control-label">Mã Khuyến Mại <span class="text-danger">(*)</span></label>

                                                    <div class="col-md-9 col-sm-8">
                                                        <input type="text" name="ma_khuyen_mai" id="ma_khuyen_mai" class="form-control" value="@isset($request['ma_khuyen_mai']){{ $request['ma_khuyen_mai'] }}@endisset">
                                                        <span id="mes_sdt"></span>
                                                    </div>
                                                </div>
                        <div class="form-group">
                            <label for="ten_khuyen_mai" class="col-md-3 col-sm-4 control-label">Tên khuyến mại <span class="text-danger">(*)</span></label>

                            <div class="col-md-9 col-sm-8">
                                <input type="text" name="ten_khuyen_mai" id="ten_khuyen_mai" class="form-control" value="@isset($request['ten_khuyen_mai']){{ $request['ten_khuyen_mai'] }}@endisset">
                                <span id="mes_sdt"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="ngay_bat_dau" class="col-md-3 col-sm-4 control-label">Thời gian bắt đầu<span class="text-danger">(*)</span></label>

                            <div class="col-md-9 col-sm-8">
                                <input type="date" name="ngay_bat_dau" id="ngay_bat_dau" class="form-control" value="@isset($request['ngay_bat_dau']){{ $request['ngay_bat_dau'] }}@endisset">
                                <span id="mes_sdt"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="ngay_ket_thuc" class="col-md-3 col-sm-4 control-label">Thời gian kết thúc<span class="text-danger">(*)</span></label>

                            <div class="col-md-9 col-sm-8">
                                <input type="date" name="ngay_ket_thuc" id="ngay_ket_thuc" class="form-control" value="@isset($request['ngay_ket_thuc']){{ $request['ngay_ket_thuc'] }}@endisset">
                                <span id="mes_sdt"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="phan_tram_khuyen_mai" class="col-md-3 col-sm-4 control-label">Phần trăm khuyến mại<span class="text-danger">(*)</span></label>

                            <div class="col-md-9 col-sm-8">
                                <input type="number" name="phan_tram_khuyen_mai" id="phan_tram_khuyen_mai" class="form-control" value="@isset($request['phan_tram_khuyen_mai']){{ $request['phan_tram_khuyen_mai'] }}@endisset">
                                <span id="mes_sdt"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 col-sm-4 control-label">Ảnh Khuyến Mại</label>
                            <div class="col-md-9 col-sm-8">
                                <div class="row">
                                    <div class="col-xs-6">
                                        <img id="hinh_anh_khuyen_mai_preview" src="http://placehold.it/100x100" alt="your image"
                                             style="max-width: 200px; height:100px; margin-bottom: 10px;" class="img-fluid"/>
                                        <input type="file" name="hinh_anh_khuyen_mai" accept="image/*"
                                               class="form-control-file @error('hinh_anh_khuyen_mai') is-invalid @enderror" id="hinh_anh_khuyen_mai">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nguon" class="col-md-3 col-sm-4 control-label">Trạng thái <span class="text-danger">(*)</span></label>
                            <div class="col-md-9 col-sm-8">
                                <select name="trang_thai" id="trang_thai" class="form-control select2" data-placeholder="Chọn trạng thái">
                                    <option value="">== Chọn trạng thái ==</option>
                                    @foreach($trang_thai as $index => $item)
                                        <option value="{{ $index }}" @isset($request['trang_thai']) @if($request['trang_thai'] == $index) selected @endif @endisset>{{ $item }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>
                    {{--                    tab2--}}
                    <div class="col-md-6">

                    </div>
                </div>

            </div>
            <!-- /.box-body -->
            <div class="text-center">
                <button type="submit" class="btn btn-primary"> Save</button>
                <a href="{{ route('route_BackEnd_DanhSachKhhuyenMai_index') }}" class="btn btn-default">Cancel</a>
            </div>
            <!-- /.box-footer -->
        </form>

    </section>
@endsection
@section('script')
    <script src="{{ asset('default/plugins/input-mask/jquery.inputmask.js') }}"></script>
    <script src="{{ asset('default/plugins/input-mask/jquery.inputmask.date.extensions.js') }}"></script>
    <script src="{{ asset('js/khuyenmai.js') }} "></script>
    {{--    <script src="public/default/plugins/input-mask/jquery.inputmask.extensions.js"></script>--}}
    {{--    <script src="public/js/taisan.js"></script>--}}

@endsection

