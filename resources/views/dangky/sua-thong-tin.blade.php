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
    <form class="form-horizontal " action="{{ route('route_BackEnd_AdminDangKy_Update', ['id' => request()->route('id'),'email'=>$itemHV->email,'oldClass'=>$itemDK,'newClass' => 4]) }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="box-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="ten_khoa_hoc" class="col-md-3 col-sm-4 control-label">Tên học viên <span class="text-danger">(*)</span></label>

                        <div class="col-md-9 col-sm-8">
                            <input type="text" name="ho_ten" id="ho_ten" class="form-control" value="{{ $itemHV->ho_ten }}" disabled>
                            <span id="mes_sdt"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="ngay_sinh" class="col-md-3 col-sm-4 control-label">Ngày sinh <span class="text-danger">(*)</span></label>

                        <div class="col-md-9 col-sm-8">
                            <input type="text" name="ngay_sinh" id="ngay_sinh" class="form-control" value="{{ $itemHV->ngay_sinh }}" disabled>
                            <span id="mes_sdt"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="so_dien_thoai" class="col-md-3 col-sm-4 control-label">Số điện thoại <span class="text-danger">(*)</span></label>

                        <div class="col-md-9 col-sm-8">
                            <input type="text" name="so_dien_thoai" id="so_dien_thoai" class="form-control" value="{{ $itemHV->so_dien_thoai }}" disabled>
                            <span id="mes_sdt"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="gia_tien" class="col-md-3 col-sm-4 control-label">Giá tiền <span class="text-danger">(*)</span></label>

                        <div class="col-md-9 col-sm-8">
                            <input type="text" name="gia_tien" id="gia_tien" class="form-control" value="{{ number_format($itemGia, 0, ',', '.') }}" disabled>
                            <span id="mes_sdt"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email" class="col-md-3 col-sm-4 control-label">Email <span class="text-danger">(*)</span></label>

                        <div class="col-md-9 col-sm-8">
                            <input type="text" name="email" id="email" class="form-control" value="{{ $itemHV->email }}" disabled>
                            <span id="mes_sdt"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email" class="col-md-3 col-sm-4 control-label">Khoá Học <span class="text-danger">(*)</span></label>

                        <div class="col-md-9 col-sm-8">
                            <input type="text" name="email" id="email" class="form-control" value="{{ $itemKH->name }}" disabled>
                            <span id="mes_sdt"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="id_lop_hoc" class="col-md-3 col-sm-4 control-label">Lớp Học</label>
                        <div class="col-md-9 col-sm-8">
                            <select name="id_lop_hoc" id="id_khoa_hoc" class="form-control select2" style="width: 100%" data-placeholder="Chọn lớp học">
                                <option value="">== Chọn Lớp học==</option>
                                @foreach ($listClass as $item)
                                <option value="{{ $item->id }}" @if($item->id == $itemDK) selected="selected" @endif>
                                    {{ $item->name }}
                                </option>

                                @endforeach
                            </select>
                        </div>
                    </div>
                    @if ($itemDKTT == 1)
                    <div class="form-group">
                        <label for="email" class="col-md-3 col-sm-4 control-label">Khoá Học <span class="text-danger">(*)</span></label>

                        <div class="col-md-9 col-sm-8">
                            <input type="text" name="trang_thai" id="trang_thai" class="form-control" value="Đã Thanh Toán" disabled>
                            <span id="mes_sdt"></span>
                        </div>
                    </div>
                    @elseif($itemDKTT == 0)
                    <div class="form-group">
                        <label for="nguon" class="col-md-3 col-sm-4 control-label">Trạng thái <span class="text-danger">(*)</span></label>
                        <div class="col-md-9 col-sm-8">
                            <select name="trang_thai" id="trang_thai" class="form-control select2" data-placeholder="Chọn trạng thái">
                                <option value="">== Chọn trạng thái ==</option>
                                <option value="0" @if ($itemTT==0) selected @endif>Chưa Thanh
                                    Toán</option>
                                <option value="1" @if ($itemTT==1) selected @endif>Đã Thanh
                                    Toán</option>
                                {{-- @foreach ($trang_thai as $index => $item)
                                            <option value="{{ $index }}"
                                @isset($request['trang_thai']) @if ($request['trang_thai'] == $index) selected @endif
                                @else @if ($index == $itemDKTT) selected @endif @endisset>
                                {{ $item }}</option>
                                @endforeach --}}
                            </select>
                        </div>
                    </div>
                    @endif



                    @if($getDuNo != 0 )
                    <div class="form-group">
                        <label for="so_dien_thoai" class="col-md-3 col-sm-4 control-label">Còn thiếu <span class="text-danger">(*)</span></label>

                        <div class="col-md-9 col-sm-8">
                            <input type="text" name="so_dien_thoai" id="so_dien_thoai" class="form-control" value="{{ number_format($getDuNo) }}" disabled>
                            <span id="mes_sdt"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="dong_them" class="col-md-3 col-sm-4 control-label">Đóng thêm <span class="text-danger">(*)</span></label>
                        <div class="col-md-9 col-sm-8">
                            <input type="number" name="dong_them" id="dong_them" class="form-control">
                            <span id="dong_them"></span>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>
        <!-- /.box-body -->
        <div class="text-center">
            <button type="submit" class="btn btn-primary"> Save</button>
            <a href="" class="btn btn-primary">In Hoá Dơn</a>
            <a href="{{ route('route_BackEnd_DanhSachDangKy_index') }}" class="btn btn-default">Cancel</a>
        </div>
        <!-- /.box-footer -->
    </form>

</section>
@endsection
@section('script')
<script src="{{ asset('default/plugins/input-mask/jquery.inputmask.js') }}"></script>
<script src="{{ asset('default/plugins/input-mask/jquery.inputmask.date.extensions.js') }}"></script>
<script src="{{ asset('js/khoahoc.js') }} "></script>
{{-- <script src="public/default/plugins/input-mask/jquery.inputmask.extensions.js"></script> --}}
{{-- <script src="public/js/taisan.js"></script> --}}

@endsection