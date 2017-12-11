/**
 * Created by Administrator on 2016/7/25.
 */

tatol = 0;
tatolPage = 0;
currenpPge = 0;
nexti = 0;
upi = 0;
vardis = 3;
num = 20;
order = 'created_at';
orderBy = 'DESC';
function seachList(pcurrenpPge,dis){
    
    var data =$('#accountFrom').serialize();
    $.ajax({
        url: $('#accountFrom').attr('action'),
        type: 'POST',
        dataType: 'json',
        data: data+'&currenpPge='+pcurrenpPge+'&isDisabled='+dis+'&num='+num+'&orderBy='+orderBy+'&order='+order,
        beforeSend: function () {
            loadingstart();
        },
    })
    .done(function(r) {
        var html ='';
        if(r.status == 1){
            $.each(r.data,function (name,vale) {
                html +='<tr>';
                html += '<td class="center"><label class="position-relative"><input class="ace" name ="id" value="'+vale.id+'" type="checkbox"><span class="lbl"></span></label></td>';
                html += '<td>'+vale.id+' </td>';
                html += '<td>'+vale.display_name+' </td>';
                html += '<td>'+vale.email+' </td>';
                html += '<td>'+vale.mobile+' </td>';
                html += '<td>'+vale.group_id+' </td>';
                html += '<td>'+vale.created_at+' </td>';
                html += '<td>'+vale.activity+'</td>';
                html += '<td>'+vale.duration+'</td>';
                html += '<td><button onclick = "edituser('+vale.id+')" class="btn btn-xs btn-success">编辑</button> ';
                if(dis == 0){
                    html += '<button onclick = "fengjin('+vale.id+',this)" class="btn btn-xs btn-success">封禁用户</button>';
                }
                html += '</td>';
                html +='</tr>';
            })
            $('#userTbody').html(html);
            tatol = r.total;
            tatolPage = r.totalPage;
            currenpPge = r.currenpPge;
            nexti = r.next;
            upi = r.up;
            $('#tatol').text(tatol);
            $('#tatolPage').text(tatolPage);
            $('#currenpPge').text(currenpPge);

        }else{
            $('#userTbody').html('<tr><td colspan="10">没有记录！</td></tr>');
        }
        vardis = dis
        changeAttr(dis);
        loadingend();
    })
    .fail(function(XMLHttpRequest, textStatus, errorThrown) {
        if(textStatus == 'error'){
            var obj = JSON.parse(XMLHttpRequest.responseText);
            var errors = obj.errors;
            $.each(errors,function (name,vale) {
                pub_alert_error(vale);
            })
        }
        loadingend();
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
    seachList(nexti,vardis);
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
    seachList(upi,vardis);
}

seachList(1,0);

function fengjin(id) {
        pub_alert_confirm('/admin/ajax/account/disableduser',{id:id},'封禁用户成功!');
}

function changeAttr(dis) {
    if(dis == 0){
        $('#dis0').attr({
            'class':'btn btn-primary'
        });
        $('#dis1').attr({
            'class':'btn'
        });
        $('.table-header').text('用户列表');
    }else{
        $('#dis1').attr({
            'class':'btn btn-primary'
        });
        $('#dis0').attr({
            'class':'btn'
        });
        $('.table-header').text('封禁用户列表');

    }
}

function edituser(id) {
    window.location.href="/admin/account/edit/id/"+id;
}

function selectcount(th) {
    num = $(th).val();
    seachList(1);
}

function orderby(or, th) {
    $('.grforder').remove();
    if(or == order){
        if(orderBy == 'DESC'){
            orderBy = 'ASC';
            $(th).after('<span class="grforder">&nbsp;&nbsp;<i class="ace-icon fa fa-arrow-up"></i></span>');
        }else{
            orderBy = 'DESC';
            $(th).after('<span class="grforder">&nbsp;&nbsp;<i class="ace-icon fa fa-arrow-down"></i></span>')
        }
    }else{
        order = or;
        orderBy ='DESC';
        $(th).after('<span class="grforder">&nbsp;&nbsp;<i class="ace-icon fa fa-arrow-down"></i></span>')
    }
    seachList(1);
}

