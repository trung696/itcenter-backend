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
    <p>Xin chào học viên, {{$email->ho_ten}}<b></b></p>
    <p>Trung tâm văn hoá Thanh Trung Academics </p>
    <p>Xin thông báo, bạn đã đăng ký thành công lớp học: {{$emails->ten_lop_hoc}} thuộc khoá học: {{$emails->ten_khoa_hoc}}</p>
<table style="width: 100%; border-collapse: collapse">
    <tr style="background-color: #0f81bb;">
        <th style="border: 1px solid #dddddd; text-align: left;padding: 8px">Học phí gốc</th>
        <th style="border: 1px solid #dddddd; text-align: left;padding: 8px">Ưu đãi</th>
        <th style="border: 1px solid #dddddd; text-align: left;padding: 8px">Số tiền phải đóng</th>
        <th style="border: 1px solid #dddddd; text-align: left;padding: 8px">Trạng Thái</th>
    </tr>
    <tr>
        <td style='border: 1px solid #dddddd; text-align: left; padding: 8px'>{{$emails->hoc_phi}}</td>

        <td style='border: 1px solid #dddddd; text-align: left; padding: 8px'>{{$emails->so_dien_thoai}}%</td>
        <td style='border: 1px solid #dddddd; text-align: left; padding: 8px'>{{$emails->gia_tien}}</td>
        <td style='border: 1px solid #dddddd; text-align: left; padding: 8px'> @if($emails->trang_thai == 0)
                Chưa Thanh Toán
            @elseif($emails->trang_thai == 1)
                Đã Thanh Toán
            @endif</td>
    </tr>
</table>
    @if($emails->trang_thai == 0)
        <p>Xin vui lòng kiểm tra và xác nhận lại thông tin học phí của bạn trước khi khai giảng để đảm bảo thông tin học phí của bạn đóng là chính xác </p>
        <p>Vui lòng thanh toán trước khi khai giảng </p>
    @endif

    <p><strong>Nếu có vấn đề về thông tin học phí, khoá học, lịch học vui lòng bạn liên hệ  Mr. Nguyễn Thành Trung -Số điện thoại: 0898555917</strong></p>
    <p><strong>Chúc bạn có một khoá học thật tốt</strong></p>
    <p><strong>Bộ phận kế toán Trung tâm văn hoá Thanh Trung Academics xin trân trọng cảm ơn!</strong></p>
</table>
</body>
</html>