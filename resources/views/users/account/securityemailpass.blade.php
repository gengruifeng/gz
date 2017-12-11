@extends('layouts.layout')

@section('head')
    <title>设置邮箱-工作网</title>
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
                <!--邮箱验证修改开始-->
                <div class="emailCheck">
                    <div><h3>设置邮箱</h3></div>
                    <div>
                        <p>邮件已发送到您的邮箱 : <span>{{ $userData['email'] }}</span></p>
                        <p>请点击邮箱中的验证链接完成验证</p>

                    </div>
                    <div>
                        <a onclick="goMail('{{ $userData['email'] }}')" href="javascript:void(0)">前往邮箱验证</a>
                    </div>
                    <div>
                        <p>没有收到邮件？</p>
                        <p>1. 电子邮件偶尔会有延时状况，请耐心等待</p>
                        <p>2. 尝试到垃圾邮件里找找看</p>
                    </div>
                </div>
                <!--邮箱验证修改结束-->
            </div>
        </div>
    </div>
    <!--个人信息结束-->
@endsection
@section('javascripts')
<script>
	window.onload = function () {
		$(function () {
			mobile ="{{ !empty($userData['mobile'])?$userData['mobile']:'' }}";
		});
	}
</script>

<script src="{{ asset('js/safety.js') }}"></script>
@endsection