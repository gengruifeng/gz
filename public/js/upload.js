/**
 * Created by Administrator on 2016/7/22.
 */

function avatarUploadzhong(token,img) {
    $.ajax({
        url: '/ajax/account/avatarupload',
        type: 'POST',
        dataType: 'json',
        data: {_token:token,img:img},
    })
        .done(function() {
            $('.av-stepfirst').css('display','block');
            $('.av-stepsecond').css('display','none');
            $('#userimg').attr('src',img);
            $('#logindlyc').attr('src',$('#userimg').attr('src'));
            window.location.reload();
            dialogcom_yes("保存成功!");
        })
        .fail(function(XMLHttpRequest, textStatus, errorThrown) {
            if(textStatus == 'error'){
                if(XMLHttpRequest.status == 401){
                    dialoglogin();
                    return false;
                }
                if(XMLHttpRequest.status == 403){
                    dialogcom_wrong('页面停留时间过长请刷新页面后再操作!');
                    return false;
                }
                var obj = JSON.parse(XMLHttpRequest.responseText);
                var errors = obj.errors;
                $.each(errors,function (name,vale) {
                    dialogcom_wrong(vale);
                })
                return false;
            }else{
                $('.av-stepfirst').css('display','block');
                $('.av-stepsecond').css('display','none');
                $('#userimg').attr('src',img);
                $('#logindlyc').attr('src',$('#userimg').attr('src'));
                window.location.reload();
                dialogcom_yes("保存成功!");
                return true;
            }
        })
}

function rsumeUploadzhong(token,img) {
    $.ajax({
        url: '/ajax/resume/resumeupload',
        type: 'POST',
        dataType: 'json',
        data: {_token:token,img:img},
    })
        .done(function() {
            dialogcom_yes("保存成功!");
            $('.av-stepfirst').css('display','block');
            $('.av-stepsecond').css('display','none');
            $('#userimg').attr('src',img);
            window.location.reload();
            return true;
        })
        .fail(function(XMLHttpRequest, textStatus, errorThrown) {
            if(textStatus == 'error'){
                if(XMLHttpRequest.status == 401){
                    dialoglogin();
                    return false;
                }
                if(XMLHttpRequest.status == 403){
                    dialogcom_wrong('页面停留时间过长请刷新页面后再操作!');
                    return false;
                }
                var obj = JSON.parse(XMLHttpRequest.responseText);
                var errors = obj.errors;
                $.each(errors,function (name,vale) {
                    dialogcom_wrong(vale);
                    return false;
                })
            }else{
                dialogcom_yes("保存成功!");
                $('.imgup').dialog('close');
                $('.av-stepfirst').css('display','block');
                $('.av-stepsecond').css('display','none');
                $('#userimg').attr('src',img);
                window.location.reload();
                return true;
            }
        })
}

