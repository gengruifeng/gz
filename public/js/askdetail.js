var url = window.location.protocol+'//'+window.location.host;
//上传图片
var token = $("input[name = '_token']").val();
var imditor = new Simditor({
    textarea: $('#editor'),
    placeholder: '请输入...',
    defaultImage: '',
    upload: {
        url: '/ajax/questions/askupload',
        params: {_token:token},
        fileKey: 'file',
        connectionCount: 3,
        leaveConfirm: '正在上传图片，您确定要终止吗？'
    },
    tabIndent: false,
    toolbar: [
        'title',
        'bold',
        'italic',
        'underline',
        'image',
    ],
    toolbarFloat: true,
    toolbarFloatOffset: 0,
    toolbarHidden: false,
    pasteImage: true,
});
$(document).ready(function() {
    var token = $("input[name = '_token']").val();
    var askid = $("#askid").val();
    $.ajax({
        type:"post",
        url:"/ajax/questions/answerslist",
        data:{askType:6,askid:askid,_token:token},
        dataType: "json",
        success:function(data) {
            var st = "";
            var jsonobj=eval(data);
            for(var i=0;i<jsonobj.length;i++){

                st +="<li id='answersdel"+jsonobj[i].answers_id+"'> <div> <div>";
                st +=" <a href='javascript:void(0)'>";
                st += "<img class='rich-avatar' data-card-url='/users/card/"+jsonobj[i].answersuid+"'  src='"+url+"/avatars/60/"+jsonobj[i].answersavatar+"'/></a>";
                if(jsonobj[i].votestatus==1){
                    st +=" </div> <div><a href='javascript:void(0)'  onclick='isLogin(\"vote("+jsonobj[i].answers_id+","+jsonobj[i].answersuid+")\",2)' id='votes"+jsonobj[i].answers_id+"' >"+jsonobj[i].vote_up+"</a>";
                }else if(jsonobj[i].votestatus==2){
                    st +=" </div> <div><a href='javascript:void(0)'  onclick='isLogin(\"vote("+jsonobj[i].answers_id+","+jsonobj[i].answersuid+")\",2)' id='votes"+jsonobj[i].answers_id+"'   class='active'>"+jsonobj[i].vote_up+"</a>";
                }
                st +=" </div> </div> <div> <div>";
                st +="<a href='/profile/"+jsonobj[i].answersuid+"' class='rich-avatar' data-card-url='/users/card/"+jsonobj[i].answersuid+"' data-text='"+jsonobj[i].answersuid+"' >"+jsonobj[i].answersname+"</a> </div> <div>";
                if(jsonobj[i].corporate!=undefined){
                    st +=" <a href='javascript:void(0)'>"+jsonobj[i].corporate+"</a>";
                }else{
                    st +="<a href='javascript:void(0)'></a>";
                }
                if(jsonobj[i].position!=undefined){
                    st +=" <a href='javascript:void(0)'>"+jsonobj[i].position+"</a> ";
                }else {
                    st +=" <a href='javascript:void(0)'></a>";
                }
                st +="  </div><div id='edit"+jsonobj[i].answers_id+"'> ";
                st +="<div> "+jsonobj[i].detail+" </div>";
                st +=" </div> <div> <p>";
                st +="  <a onclick='discuss("+jsonobj[i].answers_id+","+jsonobj[i].answersuid+")' class='discuss' href='javascript:void(0)' id='pinglunnum"+jsonobj[i].answers_id+"' >评论("+jsonobj[i].commented+")</a> ";
                // st +=" <a class='share' href='javascript:void(0)'>分享</a>  |";
                if(jsonobj[i].answersuserstatus==1){
                    st +=" <a onclick='edit(event,"+jsonobj[i].answers_id+")' id='edit_1"+jsonobj[i].answers_id+"' href='javascript:void(0)' class='' >编辑</a>";
                    st +=" <a onclick='offEdit(event,"+jsonobj[i].answers_id+")' id='offEdit"+jsonobj[i].answers_id+"' class='display' href='javascript:void(0)'>取消编辑" +
                        "</a> <a href='javascript:void(0)' onclick='answeredel("+jsonobj[i].answers_id+")' class='' >删除</a> ";
                }else{
                    st +="<a href='javascript:void(0)' class='display' ></a>";
                    st +="<a  href='javascript:void(0)' class='display'></a><a href='javascript:void(0)'  class='display'></a> ";
                }
                st +=" <a href='javascript:void(0)'>"+jsonobj[i].created_at+"</a> </p> </div>";
                st +="<div></div>"
                st +="<div id='discuss"+jsonobj[i].answers_id+"'  class='pingadd' > </div>";
                st +=" </div> </li>";
            }
            if (203 != jsonobj.error_id) {
                $('#answerpage').val(jsonobj[0].page);

            }

            $("#answerslist").append(st);
        }, error: function(xhr, status, error) {
            if (404 === xhr.status) {
            }
        }
    })
});
//关注
$('#stared').click(function () {
    isLogin('stared()',2);
});
function stared() {
    var token = $("input[name = '_token']").val();
    var askid = $("#askid").val();
    var askuid = $("#askuid").val();
    $("#stared").attr('disabled',true);
    $.ajax({
        type:"post",
        url:"/ajax/questions/stared",
        data:{askType:8,ask_id:askid,askuid:askuid,_token:token},
        dataType: "json",
        success:function(data) {
            console.log(data)
            var jsonobj=eval(data);
            if(jsonobj.status=="取消"){
                $(".question-operation button#stared").addClass('btn-attion-cancel');
                $("#stared").html('取消关注')
            }else if(jsonobj.status=="关注"){
                $("#stared").html('关注')
                $(".question-operation button#stared").removeClass('btn-attion-cancel');
            }
            // console.log(jsonobj.stared)
            $('#staredNumber').html(jsonobj.stared+"人关注");

            $("#stared").attr('disabled',false);
        },error: function(xhr, status, error) {
            if(xhr.status == 401){
                dialoglogin();
                return false;
            }
            if(xhr.status == 403){
                dialogcom_wrong('页面停留时间过长请刷新页面后再操作!');
                return false;
            }
            if (400 === xhr.status) {
                dialogcom_wrong(xhr.responseJSON.errors[0].message);
            } else {
                dialogcom_wrong(xhr.responseJSON.description);
            }
        }
    })
}
//点赞
function vote(answers_id,answersuid) {
    var token = $("input[name = '_token']").val();
    var askid = $("#askid").val();
    $.ajax({
        type:"post",
        url:"/ajax/questions/voteup",
        data:{askType:8,ask_id:askid,answers_id:answers_id,up:1,answersuid:answersuid,_token:token},
        dataType: "json",
        success:function(data) {
            var jsonobj=eval(data);
            $("#votes"+answers_id).html(jsonobj.vote_up);
            $("#votes"+answers_id).addClass("active");

        },
        error: function(xhr, status, error) {
            var type = true;
            if(xhr.status == 401){
                dialoglogin();
                type = false;
            }
            if(xhr.status == 403){
                dialogcom_wrong('页面停留时间过长请刷新页面后再操作!');
                type =  false;
            }
            if(type ==true ){
                if (400 === xhr.status) {
                    dialogcom_wrong(xhr.responseJSON.errors[0].message);
                } else {
                    dialogcom_wrong(xhr.responseJSON.description);
                }
            }

        }
    })
}
//添加回答
$('#tijiao').click(function () {
    var editor =  $('#editor').val();
    var token = $("input[name = '_token']").val();
    var askid = $("#askid").val();
    var askuid = $("#askuid").val();
    $.ajax({
        type:"post",
        url:"/ajax/questions/answers",
        data:{askType:9,ask_id:askid,askuid:askuid,editor:editor,_token:token},
        dataType: "json",
        success:function(data) {
            var st = '';
            var jsonobj=eval(data);
            st +=" <div> <div>";
            st +=" <a href='javascript:void(0)'>";
            st +="<img class='rich-avatar' data-card-url='/users/card/"+jsonobj.answersuid+"'  src='"+url+"/avatars/60/"+jsonobj.answersavatar+"'/></a>";
            if(jsonobj.votestatus==1){
                st +=" </div> <div><a href='javascript:void(0)'  onclick='vote("+jsonobj.answers_id+","+jsonobj.answersuid+")' id='votes"+jsonobj.answers_id+"'>"+jsonobj.vote_up+"</a>";
            }else if(jsonobj.votestatus==2){
                st +=" </div> <div><a href='javascript:void(0)' class='active' onclick='vote("+jsonobj.answers_id+","+jsonobj.answersuid+")' id='votes"+jsonobj.answers_id+"'>"+jsonobj.vote_up+"</a>";
            }

            st +=" </div> </div> <div> <div>";
            st +="<a href='/profile/"+jsonobj.answersuid+"' class='rich-avatar' data-card-url='/users/card/"+jsonobj.answersuid+"' data-text='"+jsonobj.answersuid+"' >"+jsonobj.answersname+"</a> </div> <div> ";

            if(jsonobj.corporate!=undefined){
                st +=" <a href='javascript:void(0)'>"+jsonobj.corporate+"</a>";
            }else{
                st +="<a href='javascript:void(0)'></a>";
            }
            if(jsonobj.position!=undefined){
                st +=" <a href='javascript:void(0)'>"+jsonobj.position+"</a> ";
            }else {
                st +=" <a href='javascript:void(0)'></a>";
            }

            st +=" </div> <div id='edit"+jsonobj.answers_id+"'> ";
            st +="<div> "+jsonobj.detail+" </div>";
            st +=" </div> <div> <p>";
            st +="  <a onclick='discuss("+jsonobj.answers_id+","+jsonobj.answersuid+")' class='discuss' href='javascript:void(0)' id='pinglunnum"+jsonobj.answers_id+"'>评论("+jsonobj.commented+")</a> ";
            //st +=" <a class='share' href='javascript:void(0)'>分享</a> ";
            if(jsonobj.answersuserstatus==1){
                st +=" <a onclick='edit(event,"+jsonobj.answers_id+")' id='edit_1"+jsonobj.answers_id+"' href='javascript:void(0)' class='' >编辑</a>";
                st +=" <a onclick='offEdit(event,"+jsonobj.answers_id+")' id='offEdit"+jsonobj.answers_id+"' class='display' href='javascript:void(0)'>取消编辑</a> <a href='javascript:void(0)' onclick='answeredel("+jsonobj.answers_id+")' class='' > 删除</a> ";
            }else{
                st +="<a href='javascript:void(0)' class='display'></a>";
                st +=" <a class='display' href='javascript:void(0)' class='display'></a><a href='javascript:void(0)' ></a> ";
            }
            st +=" <a href='javascript:void(0)'>"+jsonobj.created_at+"</a> </p> </div>";
            st +="<div></div>"
            st +="<div id='discuss"+jsonobj.answers_id+"'  class='pingadd' > </div>";
            st +=" </div>";
            if($("#answerslist").find("li").length > 0){
                $("<li id='answersdel"+jsonobj.answers_id+"'>"+st+"</li>").insertBefore("#answerslist li:first");
            }else{
                $("#answerslist").append("<li id='answersdel"+jsonobj.answers_id+"'>"+st+"</li>");
            }
            $("#noneimg").removeClass('noneData');
            imditor.setValue('');
            $("#answerednum").html(jsonobj.answered+"个回答");
            $('#answerslist').append("<input type='hidden' name='answer_id[]' value='"+jsonobj.answers_id+"'/>");
            $('#btm').append("<div class='btmTips'>一个问题只能回答一次，但是你可以编辑自己回答的内容。</div>");
            $('#discuss_11').hide();
        },error: function(xhr, status, error) {
            var type = true;
            if(xhr.status == 401){
                dialoglogin();
                 type = false;
            }
            if(xhr.status == 403){
                dialogcom_wrong('页面停留时间过长请刷新页面后再操作!');
                type = false;
            }
            if(type==true){
                dialogcom_wrong(xhr.responseJSON.description);
            }
        }
    })
});
//问答删除
function askdel(ask_id) {
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
                        var askid = $("#askid").val();
                        $.ajax({
                            type:"post",
                            url:"/ajax/questions/askdel",
                            data:{ask_id:ask_id,_token:token},
                            dataType: "json",
                            success:function(data) {
                                dialogcom_yes('删除成功');
                                location.href='/questions';
                            },error: function(xhr, status, error) {
                                var type = true;
                                if(xhr.status == 401){
                                    dialoglogin();
                                    type = false;
                                }
                                if(xhr.status == 403){
                                    dialogcom_wrong('页面停留时间过长请刷新页面后再操作!');
                                    type = false;
                                }
                                if(type == true ) {
                                    if (400 === xhr.status) {
                                        dialogcom_wrong(xhr.responseJSON.errors[0].message);
                                    } else {
                                        dialogcom_wrong(xhr.responseJSON.description);
                                    }
                                }
                            }
                        })
                    }
                }
            ]
        });
    });
}
//问答删除
function answeredel(answer_id) {
    $(function () {
        $("#dialog").dialog({
            modal:true,
            // width:440,
            // height:180,
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
                        var askid = $("#askid").val();
                        $.ajax({
                            type:"post",
                            url:"/ajax/questions/answeredel",
                            data:{answer_id:answer_id,ask_id:askid,_token:token},
                            dataType: "json",
                            success:function(data) {
                                $('.btmTips').addClass('hide');
                                dialogcom_yes('删除成功');
                                $("#answersdel"+answer_id).remove();
                                $('#discuss_11').show();
                                $("#answerednum").html(data.answered+"个回答");
                            },error: function(xhr, status, error) {
                                var type = true;
                                if(xhr.status == 401){
                                    dialoglogin();
                                    type = false;
                                }
                                if(xhr.status == 403){
                                    dialogcom_wrong('页面停留时间过长请刷新页面后再操作!');
                                    type = false;
                                }
                                if(type == true ) {
                                    if (400 === xhr.status) {
                                        dialogcom_wrong(xhr.responseJSON.errors[0].message);
                                    } else {
                                        dialogcom_wrong(xhr.responseJSON.description);
                                    }
                                }
                            }
                        })
                    }
                }
            ]
        });
    });
}
//问答编辑
function answereup(answer_id) {
    var answer =  $("#editor_"+answer_id).val();
    var token = $("input[name = '_token']").val();
    $.ajax({
        type:"post",
        url:"/ajax/questions/answereup",
        data:{askType:9,answer_id:answer_id,detail:answer,_token:token},
        dataType: "json",
        success:function(data) {
            var str = '';
            var jsonobj=eval(data);
            $("#edit_1"+answer_id).removeClass('display');
            $("#offEdit"+answer_id).addClass('display');
            $("#edit"+answer_id).empty();
            $("#edit"+answer_id).append("<div>"+jsonobj.detail+"</div>");
            $("#edit"+answer_id+" div").removeClass('display');
            dialogcom_yes('编辑成功！')
        },error: function(xhr, status, error) {
            var type = true;
            if(xhr.status == 401){
                dialoglogin();
                type = false;
            }
            if(xhr.status == 403){
                dialogcom_wrong('页面停留时间过长请刷新页面后再操作!');
                type = false;
            }
            if(type == true ) {
                if (400 === xhr.status) {
                    dialogcom_wrong(xhr.responseJSON.errors[0].message);
                } else {
                    dialogcom_wrong(xhr.responseJSON.description);
                }
            }
        }
    })
}

