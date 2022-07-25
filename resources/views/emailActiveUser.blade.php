<h1>đăng kí thành công</h1>
<h2>{{$user->name}}</h2>
<h2>{{$user->email}}</h2>
<h2>{{$user->phone}}</h2>
<h2>{{$user->address}}</h2>
<h2> token {{$user->tokenActive}}</h2>
<br>
<!-- <p> vui lòng   để kích hoạt thg gà </p> -->
<a href="{{route('active.user',['hocVien'=>$user->id ,'token'=>$user->tokenActive])}}}">Nhấn vào đây để kích hoạt tài khoản của bạn</a>
