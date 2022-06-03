@extends('templates.layout')
@section('title', $_title)
@section('content')
    <section class="content-header">
        @include('templates.header-action')
    </section>

    <!-- Main content -->
    <section class="content appTuyenSinh">
        <link rel="stylesheet" href="{{ asset('default/bower_components/select2/dist/css/select2.min.css')}} ">
        <link rel="stylesheet" href="{{ asset('default/bower_components/bootstrap-daterangepicker/daterangepicker.css')}} ">

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

            .select2-container--default .select2-selection--multiple {
                margin-top: 10px;
                border-radius: 0;
            }

            .select2-container--default .select2-results__group {
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
        <form class="form-horizontal "
              action="{{ route('route_BackEnd_KhoaHoc_Update',['id'=>request()->route('id')]) }}" method="post"
              enctype="multipart/form-data">
            @csrf
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="ten_khoa_hoc" class="col-md-3 col-sm-4 control-label">Tên Khoá Học <span
                                        class="text-danger">(*)</span></label>

                            <div class="col-md-9 col-sm-8">
                                <input type="text" name="ten_khoa_hoc" id="ten_khoa_hoc" class="form-control"
                                       value="@isset($request['ten_khoa_hoc'])  {{ $request['ten_khoa_hoc'] }} @else {{ $objItem->ten_khoa_hoc }} @endisset" @if($objItem->ten_khoa_hoc != '')  @endif>
                                <span id="mes_sdt"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="thoi_gian" class="col-md-3 col-sm-4 control-label">Thời gian <span
                                        class="text-danger">(*)</span></label>

                            <div class="col-md-9 col-sm-8">
                                <input type="text" name="thoi_gian" id="thoi_gian" class="form-control"
                                       value="@isset($request['thoi_gian'])  {{ $request['thoi_gian'] }} @else {{ $objItem->thoi_gian }} @endisset" @if($objItem->thoi_gian != '')  @endif>
                                <span id="mes_sdt"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="hoc_phi" class="col-md-3 col-sm-4 control-label">Học phí <span
                                        class="text-danger">(*)</span></label>

                            <div class="col-md-9 col-sm-8">
                                <input type="text" name="hoc_phi" id="hoc_phi" class="form-control"
                                       value="@isset($request['hoc_phi'])  {{ $request['hoc_phi'] }} @else {{ $objItem->hoc_phi }} @endisset" @if($objItem->hoc_phi != '')  @endif>
                                <span id="mes_sdt"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="hoc_phi" class="col-md-3 col-sm-4 control-label">Hình ảnh khoá học <span
                                        class="text-danger">(*)</span></label>

                            <div class="col-md-9 col-sm-8">
                                <img id="hinh_anh_khoa_hoc_preview" src="{{ $objItem->hinh_anh?Storage::url($objItem->hinh_anh):'http://placehold.it/100x100' }}" alt="your image"
                                     style="max-width: 200px; height:100px; margin-bottom: 10px;" class="img-fluid"/>
                                <input type="file" name="hinh_anh_khoa_hoc" accept="image/*"
                                       class="form-control-file @error('hinh_anh_khoa_hoc') is-invalid @enderror" id="hinh_anh_khoa_hoc">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="thong_tin_khoa_hoc" class="col-md-3 col-sm-4 control-label">Thông tin khoá học <span
                                        class="text-danger">(*)</span></label>

                            <div class="col-md-9 col-sm-8">
                                <textarea name="thong_tin_khoa_hoc" id="thong_tin_khoa_hoc" class="form-control">
                                          @isset($request['thong_tin_khoa_hoc'])  {{ $request['thong_tin_khoa_hoc'] }} @else {{ $objItem->thong_tin_khoa_hoc }} @endisset @if($objItem->thong_tin_khoa_hoc != '')  @endif

                                </textarea>
                                <span id="mes_sdt"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="danh_muc_tai_san_id" class="col-md-3 col-sm-4 control-label">Danh Mục Khoá Học</label>
                            <div class="col-md-9 col-sm-8">
                                <select name="id_danh_muc" id="id_danh_muc" class="form-control select2"
                                        data-placeholder="Chọn danh mục khoá học">
                                    <option value="">== Chọn danh mục khoá học ==</option>
                                    @foreach($danh_muc_khoa_hoc as $item)
                                        <option value="{{ $item->id }}"
                                                @isset($request['danh_muc_khoa_hoc']) @if($request['danh_muc_khoa_hoc'] == $item->id) selected
                                                @endif @else @if($objItem->id_danh_muc == $item->id) selected @endif @endisset>
                                            {{ $item->ten_danh_muc }}
                                        </option>
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
                <a href="{{ route('route_BackEnd_KhoaHoc_index') }}" class="btn btn-default">Cancel</a>
            </div>
            <!-- /.box-footer -->
        </form>


        <div class="box box-primary" style="margin-top: 50px">
            <div class="box-header with-border">
                <div class="box-title">
                    Danh Sách Lớp Học
                </div>
                {{--                <span class="pull-right"><i class="fa fa-usd"></i> Đã thu: <strong>{{ formatNumber(approve) }}</strong><sup>đ</sup></span>--}}
            </div>
            <div class="box-body">

                <button v-if="marketing==0" class="btn btn-primary" onclick="addLopHoc()">Thêm Lớp Học</button>
                <a href="{{ route('route_BackEnd_TaiSanCon_InNhanTaiSan_Update',['id'=>request()->route('id')]) }}" target="_blank" class="btn btn-info"><i class="fa fa-print" style="color:white;"></i>
                    In Nhãn Tài Sản</a>
{{--                <button v-if="marketing==0" class="btn btn-success" onclick="addLopHocTS()">Tạo Tự Động Số Lượng Tài Sản Con</button>--}}
                <div style="border: 1px solid #ccc;margin-top: 10px;padding: 5px;">
                    <form action="" method="get">
                        <div class="row">
                            <div class="col-md-3 col-sm-6">
                                <div class="form-group">
                                    <input type="text" name="search_ten_lop" class="form-control" placeholder="Tên lớp học"
                                           value="@isset($extParams['search_ten_lop']){{$extParams['search_ten_lop']}}@endisset">
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="form-group">
                                    <input type="text" name="search_ngay_khai_giang" class="form-control daterangepicker-click" placeholder="Ngày khai giảng"
                                           value="@isset($extParams['search_ngay_khai_giang']){{$extParams['search_ngay_khai_giang']}}@endisset" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="form-group" style="margin-top: 5px">
                                    <select name="trang_thai" id="trang_thai" class="form-control select2"
                                            data-placeholder="Chọn trạng thái">
                                        <option value=""> == Chọn trạng thái ==</option>
                                        @if(count($trang_thai)>0)
                                            @foreach($trang_thai as $index => $mh)
                                                <option value="{{ $index }}"
                                                        @isset($extParams['trang_thai']) @if($extParams['trang_thai'] == $index) selected @endif @endisset>{{$mh}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            </div>

                            {{--                    @endif--}}
                            <div class="clearfix"></div>
                            <div class="col-xs-12" style="text-align:center;">
                                <div class="form-group">
                                    <button type="submit" name="btnSearch" class="btn btn-primary btn-sm "><i
                                                class="fa fa-search" style="color:white;"></i> Search
                                    </button>
                                    <a href="{{ route('route_BackEnd_TaiSan_Detail',['id'=>request()->route('id')]) }}" class="btn btn-default btn-sm "><i class="fa fa-remove"></i>
                                        Clear </a>
                                </div>
                            </div>
                    </form>
                    <div class="clearfix"></div>
                </div>

                <div class="modal fade" id="myModal" role="dialog">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                {{--                                <h4 class="modal-title">Thêm mới lần đóng: {{ hoten}}</h4>--}}
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-danger" style="display:none"></div>
                                <form action="{{ route('route_BackEnd_addLopHoc_Add') }}" method="post" id="preview_form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="ten_lop_hoc">Tên Lớp Học <span
                                                            class="text-danger">(*)</span></label>
                                                <input type="text" class="form-control" name="ten_lop_hoc"
                                                       id="ten_lop_hoc">
                                            </div>
                                            <div class="form-group">
                                                <label for="ca_hoc">Ca học<span class="text-danger">(*)</span></label>
                                                <input type="text" class="form-control" name="ca_hoc"
                                                       id="ca_hoc">
                                            </div>
                                            <div class="form-group">
                                                <label for="thoi_giang_khai_giang">Thời gian khai giảng<span class="text-danger">(*)</span></label>
                                                <input type="date" class="form-control" name="thoi_giang_khai_giang" id="thoi_giang_khai_giang">
                                            </div>

                                            <div class="form-group">
                                                <label for="id_dia_diem" class="col-md-12" style="padding:0">Địa điểm<span class="text-danger">(*)</span></label>
                                                <input type="text" class="form-control" name="id_dia_diem" id="id_dia_diem">
{{--                                                <select name="id_dia_diem" id="id_dia_diem"--}}
{{--                                                        class="form-control select2  col-md-12" style="width: 100%">--}}
{{--                                                    <option value="">Vui lòng lựa chọn địa điểm sử dụng</option>--}}
{{--                                                    @foreach($don_vi as $item)--}}
{{--                                                        <option value="{{ $item->id }}"--}}
{{--                                                                @isset($request['id_don_vi']) @if($request['id_don_vi'] == $item->id) selected @endif @endisset>{{ $item->ten_don_vi }}</option>--}}
{{--                                                    @endforeach--}}
{{--                                                </select>--}}
                                            </div>
                                            <div class="form-group">
                                                <label for="so_cho" class="col-md-12" style="padding:0">Số chỗ<span class="text-danger">(*)</span></label>
                                                <input type="text" class="form-control" name="so_cho" id="so_cho">
                                            </div>

                                            <div class="form-group hide">
                                                <label for="id_khoa_hoc" class="col-md-12" style="padding:0">Khoá học<span class="text-danger">(*)</span></label>
                                                <input type="text" class="form-control" name="id_khoa_hoc" id="id_khoa_hoc" value="{{ $objItem->id }}">
                                            </div>
                                            <div class="form-group">
                                                <label for="id_giang_vien">Giảng Viên<span
                                                            class="text-danger">(*)</span></label>
                                                <input type="text" class="form-control" name="id_giang_vien" id="id_giang_vien">
                                            </div>
                                        </div>
                                    </div>
                                            <div class="form-group">
                                                <label for="trang_thai" class="col-md-12" style="padding:0">Trạng thái <span
                                                            class="text-danger">(*)</span></label>
                                                <select name="trang_thai" id="trang_thai"
                                                        class="form-control select2 col-md-12" style="width: 100%">
                                                    <option value="">== Chọn trạng thái ==</option>
                                                    @foreach($trang_thai as $index => $item)
                                                        <option value="{{ $index }}">{{ $item }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                    <div class="text-center">
                                        <button class="btn btn-primary" id="saveLopHoc" type="submit">Lưu lại</button>
                                        <button type="reset" class="btn btn-default">Nhập lại</button>
                                        <button type="button" class="btn btn-danger"  onclick="huy()">Hủy</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                {{--                <div class="modal fade" id="myModalTS" role="dialog">--}}
                {{--                    <div class="modal-dialog modal-lg">--}}
                {{--                        <div class="modal-content">--}}
                {{--                            <div class="modal-header">--}}
                {{--                                <button type="button" class="close" data-dismiss="modal">&times;</button>--}}
                {{--                                --}}{{--                                <h4 class="modal-title">Thêm mới lần đóng: {{ hoten}}</h4>--}}
                {{--                            </div>--}}
                {{--                            <div class="modal-body">--}}
                {{--                                <div class="alert alert-danger" style="display:none"></div>--}}
                {{--                                <form action="{{ route('route_BackEnd_addSoLuongTaiSan_Add') }}" method="post" id="preview_form">--}}
                {{--                                    @csrf--}}
                {{--                                    <div class="row">--}}
                {{--                                        <div class="col-md-12">--}}
                {{--                                            <div class="form-group">--}}
                {{--                                                <label for="so_luong_tai_san">Nhập số lượng tài sản<span--}}
                {{--                                                        class="text-danger">(*)</span></label>--}}
                {{--                                                <input type="number" class="form-control" name="so_luong_tai_san"--}}
                {{--                                                       id="so_luong_tai_san">--}}
                {{--                                            </div>--}}
                {{--                                            <div class="form-group hidden">--}}
                {{--                                                <label for="id_tai_san">ID Tài Sản<span--}}
                {{--                                                        class="text-danger">(*)</span></label>--}}
                {{--                                                <input type="number" class="form-control" name="id_tai_san"--}}
                {{--                                                       id="id_tai_san" value="{{ $objItem->id }}">--}}
                {{--                                            </div>--}}
                {{--                                    </div>--}}
                {{--                                    <div class="text-center">--}}
                {{--                                        <button class="btn btn-primary" id="saveSoLuongTS" type="submit">Lưu lại</button>--}}
                {{--                                        <button type="reset" class="btn btn-default">Nhập lại</button>--}}
                {{--                                        <button type="button" class="btn btn-danger"  onclick="huyTS()">Hủy</button>--}}
                {{--                                    </div>--}}
                {{--                                    </div>--}}
                {{--                                </form>--}}
                {{--                            </div>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                </div>--}}
                <div class="clearfix"></div>
                <div v-if="list_hoa_dons.length>0" class="table-responsive">
                    <table class="table table-bordered" style="margin-top:20px;">
                        <tbody>
                        <tr>
                            <th>#ID</th>
                            <th>Tên lớp hoc</th>
{{--                            <th>Ca học</th>--}}
                            <th>Thời gian khai giang</th>
                            <th>Địa điểm</th>
                            <th>Số chỗ</th>
                            <th>Trạng thái</th>
                            <th>Công cụ</th>
                        </tr>
                        @foreach($lists as $key => $item)
                            <tr>

                                <td>{{ $item->id }}</td>
                                <td>{{ $item->ten_lop_hoc }}</td>
                                <td>{{ $item->thoi_gian_khai_giang }}</td>
                                <td>{{ $item->id_dia_diem }}</td>
                                <td>{{ $item->so_cho }}</td>
                                <td>
                                    @if($item->trang_thai == 0)
                                        Đóng
                                    @elseif($item->trang_thai == 1)
                                        Mở
                                @endif
{{--                                <td>{{ number_format($item->nguyen_gia) }} vnđ</td>--}}
{{--                                <td>{{ $item->thoi_gian_khau_hao }} năm</td>--}}
{{--                                <td>{{ $item->gia_tri_con_lai }}%</td>--}}
{{--                                <td>{{ $item->thoi_han_bao_hanh }} năm</td>--}}
{{--                                <td class="text-center" style="background-color:--}}
{{--                                @if($item->trang_thai == 0)--}}
{{--                                        red--}}
{{--                                @elseif($item->trang_thai == 1)--}}
{{--                                        blue--}}
{{--                                @elseif($item->trang_thai == 2)--}}
{{--                                        orange--}}
{{--                                @elseif($item->trang_thai == 3)--}}
{{--                                        violet--}}
{{--                                @elseif($item->trang_thai == 4)--}}
{{--                                        green--}}
{{--                                @endif;--}}
{{--                                        color: white">--}}
{{--                                    @if($item->trang_thai == 0)--}}
{{--                                        tạm dừng sử dụng--}}
{{--                                    @elseif($item->trang_thai == 1)--}}
{{--                                        thanh lý--}}
{{--                                    @elseif($item->trang_thai == 2)--}}
{{--                                        bảo hành--}}
{{--                                    @elseif($item->trang_thai == 3)--}}
{{--                                        sửa chữa--}}
{{--                                    @elseif($item->trang_thai == 4)--}}
{{--                                        đang sử dụng--}}
{{--                                    @endif--}}
{{--                                </td>--}}
                                <td class="text-center">
                                    <a href="{{ route('route_BackEnd_LopHoc_Detail',['id'=> $item->id]) }}" title="Sửa" ><i class="fa fa-edit"></i></a>
                                    <a href="{{ route('route_BackEnd_TaiSanCon_Delete',['id'=> $item->id]) }}" title="Xóa" ><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>

                    </table>
                </div>
                {{--                <div v-else class="alert alert-warning" style="margin-top:20px;">Đẹp zai lỗi tại ai :)))))</div>--}}

            </div>
            <br>
            <div class="text-center">
{{--                {{  $lists->appends($extParams)->links() }}--}}
            </div>
        </div>

    </section>
@endsection
@section('script')
    <script src="{{ asset('default/plugins/input-mask/jquery.inputmask.js') }}"></script>
    <script src="{{ asset('default/plugins/input-mask/jquery.inputmask.date.extensions.js') }}"></script>
    {{--    <script src="public/default/plugins/input-mask/jquery.inputmask.extensions.js"></script>--}}
    <script src="{{ asset('default/bower_components/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('default/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>


    <script src="{{ asset('js/taisan.js') }} "></script>
    <script src="{{ asset('js/khoahoc.js') }} "></script>


@endsection