//添加评论
function commented(answer_id,answers_uid) {
    var detail = $("#pltext"+answer_id).val();
    var token = $("input[name = '_token']").val();
    $.ajax({
        type:"post",
        url:"/ajax/questions/commented",
        data:{askType:9,answer_id:answer_id,answers_uid:answers_uid,detail:detail,_token:token},
        dataType: "json",
        success:function(data) {
            var jsonobj=eval(data);
            var str = '';
            str += "<li class='clearfix' id='offLiBig"+jsonobj.comment_id+"'> <div class='pingadd-hd'> <a href='/profile/"+jsonobj.comment_uid+"'><img class='rich-avatar' data-card-url='/users/card/"+jsonobj.comment_uid+"'  src='"+url+"/avatars/60/"+jsonobj.avatar+"' alt=''></a> </div> <div class='pingadd-tp clearfix'> <a href='/profile/"+jsonobj.comment_uid+"' data-text='"+jsonobj.comment_uid+"' class='rich-avatar fl' data-card-url='/users/card/"+jsonobj.comment_uid+"' >"+jsonobj.username+"</a> <span class='fl'>"+jsonobj.created_at+"</span>" ;
            str += " <a href='javascript:;' onclick='offLiBig("+jsonobj.comment_id+','+answer_id+")' class='fr'>删除</a>" ;
            str += "</div> <p>"+jsonobj.content+"</p> </li>";
            $(".tp").addClass('hide');
            $("#pltext"+answer_id).val('');
            $("#pltianjia"+answer_id).append(str);
            $("#pinglunnum"+answer_id).text("评论（"+jsonobj.commented+"）");
        },error: function(xhr, status, error) {
            var type = true;
            if(xhr.status == 401){
                dialoglogin();
                type = false;
            }
            if(xhr.status == 403){
                dialogcom_wrong('页面停留时间过长请刷新页面后再操作!');
                type = false;
            }
            if(type == true ) {
                if (400 === xhr.status) {
                    dialogcom_wrong(xhr.responseJSON.errors[0].message);
                } else {
                    dialogcom_wrong(xhr.responseJSON.description);
                }
            }
        }
    })
}

