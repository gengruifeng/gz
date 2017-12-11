<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>个人经历-工作网</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/common.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/vlogin.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/resume-step.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/css/datepicker.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/avatar.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}"/>
</head>
<body>
<!--内容开始-->
<section class="resume-step resume-three clearfix">
    <div class="inner">
        <div class="secwrap">
            <textarea style="display:none" id="template">
                    <!-- 容器 -->

                        <div class="sec{0}">
                            <dl class="clearfix school">
                                <dt class="fl">公司名称：</dt>
                                <dd class="fl">
                                    <input type="text" name="company_name-{0}" class="company_name">
                                </dd>
                            </dl>
                            <dl class="clearfix sexspan">
                                <dt class="fl">职位名称：</dt>
                                <dd class="fl">
                                    <input  type="text" name="job_name-{0}" class="job_name" >
                                </dd>
                            </dl>
                            <div class="timecheck clearfix">
                                <dl class="fl duringSchool">
                                    <dt class="fl">起始时间：</dt>
                                    <dd class="fl">
                                        <input type="text"  name="time_start-{0}" class="input-startday time_start">

                                    </dd>
                                </dl>
                                <dl class="fl duringSchool">
                                    <dt class="fl">&nbsp;&nbsp;结束时间：</dt>
                                    <dd class="fr">
                                        <input type="text" id="time_end" name="time_end-{0}" class="input-startday time_end">
                                    </dd>
                                </dl>
                            </div>
                             <div class="addmore clearfix">
                                    <a href="javascript:;" class="fr btn-close" onclick="removeinfo('{0}')"></a>
                             </div>
                        </div>

            </textarea>
        </div>
        <form action="" id="resumeThreeForm" autocomplete="off">
            {{ csrf_field() }}
            <div class="head" id="destination">
                <img id="userimg" onclick="dialogupload()" src="{{ !empty($persons->resumeavatar)? url('/resume/'.$persons->resumeavatar): '/avatars/120/head.png' }}"/>
            </div>
            <h3><span>个人经历</span></h3>
            <div class="stepmsg">
                @if(!empty($experiences))
                    @foreach($experiences as $experiencekey => $experience)
                        <div class="sec{{$experiencekey}}">
                            <dl class="clearfix school">
                                <dt class="fl">公司名称：</dt>
                                <dd class="fl">
                                    <input type="text" value="{{$experience['company']}}" name="company_name-{{$experiencekey}}" class="company_name">
                                </dd>
                            </dl>
                            <dl class="clearfix sexspan">
                                <dt class="fl">职位名称：</dt>
                                <dd class="fl">
                                    <input  type="text" value="{{$experience['position']}}" name="job_name-{{$experiencekey}}" class="job_name" >
                                </dd>
                            </dl>
                            <div class="timecheck clearfix">
                                <dl class="fl duringSchool">
                                    <dt class="fl">起始时间：</dt>
                                    <dd class="fl">
                                        <input type="text"  value="{{$experience['from']}}"  name="time_start-{{$experiencekey}}" class="input-startday time_start">

                                    </dd>
                                </dl>
                                <dl class="fl duringSchool">
                                    <dt class="fl">&nbsp;&nbsp;结束时间：</dt>
                                    <dd class="fr">
                                        <input type="text" value="{{$experience['to']}}"  id="time_end" name="time_end-{{$experiencekey}}" class="input-startday time_end">
                                    </dd>
                                </dl>
                            </div>
                            <div class="addmore clearfix">
                                <a href="javascript:;" class="fr btn-close" onclick="removeinfo({{$experiencekey}})"></a>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="sec0">
                        <dl class="clearfix school">
                            <dt class="fl">公司名称：</dt>
                            <dd class="fl">
                                <input type="text" name="company_name-0" class="company_name">
                            </dd>
                        </dl>
                        <dl class="clearfix sexspan">
                            <dt class="fl">职位名称：</dt>
                            <dd class="fl">
                                <input  type="text" name="job_name-0" class="job_name" >
                            </dd>
                        </dl>
                        <div class="timecheck clearfix">
                            <dl class="fl duringSchool">
                                <dt class="fl">起始时间：</dt>
                                <dd class="fl">
                                    <input type="text"  name="time_start-0" class="input-startday time_start">

                                </dd>
                            </dl>
                            <dl class="fl duringSchool">
                                <dt class="fl">&nbsp;&nbsp;结束时间：</dt>
                                <dd class="fr">
                                    <input type="text" id="time_end" name="time_end-0" class="input-startday time_end">
                                </dd>
                            </dl>
                        </div>
                    </div>
                @endif
            </div>
                <!-- 添加更多 -->
            <input type="hidden" value="{{empty($i)?1:$i}}" id="i">
                <div class="addmore clearfix">
                    <a href="javascript:;" class="fl btn-more" id="add">添加更多个人经历</a>
                </div>
                <div class="btns clearfix">
                    <input class="btn btn-yellow btn-yellow-inner fr" type="submit" value="创建完成！">
                    <a class="fl btn btn-grey btn-grey-inner" onclick="threeback('/myeducations')" href="javascript:;" >上一步</a>
                </div>

        </form>

    </div>

