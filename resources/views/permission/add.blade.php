@extends('templates.layout')
@section('content')


    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-6">
                    @if(session()->has('success'))
                        <div class="alert alert-success">
                        {{ session()->get('success') }}
                        </div>
                    @endif
                    <form action="{{route('route_BackEnd_permission_store')}}" method="post">
                        @csrf
                        <div class="form-group">
                            <label>Chọn phân quyền cha</label>
                            <select class="form-control" name="table_module">
                                <option value="0">Chọn phân quyền cha</option>
                                @foreach (config('permission.table_module') as $per)
                                <option value="{{$per}}">{{$per}}</option>
                                @endforeach
                            </select>
                        
                        </div>
                        <div class="form-group">
                            <div class="row">
                                @foreach(config('permission.module_children') as $moduleChildrenItem)
                                    <div class="col-md-3">
                                        <h5 class="card-title ">
                                            <label class="">
                                                <input type="checkbox" name="module_children[]"  class="checkbox_children" value="{{$moduleChildrenItem}}">
                                                {{$moduleChildrenItem}}
                                            </label>
                                        </h5>
                                    </div>
                                @endforeach

                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>

            </div>

        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->

</div>
@endsection
@section('script')
    <script src="{{ asset('default/plugins/input-mask/jquery.inputmask.js') }}"></script>
    <script src="{{ asset('default/plugins/input-mask/jquery.inputmask.date.extensions.js') }}"></script>
    <script src="{{ asset('js/khoahoc.js') }} "></script>
@endsection

