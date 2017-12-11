/**
 * Created by Administrator on 2016/8/1.
 */
tatol = 0;
tatolPage = 0;
currenpPge = 0;
nexti = 0;
upi = 0;
order = 'updated_at';
orderBy = 'DESC';
num = 20;
function seachList(pcurrenpPge){

    var data =$('#questionsFrom').serialize();
    $.ajax({
        url: $('#questionsFrom').attr('action'),
        type: 'POST',
        dataType: 'json',
        data: data+'&currenpPge='+pcurrenpPge+'&orderBy='+orderBy+'&order='+order+'&num='+num,
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
                    var hot ='';
                    if(vale.is_hot == 1){
                        hot = '<span class="label label-lg label-yellow arrowed-in">热门</span>'
                    }
                    html += '<td><span style="display:inline-block;width: 300px;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">'+vale.subject+' </span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+hot+'</td>';

                    var answered = vale.answered ;
                    if(vale.answered != 0){
                        answered = '<a href="/admin/answered/'+vale.id+'">'+answered+'</a>'
                    }
                    html += '<td>'+answered+' </td>';
                    html += '<td>'+vale.stared+' </td>';
                    html += '<td>'+vale.viewed+' </td>';
                    html += '<td>'+vale.display_name+' </td>';
                    html += '<td>'+vale.updated_at+' </td>';
                    html += '<td>';
                    html += '<button class="btn btn-xs btn-success" data-content="'+vale.caozuo+'" title="操作历史" data-placement="top" data-rel="popover" class="btn btn-info btn-sm popover-info" data-original-title="Some Info">查看历史</button> ';

                    html += '<button  onclick = "edituser('+vale.id+')" class="btn btn-xs btn-success">编辑</button> ';
                    if(vale.status == 0){
                        html += '<button  onclick = "delquetion('+vale.id+')" class="btn btn-xs btn-success">删除</button> ';
                    }

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





function edituser(id) {
    window.location.href="/admin/questions/edit/id/"+id;
}

function delquetion(id) {
    pub_alert_confirm('/admin/ajax/questions/del',{id:id},'删除成功!');
}


function subedit() {
    var data =$('#validation-form').serialize();
    $.ajax({
        url: $('#validation-form').attr('action'),
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

function recommend(type) {

    var is_subimt = false;
    $("input[name='id[]']").each(function(){
        if(this.checked==true){
            is_subimt = true;
        }
    });

    if(is_subimt === false){
        pub_alert_error('请选择问题');
    }

    if(!is_subimt){
        return is_subimt;
    }

    if(type == 1){
        if($('td input:checked').length > 3){

            pub_alert_error('热门问题最多推介三个');
            return false;
        }
    }

    var input = new Array();
    $('td input:checked').each(function (index,value) {
        input[index] = $(this).val();
    })

    $.ajax({
        url: $('#hotFrom').attr('action'),
        type: 'POST',
        dataType: 'json',
        data: {_token:$('input[name = _token]').val(),id:input,type:type},
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
            pub_alert_success('操作成功');
            window.location.reload();
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




