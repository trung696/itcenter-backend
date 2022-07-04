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


    <!-- <div class="alert alert-danger alert-dismissible" role="alert">
             <ul>
                    <li>1</li>
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>
            </button>
        </div> -->


    <!-- Phần nội dung riêng của action  -->
    <form class="form-horizontal " action="{{route('route_BackEnd_teacher_update',['id'=>$teacherEdit->id])}}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="box-body">
            <div class="row">
                <div class="col-md-6">
                    <!-- @error('name')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror -->
                    <div class="form-group">
                        <label for="name" class="col-md-3 col-sm-4 control-label">Tên giảng viên <span class="text-danger">(*)</span></label>
                        <div class="col-md-9 col-sm-8">
                            <input type="text" name="name" @error('name') is-invalid @enderror id="name" value="{{$teacherEdit->name}}" class="form-control">
                            <span id="mes_sdt"></span>
                        </div>
                    </div>

                    <!-- @error('email')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror -->
                    <div class="form-group">
                        <label for="email" class="col-md-3 col-sm-4 control-label">Email <span class="text-danger">(*)</span></label>
                        <div class="col-md-9 col-sm-8">
                            <input type="text" name="email" @error('email') is-invalid @enderror id="email" class="form-control" value="{{$teacherEdit->email}}">
                            <span id="mes_sdt"></span>
                        </div>
                    </div>
<!--                    
                    @error('password')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror -->
                    <div class="form-group">
                        <label for="email" class="col-md-3 col-sm-4 control-label">Mật khẩu <span class="text-danger">(*)</span></label>
                        <div class="col-md-9 col-sm-8">
                            <input type="password" name="password" @error('password') is-invalid @enderror id="password" value="{{$teacherEdit->password}}" class="form-control" >
                            <span id="mes_sdt"></span>
                        </div>
                    </div>
                  
                    <!-- @error('address')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror -->
                    <div class="form-group">
                        <label for="address" class="col-md-3 col-sm-4 control-label">Địa chỉ <span class="text-danger">(*)</span></label>
                        <div class="col-md-9 col-sm-8">
                            <input type="text" name="address" @error('address') is-invalid @enderror id="address" class="form-control" value="{{$teacherEdit->address}}">
                            <span id="mes_sdt"></span>
                        </div>
                    </div>

                     <!-- @error('address')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror -->
                    <div class="form-group">
                        <label for="sex" class="col-md-3 col-sm-4 control-label">Giới tính <span class="text-danger">(*)</span></label>
                        <div class="col-md-9 col-sm-8">
                            <select  name="sex" id="sex" class="form-control select2" data-placeholder="Chọn giới tính">
                                <option value="">== Chọn giới tính ==</option>
                                @foreach(config('gioi_tinh.sex') as $key => $valueSex)
                                <option value="{{$key}}">{{$valueSex}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                     <!-- @error('address')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror -->
                    <div class="form-group">
                        <label for="address" class="col-md-3 col-sm-4 control-label">Ảnh <span class="text-danger">(*)</span></label>
                        <div class="col-md-9 col-sm-8">
                            <input type="file" name="image" @error('image') is-invalid @enderror id="image" class="form-control" value="{{old('image')}}">
                            <span id="mes_sdt"></span>
                        </div>
                    </div>
                
                    
                    <!-- @error('phone')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror -->
                    <div class="form-group">
                        <label for="phone" class="col-md-3 col-sm-4 control-label">Số điện thoại <span class="text-danger">(*)</span></label>
                        <div class="col-md-9 col-sm-8">
                            <input type="number" id="phone" name="phone" @error('phone') is-invalid @enderror class="form-control" value="{{$teacherEdit->phone}}">
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
            <a href="{{ route('route_BackEnd_NguoiDung_index') }}" class="btn btn-default">Cancel</a>
        </div>
        <!-- /.box-footer -->
    </form>

</section>
@endsection
@section('script')
<script src="{{ asset('default/plugins/input-mask/jquery.inputmask.js') }}"></script>
<script src="{{ asset('default/plugins/input-mask/jquery.inputmask.date.extensions.js') }}"></script>
{{-- <script src="public/default/plugins/input-mask/jquery.inputmask.extensions.js"></script>--}}
{{-- <script src="public/js/taisan.js"></script>--}}

@endsection
