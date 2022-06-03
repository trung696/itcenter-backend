<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Nhãn tài sản</title>
    <style>
        html,body{
            height:297mm;
            width:210mm;
            margin: auto;
            font-family: DejaVu Sans;
            font-size:14px;
        }
        .border_baby{
            padding: 5px;
            border: 1px solid black;
            border-radius: 5px;
            margin: 15px;
        }

        h3{
            margin: 5px;
        }
        .row:after {
            clear: both;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
<div class='row'>
    @php
        $check=0;

    @endphp
    @foreach($dataNhans->chunk(3) as $index => $nhans )
        @php $check++ @endphp
        @foreach ($nhans as $product)

            <div class='border'>
                <div class='border_baby' style='float: left'>
                    <div>
                        <h3>TEM NHÃN TÀI SẢN</h3>
                    </div>
                    <div>
                        <span>Mã TS :{{  $product->ma_tai_san_con }}</span>
                        <br>
                        <span>Tên tài sản :{{  $product->ten_tai_san }}</span>
                        <br>
                        <span>NSD : {{  $product->nam_su_dung }}</span>

                    </div>
                </div>
            </div>

        @endforeach
        <div style='clear: both'></div>

        @if( $check % 8 == 0 && count($nhans) == 3)
            @php echo '<div class="page-break"></div>'; @endphp
        @endif

    @endforeach

</div>
</body>
