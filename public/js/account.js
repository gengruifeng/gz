/**
 * Created by Administrator on 2016/7/19.
 */

function subInfo() {

    if($("input[name=display_name]").val().trim().length<= 0){
        dialogcom_warn('名号不能为空！');
        return false;
    }
    if($("input[name=display_name]").val() != undefined){
        var reg = /^[a-zA-Z0-9_\u4e00-\u9fa5]+$/;
        if(!reg.test($("input[name=display_name]").val().trim())){
            dialogcom_warn('名号只支持中文、英文、数字、“_”！');
            return false;
        }
    }
    if($("input[name=occupation]").val().trim().length<= 0){
        dialogcom_warn('状态不能为空！');
        return false;
    }else if($("input[name=gender]").val().trim().length<= 0){
        dialogcom_warn('性别不能为空！');
        return false;
    }else if($("input[name=slogan]").val().trim().length<= 0){
        dialogcom_warn('个人签名不能为空！');
        return false;
    }
    
    if($("input[name=occupation]:checked").val() == 1){
        if($("input[name=school]").val().trim().length<= 0){
            dialogcom_warn('学校不能为空！');
            return false;
        }else if($("input[name=department]").val().trim().length<= 0){
            dialogcom_warn('专业不能为空！');
            return false;
        }
    }else if($("input[name=occupation]:checked").val() == 2){
        if($("input[name=company]").val().trim().length<= 0){
            dialogcom_warn('公司不能为空！');
            return false;
        }else if($("input[name=position]").val().trim().length<= 0){
            dialogcom_warn('职位不能为空！');
            return false;
        }
    }

    var data =$('#form').serialize();
    $.ajax({
        url: $('#form').attr('action'),
        type: 'POST',
        dataType: 'json',
        data: data,
    })
        .done(function() {
            dialogcom_yes("保存成功!");
        })
        .fail(function(XMLHttpRequest, textStatus, errorThrown) {
            if(textStatus == 'error'){
                var obj = JSON.parse(XMLHttpRequest.responseText);
                if(XMLHttpRequest.status == 401){
                    dialoglogin();
                    return false;
                }
                if(XMLHttpRequest.status == 403){
                    dialogcom_wrong('页面停留时间过长请刷新页面后再操作!');
                    return false;
                }
                var errors = obj.errors;
                $.each(errors,function (name,vale) {
                    dialogcom_wrong(vale);
                })
            }else{
                dialogcom_yes("保存成功!");
            }
        })

}
function ddjsobj(obj){
    var description = "";
    for(var i in obj){
        var property=obj[i];
        description+=i+" = "+property+"\n";
    }
    alert(description);
}

function changeCity(th) {
    if($(th).val() == '请选择省份或直辖市'){
        $(".gogogrf").hide();
    }else{
        $(".gogogrf").hide();
        $('#city').find('option').eq(0).prop('selected',true);
        val = $("#province  option:selected").attr('data-text');
        $(".subcity"+val).show();
    }

}

$("input[name=occupation]").click(function(){
    if($("input[name=occupation]:checked").val() == 1){
        $("input[name=school]").prev().html('<b class="importantip">*</b>学校 :');
        $("input[name=department]").prev().html('<b class="importantip">*</b>专业 :');

        $("input[name=company]").prev().text('公司 :');
        $("input[name=position]").prev().text('职位 :');

        // $("input[name=school]").removeAttr('readonly');
        // $("input[name=department]").removeAttr('readonly');
        // $("input[name=company]").attr('readonly',true);
        // $("input[name=position]").attr('readonly',true);
    }else if($("input[name=occupation]:checked").val() == 2){
        $("input[name=school]").prev().text('学校 :');
        $("input[name=department]").prev().text('专业 :');

        $("input[name=company]").prev().html('<b class="importantip">*</b>公司 :');
        $("input[name=position]").prev().html('<b class="importantip">*</b>职位 :');

        // $("input[name=company]").removeAttr('readonly');
        // $("input[name=position]").removeAttr('readonly');
        // $("input[name=school]").attr('readonly',true);
        // $("input[name=department]").attr('readonly',true);

    }
});
