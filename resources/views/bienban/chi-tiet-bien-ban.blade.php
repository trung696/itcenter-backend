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
              action="{{ route('route_BackEnd_BienBan_Update',['id'=>request()->route('id')]) }}" method="post"
              enctype="multipart/form-data">
            @csrf
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <h3 class="text-center text-success">Bên Giao</h3>
                        <div class="form-group">
                            <label for="ten_tai_san" class="col-md-4 col-sm-5 control-label">Họ tên người giao </label>

                            <div class="col-md-8 col-sm-7">
                                <input type="text" name="ho_ten_nguoi_giao" id="ho_ten_nguoi_giao" class="form-control" value="@isset($request['ho_ten_nguoi_giao'])  {{ $request['ho_ten_nguoi_giao'] }} @else {{ $objItem->ho_ten_nguoi_giao }} @endisset" @if($objItem->ho_ten_nguoi_giao != '')  @endif>
                                <span id="mes_sdt"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="chuc_danh_nguoi_giao" class="col-md-4 col-sm-5 control-label">Chức danh người giao </label>

                            <div class="col-md-8 col-sm-7">
                                <input type="text" name="chuc_danh_nguoi_giao" id="chuc_danh_nguoi_giao" class="form-control" value="@isset($request['chuc_danh_nguoi_giao'])  {{ $request['chuc_danh_nguoi_giao'] }} @else {{ $objItem->chuc_danh_nguoi_giao }} @endisset" @if($objItem->chuc_danh_nguoi_giao != '')  @endif">
                                <span id="mes_sdt"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="bo_phan_nguoi_giao" class="col-md-4 col-sm-5 control-label">Bộ phận người giao </label>

                            <div class="col-md-8 col-sm-7">
                                <input type="text" name="bo_phan_nguoi_giao" id="bo_phan_nguoi_giao" class="form-control" value="@isset($request['bo_phan_nguoi_giao'])  {{ $request['bo_phan_nguoi_giao'] }} @else {{ $objItem->bo_phan_nguoi_giao }} @endisset" @if($objItem->bo_phan_nguoi_giao != '')  @endif">
                                <span id="mes_sdt"></span>
                            </div>
                        </div>
                    </div>
                    {{--                    tab2--}}
                    <div class="col-md-6">
                        <h3 class="text-center text-success">Bên Nhận</h3>
                        <div class="form-group">
                            <label for="ho_ten_nguoi_nhan" class="col-md-4 col-sm-5 control-label">Họ tên người nhận</label>

                            <div class="col-md-8 col-sm-7">
                                <input type="text" name="ho_ten_nguoi_nhan" id="ho_ten_nguoi_nhan" class="form-control" value="@isset($request['ho_ten_nguoi_nhan'])  {{ $request['ho_ten_nguoi_nhan'] }} @else {{ $objItem->ho_ten_nguoi_nhan }} @endisset" @if($objItem->ho_ten_nguoi_nhan != '')  @endif">
                                <span id="mes_sdt"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="chuc_danh_nguoi_nhan" class="col-md-4 col-sm-5 control-label">Chức danh người nhận</label>

                            <div class="col-md-8 col-sm-7">
                                <input type="text" name="chuc_danh_nguoi_nhan" id="chuc_danh_nguoi_nhan" class="form-control" value="@isset($request['chuc_danh_nguoi_nhan'])  {{ $request['chuc_danh_nguoi_nhan'] }} @else {{ $objItem->chuc_danh_nguoi_nhan }} @endisset" @if($objItem->chuc_danh_nguoi_nhan != '')  @endif">
                                <span id="mes_sdt"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="bo_phan_nguoi_nhan" class="col-md-4 col-sm-5 control-label">Bộ phận người nhận </label>

                            <div class="col-md-8 col-sm-7">
                                <input type="text" name="bo_phan_nguoi_nhan" id="bo_phan_nguoi_nhan" class="form-control" value="@isset($request['bo_phan_nguoi_nhan'])  {{ $request['bo_phan_nguoi_nhan'] }} @else {{ $objItem->bo_phan_nguoi_nhan }} @endisset" @if($objItem->bo_phan_nguoi_nhan != '')  @endif">
                                <span id="mes_sdt"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 text-center">
                        <div class="form-group">
                            <label for="id_don_vi" class="col-md-4 col-sm-3 control-label">Đơn Vị <span class="text-danger">(*)</span></label>
                            <div class="col-md-5 col-sm-4">
                                <select name="id_don_vi" id="id_don_vi" class="form-control select2" data-placeholder="Chọn đơn vị">
                                    <option value="">== Chọn đơn vị ==</option>
                                    @foreach($don_vi as $item)
                                        <option value="{{ $item->id }}"
                                                @isset($request['id_don_vi']) @if($request['id_don_vi'] == $item->id) selected
                                                @endif @else @if($objItem->id_don_vi == $item->id) selected @endif @endisset>
                                            {{ $item->id }}.{{ $item->ten_don_vi }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.box-body -->
            <div class="text-center">
                <button type="submit" class="btn btn-primary"> Save</button>
                <a href="{{ route('route_BackEnd_BienBan_index') }}" class="btn btn-default">Cancel</a>
                <a href="{{ route('route_BackEnd_TaiSanCon_InBienBanBanGiao_Update',['id'=>request()->route('id')]) }}" target="_blank" class="btn btn-success">In biên bản</a>

            </div>
            <!-- /.box-footer -->
        </form>

    </section>
@endsection
@section('script')
    <script src="{{ asset('default/plugins/input-mask/jquery.inputmask.js') }}"></script>
    <script src="{{ asset('default/plugins/input-mask/jquery.inputmask.date.extensions.js') }}"></script>


@endsection

