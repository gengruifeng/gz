/**
 * Created by Administrator on 2016/7/21.
 */

$(window).bind('hashchange', function() {
//
    u = location.href.split('#');
    if(u[1] == undefined){
        $('.beGoodCard').eq(0).addClass('dispaly');
        $('.beGood').eq(0).removeClass('dispaly');
    }

});


function tosettabs(){
    window.location.href="/account/setproficiency";
}

function nextTags() {
    var data =$('#nextfrom').serialize();
    $.ajax({
        url: $('#nextfrom').attr('action'),
        type: 'POST',
        dataType: 'json',
        data: data,
    })
        .done(function(r) {
            var tahHtml = '';

            $('#grful').html(tahHtml);
            $('.beGood').eq(0).addClass('dispaly');
            $('.beGoodCard').eq(0).attr('class','beGoodCard')
            $.each(r,function (name,vale) {
                tahHtml += "<li> <a onclick='selecttag(this)' class='bgCard' href='javascript:void(0)'>"+vale.name+" <input value='"+vale.id+"' name='tagsid[]' type='checkbox' /> </a> </li>";
                // tahHtml += '<li><a href="javascript:void(0)" class="bgCard">产品设计<input type="checkbox" checked="checked"> </a></li>';
            })
            $('#grful').append(tahHtml);
            location.hash='beGoodCardss'
        })
        .fail(function(XMLHttpRequest, textStatus, errorThrown) {
            if(textStatus == 'error'){
                if(XMLHttpRequest.status == 401){
                    dialoglogin();
                    return false;
                }
                if(XMLHttpRequest.status == 403){
                    dialogcom_wrong('页面停留时间过长请刷新页面后再操作!');
                    return false;
                }
                var obj = JSON.parse(XMLHttpRequest.responseText);
                var errors = obj.errors;
                $.each(errors,function (name,vale) {
                    dialogcom_wrong(vale);
                })
            }
        })
}

function subUserTags() {
    var data =$('#subUserTagsfrom').serialize();
    $.ajax({
        url: $('#subUserTagsfrom').attr('action'),
        type: 'POST',
        dataType: 'json',
        data: data,
    })
        .done(function() {
            window.location.href="/account/proficiency";

        })
        .fail(function(XMLHttpRequest, textStatus, errorThrown) {
            if(textStatus == 'error'){
                if(XMLHttpRequest.status == 401){
                    dialoglogin();
                    return false;
                }
                if(XMLHttpRequest.status == 403){
                    dialogcom_wrong('页面停留时间过长请刷新页面后再操作!');
                    return false;
                }
                var obj = JSON.parse(XMLHttpRequest.responseText);
                var errors = obj.errors;
                $.each(errors,function (name,vale) {
                    dialogcom_wrong(vale);
                })
            }else{
                window.location.href="/account/proficiency";
            }
        })
}

function selecttag(th) {
    var leg = $("#grful input[type='checkbox']:checked").length;
    if(leg >= 10){
        dialogcom_warn('最多可以选择9个标签！');
        $(th).find('input').removeAttr('checked');
    }else{
        var reg=/onClick/g;
        if(reg.test($(th).attr('class'))){
            $(th).removeClass('onClick');
            $(th).find('input').removeAttr('checked');
        }else{
                $(th).addClass('onClick');
                $(th).find('input').attr('checked','checked');

        }
    }

}

function selectcategory(th) {
    var reg=/onClick/g;
    if(reg.test($(th).attr('class'))){
        $(th).removeClass('onClick');
        $(th).find('span').removeClass('onClick');
        $(th).find('input').removeAttr('checked');
        $(th).find('.imgshow').css('display','block');
        $(th).find('.hide').css('display','none');
    }else{
        $(th).addClass('onClick');
        $(th).find('span').addClass('onClick');
        $(th).find('input').attr('checked','checked');
        $(th).find('.imgshow').css('display','none');
        $(th).find('.hide').css('display','block');
    }
}

$()