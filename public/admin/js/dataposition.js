/**
 * Created by Administrator on 2016/8/1.  position
 */
positiontatol = 0;
positiontatolPage = 0;
positioncurrenpPge = 0;
positionnexti = 0;
positionupi = 0;
function positionseachList(pcurrenpPge){

    var data =$('#positionFrom').serialize();
    $.ajax({
        url: $('#positionFrom').attr('action'),
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
                    html += '<td><span style="display:inline-block;width: 300px;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">'+vale.name+' </span></td>';
                    html += '<td><span>'+vale.type+' </span></td>';
                    html += '<td><span>'+vale.order+' </span></td>';
                    html += '<td>'+vale.created_at+' </td>';
                    html += '<td>';
                    html += '<button  onclick = "addposition(2,'+vale.id+',\''+vale.name+'\','+vale.pid+','+vale.pidtwo+','+vale.order+')" class="btn btn-xs btn-success">编辑</button> ';
                    html += ' <button  onclick = "positiondel('+vale.id+')" class="btn btn-xs btn-success">删除</button> ';

                    html += '</td>';
                    html +='</tr>';
                })
                $('#positionTbody').html(html);
                positiontatol = r.total;
                positiontatolPage = r.totalPage;
                positioncurrenpPge = r.currenpPge;
                positionnexti = r.next;
                positionupi = r.up;
                $('#positiontatol').text(positiontatol);
                $('#positiontatolPage').text(positiontatolPage);
                $('#positioncurrenpPge').text(positioncurrenpPge);
                $('[data-rel=tooltip]').tooltip();
                $('[data-rel=popover]').popover({html:true});
            }else{
                $('#positionTbody').html('<tr><td colspan="10">没有记录！</td></tr>');
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


function addposition(type,id,name,one,two,order){
    if(type ==1){
        $("#positionaddname").val('');
        $("#positionaddorder").val('');

        $("#positionaddblue").text('添加职位');
        $('#positionselects').find('option').eq(0).prop('selected',true);
        $("#erselect").css('display','none');
        $(".adderselect").css('display','none');
        $(".adderselect").attr('disabled',true);
        $("#positionselects").removeAttr('disabled');
        $('#positioneditfrom').attr({
            'action':'/admin/ajax/templatedata/positionadd'
        });
    }else{
        $("#positionaddname").val(name);
        $("#positionaddblue").text('编辑职位');
        $("#positionid").val(id);
        $("#positionaddorder").val(order);
        $('#positioneditfrom').attr({
            'action':'/admin/ajax/templatedata/positionedit'
        });

        $('#positionselects').find('option').eq(0).prop('selected',true);
        $("#erselect").css('display','none');
        $(".adderselect").css('display','none');
        $(".adderselect").attr('disabled',true);
        $("#positionselects").attr('disabled',true);


        if(one == 0){
            $('#positionselects').find('option').eq(0).prop('selected',true);

        }else{
            if(two == 0 ){
                $('#positionselects').find('option').each(function () {
                    if($(this).attr('value') == one){
                        $(this).prop('selected',true);
                    }
                });
                $("#erselect").css('display','block');
                $("#subpositionselects"+one).css('display','block');
                // $("#subpositionselects"+one).removeAttr('disabled');
                $("#subpositionselects"+one).find('option').each(function () {
                    if($(this).attr('value') == two){
                        $(this).prop('selected',true);
                    }
                });
            }else{
                $('#positionselects').find('option').each(function () {
                    if($(this).attr('value') == one){
                        $(this).prop('selected',true);
                    }
                });

                $("#erselect").css('display','block');

                $("#subpositionselects"+one).css('display','block');
                // $("#subpositionselects"+one).removeAttr('disabled');

                $("#subpositionselects"+one).find('option').each(function () {
                    if($(this).attr('value') == two){
                        $(this).prop('selected',true);
                    }
                });

            }
        }
    }
    public_alert_bootbox('pub_edit_bootbox_position');

}


function positionnext() {
    if(positioncurrenpPge ==positiontatolPage){
        pub_alert_error('已经是最后一页了');
        return false;
    }
    if(positionnexti > positiontatolPage){
        pub_alert_error('参数有误');
        return false;
    }
    positionseachList(positionnexti);
}

function positionup() {
    if(positioncurrenpPge ==1){
        pub_alert_error('已经是第一页了');
        return false;
    }
    if(positionupi <1){
        pub_alert_error('参数有误');
        return false;
    }
    positionseachList(positionupi);
}

function positiondel(id) {
    var _token = $('input[name=_token]').val();
    pub_alert_confirm('/admin/ajax/templatedata/positiondel',{id:id,_token:_token},'删除成功!');
}

function positionsubedit() {
    var data =$('#positioneditfrom').serialize();
    $.ajax({
        url: $('#positioneditfrom').attr('action'),
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

function selectchage(th) {

    $(".subselect").css('display','none');
    $(".subselect").attr('disabled',true);
    if($(th).val() == 0 ){
        $('#subpositionselect').css('display','block');
        $('#subpositionselect').removeAttr('disabled');
        $('#subpositionselect').find('option').eq(0).prop('selected',true);
    }else if($(th).val() == -1){
        $('#subpositionselect').css('display','block');
        $('#subpositionselect').find('option').eq(0).prop('selected',true);
    }else{
        $('#subpositionselect'+$(th).val()).css('display','block');
        $('#subpositionselect'+$(th).val()).removeAttr('disabled');
        $('#subpositionselect'+$(th).val()).find('option').eq(0).prop('selected',true);
    }
}

function selectchages(th) {
    $(".adderselect").css('display','none');
    $(".adderselect").attr('disabled',true);
    if($(th).val() == 0){
        $("#erselect").css('display','none');
        $(".adderselect").css('display','none');
        $(".adderselect").attr('disabled',true);
    }else{
        $("#erselect").css('display','block');
        $("#subpositionselects"+$(th).val()).css('display','block');
        $("#subpositionselects"+$(th).val()).removeAttr('disabled');
    }
}







