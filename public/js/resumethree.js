
$.validator.messages.max = jQuery.validator.format("Your totals mustn't exceed {0}!");

$.validator.addMethod("company_name", function(value, element) {
    return !this.optional(element);
}, "请输入您的公司");
$.validator.addMethod("job_name", function(value, element) {
    return !this.optional(element);
}, "请输入您职业");

var i = $("#i").val();

$().ready(function() {

    var template = jQuery.validator.format($.trim($("#template").val()));
    function addRow() {
        $(template(i++)).appendTo("#resumeThreeForm .stepmsg");
        $("#i").val(i)
    }
    // 开始时只有一行
    $("#resumeThreeForm").validate({
        errorElement: 'p',
        errorClass: 'red-text',
        focusInvalid: true,
        highlight: function (e) {//错误的显示
            $(e).parent().removeClass('correct');
            $(e).parent().addClass('err');
        },
        success: function (e) {//成功
            $(e).prev().removeClass('err');
            $(e).prev().addClass('correct');
            $(e).remove();
        },
        errorPlacement: function (error, element) {//错误显示位置
            if(element.parent().next().attr('class') == 'red-text'){
                element.parent().next().remove();
            }
            error.insertAfter(element.parent());
        },submitHandler: function() {
            var data =$("#resumeThreeForm").serialize();
            $.ajax({
                type:"post",
                url:"/ajax/resume/myexperiences",
                async : false,
                data:data+"&i="+i,
                dataType: "json",
                success:function() {
                    window.location.replace('/myeducations');
                },error: function(xhr, status, error) {
                    if(xhr.status == 401){
                        dialoglogin();
                        type = false;
                    }
                    if(xhr.status == 403){
                        dialogcom_wrong('页面停留时间过长请刷新页面后再操作!');
                        type = false;
                    }
                }
            })
        }
    });
    // 点击时添加更多行
    $("#add").click(function(){
        addRow();
        // $('.input-startday').each(function(index, element) {
        //     $(this).datepicker({
        //         autoclose: true,
        //         format: 'yyyy-mm-dd',
        //         language: 'zh-CN'
        //     }).on('click',function () {
        //         var top = $(this).offset().top-340+$("body").scrollTop();
        //         var left = $(this).offset().left;
        //         $('.datepicker').css({
        //             'top':top,
        //             'left':left,
        //         });
        //     });
        // })
        $('.time_start').each(function(index,element){
            $(this).datepicker({    
                autoclose : true,  
                format: 'yyyy-mm-dd',  
                language: 'zh-CN'
            }).on('changeDate',function(e){  
                
                var startTime = e.date;  
                $('input[name="time_end-'+index+'"]').datepicker('setStartDate',startTime);  
            });  
        })        
        $('.time_end').each(function(indexed,element){
            $(this).datepicker({    
                autoclose : true,    
                format: 'yyyy-mm-dd',  
                language: 'zh-CN'
            }).on('changeDate',function(e){  
                var endTime = e.date;  
                $('input[name="time_start-'+indexed+'"]').datepicker('setStartDate',endTime);  
            });  
        })
        $(".tabs").tabs(".pane", {
                onClick: function () {

                }
            });
    });
});
function threeback(loca) {
    var data =$("#resumeThreeForm").serialize();
    $.ajax({
        type:"post",
        url:"/ajax/backexperiences",
        async : false,
        data:data+"&i="+i,
        dataType: "json",
        success:function() {
            window.location.replace(loca);
        },error: function(xhr, status, error) {
            if(xhr.status == 401){
              location.href='/login'
            }
            if(xhr.status == 403){
                dialogcom_wrong('页面停留时间过长请刷新页面后再操作!');
                type = false;
            }
        }
    })

}
function showjob(th) {
    if($(th).parent().parent().next().hasClass('hide')){
        $(th).parent().parent().next().removeClass('hide');
    }else{
        $(th).parent().parent().next().addClass('hide');
    }
}

function selectjob(th) {
    var s = $(th).text()
    s=s.substring(0,s.length-1);
    $(th).parent().parent().parent().parent().parent().addClass('hide');
    $(th).parent().parent().parent().parent().parent().prev().find('input').val(s)
}

