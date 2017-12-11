<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>登录-工作网</title>
    <link rel="stylesheet" href="{{ asset('css/vlogin.css') }}"/>
</head>
<body class="bcimg15">
<!-- 主体开始  -->
<div class="vlogin">
    <div class="inner">
        <a href="{{url('/')}}"><h1></h1></a>
        @if($isUpdated == 1)
            <h3>密码设置成功，请重新登录</h3>
        @endif
        <form id="login-form"  method="post" action="{{ url('/ajax/login') }}" autocomplete="off" >
            <!-- 信息相关 开始 -->
            <div class="tp">
                <div class="testwap">
                    <div class="inp inpUser">
                        <input type="text" id="auth_name" name="auth_name" placeholder="您的邮箱/手机号" >
                    </div>
                </div>
                <div class="testwap">
                    <div class="inp inpPassword">
                        <input type="password" id="passcode" name="passcode" placeholder="您的密码">
                    </div>
                </div>
                <div class="clearfix passwordRm">
                    <div class="fl checkbox">
                        <p class="fl"><input name="remember_me" value="1" type="checkbox" id="checkbox"><span><b></b></span></p><label class="fl" for="checkbox">记住我</label>
                    </div>
                    <a href="{{ url('forgot/mobile') }}" class="fr">忘记密码？</a>
                </div>
                {{ csrf_field() }}
                <input type="hidden" name="l" value="{{ !empty($l)?$l:'' }}" />
                <input type="submit" value="登录">
                <p class="tips">还没有账号？ <a href="{{ url('registermobile') }}">立即注册&nbsp;<span>>></span></a></p>
            </div>
            <!-- 信息相关 结束 -->

            <!-- 第三方账号登录 开始 -->
            <div class="btm">
                <p><span>第三方账号登录</span></p>
                <ul class="clearfix">
                    <li class="fl"><a href="{{ url("auth/weixinweb") }}" target="_blank"></a></li>
                    <li class="fl"><a href="{{ url("auth/qq") }}" target="_blank"></a></li>
                    <li class="fl"><a href="{{ url("auth/weibo") }}" target="_blank"></a></li>
                </ul>
            </div>
            <!-- 第三方账号登录 结束 -->
        </form>
    </div>
</div>
<!-- 主体结束 -->
</body>
<script src="{{ asset('js/jquery-2.1.0.js') }}"></script>
<script src="{{ asset('js/jquery.form.js') }}"></script>
<script src="{{ asset('js/jquery.validate.js') }}"></script>
<script src="{{ asset('js/login.js') }}"></script>
</html>

