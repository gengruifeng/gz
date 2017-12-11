<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>邮箱找回密码-工作网</title>
    <link rel="stylesheet" type="text/css" href="{{asset('/css/vlogin.css')}}"/>
</head>
<body class="bcimg11">
<!-- 主体开始  -->
<div class="vlogin retrieveMobile retrieveEmail">
    <div class="inner">
        <a href="{{url('/')}}"><h1></h1></a>
        <h2>找回密码</h2>
        <form id="loginFindEmail-form" action="{{ url('ajax/sendmail') }}" method="post" onsubmit="return false"  autocomplete="off">
            <!-- 信息相关 开始 -->
            <div class="tp">
                <div class="testwap">
                    <div class="inp inpEmail"><input type="text" id="mail" name="mail" placeholder="您的邮箱"></div>
                </div>

                <div class="clearfix regestypeChange"><a href="{{url('forgot/mobile')}}" class="fr">使用手机号找回</a></div>
                <dl class="clearfix identifying">
                    <dt class="fl"><img id="img" src="{{ asset('/users/code/123123') }}"  onclick="this.src='{{url('users/code')}}/'+Math.random()"/></dt>
                    <dd class="fr identifyingdd">
                        <input type="text" id="code" name="code" placeholder="验证码">
                    </dd>
                </dl>
                <input type="submit" value="立即找回">
            </div>
        {{ csrf_field() }}
        <!-- 信息相关 结束 -->
        </form>
    </div>

</div>
<!-- 主体结束 -->
<!-- 主体开始  -->
<div class="vlogin Emailmodify hide">
    <div class="inner">
        <a href="{{url('/')}}"><h1></h1></a>
        <div class="cnt">
            <!-- 信息相关 开始 -->
            <div class="tp">
                <p>邮件已发送到您的邮箱</p>
                <p class="emialdress"></p>
                <p>请点击邮箱中的验证链接完成验证</p>
            </div>
            <a href="javascript:void(0)" class="sub" >前往邮箱验证</a>
            <div class="list">
                <div class="tit"><span>没有收到邮件?</span></div>
                <ul>
                    <li><span>1</span>看看Email地址有没有写错</li>
                    <li><span>2</span>看看是否在垃圾邮箱里</li>
                    <li><span>3</span>致电客服电话：400-XXX-8888</li>
                </ul>
            </div>
            <!-- 信息相关 结束 -->
        </div>
    </div>
</div>
<!-- 主体结束 -->


</body>
<script src="{{asset('/js/jquery-2.1.0.js')}}"></script>
<script src="{{asset('/js/jquery.validate.js')}}"></script>
<script src="{{asset('/js/vlogin.js')}}"></script>
<script src="{{asset('/js/common.js')}}"></script>
<script>
    $(window).bind('hashchange', function() {
        u = location.href.split('#');
        if(u[1] == undefined){
            $('.Emailmodify').addClass('hide');
            $('.retrieveMobile').removeClass('hide');
        }

    });
</script>
</html>
