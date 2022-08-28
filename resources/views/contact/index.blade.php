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
                Danh Sách gọi điện tư vấn
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
                            <th>Ghi chú</th>
                            <th>Trạng thái</th>

                            <th>Công cụ</th>


                        </tr>
                        @foreach($lisContacts as $key => $item)
                        <tr>

                            <td>{{ $item->id }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->email }}</td>
                            <td>{{ $item->phone }}</td>
                            <td>{{ $item->birthday }}</td>
                            <td>{{ $item->note }}</td>
                            <td>
                                @php if ($item->status == 0){
                                        echo "<p style='color:red;'>" . 'Chưa gọi' . "</p>";
                                    }else{
                                        echo "<p style='color:green;'>" .' Đã gọi' . "</p>";
                                    }
                                @endphp
                            </td>
                            <td> <a href="{{ route('route_BackEnd_contact_check',['id'=>$item->id] ) }}" onclick="return  confirm('Bạn có chắc chắn gọi')"> <button>Gọi</button> </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {!! $lisContacts->links() !!}

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