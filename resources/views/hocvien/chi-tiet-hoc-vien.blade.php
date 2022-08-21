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
            action="{{ route('route_BackEnd_DanhSachHocVien_Update', ['id' => request()->route('id')]) }}" method="post"
            enctype="multipart/form-data">
            @csrf
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="ho_ten" class="col-md-3 col-sm-4 control-label">Họ tên học viên <span
                                    class="text-danger">(*)</span></label>

                            <div class="col-md-9 col-sm-8">
                                <input type="text" name="ho_ten" id="ho_ten" class="form-control"
                                    value="@isset($request['ho_ten']) {{ $request['ho_ten'] }} @else {{ $objItem->ho_ten }} @endisset"
                                    @if ($objItem->ho_ten != '')  @endif>
                                <span id="mes_sdt"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="ngay_sinh" class="col-md-3 col-sm-4 control-label">Ngày sinh <span
                                    class="text-danger">(*)</span></label>
                            <?php
                            $date = date('Y-m-d');
                            $newdate = strtotime('-13 year', strtotime($date));
                            $enddate = strtotime('-80 year', strtotime($date));
                            $maxdate = date('Y-m-d', $newdate);
                            $mindate = date('Y-m-d', $enddate);
                            ?>

                            <div class="col-md-9 col-sm-8">
                                <input type="date" name="ngay_sinh" id="ngay_sinh" class="form-control" min="1980-01-01"
                                    max="{{ $maxdate }}"
                                    value="@isset($request['ngay_sinh']) {{ $request['ngay_sinh'] }} @else{{ $objItem->ngay_sinh }} @endisset"@if ($objItem->ngay_sinh != '')  @endif>
                                <span id="mes_sdt"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="so_dien_thoai" class="col-md-3 col-sm-4 control-label">Số điện thoại <span
                                    class="text-danger">(*)</span></label>

                            <div class="col-md-9 col-sm-8">
                                <input type="text" name="so_dien_thoai" id="so_dien_thoai" class="form-control"
                                    value="@isset($request['so_dien_thoai']) {{ $request['so_dien_thoai'] }} @else {{ $objItem->so_dien_thoai }} @endisset"
                                    @if ($objItem->so_dien_thoai != '')  @endif>
                                <span id="mes_sdt"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-md-3 col-sm-4 control-label">Email <span
                                    class="text-danger">(*)</span></label>

                            <div class="col-md-9 col-sm-8">
                                <input type="text" name="email" id="email" class="form-control"
                                    value="@isset($request['email']) {{ $request['email'] }} @else {{ $objItem->email }} @endisset"
                                    @if ($objItem->email != '')  @endif>
                                <span id="mes_sdt"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="hoc_phi" class="col-md-3 col-sm-4 control-label">Hình ảnh học viên <span
                                    class="text-danger">(*)</span></label>

                            <div class="col-md-9 col-sm-8">
                                <img id="hinh_anh_hoc_vien_preview"
                                    src="{{ $objItem->hinh_anh ? Storage::url($objItem->hinh_anh) : 'http://placehold.it/100x100' }}"
                                    alt="your image" style="max-width: 200px; height:100px; margin-bottom: 10px;"
                                    class="img-fluid" />
                                <input type="file" name="hinh_anh_hoc_vien" accept="image/*"
                                    class="form-control-file @error('hinh_anh_hoc_vien') is-invalid @enderror"
                                    id="hinh_anh_hoc_vien">
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.box-body -->
            <div class="text-center">
                <button type="submit" class="btn btn-primary"> Save</button>
                <a href="{{ route('route_BackEnd_DanhSachHocVien_index') }}" class="btn btn-default">Cancel</a>
            </div>
            <!-- /.box-footer -->
        </form>


        <div class="box box-primary" style="margin-top: 50px">
            <div class="box-header with-border">
                <div class="box-title">
                    Danh Sách Lớp Học Đã Đăng Ký
                </div>
                {{-- <span class="pull-right"><i class="fa fa-usd"></i> Đã thu: <strong>{{ formatNumber(approve) }}</strong><sup>đ</sup></span> --}}
            </div>
            <div class="box-body">
                <div style="border: 1px solid #ccc;margin-top: 10px;padding: 5px;">
                    <form action="" method="get">
                        <div class="row">
                            <div class="col-md-3 col-sm-6">
                                <div class="form-group">
                                    <input type="text" name="search_ten_lop_hoc" class="form-control"
                                        placeholder="Tên lớp học"
                                        value="@isset($extParams['search_ten_lop_hoc']) {{ $extParams['search_ten_lop_hoc'] }} @endisset">
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="form-group">
                                    <input type="text" name="search_ngay_khai_giang"
                                        class="form-control daterangepicker-click" placeholder="Chọn ngày khai giảng"
                                        value="@isset($extParams['search_ngay_khai_giang']) {{ $extParams['search_ngay_khai_giang'] }} @endisset"
                                        autocomplete="off">
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
                            <a href="{{ route('route_BackEnd_DanhSachHocVien_Detail', ['id' => request()->route('id')]) }}"
                                class="btn btn-default btn-sm "><i class="fa fa-remove"></i>
                                Clear </a>
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
                            <th class="text-center">STT</th>
                            <th class="text-center">Tên lớp hoc</th>
                            <th class="text-center">Thời gian khai giảng</th>
                            <th class="text-center">Thời gian kết thúc</th>
                            <th class="text-center">Trạng thái</th>
                        </tr>
                        @php($i = 1)
                        @php($now = date('Y-m-d'))
                        @foreach ($list as $key => $item)
                            <tr>

                                <td class="text-center">{{ $i++ }}</td>
                                <td class="text-center">{{ $item->name }}</td>
                                <td class="text-center">{{ date('d/m/Y', strtotime($item->start_date)) }}</td>
                                <td class="text-center">{{ date('d/m/Y', strtotime($item->end_date)) }}
                                </td>
                                <td class="text-center">
                                    <?php
                                    if($item->start_date < $now){
                                        if ($item->trang_thai == 0){
                                            ?>
                                    Đã Khoá
                                    <?php
                                        }else{
                                            ?>
                                    Đã Thanh Toán
                                    <?php

                                        }
                                    }else{
                                        if($item->trang_thai == 0){
                                            ?>
                                    Chưa Thanh Toán
                                    <?php

                                        }else{
                                            ?>
                                    Đã Thanh Toán
                                    <?php
                                        }
                                    }
                                    ?>
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
            {{ $list->appends($extParams)->links() }}
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


<script src="{{ asset('js/taisan.js') }} "></script>
<script src="{{ asset('js/khoahoc.js') }} "></script>

<script src="{{ asset('js/hocvien.js') }} "></script>
@endsection
