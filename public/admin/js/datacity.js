/**
 * Created by Administrator on 2016/8/1.  city
 */
citytatol = 0;
citytatolPage = 0;
citycurrenpPge = 0;
citynexti = 0;
cityupi = 0;
function cityseachList(pcurrenpPge){

    var data =$('#cityFrom').serialize();
    $.ajax({
        url: $('#cityFrom').attr('action'),
        type: 'POST',
        dataType: 'json',
        data: data+'&currenpPge='+pcurrenpPge,
        beforeSend: function () {
            loadingstart();
        },
    })
        .done(function(r) {
            var html ='';
            if(r.status == 1){
                $.each(r.data,function (name,vale) {
                    html +='<tr>';
                    html += '<td>'+vale.id+' </td>';
                    html += '<td><span style="display:inline-block;width: 300px;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">'+vale.areaname+' </span></td>';
                    html += '<td><span>'+vale.type+' </span></td>';
                    html += '<td><span>'+vale.sort+' </span></td>';
                    html += '<td>'+vale.created_at+' </td>';
                    html += '<td>';
                    html += '<button  onclick = "addcity(2,'+vale.id+',\''+vale.areaname+'\','+vale.parentid+','+vale.parentidtwo+','+vale.sort+')" class="btn btn-xs btn-success">编辑</button> ';
                    html += ' <button  onclick = "citydel('+vale.id+')" class="btn btn-xs btn-success">删除</button> ';

                    html += '</td>';
                    html +='</tr>';
                })
                $('#cityTbody').html(html);
                citytatol = r.total;
                citytatolPage = r.totalPage;
                citycurrenpPge = r.currenpPge;
                citynexti = r.next;
                cityupi = r.up;
                $('#citytatol').text(citytatol);
                $('#citytatolPage').text(citytatolPage);
                $('#citycurrenpPge').text(citycurrenpPge);
                $('[data-rel=tooltip]').tooltip();
                $('[data-rel=popover]').popover({html:true});
            }else{
                $('#cityTbody').html('<tr><td colspan="10">没有记录！</td></tr>');
            }
            loadingend();
        })
        .fail(function(XMLHttpRequest, textStatus, errorThrown) {
            loadingend();
            if(textStatus == 'error'){
                var obj = JSON.parse(XMLHttpRequest.responseText);
                var errors = obj.errors;
                $.each(errors,function (name,vale) {
                    pub_alert_error(vale);
                })
            }
        })
}


function addcity(type,id,name,one,two,order){
    if(type ==1){
        $("#cityaddname").val('');
        $("#cityaddorder").val('');

        $("#cityaddblue").text('添加城市');
        $('#cityselects').find('option').eq(0).prop('selected',true);
        $("#erselectcity").css('display','none');
        $(".adderselect").css('display','none');
        $(".adderselect").attr('disabled',true);
        $("#cityselects").removeAttr('disabled');
        $('#cityeditfrom').attr({
            'action':'/admin/ajax/templatedata/cityadd'
        });
    }else{
        $("#cityaddname").val(name);
        $("#cityaddblue").text('编辑城市');
        $("#cityid").val(id);
        $("#cityaddorder").val(order);
        $('#cityeditfrom').attr({
            'action':'/admin/ajax/templatedata/cityedit'
        });

        $('#cityselects').find('option').eq(0).prop('selected',true);
        $("#erselectcity").css('display','none');
        $(".adderselect").css('display','none');
        $(".adderselect").attr('disabled',true);
        $("#cityselects").attr('disabled',true);


        if(one == 0){
            $('#cityselects').find('option').eq(0).prop('selected',true);

        }else{
            if(two == 0 ){
                $('#cityselects').find('option').each(function () {
                    if($(this).attr('value') == one){
                        $(this).prop('selected',true);
                    }
                });
                $("#erselectcity").css('display','block');
                $("#subcityselectscity"+one).css('display','block');
                // $("#subcityselects"+one).removeAttr('disabled');
                $("#subcityselectscity"+one).find('option').each(function () {
                    if($(this).attr('value') == two){
                        $(this).prop('selected',true);
                    }
                });
            }else{
                $('#cityselects').find('option').each(function () {
                    if($(this).attr('value') == one){
                        $(this).prop('selected',true);
                    }
                });

                $("#erselectcity").css('display','block');

                $("#subcityselectscity"+one).css('display','block');
                // $("#subcityselects"+one).removeAttr('disabled');

                $("#subcityselectscity"+one).find('option').each(function () {
                    if($(this).attr('value') == two){
                        $(this).prop('selected',true);
                    }
                });

            }
        }
    }
    public_alert_bootbox('pub_edit_bootbox_city');

}


function citynext() {
    if(citycurrenpPge ==citytatolPage){
        pub_alert_error('已经是最后一页了');
        return false;
    }
    if(citynexti > citytatolPage){
        pub_alert_error('参数有误');
        return false;
    }
    cityseachList(citynexti);
}

function cityup() {
    if(citycurrenpPge ==1){
        pub_alert_error('已经是第一页了');
        return false;
    }
    if(cityupi <1){
        pub_alert_error('参数有误');
        return false;
    }
    cityseachList(cityupi);
}

function citydel(id) {
    var _token = $('input[name=_token]').val();
    pub_alert_confirm('/admin/ajax/templatedata/citydel',{id:id,_token:_token},'删除成功!');
}

function citysubedit() {
    var data =$('#cityeditfrom').serialize();
    $.ajax({
        url: $('#cityeditfrom').attr('action'),
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
                window.location.reload();
            }
        })
}

function selectchagecity(th) {

    $(".subselect").css('display','none');
    $(".subselect").attr('disabled',true);
    if($(th).val() == 0 ){
        $('#subcityselect').css('display','block');
        $('#subcityselect').removeAttr('disabled');
        $('#subcityselect').find('option').eq(0).prop('selected',true);
    }else if($(th).val() == -1){
        $('#subcityselect').css('display','block');
        $('#subcityselect').find('option').eq(0).prop('selected',true);
    }else{
        $('#subcityselect'+$(th).val()).css('display','block');
        $('#subcityselect'+$(th).val()).removeAttr('disabled');
        $('#subcityselect'+$(th).val()).find('option').eq(0).prop('selected',true);
    }
}

function selectchagescity(th) {
    $(".adderselect").css('display','none');
    $(".adderselect").attr('disabled',true);
    if($(th).val() == 0){
        $("#erselectcity").css('display','none');
        $(".adderselect").css('display','none');
        $(".adderselect").attr('disabled',true);
    }else{
        $("#erselectcity").css('display','block');
        $("#subcityselectscity"+$(th).val()).css('display','block');
        $("#subcityselectscity"+$(th).val()).removeAttr('disabled');
    }
}







