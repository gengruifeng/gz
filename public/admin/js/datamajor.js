/**
 * Created by Administrator on 2016/8/1.  major
 */
majortatol = 0;
majortatolPage = 0;
majorcurrenpPge = 0;
majornexti = 0;
majorupi = 0;
function majorseachList(pcurrenpPge){

    var data =$('#majorFrom').serialize();
    $.ajax({
        url: $('#majorFrom').attr('action'),
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
                    html += '<td>'+vale.created_at+' </td>';
                    html += '<td>';
                    html += '<button  onclick = "addmajor(2,'+vale.id+',\''+vale.name+'\')" class="btn btn-xs btn-success">编辑</button> ';
                    html += ' <button  onclick = "majordel('+vale.id+')" class="btn btn-xs btn-success">删除</button> ';

                    html += '</td>';
                    html +='</tr>';
                })
                $('#majorTbody').html(html);
                majortatol = r.total;
                majortatolPage = r.totalPage;
                majorcurrenpPge = r.currenpPge;
                majornexti = r.next;
                majorupi = r.up;
                $('#majortatol').text(majortatol);
                $('#majortatolPage').text(majortatolPage);
                $('#majorcurrenpPge').text(majorcurrenpPge);
                $('[data-rel=tooltip]').tooltip();
                $('[data-rel=popover]').popover({html:true});
            }else{
                $('#majorTbody').html('<tr><td colspan="10">没有记录！</td></tr>');
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


function addmajor(type,id,name){
    if(type ==1){
        $("#name").val('');
        $("#blue").text('添加专业');
        $('#majoreditfrom').attr({
            'action':'/admin/ajax/templatedata/majoradd'
        });
    }else{
        $("#name").val(name);
        $("#blue").text('编辑专业');
        $("#majorid").val(id);

        $('#majoreditfrom').attr({
            'action':'/admin/ajax/templatedata/majoredit'
        });
    }
    public_alert_bootbox('pub_edit_bootbox_major');

}


function majornext() {
    if(majorcurrenpPge ==majortatolPage){
        pub_alert_error('已经是最后一页了');
        return false;
    }
    if(majornexti > majortatolPage){
        pub_alert_error('参数有误');
        return false;
    }
    majorseachList(majornexti);
}

function majorup() {
    if(majorcurrenpPge ==1){
        pub_alert_error('已经是第一页了');
        return false;
    }
    if(majorupi <1){
        pub_alert_error('参数有误');
        return false;
    }
    majorseachList(majorupi);
}

function majordel(id) {
    var _token = $('input[name=_token]').val();
    pub_alert_confirm('/admin/ajax/templatedata/majordel',{id:id,_token:_token},'删除成功!');
}

function majorsubedit() {
    var data =$('#majoreditfrom').serialize();
    $.ajax({
        url: $('#majoreditfrom').attr('action'),
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






