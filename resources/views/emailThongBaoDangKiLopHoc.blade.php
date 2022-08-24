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
    @if(isset($payment))
        <h1>Hệ thống thông báo bạn đã đăng ký lớp học và thanh toán tiền</h1>
    @else
        <h1>Hệ thống thông báo bạn đã đăng ký lớp học</h1>
    @endif

    <p>Lớp đăng kí : {{$classDk->name}} </p>
    @if(isset($payment))
        <p>Số tiền đã nộp : {{ number_format($payment->price)}} </p>
        <h1>Vui lòng xác nhận</h1>
    @else
        <h1>Vui lòng thanh toán học phí để có thể tham gia lớp học </h1>
    @endif

    
   
</body>

</html>