<body>
    <h2>Thông tin lớp cũ</h2>
    <p>{{$classOld->name}}</p>  
    <p>Giá tiền lớp cũ {{$dangKyOld->so_tien_da_dong}}</p>
    <h2>Thông tin lớp mới</h2>
    <p> {{$classNews->name}}</p>
    <p>Giá tiền lớp mới {{$dangKyOld->gia_tien}} </p>
    <h1>Do giá tiền của lớp bạn chuyển sang cao hơn lớp học bạn đã đăng kí trước kia vui lòng đóng thêm số tiền {{abs($dangKyOld->du_no)}} trước ngày {{$classNews->start_date}} </h1>\
    <h2> Có thể đóng trực tiếp tại trường hoặc </h2>
</body>