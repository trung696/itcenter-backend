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
        <form class="form-horizontal " action="" method="post" >
            @csrf
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        {{--                        <div class="form-group">--}}
                        {{--                            <label for="ma_tai_san" class="col-md-3 col-sm-4 control-label">Mã tài sản <span class="text-danger">(*)</span></label>--}}

                        {{--                            <div class="col-md-9 col-sm-8">--}}
                        {{--                                <input type="text" name="ma_tai_san" id="ma_tai_san" class="form-control" value="@isset($request['ma_tai_san']){{ $request['ma_tai_san'] }}@endisset">--}}
                        {{--                                <span id="mes_sdt"></span>--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}
                        <div class="form-group">
                            <label for="name" class="col-md-3 col-sm-4 control-label">Tên khoá học <span class="text-danger">(*)</span></label>

                            <div class="col-md-9 col-sm-8">
                                <input type="text" name="name" id="ten_khoa_hoc" class="form-control" value="@isset($request['name']){{ $request['name'] }}@endisset">
                                <span id="mes_sdt"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="price" class="col-md-3 col-sm-4 control-label">Giá <span class="text-danger">(*)</span></label>

                            <div class="col-md-9 col-sm-8">
                                <input type="text" name="price" id="ten_khoa_hoc" class="form-control" value="@isset($request['price']){{ $request['price'] }}@endisset">
                                <span id="mes_sdt"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="description" class="col-md-3 col-sm-4 control-label">Thông tin khoá học <span class="text-danger">(*)</span></label>

                            <div class="col-md-12 col-sm-8">
                                <textarea name="description" class="form-control " value="@isset($request['description']){{ $request['description'] }}@endisset"></textarea>
                                <span id="mes_sdt"></span>
                            </div>
                        </div>

                        <h2 class="mt-4">ảnh khóa học</h2>
                        <div class="input-group">
                        <span class="input-group-btn">
                            <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-primary text-white">
                            <i class="fa fa-picture-o"></i> Choose
                            </a>
                        </span>
                        <input id="thumbnail" class="form-control  @error('image') is-invalid @enderror" type="text" name="image" accept="image/*">
                        </div>
                        <div id="holder" style="margin-top:15px;max-height:100px;"></div>

                        {{-- <div class="form-group">
                            <label class="col-md-3 col-sm-4 control-label">Ảnh Khoá Học</label>
                            <div class="col-md-9 col-sm-8">
                                <div class="row">
                                    <div class="col-xs-6">
                                        <img id="hinh_anh_khoa_hoc_preview" src="http://placehold.it/100x100" alt="your image"
                                             style="max-width: 200px; height:100px; margin-bottom: 10px;" class="img-fluid"/>
                                        <input type="file" name="image" accept="image/*"
                                               class="form-control-file  @error('image') is-invalid @enderror" id="hinh_anh_khoa_hoc">
                                    </div>
                                </div>
                            </div>
                        </div> --}}

                        <div class="form-group">
                            <label for="category_id" class="col-md-3 col-sm-4 control-label">Danh Mục Khoá Học</label>
                            <div class="col-md-9 col-sm-8">
                                <select name="category_id" id="id_danh_muc" class="form-control select2" data-placeholder="Chọn danh mục khoá học">
                                    <option value="">== Chọn danh mục khoá học==</option>
                                    @foreach($course_categories as $item)
                                        <option value="{{ $item->id }}" @isset($request['category_id']) @if($request['category_id'] == $item->id) selected @endif @endisset>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
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
                <a href="{{ route('route_BackEnd_Course_List') }}" class="btn btn-default">Cancel</a>
            </div>
            <!-- /.box-footer -->
        </form>

    </section>
@endsection
@section('script')

    <script src="{{ asset('default/plugins/input-mask/jquery.inputmask.js') }}"></script>
    <script src="{{ asset('default/plugins/input-mask/jquery.inputmask.date.extensions.js') }}"></script>
    <script src="{{ asset('js/khoahoc.js') }} "></script>
    <script src="{{ asset('js/add.js') }} "></script>
    <script src="https://cdn.tiny.cloud/1/xht20xn6skuyq83j2zuka7ftxnsw0g9mazxzwbcjfedylq9r/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
     <script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
     <script>
        {!! \File::get(base_path('vendor/unisharp/laravel-filemanager/public/js/stand-alone-button.js')) !!}
      </script>
      <script>
        $('#lfm').filemanager('image', {prefix: route_prefix});
        // $('#lfm').filemanager('file', {prefix: route_prefix});
      </script>
    {{--    <script src="public/default/plugins/input-mask/jquery.inputmask.extensions.js"></script>--}}
    {{--    <script src="public/js/taisan.js"></script>--}}

@endsection