//点击评论显示评论列表开始
function discuss(answer_id,answersuid){
    var token = $("input[name = '_token']").val();
    $.ajax({
        type:"post",
        url:"/ajax/questions/commentedlist",
        data:{answer_id:answer_id,answersuid:answersuid,_token:token},
        dataType: "json",
        success:function(jsonobj) {
            var userimg = $("#userimg").attr('src');
            var str = "";
            str +=" <div class='btm'> " +
                " <div class='clearfix'>" +
                    "<div class='fl pinghead'>" +
                    "<img src='"+userimg+"' alt=''>" +
                    "</div>" +
                    "<input class='fl' type='text' id='pltext"+answer_id+"' placeholder='评论一下...'>" +
                "</div>" +
                    "<div class='btns clearfix'>" +
                    "<a href='javascript:;' class='fr'  onclick='isLogin(\"commented("+answer_id+","+answersuid+")\",2)'>评论</a>" +
                    " <a id='offDiscuss' href='javascript:;' class='fr' onclick='offDiscuss("+answer_id+")' >取消</a> " +
                    "</div>" +
                " </div> " ;
                str +="<div class='tp hide'>暂无评论</div>";
            str += "<ul class=''  id='pltianjia"+answer_id+"'>";
            for(var i=0;i<jsonobj.length;i++){
                str += "<li class='clearfix' id='offLiBig"+jsonobj[i].comment_id+"'> <div class='pingadd-hd'> <a href='/profile/"+jsonobj[i].comment_uid+"'><img class='rich-avatar' data-card-url='/users/card/"+jsonobj[i].comment_uid+"' src='"+url+"/avatars/60/"+jsonobj[i].avatar+"' alt=''></a> </div> <div class='pingadd-tp clearfix'> <a href='/profile/"+jsonobj[i].comment_uid+"' data-text='"+jsonobj[i].comment_uid+"' class='rich-avatar fl' data-card-url='/users/card/"+jsonobj[i].comment_uid+"' >"+jsonobj[i].username+"</a> <span class='fl'>"+jsonobj[i].created_at+"</span>" ;
                if(jsonobj[i].commentedstatus==1){
                    str += " <a href='javascript:;' onclick='offLiBig("+jsonobj[i].comment_id+','+answer_id+")' class='fr'>删除</a>" ;
                }
                str += "</div> <p>"+jsonobj[i].content+"</p> </li>";
            }

            str += "</ul>";
            $("#discuss"+answer_id).html(str);
            if(1>jsonobj.length){
                $('.tp').removeClass('hide');
            }
            // $("#discuss"+answer_id).fadeToggle();
            if($("#discuss"+answer_id).is(':visible')){
                $("#discuss"+answer_id).hide();
            }else{
                $("#discuss"+answer_id).show();
            }
        },error: function(xhr, status, error) {
            var type = true;
            if(xhr.status == 401){
                dialoglogin();
                type = false;
            }
            if(xhr.status == 403){
                dialogcom_wrong('页面停留时间过长请刷新页面后再操作!');
                type = false;
            }
            if(type == true ) {
                if (400 === xhr.status) {
                    dialogcom_wrong(xhr.responseJSON.errors[0].message);
                } else {
                    dialogcom_wrong(xhr.responseJSON.description);
                }
            }
        }
    })
}
//
//点击评论显示评论列表结束
//编辑
function edit(e,answer_id){
    var text=$("#edit"+answer_id+" div").html();
    $("#edit"+answer_id+" div").addClass('display');
    $(e.target).addClass('display');
    $("#offEdit"+answer_id).removeClass('display');
    var textarea_1=$("<textarea id='editor_"+answer_id+"'></textarea><button style='width:82px;height:32px;text-align:center;line-height:32px;font-size:12px;color:white;background:#f87e6a;float:right;outline:none;border:none;border-radius:6px;' onclick='answereup("+answer_id+")'>提交</button>")
    $("#edit"+answer_id).append(textarea_1);
    var token = $("input[name = '_token']").val();
    var editor = new Simditor({
        textarea: $("#editor_"+answer_id),
        placeholder: '请输入',
        defaultImage: '',
        upload: {
            url: '/ajax/questions/askupload',
            params: {_token:token},
            fileKey: 'file',
            connectionCount: 3,
            leaveConfirm: 'Uploading is in progress, are you sure to leave this page?'
        },
        tabIndent: false,
        toolbar: [
            'title',
            'bold',
            'italic',
            'underline',
            'image',
        ],
        toolbarFloat: true,
        toolbarFloatOffset: 0,
        toolbarHidden: false,
        pasteImage: true,
    });
    editor.setValue(text);
}
//取消编辑
function offEdit(e,answer_id){

    $(e.target).addClass('display');
    $("#edit_1"+answer_id).removeClass('display');
    var text=$("#edit"+answer_id+" div").html();
    $("#edit"+answer_id).empty();
    $("#edit"+answer_id).append("<div>"+text+"</div>");
    $("#edit"+answer_id+" div").removeClass('display');

}
//评论删除
function offLiBig(comment_id,answer_id){
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
                    var askid = $("#askid").val();
                    $.ajax({
                        type:"post",
                        url:"/ajax/questions/commenteddel",
                        data:{comment_id:comment_id,answer_id:answer_id,_token:token},
                        dataType: "json",
                        success:function(data) {
                            dialogcom_yes('删除成功');
                            $("#offLiBig"+comment_id).remove();
                            $("#pinglunnum"+answer_id).text("评论（"+data.commented+"）");
                            if(1>data.commented){
                                $('.tp').removeClass('hide');
                            }else{
                                $('.tp').addClass('hide');
                            }

                        },error: function(xhr, status, error) {
                            var type = true;
                            if(xhr.status == 401){
                                dialoglogin();
                                type = false;
                            }
                            if(xhr.status == 403){
                                dialogcom_wrong('页面停留时间过长请刷新页面后再操作!');
                                type = false;
                            }
                            if(type == true ) {
                                if (400 === xhr.status) {
                                    dialogcom_wrong(xhr.responseJSON.errors[0].message);
                                } else {
                                    dialogcom_wrong(xhr.responseJSON.description);
                                }
                            }
                        }
                    })

                }
            }
        ]
    });
}

