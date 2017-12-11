@extends('layouts.layout')

@section('head')
    <title>我的简历-工作网</title>
@endsection

@section('stylesheets')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/resume.css') }}"/>
@endsection
@section('content')
    <!-- 简历主要 开始-->
    <div class="resume resumeMine clearfix">
        {{ csrf_field() }}
        <div class="fl main">
            <div class="tp clearfix">我的简历 <a class="fr" href="{{url('resume/persons')}}">编辑简历</a></div>
            <div class="datalist">
                <!-- 个人信息 -->
                <div class="colomn colomn-personal">
                    <h3><span>个人信息</span></h3>
                    <ul class="clearfix">
                        <li class="fl">姓名：{{$persons->name}}</li>
                        <li class="fl">性别：{{$persons->gender}}</li>
                        <li class="fl">出生日期：{{str_replace('-','.',$persons->birthday)}}</li>
                        <li class="fl">手机：{{$persons->mobile}}</li>
                        <li class="fl">邮箱：{{$persons->email}}</li>
                        <li class="fl">所在城市：{{$persons->city}}</li>
                    </ul>
                    <!-- 描述等 -->
                    <!-- <div class="describing"></div> -->
                </div>
                <!-- 教育背景 -->
                @if(!empty($educations))
                <div class="colomn colomn-education">
                    <h3><span>教育信息</span></h3>
                    @foreach($educations as $education)
                    <ul class="clearfix">
                        <li class="fl">院校名称：{{$education->school}}</li>
                        <li class="fl">所学专业：{{$education->department}}</li>
                        <li class="fl">起止时间：{{str_replace('-','.',$education->enrolled)}}-{{str_replace('-','.',$education->graduated)}}</li>
                        <li class="fl">学历：{{$education->education}}</li>
                    </ul>
                    <!-- 描述等 -->
                    @if(!empty($education->success))
                    <div class="describing">
                        <p>取得成就：{{$education->success}}</p>
                    </div>
                    @endif
                    @endforeach
                </div>
                @endif
                <!-- 个人经历 -->
                @if(!empty($experiences))
                <div class="colomn colomn-experice">
                    <h3><span>个人经历</span></h3>
                    @foreach($experiences as $experience)
                    <ul class="clearfix">
                        <li class="fl">公司名称：{{$experience->company}}</li>
                        <li class="fl">职位：{{$experience->position}}</li>
                        <li class="fl">起止时间：{{str_replace('-','.',$experience->from)}}-{{str_replace('-','.',$experience->to)}}</li></ul>
                    @if(!empty($experience->jobdescription))
                    <!-- 描述等 -->
                    <div class="describing">
                        <p>职位描述： </p>
                        <div class="des_contain">
                            {{$experience->jobdescription}}
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
                @endif
                <!-- 求职意向 -->
                @if(!empty($advices))
                <div class="colomn colomn-toward">
                    <h3><span>求职意向</span></h3>
                    <ul class="clearfix">
                        <li class="fl">工作经验：{{$advices->word_period}}</li>
                        <li class="fl">期望城市：{{$advices->city}}</li>
                        <li class="fl">期望职位：{{$advices->position}}</li>
                        @if($advices->employment_type==1)
                            <li class="fl">职位类型：全职</li>
                        @elseif($advices->employment_type==2)
                            <li class="fl">职位类型：兼职</li>
                        @elseif($advices->employment_type==3)
                            <li class="fl">职位类型：实习生</li>
                        @endif
                        <li class="fl">期望月薪：{{$advices->salary}}</li>
                        <li class="fl">求职状态：{{$advices->job_type}}</li>
                    </ul>
                    <!-- 描述等 -->
                    <!-- <div class="describing"></div> -->
                </div>
                @endif
                <!-- 技能证书 -->
                @if(!empty($diplomas))
                <div class="colomn colomn-expert">
                    <h3><span>技能证书</span></h3>
                    @foreach($diplomas as $diploma)
                    <ul class="clearfix">
                        @if(!empty($diploma->certificate))
                            <li class="fl";>证书名称：{{$diploma->certificate}}</li>
                        @elseif(!empty($diploma->supplementary))
                            <li class="fl";>证书名称：{{$diploma->supplementary}}</li>
                        @endif
                    </ul>
                    <!-- 描述等 -->
                    <div class="describing">
                        <p>成绩： {{$diploma->achivement}}</p>
                    </div>
                    @endforeach
                </div>
                @endif
                <!-- 奖项荣誉 -->
                @if(!empty($honors))
                <div class="colomn colomn-gold">
                    <h3><span>奖项荣誉</span></h3>
                    @foreach($honors as $honor)
                    <ul class="clearfix">
                        <li class="fl">获得日期：{{$honor->received_at}}</li>
                    </ul>
                    <!-- 描述等 -->
                    <div class="describing">
                        <p>荣誉内容：{{$honor->award}}</p>
                    </div>
                    @endforeach
                </div>
                @endif
                <!-- 个人作品 -->
                @if(!empty($projects))
                <div class="colomn colomn-work">
                    <h3><span>个人作品</span></h3>
                    @foreach($projects as $project)
                        <ul class="clearfix">
                            <li class="fl">时间：{{str_replace('-','.',$project->worked_at)}}</li>
                            <li class="fl">作品名称：{{$project->title}}</li>
                            @if(!empty($project->subtitle))
                            <li class="fl">副标题：{{$project->subtitle}}</li>
                            @endif
                        </ul>
                        <!-- 描述等 -->
                        <div class="describing">
                            <p>作品描述：</p>
                            <div class="des_contain">
                                {{$project->description}}
                            </div>
                        </div>
                    @endforeach
                </div>
                @endif
                <!-- 兴趣爱好 -->
                @if(!empty($interests))
                <div class="colomn colomn-hobby">
                    <h3><span>兴趣爱好</span></h3>
                    <!-- 描述等 -->
                    <div class="describing">
                        <div class="des_contain">
                            {{$interests->interests}}
                        </div>
                    </div>
                </div>
                @endif
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
            <a href="javascript:;"  onclick="resumeselect()"  class="sub-btn">生成A4简历</a>
        </div>
    </div>
    <!--简历提示 结束-->
@endsection
@section('javascripts')
    <script src="{{ asset('js/resumeTemplate.js') }}"></script>
@endsection
