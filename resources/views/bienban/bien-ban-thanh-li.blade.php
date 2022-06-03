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
        <div class="box box-primary" style="margin-top: 50px">
            <div class="box-header with-border">
                <div class="box-title">
                    Danh Sách Biên Bản Thanh Lí
                </div>
                {{--                <span class="pull-right"><i class="fa fa-usd"></i> Đã thu: <strong>{{ formatNumber(approve) }}</strong><sup>đ</sup></span>--}}
            </div>
            <div class="box-body">
                <div style="border: 1px solid #ccc;margin-top: 10px;padding: 5px;">
                    <form action="" method="get">
                        <div class="row">
                            <div class="col-md-3 col-sm-6">
                                <div class="form-group">
                                    <input type="text" name="search_ma_tai_san_con" class="form-control" placeholder="Mã tài sản con"
                                           value="@isset($extParams['search_ma_tai_san_con']){{$extParams['search_ma_tai_san_con']}}@endisset">
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="form-group">
                                    <input type="text" name="search_nam_su_dung" class="form-control daterangepicker-click" placeholder="Năm sử dụng"
                                           value="@isset($extParams['search_nam_su_dung']){{$extParams['search_nam_su_dung']}}@endisset" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="form-group" style="margin-top: 5px">
                                    <select name="trang_thai" id="trang_thai" class="form-control select2"
                                            data-placeholder="Chọn trạng thái">
                                        <option value=""> == Chọn trạng thái ==</option>
                                        @if(count($trang_thais)>0)
                                            @foreach($trang_thais as $index => $mh)
                                                <option value="{{ $index }}"
                                                        @isset($extParams['trang_thai']) @if($extParams['trang_thai'] == $index) selected @endif @endisset>{{$mh}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="form-group" style="margin-top: 5px">
                                    <select name="id_don_vi" id="id_don_vi"
                                            class="form-control select2" style="width: 100% ;" data-placeholder="Chọn đơn vị sử dụng">
                                        <option value="">Tìm kiếm đơn vị sử dụng</option>
                                        @foreach($don_vi as $item)
                                            <option value="{{ $item->id }}" @isset($extParams['id_don_vi']) @if($extParams['id_don_vi'] == $item->id) selected @endif @endisset>{{ $item->ten_don_vi }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{--                    @endif--}}
                            <div class="clearfix"></div>
                            <div class="col-xs-12" style="text-align:center;">
                                <div class="form-group">
                                    <button type="submit" name="btnSearch" class="btn btn-primary btn-sm "><i
                                            class="fa fa-search" style="color:white;"></i> Search
                                    </button>
                                    <a href="{{ route('route_BackEnd_TaiSanCon_InBienBanThanhLi_Update') }}" target="_blank" class="btn btn-info btn-sm"><i class="fa fa-print" style="color:white;"></i>
                                        In Biên Bản Thanh Lí</a>
{{--                                    <a href="{{ route('route_BackEnd_TaiSan_Detail',['id'=>request()->route('id')]) }}" class="btn btn-default btn-sm "><i class="fa fa-remove"></i>--}}
{{--                                        Clear </a>--}}
                                </div>
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
                            <th>Tên tài sản</th>
                            <th>Mã tài sản con</th>
                            <th>Năm sử dụng</th>
                            <th>Thông số kỹ thuật</th>
                            <th>Xuất xứ</th>
                            <th>Đơn vị sử dụng</th>
                            <th>Nguồn kinh phí</th>
                            <th>Nguyên giá</th>
                            <th>Thời gian khấu hao</th>
                            <th>Giá trị còn lại</th>
                            <th>Thời gian bảo hành</th>
                            <th>Trạng thái</th>
                        </tr>
                        @foreach($lists as $key => $item)
                            <tr>

                                <td>{{ $item->id }}</td>
                                <td>{{ isset($arrTaiSan[$item->id_tai_san]) ? $arrTaiSan[$item->id_tai_san] : "Chưa có tài sản"}}</td>
                                <td>{{ $item->ma_tai_san_con }}</td>
                                <td>{{ $item->nam_su_dung }}</td>
                                <td>{{ $item->thong_so_ky_thuat }}</td>
                                <td>{{ $item->xuat_xu }}</td>
                                <td>{{ isset($arrDonVi[$item->id_don_vi]) ? $arrDonVi[$item->id_don_vi] : "Chưa có đơn vị dùng"}}</td>
                                <td>
                                    @if($item->nguon_kinh_phi == 0)
                                        NSHH
                                    @elseif($item->nguon_kinh_phi == 1)
                                        Dự án
                                    @elseif($item->nguon_kinh_phi == 2)
                                        Biếu Tặng
                                    @elseif($item->nguon_kinh_phi == 3)
                                        Khác
                                @endif
                                <td>{{ $item->nguyen_gia }}</td>
                                <td>{{ $item->thoi_gian_khau_hao }}</td>
                                <td>{{ $item->gia_tri_con_lai }}</td>
                                <td>{{ $item->thoi_han_bao_hanh }} năm</td>
                                <td class="text-center" style="background-color:
                                @if($item->trang_thai == 0)
                                    red
                                @elseif($item->trang_thai == 1)
                                    blue
                                @elseif($item->trang_thai == 2)
                                    orange
                                @elseif($item->trang_thai == 3)
                                    violet
                                @elseif($item->trang_thai == 4)
                                    green
                                @endif;
                                    color: white">
                                    @if($item->trang_thai == 0)
                                        tạm dừng sử dụng
                                    @elseif($item->trang_thai == 1)
                                        thanh lý
                                    @elseif($item->trang_thai == 2)
                                        bảo hành
                                    @elseif($item->trang_thai == 3)
                                        sửa chữa
                                    @elseif($item->trang_thai == 4)
                                        đang sử dụng
                                    @endif
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

