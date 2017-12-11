var token = $("input[name = '_token']").val();
new Simditor({
    textarea: $("#editor"),
    placeholder: '请填写文章的正文',
    defaultImage: '',
    upload: {
        url: '/ajax/questions/askupload',
        params: {_token:token},
        fileKey: 'file',
        connectionCount: 3,
        leaveConfirm: '正在上传图片，您确定要终止吗？'
    },
    tabIndent: false,
    toolbar: [
        'title',
        'bold',
        'italic',
        'underline',
        'image',
    ],
    toolbarFloat: true,
    toolbarFloatOffset: 0,
    toolbarHidden: false,
    pasteImage: true,
});
$(function() {
    checkCookie();
    function getCookie(c_name)
    {
        if (document.cookie.length>0)
        {
            c_start=document.cookie.indexOf(c_name + "=")
            if (c_start!=-1)
            {
                c_start=c_start + c_name.length+1
                c_end=document.cookie.indexOf(";",c_start)
                if (c_end==-1) c_end=document.cookie.length
                return unescape(document.cookie.substring(c_start,c_end))
            }
        }
        return ""
    }
    function checkCookie()
    {
        var type = false;
        articlehelp=getCookie('articlehelp')
        if (articlehelp!=null && articlehelp!="")
        {
            var help = $('#is_help').val();
            var arr = [];
            arr =articlehelp.split(";");
            for (i=0;i<arr.length;i++){
                if(arr[i]==help){
                    type = true;
                }
            }
        }
        if(type === false){
            $('html').addClass('whide');
            $('.shadows-article').removeClass('hide');
        }
    }
    $('.btn-close').click(function(){
        $('.shadows-tips').remove();
        $('html').removeClass('whide');
    })
    $('.btn-not').click(function(){
        var help = $('#is_help').val();
        var exdate=new Date();
        exdate.setDate(exdate.getDate()+30)
        articlehelp=getCookie('articlehelp');
        if (articlehelp!=null && articlehelp!="")
        {
            help += ";"+articlehelp;
        }
        document.cookie='articlehelp'+ "=" +escape(help)+
            ";expires="+exdate.toGMTString();
        $('.shadows-tips').remove();

    });
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
//添加文章
$('#compose').on('click',function () {
    $("#compose").attr('disabled',true);
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
        url:$("#article-form")[0].action,
        data:{subject:subject,detail:detail,tags:arr,_token:token},
        dataType: "json",
        success: function(data, status, xhr) {
            dialogcom_yes_go('小编将尽快审核您的文章，请耐心等待哦~',"/articles/" + data.id)
        },
        error: function(xhr, status, error) {

            if(401 === xhr.status ){
                $("#compose").attr('disabled',false);
                dialoglogin();
                return false;
            }
            if(403 === xhr.status && 403 != xhr.responseJSON.error_id){
                $("#compose").attr('disabled',false);
                dialogcom_wrong(xhr.responseJSON);
                return false;
            }
            if (400 === xhr.status && typeof xhr.responseJSON.errors === 'object') {
                $('#compose').attr("disabled",false);
                dialogcom_wrong(xhr.responseJSON.errors[0].message);
            } else {
                $('#compose').attr("disabled",false);
                dialogcom_wrong(xhr.responseJSON.description);
            }
        }
    })
})


