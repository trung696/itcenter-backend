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
                            <label for="ten_khoa_hoc" class="col-md-3 col-sm-4 control-label">Tên Lớp Học <span
                                        class="text-danger">(*)</span></label>

                            <div class="col-md-9 col-sm-8">
                                <input type="text" name="ten_khoa_hoc" id="ten_khoa_hoc" class="form-control"
                                       value="@isset($request['ten_lop_hoc'])  {{ $request['ten_lop_hoc'] }} @else {{ $objItem->ten_lop_hoc }} @endisset" @if($objItem->ten_lop_hoc != '')  @endif>
                                <span id="mes_sdt"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="thong_tin_khoa_hoc" class="col-md-3 col-sm-4 control-label">Thời gian khai giảng <span
                                        class="text-danger">(*)</span></label>

                            <div class="col-md-9 col-sm-8">
                                <input type="text" name="ca_hoc" id="ca_hoc" class="form-control"
                                       value="@isset($request['thoi_gian_khai_giang'])  {{ $request['thoi_gian_khai_giang'] }} @else {{ $objItem->thoi_gian_khai_giang }} @endisset" @if($objItem->thoi_gian_khai_giang != '')  @endif>
                                <span id="mes_sdt"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="ke_hoach_hoc_tap" class="col-md-3 col-sm-4 control-label">Địa điểm <span
                                        class="text-danger">(*)</span></label>

                            <div class="col-md-9 col-sm-8">
                                <input type="text" name="ca_hoc" id="ca_hoc" class="form-control"
                                       value="@isset($request['id_dia_diem'])  {{ $request['id_dia_diem'] }} @else {{ $objItem->id_dia_diem }} @endisset" @if($objItem->id_dia_diem != '')  @endif>
                                <span id="mes_sdt"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="ke_hoach_hoc_tap" class="col-md-3 col-sm-4 control-label">Số chỗ <span
                                        class="text-danger">(*)</span></label>

                            <div class="col-md-9 col-sm-8">
                                <input type="text" name="ca_hoc" id="ca_hoc" class="form-control"
                                       value="@isset($request['so_cho'])  {{ $request['so_cho'] }} @else {{ $objItem->so_cho }} @endisset" @if($objItem->so_cho != '')  @endif>
                                <span id="mes_sdt"></span>
                            </div>
                        </div>
                    </div>
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
                    Danh Sách Tài Sản Con
                </div>
                {{--                <span class="pull-right"><i class="fa fa-usd"></i> Đã thu: <strong>{{ formatNumber(approve) }}</strong><sup>đ</sup></span>--}}
            </div>
            <div class="box-body">

                <button v-if="marketing==0" class="btn btn-primary" onclick="addTaiSanCon()">Thêm Lớp Học</button>
                <a href="{{ route('route_BackEnd_LopHoc_InLopHoc_Update',['id'=>request()->route('id')]) }}" target="_blank" class="btn btn-info"><i class="fa fa-print" style="color:white;"></i>
                    In Danh Sách Lớp Học</a>

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
                                <form action="{{ route('route_BackEnd_addSoLuongTaiSan_Add') }}" method="post" id="preview_form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="thong_so_ky_thuat">Thông số kĩ thuật <span
                                                            class="text-danger">(*)</span></label>
                                                <input type="text" class="form-control" name="thong_so_ky_thuat"
                                                       id="thong_so_ky_thuat">
                                            </div>
                                            <div class="form-group">
                                                <label for="nam_su_dung">Năm sử dụng<span class="text-danger">(*)</span></label>
                                                <input type="date" class="form-control" name="nam_su_dung"
                                                       id="nam_su_dung">
                                            </div>
                                            <div class="form-group">
                                                <label for="xuat_xu">Xuất xứ<span class="text-danger">(*)</span></label>
                                                <input type="text" class="form-control" name="xuat_xu" id="xuat_xu">
                                            </div>
                                            <div class="form-group">
                                                <label for="id_don_vi" class="col-md-12" style="padding:0">Đơn vị sử
                                                    dụng<span class="text-danger">(*)</span></label>
                                                <select name="id_don_vi" id="id_don_vi"
                                                        class="form-control select2  col-md-12" style="width: 100%">
                                                    <option value="">Vui lòng lựa chọn đơn vị sử dụng</option>
                                                    {{--                                                    @foreach($don_vi as $item)--}}
                                                    {{--                                                        <option value="{{ $item->id }}"--}}
                                                    {{--                                                                @isset($request['id_don_vi']) @if($request['id_don_vi'] == $item->id) selected @endif @endisset>{{ $item->ten_don_vi }}</option>--}}
                                                    {{--                                                    @endforeach--}}
                                                </select>
                                            </div>
                                            <div class="form-group hide">
                                                <label for="id_tai_san" class="col-md-12" style="padding:0">Loại Tài Sản<span class="text-danger">(*)</span></label>
                                                {{--                                                <input type="text" class="form-control" name="id_tai_san" id="id_tai_san" value="{{ $objItem->id }}">--}}
                                            </div>
                                            <div class="form-group">
                                                <label for="nguon_kinh_phi">Nguồn kinh phí<span
                                                            class="text-danger">(*)</span></label>
                                                <select name="nguon_kinh_phi" id="nguon_kinh_phi" class="form-control select2 col-md-12" style="width: 100%">
                                                    <option value="">Vui lòng lựa chọn nguồn kinh phí</option>
                                                    {{--                                                    @foreach($nguon_kinh_phi as $index => $item)--}}
                                                    {{--                                                        <option value="{{ $index }}">{{ $item }}</option>--}}
                                                    {{--                                                    @endforeach--}}
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group  row">
                                                <div class="col-md-12">
                                                    <label for="nguyen_gia">Nguyên giá<span
                                                                class="text-danger">(*)</span>
                                                    </label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control" name="nguyen_gia"
                                                           id="nguyen_gia">
                                                </div>
                                                VNĐ
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-md-12">
                                                    <label for="thoi_gian_khau_hao">Thời gian khấu hao<span
                                                                class="text-danger">(*)</span></label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control" name="thoi_gian_khau_hao"
                                                           id="thoi_gian_khau_hao">
                                                </div> Năm
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-md-12">
                                                    <label for="gia_tri_con_lai">Giá trị còn lại<span class="text-danger">(*)</span></label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control" name="gia_tri_con_lai"
                                                           id="gia_tri_con_lai">
                                                </div>
                                                %
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-md-12">
                                                    <label for="thoi_han_bao_hanh">Thời gian bảo hành<span
                                                                class="text-danger">(*)</span></label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control" name="thoi_han_bao_hanh"
                                                           id="thoi_han_bao_hanh">
                                                </div>Năm
                                            </div>
                                            <div class="form-group">
                                                <label for="nguon" class="col-md-12" style="padding:0">Trạng thái <span
                                                            class="text-danger">(*)</span></label>
                                                <select name="trang_thai" id="trang_thai"
                                                        class="form-control select2 col-md-12" style="width: 100%">
                                                    <option value="">== Chọn trạng thái ==</option>
                                                    {{--                                                    @foreach($trang_thais as $index => $item)--}}
                                                    {{--                                                        <option value="{{ $index }}">{{ $item }}</option>--}}
                                                    {{--                                                    @endforeach--}}
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group row">
                                                <div class="col-md-3">
                                                    <label for="so_luong_tai_san">Nhập số lượng tài sản<span
                                                                class="text-danger">(*)</span></label>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="number" class="form-control" name="so_luong_tai_san"
                                                           id="so_luong_tai_san">
                                                </div>
                                                Tài Sản Con
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <button class="btn btn-primary" id="saveTaiSanCon" type="submit">Lưu lại</button>
                                        <button type="reset" class="btn btn-default">Nhập lại</button>
                                        <button type="button" class="btn btn-danger"  onclick="huy()">Hủy</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div v-if="list_hoa_dons.length>0" class="table-responsive">
                    <table class="table table-bordered" style="margin-top:20px;">
                        <tbody>
                        <tr>
                            <th>STT</th>
                            <th>Tên học viên</th>
                            <th>Ngày sinh</th>
                            <th>Ngày đăng ký</th>
                            <th>Số điện thoại</th>
                            <th>Email</th>
                            <th>Trạng thái</th>
                            <th>Công cụ</th>
                        </tr>
                        @foreach($lists as $key => $item)
                            <tr>

                                <td>{{$item->id}}</td>
                                <td>{{$item->ho_ten}}</td>
                                <td>{{$item->ngay_sinh}}</td>
                                <td>{{$item->ngay_dang_ky}}</td>
                                <td>{{$item->so_dien_thoai}}</td>
                                <td>{{$item->email}}</td>
                                <td>
                                    @if($item->trang_thai == 0)
                                        Chưa thanh toán
                                    @elseif($item->trang_thai == 1)
                                        Đã thanh toán
                                @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('route_BackEnd_TaiSanCon_Detail',['id'=> $item->id,'idTaiSan'=>request()->route('id')]) }}" title="Sửa" ><i class="fa fa-edit"></i></a>
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


@endsection

