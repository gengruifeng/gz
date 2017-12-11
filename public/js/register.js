$(function () {
    // 开始倒计时
    settime($("#sendsmscode"));
});
// 获取短信验证码
$("#sendsmscode").click(function () {
    var mobile = $("#mobile").val();
    var token = $("input[name = '_token']").val();
    $.ajax({
        type:"post",
        url:"/ajax/sendsms",
        data:{mobile:mobile,isTable:2,template:'registered',_token:token},
        dataType: "json"
    }).fail(function(XMLHttpRequest, textStatus) {
        $('.red-text').remove();
        if(textStatus == 'error'){
            if( XMLHttpRequest.status == 400){
                var errors = XMLHttpRequest.responseJSON.errors;
                $.each(errors,function (name,vale) {
                    $("input[name=" + vale.input + "]").parent().after('<p id="'+name+'-error" class="red-text">'+vale.message+'</p>')
                })
            }else{
                var errors = XMLHttpRequest.responseJSON.errors;
                $.each(errors,function (name,vale) {
                    $("input[name=verifycode]").parent().after('<p id="verifycode-error" class="red-text">'+vale+'</p>')
                })
            }
        }else{
            document.cookie = "secondsremained" + "=60";
            settime($("#sendsmscode"));
        }
    });
});

function selecttag(th) {
    var leg = $("#grful input[type='checkbox']:checked").length;
    if(leg >= 10){
        dialogcom_warn('最多可以选择9个标签！');
        $(th).find('input').removeAttr('checked');
    }else{
        var reg=/onClick/g;
        if(reg.test($(th).attr('class'))){
            $(th).removeClass('onClick');
            $(th).find('input').removeAttr('checked');
        }else{
            $(th).addClass('onClick');
            $(th).find('input').attr('checked','checked');

        }
    }
}


// 擅长领域添加
function begoodat(uid){
    var tags=[];
    $(".panes .pane a input[type=checkbox]").each(function(){
        if(this.checked){
            var tag = $.inArray($(this).val(),tags);
            if(tag < 0){
                tags.push($(this).val());
            }
        }
    });
    if(tags != ""){
        var token = $("input[name = '_token']").val();
        $.ajax({
            type:"post",
            url:"/ajax/users/register",
            data:{uid:uid,tagIds:tags,_token:token,registerType:7},
            dataType: "json"
        }).fail(function(XMLHttpRequest, textStatus) {
            $('.red-text').remove();
            if(textStatus != 'error'){
                 location.href="/"
            }else{
                var errormsg = XMLHttpRequest.responseJSON.description;
                $(".panes ").after('<p class="red-text" style="bottom: 80px">'+errormsg+'</p>');
            }
        });
    }else{
        location.href="/"
    }
}