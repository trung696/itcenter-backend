<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Phiếu kiểm kê tài sản</title>
    <link rel="stylesheet" href="">
    <style>
        html,body{
            height:297mm;
            width:210mm;
            margin: auto;
            font-family: DejaVu Sans;
            font-size:14px;
            padding: 20px;
        }
        #wrapper{
            padding-top: 30px;
        }
        .col1,.col2,.col3{
            text-align: center;
            line-height: 10px;
            font-size: 12px;
        }
        .col4,.col5,.col6{
            text-align: left;
            line-height: 12px;
            font-size: 12px;
        }
        .center{
            text-align: center;
        }
        .main{
            font-size: 12px;
        }
        p{
            margin: 0;
        }
    </style>
</head>
<body>
<div id="wrapper">
    <table class="table1">
        <tr >
            <th style="padding-top:20px ; padding-right:50px">BỘ TÀI NGUYÊN VÀ MÔI TRƯỜNG <br>TRƯỜNG ĐẠI HỌC TÀI NGUYÊN VÀ MÔI TRƯỜNG <br>	TP. HỒ CHÍ MINH</th>
            <th style="padding-top:30px">CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM
                <br>
                Độc Lập - Tự Do - Hạnh Phúc
                <br>
                <hr style="width: 50%">
            </th>
        </tr>
        <tr>
            <th colspan="2" style="padding:10px ; font-size: 20px;">
{{--                <img src="{{ asset('img/img.png') }}" alt="" width="10%">--}}
{{--                <br>--}}
                PHIẾU KIỂM KÊ TÀI SẢN
            </th>

        </tr>
        <tr>
            <td>
                <p><span><b>Mã đơn vị</b></span> : {{ $donVi->ma_don_vi }}</p>
                <p><span><b>Tên đơn vị</b></span> :{{ $donVi->ten_don_vi }}</p>
            </td>
            <td >
                <p style="float: right ; padding: 5px">Ngày in phiếu: {{ date('d/m/Y') }}</p>
            </td>
        </tr>
    </table>
    <div class="main">
        <table border="1" cellpadding="5" cellspacing="0" width="95%">
            <tr>
                <th>Mã tài sản</th>
                <th>Tên tài sản</th>
                <th>Thông số KT</th>
                <th>Nước SX</th>
                <th>Năm SD</th>
                <th>SL</th>
                <th>Giá trị</th>
                <th>Ghi chú</th>
            </tr>
            @foreach($detailTaiSan as $key=>$value)
            <tr>
                <td>{{ $value->ma_tai_san }}</td>
                <td>{{ $value->ten_tai_san }}</td>
                <td>{{ $value->thong_so_ky_thuat }}</td>
                <td>{{ $value->xuat_xu }}</td>
                <td class="center">{{ $value->nam_su_dung }}</td>
                <td class="center">{{ $value->so_luong }}</td>
                <td>{{ $arrKiemKe[$value->tinh_trang_kk] }}</td>
                <td></td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
</body>
</html>
