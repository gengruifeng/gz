<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>请输入接收邮箱地址</title>
    <meta name="description" content="帮你解决简历的基本问题、难题！ 工作网欢迎您来分享您的职场经验。"/>
    <meta name="keywords" content="简历"/>
    <meta name="viewport" content="width=device-width,initial-scale=1, minimum-scale=1.0, maximum-scale=1, user-scalable=no">
    <link rel="stylesheet" href="{{ asset('css/css3/public.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/css3/resume.css') }}"/>
</head>
<body>
<!--头部开始-->
<div class="common-head clearfix">
    <a href="javascript:window.history.go(-1);" class="fl btn-close-bac btn-close"></a>
    <div class="logo">请输入接收邮箱地址</div>
    <a href="javascript:void(0)" class="btn-right btn-finish"></a>
</div>
<!--内容开始-->
<section>
    <div class="resume-wrap-email">
        <input type="email" name="" id="sendemail">
        <input type="hidden" id="prevurl" value="{{ $url }}">
        {{ csrf_field() }}
    </div>
    <div class="alertTips" style="display: none;">
        <span>请输入正确的邮箱!</span>
    </div>
</section>
<script src="{{ asset('js/jquery-2.1.0.js') }}"></script>
<script>
    $("#sendemail").focus();
    $('.alertTips').on('click',function(){
        $("#sendemail").focus();
        $("#sendemail").val("");
        $(this).hide();
    });
    //发送邮件
    $(".btn-finish").click(function() {
        var flag = true;
        var email = $("#sendemail").val();
        var prevurl = $("#prevurl").val();
        var regemail = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        var token = $("input[name = '_token']").val();
        if (!regemail.test(email)) {
            $(".alertTips").css('display','block');
            flag = false;
            return;
        }
        if (flag) {
            $.ajax({
                type: "post",
                url: "/ajax/cv/templates/sendemail",
                data: {email: email, url: prevurl, _token: token},
                dataType: "json"
            }).fail(function (XMLHttpRequest, textStatus) {
                if (textStatus == 'error') {

                    $(".alertTips").html("<span>"+XMLHttpRequest.responseJSON.description+"</span>");
                    $(".alertTips").css('display','block');
                } else {
                    window.history.go(-1);
                }
            });
        }
    });
</script>
</body>
</html>
