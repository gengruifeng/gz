/**
 * Created by Administrator on 2016/8/2.
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

    var data =$('#articlesFrom').serialize();
    $.ajax({
        url: $('#articlesFrom').attr('action'),
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
                    html += '<td>'+vale.id+' </td>';
                    var subject = '';
                    if(vale.standard == '审核通过'){
                        subject = '<a target="_blank" href="/articles/'+vale.id+'">'+vale.subject+'</a>'
                    }else{
                        subject = '<a target="_blank" href="/admin/articlesview/'+vale.id+'">'+vale.subject+'</a>'
                    }
                    html += '<td><span style="display:inline-block;width: 300px;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">'+subject+' </span></td>';
                    var num = vale.num ;
                    if(vale.num != 0){
                        num = '<a href="/admin/articlecomment/'+vale.id+'">'+vale.num+'</a>'
                    }
                    html += '<td>'+num+' </td>';
                    html += '<td>'+vale.viewed+' </td>';
                    html += '<td>'+vale.display_name+' </td>';
                    html += '<td>'+vale.updated_at+' </td>';
                    html += '<td>'+vale.standard+' </td>';
                    html += '<td>';
                    html += ' <button class="btn btn-xs btn-success" data-content="'+vale.caozuo+'" title="操作历史" data-placement="top" data-rel="popover" class="btn btn-info btn-sm popover-info" data-original-title="Some Info">查看历史</button> ';
                    html += ' <button  onclick = "edit('+vale.id+')" class="btn btn-xs btn-success">编辑</button> ';

                    if(vale.standard == '审核中'){
                        html += ' <button  onclick = "checkarticles('+vale.id+',1,\'\')" class="btn btn-xs btn-success">审核通过</button> ';
                        html += ' <button  onclick = "boxcheckarticles('+vale.id+',2)" class="btn btn-xs btn-success">审核不通过</button> ';
                        html += ' <button  onclick = "boxcheckarticles('+vale.id+',3)" class="btn btn-xs btn-success">删除</button> ';
                    }else if(vale.standard == '审核通过' || vale.standard == '审核不通过'){
                        html += ' <button  onclick = "boxcheckarticles('+vale.id+',3)" class="btn btn-xs btn-success">删除</button> ';
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

function checkarticles(id,type,reason) {
    var data = {id:id,type:type,reason:reason}
    if(type == 1){
        pub_alert_confirm('/admin/ajax/articles/check',data,'审核成功');
    }else{
        $.ajax({
            url: '/admin/ajax/articles/check',
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
                if( type == 2){
                    pub_alert_success('审核成功');
                }else if(type == 3){
                    pub_alert_success('删除成功');
                }
                window.location.reload();
            }
        })
    }
}

function boxcheckarticles(id,type) {
    if(type == 2){
        $('.blue').text('审核文章');
        $('#reason').attr('placeholder','审核不通过原因');
    }else if(type == 3){
        $('.blue').text('确认要删除吗？');
        $('#reason').attr('placeholder','删除之后，文章将不再显示在页面上。如果文章已经有评论，请慎重删除');
    }
    $('#reason').val('');
    $('#id').val(id);
    $('#type').val(type);
    public_alert_bootbox();
}

function subcheck() {
    checkarticles($('#id').val(),$('#type').val(),$('#reason').val());
}
function edit(id) {
    window.location.href="/admin/articles/edit/id/"+id;
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



function articlesUpload (token,id) {
    var uploader = new plupload.Uploader({ //实例化一个plupload上传对象
        browse_button : 'imgUpload',
        url : '/admin/ajax/articles/upload',
        filters: {
            mime_types : [
                { title : "图片文件", extensions : "jpg,gif,png,bmp,doc" },
            ],
            max_file_size : '5120kb', //最大只能上传5M的文件
            prevent_duplicates : false, //不允许队列中存在重复文件
            width : 200,
            height : 200,

        },

    });
    uploader.init(); //初始化
    uploader.settings.multipart_params = {_token:  token, id:id}

    //绑定文件添加进队列事件
    uploader.bind('FilesAdded',function(uploader,files){

        var reader = new FileReader();
        reader.readAsDataURL(files[0].getNative());
        reader.onload = (function (e) {
            var image = new Image();
            image.src = e.target.result;
            image.onload = function () {
                if (this.width == 260 && this.height  == 190) {
                    $.each(uploader.files, function (i, file) {
                        if (uploader.files.length <= 1) {
                            return;
                        }
                        uploader.removeFile(file);
                    });

                    for(var i = 0, len = files.length; i<len; i++){
                        !function(i){
                            previewImage(files[i],function(imgsrc){
                                $("#suteng").remove();
                                $("#72l22").append('<img class="col-xs-offset-3" id="suteng" style="float: left;width: 260px;height: 190px;" src="'+ imgsrc +'" />');
                            })
                        }(i);
                    }
                } else {
                    pub_alert_error('图片尺寸不符，应：260*190')
                    uploader.stop();
                    uploader.removeFile(files[0])
                }

            };
        });


    });


    //绑定文件添加进队列事件
    uploader.bind('Error',function(uploader,errObject){
        if(errObject.status == 500){
            info = JSON.parse(errObject.response)
            pub_alert_error(info.description);
        }else if(errObject.status == 200){
            pub_alert_success( '上传成功');
        }else{
            pub_alert_error( errObject.message);
        }
    });

    uploader.bind('FileUploaded',function(uploader,file,responseObject){
        if(responseObject.status == 200){
            pub_alert_success('上传成功！');
        }
    });

    //plupload中为我们提供了mOxie对象
    //有关mOxie的介绍和说明请看：https://github.com/moxiecode/moxie/wiki/API
    //如果你不想了解那么多的话，那就照抄本示例的代码来得到预览的图片吧
    function previewImage(file,callback){//file为plupload事件监听函数参数中的file对象,callback为预览图片准备完成的回调函数
        if(!file || !/image\//.test(file.type)) return; //确保文件是图片
        if(file.type=='image/gif'){//gif使用FileReader进行预览,因为mOxie.Image只支持jpg和png
            var fr = new mOxie.FileReader();
            fr.onload = function(){
                callback(fr.result);
                fr.destroy();
                fr = null;
            }
            fr.readAsDataURL(file.getSource());
        }else{
            var preloader = new mOxie.Image();
            preloader.onload = function() {
                preloader.downsize( 300, 300 );//先压缩一下要预览的图片,宽300，高300
                var imgsrc = preloader.type=='image/jpeg' ? preloader.getAsDataURL('image/jpeg',80) : preloader.getAsDataURL(); //得到图片src,实质为一个base64编码的数据
                callback && callback(imgsrc); //callback传入的参数为预览图片的url
                preloader.destroy();
                preloader = null;
            };
            preloader.load( file.getSource() );
        }
    }
    // //上传按钮
    $('#wodeche').click(function () {
        uploader.start(); //开始上传
    });

}

function start() {

}
function selectcount(th) {
    num = $(th).val();
    seachList(1);
}