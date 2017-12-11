//个人中心加关注,fid-粉丝id
function addAttention(fid){
    var token = $("input[name = '_token']").val();
    var fansnum = parseInt($("#fansNums"+fid).html());
    $.ajax({
        type:"post",
        url:"/ajax/personal/addattention",
        data:{fid:fid,_token:token},
        dataType: "json",
        beforeSend:function(){
           $(".attention"+fid).removeAttr("onclick");
        }
    }).fail(function (XMLHttpRequest,textStatus) {
        if(XMLHttpRequest.status == 401){
            $(".attention"+fid).attr("onclick","addAttention("+fid+")");
            dialoglogin();
            return false;
        }
        if(XMLHttpRequest.status == 403){
            $(".attention"+fid).attr("onclick","addAttention("+fid+")");
            dialogcom_wrong('页面停留时间过长请刷新页面后再操作!');
            return false;
        }

        if(textStatus != 'error'){
            $("#fansNums"+fid).html(fansnum+1);
            $(".attention"+fid).text("取消关注");
            $(".attention"+fid).attr("onclick","deleteAttention("+fid+")");
            $(".attention"+fid).addClass('btn-attion-cancel');

        }
    })
}

//个人中心取消关注
function deleteAttention(fid){
    var token = $("input[name = '_token']").val();
    var fansnum = parseInt($("#fansNums"+fid).html());
    $.ajax({
        type:"post",
        url:"/ajax/personal/delattention",
        data:{fid:fid,_token:token},
        dataType: "json",
        beforeSend:function(){
            $(".attention"+fid).removeAttr("onclick");
        }
    }).fail(function (XMLHttpRequest,textStatus) {
        if(XMLHttpRequest.status == 401){
            $(".attention"+fid).attr("onclick","deleteAttention("+fid+")");
            dialoglogin();
            return false;
        }
        if(XMLHttpRequest.status == 403){
            $(".attention"+fid).attr("onclick","deleteAttention("+fid+")");
            dialogcom_wrong('页面停留时间过长请刷新页面后再操作!');
            return false;
        }
        if(textStatus != 'error'){
            if(fansnum > 0){
                $("#fansNums"+fid).html(fansnum-1);
            }
            $(".attention"+fid).text("加关注");
            $(".attention"+fid).attr("onclick","addAttention("+fid+")");
            $(".attention"+fid).addClass('btn-attion');
            $(".attention"+fid).removeClass('btn-attion-cancel');
        }
    })
}


//他人关注页面取消关注
function delOtherAttention(fid){
    var token = $("input[name = '_token']").val();
    var fansnum = parseInt($("#fansNums"+fid).html());
    $.ajax({
        type:"post",
        url:"/ajax/personal/delattention",
        data:{fid:fid,_token:token},
        dataType: "json",
        beforeSend:function(){
            $(".attention"+fid).removeAttr("onclick");
        }
    }).fail(function (XMLHttpRequest,textStatus) {
        if(XMLHttpRequest.status == 401){
            $(".attention"+fid).attr("onclick","deleteAttention("+fid+")");
            dialoglogin();
            return false;
        }
        if(XMLHttpRequest.status == 403){
            $(".attention"+fid).attr("onclick","deleteAttention("+fid+")");
            dialogcom_wrong('页面停留时间过长请刷新页面后再操作!');
            return false;
        }
        if(textStatus != 'error'){
            if(fansnum > 0){
                $("#fansNums"+fid).html(fansnum-1);
            }
            $(".attention"+fid).text("加关注");
            $(".attention"+fid).removeClass("btn-attion");
            $(".attention"+fid).attr("onclick","addAttention("+fid+")");
        }
    })
}

