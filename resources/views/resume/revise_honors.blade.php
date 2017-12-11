@extends('layouts.layout')
@section('head')
    <title>奖项荣誉-工作网</title>
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
                    <li class="fl"><a href="{{url('/resume/honors')}}" class="finish current">奖项荣誉</a></li>
                @else
                    <li class="fl"><a href="{{url('/resume/honors')}}" class="current">奖项荣誉</a></li>
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
                <!--奖项荣誉 开始-->
                <div class="clearfix pane modify-gold" style="display:block">
                    <div class="tp"><span>奖项荣誉</span></div>
                    <!-- 完成状态 开始 -->
                    @if(!empty($honors))
                        @foreach($honors as $honor)
                    <div class="cnt">

                            <div class="cnt-inner cnt-finish">
                                <a href="javascript:;" class="btn-modify" onclick="honor({{$honor->id}})">编辑</a>

                                    <ul class="clearfix">
                                        <li class="fl";>获得时间：{{str_replace('-','.',$honor->received_at)}}</li>
                                    </ul>
                                    <!-- 长描述开始 -->
                                    <dl>
                                        <dt>荣誉内容：</dt>
                                        <dd>{{$honor->award}} </dd>
                                    </dl>

                            <!-- 长描述 结束 -->
                            </div>

                        <div class="cnt-inner cnt-mod clearfix hide">
                            <form action="" id="from-honor{{$honor->id}}">
                                <input type="hidden" name="honorid" value="{{$honor->id}}">
                                {{ csrf_field() }}
                                <div class="clearfix">
                                    <dl class="fl">
                                        <dt class="fl">*获得时间：</dt>
                                        <dd class="fr"><input class="input-startday" type="text" value="{{$honor->received_at}}" autocomplete="off" name="received_at" placeholder="请选择获得时间"></dd>
                                    </dl>
                                </div>
                                <div class="inptext">
                                    <p>取得成就：</p>
                                    <div class="inptext-wrap">
                                        <textarea name="award" id="" placeholder="在校期间最自豪的事，有什么重大的课题研究，是否参与社团，获得过什么奖励和荣誉（2-1000字）">{{$honor->award}} </textarea>
                                    </div>
                                </div>
                                <div  class="btn-del clearfix"><a href="javascript:;" class="fr" onclick="honordel({{$honor->id}})">删除</a></div>
                                <div class="btns">
                                    <input type="submit" value="保存" class="fl btn btn-finish">
                                    <a href="javascript:;" class="fr btn btn-cancel">取消</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endforeach
                            <div class="addmore"><span>添加更多奖项荣誉</span></div>
                            <!-- 完成状态 结束 -->
                    @else
                    <!-- 没有时开始 -->
                    <div class="cnt">
                        <div class="cnt-mod clearfix">
                            <form action="" id="from-honoradd">
                                {{ csrf_field() }}
                                <div class="clearfix">
                                    <dl class="fl">
                                        <dt class="fl">*获得时间：</dt>
                                        <dd class="fr"><input autocomplete="off" class="input-startday" type="text" name="received_at" placeholder="获得时间"></dd>
                                    </dl>
                                </div>
                                <div class="inptext">
                                    <p>取得成就：</p>
                                    <div class="inptext-wrap">
                                        <textarea name="award" id="" placeholder="在校期间最自豪的事，有什么重大的课题研究，是否参与社团，获得过什么奖励和荣誉（2-1000字）"></textarea>
                                    </div>
                                </div>
                                <div class="btns">
                                    <input type="submit" value="保存" class="fl btn btn-finish">
                                    <a href="javascript:;" class="fr btn btn-cancel">取消</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endif
                    <!-- 没有时 结束-->
                    <div class="cnt hide cnt-new">
                        <div class="cnt-mod clearfix">
                            <form action="" id="from-honoradd">
                                {{ csrf_field() }}
                                <div class="clearfix">
                                    <dl class="fl">
                                        <dt class="fl">*获得时间：</dt>
                                        <dd class="fr"><input class="input-startday" type="text" name="received_at" autocomplete="off" placeholder="获得时间"></dd>
                                    </dl>
                                </div>
                                <div class="inptext">
                                    <p>取得成就：</p>
                                    <div class="inptext-wrap">
                                        <textarea name="award" id="" placeholder="在校期间最自豪的事，有什么重大的课题研究，是否参与社团，获得过什么奖励和荣誉（2-1000字）"></textarea>
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
                <!--奖项荣誉 结束-->
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
