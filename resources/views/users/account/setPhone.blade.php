@extends('layouts.layout')

@section('head')
    <title>设置手机号-工作网</title>
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
                <!--设置手机>设置手机号开始-->
                <div class="setPhone">
                    <div><h3>设置手机号</h3></div>
                    <div>
                        <a href="javascript:void(0)">
                            <span></span>
                            <span>设置手机号</span>
                            <span></span>
                        </a>
                        <a href="javascript:void(0)">
                            <span>2</span>
                            <span>完成绑定</span>
                        </a>

                    </div>
                    <div class="setPhone display">
                        <form id = "mobileForm" action="{{ url('ajax/sendsms') }}" onsubmit="return false" autocomplete="off">

                            <div>
                                <input type="text" name="mobile" placeholder="新的手机号码" />

                                <button id = "sendcode" onclick="grfsendSms(this,2)">获取验证码</button>
                                {{ csrf_field() }}
                            </div>
                        </form>
                        <form id = "submobileForm" action="{{ url('ajax/account/bindingmobile') }}" onsubmit="return false" autocomplete="off">

                            <div>
                                <input name="code" type="text" placeholder="请输入验证码" />

                            </div>
                            <div>
                                <a class="setPhonebtn" onclick="subbindingMobiletwo('setmobilefinish')" href="javascript:void(0)">提交</a>
                            </div>
                            {{ csrf_field() }}

                        </form>
                    </div>
                </div>
                <!--设置手机>设置手机号结束-->

                <!--设置手机>完成绑定开始-->
                <div class="setPhoneFinish display">
                    <div><h3>设置手机号</h3></div>
                    <div>
                        <a href="javascript:void(0)">
                            <span>1</span>
                            <span>设置手机号</span>
                            <span></span>
                        </a>
                        <a href="javascript:void(0)">
                            <span></span>
                            <span>完成绑定</span>
                        </a>

                    </div>
                    <div>
                        <h3>修改成功</h3>
                        <p>新的手机号为 : <span class="newp"></span></p>
                        <p>可使用新的手机号进行登录</p>
                        <a href="{{ url('account/safety') }}">返回账号安全</a>
                    </div>
                </div>
                <!--设置手机>完成绑定结束-->
            </div>
        </div>
    </div>
    <!--个人信息结束-->
@endsection
@section('javascripts')
<script>

	window.onload = function () {
		$(function () {
			settime($('#sendcode'));
		});
	}
</script>
<script src="{{ asset('js/safety.js') }}"></script>
@endsection
