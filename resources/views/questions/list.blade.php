@extends('layouts.layout')

@section('head')
    <title>工作网-问答-分享有价值的职场经验</title>
    <meta name="keywords" content="工作网,简历,面试,笔试,实习,offer,500强,案例,群面,职场,职场经验">
    <meta name="description" content="工作网问答，帮助大学生解决笔试面试疑问的问答社区，获得有价值的职场经验。">
@endsection

@section('stylesheets')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/answers.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/selectize/selectize.default.css') }}"/>
@endsection
@section('content')
<!--内容开始-->
    <!--搜索开始-->
    <div class="search-section">
        <form action="/search" class="clearfix search-index">
            <div class="fl search-inner"><input type="text" name="q" id="search" placeholder="提问前请先搜索，看看您的问题其他用户是否已经问过..." /></div>

            <button class="fr"></button>
            <input class="_token" type="text" />
        </form>
        <p class="search-tips">共<span> {{$askinfo['questioncount']}} </span> 问题 <span>  {{$askinfo['usercount']}} </span>人参与</p>
    </div>
    <!--搜索结束-->
    <div class="clearfix margincenter">
        <!--热门问题开始-->
        <div class="question-main">
            <!-- 热门问题 -->
            <div class="question-hot">
                <div class="question-hot-title">热门问题</div>
                <ul>
                    @foreach ($askinfo['hotasks'] as $hotasks)
                    <li>
                        <div class="question-hot-numb">
                            <span>{{$hotasks->answered}}</span>
                            <span>回答</span>
                        </div>
                        <div class="question-hot-msg">
                            <p>
                                <a href="/questions/{{  $hotasks->id }}">
                                    {!! $hotasks->subject !!}
                                </a>
                            </p>
                            <p>
                                {!! strip_tags($hotasks->detail) !!}
                            </p>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
            <!-- 问题列表 -->
            <div class="question-list">
                <div class="question-list-tab">
                    @if($askinfo['type']=='created_at')
                        <a class="qa onClick" href='{{url("questions?type=created_at")}}' id="created_at">最新问题</a>
                    @else
                        <a class="qa" href='{{url("questions?type=created_at")}}' id="created_at">最新问题</a>
                    @endif
                    @if($askinfo['type']=='vote_up')
                        <a class="qa onClick" href='{{url("questions?type=vote_up")}}' id="vote_up">最多点赞</a>
                    @else
                        <a class="qa" href='{{url("questions?type=vote_up")}}' id="vote_up">最多点赞</a>
                    @endif
                    @if($askinfo['type']=='answered')
                        <a class="qa onClick" href='{{url("questions?type=answered")}}' id="answered">待回答问题</a>
                    @else
                        <a class="qa" href='{{url("questions?type=answered")}}' id="answered">待回答问题</a>
                    @endif

                </div>
                <div id="paged">
                    <ul class="qa_ul" >
                    </ul>
                </div>
                <input type="hidden" class="_token" id="type" value="{{$askinfo['type']}}">
            </div>
            {{ csrf_field() }}
            <input class='_token' id='page' type='hidden'>
        </div>
        <!--热门问题结束-->
        <!--我要提问开始-->
        <div>
            <div><a onclick="isLogin('/questions/ask',1)" href="javascript:void(0)">我要提问</a></div>
            <div>
                <a href="http://campus.jd.com/web/static/forward?to=video_list&t=8" target="_blank"><img src="{{ asset('images/advertisement/jdstory.png') }}" /></a>
            </div>
            <div>
                <div><h3>热门标签</h3></div>
                <div>
                    @foreach ($askinfo['hottags'] as $hottags)
                    <a href="{{url('questions/tagged/'.$hottags->name)}}">{{$hottags->name}}</a>
                    @endforeach
                </div>
            </div>
        </div>
        <!--我要提问结束-->
    </div>
<a href="javascript:;" id="btn-back-top"></a>
<!--内容结束-->
@endsection
@section('askdateil')
    <!--私信遮罩层开始-->
    <div id="maskLayer" class="dispaly">

    </div>
    <!--私信遮罩层结束-->
    <!--提问遮罩层开始-->
    <div id="maskLayer_1" class="dispaly">

    </div>
    <!--提问遮罩层结束-->
@endsection

@section('javascripts')
    <script src="{{ asset('js/typeahead.bundle.js')}}"></script>
    <script src="{{ asset('js/handlebars.js')}}"></script>
    <script src="{{ asset('js/asklist.js') }}"></script>
    <script src="{{ asset('js/search.js') }}"></script>
    <script src="{{ asset('js/selectize.js') }}"></script>
    <script src="{{ asset('js/shared/util.js') }}"></script>
    <script src="{{ asset('js/view/paged_list.js') }}"></script>
    <script src="{{ asset('js/view/hover_menu.js') }}"></script>
    <script src="{{ asset('js/card.js') }}"></script>
    <script>
        $('#paged').pagedList({
            serverCall: 'asklist',
            kwargs: QueryString,
            hiddenClass: 'hidden'
        });

        $(document).on('mouseenter', '.rich-avatar', function (event) {
            $(this).hoverCard({
                cardUrl: 'card-url',
                hiddenClass: 'hidden',
                cardOffset: 11
            });
        });
    </script>
@endsection

<link rel="stylesheet" type="text/css" href="../css/vcommon.css"/>