//我的关注页面取消关注
function delAttention(fid){
    var token = $("input[name = '_token']").val();
    $.ajax({
        type:"post",
        url:"/ajax/personal/delattention",
        data:{fid:fid,_token:token},
        dataType: "json"
    }).fail(function (XMLHttpRequest,textStatus) {
        if(XMLHttpRequest.status == 401){
            dialoglogin();
            return false;
        }
        if(XMLHttpRequest.status == 403){
            dialogcom_wrong('页面停留时间过长请刷新页面后再操作!');
            return false;
        }
        if(textStatus != 'error'){
            if($("#fansNum").text() >0){
                $("#fansNum").text($("#fansNum").text()-1);
            }
            $("#attention"+fid).remove();
        }
    })
}

function foucus_1(id) {
    $(id).on('focus',function () {
        $(this).css({
            'border':'1px solid #dfdfdf'
        });
    });
}

//发私信
function addDialog(){
    var flag=true;
    var message = $("#privateLetterMsg").val();
    var recipient = $("#privateMsgId").val();
    var token = $("input[name = '_token']").val();
    if(message == ""){
        flag = false;
        $('#privateLetterMsg').css({
            'borderColor':'#ffeecc'
        });
    }
    if(recipient == ""){
        flag = false;
        $('#privateMsgId').css({
            'borderColor':'#ffeecc'
        });
    }
    if(flag){
        $.ajax({
            type:"post",
            url:"/ajax/notice/dialogs",
            data:{recipient:recipient,message:message,_token:token},
            dataType: "json",
            beforeSend:function(){
                $("#adddialog").removeAttr("onclick");
            }
        }).always(function(XMLHttpRequest) {
            if(XMLHttpRequest.status == 401){
                $("#maskLayer").hide();
                dialoglogin();
                return;
            }
            if(XMLHttpRequest.status == 403){
                $("#maskLayer").hide();
                dialogcom_wrong('页面停留时间过长请刷新页面后再操作!');
                return;
            }
            if(XMLHttpRequest.status == 200){
                $("#privateLetterMsg").val("");
                $("#maskLayer").hide();
                dialogcom_yes('发送成功');
            }else{
                $("#maskLayer").hide();
                dialogcom_wrong(XMLHttpRequest.responseJSON.description)
            }
            $("#adddialog").attr("onclick","addDialog()");
        })
    }
}

//向TA提问
function askQuestion(uid){
    var flag=true;
    var title = $("#questioonTitle").val();
    var content = $("#questionContent").val();
    var arr=$('.item');
    var str='';
    for(var i=0;i<arr.length;i++){
        str+=arr.eq(i).text()+',';
    }
    str = str.substring(0,str.length-1);
    var token = $("input[name = '_token']").val();
    if(title == ""){
        flag = false;
        $('#questioonTitle').css({
            'borderColor':'#ffeecc'
        });
    }
    if(content == ""){
        flag = false;
        $('#questionContent').css({
            'borderColor':'#ffeecc'
        });
    }
    if(str == ""){
        flag = false;
        $('.ui-autocomplete-input').css({
            'borderColor':'#ffeecc'
        });
    }
    if(flag){
        $.ajax({
            type:"post",
            url:"/ajax/personal/askquestion",
            data:{userid:uid,title:title,content:content,tags:str,_token:token},
            dataType: "json",
            beforeSend:function(){
                $("#askquestion").removeAttr("onclick");
            }
        }).always(function(XMLHttpRequest) {
            if(XMLHttpRequest.status == 401){
                $("#maskLayer_1").hide();
                dialoglogin();
                return;
            }
            if(XMLHttpRequest.status == 403){
                $("#maskLayer_1").hide();
                dialogcom_wrong('页面停留时间过长请刷新页面后再操作!');
                return;
            }
            if(XMLHttpRequest.status == 200){
                $("#questioonTitle").val("");
                $("#questionContent").val("");
                $("#maskLayer_1").hide();
                dialogcom_yes('发送成功');
            }else{
                $("#maskLayer_1").hide();
                dialogcom_wrong(XMLHttpRequest.responseJSON.description);
            }
            $("#askquestion").attr("onclick","askQuestion("+uid+")");
        })
    }
}

function maohash(h) {
    location.hash=h;
}