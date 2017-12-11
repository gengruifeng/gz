@extends('layouts.layout')
@section('head')
    <title>个人经历-工作网</title>
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
                    <li class="fl last"><a href="{{url('/resume/experiences')}}" class="finish current">个人经历</a></li>
                @else
                    <li class="fl last"><a href="{{url('/resume/experiences')}}" class="current">个人经历</a></li>
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
            <div class="panes modify-mainlist">
            <!--个人经历 开始-->
                <div class="clearfix pane modify-experience" style="display:block">
                    <div class="tp"><span>个人经历</span></div>
                    <!-- 完成状态 开始 -->
                    @if(!empty($experiences))
                        @foreach($experiences as $experience)
                            <div class="cnt">
                        <!-- 完成状态 开始 -->
                            <div class="cnt-inner cnt-finish">
                                <a href="javascript:;" class="btn-modify" onclick="experience({{$experience->id}})">编辑</a>
                                    <ul class="clearfix">
                                        <li class="fl">公司名称：{{$experience->company}}</li>
                                        <li class="fl">职位：{{$experience->position}}</li>
                                        <li class="fl">起始时间：{{str_replace('-','.',$experience->from)}}</li>
                                        <li class="fl">结束时间：{{str_replace('-','.',$experience->to)}}</li>
                                    </ul>
                                    </ul>
                                    <!-- 长描述开始 -->
                                @if(!empty($experience->jobdescription))
                                    <dl>
                                        <dt>职位描述:</dt>
                                        <dd> {{$experience->jobdescription}}</dd>
                                    </dl>
                                @endif
                            <!-- 长描述 结束 -->
                            </div>
                    <!-- 完成状态 结束 -->
                        <!-- 编辑状态 开始 -->
                        <div class="cnt-inner cnt-mod clearfix hide">
                            <form action="" id="from-experience{{$experience->id}}">
                                {{ csrf_field() }}
                                <input type="hidden" value="{{$experience->id}}" name="experienceid">
                                <div class="clearfix">
                                    <dl class="fl">
                                        <dt class="fl">*公司名称：</dt>
                                        <dd class="fr"><input type="text" placeholder="公司、社团、项目" name="company" value="{{$experience->company}}"></dd>
                                    </dl>
                                    <dl class="fl">
                                        <dt class="fl">*职位名称：</dt>
                                        <dd class="fr">
                                            <input type="text" placeholder="如:PHP工程师" name="position" value="{{$experience->position}}" class="inpbtn">
                                            <!-- 职位 开始 -->
                                        </dd>
                                    </dl>
                                    <dl class="fl">
                                        <dt class="fl">*起始时间：</dt>
                                        <dd class="fr time"><input class="input-startday" type="text" autocomplete="off" name="time_start" value="{{$experience->from}}" placeholder="起始时间"></dd>
                                    </dl>
                                    <dl class="fl">
                                        <dt class="fl">*结束时间：</dt>
                                        <dd class="fr time"><input class="input-startday" name="time_end" autocomplete="off" value="{{$experience->to}}" type="text" placeholder="结束时间"></dd>
                                    </dl>
                                </div>
                                <div class="inptext">
                                    <p>职位描述：</p>
                                    <div class="inptext-wrap">
                                        <textarea name="jobdescription" id="" placeholder="具体描述你在这段经历中做了什么事情（2-1000字符）。可以包括目标、方法、使用的技术或者工具, 若有不错的成绩，会提高这段经历的竞争力, 如有必要，可以简单介绍公司、社团或项目">{{$experience->jobdescription}}</textarea>
                                    </div>
                                </div>
                                <div  class="btn-del clearfix"><a href="javascript:;" class="fr" onclick="experiencedel({{$experience->id}})">删除</a></div>
                                <div class="btns">
                                    <input type="submit" value="保存" class="fl btn btn-finish">
                                    <a href="javascript:;" class="fr btn btn-cancel">取消</a>
                                </div>
                            </form>
                        </div>
                        <!-- 编辑状态 结束 -->
                    </div>
                        @endforeach
                    <!-- 完成状态 结束 -->
                    <div class="addmore"><span>添加更多个人经历</span></div>
                    <!-- 没有时 开始 -->
                    @else
                    <div class="cnt">
                        <div class="cnt-mod clearfix">
                            <form action="" id="from-experienceadd">
                                {{ csrf_field() }}
                                <div class="clearfix">
                                    <dl class="fl">
                                        <dt class="fl">*公司名称：</dt>
                                        <dd class="fr"><input type="text" placeholder="公司、社团、项目" name="company"></dd>
                                    </dl>
                                    <dl class="fl">
                                        <dt class="fl">*职位名称：</dt>
                                        <dd class="fr">
                                            <input type="text" placeholder="如:PHP工程师" name="position"  class="inpbtn">
                                            <!-- 职位 开始 -->
                                        </dd>
                                    </dl>
                                    <dl class="fl">
                                        <dt class="fl">*起始时间：</dt>
                                        <dd class="fr time"><input class="input-startday" type="text" autocomplete="off" name="time_start" placeholder="起始时间"></dd>
                                    </dl>
                                    <dl class="fl">
                                        <dt class="fl">*结束时间：</dt>
                                        <dd class="fr time"><input class="input-startday" name="time_end" autocomplete="off" type="text" placeholder="结束时间"></dd>
                                    </dl>
                                </div>
                                <div class="inptext">
                                    <p>取得成就：</p>
                                    <div class="inptext-wrap">
                                        <textarea name="jobdescription" id="" placeholder="具体描述你在这段经历中做了什么事情（2-1000字符）。可以包括目标、方法、使用的技术或者工具, 若有不错的成绩，会提高这段经历的竞争力, 如有必要，可以简单介绍公司、社团或项目"></textarea>
                                    </div>
                                </div>
                                <div class="btns">
                                    <input type="submit" value="保存" class="fl btn btn-finish">
                                    <a href="javascript:;" class="fr btn btn-cancel">取消</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- 没有时 结束-->
                    @endif
                        <div class="cnt hide cnt-new">
                            <div class="cnt-mod clearfix">
                                <form action="" id="from-experienceadd">
                                    {{ csrf_field() }}
                                    <div class="clearfix">
                                        <dl class="fl">
                                            <dt class="fl">*公司名称：</dt>
                                            <dd class="fr"><input type="text" placeholder="公司、社团、项目" name="company"></dd>
                                        </dl>
                                        <dl class="fl">
                                            <dt class="fl">*职位名称：</dt>
                                            <dd class="fr">
                                                <input type="text" placeholder="如:PHP工程师" name="position" class="inpbtn">
                                                <!-- 职位 开始 -->
                                            </dd>
                                        </dl>
                                        <dl class="fl">
                                            <dt class="fl">*起始时间：</dt>
                                            <dd class="fr time"><input class="input-startday" autocomplete="off" type="text" name="time_start" placeholder="起始时间"></dd>
                                        </dl>
                                        <dl class="fl">
                                            <dt class="fl">*结束时间：</dt>
                                            <dd class="fr time"><input class="input-startday" autocomplete="off" name="time_end"  type="text" placeholder="结束时间"></dd>
                                        </dl>
                                    </div>
                                    <div class="inptext">
                                        <p>取得成就：</p>
                                        <div class="inptext-wrap">
                                            <textarea name="jobdescription" id="" placeholder="具体描述你在这段经历中做了什么事情（2-1000字符）。可以包括目标、方法、使用的技术或者工具, 若有不错的成绩，会提高这段经历的竞争力, 如有必要，可以简单介绍公司、社团或项目"></textarea>
                                        </div>
                                    </div>
                                    <div class="btns">
                                        <input type="submit" value="保存" class="fl btn btn-finish">
                                        <a href="javascript:;" class="fr btn btn-cancel">取消</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                </div>
            <!--个人经历 结束-->
            </div>
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