</section>
<!--内容结束-->
<!-- 提示信息 开始-->
<div class="dialogcom dialogcom_yes hide">
    <form action="">
        <span>提问成功</span>
        <input class="_token" type="hidden" name="" id="" value="">
    </form>
</div>
<div class="dialogcom dialogcom_warn hide ">
    <form action="">
        <span>操作过于频繁，请明天再来</span>
        <input class="_token" type="hidden" name="" id="" value="">
    </form>
</div>
<div class="dialogcom dialogcom_wrong hide">
    <form action="">
        <span>操作过于频繁，请明天再来</span>
        <input class="_token" type="hidden" name="" id="" value="">
    </form>
</div>
<!-- 提示信息 结束-->
<!--上传图 开始-->
<div class="imgup hide">
    <div class=" av-step av-resume">
        <div class="hd-title clearfix">照片设置
            <a href="javascript:;" class="btn-close fr"></a>
        </div>
        <div class="clearfix av-resumebtm">
            <div class="fl av-main">
                <div class="imageBox">
                    <div class="thumbBox"></div>
                </div>
                <div class="action">
                    <div class="btn-numb clearfix">
                        <input type="button" id="btnZoomIn" class="Btnsty_peyton" value=""  >
                        <input type="button" id="btnZoomOut" class="Btnsty_peyton" value="" >
                    </div>
                    <div class="btn-mod clearfix">
                        <div class="new-contentarea tc fl">
                            <a href="javascript:void(0)" class="upload-img">
                                <label for="upload-file">上传真实头像</label>
                            </a>
                            <input type="file" class="" name="upload-file" id="upload-file" />
                        </div>
                        <input type="button" id="btnCrop"  class="Btnsty_peyton fl" value="保存头像">
                    </div>
                    <p class="tips">只支持jpg、png、jpeg、bmp，图片大小5M以内</p>
                </div>
            </div>
            <div class="fl av-side">
                <div class="title">头像预览</div>
                <div id="">
                    <img id="72l22" src="../images/riji3.png"/>
                </div>
            </div>
        </div>

    </div>
</div>
<!--上传图 结束-->
<script src="{{ asset('js/jquery-2.1.0.js')}}"></script>
<script src="{{ asset('js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('js/jquery.form.js')}}"></script>
<script src="{{ asset('js/jquery.validate.js')}}"></script>
<script src="{{ asset('js/plupload.full.min.js') }}"></script>
<script src="{{ asset('js/upload.js') }}"></script>
<script src="{{ asset('js/vlogin.js') }}"></script>
<script src="{{ asset('admin/assets/js/ace/ace.js')}}"></script>
<script src="{{ asset('admin/assets/js/date-time/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('admin/assets/js/date-time/locales/bootstrap-datepicker.zh-CN.js') }}"></script>
<script src="{{ asset('js/resumeFirst.js')}}"></script>
<script src="{{ asset('js/resumethree.js')}}"></script>
<script src="{{ asset('js/tabs.js')}}"></script>
<script src="{{ asset('js/common.js')}}"></script>
<script src="{{ asset('js/cropbox.js') }}"></script>

