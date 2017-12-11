/**
 * Created by Administrator on 2016/7/28.
 */
function pub_alert_success(msg){
    var msg = msg ? msg : '操作成功';
    $.gritter.add({
        title: '提示!',
        text: msg,
        class_name: 'gritter-success'
    });
}

function pub_alert_error(msg){
    var msg = msg ? msg : '操作失败';
    $.gritter.add({
        title: '提示!',
        text: msg,
        class_name: 'gritter-error'
    });
}

function pub_alert_html(url,isjump,addvar){
    addvar = addvar ? '&' : '?';
    isjump ? location.href=url+addvar+UVAR : '';
    $.ajax({
        type:'GET',
        url:url,
        dataType:'json',
        success:function(r){
            if(r.state == 1){
                $('body').prepend(r.data);
                public_alert_bootbox();
            }else{
                pub_alert_error(r.info);
            }
        }
    })
}


function public_alert_bootbox(id){
    if(id == undefined || id ==''){
        id = 'pub_edit_bootbox';
    }
    $("#"+id+" .close").on('click',function(){
        $("#"+id).remove();$("div.modal-backdrop").remove();$("body").removeClass('modal-open');
    });
    $("#"+id+" .uptrue").on('click',function(){
        $("#"+id).remove();$("div.modal-backdrop").remove();$("body").removeClass('modal-open');
    });
    $("#"+id).modal({
        "backdrop": "static",
        "keyboard": true,
        "show": true
    });
}





function ddjsobj(obj){
    var description = "";
    for(var i in obj){
        var property=obj[i];
        description+=i+" = "+property+"\n";
    }
    alert(description);
}

function pub_alert_confirm(url,data,msg,title){
    if(title == undefined){
        title = '确定要执行本次操作吗？';
    }
    if(!url) return false;
    bootbox.confirm({
            message: title,
            buttons: {
                confirm: {
                    label: "确定",
                    className: "btn-primary btn-sm"
                },
                cancel: {
                    label: "取消",
                    className: "btn-sm"
                }
            },
            callback: function(result) {
                if(result){
                    $.ajax({
                        url: url,
                        type: 'POST',
                        dataType: 'json',
                        data: data,
                        beforeSend: function () {
                            loadingstart();
                        },
                    })
                        .done(function() {
                            loadingend();
                            pub_alert_success(msg);
                            window.location.reload();
                        })
                        .fail(function(XMLHttpRequest, textStatus, errorThrown) {
                            if(textStatus == 'error'){
                                loadingend();

                                var obj = JSON.parse(XMLHttpRequest.responseText);
                                var errors = obj.errors;
                                $.each(errors,function (name,vale) {
                                    pub_alert_error(vale);
                                })

                            }else {
                                loadingend();
                                pub_alert_success(msg);
                                window.location.href=window.location.href;
                                window.location.reload();
                            }
                        })
                }
            }
        }
    );
}

function loadingstart(){
    //opts 可从网站在线制作
    var opts = {
        lines: 8, // 花瓣数目
        length: 10, // 花瓣长度
        width: 8, // 花瓣宽度
        radius: 10, // 花瓣距中心半径
        corners: 1, // 花瓣圆滑度 (0-1)
        rotate: 0, // 花瓣旋转角度
        direction: 1, // 花瓣旋转方向 1: 顺时针, -1: 逆时针
        color: '#000000', // 花瓣颜色
        speed: 1, // 花瓣旋转速度
        trail: 60, // 花瓣旋转时的拖影(百分比)
        shadow: false, // 花瓣是否显示阴影
        hwaccel: false, //spinner 是否启用硬件加速及高速旋转
        className: 'spinner', // spinner css 样式名称
        zIndex: 2000000, // spinner的z轴 (默认是2000000000)
        top: 'auto', // spinner 相对父容器Top定位 单位 px
        left: 'auto'// spinner 相对父容器Left定位 单位 px
    };

    spinner = new Spinner(opts);

    $("#firstDiv").text("");
    var target = $("#firstDiv").get(0);
    var text='<div id="grfloading" class="modal-backdrop-grf fade in"></div>';
    $('body').append(text);
    spinner.spin(target);
}

function loadingend() {
    spinner.spin();
    $('#grfloading').remove();
}