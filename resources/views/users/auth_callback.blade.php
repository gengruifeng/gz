<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>第三方登录账号注册</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/vlogin.css') }}"/>
</head>
<body class="bcimg2">
<!-- 主体开始  -->
<div class="vlogin loginOther">
    <div class="inner">
        <h1></h1>
        <h2>账号注册</h2>
        <form action="" id="mobile-form" autocomplete="off">
            <!-- 信息相关 开始 -->
            <div class="tp">
                <div class="testwap">
                    <div class="inp inpYaoq">
                        <input type="text" id="referral_code" name="referral_code" placeholder="您的邀请码">
                    </div>
                </div>
                <div class="testwap">
                    <div class="inp inpMobile">
                        <input type="text" id="mobile" name="mobile" placeholder="您的手机号码">
                    </div>
                </div>
                <div class="testwap">
                    <div class="inp inpPassword">
                        <input type="password" id="password" name="password" placeholder="您的密码">
                    </div>
                </div>
                <div class="testwap">
                    <div class="inp inpPassword">
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="确认密码">
                    </div>
                </div>
                <div class="verifywrap">
                    {{ csrf_field() }}
                    <div class="testwap">
                        <dl class="clearfix verify">
                            <dt class="fl mobMsg"><input type="text" id="mobMsg" name="mobMsg"  placeholder="短信验证码"></dt>
                            <dt class="fr"><a href="javascript:;" id="sendsmscodews" >免费获取验证码</a></dt>
                        </dl>
                    </div>
                </div>
                <input type="submit" value="注册">
            </div>
            <!-- 信息相关 结束 -->

        </form>
    </div>
</div>
<!-- 主体结束 -->
</body>
<script src="{{asset('js/jquery-2.1.0.js')}}"></script>
<script src="{{asset('js/jquery.validate.js')}}"></script>
<script src="{{asset('js/vlogin.js')}}"></script>
<script src="{{asset('js/common.js')}}"></script>
<script src="{{asset('js/information.js')}}"></script>
</html>