<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <p>Xin chào học viên, {{ $email->ho_ten }}<b></b></p>
    <p>Thay mặt trung tâm NextDev </p>
    <p>Cảm ơn bạn đã hoàn tất thủ tục đăng kí lớp học: <span style="color: red">{{ $emails->name }}</span> thuộc khoá
        học:
        <span style="color: red">{{ $emails->course_name }}</span>
    </p>

    <table style="width: 100%; border-collapse: collapse">
        <tr style="background-color: #0f81bb;">
            <th style="border: 1px solid #dddddd; text-align: left;padding: 8px">Học phí gốc</th>
            <th style="border: 1px solid #dddddd; text-align: left;padding: 8px">Ưu đãi</th>
            <th style="border: 1px solid #dddddd; text-align: left;padding: 8px">Học phí sau khi giảm giá</th>
            <th style="border: 1px solid #dddddd; text-align: left;padding: 8px">
                @if ($emails->du_no <= 0)
                    Số tiền phải đóng
                @else
                    Số dư còn lại
                @endif
            </th>
            <th style="border: 1px solid #dddddd; text-align: left;padding: 8px">Trạng Thái</th>
        </tr>
        <tr>
            <td style='border: 1px solid #dddddd; text-align: left; padding: 8px'>{{ $emails->price }}</td>
            <td style='border: 1px solid #dddddd; text-align: left; padding: 8px'>{{ $emails->so_dien_thoai }}%</td>
            <td style='border: 1px solid #dddddd; text-align: left; padding: 8px'>{{ $emails->gia_tien }}</td>
            <td style='border: 1px solid #dddddd; text-align: left; padding: 8px'>
                @if ($emails->trang_thai == 0)
                    {{ $emails->gia_tien }}
                @else
                    {{ $emails->du_no }}
                @endif
            </td>
            <td style='border: 1px solid #dddddd; text-align: left; padding: 8px'>
                Đã hoàn tất thủ tục thanh toán
            </td>
        </tr>
    </table>
    <p><strong>Dưới đây là hóa đơn của bạn</strong></p>
    <p><strong>Chúc bạn có một khoá học thật tốt</strong></p>
    <p><strong>Bộ phận kế toán Trung tâm NextDev xin trân trọng cảm ơn!</strong></p>
    </table>
</body>

</html>
