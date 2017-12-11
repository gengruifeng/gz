$(function () {
    //保存此简历遮罩层
    $(".btn-save").click(function () {
        $(".resume-alert").removeClass("hide");
        $("#resumeTitle").val("");
    });
    //取消
    $(".btns .fr").click(function () {
        $(".resume-alert").addClass("hide");
    });
    
    $("#resumeTitle").keyup(function () {
        var title = $(this).val();
        if(title == "" || title == "undefined"){
            $(this).next().html("请更新您的简历标题");
        }else{
            $(this).css("border-color","#eee");
            $(this).next().html("");
        }
    });


    $(".btn-success").click(function () {
        var titleflag=true;
        var title = $("#resumeTitle").val();
        if(title == "" || title == "undefined"){
            titleflag=false;
            $("#resumeTitle").next().html("请更新您的简历标题");
        }
        if(titleflag){
            var token = $("input[name = '_token']").val();
            var cvid = $("#cvid").val();
            var resumemodel = $("#resumemodel").val();
            $.ajax({
                type:"post",
                url:"/ajax/resumemanage/resumesave",
                data:{title:title,cvid:cvid,resumemodel:resumemodel,_token:token},
                dataType: "json"
            }).always(function(XMLHttpRequest, textStatus) {
                if(XMLHttpRequest.status == 401){
                    $(".resume-alert").addClass("hide");
                    dialoglogin();
                    return false;
                }
                if(XMLHttpRequest.status == 403){
                    $(".resume-alert").addClass("hide");
                    dialogcom_wrong('页面停留时间过长请刷新页面后再操作!');
                    return false;
                }
                if(textStatus == 'error'){
                    if( XMLHttpRequest.status == 400){
                        var errors = XMLHttpRequest.responseJSON.errors;
                        $.each(errors,function (name,vale) {
                            $("input[name=title]").css("border-color","#fa7e65");
                            $("input[name=title]").next().text(vale)
                        })
                    }
                }else{
                    location.href="/resume/list";
                }
            });
        }
    });

});