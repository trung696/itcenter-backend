@extends('templates.layout')
@section('title', '1233')
@section('css')
    <style>
        body {
            /*-webkit-touch-callout: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            -o-user-select: none;*/
            user-select: none;
        }

        .toolbar-box form .btn {
            /*margin-top: -3px!important;*/
        }

        .select2-container {
            margin-top: 0;
        }

        .select2-container--default .select2-selection--multiple {
            border-radius: 0;
        }

        .select2-container .select2-selection--multiple {
            min-height: 30px;
        }

        .select2-container .select2-search--inline .select2-search__field {
            margin-top: 3px;
        }

        .table > tbody > tr.success > td {
            background-color: #009688;
            color: white !important;
        }

        .table > tbody > tr.success > td span {
            color: white !important;
        }

        .table > tbody > tr.success > td span.button__csentity {
            color: #333 !important;
        }

        /*.table>tbody>tr.success>td i{*/
        /*    color: white !important;*/
        /*}*/
        .text-silver {
            color: #f4f4f4;
        }

        .btn-silver {
            background-color: #f4f4f4;
            color: #333;
        }

        .select2-container--default .select2-results__group {
            background-color: #eeeeee;
        }
    </style>
@endsection
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
                @include('templates.header-action')
        <div class="clearfix"></div>
        <div style="border: 1px solid #ccc;margin-top: 10px;padding: 5px;">
            <form action="" method="get">
                <div class="row">
                    <div class="col-md-3 col-sm-6">
                        <div class="form-group">
                            <!-- <input type="text" name="search_ten_nguoi_dung" class="form-control" placeholder="Tên người dùng"
                                   value="@isset($extParams['search_ten_nguoi_dung']){{$extParams['search_ten_nguoi_dung']}}@endisset"> -->
                            <input type="text" name="search_ten_nguoi_dung" class="form-control" placeholder="Tên người dùng"
                                   value="">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-xs-12" style="text-align:center;">
                        <div class="form-group">
                            <button type="submit" name="btnSearch" class="btn btn-primary btn-sm "><i
                                    class="fa fa-search" style="color:white;"></i> Search
                            </button>
                            <a href="{{ url('/user') }}" class="btn btn-default btn-sm "><i class="fa fa-remove"></i>
                                Clear </a>
                            <a href="{{route('route_BackEnd_user_add')}}" class="btn btn-info btn-sm"><i class="fa fa-user-plus" style="color:white;"></i>
                                Add new</a>

                        </div>
                    </div>
                </div>

            </form>
            <div class="clearfix"></div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content appTuyenSinh">
        <div id="msg-box">
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
        </div>
        
        <div class="box-body table-responsive no-padding">
            <form action="{{route('route_BackEnd_user_delete_checkbox')}}" method="get">
                @csrf
                <span class="pull-right">Tổng số bản ghi tìm thấy: <span
                        style="font-size: 15px;font-weight: bold;"></span></span>
                <div class="clearfix"></div>
                <div class="double-scroll">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 50px" class="text-center">
                                #ID
                            </th>
                            <th class="text-center">Tên người dùng</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Địa chỉ</th>
                            <th class="text-center">Số điện thoại</th>
                            <th class="text-center">Quyền</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-center">Hành động</th>

                        </tr>

                        @foreach($listUser as  $userItem)
                            @php 
                             $roleOfUser = $userItem->roles
                            @endphp
                            <tr>
                                <td><input type="checkbox" name="idUser[]" class="chk_hv" id="" value="{{$userItem->id}}"> </td>
                                <td class="text-center"><a style="color:#333333;font-weight: bold;" href="{{ route('route_BackEnd_NguoiDung_Detail',['id'=> $userItem->id ]) }}" style="white-space:unset;text-align: justify;"> {{$userItem->name}} <i class="fa fa-edit"></i></a></td>
                                <td class="text-center">{{$userItem->email}}</td>
                                <td class="text-center">{{$userItem->address}}</td>
                                <td class="text-center">{{$userItem->phone}}</td>
                                <td class="text-center">
                                    @foreach($roleOfUser as $role)
                                     {{$role->name}} @if(count($roleOfUser) > 1)  - @endif    
                                    @endforeach
                                </td>
                                <td width="50px" class="text-center" style="background-color:
                                @if($userItem->status == 0)
                                    red
                                @else
                                    green
                                @endif;
                                    color: white">
                                    @if($userItem->status == 0)
                                       Chưa kích hoạt
                                    @else
                                        Kích hoạt
                                    @endif

                                </td>
                                <td class="text-center">
                                    <a href="{{route('route_BackEnd_user_edit',['id'=>$userItem->id])}}" title="Sửa"><i class="fa fa-edit"></i></a>
                                    <a class="delete_user" data-url="{{route('route_BackEnd_user_delete',['id'=>$userItem->id])}}" title="Xoa"><i class="fas fa-remove"></i></a>
                                </td>

                            </tr>
                        @endforeach

                    </table>
                </div>
                <input  class="btn btn-danger btn-sm" placeholder="Xoas" type=submit> 
            </form>
        </div>
        <br>
        <div class="text-center">
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
    $(document).on('click','.delete_user',actionDelete);
})
</script>
<script src="{{ asset('js/sweetAlert.js') }} "></script>
@endsection


