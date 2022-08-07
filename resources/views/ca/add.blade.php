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

    <form class="form-horizontal " action="{{route('route_BackEnd_ca_store')}}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="box-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="ca_hoc" class="col-md-3 col-sm-4 control-label">Tên ca học <span class="text-danger">(*)</span></label>
                        <div class="col-md-9 col-sm-8">
                            <input type="text" name="ca_hoc" @error('ca_hoc') is-invalid @enderror id="ca_hoc" value="{{old('ca_hoc')}}" class="form-control">
                            <span id="mes_sdt"></span>
                        </div>
                    </div>
                    @error('ca_hoc')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

                    <div class="form-group">
                        <label for="trang_thai" class="col-md-3 col-sm-4 control-label">Trạng thái <span class="text-danger">(*)</span></label>
                        <select class="form-control" name="trang_thai">
                            <option value="0">Chưa kích hoạt</option>
                            <option value="1">Kích hoạt</option>

                        </select>
                    </div>
                    @error('trang_thai')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

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
<script src="{{ asset('js/add.js') }} "></script>
<script src="https://cdn.tiny.cloud/1/xht20xn6skuyq83j2zuka7ftxnsw0g9mazxzwbcjfedylq9r/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>

<script>
    $('#lfm').filemanager('image', {
        prefix: route_prefix
    });
    // $('#lfm').filemanager('file', {prefix: route_prefix});
</script>
{{-- <script src="public/default/plugins/input-mask/jquery.inputmask.extensions.js"></script>--}}
{{-- <script src="public/js/taisan.js"></script>--}}

@endsection