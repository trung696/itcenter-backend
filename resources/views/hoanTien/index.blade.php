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
    </style>

    <!-- Phần nội dung riêng của action  -->
    <div class="box box-primary" style="margin-top: 50px">
        <div class="box-header with-border">
            <div class="box-title">
                Danh Sách Sinh viên đăng kí còn thừa tiền
            </div>
        </div>
        @if($errors->any())
        <h4>{{$errors->first()}}</h4>
        @endif
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
                            <td> <a href=""> <button>Hoàn tiền</button></td>
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
@endsection