/**
 * Created by Administrator on 2016/8/3.
 */


function addtags(type,id,name,order,pic,picurl,pic2,pic2url){
    if(type ==1){
        $("#entity").val('');
        $("#order").val('');
        $("#categoryurl").val('');
        $("#categoryImg").html('');
        $("#categoryurlhide").val('');
        $("#categoryImgHide").html('');
        $(".blue").text('添加领域');
        $('#tijiao').attr({
            'onclick':'subaddcategories()'
        });
    }else{
        $("#entity").val(name);
        $(".blue").text('编辑领域');
        $("#id").val(id);
        $("#categoryurl").val(pic);
        $("#categoryImg").html("<image src='"+picurl+"'/>");
        $("#categoryurlhide").val(pic2);
        $("#categoryImgHide").html("<image src='"+pic2url+"'/>");
        $("#order").val(order);
        $('#tijiao').attr({
            'onclick':'subeditcategories()'
        });
    }
    public_alert_bootbox();
    
}
function uptags(id,name,order){
        $("#entity").val(name);
        $("#blue").text('上传擅长领域图片');
        $("#id").val(id);
        $("#order").val(order);
    public_alert_bootbox();
}
function subaddcategories() {
    var data =$('#edittagsfrom').serialize();
    $.ajax({
        url: '/admin/ajax/tags/addcategoriesinfo',
        type: 'POST',
        dataType: 'json',
        data: data,

    })

        .fail(function(XMLHttpRequest, textStatus, errorThrown) {
            if(textStatus == 'error'){
                var obj = JSON.parse(XMLHttpRequest.responseText);
                var errors = obj.errors;
                $.each(errors,function (name,vale) {
                    pub_alert_error(vale);
                })
            }else{
                pub_alert_success('保存成功');
                // window.location.reload();
                window.location.href=window.location.href;
            }
        })
}

function subeditcategories() {
    var data =$('#edittagsfrom').serialize();
    $.ajax({
        url: '/admin/ajax/tags/editcategoriesinfo',
        type: 'POST',
        dataType: 'json',
        data: data,

    })

        .fail(function(XMLHttpRequest, textStatus, errorThrown) {
            if(textStatus == 'error'){
                var obj = JSON.parse(XMLHttpRequest.responseText);
                var errors = obj.errors;
                $.each(errors,function (name,vale) {
                    pub_alert_error(vale);
                })
            }else{
                pub_alert_success('保存成功');
                window.location.href=window.location.href;
                window.location.reload();
            }
        })
}

function del(id) {
    pub_alert_confirm('/admin/ajax/tags/delcate',{id:id},'删除成功')
}

$(document).ready(function() {
    var _token =  $("input[name = '_token']").val();
    categoryUpload(_token);
    categoryUploadHide(_token);
});

