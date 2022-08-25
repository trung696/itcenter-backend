<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td,
        th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }
    </style>
</head>

<body>
    <p>Trung tâm văn hoá Thanh Trung Academics </p>
    <h1>Hệ thống thông báo bạn đã chuyển lớp thành công</h1>
    @if(isset($classOld) && isset($classNew) && isset($dangKyOld) && $dangKyOld->du_no < 0 )
    <table>
        <tr>
            <th>Lớp cũ</th>
            <th>Lớp mới</th>
            <th>Giá tiền lớp mới</th>
            <th>Số tiền đã đóng</th>
            <th>Số tiền còn thiếu</th>
            <th>Số tiền cần đóng</th>
        </tr>
        <tr>
            <td>{{$classOld->name}}</td>
            <td>{{$classNew->name}}</td>
            <td>{{$dangKyOld->gia_tien}}</td>
            <td>{{$dangKyOld->so_tien_da_dong}}</td>
            <td>{{number_format($dangKyOld->du_no)}} VNĐ</td>
            <td>{{number_format(abs($dangKyOld->du_no))}} VNĐ</td>
        </tr>

    </table>
    @elseif(isset($classOld) && isset($classNew) && isset($dangKyOld) && $dangKyOld->du_no > 0 )
    <table>
        <tr>
            <th>Lớp cũ</th>
            <th>Lớp mới</th>
            <th>Giá tiền lớp mới</th>
            <th>Số tiền đã đóng</th>
            <th>Số tiền thừa</th>
        </tr>
        <tr>
            <td>{{$classOld->name}}</td>
            <td>{{$classNew->name}}</td>
            <td>{{number_format($dangKyOld->gia_tien)}} VNĐ</td>
            <td>{{number_format($dangKyOld->so_tien_da_dong)}} VNĐ</td>
            <td>{{number_format($dangKyOld->du_no)}} VNĐ</td>
        </tr>

    </table>
    <h2>Bạn vui lòng đến trường để nhận lại số tiền thừa</h2>
    @endif



</body>

</html>