/**
 * Created by Administrator on 2016/7/28.
 */


function addcon(th,pid,level){
    cleaninput();
    isdiv(level,1);
    public_alert_bootbox();
    $("#pid").val(pid);
    $('#tijiao').attr({
        'onclick':'subcon()'
    });
    $("#is_defult").find("option[value='0']").attr("selected",true);
    $("#is_defult").find("option[value='0']").attr("selected",true);
}

function subcon(){
    var data = $('#confrom').serialize();
    $.ajax({
        url: $('#confrom').attr('action'),
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
            window.location.reload();
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
                window.location.reload();
            }
        })
}

function cleaninput() {
    $('#name').val('');
    $('#con').val('');
    $('#url_name').val('');
    $('#order').val('');
}

function editcon(th,id,level) {


    $.ajax({
        url: '/admin/ajax/competence/getone',
        type: 'POST',
        dataType: 'json',
        data: {'id': id},
    })
        .done(function (r) {

            $('#name').val(r.name);
            $('#con').val(r.con);
            $('#url_name').val(r.url_name);
            $('#order').val(r.order);
            $('#id').val(r.id);
            $('#pid').val(r.pid);
            isdiv(level,0);
            $('#tijiao').attr({
                'onclick':'subcomedit()'
            });
            if(r.is_defult == 1){
                $("#is_defult").find("option[value='1']").attr("selected",true);
            }else{
                $("#is_defult").find("option[value='0']").attr("selected",true);
            }
            public_alert_bootbox();

        })
        .fail(function (XMLHttpRequest, textStatus, errorThrown) {
            if (textStatus == 'error') {
                var obj = JSON.parse(XMLHttpRequest.responseText);
                var errors = obj.errors;
                $.each(errors, function (name, vale) {
                    pub_alert_error(vale);
                })
            }
        })


}

function isdiv(level,add ) {
    $("#isdefalutdiv").css(
        "display","none"
    );
    $("#isurldiv").css(
        "display","none"
    );
    $("#isurlnamediv").css(
        "display","none"
    );
    if(add ==1){
        if(level == 1){
            $("#isdefalutdiv").css(
                "display","block"
            );
            $("#isurldiv").css(
                "display","block"
            );
            $("#isurlnamediv").css(
                "display","block"
            );


        }else if(level == 2){
            $("#isurldiv").css(
                "display","block"
            );
            $("#isurlnamediv").css(
                "display","block"
            );



        }

    }else{
        if(level == 2){
            $("#isdefalutdiv").css(
                "display","block"
            );
            $("#isurldiv").css(
                "display","block"
            );
            $("#isurlnamediv").css(
                "display","block"
            );
        }else if(level == 3){
            $("#isurldiv").css(
                "display","block"
            );
            $("#isurlnamediv").css(
                "display","block"
            );

        }

    }

}


function subcomedit(){
    var data = $('#confrom').serialize();
    $.ajax({
        url: '/admin/ajax/competence/edit',
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
            window.location.reload();
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
                window.location.reload();
            }
        })
}

function del(id){
    pub_alert_confirm('/admin/ajax/competence/del',{id:id},'删除成功!');
}