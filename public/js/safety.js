/**
 * Created by Administrator on 2016/7/20.
 */
$(document).ready(function(){
    // 自定义方法 密码
    jQuery.validator.addMethod("password", function(value, element) {
        return this.optional(element) || /^[\w_]{6,16}$/.test(value);
    }, "密码为6-16位字符，只能输入英文、数字、‘_’。");

    if($('#mobileChange-form').length > 0){
        $('#mobileChange-form').validate({
            errorElement: 'p',
            errorClass: 'red-text',
            focusInvalid: true,
            rules: {
                password: {//密码 输入长度必须介于 6 和 16之间的字符串
                    required: true,
                    password:true
                },
                password_confirmation: {//确认密码
                    required: true,
                    password:true,
                    equalTo: "#password"
                },
                code: {// 短信验证码 6位数字
                    required: true,
                    digits:true,
                    maxlength:6,
                    minlength:6
                }
            },
            messages: {
                password: {
                    required: "请输入密码。",
                    password:"密码为6-16位字符，只能输入英文、数字、‘_’"
                },
                password_confirmation: {
                    required: "请输入密码",
                    password:"确认密码为6-16位字符，只能输入英文、数字、‘_’",
                    equalTo: "两次密码不一致"
                },
                code:{
                    required: "请输入短信验证码",
                    minlength: "短信验证码为6位",
                    maxlength:"短信验证码为6位",
                    digits:"只能为数字"
                }
            },
            highlight: function (e) {//错误的显示
                $(e).parent().removeClass('correct');
                $(e).parent().addClass('err');

            },
            success: function (e) {//成功
                $(e).prev().removeClass('err');
                $(e).prev().addClass('correct');
                $(e).remove();
            },
            errorPlacement: function (error, element) {//错误显示位置
                error.insertAfter(element.parent());
            },
            submitHandler: function (form) {//可提交后操作
                //ajax
                var data =$(form).serialize();
                $.ajax({
                    url: $(form).attr('action'),
                    type: 'POST',
                    dataType: 'json',
                    data: data+'&template=updatepass&type=1&isTable=1&mobile='+mobile,
                })
                    .done(function() {
                        window.location.href="/login";
                    })
                    .fail(function(XMLHttpRequest, textStatus, errorThrown) {
                        if(textStatus == 'error'){
                            if(XMLHttpRequest.status == 401){
                                dialoglogin();
                                return false;
                            }
                            if(XMLHttpRequest.status == 403){
                                dialogcom_wrong('页面停留时间过长请刷新页面后再操作!');
                                return false;
                            }
                            var obj = JSON.parse(XMLHttpRequest.responseText);
                            var errors = obj.errors;
                            $.each(errors,function (name,vale) {
                                $("input[name=" + name + "]").parent().after('<p id="'+name+'-error" class="red-text">'+vale+'</p>');
                            })
                        }else{
                            window.location.href="/login";
                        }
                    })
            }
        });
    }

});

function checkMobile() {
    if(mobile == ''){
        dialogcom_warn('您还没绑定手机号！');
        return false;
    }

    var data =$('#mobileForm').serialize();
    $.ajax({
        url: $('#mobileForm').attr('action'),
        type: 'POST',
        dataType: 'json',
        data: data+'&template=updatepass&isTable=1&mobile='+mobile,
    })
    .done(function() {
        window.location.href="/account/safetymobile"
    })
    .fail(function(XMLHttpRequest, textStatus, errorThrown) {
        if(textStatus == 'error'){
            if(XMLHttpRequest.status == 401){
                dialoglogin();
                return false;
            }
            if(XMLHttpRequest.status == 403){
                dialogcom_wrong('页面停留时间过长请刷新页面后再操作!');
                return false;
            }
            var obj = JSON.parse(XMLHttpRequest.responseText);
            var errors = obj.errors;
            $.each(errors,function (name,vale) {
                dialogcom_wrong(vale);
            })
        }else{
            window.location.href="/account/safetymobile"
        }
    })
}

function checkEmail() {
    if(email == ''){
        dialogcom_wrong('您还没绑定邮件！');
        return false;
    }
    var data =$('#mailForm').serialize();
    $.ajax({
        url: $('#mailForm').attr('action'),
        type: 'POST',
        dataType: 'json',
        data: data+'&template=updatepassword&isTable=1&mail='+email,
    })
    .done(function() {
        window.location.href="/account/safetyemail";
    })
    .fail(function(XMLHttpRequest, textStatus, errorThrown) {
        if(textStatus == 'error'){
            if(XMLHttpRequest.status == 401){
                dialoglogin();
                return false;
            }
            if(XMLHttpRequest.status == 403){
                dialogcom_wrong('页面停留时间过长请刷新页面后再操作!');
                return false;
            }
            var obj = JSON.parse(XMLHttpRequest.responseText);
            var errors = obj.errors;
            $.each(errors,function (name,vale) {
                $("input[name=mail]").after("<p class='grf'>"+vale+"</p>");
            })
        }else{
            window.location.href="/account/safetyemail";
        }

    })
}

