@extends('layouts.layout')

@section('head')
    <title>更改号码-工作网</title>
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
                <a class="onClick" href="{{ url('account/safety') }}"><span></span><span>账户安全</span></a>
                <a href="{{ url('account/proficiency') }}"><span></span><span>擅长领域</span></a>
            </div>
            <div class="message_right">
                <!--更换号码>完成绑定开始-->
                <div class="finishBind">
                    <div><h3>更换号码</h3></div>
                    <div>
                        <a href="javascript:void(0)">
                            <span>1</span>
                            <span>身份验证</span>
                            <span></span>
                        </a>
                        <a href="javascript:void(0)">
                            <span>2</span>
                            <span>修改绑定手机</span>
                            <span></span>
                        </a>
                        <a href="javascript:void(0)">
                            <span></span>
                            <span>完成修改</span>
                        </a>
                    </div>
                    <div>
                        <h3>修改成功</h3>
                        <p>新的手机号为 : <span>{{ $userData['mobile'] }}</span></p>
                        <p>可使用新的手机号进行登录</p>
                        <a href="{{ url('account/safety') }}">返回账号安全</a>
                    </div>
                </div>
                <!--更换号码>完成绑定结束-->
            </div>
        </div>
    </div>
    <!--个人信息结束-->
@endsection

