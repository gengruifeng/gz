/**
 * Created by Administrator on 2016/7/29.
 */


function edit(id) {
    window.location.href="/admin/usergroup/edit/id/"+id;
}

function add() {
    window.location.href="/admin/usergroup/add/";
}

function subadd() {
    var data = $('#usergroupform').serialize();
    $.ajax({
        url: '/admin/ajax/usergroup/add',
        type: 'POST',
        dataType: 'json',
        data: data,
        beforeSend: function () {
            loadingstart();
        },
    })
        .done(function() {
            loadingend();
            pub_alert_success('添加成功');
            window.location.href="/admin/usergroup/list";
        })
        .fail(function(XMLHttpRequest, textStatus, errorThrown) {
            loadingend();
            if(textStatus == 'error'){
                var obj = JSON.parse(XMLHttpRequest.responseText);
                var errors = obj.errors;
                $.each(errors,function (name,vale) {
                    pub_alert_error(vale);
                })
            }else {
                pub_alert_success('添加成功');
                window.location.href="/admin/usergroup/list";
            }
        })
}

function subedit() {
    var data = $('#usergroupform').serialize();
    $.ajax({
        url: '/admin/ajax/usergroup/edit',
        type: 'POST',
        dataType: 'json',
        data: data,
        beforeSend: function () {
            loadingstart();
        },
    })
        .done(function() {
            loadingend();
            pub_alert_success('编辑成功');
            window.location.href="/admin/usergroup/list";

        })
        .fail(function(XMLHttpRequest, textStatus, errorThrown) {
            loadingend();
            if(textStatus == 'error'){
                var obj = JSON.parse(XMLHttpRequest.responseText);
                var errors = obj.errors;
                $.each(errors,function (name,vale) {
                    pub_alert_error(vale);
                })
            }else {
                pub_alert_success('编辑成功');
                window.location.href="/admin/usergroup/list";

            }
        })
}

function editcon(id) {
    window.location.href="/admin/usergroup/editcon/id/"+id;
}




function getcon(groupid) {
    $.ajax({
        url: '/admin/ajax/usergroup/getcon',
        type: 'POST',
        dataType: 'json',
        data: {id:groupid},
        beforeSend: function () {
            loadingstart();
        },
    })
        .done(function(r) {
            loadingend();
            var setting = {
                check: {
                    enable: true,
                    chkDisabledInherit: true
                },
                view: {
                    showIcon: false
                },
                data: {
                    simpleData: {
                        enable: true
                    }
                }

            };
            $.fn.zTree.init($("#treeDemo"), setting, r);
            $("#disabledTrue").removeAttr('href');
            $("#disabledFalse").removeAttr('href');
            // var treeObj = $.fn.zTree.getZTreeObj("treeDemo");
            // treeObj.expandAll(true);
        })
        .fail(function(XMLHttpRequest, textStatus, errorThrown) {
            loadingend();
            if(textStatus == 'error'){
                var obj = JSON.parse(XMLHttpRequest.responseText);
                var errors = obj.errors;
                $.each(errors,function (name,vale) {
                    pub_alert_error(vale);
                })
            }
        })
}

function dosave() {
    var checked_node = $.fn.zTree.getZTreeObj("treeDemo").getCheckedNodes(true);
    var save_node = [];
    var id = groupid;
    $(checked_node).each(function(i,v){
        save_node.push(v.id);
    });

    $.ajax({
        url: '/admin/ajax/usergroup/saveusercon',
        type: 'POST',
        dataType: 'json',
        data: {competence_id:save_node,group_id:id},
        beforeSend: function () {
            loadingstart();
        },
    })
        .done(function() {
            loadingend();
            pub_alert_success('授权成功');
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
                pub_alert_success('授权成功');

            }
        })
}
