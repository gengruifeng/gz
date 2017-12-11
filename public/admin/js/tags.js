/**
 * Created by Administrator on 2016/8/3.
 */
tatol = 0;
tatolPage = 0;
currenpPge = 0;
nexti = 0;
upi = 0;
order = 'created_at';
orderBy = 'DESC';
num = 20;
function seachList(pcurrenpPge){

    var data =$('#tagsFrom').serialize();
    $.ajax({
        url: $('#tagsFrom').attr('action'),
        type: 'POST',
        dataType: 'json',
        data: data+'&currenpPge='+pcurrenpPge+'&order='+order+'&orderBy='+orderBy+'&num='+num,
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
                    html += '<td>'+vale.name+' </td>';
                    html += '<td>'+vale.tagged_answers+' </td>';
                    html += '<td>'+vale.tagged_articles+' </td>';
                    html += '<td>'+vale.display_name+' </td>';
                    html += '<td>'+vale.created_at+' </td>';
                    html += '<td>';
                    html += ' <button class="btn btn-xs btn-success" data-content="'+vale.caozuo+'" title="操作历史" data-placement="top" data-rel="popover" class="btn btn-info btn-sm popover-info" data-original-title="Some Info">查看历史</button> ';
                    html += ' <button  onclick = "addtags(2,'+vale.id+',\''+vale.name+'\')" class="btn btn-xs btn-success">编辑</button> ';
                    html += ' <button  onclick = "pub_alert_confirm(\'/admin/ajax/tags/del\',{id:'+vale.id+'},\'删除成功!\')" class="btn btn-xs btn-success">删除</button> ';
                    html += '</td>';
                    html +='</tr>';
                })
                $('#questionsTbody').html(html);
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
    if (currenpPge == 1) {
        pub_alert_error('已经是第一页了');
        return false;
    }
    if (upi < 1) {
        pub_alert_error('参数有误');
        return false;
    }
    seachList(upi);
}

// function orderbyfunc(ziduan,th){
//
//     if($("#"+ziduan).attr('class')!=undefined){
//
//         if(orderBy == 'desc'){
//             orderBy = 'asc'
//             $(th).find('i').attr('class','fa fa-angle-up');
//         }else{
//           
//             orderBy = 'desc';
//             $(th).find('i').attr('class','fa fa-angle-down');
//         }
//     }else{
//         $('thead').find('i').each(function(){
//             this.remove();
//         })
//         $(th).append('<i id ="'+ziduan+'" class="fa fa-angle-down"></i>');
//         orderBy = 'desc';
//     }
//     order = ziduan;
//
//     seachList(1);
// }

function addtags(type,id,name){
    if(type ==1){
        $("#addname").val('');
        $("#blue").text('添加标签');
        $('#tijiao').attr({
            'onclick':'subaddtags()'
        });
    }else{
        $("#addname").val(name);
        $("#blue").text('编辑标签');
        $("#id").val(id);
        $('#tijiao').attr({
            'onclick':'subedittags()'
        });
    }
    public_alert_bootbox();
    
}

function subaddtags(th) {
    var data =$('#edittagsfrom').serialize();
    $.ajax({
        url: '/admin/ajax/tags/add',
        type: 'POST',
        dataType: 'json',
        data: data,
        beforeSend: function () {
            loadingstart();
        },
    })

        .fail(function(XMLHttpRequest, textStatus, errorThrown) {
            if(textStatus == 'error'){
                loadingend();
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

function subedittags() {
    var data =$('#edittagsfrom').serialize();
    $.ajax({
        url: '/admin/ajax/tags/edit',
        type: 'POST',
        dataType: 'json',
        data: data,
        beforeSend: function () {
            loadingstart();
        },
    })

        .fail(function(XMLHttpRequest, textStatus, errorThrown) {
            if(textStatus == 'error'){
                loadingend();
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

function selectCategories(cid,tid) {
    var data = {tag_id:tid,category_id:cid};
    $.ajax({
        url: '/admin/ajax/tags/addcategories',
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
            }
        })
}

function delSelectCategories(cid,tid) {
    var data = {tag_id:tid,category_id:cid};
    $.ajax({
        url: '/admin/ajax/tags/delcategories',
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
                pub_alert_success('删除成功');
            }
        })
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

function selectcount(th) {
    num = $(th).val();
    seachList(1);
}