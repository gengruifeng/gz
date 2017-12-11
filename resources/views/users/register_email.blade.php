<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>账号注册-工作网</title>
    <link rel="stylesheet" type="text/css" href="{{asset('/css/vlogin.css')}}"/>
</head>
<body class="bcimg14">
<!-- 主体开始  -->
<div class="vlogin retrieveMobile">
    <div class="inner">
        <a href="{{url('/')}}"><h1></h1></a>
        <h2>账号注册</h2>
        <form action="" id="loginRegistrationEpassword-form"  autocomplete="off">
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
                <dl class="clearfix identifying">
                    <dt class="fl" id="verifyCodeReplace"><img src="{{url('users/code/1')}}" onclick="this.src='{{url('users/code')}}/'+Math.random()"
                        /></dt>
                    <dd class="fr identifyingdd">
                        <input type="text" name="verifycode" id="messageCheck" placeholder="验证码">
                    </dd>
                </dl>
                <input id="id" type="hidden" value="{{$id}}" />
                <input type="submit" value="下一步">
            </div>
        {{ csrf_field() }}
            <!-- 信息相关 结束 -->
        </form>
    </div>
</div>
<!-- 主体结束 -->
</body>
<script src="{{asset('/js/jquery-2.1.0.js')}}"></script>
<script src="{{asset('/js/jquery.validate.js')}}"></script>
<script src="{{asset('/js/vlogin.js')}}"></script>
</html>
