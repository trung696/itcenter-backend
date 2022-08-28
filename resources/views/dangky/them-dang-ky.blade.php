@extends('templates.layout')
@section('title', $_title)
@section('content')
    <section class="content-header">
        @include('templates.header-action')
    </section>

    <!-- Main content -->
    <section class="content appTuyenSinh">
        <link rel="stylesheet" href="{{ asset('default/bower_components/select2/dist/css/select2.min.css') }} ">
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
        <form class="form-horizontal " action="" method="post" enctype="multipart/form-data">
            @csrf
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="ho_ten" class="col-md-3 col-sm-4 control-label">Họ tên học viên <span
                                    class="text-danger">(*)</span></label>
                            <div class="col-md-9 col-sm-8">
                                <input type="text" name="ho_ten" id="ho_ten" class="form-control"
                                    value="@isset($request['ho_ten']) {{ $request['ho_ten'] }} @endisset">
                                <span id="mes_sdt"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="cccd" class="col-md-3 col-sm-4 control-label">Căn cước công dân<span
                                    class="text-danger">(*)</span></label>
                            <div class="col-md-9 col-sm-8">
                                <input type="text" name="cccd" id="cccd" maxlength="12" class="form-control"
                                    value="@isset($request['cccd']) {{ $request['cccd'] }} @endisset">
                                <span id="mes_sdt"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="ngay_sinh" class="col-md-3 col-sm-4 control-label">Ngày sinh<span
                                    class="text-danger">(*)</span></label>
                            <div class="col-md-9 col-sm-8">
                                <?php
                                $date = date('Y-m-d');
                                $newdate = strtotime('-16 year', strtotime($date));
                                $maxdate = date('Y-m-d', $newdate);
                                $oldate = strtotime('-80 year', strtotime($date));
                                $mindate = date('Y-m-d', $oldate);
                                ?>
                                <input type="date" name="ngay_sinh" id="ngay_sinh" class="form-control"
                                    min="{{ $mindate }}" max="{{ $maxdate }}"
                                    value="@isset($request['ngay_sinh']) {{ $request['ngay_sinh'] }} @endisset">
                                <span id="mes_sdt"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="gioi_tinh" class="col-md-3 col-sm-4 control-label">Giới tính<span
                                    class="text-danger">(*)</span></label>
                            <div class="col-md-9 col-sm-8">
                                <input type="radio" id="html" name="gioi_tinh" value="1">
                                <label for="html">Nam</label><br>
                                <input type="radio" id="html" name="gioi_tinh" value="2">
                                <label for="html">Nữ</label><br>
                                <input type="radio" id="html" name="gioi_tinh" value="3">
                                <label for="html">Khác</label><br>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="so_dien_thoai" class="col-md-3 col-sm-4 control-label">Số điện thoại<span
                                    class="text-danger">(*)</span></label>
                            <div class="col-md-9 col-sm-8">
                                <input type="text" name="so_dien_thoai" id="so_dien_thoai" maxlength="10"
                                    class="form-control"
                                    value="@isset($request['so_dien_thoai']) {{ $request['so_dien_thoai'] }} @endisset">
                                <span id="mes_sdt"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-md-3 col-sm-4 control-label">Gmail<span
                                    class="text-danger">(*)</span></label>
                            <div class="col-md-9 col-sm-8">
                                <input type="text" name="email" id="email" class="form-control"
                                    value="@isset($request['email']) {{ $request['email'] }} @endisset">
                                <span id="mes_sdt"></span>
                            </div>
                        </div>


                        {{-- <div class="form-group">
                            <label class="col-md-3 col-sm-4 control-label">Ảnh Học Viên</label>
                            <div class="col-md-9 col-sm-8">
                                <div class="row">
                                    <div class="col-xs-6">
                                        <img id="hinh_anh_preview" src="http://placehold.it/100x100" alt="your image"
                                            style="max-width: 200px; height:100px; margin-bottom: 10px;"
                                            class="img-fluid" />
                                        <input type="file" name="hinh_anh" accept="image/*"
                                            class="form-control-file @error('hinh_anh') is-invalid @enderror"
                                            id="hinh_anh">
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                        <div class="form-group">
                            <label for="id_khoa_hoc" class="col-md-3 col-sm-4 control-label">Khoá Học</label>
                            <div class="col-md-9 col-sm-8">
                                <select name="id_khoa_hoc" id="id_khoa_hoc" class="form-control select2"
                                    data-placeholder="Chọn khoá học">
                                    <option value="">== Chọn khoá học==</option>
                                    @foreach ($objKhoaHoc as $item)
                                        <option value="{{ $item->id }}"
                                            @isset($request['id_khoa_hoc']) @if ($request['id_khoa_hoc'] == $item->id) selected @endif @endisset>
                                            {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="Mã giảm giá" class="col-md-3 col-sm-4 control-label">Mã khuyến mãi<span
                                    class="text-danger">(*)</span></label>
                            <div class="col-md-9 col-sm-8">
                                <input type="text" name="ma_khuyen_mai" id="ma_khuyen_mai" class="form-control"
                                    value="@isset($request['ma_khuyen_mai']) {{ $request['ma_khuyen_mai'] }} @endisset">
                                <span id="mes_sdt"></span>
                            </div>
                        </div>


                        <div class="form-group">
                            <label for="id_lop_hoc" class="col-md-3 col-sm-4 control-label">Danh sách lớp học</label>
                            <div class="col-md-9 col-sm-8">
                                <select name="id_lop_hoc" id="id_lop_hoc" class="form-control select2"
                                    data-placeholder="Chọn lớp học">
                                    <!-- @foreach ($objLopHoc as $item)
    <option value="{{ $item->id }}"
                                                                                                @isset($request['id_lop_hoc']) @if ($request['id_khoa_hoc'] == $item->id) selected @endif @endisset>{{ $item->name }}</option>
    @endforeach -->
                                </select>
                            </div>

                        </div>

                        <div class="form-group">
                            <label for="nguon" class="col-md-3 col-sm-4 control-label">Trạng thái <span
                                    class="text-danger">(*)</span></label>
                            <div class="col-md-9 col-sm-8">
                                <select name="trang_thai" id="trang_thai" class="form-control select2"
                                    data-placeholder="Chọn trạng thái">
                                    <option value="">== Chọn trạng thái ==</option>
                                    <option value="0"
                                        @isset($request['trang_thai']) @if ($request['trang_thai'] == 0) selected @endif @endisset>
                                        Chưa thanh toán</option>
                                    <option value="1"
                                        @isset($request['trang_thai']) @if ($request['trang_thai'] == 1) selected @endif @endisset>
                                        Đã thanh toán</option>

                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- /.box-body -->
                <div class="text-center">
                    <button type="submit" class="btn btn-primary"> Save</button>
                    <a href="{{ route('route_BackEnd_DanhSachDangKy_index') }}" class="btn btn-default">Cancel</a>
                </div>
                <!-- /.box-footer -->
        </form>

    </section>
@endsection
@section('script')
    <script src="{{ asset('default/plugins/input-mask/jquery.inputmask.js') }}"></script>
    <script src="{{ asset('default/plugins/input-mask/jquery.inputmask.date.extensions.js') }}"></script>
    <!-- <script src="{{ asset('js/dangkyadmin.js') }} "></script> -->
    {{-- <script src="public/default/plugins/input-mask/jquery.inputmask.extensions.js"></script> --}}
    <script src="{{ asset('/js/addDangky.js') }}"></script>

@endsection
