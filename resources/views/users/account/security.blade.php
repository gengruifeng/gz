@extends('layouts.layout')

@section('head')
    <title>账户安全-工作网</title>
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
                <a class="onClick" href="javascript:void(0)"><span></span><span>账户安全</span></a>
                <a href="{{ url('account/proficiency') }}"><span></span><span>擅长领域</span></a>
            </div>
            <div class="message_right">
                <!--账户安全开始-->
                <div class="security">
                    <div class="security-first">
                        <h3>账号安全</h3>
                    </div>
                    <div class="security-second">
                        <div class="security-img"></div>
                        <div class="security-second-first">
                            <p>账号密码</p>
                            <p>用于保护账号信息和登录安全</p>
                        </div>
                        <div class="security-second-second">
                            <form id = "mobileForm" action="{{ url('ajax/sendsms') }}" onsubmit="return false">
                                <a id="mobileCheck" href="javascript:void(0)" onclick="checkMobile()">手机验证修改</a>
                                {{ csrf_field() }}
                            </form>

                            <form id = "mailForm" action="{{ url('ajax/sendmail') }}" onsubmit="return false">
                                <a id="emailCheck" href="javascript:void(0)" onclick="checkEmail()">邮箱验证修改</a>
                                {{ csrf_field() }}
                            </form>
                        </div>
                    </div>
                    <div class="security-third">
                        <div class="security-img"></div>
                        <div class="security-third-first">
                            <p>绑定邮箱 :
                                    @if (!empty($userData['email']))
                                        {{ $userData['email'] }}<span></span>
                                    @else
                                        <a href="{{ url('account/setemailone') }}">设置邮箱</a>
                                    @endif
                                    </p>
                            <p>安全邮箱用于登录，重置密码或其他安全验证</p>
                        </div>
                    </div>
                    <div class="security-fourth">
                        <div class="security-img"></div>
                        <div class="security-fourth-first">
                            <p>绑定手机 :
                                    @if (!empty($userData['mobile']))
                                        {{ $userData['mobile'] }}
                                        <span></span>
                                    @else
                                        <a href="{{ url('account/setmobile') }}">设置手机</a>
                                    @endif
                                </p>
                            <p>安全邮箱用于登录，重置密码或其他安全验证</p>
                        </div>
                        <div class="security-fourth-second">
                            @if (!empty($userData['mobile']))
                                <form id = "changemobileForm" action="{{ url('ajax/sendsms') }}" onsubmit="return false">
                                    <a href="javascript:void(0)" onclick="changemobileonecode()">更换号码</a>
                                    {{ csrf_field() }}
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
                <!--账户安全结束-->
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
			email ="{{ !empty($userData['email'])?$userData['email']:'' }}";
		});
	}
</script>

<script src="{{ asset('js/safety.js') }}"></script>
@endsection