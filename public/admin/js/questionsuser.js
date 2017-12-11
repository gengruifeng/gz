/**
 * Created by Administrator on 2016/8/1.  questionuser
 */
questionusertatol = 0;
questionusertatolPage = 0;
questionusercurrenpPge = 0;
questionusernexti = 0;
questionuserupi = 0;
function questionuserseachList(pcurrenpPge){

    var data =$('#questionuserFrom').serialize();
    $.ajax({
        url: $('#questionuserFrom').attr('action'),
        type: 'POST',
        dataType: 'json',
        data: data+'&currenpPge='+pcurrenpPge+'&questionuser=1',
    })
        .done(function(r) {
            var html ='';
            if(r.status == 1){
                if($(".sutenguser").length > 0){
                    $(".sutenguser").remove() ;
                }
                $.each(r.data,function (name,vale) {
                    html +='<div class="profile-activity sutenguser clearfix"><div><img class="pull-left" src="../../avatars/120/'+vale.avatar+'">';
                    html +='<a class="user" href="#"> '+vale.display_name+' </a>&nbsp;&nbsp;|&nbsp;&nbsp; '+vale.email+'&nbsp;&nbsp;|&nbsp;&nbsp;'+vale.mobile;
                    // html +='<div title="今天已经发布n次" class="time"><i class="ace-icon fa fa-check-square-o bigger-110"></i>n次</div></div>';
                    html +='</div>';
                    html +='<div class="tools action-buttons"><a href="javascript:void(0)" class="red" onclick="deluser('+vale.id+',this)"><i class="ace-icon glyphicon glyphicon-remove bigger-125"></i></a></div></div>';
                })
                $('#questionuserFrom').append(html);
                questionusertatol = r.total;
                questionusertatolPage = r.totalPage;
                questionusercurrenpPge = r.currenpPge;
                questionusernexti = r.next;
                questionuserupi = r.up;
                $('#questionusertatol').text(questionusertatol);
                $('#questionusertatolPage').text(questionusertatolPage);
                $('#questionusercurrenpPge').text(questionusercurrenpPge);
                // $('[data-rel=tooltip]').tooltip();
                // $('[data-rel=popover]').popover({html:true});
            }else{
                if($(".sutenguser").length > 0){
                    $(".sutenguser").remove() ;
                }
                html = '<div class="profile-activity sutenguser clearfix">没有记录！</div>';
                questionusertatol = r.total;
                questionusertatolPage = r.totalPage;
                questionusercurrenpPge = r.currenpPge;
                questionusernexti = r.next;
                questionuserupi = r.up;
                $('#questionusertatol').text(questionusertatol);
                $('#questionusertatolPage').text(questionusertatolPage);
                $('#questionusercurrenpPge').text(questionusercurrenpPge);
                $('#questionuserFrom').append(html);
            }
        })
        .fail(function(XMLHttpRequest, textStatus, errorThrown) {
            if(textStatus == 'error'){
                var obj = JSON.parse(XMLHttpRequest.responseText);
                var errors = obj.errors;
                $.each(errors,function (name,vale) {
                    pub_alert_error(vale);
                })
            }
        })
}

function questionusernext() {
    if(questionusercurrenpPge ==questionusertatolPage){
        pub_alert_error('已经是最后一页了');
        return false;
    }
    if(questionusernexti > questionusertatolPage){
        pub_alert_error('参数有误');
        return false;
    }
    questionuserseachList(questionusernexti);
}

function questionuserup() {
    if(questionusercurrenpPge ==1){
        pub_alert_error('已经是第一页了');
        return false;
    }
    if(questionuserupi <1){
        pub_alert_error('参数有误');
        return false;
    }
    questionuserseachList(questionuserupi);
}


/**
 * Created by Administrator on 2016/8/1.  user
 */
