/**
 * Created by Administrator on 2016/8/1.
 */
tatol = 0;
tatolPage = 0;
currenpPge = 0;
nexti = 0;
upi = 0;
num = 100;
function seachList(pcurrenpPge){

    var data =$('#questionsFrom').serialize();
    $.ajax({
        url: $('#questionsFrom').attr('action'),
        type: 'POST',
        dataType: 'json',
        data: data+'&currenpPge='+pcurrenpPge+'&num='+num,
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
                    html += '<td><span style="display:inline-block;width: 300px;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">'+vale.subject+' </span></td>';
                    html += '<td>'+vale.display_name+' </td>';
                    html += '<td>'+vale.created_at+' </td>';
                    html += '<td>';
                    html += '<button  onclick = "editquestions('+vale.id+')" class="btn btn-xs btn-success">编辑</button> ';
                    html += '<button  onclick = "delquetion('+vale.id+')" class="btn btn-xs btn-success">删除</button> ';
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


function addquestions() {
    $('textarea[name=subject]').val('');
    $('textarea[name=detail]').val('');
    $('input[name=detail]').val('');
    $('.simditor-body').text('');
    $('.simditor-placeholder').css('display','block');



    $('#tag').find('option').each(function () {
        $(this).removeAttr('selected')
    });
    $('.chosen-select').trigger("chosen:updated");
    $("#questionsblue").text('添加问题');
    $('#questionsadd-form').attr({
        'action':'/admin/ajax/questiontool/add'
    });
    public_alert_bootbox('pub_edit_bootbox_questions');
}

function questionsaddajax() {


    var data =$('#questionsadd-form').serialize();
    $.ajax({
        url: $('#questionsadd-form').attr('action'),
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


function editquestions(id) {

    if(id == undefined){
        pub_alert_error('参数错误')
        return 0;
    }

    $.ajax({
        url: '/admin/ajax/questiontool/getone',
        type: 'POST',
        dataType: 'json',
        data: {id:id,_token:$('input[name=_token]').val()},

    })
    .done(function(r) {
        $('textarea[name=subject]').val(r.subject);
        $('textarea[name=detail]').val(r.detail);
        $('input[name=detail]').val(r.detail);
        $('input[name=id]').val(r.id);
        $('.simditor-body').html(r.detail);

        $('.simditor-placeholder').css('display','none');


        $('#tag').find('option').each(function () {
            $(this).removeAttr('selected')
        });

        $('#tag').find('option').each(function () {
            var ssss = $(this);
            $.each(r.tagsjson,function (name,vale) {
                if(ssss.attr('value') == vale){
                    ssss.prop('selected',true);
                }
            })

        });
        $('.chosen-select').trigger("chosen:updated");
        $("#questionsblue").text('编辑问题');
        $('#questionsadd-form').attr({
            'action':'/admin/ajax/questiontool/edit'
        });
        public_alert_bootbox('pub_edit_bootbox_questions');
    })
    .fail(function(XMLHttpRequest, textStatus, errorThrown) {
        if(textStatus == 'error'){
            var obj = JSON.parse(XMLHttpRequest.responseText);
            var errors = obj.errors;
            $.each(errors,function (name,vale) {
                pub_alert_error(vale);
            })
        }
    })


}


function delquetion(id) {
    pub_alert_confirm('/admin/ajax/questiontool/del',{id:id},'删除成功!');
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

function edituser() {
    questionuserseachList(1);
    userseachList(1);
    public_alert_bootbox('pub_edit_bootbox_user');

}

function fb() {
    pub_alert_confirm('/admin/ajax/questiontool/del',{id:1},'发布成功!','发布问题为200个，发布用户为20位，发布时间2016-10-26 9点至24点。');
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




