@extends('layouts.layout')

@section('head')
    <title>样式模板-工作网</title>
@endsection

@section('stylesheets')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/resume.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('/css/vcommon.css')}}"/>
@endsection


@section('content')
    <!--内容开始-->
    <section class="resume resumeMine resum-choice clearfix" >
        <!-- 放模板简历 开始-->
        <div class="fl main">
            <div class="your-Tm">
                <div class="tm-two clearfix">
                    <div class="colomn-base">
                        @if(!empty($persons))
                            <h2>{{ $persons->name }}</h2>
                            <dl class="head clearfix">
                                <dt class="fl">
                                <p>年龄：{{ $persons->age }}岁	</p>
                                <p>电话：{{ $persons->mobile }}</p>
                                </dt>
                                <dt class="fl">
                                <p>性别：{{ $persons->gender }}</p>
                                <p>邮箱：{{ $persons->email }}</p>
                                </dt>
                                <dd class="fl">
                                    @if(!empty($persons->resumeavatar))
                                        <img src="{{ asset('/resume/'.$persons->resumeavatar) }}">
                                    @else
                                        <img src="{{ asset('/images/haili.png') }}">
                                    @endif
                                </dd>
                            </dl>
                        @endif
                    </div>
                    <div class="colomn">
                        <h2>教育背景：</h2>
                        @if(!empty($educations))
                            @foreach( $educations as $education)
                                <p>{{ $education->educationtime }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $education->school }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $education->department }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $education->education }}</p>
                            @endforeach
                        @endif
                    </div>
                    <div class="colomn">
                        <h2>奖项荣誉：</h2>
                        @if(!empty($honors))
                            @foreach( $honors as $honor)
                                <p>{{ $honor->honortime }}，{{ $honor->award }}</p>
                            @endforeach
                        @endif
                    </div>
                    <div class="colomn">
                        <h2>个人经历：</h2>
                        @if(!empty($experiences))
                            @foreach($experiences as $experience)
                                <div class="colomn-inner">
                                    <h3>{{ $experience->experiencetime }}&nbsp;&nbsp;&nbsp;&nbsp;{{ $experience->company }}&nbsp;&nbsp;&nbsp;&nbsp;{{ $experience->position }}</h3>
                                    @if(!empty($experience->jobdescription))
                                        <p>{{ $experience->jobdescription }}</p>
                                    @endif
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="colomn">
                        <h2>个人作品：</h2>
                        <div class="colomn-inner">
                            @if(!empty($projects))
                                @foreach($projects as $project)
                                    <p>{{ $project->projecttime }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $project->description }}</p>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="colomn">
                        <h2>技能证书：</h2>
                        @if(!empty($diplomas))
                            @foreach($diplomas as $diploma)
                                <div class="colomn-inner">
                                    <p>@if(!empty($diploma->certificate))
                                            {{ $diploma->certificate }}
                                        @else
                                            {{ $diploma->supplementary }}
                                        @endif @if(!empty($diploma->achivement)) , {{ $diploma->achivement }}@endif
                                    </p>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="colomn">
                        <h2>求职意向：</h2>
                        @if(!empty($advices))
                            <div class="colomn-inner">
                                <p>期望职位：{{ $advices->position }}</p>
                                <p>期望城市：{{ $advices->city }}</p>
                            </div>
                        @endif</div>

                    </div>
                </div>
                @if(!empty($persons))
                    <input type="hidden" id="cvid" value="{{ $persons->id }}">
                @endif
                <input type="hidden" id="resumemodel" value="fourth">
            </div>
        </div>
        <!--放模板简历 结束-->

        <!-- 简历提示 开始-->
        <div class="fr side">
            <div class="tp">
                <p class="step">完成以下步骤：</p>
                <p class="steptip">快速生成A4简历，适合打印和邮件发送</p>
                <div class="process clearfix">
                    <span class="fl active"></span>
                    <span class="fl line active"></span>
                    <span class="fl active"></span>
                    <span class="fl line active"></span>
                    <span class="fl"></span>
                </div>
                <div class="processtip">
                    <span class="active">1.编辑简历</span>
                    <span class="active">2.选择模板</span>
                    <span>3.保存简历</span>
                </div>
            </div>
            <!-- 选择 -->
            <div class="tm-choice">
                <h3><span>样式模板</span></h3>
                <div class="btnwp clearfix">
                    <a href="{{ url('/resume/choice/first') }}" class="fl">1</a>
                    <a href="{{ url('/resume/choice/second') }}" class="fl">2</a>
                    <a href="{{ url('/resume/choice/third') }}" class="fl">3</a>
                    <a href="{{ url('/resume/choice/fourth') }}" class="fl active">4</a>
                    {{--<a href="{{ url('/resume/choice/fifth') }}" class="fl last">5</a>--}}
                    {{--<a href="javascript:;" class="fl">6</a>--}}
                    {{--<a href="javascript:;" class="fl">7</a>--}}
                    {{--<a href="javascript:;" class="fl">8</a>--}}
                    {{--<a href="javascript:;" class="fl">9</a>--}}
                    {{--<a href="javascript:;" class="fl last">10</a>--}}
                    {{--<a href="javascript:;" class="fl">11</a>--}}
                    {{--<a href="javascript:;" class="fl">12</a>--}}
                    {{--<a href="javascript:;" class="fl">13</a>--}}
                    {{--<a href="javascript:;" class="fl">14</a>--}}
                    {{--<a href="javascript:;" class="fl last">15</a>--}}
                </div>
            </div>
            <div class="btns">
                <a href="javascript:void(0);" class="btn btn-yellow  btn-save btn-wfull">保存此简历</a>
                <a href="{{ url('resume/my') }}"  class="btn btn-grey btn-wfull">返回上一步</a>
            </div>
        </div>
        <!--简历提示 结束-->
    </section>
    <!--内容结束-->
    <!-- 弹出确认层 开始 -->
    <div class="resume-alert hide">
        <div class="inner">
            <h3><span>简历标题</span></h3>
            <div class="cnt">
                <dl class="clearfix">
                    <dt class="fl">*简历标题：</dt>
                    <dd class="fl">
                        <input id="resumeTitle" name="title" type="text" placeholder="请更新您的简历标题">
                        <p>请更新您的简历标题</p>
                    </dd>
                </dl>
                <p>建议格式：应聘XX岗位-姓名-学校-手机号</p>
                <p>实际样例：应聘市场营销-小明-清华大学-18513220921</p>
            </div>
            {{ csrf_field() }}
            <div class="btns cleafix">
                <div class="btn btn-yellow btn-success fl">完成</div>
                <div class="btn fr">取消</div>
            </div>
        </div>
    </div>
    <!-- 弹出确认层 结束 -->
@endsection

@section('javascripts')
    <script src="{{ asset('js/resumeTemplate.js') }}"></script>
    <script src="{{ asset('js/resumeChoice.js') }}"></script>
    <script src="{{asset('/js/jquery.validate.js')}}"></script>
@endsection