// function mobilePass() {
//     if($('.grf').length!=0){
//         $('.grf').remove();
//     }
//
//     if($("input[name=password]").val().trim().length<= 0){
//         $("input[name=password]").after("<p class='grf'>密码不能为空!</p>");
//         return false;
//     }else if($("input[name=password_confirmation]").val().trim().length<= 0){
//         $("input[name=password_confirmation]").after("<p class='grf'>确认密码不能为空!</p>");
//         return false;
//     }else if($("input[name=code]").val().trim().length<= 0){
//         $("input[name=code]").after("<p class='grf'>验证码不能为空!</p>");
//         return false;
//     }
//     var data =$('#form').serialize();
//     $.ajax({
//         url: $('#form').attr('action'),
//         type: 'POST',
//         dataType: 'json',
//         data: data+'&template=updatepass&type=1&isTable=1&mobile='+mobile,
//     })
//         .done(function() {
//             window.location.href="/login";
//         })
//         .fail(function(XMLHttpRequest, textStatus, errorThrown) {
//             if(textStatus == 'error'){
//                 var obj = JSON.parse(XMLHttpRequest.responseText);
//                 var errors = obj.errors;
//                 $.each(errors,function (name,vale) {
//                     $("input[name="+name+"]").after("<p class='grf'>"+vale+"</p>");
//                 })
//             }else{
//                 window.location.href="/login";
//             }
//
//         })
// }



function setEmail() {
    if($('.grf').length!=0){
        $('.grf').remove();
    }
    if($("input[name=mail]").val().trim().length<= 0){
        $("input[name=mail]").after("<p class='grf'>邮箱不能为空!</p>");
        return false;
    }else if(!/^\w{3,}@\w+(\.\w+)+$/.test($("input[name=mail]").val().trim())){
        $("input[name=mail]").after("<p class='grf'>邮箱格式不正确!</p>");
        return false;
    }
    var data =$('#setMailForm').serialize();
    $.ajax({
        url: $('#setMailForm').attr('action'),
        type: 'POST',
        dataType: 'json',
        data: data+'&template=setmail&isTable=2',
    })
        .done(function() {
            window.location.href="/account/setemailtwo/email/"+$("input[name=mail]").val().trim();
        })
        .fail(function(XMLHttpRequest, textStatus, errorThrown) {
            if(textStatus == 'error'){
                if(XMLHttpRequest.status == 401){
                    dialoglogin();
                    return false;
                }
                if(XMLHttpRequest.status == 403){
                    dialogcom_wrong('页面停留时间过长请刷新页面后再操作!');
                    return false;
                }
                var obj = JSON.parse(XMLHttpRequest.responseText);
                var errors = obj.errors;
                $.each(errors,function (name,vale) {
                    $("input[name=mail]").after("<p class='grf'>"+vale+"</p>");
                })
            }else{
                window.location.href="/account/setemailtwo/email/"+$("input[name=mail]").val().trim();
            }

        })
}

        
grfsendSms = function sendSms(th,isTable) {

    if($('.grf').length!=0){
        $('.grf').remove();
    }
    if($("input[name=mobile]").val().trim().length<= 0){
        $("input[name=mobile]").after("<p class='grf'>手机不能为空!</p>");
        return false;
    }else if(!/^1[34578]\d{9}$/.test($("input[name=mobile]").val().trim())){
        $("input[name=mobile]").after("<p class='grf'>手机格式不正确!</p>");
        return false;
    }

    var data =$('#mobileForm').serialize();
    $.ajax({
        url: $('#mobileForm').attr('action'),
        type: 'POST',
        dataType: 'json',
        data: data+'&template=binding&isTable='+isTable,
    })
        .fail(function(XMLHttpRequest, textStatus, errorThrown) {
            if(textStatus == 'error'){
                if(XMLHttpRequest.status == 401){
                    dialoglogin();
                    return false;
                }
                if(XMLHttpRequest.status == 403){
                    dialogcom_wrong('页面停留时间过长请刷新页面后再操作!');
                    return false;
                }
                var obj = JSON.parse(XMLHttpRequest.responseText);
                var errors = obj.errors;
                $.each(errors,function (name,vale) {
                    if(isTable == 1){
                        dialogcom_wrong(vale);
                    }else{
                        $("input[name="+name+"]").after("<p class='grf'>"+vale+"</p>");
                    }

                })
            }else{
                addCookie("secondsremained",60,60);//添加cookie记录,有效时间60s
                settime($(th));
            }
        })
}





