@extends('templates.layout')
@section('content')

<section class="content-header">
    <h1 class=" text-info">
        <i class="fa fa-caret-right btn-spx-toggle-toolbar" style="cursor: pointer"></i>
        <small>
            <ol class="breadcrumb">
                <li><a href=""><i class="fa fa-dashboard"></i> Home</a></li>
                    <li><a href=""> Role </a></li>
                    <li lass="active">List</li>
            </ol>
        </small>
    </h1>

    <div class="clearfix"></div>
    <div style="border: 1px solid #ccc;margin-top: 10px;padding: 5px;">
        <form action="" method="get">
            <div class="row">
                <!-- <div class="col-md-3 col-sm-6">
                    <div class="form-group">
                        <input type="text" name="search_ten_danh_muc_khoa_hoc" class="form-control" placeholder="Tên chức vụ" value="@isset($extParams['search_ten_dia_diem']){{$extParams['search_ten_dia_diem']}}@endisset">
                    </div>
                </div> -->
                <div class="clearfix"></div>
                <div class="col-xs-12" style="text-align:center;">
                    <div class="form-group">
                        <!-- <button type="submit" name="btnSearch" class="btn btn-primary btn-sm "><i class="fa fa-search" style="color:white;"></i> Search
                        </button> -->
                        <!-- <a href="" class="btn btn-default btn-sm "><i class="fa fa-remove"></i>
                            Clear </a> -->
                        <a href="{{route('route_BackEnd_role_add')}}" class="btn btn-info btn-sm"><i class="fa fa-user-plus" style="color:white;"></i>
                            Add new</a>
                    </div>
                </div>
            </div>

        </form>
        <div class="clearfix"></div>
    </div>
</section>

<section class="content appTuyenSinh">
    <div id="msg-box">
        <?php //Hiển thị thông báo thành công
        ?>
        @if ( Session::has('successs') )
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
    </div>
    @if(count($listRoles) == 0 )
    <p class="alert alert-warning">
        Không có dữ liệu phù hợp
    </p>
    @endif
    <div class="box-body table-responsive no-padding">
            @if(session()->has('success'))
            <div class="alert alert-success">
            {{ session()->get('success') }}
            </div>
        @endif
        <form action="" method="post">
            @csrf
            <span class="pull-right">Tổng số bản ghi tìm thấy: {{count($listRoles)}} <span style="font-size: 15px;font-weight: bold;"></span></span>
            <div class="clearfix"></div>
            <div class="double-scroll">
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 50px" class="text-center">
                            STT
                        </th>
                        <th class="text-center" width="200px"> Chức vụ</th>
                        <th class="text-center">Mô tả chức vụ</th>
                        <th width="200px" class="text-center">Hành động</th>
                    </tr>
                    @foreach ($listRoles as $role)
                        <tr>  
                            <td class="text-center">{{$role->id}}</td>
                            <td class="text-center">{{$role->name}}</td>
                            <td class="text-center">{{$role->description}}</td>
                            <td class="text-center">
                                <a href="{{route('route_BackEnd_role_edit',['id'=>$role->id])}}" title="Sửa"><i class="fa fa-edit"></i></a>
                                <a class="delete_role" data-url="{{route('route_BackEnd_role_delete',['id'=>$role->id])}}"  title="Xóa"><i class="fas fa-remove"></i></a>
                            </td>
                    </tr>
                    @endforeach 
                </table>
            </div>
        </form>
    </div>
    <br>
    <div class="text-center">
        {!! $listRoles->links() !!}
    </div>
    <index-cs ref="index_cs"></index-cs>
</section>
@endsection
@section('script')
<script>
    function actionDelete(event){
    event.preventDefault();
    // lấy url : http://127.0.0.1:8000/admin/products/delete/16
    let urlRequest = $(this).data('url');
    //khi ấn vào thẻ
    let that = $(this);
    Swal.fire({
        title: 'Bạn có chắc chắn xóa?',
        text: "Bạn sẽ không thể hoàn tác lại điều này!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Đồng ý!'
    }).then((result) => {
    if (result.value) {
        //gọi ajax
        $.ajax({
            //cấu hình của ajax gồm
            type: 'GET',
            url: urlRequest,

            success: function(data){
                console.log(data);
                if(data.code == 200){
                    that.parent().parent().remove();
                    Swal.fire(
                        'Xóa!',
                        'Đã xóa thành công',
                        'success'
                      )
                }
              },
              error: function(){

              }
        })

    }
    })
}

$(function(){
    $(document).on('click','.delete_role',actionDelete);
})
</script>
<script src="{{ asset('default/plugins/input-mask/jquery.inputmask.js') }}"></script>
<script src="{{ asset('default/plugins/input-mask/jquery.inputmask.date.extensions.js') }}"></script>
<!-- <script src="{{ asset('js/khoahoc.js') }} "></script> -->
<script src="{{ asset('js/sweetAlert.js') }} "></script>
@endsection