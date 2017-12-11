@extends('layouts.layout')
@section('head')
    <title>兴趣爱好-工作网</title>
@endsection
@section('content')

    <div class="resume resumeMine resume-modify clearfix">
        <!-- 简历主要 开始-->
        <div class="fl modify-maintp">
            <!-- 选择编辑项目 开始 -->
            <ul class="tabs clearfix">
                <li class="fl "><a href="{{url('/resume/persons')}}" class="finish">个人信息</a></li>
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
                    <li class="fl last"><a href="{{url('/resume/interests')}}" class="current finish">兴趣爱好</a></li>
                @else
                    <li class="fl last"><a href="{{url('/resume/interests')}}" class="current">兴趣爱好</a></li>
                @endif

            </ul>
            <!-- 选择编辑项目 结束 -->

            <!-- 编辑项目 开始 -->
            <div class="panes modify-mainlist">
                <!--兴趣爱好 开始-->
                <div class="clearfix pane modify-hobby" style="display:block">
                    <div class="tp"><span>兴趣爱好</span></div>
                    <!-- 完成状态 开始 -->
                    @if(!empty($interests))
                    <div class="cnt">
                            <div class="cnt-inner cnt-finish">
                                <a href="javascript:;" class="btn-modify">编辑</a>
                                <!-- 长描述开始 -->
                                <dl>
                                    <dt>描述：</dt>
                                    <dd> {{$interests->interests}}</dd>
                                </dl>
                                <!-- 长描述 结束 -->
                            </div>
                        <div class="cnt-inner cnt-mod clearfix hide">
                            <form action="" id="form-interests">
                                {{ csrf_field() }}
                                <div class="inptext">
                                    <p>描述：</p>
                                    <div class="inptext-wrap">
                                        <textarea name="interests" id="" placeholder="请填写你的特长爱好">{{$interests->interests}}</textarea>
                                    </div>
                                </div>
                                <div class="btns">
                                    <input type="submit" value="保存" class="fl btn btn-finish">
                                    <a href="javascript:;" class="fr btn btn-cancel">取消</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- 完成状态 结束 -->
                    @else
                    <!-- 没有信息时 开始 -->
                    <div class="cnt">
                        <div class="cnt-mod clearfix">
                            <form action="" id="form-interests">
                                {{ csrf_field() }}
                                <div class="inptext">
                                    <p>描述：</p>
                                    <div class="inptext-wrap">
                                        <textarea name="interests" id="" placeholder="请填写你的特长爱好"></textarea>
                                    </div>
                                </div>
                                <div class="btns">
                                    <input type="submit" value="保存" class="fl btn btn-finish">
                                    <a href="javascript:;" class="fr btn btn-cancel">取消</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- 没有信息时  结束-->
                    @endif
                </div>
                <!--兴趣爱好 结束-->
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
            <a href="javascript:;"  onclick="resumeselect()"  class="sub-btn">生成A4简历</a>
        </div>
        <!--简历提示 结束-->
    </div>
@endsection
@section('stylesheets')
    <link href="{{ asset('css/resume.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/resum-modify.css') }}" rel="stylesheet" />
    <link href="{{ asset('admin/assets/css/datepicker.css') }}" rel="stylesheet" />
@endsection
@section('javascripts')
    <!--脚部结束-->
    <script src="{{ asset('admin/assets/js/date-time/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/date-time/locales/bootstrap-datepicker.zh-CN.js') }}"></script>
    <script src="{{ asset('js/resumeModify.js')}}"></script>
    <script src="{{ asset('admin/assets/js/ace/ace.js')}}"></script>
@endsection