function categoryUpload (token) {
    var imgr = '';
    uploader = new plupload.Uploader({ //实例化一个plupload上传对象
        browse_button: 'categoryUpload',
        url: '/admin/ajax/tags/addcategoriespic',
        filters: {
            mime_types: [
                {title: "图片文件", extensions: "png"}
            ],
            max_file_size: '5120kb', //最大只能上传5M的文件
            prevent_duplicates: false //不允许队列中存在重复文件
        },
        resize: {
            width: 58,
            height: 58,
            crop: false,
            quality: 60,
            preserve_headers: false
        }
    });
    uploader.init(); //初始化
    uploader.settings.multipart_params = {_token: token};

    //绑定文件添加进队列事件
    uploader.bind('FilesAdded', function (uploader, files) {
        var reader = new FileReader();
        reader.readAsDataURL(files[0].getNative());
        reader.onload = (function (e) {
            var image = new Image();
            image.src = e.target.result;
            image.onload = function () {
                if (this.width == 58 && this.height == 58) {
                    $.each(uploader.files, function (i, file) {
                        if (uploader.files.length <= 1) {
                            return;
                        }

                        uploader.removeFile(file);
                    });
                    for (var i = 0, len = files.length; i < len; i++) {
                        !function (i) {
                            previewImage(files[i], function (imgsrc) {
                                imgr = imgsrc;
                                uploader.start();
                            })
                        }(i);
                    }
                } else {
                    pub_alert_error('图片尺寸不符，应：58*58的png图片');
                    uploader.stop();
                    uploader.removeFile(files[0])
                }

            };

        });
    });

    //绑定文件添加进队列事件
    uploader.bind('Error', function (uploader, errObject) {
        if (errObject.status == 500) {
            info = JSON.parse(errObject.response);
            pub_alert_error(info.description);
        } else {
            pub_alert_error(errObject.message);
        }

    });

    uploader.bind('FileUploaded', function (uploader, file, responseObject) {
        if (responseObject.status == 200) {
            if (imgr != "") {
                var imginfo = eval('('+responseObject.response+')');
                $("#categoryurl").val(imginfo.filename);
                $("#categoryImg").html('<img id="userimg" src="' + imgr + '" />');
            }
        }
    });

    //plupload中为我们提供了mOxie对象
    //有关mOxie的介绍和说明请看：https://github.com/moxiecode/moxie/wiki/API
    //如果你不想了解那么多的话，那就照抄本示例的代码来得到预览的图片吧
    function previewImage(file, callback) {//file为plupload事件监听函数参数中的file对象,callback为预览图片准备完成的回调函数
        if (!file || !/image\//.test(file.type)) return; //确保文件是图片
        if (file.type == 'image/gif') {//gif使用FileReader进行预览,因为mOxie.Image只支持jpg和png
            var fr = new mOxie.FileReader();
            fr.onload = function () {
                callback(fr.result);
                fr.destroy();
                fr = null;
            };
            fr.readAsDataURL(file.getSource());
        } else {
            var preloader = new mOxie.Image();
            preloader.onload = function () {
                preloader.downsize(120, 120);//先压缩一下要预览的图片,宽300，高300
                var imgsrc = preloader.type == 'image/jpeg' ? preloader.getAsDataURL('image/jpeg', 80) : preloader.getAsDataURL(); //得到图片src,实质为一个base64编码的数据
                callback && callback(imgsrc); //callback传入的参数为预览图片的url
                preloader.destroy();
                preloader = null;
            };
            preloader.load(file.getSource());
        }
    }

}

function categoryUploadHide (token) {
    var imgr = '';
    uploader = new plupload.Uploader({ //实例化一个plupload上传对象
        browse_button: 'categoryUploadHide',
        url: '/admin/ajax/tags/addcategoriespic',
        filters: {
            mime_types: [
                {title: "图片文件", extensions: "png"}
            ],
            max_file_size: '5120kb', //最大只能上传5M的文件
            prevent_duplicates: false //不允许队列中存在重复文件
        },
        resize: {
            width: 58,
            height: 58,
            crop: false,
            quality: 60,
            preserve_headers: false
        }
    });
    uploader.init(); //初始化
    uploader.settings.multipart_params = {_token: token};

    //绑定文件添加进队列事件
    uploader.bind('FilesAdded', function (uploader, files) {
        var reader = new FileReader();
        reader.readAsDataURL(files[0].getNative());
        reader.onload = (function (e) {
            var image = new Image();
            image.src = e.target.result;
            image.onload = function () {
                if (this.width == 58 && this.height == 58) {
                    $.each(uploader.files, function (i, file) {
                        if (uploader.files.length <= 1) {
                            return;
                        }

                        uploader.removeFile(file);
                    });
                    for (var i = 0, len = files.length; i < len; i++) {
                        !function (i) {
                            previewImage(files[i], function (imgsrc) {
                                imgr = imgsrc;
                                uploader.start();
                            })
                        }(i);
                    }
                } else {
                    pub_alert_error('图片尺寸不符，应：58*58的png图片');
                    uploader.stop();
                    uploader.removeFile(files[0])
                }

            };

        });
    });

    //绑定文件添加进队列事件
    uploader.bind('Error', function (uploader, errObject) {
        if (errObject.status == 500) {
            info = JSON.parse(errObject.response);
            pub_alert_error(info.description);
        } else {
            pub_alert_error(errObject.message);
        }

    });

    uploader.bind('FileUploaded', function (uploader, file, responseObject) {
        if (responseObject.status == 200) {
            if (imgr != "") {
                var imginfo = eval('('+responseObject.response+')');
                $("#categoryurlhide").val(imginfo.filename);
                $("#categoryImgHide").html('<img id="userimghide" src="' + imgr + '" />');
            }
        }
    });

    //plupload中为我们提供了mOxie对象
    //有关mOxie的介绍和说明请看：https://github.com/moxiecode/moxie/wiki/API
    //如果你不想了解那么多的话，那就照抄本示例的代码来得到预览的图片吧
    function previewImage(file, callback) {//file为plupload事件监听函数参数中的file对象,callback为预览图片准备完成的回调函数
        if (!file || !/image\//.test(file.type)) return; //确保文件是图片
        if (file.type == 'image/gif') {//gif使用FileReader进行预览,因为mOxie.Image只支持jpg和png
            var fr = new mOxie.FileReader();
            fr.onload = function () {
                callback(fr.result);
                fr.destroy();
                fr = null;
            };
            fr.readAsDataURL(file.getSource());
        } else {
            var preloader = new mOxie.Image();
            preloader.onload = function () {
                preloader.downsize(120, 120);//先压缩一下要预览的图片,宽300，高300
                var imgsrc = preloader.type == 'image/jpeg' ? preloader.getAsDataURL('image/jpeg', 80) : preloader.getAsDataURL(); //得到图片src,实质为一个base64编码的数据
                callback && callback(imgsrc); //callback传入的参数为预览图片的url
                preloader.destroy();
                preloader = null;
            };
            preloader.load(file.getSource());
        }
    }

}

function dialog_upload() {
    $( "#dialog-upload" ).dialog({
        width:490,
        height:503,
        modal: true,
    });
    validate_login()

    $('.btnclose').click(function () {
        $('#dialog-login').dialog('close');
    })
}

