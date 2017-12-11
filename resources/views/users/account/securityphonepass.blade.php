@extends('layouts.layout')

@section('head')
    <title>修改密码-工作网</title>
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
                <!--手机验证修改开始-->
<!--                 <div class="phoneCheck">
                    <div><h3>修改密码</h3></div>
                    <div>
                        <form id="form" action="{{ url('ajax/mobilepass') }}" method="post" onsubmit="return false">
                            <div>
                                <p>短信验证码已下发，请注意查收</p>
                            </div>
                            <div>
                                <input type="password" name="password" placeholder="您的密码" />
                            </div>
                            <div>
                                <input type="password" name="password_confirmation" placeholder="确认密码" />
                            </div>
                            <div>
                                <input type="text" name="code" placeholder="短信验证码" />
                            </div>
                            <div>
                                <a onclick="mobilePass()" class="phoneCheckFinish" href="javascript:void(0)">完成</a>
                            </div>
                            {{ csrf_field() }}
                        </form>
                    </div>
                </div>  -->
                <!-- <div class="phoneCheck"> -->
                    <div class="inner">
                    <h3>修改密码</h3>
                    <p class="tips">短信验证码已下发，请注意查收。</p>
                    
                        <form id="mobileChange-form" action="{{ url('ajax/mobilepass') }}" method="post" onsubmit="return false">
                            <div class="vlogin loginOther">
                                    <!-- 信息相关 开始 -->
                                    <div class="tp">
                                        <div class="testwap">
                                            <div class="inp inpPassword">
                                                <input type="password" id="password" name="password" placeholder="新密码">
                                            </div>
                                        </div>
                                        <div class="testwap">
                                            <div class="inp inpPassword">
                                                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="确认密码">
                                            </div>
                                        </div>
                                        <div class="testwap">
                                            <div class="inp inpEmail">
                                                <input type="text" id="code" name="code" placeholder="短信验证码">
                                            </div>
                                        </div>
                                        {{ csrf_field() }}
                                        <input type="submit" value="保存">
                                    </div>
                                    <!-- 信息相关 结束 -->   
                            </div>
                        </form>
                <!-- </div> -->
                <!--手机验证修改结束-->
                </div>
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
<script src="{{ asset('js/vlogin.js') }}"></script>
@endsection