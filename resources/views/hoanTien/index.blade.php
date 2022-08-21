@extends('templates.layout')
@section('content')

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

        .img-container {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid gray;
            border-radius: 15px;
        }

        .img-container>img {
            width: 100%;
            height: 100%;
        }

        .mt-4 {
            margin-top: 1rem !important;
        }
    </style>

    <!-- Phần nội dung riêng của action  -->

    @if (\Session::has('searchs'))
    <div class="alert alert-success">
        <ul>
            <li>{!! \Session::get('searchs') !!}</li>
        </ul>
    </div>
    @endif

    <div class="box box-primary" style="margin-top: 50px">
        <div class="box-header with-border">
            <div class="box-title">
                Danh Sách Sinh viên đăng kí còn thừa tiền
            </div>
        </div>
        @if (\Session::has('msg'))
        <div class="alert alert-success">
            <ul>
                <li>{!! \Session::get('msg') !!}</li>
            </ul>
        </div>
        @endif
        <div class="box-body">
            <!-- <form action="" method="post"> -->
            <!-- @csrf -->
            <div class="clearfix"></div>
            <div style="border: 1px solid #ccc;margin-top: 10px;padding: 5px;">
                <form action="{{route('route_BackEnd_edit_search')}}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-3 col-sm-6">
                            <div class="form-group">
                                <label for="exampleFormControlInput1">Email học viên</label>
                                <input type="email" name='email' class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
                            </div>
                        </div>

                        <div class="col-xs-12" style="text-align:center;">
                            <div class="form-group">
                                <button type="submit" name="btnSearch" class="btn btn-primary btn-sm "><i class="fa fa-search" style="color:white;"></i> Search
                                </button>
                                <a href="{{ url('/danh-sach-hoc-vien') }}" class="btn btn-default btn-sm "><i class="fa fa-remove"></i>
                                    Clear </a>
                            </div>
                        </div>
                    </div>

                </form>
                <div class="clearfix"></div>
            </div>
            <div v-if="list_hoa_dons.length>0" class="table-responsive">

                <table class="table table-bordered" style="margin-top:20px; ">
                    <thead>
                        <tr>
                            <th>#ID</th>
                            <th>Tên Sinh Viên</th>
                            <th>Email</th>
                            <th>Số điện thoại</th>
                            <th>Ngày sinh</th>
                            <th>Giới tính</th>
                            <th>Địa chỉ</th>
                            <th>Trạng thái</th>
                            <th>Số tiền thừa</th>
                            <th>Công cụ</th>
                        </tr>
                    </thead>

                    <tbody id="myTable">

                        @foreach($listDangKyThuaTienKhiChuyenLop as $listClassDaKhaiGiangIteam)

                        <div class="modal fade" id="exampleModal-{{ $listClassDaKhaiGiangIteam->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Tải lên tài liệu - {{$listClassDaKhaiGiangIteam->id}} </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="" method="get">
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <input type="file" name="file_upload" id="" onchange="readURL(this);">
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="img-container">
                                                        <img id="blah" class="" src="data:image/png;base64,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" alt="..." />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button onclick="clearInputFile()" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                        <tr>
                            <td>{{$listClassDaKhaiGiangIteam->id}}</td>
                            <td>
                                @php
                                foreach($listHocVien as $listHocVienItem){
                                if($listClassDaKhaiGiangIteam->id_hoc_vien == $listHocVienItem->id){
                                echo $listHocVienItem->ho_ten;
                                }
                                }
                                @endphp
                            </td>
                            <td> @php
                                foreach($listHocVien as $listHocVienItem){
                                if($listClassDaKhaiGiangIteam->id_hoc_vien == $listHocVienItem->id){
                                echo $listHocVienItem->email;
                                }
                                }
                                @endphp</td>
                            <td> @php
                                foreach($listHocVien as $listHocVienItem){
                                if($listClassDaKhaiGiangIteam->id_hoc_vien == $listHocVienItem->id){
                                echo $listHocVienItem->so_dien_thoai;
                                }
                                }
                                @endphp</td>
                            <td> @php
                                foreach($listHocVien as $listHocVienItem){
                                if($listClassDaKhaiGiangIteam->id_hoc_vien == $listHocVienItem->id){
                                echo $listHocVienItem->ngay_sinh;
                                }
                                }
                                @endphp</td>
                            <td> @php
                                foreach($listHocVien as $listHocVienItem){
                                if($listClassDaKhaiGiangIteam->id_hoc_vien == $listHocVienItem->id){
                                echo $listHocVienItem->gioi_tinh;
                                }
                                }
                                @endphp</td>
                            <td> @php
                                foreach($listHocVien as $listHocVienItem){
                                if($listClassDaKhaiGiangIteam->id_hoc_vien == $listHocVienItem->id){
                                echo $listHocVienItem->hinh_anh;
                                }
                                }
                                @endphp</td>
                            <td> Chưa trả </td>
                            <td>{{number_format($listClassDaKhaiGiangIteam->du_no)}} VNĐ </td>
                            <td> <a href="{{route('route_BackEnd_edit_thua_tien_hoan_tien',['id'=>$listClassDaKhaiGiangIteam->id])}}"> <button>Hoàn tiền</button></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- </form>     -->
        </div>
        <br>
        <div class="text-center">
        </div>
    </div>

    <div class="box box-primary" style="margin-top: 50px">
        <div class="box-header with-border">
            <div class="box-title">
                Danh Sách Hoàn tiền sinh viên khi không đóng đủ học phí
            </div>
        </div>

        <div class="box-body">
            <!-- <form action="" method="post"> -->
            <!-- @csrf -->
            <div class="clearfix"></div>
            <div v-if="list_hoa_dons.length>0" class="table-responsive">

                <table class="table table-bordered" style="margin-top:20px;">
                    <tbody>
                        <tr>
                            <th>#ID</th>
                            <th>Tên Sinh Viên</th>
                            <th>Email</th>
                            <th>Số điện thoại</th>
                            <th>Ngày sinh</th>
                            <th>Giới tính</th>
                            <th>Địa chỉ</th>
                            <th>Trạng thái</th>
                            <th>Số tiền thừa</th>
                            <th>Công cụ</th>
                        </tr>
                        @foreach($listDangKyThuaTien as $listDangKyThuaTienItem)

                        <div class="modal fade" id="notPayModal-{{ $listDangKyThuaTienItem->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Tải lên tài liệu - {{$listDangKyThuaTienItem->id }} </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="" method="get">
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <input type="file" name="file_upload" id="" onchange="readURL(this);">
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="img-container">
                                                        <img id="blah" class="" src="data:image/png;base64,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" alt="..." />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button onclick="clearInputFile()" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                        <tr>
                            <td>{{$listDangKyThuaTienItem->id}}</td>
                            <td>
                                @php
                                foreach($listHocVien as $listHocVienItem){
                                if($listDangKyThuaTienItem->id_hoc_vien == $listHocVienItem->id){
                                echo $listHocVienItem->ho_ten;
                                }
                                }
                                @endphp
                            </td>
                            <td> @php
                                foreach($listHocVien as $listHocVienItem){
                                if($listDangKyThuaTienItem->id_hoc_vien == $listHocVienItem->id){
                                echo $listHocVienItem->email;
                                }
                                }
                                @endphp</td>
                            <td> @php
                                foreach($listHocVien as $listHocVienItem){
                                if($listDangKyThuaTienItem->id_hoc_vien == $listHocVienItem->id){
                                echo $listHocVienItem->so_dien_thoai;
                                }
                                }
                                @endphp</td>
                            <td> @php
                                foreach($listHocVien as $listHocVienItem){
                                if($listDangKyThuaTienItem->id_hoc_vien == $listHocVienItem->id){
                                echo $listHocVienItem->ngay_sinh;
                                }
                                }
                                @endphp</td>
                            <td> @php
                                foreach($listHocVien as $listHocVienItem){
                                if($listDangKyThuaTienItem->id_hoc_vien == $listHocVienItem->id){
                                echo $listHocVienItem->gioi_tinh;
                                }
                                }
                                @endphp</td>
                            <td> @php
                                foreach($listHocVien as $listHocVienItem){
                                if($listDangKyThuaTienItem->id_hoc_vien == $listHocVienItem->id){
                                echo $listHocVienItem->hinh_anh;
                                }
                                }
                                @endphp</td>
                            <td> Chưa trả </td>
                            <td>{{number_format($listDangKyThuaTienItem->du_no)}} VNĐ </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Hoàn tiền
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <button class="btn btn-primary" data-toggle="modal" data-target="#notPayModal-{{ $listDangKyThuaTienItem->id }}">
                                            Hoàn tiền thủ công
                                        </button>
                                        <button class="btn btn-primary mt-4">
                                            Hoàn tiền tự động
                                        </button>
                                    </div>
                                </div>

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- </form>     -->
        </div>
        <br>
        <div class="text-center">
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
<script src="{{ asset('/js/uploadFileWithModal.js') }}"></script>
@endsection