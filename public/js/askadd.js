var token =  $("input[name = '_token']").val();
new Simditor({
    textarea: $("#editor"),
    placeholder: '请详细描述你的问题，认真的提问才能带来专业的回答。包括背景信息，具体疑问、参考资料等。如有多个疑问，建议分条说明。',
    defaultImage: '',
    upload: {
        url: '/ajax/questions/askupload',
        params: {_token:token},
        fileKey: 'file',
        connectionCount: 1,
        leaveConfirm: '正在上传图片，您确定要终止吗？'
    },
    tabIndent: false,
    toolbar: [
        'title',
        'bold',
        'italic',
        'underline',
        'image'
    ],
    toolbarFloat: true,
    toolbarFloatOffset: 0,
    toolbarHidden: false,
    pasteImage: true
});
$(function() {
    checkCookie();
    function getCookie(c_name)
    {
        if (document.cookie.length>0)
        {
            c_start=document.cookie.indexOf(c_name + "=");
            if (c_start!=-1)
            {
                c_start=c_start + c_name.length+1;
                c_end=document.cookie.indexOf(";",c_start);
                if (c_end==-1) c_end=document.cookie.length;
                return unescape(document.cookie.substring(c_start,c_end))
            }
        }
        return ""
    }
    function checkCookie()
    {

        var type = false;
        askhelp=getCookie('askhelp');
        if (askhelp!=null && askhelp!="")
        {
            var help = $('#is_help').val();
            var arr = [];
            arr =askhelp.split(";");
            for (i=0;i<arr.length;i++){
                if(arr[i]==help){
                    type = true;
                }
            }
        }
        if(type === false){
            $('html').addClass('whide');
            $('.shadows-question').removeClass('hide');
        }
    }
    $('.btn-close').click(function(){
        $('.shadows-tips').remove();
        $('html').removeClass('whide');
    });
    $('.btn-not').click(function(){
        var help = $('#is_help').val();
        var exdate=new Date();
        exdate.setDate(exdate.getDate()+30);
        askhelp=getCookie('askhelp');
        if (askhelp!=null && askhelp!="")
        {
            help += ";"+askhelp;
        }
        document.cookie='askhelp'+ "=" +escape(help)+
            ";expires="+exdate.toGMTString();
        $('.shadows-tips').remove();

    });
    $('#inputnumber').text(50 - $('#subject').val().length);
    //添加标签
    $('#search_tag').selectize({
        maxItems:5,
        create: function(input){
            if(input.length >6){
                dialogcom_warn("标签长度最多为6个字符！");
                return false;
            }
            return{
                name:input
            }
        },
        valueField: 'name',
        labelField: 'name',
        searchField: 'name',

        options: [],
        render: {
            option: function(item, escape) {
                var tags = [];
                for (var i = 0, n = item.length; i < n; i++) {
                    tags.push('<span>' + escape(item[i].name) + '</span>');
                }
                return '<div>' +
                    '<span class="name">' + escape(item.name) + '</span>' +
                    '</div>';
            }
        },
        load: function(query, callback) {
            if (!query.length) return callback();
            $.ajax({
                url: '/tags/search',
                type: 'GET',
                dataType: 'json',
                data: {
                    q: query
                },
                error: function() {
                    callback();
                },
                success: function(res) {

                    callback(res);

                }
            });
        }
    });
});

//添加
$('#askadd').on('click',function () {
    $("#askadd").attr('disabled',true);
    var detail = $("#editor").val();
    var subject = $('#subject').val();
    var _arr=$(".item");
    var arr='';
    for(var i=0;i<_arr.length;i++){
        arr +=_arr.eq(i).text()+';';
    }
    if(arr.substr(arr.length-1)==";"){
        arr = arr.substr(0,arr.length - 1)
    }
    $.ajax({
        type:"post",
        url:"/ajax/questions/ask",
        data:{subject:subject,detail:detail,tags:arr,_token:token},
        dataType: "json",
        success:function(data) {
            var jsonobj=eval(data);
            dialogcom_yes_go('提问成功，快去通知你的小伙伴们回答吧～',"/questions/"+jsonobj.question_id);
        },
        error: function(xhr, status, error) {
            if(401 === xhr.status){
                $("#askadd").attr('disabled',false);
                dialoglogin();
                return false;
            }
            if(403 === xhr.status){
                $("#askadd").attr('disabled',false);
                dialogcom_wrong(xhr.responseJSON);
                return false;
            }
            if (400 === xhr.status) {
                $("#askadd").attr('disabled',false);
                dialogcom_wrong(xhr.responseJSON.errors[0].message);
            } else {
                $("#askadd").attr('disabled',false);
                dialogcom_wrong(xhr.responseJSON.description);
            }
        }
    })
});
//编辑
$('#askedit').on('click',function () {
    $("#askadd").attr('disabled',true);
    var askid = $('#askid').val();
    var detail = $("#editor").val();
    var subject = $('#subject').val();
    var _arr=$(".item");
    var arr='';
    for(var i=0;i<_arr.length;i++){
        arr +=_arr.eq(i).text()+';';
    }
    if(arr.substr(arr.length-1)==";"){
        arr = arr.substr(0,arr.length - 1)
    }
    $.ajax({
        type:"post",
        url:"/ajax/questions/askedit",
        async : false,
        data:{subject:subject,detail:detail,tags:arr,askid:askid,_token:token},
        dataType: "json",
        success:function(data) {
            var str = '';
            var jsonobj=eval(data);
            dialogcom_yes_go('编辑成功～',"/questions/"+jsonobj.question_id);
        },
        error: function(xhr, status, error) {
            if(401 === xhr.status){
                $("#askadd").attr('disabled',false);
                dialoglogin();
                return false;
            }
            if(403 === xhr.status){
                $("#askadd").attr('disabled',false);
                dialogcom_wrong(xhr.responseJSON);
                return false;
            }
            if (400 === xhr.status) {
                $("#askadd").attr('disabled',false);
                dialogcom_wrong(xhr.responseJSON.errors[0].message);
            } else {
                $("#askadd").attr('disabled',false);
                dialogcom_wrong(xhr.responseJSON.description);
            }
        }
    })
});

