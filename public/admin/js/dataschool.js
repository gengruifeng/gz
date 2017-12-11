/**
 * Created by Administrator on 2016/8/1.  school
 */
schooltatol = 0;
schooltatolPage = 0;
schoolcurrenpPge = 0;
schoolnexti = 0;
schoolupi = 0;
function schoolseachList(pcurrenpPge){

    var data =$('#schoolFrom').serialize();
    $.ajax({
        url: $('#schoolFrom').attr('action'),
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
                    html += '<td><span>'+vale.cityname+' </span></td>';
                    html += '<td><span>'+vale.order+' </span></td>';
                    html += '<td>'+vale.created_at+' </td>';
                    html += '<td>';
                    html += '<button  onclick = "addschool(2,'+vale.id+',\''+vale.name+'\','+vale.cityid+','+vale.order+')" class="btn btn-xs btn-success">编辑</button> ';
                    html += ' <button  onclick = "schooldel('+vale.id+')" class="btn btn-xs btn-success">删除</button> ';

                    html += '</td>';
                    html +='</tr>';
                })
                $('#schoolTbody').html(html);
                schooltatol = r.total;
                schooltatolPage = r.totalPage;
                schoolcurrenpPge = r.currenpPge;
                schoolnexti = r.next;
                schoolupi = r.up;
                $('#schooltatol').text(schooltatol);
                $('#schooltatolPage').text(schooltatolPage);
                $('#schoolcurrenpPge').text(schoolcurrenpPge);
                $('[data-rel=tooltip]').tooltip();
                $('[data-rel=popover]').popover({html:true});
            }else{
                $('#schoolTbody').html('<tr><td colspan="10">没有记录！</td></tr>');
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


function addschool(type,id,name,one,order){
    if(type ==1){
        $("#schooladdname").val('');
        $("#schooladdorder").val('');

        $("#schooladdblue").text('添加院校');
        $('#schoolselects').find('option').eq(0).prop('selected',true);
        $('#schooleditfrom').attr({
            'action':'/admin/ajax/templatedata/schooladd'
        });
    }else{
        $("#schooladdname").val(name);
        $("#schooladdblue").text('编辑院校');
        $("#schoolid").val(id);
        $("#schooladdorder").val(order);
        $('#schooleditfrom').attr({
            'action':'/admin/ajax/templatedata/schooledit'
        });
        if(one == 0){
            $('#schoolselects').find('option').eq(0).prop('selected',true);

        }else{
            $('#schoolselects').find('option').each(function () {
                if($(this).attr('value') == one){
                    $(this).prop('selected',true);
                }
            });
        }
    }
    public_alert_bootbox('pub_edit_bootbox_school');

}


function schoolnext() {
    if(schoolcurrenpPge ==schooltatolPage){
        pub_alert_error('已经是最后一页了');
        return false;
    }
    if(schoolnexti > schooltatolPage){
        pub_alert_error('参数有误');
        return false;
    }
    schoolseachList(schoolnexti);
}

function schoolup() {
    if(schoolcurrenpPge ==1){
        pub_alert_error('已经是第一页了');
        return false;
    }
    if(schoolupi <1){
        pub_alert_error('参数有误');
        return false;
    }
    schoolseachList(schoolupi);
}

function schooldel(id) {
    var _token = $('input[name=_token]').val();
    pub_alert_confirm('/admin/ajax/templatedata/schooldel',{id:id,_token:_token},'删除成功!');
}

function schoolsubedit() {
    var data =$('#schooleditfrom').serialize();
    $.ajax({
        url: $('#schooleditfrom').attr('action'),
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