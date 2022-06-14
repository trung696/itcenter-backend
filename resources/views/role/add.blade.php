@extends('templates.layout')
@section('content')
<div class="content-wrapper">
    
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                    @if(session()->has('success'))
                        <div class="alert alert-success">
                        {{ session()->get('success') }}
                        </div>
                    @endif
                <form action="{{route('route_BackEnd_role_store')}}" method="post" style="width:100%">
                    @csrf

                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Thêm vai trò</label>
                            <input type="text" name="name" class="form-control" placeholder="Nhập vai trò">
                        </div>
                        <div class="form-group">
                            <label>Mô tả vai trò</label>
                            <input type="text" name="description" class="form-control" placeholder="Nhập mô tả vai trò">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                        @foreach($listModule as $moduleParent)
                            <div class="card bg-light mb-3 col-md-12">
                                <div class="card-header" style="background-color:cyan">
                                    <label class="">
                                        <input type="checkbox" class="checkbox_wrapper" > Modul {{$moduleParent->name}}
                                    </label>
                                </div>
               
                                <div class="row">
                                    @foreach ($moduleParent->permissionChildrent as $moduleChildren)
                                        <div class="card-body col-md-3">
                                            <h5 class="card-title ">
                                                <label class="">
                                                    <input type="checkbox" name="permission_id[]"
                                                    class="checkbox_children"
                                                    value="{{$moduleChildren->id}}">
                                                    {{$moduleChildren->name}}
                                                </label>
                                            </h5>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                         @endforeach
                        </div>


                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>

        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->

</div>
@endsection
@section('script')
    <script>
        $('.checkbox_wrapper').on('click', function() {
        //$(this) chính là checkbox . tìm thàng cha là card. sau đó tìm đến checkbox_children và thêm thuộc tính prop (prop trả về true hoặc false) và nếu checked thằng cha thì  $(this).prop('checked') là true và sẽ tự thêm checked
        $(this).parents('.card').find('.checkbox_children').prop('checked', $(this).prop('checked'));
        })

    </script>
    <script src="{{ asset('default/plugins/input-mask/jquery.inputmask.js') }}"></script>
    <script src="{{ asset('default/plugins/input-mask/jquery.inputmask.date.extensions.js') }}"></script>
    <script src="{{ asset('js/khoahoc.js') }} "></script>
@endsection

