<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>重新发送验证邮箱-工作网</title>
    <link rel="stylesheet" type="text/css" href="{{asset('/css/vlogin.css')}}"/>
</head>
<body class="bcimg9">
<!-- 主体开始  -->
<div class="vlogin  retrieveEmail2">
    <div class="inner">
        <a href="{{url('/')}}"><h1></h1></a>
        <h2>重新发送验证邮箱</h2>
        <form action="" onsubmit="return false" id="loginSureEmail-form" autocomplete="off">
            <!-- 信息相关 开始 -->
            <div class="tp">
                <div class="testwap">
                    <div class="inp inpEmail"><input type="text" id="email" name="email" placeholder="请输入邮箱"></div>
                </div>
                <input type="submit" value="发送验证邮件">
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
