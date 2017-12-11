 //完善个人信息，必须绑定手机号
// $('#mobile').blur(function () {
//         var token = $("input[name = '_token']").val();
//         var mobile = $("#mobile").val();
//         $.ajax({
//             type:"post",
//             url:"ajax/users/register",
//             async : false,
//             data:{registerType:1,mobile:mobile,_token:token},
//             dataType: "json" 
//         })
//             .fail(function(XMLHttpRequest, textStatus, errorThrown) {
//                 if(textStatus == 'error'){
//                     var errors = XMLHttpRequest.responseJSON.errors;
//                     //message = errors[0].message;
//                     $.each(errors,function (name,val) {
//                         $('#jcphone').text(val.message);
//                         $('#jcphone').show();
//                     })
//                 }
//             })
// });
// //判断密码
// $('#pwdck').blur(function () {
//     information = true;
//     var pwdck = $('#pwdck').val();
//     $('#jcpwd').hide();
//     if(pwdck == '') {
//         information = false;
//         message = '密码不能为空';
//         $('#jcpwd').text(message);
//         $('#jcpwd').show();
//     }
// });
// $('#confirmpwdck').blur(function () {
//     information = true;
//     var confirmpwdck = $('#confirmpwdck').val();
//     var pwdck = $('#pwdck').val();
//     $('#jcpwdck').hide();
//     if(confirmpwdck == '') {
//         information = false;
//         message = '请再次输入密码';
//         $('#jcpwdck').text(message);
//         $('#jcpwdck').show();
//     }
//     if(pwdck!==confirmpwdck){
//         information = false;
//         message = '两次密码不一致';
//         $('#jcpwdck').text(message);
//         $('#jcpwdck').show();
//     }
// });
// //个人信息注册提交
// $('#phonesm').click(function () {
//     var phone = $('#phoneck').val();
//     var confirmpwdck = $('#pwdck').val();
//     var uid = $('#uid').val();
//     var message = '';
//     var token = $("input[name = '_token']").val();
//     var verifycode = $('#messageCheck').val();
//     //验证短信验证码不为空
//     if(verifycode == ''){
//         alert('短信验证码不能为空');
//         information = false;
//     }
//     if(information){
//         var data =$('#registerinfo').serialize();
//         $.ajax({
//             type:"post",
//             url:"ajax/users/information",
//             async : false,
//             data:{registerType:5,phone:phone,_token:token,uid:uid,password:confirmpwdck,verifycode:verifycode},
//             dataType: "json",
//             success:function(data) {
//                 location.href = 'authcallback?uid='+uid;
//             }
//         })
//             .fail(function(XMLHttpRequest, textStatus, errorThrown) {
//                 if(textStatus == 'error'){
//                     var errors = XMLHttpRequest.responseJSON.errors;
//                     //message = errors[0].message;
//                     $.each(errors,function (name,val) {
//                         alert(val.message);
//                     })
//                 }
//             })
//     }else{
//         alert('请校检参数在提交')
//     }
// });
// 获取短信验证码
$("#sendsmscodews").click(function () {
    var mobile = $("#mobile").val();
    var token = $("input[name = '_token']").val();
    $.ajax({
        type:"post",
        url:"ajax/sendsms",
        data:{mobile:mobile,isTable:2,template:'registered',_token:token},
        dataType: "json"
    }).fail(function(XMLHttpRequest, textStatus, errorThrown) {
        if(textStatus == 'error'){
            if( XMLHttpRequest.status == 400){
                var errors = XMLHttpRequest.responseJSON.errors;
                $.each(errors,function (name,val) {
                    dialogcom_wrong(val);
                })
            }else{
                dialogcom_wrong(XMLHttpRequest.responseJSON.description);
            }
        }else{
            addCookie("secondsremained",60,60);//添加cookie记录,有效时间60s
            settime($("#sendsmscodews"));//开始倒计时
        }
    });
});