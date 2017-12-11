/**
 * Created by Administrator on 2016/8/1.  suteng
 */
sutengtatol = 0;
sutengtatolPage = 0;
sutengcurrenpPge = 0;
sutengnexti = 0;
sutengupi = 0;
function sutengseachList(pcurrenpPge){

    var data =$('#professionsFrom').serialize();
    $.ajax({
        url: $('#professionsFrom').attr('action'),
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
                    html += '<td><span style="display:inline-block;width: 300px;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">'+vale.title+' </span></td>';
                    html += '<td>'+vale.created_at+' </td>';
                    html += '<td>';
                    html += '<button  onclick = "addprofessions(2,'+vale.id+',\''+vale.title+'\')" class="btn btn-xs btn-success">编辑</button> ';
                    html += ' <button  onclick = "sutengdel('+vale.id+')" class="btn btn-xs btn-success">删除</button> ';

                    html += '</td>';
                    html +='</tr>';
                })
                $('#professionsTbody').html(html);
                sutengtatol = r.total;
                sutengtatolPage = r.totalPage;
                sutengcurrenpPge = r.currenpPge;
                sutengnexti = r.next;
                sutengupi = r.up;
                $('#tatol').text(sutengtatol);
                $('#tatolPage').text(sutengtatolPage);
                $('#currenpPge').text(sutengcurrenpPge);
                $('[data-rel=tooltip]').tooltip();
                $('[data-rel=popover]').popover({html:true});
            }else{
                $('#professionsTbody').html('<tr><td colspan="10">没有记录！</td></tr>');
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


function addprofessions(type,id,name){
    if(type ==1){
        $("#title").val('');
        $("#blue").text('添加求职意向');
        $('#professionseditfrom').attr({
            'action':'/admin/ajax/templatedata/professionsadd'
        });
    }else{
        $("#title").val(name);
        $("#blue").text('编辑求职意向');
        $("#id").val(id);

        $('#professionseditfrom').attr({
            'action':'/admin/ajax/templatedata/professionsedit'
        });
    }
    public_alert_bootbox();

}


function sutengnext() {
    if(sutengcurrenpPge ==sutengtatolPage){
        pub_alert_error('已经是最后一页了');
        return false;
    }
    if(sutengnexti > sutengtatolPage){
        pub_alert_error('参数有误');
        return false;
    }
    sutengseachList(sutengnexti);
}

function sutengup() {
    if(sutengcurrenpPge ==1){
        pub_alert_error('已经是第一页了');
        return false;
    }
    if(sutengupi <1){
        pub_alert_error('参数有误');
        return false;
    }
    sutengseachList(sutengupi);
}

function sutengdel(id) {
    var _token = $('input[name=_token]').val();
    pub_alert_confirm('/admin/ajax/templatedata/professionsdel',{id:id,_token:_token},'删除成功!');
}

function sutengsubedit() {
    var data =$('#professionseditfrom').serialize();
    $.ajax({
        url: $('#professionseditfrom').attr('action'),
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






