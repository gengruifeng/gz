<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>邮箱确认-工作网</title>
    <link rel="stylesheet" type="text/css" href="{{asset('/css/vlogin.css')}}"/>
</head>
<body class="bcimg9">
<!-- 主体开始  -->
<div class="vlogin Emailmodify">
    <div class="inner">
        <a href="{{url('/')}}"><h1></h1></a>
        <div class="cnt">
            <!-- 信息相关 开始 -->
            <div class="tp">
                <p>邮件已发送到您的邮箱</p>
                <p class="emialdress" id="email">{{$email}}</p>
                <p>请点击邮箱中的验证链接完成验证</p>
                <p>链接将会在24小时后失效，请您尽快激活！</p>
            </div>
            <a href="javascript:void(0)" onclick="goMail('{{ $email }}')" class="sub">前往邮箱验证</a>
            <div class="list">
                <div class="tit"><span>没有收到邮件?</span></div>
                <ul>
                    <li><span>1</span>看看Email地址有没有写错</li>
                    <li><span>2</span>看看是否在垃圾邮箱里</li>
                    <li><span>3</span>试试重新<a href="{{url('sendemailagain')}}">申请发送</a>验证链</li>
                </ul>
            </div>
            <!-- 信息相关 结束 -->
        </div>
    </div>
</div>
<!-- 主体结束 -->
</body>
<script src="{{url('/js/jquery-2.1.0.js')}}"></script>
<script src="{{url('/js/common.js')}}"></script>
</html>
