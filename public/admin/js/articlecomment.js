/**
 * Created by Administrator on 2016/8/1.
 */
tatol = 0;
tatolPage = 0;
currenpPge = 0;
nexti = 0;
upi = 0;
function seachList(pcurrenpPge){

    var data =$('#questionsFrom').serialize();
    $.ajax({
        url: '/admin/ajax/articlecomment/list',
        type: 'POST',
        dataType: 'json',
        data: 'currenpPge='+pcurrenpPge+'&articleid='+articleid+'&_token='+token,
        beforeSend: function () {
            loadingstart();
        },
    })
        .done(function(r) {
            var html ='';
            if(r.status == 1){
                $.each(r.data,function (name,vale) {
                    html +='<tr>';
                    // html += '<td class="center"><label class="position-relative"><input class="ace" name ="id[]" value="'+vale.id+'" type="checkbox"><span class="lbl"></span></label></td>';
                    html += '<td class="center"><label class="position-relative"><input name="id[]" value="'+vale.id+'" type="checkbox" class="ace"><span class="lbl"></span></label></td>'
                    html += '<td>'+vale.id+' </td>';

                    html += '<td><span class="content'+vale.id+'" style="display:inline-block;width: 300px;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">'+vale.content+' </span></td>';

                    html += '<td>'+vale.display_name+' </td>';
                    html += '<td>'+vale.created_at+' </td>';
                    html += '<td>';
                    html += '<button  onclick = "edit('+vale.id+')" class="btn btn-xs btn-success">编辑</button> ';
                    html += '<button  onclick = "del('+vale.id+')" class="btn btn-xs btn-success">删除</button> ';

                    html += '</td>';
                    html +='</tr>';
                })
                $('#articleTbody').html(html);
                tatol = r.total;
                tatolPage = r.totalPage;
                currenpPge = r.currenpPge;
                nexti = r.next;
                upi = r.up;
                $('#tatol').text(tatol);
                $('#tatolPage').text(tatolPage);
                $('#currenpPge').text(currenpPge);
                $('[data-rel=tooltip]').tooltip();
                $('[data-rel=popover]').popover({html:true});
            }else{
                $('#questionsTbody').html('<tr><td colspan="10">没有记录！</td></tr>');
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

function next() {
    if(currenpPge ==tatolPage){
        pub_alert_error('已经是最后一页了');
        return false;
    }
    if(nexti > tatolPage){
        pub_alert_error('参数有误');
        return false;
    }
    seachList(nexti);
}

function up() {
    if(currenpPge ==1){
        pub_alert_error('已经是第一页了');
        return false;
    }
    if(upi <1){
        pub_alert_error('参数有误');
        return false;
    }
    seachList(upi);
}


function edit(id) {
    if(id == undefined){
        pub_alert_error('参数错误')
        return 0;
    }
    detail =$('.content'+id).text();
    $('textarea[name=content]').val(detail);
    $('input[name=id]').val(id);
    public_alert_bootbox('pub_edit_bootbox');
}

function del(id) {
    pub_alert_confirm('/admin/ajax/articlecomment/del',{id:id},'删除成功!');
}


function subedit() {
    var data =$('#answerFrom').serialize();
    $.ajax({
        url: '/admin/ajax/articlecomment/edit',
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
