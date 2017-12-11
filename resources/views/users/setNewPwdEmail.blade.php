<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>设置新密码-工作网</title>
    <link rel="stylesheet" type="text/css" href="{{asset('/css/vlogin.css')}}"/>
</head>
<body class="bcimg10">
<!-- 主体开始  -->
<div class="vlogin">
    <div class="inner">
        <a href="{{url('/')}}"><h1></h1></a>
        <h2>设置新密码</h2>
        <form id="loginPssswordResetEmail-form" action="{{ url('ajax/mailpass') }}" method="post" onsubmit="return false"  autocomplete="off">
            <!-- 信息相关 开始 -->
            <div class="tp">
                <div class="testwap">
                    <div class="inp inpPassword">
                        <input type="password" id="password" name="password" placeholder="新密码" autocomplete="off">
                    </div>
                </div>
                <div class="testwap">
                    <div class="inp inpPassword">
                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="确认密码" autocomplete="off">
                    </div>
                </div>
                <input type="submit" value="完  成">
            </div>
            <input type="hidden" name="code" value="{{ $token }}">
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
