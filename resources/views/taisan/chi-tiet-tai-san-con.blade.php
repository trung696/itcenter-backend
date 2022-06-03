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
        <form class="form-horizontal " action="{{ route('route_BackEnd_TaiSanCon_Update',['id'=>request()->route('id')]) }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="box-body">
                <div class="row">
                    <div class="col-md-5 col-12">
                        <div class="form-group">
                            <label for="thong_so_ky_thuat">Thông số kĩ thuật <span
                                    class="text-danger">(*)</span></label>
                            <input type="text" name="thong_so_ky_thuat" id="thong_so_ky_thuat" class="form-control"
                                   value="@isset($request['thong_so_ky_thuat'])  {{ $request['thong_so_ky_thuat'] }} @else {{ $objItem->thong_so_ky_thuat }} @endisset" @if($objItem->thong_so_ky_thuat != '')  @endif>

                        </div>
                        <div class="form-group">
                            <label for="nam_su_dung">Năm sử dụng<span class="text-danger">(*)</span></label>
                            <input type="date" name="nam_su_dung" id="nam_su_dung" class="form-control"
                                   value="{{ date('Y-m-d' , strtotime($objItem->nam_su_dung)) }}">

                        </div>
                        <div class="form-group">
                            <label for="xuat_xu">Xuất xứ<span class="text-danger">(*)</span></label>
                            <input type="text" name="xuat_xu" id="xuat_xu" class="form-control"
                                   value="@isset($request['xuat_xu'])  {{ $request['xuat_xu'] }} @else {{ $objItem->xuat_xu }} @endisset" @if($objItem->xuat_xu != '')  @endif>
                        </div>
                        <div class="form-group">
                            <label for="id_don_vi" class="col-md-12" style="padding:0">Đơn vị sử
                                dụng</label>
                            <select name="id_don_vi" id="id_don_vi"
                                    class="form-control select2  col-md-12" style="width: 100%">
                                <option value="">Vui lòng lựa chọn đơn vị sử dụng</option>
                                @foreach($don_vi as $item)
                                    <option value="{{ $item->id }}"
                                            @isset($request['id_don_vi']) @if($request['id_don_vi'] == $item->id) selected
                                            @endif @else @if($objItem->id_don_vi == $item->id) selected @endif @endisset>
                                        {{ $item->id }}.{{ $item->ten_don_vi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group hide">
                            <label for="id_tai_san" class="col-md-12" style="padding:0">Loại Tài Sản<span class="text-danger">(*)</span></label>
                            <input type="text" name="id_tai_san" id="id_tai_san" class="form-control"
                                   value="@isset($request['id_tai_san'])  {{ $request['id_tai_san'] }} @else {{ $objItem->id_tai_san }} @endisset" @if($objItem->id_tai_san != '')  @endif>
                        </div>
                        <div class="form-group">
                            <label for="nguon_kinh_phi" style="margin: 0">Nguồn kinh phí<span
                                    class="text-danger">(*)</span></label>
                            <select name="nguon_kinh_phi" id="nguon_kinh_phi"  class="form-control select2 col-md-12" style="width: 100%">
                                @foreach($nguon_kinh_phi as $index => $item)
                                    <option value="{{ $index }}"
                                            @isset($request['nguon_kinh_phi']) @if($request['nguon_kinh_phi'] == $index) selected
                                            @endif @else @if($objItem->nguon_kinh_phi == $index) selected @endif @endisset>
                                        {{ $item }}
                                    </option>
                                @endforeach
                            </select>
                            </div>

                    </div>
                    <div class="col-md-2 col-0">

                    </div>
                    <div class="col-md-5 col-12">
                        <div class="form-group">
                            <label for="nguyen_gia">Nguyên giá<span
                                    class="text-danger">(*)</span></label>
                            <input type="text" name="nguyen_gia" id="nguyen_gia" class="form-control"
                                   value="@isset($request['nguyen_gia'])  {{ $request['nguyen_gia'] }} @else {{ $objItem->nguyen_gia }} @endisset" @if($objItem->nguyen_gia != '')  @endif>
                        </div>
                        <div class="form-group">
                            <label for="thoi_gian_khau_hao">Thời gian khấu hao<span
                                    class="text-danger">(*)</span></label>
                            <input type="text" name="thoi_gian_khau_hao" id="thoi_gian_khau_hao" class="form-control"
                                   value="@isset($request['thoi_gian_khau_hao'])  {{ $request['thoi_gian_khau_hao'] }} @else {{ $objItem->thoi_gian_khau_hao }} @endisset" @if($objItem->thoi_gian_khau_hao != '')  @endif>
                        </div>
                        <div class="form-group">
                            <label for="gia_tri_con_lai">Giá trị còn lại<span class="text-danger">(*)</span></label>
                            <input type="text" name="gia_tri_con_lai" id="gia_tri_con_lai" class="form-control"
                                   value="@isset($request['gia_tri_con_lai'])  {{ $request['gia_tri_con_lai'] }} @else {{ $objItem->gia_tri_con_lai }} @endisset" @if($objItem->gia_tri_con_lai != '')  @endif>

                        </div>
                        <div class="form-group">
{{--                            <div class="col-md-12">--}}
                                <label for="thoi_han_bao_hanh" style="margin: 0">Thời gian bảo hành<span
                                        class="text-danger">(*)</span></label>
{{--                            </div>--}}
{{--                            <div class="col-md-10">--}}
                                <input type="text" name="thoi_han_bao_hanh" id="thoi_han_bao_hanh" class="form-control"
                                       value="@isset($request['thoi_han_bao_hanh'])  {{ $request['thoi_han_bao_hanh'] }} @else {{ $objItem->thoi_han_bao_hanh }} @endisset" @if($objItem->thoi_han_bao_hanh != '')  @endif>
{{--                            </div>--}}
                        </div>
                        <div class="form-group">
                            <label for="nguon" class="col-md-12" style="padding:0">Trạng thái <span
                                    class="text-danger">(*)</span></label>
                            <select name="trang_thai" id="trang_thai"
                                    class="form-control select2 col-md-12" style="width: 100%">
                                <option value="">== Chọn trạng thái ==</option>
                                @foreach($trang_thais as $index => $item)
                                    <option value="{{ $index }}"
                                            @isset($request['trang_thai']) @if($request['trang_thai'] == $index) selected
                                            @endif @else @if($objItem->trang_thai == $index) selected @endif @endisset>
                                        {{ $item }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="nguon" class="col-md-12" style="padding:0">Tình trạng kiểm kê</label>
                            <select name="tinh_trang_kk" id="tinh_trang_kk"
                                    class="form-control select2 col-md-12" style="width: 100%">
                                <option value="">== Chọn trạng thái ==</option>
                                @foreach($check_kiem_ke as $index => $item)
                                    <option value="{{ $index }}" @isset($request['tinh_trang_kk']) @if($request['tinh_trang_kk'] == $index) selected
                                            @endif @else @if($objItem->tinh_trang_kk == $index) selected @endif @endisset>{{ $item }}</option>
                                @endforeach
                            </select>
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
                <a href="{{ route('route_BackEnd_TaiSan_Detail',['id'=>$idTaiSan]) }}" class="btn btn-default">Cancel</a>
            </div>
            <input id="idTaiSanCon" name="idTaiSanCon" value="{{ $idTaiSan }}" type="hidden" >
            <!-- /.box-footer -->
        </form>
        <div class="box box-primary" style="margin-top: 50px">
            <div class="box-header with-border">
                <div class="box-title">
                    Lịch sử sửa chữa
                </div>
                {{--                <span class="pull-right"><i class="fa fa-usd"></i> Đã thu: <strong>{{ formatNumber(approve) }}</strong><sup>đ</sup></span>--}}
            </div>
            <div class="box-body">
                <button v-if="marketing==0" class="btn btn-primary" onclick="addLichSuSuaChua()">Cập nhật lịch sử sửa chữa</button>
                <div class="modal fade" id="historyModal" role="dialog">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                {{--                                <h4 class="modal-title">Thêm mới lần đóng: {{ hoten}}</h4>--}}
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-danger" style="display:none"></div>

                                <form action="{{ route('route_BackEnd_LichSuSuaChua_Add') }}" method="post"  id="preview_form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="nam_su_dung">Ngày sửa chữa<span class="text-danger">(*)</span></label>
                                                <input type="date" class="form-control" name="ngay_sua_chua"
                                                       id="nam_su_dung">
                                            </div>
                                            <div class="form-group">
                                                <label for="chi_phi" class="col-md-12" style="padding:0">Chi phí<span class="text-danger">(*)</span></label>
                                                <input type="text" class="form-control" name="chi_phi" id="chi_phi">
                                            </div>
                                            <div class="form-group">
                                                <label for="nguon_chi">Nguồn chi<span
                                                        class="text-danger">(*)</span></label>
                                                <select name="nguon_chi" id="nguon_chi"  class="form-control select2 col-md-12" style="width: 100%">
                                                    <option value="">Vui lòng lựa chọn nguồn chi</option>
                                                @foreach($nguon_kinh_phi as $index => $item)
                                                        <option value="{{ $index }}"
                                                                @isset($request['nguon_kinh_phi']) @if($request['nguon_kinh_phi'] == $item->id) selected @endif @endisset>
                                                            {{ $item }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group hide">
                                                <label for="id_tai_san_con" class="col-md-12" style="padding:0">Loại Tài Sản Con<span class="text-danger">(*)</span></label>
                                                <input type="text" class="form-control" name="id_tai_san_con" id="id_tai_san_con" value="{{$objItem->id}}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="noi_dung">Nội dung<span class="text-danger">(*)</span></label>
                                                <textarea name="noi_dung" id="noi_dung" cols="30" rows="5" class="form-control"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="id_don_vi" class="col-md-12" style="padding:0">Nguyên nhân<span class="text-danger">(*)</span></label>
                                                <textarea name="nguyen_nhan" id="nguyen_nhan" cols="30" rows="5" class="form-control"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <button class="btn btn-primary" id="saveLichSuSuaChua" type="submit">Lưu lại</button>
                                        <button type="reset" class="btn btn-default">Nhập lại</button>
                                        <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="huyls()">Hủy</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="list_hoa_dons.length>0" class="table-responsive">
                    <table class="table table-bordered" style="margin-top:20px;">
                        <tbody>
                        <tr>
                            <th>#ID</th>
                            <th>Ngày sửa chữa</th>
                            <th>Nội dung</th>
                            <th>Nguyên nhân</th>
                            <th>Chi phí</th>
                            <th>Nguồn chi</th>
                        </tr>
                        @foreach($lists as $key => $item)
                            <tr>

                                <td>{{ $item->id }}</td>
                                <td>{{ $item->ngay_sua_chua }}</td>
                                <td>{{ $item->noi_dung }}</td>
                                <td>{{ $item->nguyen_nhan }}</td>
                                <td>{{ $item->chi_phi }}</td>
                                <td>{{ $item->nguon_chi }}</td>
                            </tr>
                        @endforeach
                        </tbody>

                    </table>
                </div>
{{--                <div v-else class="alert alert-warning" style="margin-top:20px;">Đẹp zai lỗi tại ai :)))))</div>--}}

            </div>
            <br>
            <div class="text-center">
                {{  $lists->appends($extParams)->links() }}
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

