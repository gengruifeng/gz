@extends('layouts.layout')

@section('head')
    <title>擅长领域-工作网</title>
@endsection

@section('stylesheets')
<link rel="stylesheet" type="text/css" href="{{ asset('css/personnel.css') }}"/>
@endsection
@section('content')
    <!--个人信息开始-->
    <div id="message">
        <div class="message">
            <div class="message_left">
                <a href="{{ url('account/settings') }}"><span></span><span>基本资料</span></a>
                <a href="{{ url('account/avatar') }}"><span></span><span>我的头像</span></a>
                <a href="{{ url('account/oauth') }}"><span></span><span>绑定设置</span></a>
                    <a href="{{ url('account/safety') }}"><span></span><span>账户安全</span></a>
                <a class="onClick" href="javascript:void(0)"><span></span><span>擅长领域</span></a>
            </div>
            <div class="message_right">
                <!--重新选择擅长领域开始-->
                <div class="newBeGood">
                    <div><h3>擅长领域</h3></div>
                    <div>
                        <ul>
                            @foreach($userTags as $tag)
                                <li>{{ $tag['name'] }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <div><button onclick="tosettabs()">重新选择擅长领域</button></div>
                </div>
                <!--重新选择擅长领域结束-->
            </div>
        </div>
    </div>
    <!--个人信息结束-->
@endsection
@section('javascripts')
<script src="{{ asset('js/usertags.js') }}"></script>
@endsection