/**
 * Created by Administrator on 2016/7/25.
 */

function subedit(){
    var data =$('#validation-form').serialize();
    $.ajax({
        url: $('#validation-form').attr('action'),
        type: 'POST',
        dataType: 'json',
        data: data,
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
                pub_alert_success('保存成功');
            }
        })
}