//发布评论取消标记开始
function offDiscuss(answer_id) {
    // $('#discuss'+answer_id).fadeToggle();
    if($("#discuss"+answer_id).is(':visible')){
        $("#discuss"+answer_id).hide();
    }else{
        $("#discuss"+answer_id).show();
    }
}


//邀请他人回答开始
$('.inviteAt').on('click', function() {
    isLogin('inviteat()',2);

});
function inviteat() {
    $('#search-users').val('');
    var t = $('.inviteAt').offset().top;
    var l = $('.inviteAt').offset().left;
    $('#invite').css({
        'top': t + 30 + 'px',
        'left': l + 'px'
    });
    var token = $("input[name = '_token']").val();
    var askid = $('#askid').val();
    $.ajax({
        type:"post",
        url:"/ajax/questions/invitations",
        data:{askid:askid,_token:token},
        dataType: "json",
        success:function(data) {
            var jsonobj = data.invitations;
            var str = '';
            for(var i=0;i<jsonobj.length;i++){
                str +="<li> ";
                str +="<div> <a href='/profile/"+jsonobj[i].id+"'><img src='"+url+"/avatars/60/"+jsonobj[i].avatar+"'/> </a> </div> <div> <div> <a href='/profile/"+jsonobj[i].id+"'>"+jsonobj[i].display_name+"</a><span>"+jsonobj[i].corporate+"</span> <span>"+jsonobj[i].position+"</span> </div> <div> <span>"+jsonobj[i].reputation+" 点赞</span> <span >"+jsonobj[i].answer+" 回答</span> <span>"+jsonobj[i].question+" 提问</span> </div> </div> <div> ";
                if(jsonobj[i].invitations==1){
                    str +="<a href='javascript:void(0)' data-img='"+jsonobj[i].avatar+"' onclick='invitationsadd("+jsonobj[i].id+")' id='invitations"+jsonobj[i].id+"'>邀请回答</a> </div>";
                }else{
                    str +="<a href='javascript:void(0)'>已邀请</a> </div>";
                }
                str += "</li>";
            }
            // $('#invite').fadeToggle();
            if($('#invite').is(":visible")){
                $('#invite').hide();
                $('#bdopacit').hide();
            }else{
                $('#invite').show();
                $('#invite').css('z-index','5')
                $('#bdopacit').show();
            };
            $('#userinvitations').html(str);
            $('#userpage').val(1);
            // $('#userpage').val(jsonobj[0].page);
        },error: function(xhr, status, error) {
            if (400 === xhr.status) {
                dialogcom_wrong(xhr.responseJSON.errors[0].message);
            } else {
                if($('#invite').is(":visible")){
                    $('#invite').hide();
                    $('#bdopacit').hide();
                }else{
                    $('#invite').show();
                    $('#invite').css('z-index','5')
                    $('#bdopacit').show();
                };}
        }
    })
}
$('#bdopacit').on('click',function(){
    $('#invite').hide();
    $('#bdopacit').hide();
});
function  userjiazai() {
    var token = $("input[name = '_token']").val();
    var askid = $('#askid').val();
    var num = $('#userpage').val();
    num = parseInt(num)+1;
    $.ajax({
        type:"post",
        url:"/ajax/questions/invitations",
        data:{askid:askid,_token:token,page:num},
        dataType: "json",
        success:function(data) {
            var jsonobj = data.invitations;
            var str = '';
            for(var i=0;i<jsonobj.length;i++){
                str +="<li> ";
                str +="<div> <a href='/profile/"+jsonobj[i].id+"'><img src='"+url+"/avatars/60/"+jsonobj[i].avatar+"'/> </a> </div> <div> <div> <a href='/profile/"+jsonobj[i].id+"'>"+jsonobj[i].display_name+"</a><span>"+jsonobj[i].corporate+"</span> <span>"+jsonobj[i].position+"</span> </div> <div> <span>"+jsonobj[i].reputation+" 点赞</span> <span >"+jsonobj[i].answer+" 回答</span> <span>"+jsonobj[i].question+" 提问</span> </div> </div> <div> ";
                if(jsonobj[i].invitations==1){
                    str +="<a href='javascript:void(0)' data-img='"+jsonobj[i].avatar+"' onclick='invitationsadd("+jsonobj[i].id+")' id='invitations"+jsonobj[i].id+"'>邀请回答</a> </div>";
                }else{
                    str +="<a href='javascript:void(0)'>已邀请</a> </div>";
                }

                str += "</li>";
            }
            $('#userinvitations').html(str);
            $('#userpage').val(jsonobj[0].page);
            $('#invite').show();
        }, error: function(xhr, status, error) {
            if (402 === xhr.status) {
                dialogcom_warn('没有推荐人的数据');
            }
        }
    })
}

