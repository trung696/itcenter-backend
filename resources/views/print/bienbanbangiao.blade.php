<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biên Bản Bàn Giao</title>
</head>
<style>
    html,body{
        height:297mm;
        width:210mm;
        margin: auto;
        font-family: DejaVu Sans;
        font-size:14px;
    }
    p{
        line-height: 0.5;
        font-size: 14px;
    }
    b{
        line-height: 0.5;
        font-size: 14px;
    }
    i{
        line-height: 0.5;
        font-size: 14px;
    }
    .table1{
        font-size: 14px;
    }
    .table2{
        font-size: 14px;
    }
    .table3{
        font-size: 14px;
    }
    .table2 tr td{
        padding: 5px 15px;
    }
    .table3 tr th{
        padding: 5px 42px;
    }
    .container{
        width: 100%;
        padding-right: 60px;
        padding-left: 60px;
        margin-right: auto;
        margin-left: auto;
    }
</style>
<body class="container">
<table class="table1">
    <tr >
        <th style="padding-top:20px ; padding-right:50px"><u>TRƯỜNG ĐẠI HỌC <br> TÀI NGUYÊN VÀ MÔI TRƯỜNG</u></th>
        <th style="padding-top:30px">CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM
            <br>
            Độc Lập - Tự Do - Hạnh Phúc
            <br>
            <hr style="width: 50%">
        </th>
    </tr>
    <tr>
        <td  style="text-align: center;">Số: {{ $bienbanDetail->id }}/BBBG</td>
        <th></th>
    </tr>
    <tr>
        <td></td>
        <td style="text-align: center;"><i>................., ngày.......tháng.......năm.......</i></td>
    </tr>
    <tr>
        <th  colspan="2" style="padding:10px ; font-size: 20px;">BIÊN BẢN BÀN GIAO TÀI SẢN, CÔNG CỤ</th>
    </tr>
</table>
<p>Hôm nay, ngày.......tháng.......năm......., Chúng tôi gồm:</p>
<b style="line-height: 0.5;">I.&nbsp;&nbsp; &nbsp; Bên giao</b>
<p>&nbsp; &nbsp;Ông/bà: {{ $bienbanDetail->ho_ten_nguoi_giao ? $bienbanDetail->ho_ten_nguoi_giao : "................................................................................................................." }} </p>
<p>&nbsp; &nbsp;Chức danh:{{ $bienbanDetail->chuc_danh_nguoi_giao ? $bienbanDetail->chuc_danh_nguoi_giao :"....................................................." }} Phòng/Bộ phận: {{ $bienbanDetail->bo_phan_nguoi_giao ? $bienbanDetail->bo_phan_nguoi_giao : '....................................................' }}</p>
<b style="line-height: 0.5;">II.&nbsp; &nbsp; Bên nhận</b>
<p>&nbsp; &nbsp;Ông/bà: {{ $bienbanDetail->ho_ten_nguoi_nhan ? $bienbanDetail->ho_ten_nguoi_nhan : "................................................................................................................." }} </p>
<p>&nbsp; &nbsp;Chức danh:{{ $bienbanDetail->chuc_danh_nguoi_nhan ? $bienbanDetail->chuc_danh_nguoi_nhan :"....................................................." }} Phòng/Bộ phận: {{ $bienbanDetail->bo_phan_nguoi_nhan ? $bienbanDetail->bo_phan_nguoi_nhan : '....................................................' }}</p>
<p>Cùng tiến hành bàn giao sản phẩm, công cụ với nội dung như sau:</p>
<table class="table2" border="1" style="border-collapse: collapse">
    <tr style="text-align: center;">
        <td>STT</td>
        <td>Mã công cụ, tài sản</td>
        <td>Tên công cụ, tài sản</td>
        <td>Số lượng</td>
        <td>Đơn vị</td>
        <td>Tình trạng</td>
    </tr>
    @php
    $index = 1;
    @endphp
    @foreach($detailTaiSan as $key=>$value)
    <tr style="text-align: center;">
        <td>{{ $index }}</td>
        <td>{{ $value->ma_tai_san }}</td>
        <td>{{ $value->ten_tai_san }}</td>
        <td>{{ $value->so_luong }}</td>
        <td>{{ $arrDonVi[$value->id_don_vi] }}</td>
        <td>{{ $arrTrangThaiKK[$value->tinh_trang_kk] }}</td>

    </tr>
        @php
            $index ++;
        @endphp
    @endforeach
</table>
<p>Người bàn giao cam đoan rằng toàn bộ các tài sản, công cụ đã được bàn giao đầy đủ.</p>
<p>Biên bản được lập thành ... bản , mỗi bên giữ 01 bản</p>
<table class="table3">
    <tr>
        <th>Người bàn giao</th>
        <th>Người nhận bàn giao</th>
        <th>Bộ phận HC-NS</th>
    </tr>
</table>
</body>
</html>
