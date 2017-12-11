<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>账号注册-工作网</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/vlogin.css') }}"/>
</head>
<body class="bcimg14">
<!-- 主体开始  -->
<div class="vlogin">
    <div class="inner">
        <a href="{{url('/')}}"><h1></h1></a>
        <h2>账号注册</h2>
        <form action="" id="MobileRegistration-form"  autocomplete="off">
            <!-- 信息相关 开始 -->
            <div class="tp">
                <div class="testwap">
                    <div class="inp inpYaoq">
                        <input type="text" id="referral_code" name="referral_code" placeholder="您的邀请码">
                    </div>
                </div>
                <div class="testwap">
                    <div class="inp inpPassword"><input type="password" id="passcode" name="passcode" placeholder="您的密码"></div>
                </div>
                <div class="testwap">
                    <div class="inp inpPassword"><input type="password" id="passcode_confirmation" name="passcode_confirmation" placeholder="确认密码"></div>
                </div>
                <div class="verifywrap">
                    <div class="testwap">
                        <dl class="clearfix verify">
                            <dt class="fl"><input type="text" id="verifycode" name="verifycode" placeholder="短信验证码"></dt>
                            <dt class="fr codedt"><button href="javascript:;" id="sendsmscode" type="button">获取验证码</button></dt>
                        </dl>
                    </div>
                </div>
                <input id="mobile" type="hidden" value="{{$mobile}}" />
                {{ csrf_field() }}
                <input type="submit" value="下一步">

            </div>
            <!-- 信息相关 结束 -->
        </form>
    </div>
</div>
<!-- 主体结束 -->
</body>
<script src="{{ asset('js/jquery-2.1.0.js') }}"></script>
<script src="{{ asset('js/jquery.form.js') }}"></script>
<script src="{{ asset('js/jquery.validate.js') }}"></script>
<script src="{{ asset('js/vlogin.js') }}"></script>
<script src="{{ asset('js/register.js') }}"></script>
<script src="{{ asset('js/common.js') }}"></script>
</html>
