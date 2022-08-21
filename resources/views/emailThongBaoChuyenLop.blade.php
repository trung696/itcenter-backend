<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <p>Trung tâm văn hoá Thanh Trung Academics </p>
    <h1>Hệ thống thông báo chuyển lớp thành công cho bạn</h1>
    <p>thông tin dki </p>

    <!-- {{$classOfChuyenLop}}
    <hr>
    {{$classOfChuyenLop->class}} -->
    <h2>Tên lớp học</h2>
    <h3>
    {{$classOfChuyenLop->name}}

    </h3>
    @foreach ($ca as $caItem)
        @if($caItem->id == $classOfChuyenLop->id_ca)
        {{$caItem->ca_hoc}}
        @endif
    @endforeach

   
</body>

</html>