<script>
    var _token = "{{ csrf_token() }}";
    window.onload = function(){
        $(function () {

            var options =
            {
                thumbBox: '.thumbBox',
                spinner: '.spinner',
                imgSrc: '../images/resume-default.png'
            };
            var cropper = $('.imageBox').cropbox(options);
            $('#upload-file').on('change', function(){

                var aa = document.getElementById("upload-file").value.toLowerCase().split('.'); //以“.”分隔上传文件字符串

                if (aa[aa.length - 1] == 'jpg' || aa[aa.length - 1] == 'bmp'

                        || aa[aa.length - 1] == 'png' || aa[aa.length - 1] == 'jpeg') //判断图片格式
                {
                    var imagSize = document.getElementById("upload-file").files[0].size;
                    if (imagSize > 1024 * 1024 * 5)
                    {
                        dialogcom_wrong("图片大小在5M以内，为：" + imagSize / (1024 * 1024) + "M");
                        $(this).val('');
                        return false;
                    }
                } else {
                    dialogcom_wrong('请选择格式为*.jpg、*.bmp、*.png、*.jpeg 的图片'); //jpg和jpeg格式是一样的只是系统Windows认jpg，Mac OS认jpeg，
                    //二者区别自行百度
                    $(this).val('');
                    return false;
                }

                var reader = new FileReader();
                reader.onload = function(e) {
                    options.imgSrc = e.target.result;
                    cropper = $('.imageBox').cropbox(options);
                }
                reader.readAsDataURL(this.files[0]);
                $(this).val('');
//                this.files = [];
            });
            $('#btnCrop').on('click', function(){
                var img = cropper.getDataURL();
                rsumeUploadzhong(_token, img);
                $('.av-stepfirst').css('display','block');
                $('.av-stepsecond').css('display','none');
                $('#userimg').attr('src',img);

            })
            $('#btnZoomIn').on('click', function(){
                cropper.zoomIn();
                var img = cropper.getDataURL();
                $('#72l22').attr('src',img);
            })
            $('#btnZoomOut').on('click', function(){
                cropper.zoomOut();
                var img = cropper.getDataURL();
                $('#72l22').attr('src',img);
            })

        });
    }
    $(function() {
        $(".tabs").tabs(".pane", {
            onClick: function () {

            }
        });
        $('.time_start').each(function(index,element){
            $(this).datepicker({    
                autoclose : true,  
                format: 'yyyy-mm-dd',  
                language: 'zh-CN'
            }).on('changeDate',function(e){  
                
                var startTime = e.date;  
                $('input[name="time_end-'+index+'"]').datepicker('setStartDate',startTime);  
            });  
        })        
        $('.time_end').each(function(indexed,element){
            $(this).datepicker({    
                autoclose : true,    
                format: 'yyyy-mm-dd',  
                language: 'zh-CN'
            }).on('changeDate',function(e){  
                // alert($(this).length);
                var endTime = e.date;  
                $('input[name="time_start-'+indexed+'"]').datepicker('setEndDate',endTime);  
            });  
        })

        // $('.input-startday').each(function(index, element) {
        //     $(this).datepicker({
        //         autoclose: true,
        //         format: 'yyyy-mm-dd',
        //         language: 'zh-CN'
        //     }).on('click',function () {
        //         var top = $(this).offset().top-340+$("body").scrollTop();
        //         var left = $(this).offset().left;
        //         $('.datepicker').css({
        //             'top':top,
        //             'left':left,
        //         });
        //     });

        // })
    });
</script>

</body>
</html>


