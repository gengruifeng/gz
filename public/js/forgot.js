/**
 * Created by Administrator on 2016/7/15.
 */
// 自定义方法 只手机
jQuery.validator.addMethod("mobUsername", function(value, element) {
    return this.optional(element) || /^1[3|4|5|7|8]\d{9}$/.test(value);
}, "手机号格式不正确。");
$(document).ready(function(){
// 手机找回密码 loginFindMobile
    $('#loginFindMobile-form').validate({
        errorElement: 'p',
        errorClass: 'red-text',
        focusInvalid: true,
        rules: {
            mobile:{//只手机号
                required: true,
                mobUsername:true
            },
            code: {//
                required: true,
            },
        },
        messages: {
            mobile:{
                required:"请输入手机号"
            },
            code:{
                required: "短信验证码",
            },
        },
        highlight: function (e) {//错误的显示
            if($(e).attr('id') != 'code-error'){
                $(e).parent().removeClass('correct');
            }
            $(e).parent().addClass('err');

        },
        success: function (e) {//成功
            $(e).prev().removeClass('err');
            if($(e).attr('id') != 'code-error'){
                $(e).prev().addClass('correct');
            }
            $(e).remove();
        },
        errorPlacement: function (error, element) {//错误显示位置
            error.insertAfter(element.parent());
        },
        submitHandler: function (form) {//可提交后操作
            var data =$(form).serialize();
            $.ajax({
                url: $(form).attr('action'),
                type: 'POST',
                dataType: 'json',
                data: data+'&template=retrieve&type=1&isTable=1',
            })
                .fail(function(XMLHttpRequest, textStatus, errorThrown) {
                    $('.red-text').remove();
                    if(textStatus == 'error'){
                        var obj = JSON.parse(XMLHttpRequest.responseText);
                        var errors = obj.errors;
                        $.each(errors,function (name,vale) {
                            $("input[name=" + name + "]").parent().after('<p id="'+name+'-error" class="red-text">'+vale+'</p>')
                        })
                        $('#img').attr('src','/users/code/'+Math.random()*10000+1);

                    }else{
                        $('#mobiletwo').val($('#mobile').val());
                        $('.retrieveMobileone').addClass('hide');
                        $('.retrieveMobiletwo').removeClass('hide');
                        document.cookie = "secondsremained" + "=60";
                        settime($("#getverifycode"));
                        location.hash='setNewPwd';
                    }

                })
        }
    });
});



// 手机号账号注册 MobileRegistration-form
$('#MobileRegistration-form').validate({
    errorElement: 'p',
    errorClass: 'red-text',
    focusInvalid: true,
    rules: {

        password: {//密码 输入长度必须介于 6 和 16之间的字符串。
            required: true,
            rangelength:[6,16]
        },
        password_confirmation: {//确认密码
            required: true,
            rangelength:[6,16],
            equalTo: "#password"
        },
        code: {// 短信验证码 6位数字
            required: true,
            maxlength:6,
            minlength:6
        },
    },
    messages: {

        password: {
            required: "请输入密码。",
            rangelength:"密码应为6到16个字符"
        },
        password_confirmation: {
            required: "请输入密码",
            rangelength: "密码应为6到16个字符",
            equalTo: "两次密码不一致"
        },
        code:{
            required: "短信验证码",
            minlength: "短信验证码为6位",
            maxlength:"短信验证码为6位",
            digits:"只能为数字"
        },
    },
    highlight: function (e) {//错误的显示
        if($(e).attr('id') != 'code-error'){
            $(e).parent().removeClass('correct');
        }
        $(e).parent().addClass('err');

    },
    success: function (e) {//成功
        $(e).prev().removeClass('err');
        if($(e).attr('id') != 'code-error'){
            $(e).prev().addClass('correct');
        }
        $(e).remove();
    },
    errorPlacement: function (error, element) {//错误显示位置
        error.insertAfter(element.parent());
    },
    submitHandler: function (form) {//可提交后操作
        var data =$(form).serialize();
        $.ajax({
            url: $(form).attr('action'),
            type: 'POST',
            dataType: 'json',
            data: data+'&template=retrieve&type=1&isTable=1',
        })
            .fail(function(XMLHttpRequest, textStatus, errorThrown) {
                $('.red-text').remove();
                if(textStatus == 'error'){
                    var obj = JSON.parse(XMLHttpRequest.responseText);
                    var errors = obj.errors;
                    $.each(errors,function (name,vale) {
                        $("input[name=" + name + "]").parent().after('<p id="'+name+'-error" class="red-text">'+vale+'</p>')
                    })
                }else{
                    window.location.href="/login";
                }

            })
    }
});

// 获取短信验证码
$("#getverifycode").click(function () {
    var mobile = $("#mobile").val();
    var token = $("#loginFindMobile-form input[name = '_token']").val();
    $.ajax({
        type:"post",
        url:"/ajax/sendsms",
        data:{mobile:mobile,isTable:1,template:'retrieve',_token:token},
        dataType: "json"
    }).fail(function(XMLHttpRequest, textStatus) {
        $('.red-text').remove();
        if(textStatus == 'error'){
            if( XMLHttpRequest.status == 400){
                var errors = XMLHttpRequest.responseJSON.errors;
                $.each(errors,function (name,vale) {
                    $("input[name=" + vale.input + "]").parent().after('<p id="'+name+'-error" class="red-text">'+vale.message+'</p>')
                })
            }
        }else{
            document.cookie = "secondsremained" + "=60";
            settime($("#getverifycode"));
        }
    });
});
