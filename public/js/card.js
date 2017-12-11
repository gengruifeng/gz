$(function() {
    ssssss = function addcardtags(uid) {
        $("#add_tags"+uid).selectize({
            maxItems: 5,
            create: function (input) {
                if (input.length > 6) {
                    dialogcom_warn("标签长度最多为6个字符！");
                    return false;
                }
                return {
                    name: input
                }
            },
            valueField: 'name',
            labelField: 'name',
            searchField: 'name',

            options: [],
            render: {
                option: function (item, escape) {
                    var tags = [];
                    for (var i = 0, n = item.length; i < n; i++) {
                        tags.push('<span>' + escape(item[i].name) + '</span>');
                    }
                    return '<div>' +
                        '<span class="name">' + escape(item.name) + '</span>' +
                        '</div>';
                }
            },
            load: function (query, callback) {
                if (!query.length) return callback();
                $.ajax({
                    url: '/tags/search',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        q: query
                    },
                    error: function () {
                        callback();
                    },
                    success: function (res) {

                        callback(res);

                    }
                });
            }
        });
    }
    
});
//人物名片结束


$(window).on('resize',function(){
    //私信遮罩层
    showOverlay("#maskLayer");
    //确认删除问题
    showOverlay("#dialog_1");
    //确认删除评论
    showOverlay("#dialog_2");
    //发送成功
    showOverlay("#dialog_3");
    //提问遮罩层
    showOverlay("#maskLayer_1");
});
function showOverlay(id) {
    $(id).height($(window).height());
    $(id).width($(window).width());
};
function sendLetter(uid) {
    var display_name = $("#cardgz"+uid).attr('data-text');
    var str = '';
    str += " <div id='writeMessage_1'> <form action=''> <div> <h3>写私信</h3></div> <div> <input id='privateMsgId' type='search' value='"+display_name+"' disabled /> </div> <div> <textarea id='privateLetterMsg' name=''></textarea> </div> <div> <a href='javascript:void(0)' onclick='addDialog("+uid+")'>发送</a> <a id='call_oof' href='javascript:void(0)'>取消</a> </div> </form></div>";
    $("#maskLayer").html(str);
    showOverlay("#maskLayer");
    $('#maskLayer').fadeIn();
}
function quiz(uid) {
    var display_name = $("#cardgz"+uid).attr('data-text');
    var str = '';
    str +="<div id='questions'> <form action=''> <div> <h3>向 <a href='javascript:void(0)'>"+display_name+"</a> 提问</h3></div> <div> <input type='search' id='questioonTitle' placeholder='标题：一句话描述问题，用问号结尾' /> <div> </div> </div> <div> <textarea id='questionContent'></textarea> </div> <div style='display: none' ></div> <div class='clearfix demo'> <input type='text' name='' id='add_tags"+uid+"' onkeyup='tagkeyup("+uid+")' placeholder='添加标签（回车键创建新标签）' /> </div> <div> <a href='javascript:void(0)' onclick='askQuestion("+uid+")'>发送</a> <a id='call_oof_1' href='javascript:void(0)'>取消</a> </div> </form> </div> ";
    $("#maskLayer_1").html(str);
    showOverlay("#maskLayer_1");
    $('#maskLayer_1').fadeIn();
}

$(function() {
    $('#maskLayer').on({
        'click': function () {
            $('#maskLayer').fadeOut();
        }
    }, '#call_oof');
    $('#maskLayer_1').on({
        'click': function () {
            $('#maskLayer_1').fadeOut();
        }
    }, '#call_oof_1');
});

//发私信
function addDialog(){
    var flag=true;
    var message = $("#maskLayer > #writeMessage_1 > form > div:nth-child(3) > textarea").val();
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
            dataType: "json"
        }).always(function(XMLHttpRequest) {
            if(XMLHttpRequest.status == 200){
                $("#maskLayer").hide();
                dialogcom_yes("发私信成功！");
            }else{
                dialogcom_wrong(XMLHttpRequest.responseJSON.description);
            }
        })
    }
}
//关注
function addAttention(fid){
    $(this).removeAttr("onclick");
    var token = $("input[name = '_token']").val();
    $.ajax({
        type:"post",
        url:"/ajax/personal/addattention",
        data:{fid:fid,_token:token},
        dataType: "json"
    }).fail(function (XMLHttpRequest,textStatus) {
        if(textStatus != 'error'){
            $("#cardgz"+fid).text("取消关注");
            $("#cardgz"+fid).attr("onclick","deleteAttention("+fid+")");
            $("#cardgz"+fid).addClass('btn-attion-cancel');

        }
    })
}
//个人中心取消关注
function deleteAttention(fid){
    $(this).removeAttr("onclick");
    var token = $("input[name = '_token']").val();
    $.ajax({
        type:"post",
        url:"/ajax/personal/delattention",
        data:{fid:fid,_token:token},
        dataType: "json"
    }).fail(function (XMLHttpRequest,textStatus) {
        if(textStatus != 'error'){
            $("#cardgz"+fid).text("关注");
            $("#cardgz"+fid).attr("onclick","addAttention("+fid+")");
            $("#cardgz"+fid).removeClass('btn-attion-cancel');

        }
    })
}
var exp = /^[a-zA-Z0-9_\u4e00-\u9fa5]{2,11}$/;

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
            dataType: "json"
        }).always(function(XMLHttpRequest) {
            if(XMLHttpRequest.status == 200){
                $("#questioonTitle").val("");
                $("#questionContent").val("");
                $("#add_tags").val("");
                $("#addTag").html("");
                $("#maskLayer_1").hide();
                dialogcom_yes('发送成功')
            }else{
                $("#maskLayer_1").hide();
                dialogcom_wrong(XMLHttpRequest.responseJSON.description)
            }
        })
    }
}
function timer(id) {
    setTimeout(function () {
        $(id).hide();
    },2000);
}

//标签添加
function tagkeyup(uid) {
    ssssss(uid);
}