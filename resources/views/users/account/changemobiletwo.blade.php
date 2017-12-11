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
                <!--更换号码>修改绑定手机开始-->
                <div class="trimPhone">
                    <div><h3>设置手机号</h3></div>
                    <div>
                        <a href="javascript:void(0)">
                            <span></span>
                            <span>身份验证</span>
                            <span></span>
                        </a>
                        <a href="javascript:void(0)">
                            <span></span>
                            <span>修改绑定手机</span>
                            <span></span>
                        </a>
                        <a href="javascript:void(0)">
                            <span>3</span>
                            <span>完成修改</span>
                        </a>
                    </div>
                    <div>
                        <form id = "mobileForm" action="{{ url('ajax/sendsms') }}" onsubmit="return false" autocomplete="off">

                            <div>
                                <input name="mobile" type="text" placeholder="新的手机号码" />
                                <button onclick="grfsendSms(this,2)" id = "resendcode">获取验证码</button>
                            </div>
                            {{ csrf_field() }}
                        </form>
                        <form id = "submobileForm" action="{{ url('ajax/account/bindingmobile') }}" onsubmit="return false" autocomplete="off">

                            <div>
                                <input name="code" type="text" placeholder="请输入验证码" />
                            </div>
                            <div>
                                <a class="changePhone_subbtn" onclick="subbindingMobile('changemobilethree')" href="javascript:void(0)">提交</a>
                            </div>
                            {{ csrf_field() }}
                        </form>
                    </div>
                </div>
                <!--更换号码>修改绑定手机结束-->
            </div>
        </div>
    </div>
    <!--个人信息结束-->
@endsection
@section('javascripts')
<script>

	window.onload = function () {
		$(function () {
			settime($('#resendcode'));
		});
	}
</script>
<script src="{{ asset('js/safety.js') }}"></script>
@endsection