function invitationsadd(invited) {
    var avatar = $("#invitations"+invited).attr('data-img');

    var askid = $('#askid').val();
    var askuid = $('#askuid').val();
    var token = $("input[name = '_token']").val();
    $.ajax({
        type:"post",
        url:"/ajax/questions/invitationsadd",
        data:{invited:invited,askuid:askuid,askid:askid,_token:token},
        dataType: "json",
        success:function(data) {
            $(".invite-person dl").append("<dt class='fl'><a href='/profile/"+invited+"'><img src='"+url+"/avatars/60/"+avatar+"' alt='head.jpg'></a></dt>")
            $("#invitations"+invited).text(data.description);
        },error: function(xhr, status, error) {
            if (400 === xhr.status) {
                dialogcom_wrong(xhr.responseJSON.errors[0].message);
            } else {
                dialogcom_wrong(xhr.responseJSON.description);
            }
        }
    })
}
//邀请他人回答结束
//邀请你回答
//人物名片开始
$(function() {
var cache = {};

    $("#search-question").autocomplete({
        minLength: 2,
        source: function (request, response) {
            var q = request.term;

            $.getJSON("/search", {q: request.term}, function (data, status, xhr) {
                cache[q] = data;
                response(data);
            });
        },
        select: function (event, ui) {
            window.location.href = "/questions/" + ui.item.id;
        }
    });
    //邀请人
    $("#search-users").autocomplete({
    	minLength: 2,
    	source: function (request, response) {
    		var q = request.term;
    		$.getJSON("/users/search", {q: request.term}, function (data, status, xhr) {
                $('#userinvitations').html('');
    			cache[q] = data;
                var askid = $('#askid').val();
                    $.ajax({
                        type:"post",
                        url:"/ajax/questions/search",
                        data:{data:data,askid:askid,_token:token},
                        dataType: "json",
                        success:function(jsonobj) {
                            var str = '';
                            for(var i=0;i<jsonobj.length;i++){
                                str +="<li> ";
                                str +="<div> <a href='/profile/"+jsonobj[i].id+"'><img src='"+url+"/avatars/60/"+jsonobj[i].avatar+"'/> </a> </div> <div> <div> <a href='/profile/"+jsonobj[i].id+"'>"+jsonobj[i].display_name+"</a><span>"+jsonobj[i].corporate+"</span> <span>"+jsonobj[i].position+"</span> </div> <div> <span>"+jsonobj[i].reputation+" 点赞</span> <span >"+jsonobj[i].answer+" 回答</span> <span>"+jsonobj[i].question+" 提问</span> </div> </div> <div> ";
                                if(jsonobj[i].invitations==1){
                                    str +="<a href='javascript:void(0)'  data-img='"+jsonobj[i].avatar+"' onclick='invitationsadd("+jsonobj[i].id+")' id='invitations"+jsonobj[i].id+"'>邀请回答</a> </div>";
                                }else{
                                    str +="<a href='javascript:void(0)'>已邀请</a> </div>";
                                }
                                str += "</li>";
                            }
                            $('#userinvitations').html(str);
                        }
                    });
    		});
    	},
    });
});

//回答滚动到指定区域
function click_scroll() {

    var scroll_offset = $('#discuss_11').offset();
    $('body,html').animate({
        scrollTop: parseInt(scroll_offset.top),
    });
}



