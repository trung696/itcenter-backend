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
    <p>Trung tâm NextDev </p>
    <p>Xin thông báo, bạn đã đăng ký thành công lớp học: <span style="color: red">{{ $emails->name }}</span> thuộc khoá
        học:
        <span style="color: red">{{ $emails->course_name }}</span>
    </p>
    @if ($emails->trang_thai == 3)
        <p>Cảm ơn bạn đã đăng ký và đóng học phí cho khóa học tại NextDev, hãy xác nhận học phí bạn đã đóng là chính xác
        </p>
        <a href="{{ route('route_accept', ['id' => $email->id, 'token' => $email->token]) }}"
            style="display: inline-block; background: rgb(185, 185, 12); color: white; padding: 7px 25px; font-weight: bold">Xác
            nhận thanh
            toán</a>
    @endif
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
                @if ($emails->trang_thai == 0)
                    Chưa Thanh Toán
                @elseif($emails->trang_thai == 3)
                    Đã Thanh Toán
                @endif
            </td>
        </tr>
    </table>
    @if ($emails->trang_thai == 0)
        <p>Xin vui lòng kiểm tra và xác nhận lại thông tin học phí của bạn trước khi khai giảng để đảm bảo thông tin học
            phí của bạn đóng là chính xác </p>
        <p>Vui lòng thanh toán trước khi khai giảng </p>
    @endif

    <p><strong>Nếu có vấn đề về thông tin học phí, khoá học, lịch học vui lòng bạn liên hệ Mr. Bùi Văn Trung -Số
            điện thoại: 0973001430</strong></p>
    <p><strong>Chúc bạn có một khoá học thật tốt</strong></p>
    <p><strong>Bộ phận kế toán Trung tâm NextDev xin trân trọng cảm ơn!</strong></p>
    </table>
</body>

</html>
