<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>邮箱注册-工作网</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/vlogin.css') }}"/>
</head>
<body class="bcimg13">
<!-- 主体开始  -->
<div class="vlogin loginReMobile2">
    <div class="inner">
        <a href="{{url('/')}}"><h1></h1></a>
        <form action="" method="post" id="loginRegistrationEmail-form"  autocomplete="off">
            <!-- 信息相关 开始 -->
            <div class="tp">
                <div class="testwap">
                    <div class="inp inpEmail"><input type="text" name="email" id="emailUser" placeholder="您的邮箱"></div>
                </div>
                <div class="clearfix regestypeChange"><a href="{{url('/registermobile')}}" class="fr">使用手机注册</a></div>
                {{ csrf_field() }}
                <input type="submit" value="立即注册">
                <p class="tips">已经注册？ <a href="{{url('/login')}}">马上登录&nbsp;<span>>></span></a></p>
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
<script src="{{ asset('js/vlogin.js') }}"></script>
</html>


