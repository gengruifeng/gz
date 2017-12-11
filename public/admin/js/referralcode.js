/**
 * Created by Administrator on 2016/9/24.
 */


function add() {
    var _token = $("input[name = '_token']").val();
    $.ajax({
        url: '/admin/ajax/referralcode/add',
        type: 'POST',
        dataType: 'json',
        data:{_token:_token},
        beforeSend: function () {
            loadingstart();
        },
    })
        .done(function() {
            loadingend();
            pub_alert_success('生成邀请码成功');
            window.location.href="/admin/referralcode/list";
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
                pub_alert_success('生成邀请码成功');
                window.location.href="/admin/referralcode/list";
            }
        })
}

function issued() {

    var is_subimt = false;
    $("input[name='id[]']").each(function(){
        if(this.checked==true){
            is_subimt = true;
            $(this).parent().parent().next().next().next().text('已发放');
        }
    });

    if(is_subimt === false){
        pub_alert_error('请选择邀请码');
        return is_subimt;
    }
    $('#issuedFrom').submit()

}

function searchcode() {
    var issued = $('#issued').val();
    var used = $('#used').val();
    var pageSize = $('#pageSize').val();
    if(pageSize == undefined){
        pageSize = 20;
    }
    if(issued == -1 && used == -1){
        window.location.href='/admin/referralcode/list?pageSize='+pageSize;
    }else if(issued != -1 && used == -1){
        window.location.href='/admin/referralcode/list?issued='+issued+'&pageSize='+pageSize;
    }else if(issued == -1 && used != -1){
        window.location.href="/admin/referralcode/list?used="+used+"&pageSize="+pageSize;
    }else if(issued != -1 && used != -1){
        window.location.href="/admin/referralcode/list?used="+used+"&issued="+issued;
    }
}

function selectcount(th) {
    searchcode();
}