function subbindingMobiletwo(url) {
    if($('.grf').length!=0){
        $('.grf').remove();
    }

    if($("input[name=mobile]").val().trim().length<= 0){
        $("input[name=mobile]").after("<p class='grf'>手机不能为空!</p>");
        return false;
    }else if(!/^1[34578]\d{9}$/.test($("input[name=mobile]").val().trim())){
        $("input[name=mobile]").after("<p class='grf'>手机格式不正确!</p>");
        return false;
    }else if($("input[name=code]").val().trim().length<= 0){
        $("input[name=code]").after("<p class='grf'>验证码不能为空!</p>");
        return false;
    }

    type = 1;
    if(url == 'changemobiletwo'){
        type = 2;
    }
    var data =$('#submobileForm').serialize();
    $.ajax({
        url: $('#submobileForm').attr('action'),
        type: 'POST',
        dataType: 'json',
        data: data+'&type='+type+'&mobile='+ $("input[name=mobile]").val(),
    })
    .done(function() {
        $('.setPhoneFinish').attr('class','setPhoneFinish');
        $('.setPhone').css('display','none');
        $('.newp').text($("input[name=mobile]").val());
    })
    .fail(function(XMLHttpRequest, textStatus, errorThrown) {
        if(textStatus == 'error'){
            if(XMLHttpRequest.status == 401){
                dialoglogin();
                return false;
            }
            if(XMLHttpRequest.status == 403){
                dialogcom_wrong('页面停留时间过长请刷新页面后再操作!');
                return false;
            }
            var obj = JSON.parse(XMLHttpRequest.responseText);
            var errors = obj.errors;
            $.each(errors,function (name,vale) {
                $("input[name="+name+"]").after("<p class='grf'>"+vale+"</p>");

            })
        }else{
            $('.setPhoneFinish').attr('class','setPhoneFinish');
            $('.setPhone').css('display','none');
            $('.newp').text($("input[name=mobile]").val());
        }
    })
}

function subbindingMobile(url) {
    if($('.grf').length!=0){
        $('.grf').remove();
    }

    if($("input[name=mobile]").val().trim().length<= 0){
        $("input[name=mobile]").after("<p class='grf'>手机不能为空!</p>");
        return false;
    }else if(!/^1[34578]\d{9}$/.test($("input[name=mobile]").val().trim())){
        $("input[name=mobile]").after("<p class='grf'>手机格式不正确!</p>");
        return false;
    }else if($("input[name=code]").val().trim().length<= 0){
        $("input[name=code]").after("<p class='grf'>验证码不能为空!</p>");
        return false;
    }

    type = 1;
    if(url == 'changemobiletwo'){
        type = 2;
    }
    var data =$('#submobileForm').serialize();
    $.ajax({
        url: $('#submobileForm').attr('action'),
        type: 'POST',
        dataType: 'json',
        data: data+'&type='+type+'&mobile='+ $("input[name=mobile]").val(),
    })
        .done(function() {
            is = getCookieValue("secondsremained");
            if(is != undefined){
                document.cookie = "secondsremained" + "=0";
            }
            window.location.href= '/account/'+url;
        })
        .fail(function(XMLHttpRequest, textStatus, errorThrown) {
            if(textStatus == 'error'){
                if(XMLHttpRequest.status == 401){
                    dialoglogin();
                    return false;
                }
                if(XMLHttpRequest.status == 403){
                    dialogcom_wrong('页面停留时间过长请刷新页面后再操作!');
                    return false;
                }
                var obj = JSON.parse(XMLHttpRequest.responseText);
                var errors = obj.errors;
                $.each(errors,function (name,vale) {
                    $("input[name="+name+"]").after("<p class='grf'>"+vale+"</p>");

                })
            }else{
                is = getCookieValue("secondsremained");
                if(is != undefined){
                    document.cookie = "secondsremained" + "=0";
                }
                window.location.href= '/account/'+url;
            }
        })
}

function changemobileonecode() {

    if(mobile == ''){
        dialogcom_wrong('您还没绑定手机号！');
        return false;
    }

    var data =$('#mobileForm').serialize();
    $.ajax({
        url: $('#mobileForm').attr('action'),
        type: 'POST',
        dataType: 'json',
        data: data+'&template=binding&isTable=1&mobile='+mobile,
    })
        .fail(function(XMLHttpRequest, textStatus, errorThrown) {
            if(textStatus == 'error'){
                if(XMLHttpRequest.status == 401){
                    dialoglogin();
                    return false;
                }
                if(XMLHttpRequest.status == 403){
                    dialogcom_wrong('页面停留时间过长请刷新页面后再操作!');
                    return false;
                }
                var obj = JSON.parse(XMLHttpRequest.responseText);
                var errors = obj.errors;
                $.each(errors,function (name,vale) {
                    dialogcom_wrong(vale);
                })
            }else{
                document.cookie = "secondsremained" + "=60";
                window.location.href="/account/changemobileone"
            }
        })
}
