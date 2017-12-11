<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>手机找回密码-工作网</title>
    <link rel="stylesheet" href="{{ asset('css/vlogin.css') }}"/>
</head>
<body class="bcimg8">
<!-- 主体开始  -->
<div class="vlogin retrieveMobile retrieveMobileone">
    <div class="inner">
        <a href="{{url('/')}}"><h1></h1></a>
        <h2>找回密码</h2>
        <form action="{{ url('ajax/sendsms') }}" id="loginFindMobile-form" autocomplete="off">
            <!-- 信息相关 开始 -->
            <div class="tp">
                <div class="testwap">
                    <div class="inp inpMobile"><input type="text" id="mobile" name="mobile" placeholder="您的手机号码"></div>
                </div>
                <div class="clearfix regestypeChange"><a href="{{ url('forgot/email') }}" class="fr">使用邮箱找回</a></div>
                <dl class="clearfix identifying">
                    <dt class="fl"><img id="img" src="{{ asset('/users/code/123123') }}" onclick="this.src='{{ url('users/code') }}/'+Math.random()" title="点击更换验证码"></dt>
                    <dd class="fr identifyingdd">
                        <input type="text" id="code" name="code" placeholder="验证码">
                    </dd>
                </dl>
                {{ csrf_field() }}
                <input type="submit" value="立即找回">
            </div>
            <!-- 信息相关 结束 -->
        </form>
    </div>
</div>
<!-- 主体结束 -->

<!-- 主体开始  -->
<div class="vlogin retrieveMobiletwo hide">
    <div class="inner">
        <h1></h1>
        <h2>找回密码</h2>
        <form action="{{ url('ajax/mobilepass') }}" method="post" id="MobileRegistration-form">
            <!-- 信息相关 开始 -->
            <div class="tp">
                <div class="testwap">
                    <div class="inp inpPassword"><input type="password" id="password" name="password" placeholder="您的密码"></div>
                </div>
                <div class="testwap">
                    <div class="inp inpPassword"><input type="password" id="password_confirmation" name="password_confirmation" placeholder="确认密码"></div>
                </div>
                <div class="verifywrap">
                    <div class="testwap">
                        <dl class="clearfix verify">
                            <dt class="fl"><input type="text" id="code" name="code" placeholder="短信验证码"></dt>
                            <dt class="fr codedt"><button  id="getverifycode" href="javascript:;">重新获取验证码</button></dt>
                        </dl>
                    </div>
                </div>
                <input type="hidden" id="mobiletwo" name="mobile" value="">
                {{ csrf_field() }}
                <input type="submit" value="完成">

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
<script src="{{ asset('js/forgot.js') }}"></script>
<script src="{{ asset('js/common.js') }}"></script>
<script>
    $(window).bind('hashchange', function() {
        u = location.href.split('#');
        if(u[1] == undefined){
            $('.setNewPwd').addClass('display');
            $('.reBackPwd').removeClass('display');
        }

    });
</script>
</html>