usertatol = 0;
usertatolPage = 0;
usercurrenpPge = 0;
usernexti = 0;
userupi = 0;
function userseachList(pcurrenpPge){

    var data =$('#userFrom').serialize();
    $.ajax({
        url: $('#userFrom').attr('action'),
        type: 'POST',
        dataType: 'json',
        data: data+'&currenpPge='+pcurrenpPge,

    })
        .done(function(r) {
            if(r.status == 1){
                if($(".suteng").length > 0){
                   $(".suteng").remove() ;
                }
                html = '';
                $.each(r.data,function (name,vale) {
                    html +='<div class="profile-activity suteng clearfix"><div><img class="pull-left" src="../../avatars/120/'+vale.avatar+'">';
                    html +='<a class="user" href="#"> '+vale.display_name+' </a>&nbsp;&nbsp;|&nbsp;&nbsp; '+vale.email+'&nbsp;&nbsp;|&nbsp;&nbsp;'+vale.mobile;
                    // html +='<div title="今天已经发布n次" class="time"><i class="ace-icon fa fa-check-square-o bigger-110"></i>n次</div></div>';
                    html +='</div>';
                    html +='<div class="tools action-buttons"><a href="javascript:void(0)" class="red" onclick="adduser('+vale.id+',this)"><i class="ace-icon glyphicon glyphicon-ok bigger-125"></i></a></div></div>';
                })
                $('#userFrom').append(html);
                usertatol = r.total;
                usertatolPage = r.totalPage;
                usercurrenpPge = r.currenpPge;
                usernexti = r.next;
                userupi = r.up;
                $('#usertatol').text(usertatol);
                $('#usertatolPage').text(usertatolPage);
                $('#usercurrenpPge').text(usercurrenpPge);
                // $('[data-rel=tooltip]').tooltip();
                // $('[data-rel=popover]').popover({html:true});
            }else{
                if($(".suteng").length > 0){
                    $(".suteng").remove() ;
                }
                html = '<div class="profile-activity suteng clearfix">没有记录！</div>';
                usertatol = r.total;
                usertatolPage = r.totalPage;
                usercurrenpPge = r.currenpPge;
                usernexti = r.next;
                userupi = r.up;
                $('#usertatol').text(usertatol);
                $('#usertatolPage').text(usertatolPage);
                $('#usercurrenpPge').text(usercurrenpPge);
                $('#userFrom').append(html);
            }
        })
        .fail(function(XMLHttpRequest, textStatus, errorThrown) {
            if(textStatus == 'error'){
                var obj = JSON.parse(XMLHttpRequest.responseText);
                var errors = obj.errors;
                $.each(errors,function (name,vale) {
                    pub_alert_error(vale);
                })
            }
        })
}


function usernext() {
    if(usercurrenpPge ==usertatolPage){
        pub_alert_error('已经是最后一页了');
        return false;
    }
    if(usernexti > usertatolPage){
        pub_alert_error('参数有误');
        return false;
    }
    userseachList(usernexti);
}

function userup() {
    if(usercurrenpPge ==1){
        pub_alert_error('已经是第一页了');
        return false;
    }
    if(userupi <1){
        pub_alert_error('参数有误');
        return false;
    }
    userseachList(userupi);
}

function deluser(id,th) {
    $(th).removeAttr('onclick');
    $.ajax({
        url: '/admin/ajax/questiontool/userdel',
        type: 'POST',
        dataType: 'json',
        data: {_token:$('input[name = _token]').val(),id:id},
        beforeSend: function () {
            loadingstart();
        },
    })

        .fail(function(XMLHttpRequest, textStatus, errorThrown) {
            loadingend();
            if(textStatus == 'error'){
                var obj = JSON.parse(XMLHttpRequest.responseText);
                var errors = obj.errors;
                $.each(errors,function (name,vale) {
                    pub_alert_error(vale);
                })
            }else{
                questionuserseachList(questionusercurrenpPge);
                userseachList(usercurrenpPge);
                pub_alert_success('操作成功');
            }
        })
}

function adduser(id,th) {
    $(th).removeAttr('onclick');
    $.ajax({
        url: '/admin/ajax/questiontool/useradd',
        type: 'POST',
        dataType: 'json',
        data: {_token:$('input[name = _token]').val(),id:id},
        beforeSend: function () {
            loadingstart();
        },
    })

        .fail(function(XMLHttpRequest, textStatus, errorThrown) {
            loadingend();
            if(textStatus == 'error'){
                var obj = JSON.parse(XMLHttpRequest.responseText);
                var errors = obj.errors;
                $.each(errors,function (name,vale) {
                    pub_alert_error(vale);
                })
            }else{
                questionuserseachList(questionusercurrenpPge);
                userseachList(usercurrenpPge);
                pub_alert_success('操作成功');
            }
        })
}








