/**
 * Created by Administrator on 2016/8/1.
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

    var data =$('#templateFrom').serialize();
    $.ajax({
        url: $('#templateFrom').attr('action'),
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
                    html += '<td><span style="display:inline-block;width: 300px;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">'+vale.subject+' </span></td>';
                    html += '<td>'+vale.downloaded+' </td>';
                    html += '<td>'+vale.created_at+' </td>';
                    html += '<td>';
                    html += '<button  onclick = "edit('+vale.id+')" class="btn btn-xs btn-success">编辑</button> ';
                    html += ' <button  onclick = "del('+vale.id+')" class="btn btn-xs btn-success">删除</button> ';

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


function upminiaturized (token) {
    var uploader = new plupload.Uploader({ //实例化一个plupload上传对象
        browse_button: 'upminiaturized',
        url: '/admin/ajax/template/upload',
        filters: {
            mime_types: [
                {title: "图片文件", extensions: "jpg,gif,png,bmp"},
            ],
            max_file_size: '5120kb', //最大只能上传5M的文件
            prevent_duplicates: false, //不允许队列中存在重复文件
            width: 200,
            height: 200,

        },

    });
    uploader.init(); //初始化
    uploader.settings.multipart_params = {_token: token, type : 'miniaturized'}

    //绑定文件添加进队列事件
    uploader.bind('FilesAdded', function (uploader, files) {

        var reader = new FileReader();
        reader.readAsDataURL(files[0].getNative());
        reader.onload = (function (e) {
            var image = new Image();
            image.src = e.target.result;
            image.onload = function () {
                if (this.width == 170 && this.height == 235) {
                    $.each(uploader.files, function (i, file) {
                        if (uploader.files.length <= 1) {
                            return;
                        }
                        uploader.removeFile(file);
                    });

                    for (var i = 0, len = files.length; i < len; i++) {
                        !function (i) {
                            uploader.start();
                        }(i);
                    }
                } else {
                    pub_alert_error('图片尺寸不符，应：170*235')
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
            var filename = JSON.parse(responseObject.response);
            $('#miniaturized').val(filename.filename);
            pub_alert_success('上传成功！');
        }
    });

}


function uppreview (token) {
    var uploader = new plupload.Uploader({ //实例化一个plupload上传对象
        browse_button: 'uppreview',
        url: '/admin/ajax/template/upload',
        filters: {
            mime_types: [
                {title: "图片文件", extensions: "jpg,gif,png,bmp"},
            ],
            max_file_size: '5120kb', //最大只能上传5M的文件
            prevent_duplicates: false, //不允许队列中存在重复文件
            width: 200,
            height: 200,

        },

    });
    uploader.init(); //初始化
    uploader.settings.multipart_params = {_token: token, type : 'preview'}

    //绑定文件添加进队列事件
    uploader.bind('FilesAdded', function (uploader, files) {

        var reader = new FileReader();
        reader.readAsDataURL(files[0].getNative());
        reader.onload = (function (e) {
            var image = new Image();
            image.src = e.target.result;
            image.onload = function () {
                if (this.width == 795 || this.width == this.width) {
                    $.each(uploader.files, function (i, file) {
                        if (uploader.files.length <= 1) {
                            return;
                        }
                        uploader.removeFile(file);
                    });

                    for (var i = 0, len = files.length; i < len; i++) {
                        !function (i) {
                            uploader.start();
                        }(i);
                    }
                } else {
                    pub_alert_error('图片尺寸不符，宽度应795')
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
            var filename = JSON.parse(responseObject.response);
            $('#preview').val(filename.filename);
            pub_alert_success('上传成功！');
        }
    });

}

function upfile(token) {
    var uploader = new plupload.Uploader({ //实例化一个plupload上传对象
        browse_button: 'upfile',
        url: '/admin/ajax/template/upload',
        filters: {
            mime_types: [
                {title: "doc文件", extensions: "doc"},
            ],
            max_file_size: '5120kb', //最大只能上传5M的文件
            prevent_duplicates: false, //不允许队列中存在重复文件
            width: 200,
            height: 200,

        },

    });
    uploader.init(); //初始化
    uploader.settings.multipart_params = {_token: token, type : 'file'}

    //绑定文件添加进队列事件
    uploader.bind('FilesAdded', function (uploader, files) {

        var reader = new FileReader();
        reader.readAsDataURL(files[0].getNative());
        reader.onload = (function (e) {
            uploader.start();
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
            var filename = JSON.parse(responseObject.response);
            $('#file').val(filename.filename);
            pub_alert_success('上传成功！');
        }
    });

}

function edit(id) {
    window.location.href="/admin/template/edit/id/"+id;
}

function del(id) {
    var _token = $('input[name=_token]').val();
    pub_alert_confirm('/admin/ajax/template/del',{id:id,_token:_token},'删除成功!');
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






