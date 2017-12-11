@extends('layouts.layout')
@section('head')
    <title>简历个人信息-工作网</title>
@endsection
@section('content')

    <div class="resume resumeMine resume-modify clearfix">
        <!-- 简历主要 开始-->
        <div class="fl modify-maintp">
            <!-- 选择编辑项目 开始 -->
            <ul class="tabs clearfix">
                <li class="fl "><a href="{{url('/resume/persons')}}" class="current finish">个人信息</a></li>
                @if($resumestatus['advices']==2)
                    <li class="fl"><a href="{{url('/resume/advices')}}" class="finish">求职意向</a></li>
                @else
                    <li class="fl"><a href="{{url('/resume/advices')}}" >求职意向</a></li>
                @endif
                @if($resumestatus['educations']==2)
                    <li class="fl"><a href="{{url('/resume/educations')}}" class="finish">教育背景</a></li>
                @else
                    <li class="fl"><a href="{{url('/resume/educations')}}" class="">教育背景</a></li>
                @endif
                @if($resumestatus['experiences']==2)
                    <li class="fl last"><a href="{{url('/resume/experiences')}}" class="finish">个人经历</a></li>
                @else
                    <li class="fl last"><a href="{{url('/resume/experiences')}}" class="">个人经历</a></li>
                @endif
                @if($resumestatus['diplomas']==2)
                    <li class="fl"><a href="{{url('/resume/diplomas')}}" class="finish">技能证书</a></li>
                @else
                    <li class="fl"><a href="{{url('/resume/diplomas')}}" class="">技能证书</a></li>
                @endif
                @if($resumestatus['honors']==2)
                    <li class="fl"><a href="{{url('/resume/honors')}}" class="finish">奖项荣誉</a></li>
                @else
                    <li class="fl"><a href="{{url('/resume/honors')}}" class="">奖项荣誉</a></li>
                @endif
                @if($resumestatus['projects']==2)
                    <li class="fl"><a href="{{url('/resume/projects')}}" class="finish">个人作品</a></li>
                @else
                    <li class="fl"><a href="{{url('/resume/projects')}}" class="">个人作品</a></li>
                @endif
                @if($resumestatus['interests']==2)
                    <li class="fl last"><a href="{{url('/resume/interests')}}" class="finish">兴趣爱好</a></li>
                @else
                    <li class="fl last"><a href="{{url('/resume/interests')}}" class="">兴趣爱好</a></li>
                @endif

            </ul>
            <!-- 选择编辑项目 结束 -->

            <!-- 编辑项目 开始 -->
            <div class="panes modify-mainlist">
                <!--个人信息 开始-->
                <div class="clearfix modify-personnal">
                    <div class="tp"><span>个人信息</span></div>
                    <!-- 完成与编辑 开始 -->
                    <div class="cnt">
                        <!-- 完成 -->
                        <div class="cnt-inner cnt-finish">
                            <div class="sec-person clearfix">
                                <div class="person-img fl">
                                    <img src="{{ empty($persons->resumeavatar)?url('/images/head.png') :url('/resume/'.$persons->resumeavatar) }}" alt="">
                                </div>
                                <div class="fl person-msg">
                                    <a href="javascript:;" class="btn-modify">编辑</a>
                                    <ul class="clearfix">
                                        <li class="fl">姓名：{{$persons->name}}</li>
                                        @if($persons->gender == "男")
                                            <li class="fl">性别：男</li>
                                        @elseif($persons->gender == "女")
                                            <li class="fl">性别：女</li>
                                        @else
                                            <li class="fl">性别：保密</li>
                                        @endif
                                        <li class="fl">出生日期：{{str_replace('-','.',$persons->birthday)}}</li>
                                        <li class="fl">手机：{{$persons->mobile}}</li>
                                        <li class="fl">邮箱：{{$persons->email}}</li>
                                        <li class="fl">所在城市：{{$persons->city}}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- 编辑 -->
                        <div class="cnt-inner cnt-mod clearfix hide">
                            <form action="" id="form-personnal">
                                {{ csrf_field() }}
                                <input type="hidden" name="status" value="2">
                                <div class="sec-person clearfix">
                                    <div class="person-img fl" id="destination">
                                        <img onclick="dialogupload()" class="hover hide" src="../images/headhovers.png" alt="">
                                        <img id="userimg" src="{{ !empty($persons->resumeavatar)? url('/resume/'.$persons->resumeavatar): '/avatars/120/head.png' }}"/>
                                    </div>
                                    <div class="fl">
                                        <dl class="clearfix">
                                            <dt class="fl">*姓名：</dt>
                                            <dd class="fr"><input type="text" placeholder="" name="display_name" value="{{$persons->name}}" placeholder="请输入您的姓名"></dd>
                                        </dl>
                                        <dl class="clearfix">
                                            <dt class="fl">*性别：</dt>
                                            <dd class="fl">
                                                @if($persons->gender == "男")
                                                    <span class="fl"><input type="radio" name="sex" id="sexb" value="男" checked="true"> <label for="sexb">男</label></span>
                                                    <span class="fl"><input type="radio" name="sex" id="sexg" value="女"><label for="sexg">女</label></span>
                                                    <span class="fl"><input type="radio" name="sex" id="sexc" value="保密"><label for="sexc">保密</label></span>
                                                @elseif($persons->gender == "女")
                                                    <span class="fl"><input type="radio" name="sex" id="sexb" value="男" > <label for="sexb">男</label></span>
                                                    <span class="fl"><input type="radio" name="sex" id="sexg" value="女" checked="true"><label for="sexg">女</label></span>
                                                    <span class="fl"><input type="radio" name="sex" id="sexc" value="保密" ><label for="sexc">保密</label></span>
                                                @else
                                                    <span class="fl"><input type="radio" name="sex" id="sexb" value="男"> <label for="sexb">男</label></span>
                                                    <span class="fl"><input type="radio" name="sex" id="sexg" value="女"><label for="sexg">女</label></span>
                                                    <span class="fl"><input type="radio" name="sex" id="sexc" value="3" checked="true" ><label for="sexc">保密</label></span>
                                                @endif

                                            </dd>
                                        </dl>
                                        <dl class="clearfix">
                                            <dt class="fl">*出生日期：</dt>
                                            <dd class="fr"><input type="text"  class="input-startday" autocomplete="off" name="birthday" value="{{$persons->birthday}}" placeholder="出生日期"></dd>
                                        </dl>
                                        <dl class="clearfix">
                                            <dt class="fl">*手机号：</dt>
                                            <dd class="fr"><input type="text"  name="mobUsername" value="{{$persons->mobile}}" placeholder="请输入您的手机号"></dd>
                                        </dl>
                                        <dl class="clearfix">
                                            <dt class="fl">*邮箱：</dt>
                                            <dd class="fr"><input type="text"  name="email" value="{{$persons->email}}" placeholder="请输入您的邮箱"></dd>
                                        </dl>
                                        <dl class="clearfix">
                                            <dt class="fl">*所在省份：</dt>
                                            <dd class="fr">
                                                <select id="province" onchange="changeCity()" name="province">
                                                    <option value="">请选择省份</option>
                                                    @foreach ($province as $value)
                                                        <option  data-text="{{ $value->id }}" {{ $persons->province == $value->shortname ? 'selected="selected"':'' }} value="{{ $value->shortname }}">{{ $value->shortname }}</option>
                                                    @endforeach
                                                </select>
                                            </dd>
                                        </dl>
                                        <dl class="clearfix">
                                            <dt class="fl">*所在城市：</dt>
                                            <dd class="fr">
                                                <select id="city" name="city">
                                                    <option id="cityname" class="city_se" value="{{$persons->city}}">{{$persons->city}}</option>
                                                </select>
                                            </dd>
                                        </dl>

                                    </div>
                                </div>
                                <div class="btns">
                                    <input type="submit" value="保存" class="fl btn btn-finish">
                                    <a href="javascript:;" class="fr btn btn-cancel">取消</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- 完成与编辑 结束 -->
                </div>
                <!--个人信息 结束-->
            </div>
            <!-- 编辑项目 开始 -->
        </div>
        <!--简历主要 结束-->
        <!-- 简历提示 开始-->
        <div class="fr side">
            <div class="tp">
                <p class="step">完成以下步骤：</p>
                <p class="steptip">快速生成A4简历，适合打印和邮件发送</p>
                <div class="process clearfix">
                    <span class="fl active"></span>
                    <span class="fl line active"></span>
                    <span class="fl"></span>
                    <span class="fl line"></span>
                    <span class="fl"></span>
                </div>
                <div class="processtip">
                    <span class="active">1.编辑简历</span>
                    <span>2.选择模板</span>
                    <span>3.保存简历</span>
                </div>

            </div>
            <a href="javascript:;"  onclick="resumeselect()" class="sub-btn">生成A4简历</a>
        </div>
        <!--简历提示 结束-->
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
@endsection
@section('stylesheets')
    <link href="{{ asset('css/resume.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/resum-modify.css') }}" rel="stylesheet" />
    <link href="{{ asset('admin/assets/css/datepicker.css') }}" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/avatar.css') }}"/>

@endsection
@section('javascripts')
    <!--脚部结束-->
    <script src="{{ asset('admin/assets/js/date-time/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/date-time/locales/bootstrap-datepicker.zh-CN.js') }}"></script>
    <script src="{{ asset('js/common.js') }}"></script>
    <script src="{{ asset('js/resumeModify.js')}}"></script>
    <script src="{{ asset('admin/assets/js/ace/ace.js')}}"></script>
    <script src="{{ asset('js/plupload.full.min.js') }}"></script>
    <script src="{{ asset('js/upload.js') }}"></script>
    <script src="{{ asset('js/city.js') }}"></script>
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
                    var is = rsumeUploadzhong(_token, img);

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
@endsection
