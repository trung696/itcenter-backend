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
            margin-top: 30px;
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
            <th style="padding-top:20px ; padding-right:50px">BỘ TÀI NGUYÊN VÀ MÔI TRƯỜNG <br>TRƯỜNG ĐẠI HỌC TÀI NGUYÊN VÀ MÔI TRƯỜNG <br>TP. Hà Nội</th>
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
                BIÊN BẢN THANH LÍ TÀI SẢN
            </th>

        </tr>
        <tr>
            <td>
            </td>
            <td >
                <p style="float: right ; padding: 5px">Ngày in phiếu: {{ date('d/m/Y') }}</p>
            </td>
        </tr>
    </table>
    <div class="main">
        <table border="1" cellpadding="5" cellspacing="0" width="95%">
            <tr>
                <th>STT</th>
                <th>Tên học viên</th>
                <th>Ngày sinh</th>
                <th>Số điện thoại</th>
                <th>Email</th>
                <th>Ngày đăng ký</th>
            </tr>
            <?php
            $i = 1;
            ?>
            @foreach($dataNhans as $key=>$value)
                <tr>
                    <td class="center">{{$i++}}</td>
                    <td class="center">{{ $value->ho_ten }}</td>
                    <td class="center">{{ $value->ngay_sinh }}</td>
                    <td class="center">{{ $value->so_dien_thoai }}</td>
                    <td class="center">{{ $value->email }}</td>
                    <td></td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
</body>
</html>
