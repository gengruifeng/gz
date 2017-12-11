$(function() {
    $('#writeLetter').on('click', function () {
        $("#privateMsgId").val("");
        $("#privateLetterMsg").val("");
        showOverlay("#maskLayer");
        $('#maskLayer').fadeIn();
    });
    $('#call_oof').on('click', function () {
        $('#maskLayer').fadeOut();
    });

    $(".privateLettersChange").each(function() {
        while($(this).parent().height() > 50) {
            $(this).text($(this).text().replace(/(\s)*([a-zA-Z0-9]+|\W)(\.\.\.)?$/, "..."));
        }
    })

});
//跳转私信对话页
function dialogmessage(dialogid){
    location.href = "/messages/detail/"+dialogid;
}
//私信对话页面添加私信
function sendPrivateLetter(dialogid,userid){
    var flag = true;
    var message = $("#privatemsg").val();
    var token = $("input[name = '_token']").val();
    //内容不为空
    if(message == ''){
        dialogcom_warn('请输入内容!')
        flag=false;
    }
    if(flag){
        $.ajax({
            type:"post",
            url:"/ajax/notice/addprivatemsg",
            data:{dialogid:dialogid,userid:userid,message:message,_token:token},
            dataType: "json"
        }).always(function(XMLHttpRequest) {
            if(XMLHttpRequest.status == 401){
                dialoglogin();
                return false;
            }
            if(XMLHttpRequest.status == 403){
                dialogcom_wrong('页面停留时间过长请刷新页面后再操作!');
                return false;
            }
            if(XMLHttpRequest.status == 200){
                location.reload();
            }else{
                dialogcom_wrong('XMLHttpRequest.responseJSON.description')
            }
        })
    }

}

//删除私信
function delDialog(dialogid) {
    $(function () {
        $("#dialog").dialog({
            modal:true,
            dialogClass: "no-close",
            buttons: [{
                text: "取消",
                click: function() {
                    $(this).dialog("close");
                }
            },
                {
                    text: "确定",
                    click: function() {
                        $(this).dialog("close");
                        var token = $("input[name = '_token']").val();
                        $.ajax({
                            type:"post",
                            url:"/ajax/notice/deldialog",
                            data:{dialogid:dialogid,_token:token},
                            dataType: "json"
                        }).always(function(XMLHttpRequest) {
                            if(XMLHttpRequest.status == 401){
                                dialoglogin();
                                return false;
                            }
                            if(XMLHttpRequest.status == 403){
                                dialogcom_wrong('页面停留时间过长请刷新页面后再操作!');
                                return false;
                            }
                            if(XMLHttpRequest.status == 200){
                                $("#privateMsg"+dialogid).remove();
                            }else{
                                dialogcom_wrong(XMLHttpRequest.responseJSON.description);
                            }
                        })
                    }
                }
            ]
        });
    });
}
//私信列表页面添加私信
function sendPrivateMsg(){
    var flag=true;
    var message = $("#privateLetterMsg").val();
    var recipient = $("#privateMsgId").val();
    var token = $("input[name = '_token']").val();
    if(message == ""){
        flag = false;
        dialogcom_warn('私信内容不能为空!')
    }
    if(recipient == ""){
        flag = false;
        dialogcom_warn('请选择发送人!');
    }
    if(flag){
        $.ajax({
            type:"post",
            url:"/ajax/notice/dialogs",
            data:{recipient:recipient,message:message,_token:token},
            dataType: "json"
        }).always(function(XMLHttpRequest) {
            if(XMLHttpRequest.status == 401){
                dialoglogin();
                return false;
            }
            if(XMLHttpRequest.status == 403){
                dialogcom_wrong('页面停留时间过长请刷新页面后再操作!');
                return false;
            }
            if(XMLHttpRequest.status == 200){
                $(".dialogcom_yes span").text("发送成功");
                showOverlay(".dialogcom_yes");
                $(".dialogcom_yes").show();
                $("#writeMessage_1").hide();
                setTimeout(function () {
                    location.reload();
                },2000);
            }else{
                dialogcom_wrong(XMLHttpRequest.responseJSON.description);
            }
        })   
    }
}
//点击遮罩层消失并刷新页面
$('.dialogcom_yes').click(function(){
    $(this).addClass('hide');
    location.reload();
});
var cache = {};
$("#privateMsgId").autocomplete({
    minLength: 1,
    source: function (request, response) {
        var q = request.term;
        if (q in cache) {
            var result=cache[q];
            if(result != ""){
                var str ='<ul>';
                $.each(result,function(i,val){
                    str+='<li class="writeKeyUpAtList"><a href="javascript:void(0)"><img src="../avatars/30/'+val.avatar+'" /></a><a href="javascript:void(0)">'+val.display_name+'</a></li>';
                });
                str+='</ul>';
                $("#writeKeyUpAt").html(str);
                $("#writeKeyUpAt").show();
            }else{
                $("#writeKeyUpAt").html("");
                $("#writeKeyUpAt").hide();
            }
        }
        $.getJSON("/users/searchavatar", {q: request.term}, function (data, status, xhr) {
            cache[q] = data;
            if(data != ""){
                var str ='<ul>';
                $.each(data,function(i,val){
                    str+='<li class="writeKeyUpAtList"><a href="javascript:void(0)"><img src="../avatars/30/'+val.avatar+'" /></a><a href="javascript:void(0)">'+val.display_name+'</a></li>';
                });
                str+='</ul>';
                $("#writeKeyUpAt").html(str);
                $("#writeKeyUpAt").show();
            }else{
                $("#writeKeyUpAt").html("");
                $("#writeKeyUpAt").hide();
            }
        });
    }
});

// 选择用户
$("#maskLayer").on('click','.writeKeyUpAtList',function () {
    $("#privateMsgId").val($(this).find('a:nth-child(2)').text());
    $("#writeKeyUpAt").hide();
});