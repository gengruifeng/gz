<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>基本信息-工作网</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/common.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/vlogin.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/resume-step.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/css/datepicker.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/avatar.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}"/>

</head>
<body>

<!--内容开始-->
<section class="resume-step resume-one clearfix">
    <div class="inner">
        <form action="/resume/my" id="resumeOneForm">
            <div class="head" id="destination">
                <img onclick="dialogupload()" id="userimg" src="{{ !empty($persons->resumeavatar)? url('/resume/'.$persons->resumeavatar): '/resume/head.png' }}"/>
            </div>
            {{ csrf_field() }}
            <h3><span>基本信息</span></h3>
            @if(empty($persons))
            <div class="stepmsg">
                <div class="sec">
                    <dl class="clearfix">
                        <dt class="fl">姓名：</dt>
                        <dd class="fl">
                            <input type="text" id="display_name" placeholder="请输入您的姓名"  name="display_name">
                        </dd>
                    </dl>
                    <dl class="clearfix sexspan">
                        <dt class="fl">性别：</dt>
                        <dd class="fl">

                            <span><input type="radio" name="sex" id="sexb" value="男"><label for="sexb">男</label></span>
                            <span><input type="radio" name="sex" id="sexg" value="女"><label for="sexg">女</label></span>
                            <span><input type="radio" name="sex" id="sexunkonw" value="保密"><label for="sexunkonw">保密</label></span>

                        </dd>
                    </dl>
                    <dl class="clearfix birthday">
                        <dt class="fl">出生日期：</dt>
                        <dd class="fl">
                            <input class="input-startday"  type="text"  placeholder="出生日期" value="1990-01-01" autocomplete="off"  name ="birthday">
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt class="fl">邮箱：</dt>
                        <dd class="fl">
                            <input type="text" placeholder="请输入您的邮箱"  name="email">
                        </dd>
                    </dl>
                    <dl class="clearfix">
                        <dt class="fl">手机号：</dt>
                        <dd class="fl">
                            <input type="text" placeholder="请输入您的手机号"  name="mobUsername">
                        </dd>
                    </dl>
                    <dl class="clearfix city">
                        <dt class="fl">所在城市：</dt>
                        <dd class="fl">
                            <select id="province" onchange="changeCity(this)" name="province">
                                <option value="" hidden disabled style="visibility: hidden;">请选择省份</option>
                                @foreach ($province as $value)
                                    <option  data-text="{{ $value->id }}"  value="{{ $value->shortname }}">{{ $value->shortname }}</option>
                                @endforeach
                            </select>
                            <select id="city" name="city">
                                <option class="city_se" value="">请选择城市</option>
                            </select>
                        </dd>
                    </dl>
                </div>
            </div>
            @else
                <div class="stepmsg">
                    <div class="sec">
                        <dl class="clearfix">
                            <dt class="fl">姓名：</dt>
                            <dd class="fl">
                                <input type="text" id="display_name" placeholder="请输入您的姓名" name="display_name" value="{{$persons->name}}">
                            </dd>
                        </dl>
                        <dl class="clearfix sexspan">
                            <dt class="fl">性别：</dt>
                            <dd class="fl">
                                @if($persons->gender=="男")
                                    <span><input type="radio" name="sex" id="sexb" value="男" checked="true"><label for="sexb">男</label></span>
                                    <span><input type="radio" name="sex" id="sexg" value="女"><label for="sexg">女</label></span>
                                    <span><input type="radio" name="sex" id="sexunkonw" value="保密"><label for="sexunkonw">保密</label></span>
                                @elseif($persons->gender=="女")
                                    <span><input type="radio" name="sex" id="sexb" value="男" ><label for="sexb">男</label></span>
                                    <span><input type="radio" name="sex" id="sexg" value="女" checked="true"><label for="sexg">女</label></span>
                                    <span><input type="radio" name="sex" id="sexunkonw" value="保密"><label for="sexunkonw">保密</label></span>
                                @else
                                    <span><input type="radio" name="sex" id="sexb" value="男" ><label for="sexb">男</label></span>
                                    <span><input type="radio" name="sex" id="sexg" value="女" ><label for="sexg">女</label></span>
                                    <span><input type="radio" name="sex" id="sexunkonw" value="保密" checked="true"><label for="sexunkonw">保密</label></span>
                                @endif
                            </dd>
                        </dl>
                        <dl class="clearfix birthday">
                            <dt class="fl">出生日期：</dt>
                            <dd class="fl">
                                <input class="input-startday"  type="text" placeholder="出生日期" name ="birthday" value="{{$persons->birthday != '1000-01-01' ? $persons->birthday:'1990-01-01'}}">
                            </dd>
                        </dl>
                        <dl class="clearfix">
                            <dt class="fl">邮箱：</dt>
                            <dd class="fl">
                                <input type="text" name="email" placeholder="请输入您的邮箱" value="{{$persons->email}}">
                            </dd>
                        </dl>
                        <dl class="clearfix">
                            <dt class="fl">手机号：</dt>
                            <dd class="fl">
                                <input type="text" name="mobUsername" placeholder="请输入您的手机号" value="{{$persons->mobile}}">
                            </dd>
                        </dl>
                        <dl class="clearfix city">
                            <dt class="fl">所在城市：</dt>
                            <dd class="fl">
                                <select id="province" onchange="changeCity()" name="province">
                                    <option value="">请选择省份</option>
                                    @foreach ($province as $value)
                                        <option  data-text="{{ $value->id }}" {{ $persons->province == $value->shortname ? 'selected="selected"':'' }} value="{{ $value->shortname }}">{{ $value->shortname }}</option>
                                    @endforeach
                                </select>
                                <select id="city" name="city">
                                    <option id="cityname" class="city_se" value="{{$persons->city}}">{{$persons->city}}</option>

                                </select>
                            </dd>
                        </dl>
                    </div>
                </div>
            @endif
            <div class="btns">
                <input type="hidden" value="1" name="status" id="status">
                <input class="btn btn-yellow btn-wfull" id="upload-btn" type="submit" value="下一步：继续完善职业信息">
                <a class="btn btn-grey btn-wfull" href="javascript:void(0)" onclick="resumeOneForm()">保存以上信息，但暂不继续完善信息</a>
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
<!-- 提示信息 结束-->
<script src="{{ asset('js/jquery-2.1.0.js')}}"></script>
<script src="{{ asset('js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('js/jquery.form.js')}}"></script>
<script src="{{ asset('js/jquery.validate.js')}}"></script>
<script src="{{ asset('js/plupload.full.min.js') }}"></script>
<script src="{{ asset('js/upload.js') }}"></script>
<script src="{{ asset('js/vlogin.js') }}"></script>
<script src="{{ asset('admin/assets/js/date-time/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('admin/assets/js/date-time/locales/bootstrap-datepicker.zh-CN.js') }}"></script>
<script src="{{ asset('js/resumeFirst.js')}}"></script>
<script src="{{ asset('js/city.js')}}"></script>
<script src="{{ asset('js/common.js')}}"></script>
<script src="{{ asset('js/cropbox.js') }}"></script>

</body>
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
</script>
</html>


