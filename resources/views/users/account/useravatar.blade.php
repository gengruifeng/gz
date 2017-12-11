@extends('layouts.layout')

@section('head')
    <title>我的头像-工作网</title>
@endsection

@section('stylesheets')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/personnel.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/avatar.css') }}"/>
@endsection
@section('content')
    <!--个人信息开始-->
    <div id="message">
        <div class="message">
            <div class="message_left">
                <a href="{{ url('account/settings') }}"><span></span><span>基本资料</span></a>
                <a class="onClick" href="javascript:void(0)"><span></span><span>我的头像</span></a>
                <a href="{{ url('account/oauth') }}"><span></span><span>绑定设置</span></a>
                <a href="{{ url('account/safety') }}"><span></span><span>账户安全</span></a>
                <a href="{{ url('account/proficiency') }}"><span></span><span>擅长领域</span></a>
            </div>

            <div class="message_right">
                <!--我的头像开始-->
                <div class="headPortrait">
                    <div>
                        <h3>我的头像</h3>
                    </div>
                    <div>
                        <!--第二步-->
                        <div style="display: none" class="clearfix av-step av-stepsecond">
                            <div class="fl av-main">
                                <div class="imageBox">
                                    <div class="thumbBox"></div>
                                </div>

                                <div class="action">
                                    <!-- <input type="file" id="file" style=" width: 200px">-->
                                    <div class="btn-numb clearfix">
                                        <input type="button" id="btnZoomIn" class="Btnsty_peyton" value=""  >
                                        <input type="button" id="btnZoomOut" class="Btnsty_peyton" value="" >
                                    </div>
                                    <div class="btn-mod clearfix">
                                        <input type="button" id="btnCrop"  class="Btnsty_peyton fl" value="保存头像">
                                        <div class="new-contentarea tc fl">
                                            <a href="javascript:void(0)" class="upload-img">
                                                <label for="upload-file">重新选择</label>
                                            </a>
                                            <input type="file" class="" name="upload-file" id="upload-file" />
                                        </div>
                                    </div>


                                </div>
                            </div>
                            <div class="fr av-side">
                                <div class="title">头像预览</div>
                                <div id=""  style="filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(true,sizingMethod=scale); ">
                                    <img id="72l22" src="/avatars/120/head.png"/>
                                </div>
                            </div>

                        </div>
                        <!--第一步-->
                        <div style="display: block" class="av-stepfirst">
                            <div id="destination" class="select-avatar" style="filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(true,sizingMethod=scale);">
                                <input type="file" class="" name="upload-file" id="upload-file-one" />
                                <img src="../images/header/h-hover.png" alt="" class="av-hover hide">
                                <img id="userimg" src="{{ !empty($avatar->avatar)? url('/avatars/120/'.$avatar->avatar): '/avatars/120/head.png' }}"/>
                            </div>
                            <p>只支持jpg、png、jpeg、bmp,图片大小5M以内</p>
                            {{--<div class="select-avatar">--}}
                                {{--<a href="javascript:void(0)">选择照片--}}
                                    {{--<input type="file" class="" name="upload-file" id="upload-file-one" />--}}
                                {{--</a>--}}
                            {{--</div>--}}
                        </div>
                    </div>
                </div>
                <!--我的头像结束-->
            </div>
        </div>
    </div>
    <!--个人信息结束-->
@endsection
@section('javascripts')
<script src="{{ asset('js/plupload.full.min.js') }}"></script>
<script src="{{ asset('js/upload.js') }}"></script>
<script src="{{ asset('js/cropbox.js') }}"></script>
<script src="{{ asset('admin/js/common.js') }}"></script>
<script>
	var _token = "{{ csrf_token() }}";
	window.onload = function(){
	    $('#destination').hover(function () {
            $('#destination .av-hover').removeClass('hide');
        },function () {
            $('#destination .av-hover').addClass('hide');

        });
		$(function () {

            var options =
            {
                thumbBox: '.thumbBox',
                spinner: '.spinner',
                imgSrc: 'avatars/120/head.png'
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
//                this.files = [];
                $(this).val('');
            });
            $('#btnCrop').on('click', function(){
                var img = cropper.getDataURL();
                avatarUploadzhong(_token, img)

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

            $('#upload-file-one').on('change', function(){
                var aa = document.getElementById("upload-file-one").value.toLowerCase().split('.'); //以“.”分隔上传文件字符串

                if (aa[aa.length - 1] == 'jpg' || aa[aa.length - 1] == 'bmp'

                        || aa[aa.length - 1] == 'png' || aa[aa.length - 1] == 'jpeg') //判断图片格式
                {
                    var imagSize = document.getElementById("upload-file-one").files[0].size;
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
//                this.files = [];


                $('.av-stepfirst').css('display','none');
                $('.av-stepsecond').css('display','block');
                $(this).val('');
            })
		});
	}
</script>
@endsection


