{{--@if (Auth::check())--}}
{{--    <div>--}}
{{--        Bạn đang đăng nhập với quyền--}}
{{--        @if( Auth::user()->level == 1)--}}
{{--            {{ "SuperAdmin" }}--}}
{{--        @elseif( Auth::user()->level == 2)--}}
{{--            {{ "Admin" }}--}}
{{--        @elseif( Auth::user()->level == 3)--}}
{{--            {{ "Thành viên" }}--}}
{{--        @endif--}}
{{--    </div>--}}
{{--    <div class="pull-right" style="margin-top: 3px;"><a class="btn btn-primary" href="{{ url('/logout') }}">Đăng xuất</a></div>--}}
{{--@endif--}}

@extends('templates.layout')

@section('title', '123')

@section('content')
@endsection


