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
                <div class="tm-four clearfix">
                    <!-- 基础信息 -->
                    <div class="colomn-base">

                            <dl class="head clearfix">
                                <dt class="fl dtfirst">
                                <div class="name">
                                    @if(!empty($persons))
                                        {{ $persons->name }}
                                    @endif
                                </div>
                                <p>求职意向：@if(!empty($advices)) {{ $advices->position }} @endif</p>
                                </dt>
                                @if(!empty($persons))
                                    <dt class="fl dtsecond">
                                        <p class="tel">电话：{{ $persons->mobile }}</p>
                                        <p class="email">E-mail : {{ $persons->email }}</p>
                                        <p class="birthday">出生日期：{{ $persons->birthday }}</p>
                                    </dt>
                                @endif
                                <dd class="fr ddthird">
                                    @if(!empty($persons))
                                        <img src="{{ asset('/resume/'.$persons->resumeavatar) }}">
                                    @else
                                        <img src="{{ asset('/resume/head.png') }}">
                                    @endif
                                </dd>
                            </dl>

                    </div>
                    <div class="colomn">
                        <h2 class=""><span>求职意向</span></h2>
                        @if(!empty($advices))
                            <dl class="clearfix">
                                <dt class="fl">&nbsp;</dt>
                                <dd class="fl">
                                    <h3>{{ $advices->position }}</h3>
                                </dd>
                            </dl>
                        @endif
                    </div>
                    <div class="colomn">
                        <h2 class=""><span>教育背景</span></h2>
                        @if(!empty($educations))
                            @foreach( $educations as $education)
                                <dl class="clearfix">
                                    <dt class="fl">{{ $education->educationtime }}</dt>
                                    <dd class="fl">
                                        <h3>{{ $education->school }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $education->department }}</h3>
                                        @if(!empty($education->success))
                                            <p>{{ $education->success }}</p>
                                        @endif
                                    </dd>
                                </dl>
                            @endforeach
                        @endif
                    </div>
                    <div class="colomn">
                        <h2 class=""><span>工作经验</span></h2>
                        @if(!empty($experiences))
                            @foreach($experiences as $experience)
                                <dl class="clearfix">
                                    <dt class="fl">{{ $experience->experiencetime }}</dt>
                                    <dd class="fl">
                                        <h3>{{ $experience->position }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $experience->company }}</h3>
                                        @if(!empty($experience->jobdescription))
                                            <p>{{ $experience->jobdescription }}</p>
                                        @endif
                                    </dd>
                                </dl>
                            @endforeach
                        @endif
                    </div>

                    <div class="colomn">
                        <h2 class=""><span>奖项荣誉</span></h2>
                        <dl class="clearfix">
                            <dt class="fl">&nbsp;</dt>
                            <dd class="fl">
                                @if(!empty($honors))
                                    @foreach($honors as $honor)
                                        <p>{{ $honor->yearmonth }}，{{ $honor->award }}</p>
                                    @endforeach
                                @endif
                            </dd>
                        </dl>
                    </div>

                    <div class="colomn">
                        <h2 class=""><span>技能证书</span></h2>
                        <dl class="clearfix">
                            <dt class="fl">&nbsp;</dt>
                            <dd class="fl">
                                @if(!empty($diplomas))
                                    @foreach($diplomas as $diploma)
                                        <p>
                                            @if(!empty($diploma->certificate))
                                                {{ $diploma->certificate }}
                                            @else
                                                {{ $diploma->supplementary }}
                                            @endif
                                            @if(!empty($diploma->achivement))
                                                , {{ $diploma->achivement }}
                                            @endif
                                        </p>
                                    @endforeach
                                @endif
                            </dd>
                        </dl>

                    </div>

                </div>
                @if(!empty($persons))
                    <input type="hidden" id="cvid" value="{{ $persons->id }}">
                @endif
                <input type="hidden" id="resumemodel" value="second">
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
                    <a href="{{ url('/resume/choice/second') }}" class="fl active">2</a>
                    <a href="{{ url('/resume/choice/third') }}" class="fl">3</a>
                    <a href="{{ url('/resume/choice/fourth') }}" class="fl">4</a>
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



