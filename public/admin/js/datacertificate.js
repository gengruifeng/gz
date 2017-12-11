/**
 * Created by Administrator on 2016/8/1.  certificate
 */
certificatetatol = 0;
certificatetatolPage = 0;
certificatecurrenpPge = 0;
certificatenexti = 0;
certificateupi = 0;
function certificateseachList(pcurrenpPge){

    var data =$('#certificateFrom').serialize();
    $.ajax({
        url: $('#certificateFrom').attr('action'),
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
                    html += '<button  onclick = "addcertificate(2,'+vale.id+',\''+vale.name+'\','+vale.pid+','+vale.order+')" class="btn btn-xs btn-success">编辑</button> ';
                    html += ' <button  onclick = "certificatedel('+vale.id+')" class="btn btn-xs btn-success">删除</button> ';

                    html += '</td>';
                    html +='</tr>';
                })
                $('#certificateTbody').html(html);
                certificatetatol = r.total;
                certificatetatolPage = r.totalPage;
                certificatecurrenpPge = r.currenpPge;
                certificatenexti = r.next;
                certificateupi = r.up;
                $('#certificatetatol').text(certificatetatol);
                $('#certificatetatolPage').text(certificatetatolPage);
                $('#certificatecurrenpPge').text(certificatecurrenpPge);
                $('[data-rel=tooltip]').tooltip();
                $('[data-rel=popover]').popover({html:true});
            }else{
                $('#certificateTbody').html('<tr><td colspan="10">没有记录！</td></tr>');
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


function addcertificate(type,id,name,one,order){
    if(type ==1){
        $("#certificateaddname").val('');
        $("#certificateaddorder").val('');

        $("#certificateaddblue").text('添加证书');
        $('#certificateselects').find('option').eq(0).prop('selected',true);
        $("#certificateselects").removeAttr('disabled');
        $('#certificateeditfrom').attr({
            'action':'/admin/ajax/templatedata/certificateadd'
        });
    }else{
        $("#certificateaddname").val(name);
        $("#certificateaddblue").text('编辑证书');
        $("#certificateid").val(id);
        $("#certificateaddorder").val(order);
        $('#certificateeditfrom').attr({
            'action':'/admin/ajax/templatedata/certificateedit'
        });
        if(one == 0){
            $('#certificateselects').find('option').eq(0).prop('selected',true);

        }else{
            $('#certificateselects').find('option').each(function () {
                if($(this).attr('value') == one){
                    $(this).prop('selected',true);
                }
            });
        }
        $("#certificateselects").attr('disabled',true);
    }
    public_alert_bootbox('pub_edit_bootbox_certificate');

}


function certificatenext() {
    if(certificatecurrenpPge ==certificatetatolPage){
        pub_alert_error('已经是最后一页了');
        return false;
    }
    if(certificatenexti > certificatetatolPage){
        pub_alert_error('参数有误');
        return false;
    }
    certificateseachList(certificatenexti);
}

function certificateup() {
    if(certificatecurrenpPge ==1){
        pub_alert_error('已经是第一页了');
        return false;
    }
    if(certificateupi <1){
        pub_alert_error('参数有误');
        return false;
    }
    certificateseachList(certificateupi);
}

function certificatedel(id) {
    var _token = $('input[name=_token]').val();
    pub_alert_confirm('/admin/ajax/templatedata/certificatedel',{id:id,_token:_token},'删除成功!');
}

function certificatesubedit() {
    var data =$('#certificateeditfrom').serialize();
    $.ajax({
        url: $('#certificateeditfrom').attr('action'),
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