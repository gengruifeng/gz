

$.validator.messages.max = jQuery.validator.format("Your totals mustn't exceed {0}!");

$.validator.addMethod("school_name", function(value, element) {
    return !this.optional(element);
}, "请选择学校");
$.validator.addMethod("expert_name", function(value, element) {
    return !this.optional(element);
}, "请输入专业");
$.validator.addMethod("level", function(value, element) {
    return  !this.optional($(element)[0]);
}, "请选择学历");

$().ready(function() {
    var template = jQuery.validator.format($.trim($("#template").val()));
    function addRow() {
        $(template(i++)).appendTo("#resumeTwoForm .stepmsg");

    }
    i = $("#i").val();
    $("#resumeTwoForm").validate({
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
            var data =$("#resumeTwoForm").serialize();
            $.ajax({
                type:"post",
                url:"/ajax/resume/myeducations",
                async : false,
                data:data+"&i="+i,
                dataType: "json",
                success:function() {
                    window.location.replace('/myexperiences');
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




    });


});

function city(th,cityid,educationkey,event) {
    $(th).parent().addClass('hide');
    $(th).parent().parent().find('.city' + cityid).eq(0).removeClass('hide');
    ccccccid = cityid;
    education = educationkey;
    var event = event || window.event;
    if (event.stopPropagation) {
        event.stopPropagation();
    } else {
        event.cancelBubble = true;
    }
}

function showschool(th,i,event) {
    $('.slidmsg').addClass('hide');
    $('.academy-name').addClass('hide');
    $('.academy-city').removeClass('hide');
    $('.school_name').removeClass('active');
    if($(th).hasClass('active')){
        $(th).removeClass('active');
        $(th).parent().parent().next().addClass('hide');
    }else{
        $(th).addClass('active');
        $(th).parent().parent().next().removeClass('hide');
    }

    $('.academy-name h2').on('click',function(event){
        $('.academy-city').removeClass('hide');
        $('.academy-name').addClass('hide');
        var event = event || window.event;
        if (event.stopPropagation) {
            event.stopPropagation();
        } else {
            event.cancelBubble = true;
        }
    });
    $('.academy-name .btm a').on('click',function(event){
        _value=$(this).html();
        $(this).closest(".sec"+i).find('.inpbtn ').val(_value).removeClass('active');
        $('.slidmsg').addClass('hide');
        var event = event || window.event;
        if (event.stopPropagation) {
            event.stopPropagation();
        } else {
            event.cancelBubble = true;
        }
        if($(th).val() != '' && $(th).parent().next().attr('id') == 'school_name-'+i+'-error'){
            $(th).parent().next().remove();
        }
    });
    var event = event || window.event;
    if (event.stopPropagation) {
        event.stopPropagation();
    } else {
        event.cancelBubble = true;
    }
}
function sousuoschool(event) {
    var cache = {};
    $("input[name='seachschool']").autocomplete({
        minLength: 2,
        source: function (request, response) {
            var q = request.term;
            for (q in cache) {
                response(cache[q]);
            }
            $.getJSON("/school/search", {q: request.term,cityid:ccccccid}, function (data, status, xhr) {
                var i,
                    l = data.length,
                    tmp = null,
                    map = [];

                for (i = 0; i < l; i++) {
                    tmp = {
                        value: data[i].name,
                        label: data[i].name,
                        source_id: data[i].source_id,
                        id: data[i].id
                    };

                    map.push(tmp);
                }

                cache[q] = map;
                response(map);
            });
        },
        select: function (event, ui) {
            $('.slidmsg').addClass('hide');
            $("#school_name-"+education).val(ui.item.label);
        }
    });
    var event = event || window.event;
    if (event.stopPropagation) {
        event.stopPropagation();
    } else {
        event.cancelBubble = true;
    }
}
//
// function changeCity(th) {
//     if($(th).val() == '请选择省份或直辖市'){
//         $(".gogogrf").hide();
//     }else{
//         $(".gogogrf").hide();
//         $('#city').find('option').eq(0).prop('selected',true);
//         val = $("#province  option:selected").attr('data-text');
//         $(".subcity"+val).show();
//     }
//
// }
// 点其它地方消失
$(document).on('click',function(){
    $('.inpbtn').removeClass('active');
    if($(this).find('.slidmsg').length>0){
        $('.slidmsg').addClass('hide');
    }
});