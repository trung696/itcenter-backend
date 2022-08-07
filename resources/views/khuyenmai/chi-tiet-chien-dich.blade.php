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
        <form class="form-horizontal "
            action="{{ route('route_BackEnd_ChienDich_Update', ['id' => request()->route('id')]) }}" method="post"
            enctype="multipart/form-data">
            @csrf
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="ten_chien_dich" class="col-md-3 col-sm-4 control-label">Tên Chiến Dịch <span
                                    class="text-danger">(*)</span></label>

                            <div class="col-md-9 col-sm-8">
                                <input type="text" name="ten_chien_dich" id="ten_chien_dich" class="form-control"
                                    value="@isset($request['ten_chien_dich']) {{ $request['ten_chien_dich'] }} @else {{ $objItem->ten_chien_dich }} @endisset"
                                    @if ($objItem->ten_chien_dich != '')  @endif>
                                <span id="mes_sdt"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="phan_tram_giam" class="col-md-3 col-sm-4 control-label">Phần Trăm Giảm Giá<span
                                    class="text-danger">(*)</span></label>

                            <div class="col-md-9 col-sm-8">
                                <input type="text" name="phan_tram_giam" id="phan_tram_giam" class="form-control"
                                    value="@isset($request['phan_tram_giam']) {{ $request['phan_tram_giam'] }} @else {{ $objItem->phan_tram_giam }} @endisset"
                                    @if ($objItem->phan_tram_giam != '')  @endif>
                                <span id="mes_sdt"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="hoc_phi" class="col-md-3 col-sm-4 control-label">Ngày Bắt Đầu <span
                                    class="text-danger">(*)</span></label>

                            <div class="col-md-9 col-sm-8">
                                <input type="date" name="ngay_bat_dau" id="ngay_bat_dau" class="form-control"
                                    value="@isset($request['ngay_bat_dau']) {{ $request['ngay_bat_dau'] }}@else{{ $objItem->ngay_bat_dau }} @endisset"@if ($objItem->ngay_bat_dau != '')  @endif>
                                <span id="mes_sdt"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="ngay_ket_thuc" class="col-md-3 col-sm-4 control-label">Ngày Kết Thúc <span
                                    class="text-danger">(*)</span></label>

                            <div class="col-md-9 col-sm-8">
                                <input type="date" name="ngay_ket_thuc" id="ngay_ket_thuc" class="form-control"
                                    value="@isset($request['ngay_ket_thuc']) {{ $request['ngay_ket_thuc'] }}@else{{ $objItem->ngay_ket_thuc }} @endisset"@if ($objItem->ngay_ket_thuc != '')  @endif>
                                <span id="mes_sdt"></span>
                            </div>
                        </div>
                    </div>
                    {{-- tab2 --}}
                    <div class="col-md-6">

                    </div>
                </div>

            </div>
            <!-- /.box-body -->
            <div class="text-center">
                <button type="submit" class="btn btn-primary"> Save</button>
                <a href="{{ route('route_BackEnd_ChienDich_index') }}" class="btn btn-default">Cancel</a>
            </div>
            <!-- /.box-footer -->
        </form>


        <div class="box box-primary" style="margin-top: 50px">
            <div class="box-header with-border">
                <div class="box-title">
                    Danh Sách Mã Chiến Dịch {{ $objItem->ten_chien_dich }}
                </div>
                {{-- <span class="pull-right"><i class="fa fa-usd"></i> Đã thu: <strong>{{ formatNumber(approve) }}</strong><sup>đ</sup></span> --}}
            </div>
            <div class="box-body">

                <button v-if="marketing==0" class="btn btn-primary" onclick="addMaChienDich()">Tạo Mã Tự Động</button>
                <div style="border: 1px solid #ccc;margin-top: 10px;padding: 5px;">
                    <form action="" method="get">
                        <div class="row">
                            <div class="col-md-3 col-sm-6">
                                <div class="form-group">
                                    <input type="text" name="search_ma" class="form-control"
                                        placeholder="Nhập mã cần tìm"
                                        value="@isset($extParams['search_ma']) {{ $extParams['search_ma'] }} @endisset">
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="form-group" style="margin-top: 5px">
                                    <select name="trang_thai" id="trang_thai" class="form-control select2"
                                        data-placeholder="Chọn trạng thái">
                                        <option value=""> == Chọn trạng thái ==</option>
                                        @if (count($trang_thai) > 0)
                                            @foreach ($trang_thai as $index => $mh)
                                                <option value="{{ $index }}"
                                                    @isset($extParams['trang_thai']) @if ($extParams['trang_thai'] == $index) selected @endif
                                                @endisset>{{ $mh }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- @endif --}}
                    <div class="clearfix"></div>
                    <div class="col-xs-12" style="text-align:center;">
                        <div class="form-group">
                            <button type="submit" name="btnSearch" class="btn btn-primary btn-sm "><i
                                    class="fa fa-search" style="color:white;"></i> Search
                            </button>
                            <a href="{{ route('route_BackEnd_ChienDich_Detail', ['id' => request()->route('id')]) }}"
                                class="btn btn-default btn-sm "><i class="fa fa-remove"></i>
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
                            <h4 class="modal-title">Tạo Mã Chiến Dịch {{ $objItem->ten_chien_dich }}</h4>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-danger" style="display:none"></div>
                            <form action="" method="post" id="preview_form">
                                @csrf
                                <div class="row">
                                    <div class="form-group">
                                        <label for="so_luong">Số Lượng Mã<span class="text-danger">(*)</span></label>
                                        <input type="text" class="form-control" name="so_luong" id="so_luong"
                                            value="@isset($request['so_luong']) {{ $request['so_luong'] }} @endisset">
                                        <input type="hidden" class="form-control" name="id_chien_dich"
                                            id="id_chien_dich" value="{{ $objItem->id }}">
                                    </div>
                                </div>

                                <div class="text-center">
                                    <button class="btn btn-primary" id="saveMaChienDich" type="submit">Lưu
                                        lại</button>
                                    <button type="reset" class="btn btn-default">Nhập lại</button>
                                    <button type="button" class="btn btn-danger" onclick="huy()">Hủy</button>
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
                            <th class="text-center">STT</th>
                            <th class="text-center">Mã Giảm Giá</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-center">Công cụ</th>
                        </tr>
                        @php($i = 1)
                        @foreach ($lists as $key => $item)
                            <tr>

                                <td class="text-center">{{ $i++ }}</td>
                                <td class="text-center">{{ $item->ma_khuyen_mai }}</td>
                                <td class="text-center">
                                    @if ($item->trang_thai == 0)
                                        Chưa Sử Dụng
                                    @elseif($item->trang_thai == 1)
                                        Đã Sử Dụng
                                    @endif
                                <td class="text-center">
                                    <a href="{{ route('route_BackEnd_MaChienDich_Detail', ['id' => $item->id]) }}"
                                        title="Sửa"><i class="fa fa-edit"></i></a>
                                    <a href="{{ route('route_BackEnd_MaChienDich_Detele', ['id' => $item->id]) }}"
                                        title="Xóa"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
            {{-- <div v-else class="alert alert-warning" style="margin-top:20px;">Đẹp zai lỗi tại ai :)))))</div> --}}

        </div>
        <br>
        <div class="text-center">
            {{ $lists->appends($extParams)->links() }}
        </div>
    </div>

</section>
@endsection
@section('script')
<script src="{{ asset('default/plugins/input-mask/jquery.inputmask.js') }}"></script>
<script src="{{ asset('default/plugins/input-mask/jquery.inputmask.date.extensions.js') }}"></script>
{{-- <script src="public/default/plugins/input-mask/jquery.inputmask.extensions.js"></script> --}}
<script src="{{ asset('default/bower_components/moment/min/moment.min.js') }}"></script>
<script src="{{ asset('default/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>


<script src="{{ asset('js/chiendich.js') }} "></script>


@endsection
