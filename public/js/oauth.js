$('#wxjb').click(function () {
    var uid =$('#hidden').val();
    var token = $("input[name = '_token']").val();
    $.ajax({
        type:"post",
        url:"/ajax/account/deloauth",
        async : false,
        data:{type:'unbundlingweixin',uid:uid,_token:token},
        dataType: "json"
    })
        .fail(function(XMLHttpRequest, textStatus, errorThrown) {
            if(textStatus == 'error'){
                var errors = XMLHttpRequest.responseJSON.errors;
                //message = errors[0].message;
                $.each(errors,function (name,val) {
                    dialogcom_wrong(val.message);
                })
            }else{
                location.href = 'oauth';
            }
        })
});
$('#wbjb').click(function () {
    var uid =$('#hidden').val();
    var token = $("input[name = '_token']").val();
    $.ajax({
        type:"post",
        url:"/ajax/account/deloauth",
        async : false,
        data:{type:'unbundlingweibo',uid:uid,_token:token},
        dataType: "json"
    })
        .fail(function(XMLHttpRequest, textStatus, errorThrown) {
            if(textStatus == 'error'){
                var errors = XMLHttpRequest.responseJSON.errors;
                //message = errors[0].message;
                $.each(errors,function (name,val) {
                    dialogcom_wrong(val.message);
                })
            }else{
                location.href = 'oauth';
            }
        })
});
$('#qqjb').click(function () {
    var uid =$('#hidden').val();
    var token = $("input[name = '_token']").val();
    $.ajax({
        type:"post",
        url:"/ajax/account/deloauth",
        async : false,
        data:{type:'unbundlingqq',uid:uid,_token:token},
        dataType: "json"
    })
        .fail(function(XMLHttpRequest, textStatus, errorThrown) {
            if(textStatus == 'error'){
                var errors = XMLHttpRequest.responseJSON.errors;
                //message = errors[0].message;
                $.each(errors,function (name,val) {
                    dialogcom_wrong(val.message);
                })
            }else{
                location.href = 'oauth';
            }
        })
});