@extends('layouts.layout')

@section('head')
    <title>个人中心-工作网</title>
@endsection

@section('stylesheets')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/myCenter.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/jquery-ui.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/selectize/selectize.default.css') }}"/>
@endsection

@section('content')
    <!--个人中心开始-->
    <!--第一部分开始-->
    <div class="user-info clearfix">
        <div class="user-base ">
            <div class="user-avatar">
                @if(!empty($personalCenterInfo['avatar']))
                    <img src="{{url('/avatars/120/'.$personalCenterInfo['avatar'])}}"/>
                @else
                    <img src="{{url('/avatars/120/head.png')}}"/>
                @endif
            </div>
            <div class="user-operator">
                <p><span>{{$personalCenterInfo['display_name']}}</span></p>
                <p>
                    @if(!empty($personalCenterInfo['describe']))
                        <span>{{$personalCenterInfo['describe']['first']}}</span><span>{{$personalCenterInfo['describe']['second']}}</span>
                    @endif
                </p>
                <p class="about-s">
                    @if(empty($uid))
                        <a href="{{url('profile/'.$personalCenterInfo['userId'].'/follower')}}" class="user-follow">粉丝 <span id = "fansNums{{$personalCenterInfo['userId']}}">{{$personalCenterInfo['follower']['num'] or 0}}</span></a>
                        <a href="{{url('profile/'.$personalCenterInfo['userId'].'/following')}}" class="user-follow">关注 <span>{{$personalCenterInfo['following']['num'] or 0}}</span></a>
                        @if($personalCenterInfo['isAttention'] >0)
                            <a href="javascript:void(0)" class="btn-attion btn-attion-cancel attention{{$personalCenterInfo['userId']}}" onclick="deleteAttention({{$personalCenterInfo['userId']}})">取消关注</a>
                        @else
                            <a href="javascript:void(0)" class="btn-attion attention{{$personalCenterInfo['userId']}}" onclick="isLogin('addAttention({{$personalCenterInfo['userId']}})',2)">加关注</a>
                        @endif
                        <a id="myCenter_sendLetter" href="javascript:void(0)" class="user-sendinfo">发私信</a><a id="myCenter_quiz" href="javascript:void(0)" class="user-sendinfo">向ta提问</a>
                    @else
                        <a href="{{url('profile/follower')}}" class="user-follow">粉丝
                            <span id="fansNums">
                                {{$personalCenterInfo['follower']['num'] or 0}}
                            </span>
                        </a>
                        <a href="{{url('profile/following')}}" class="user-follow">关注 <span>{{$personalCenterInfo['following']['num'] or 0}}</span></a>
                    @endif
                </p>
            </div>
            <div class="user-edit">
                @if(!empty($uid))
                    <a href="{{url('account/settings')}}">编辑</a>
                @endif
            </div>
        </div>
    </div>
    <!--第二部分开始-->
    <div class="field">
        <div>
            <h3>擅长领域</h3>
        </div>
        <div>
            <ul class="clearfix">
                @if(!empty($personalCenterInfo['userTagInfo']))
                    @foreach($personalCenterInfo['userTagInfo'] as $tagInfo)
                        <li>{{$tagInfo->name}}</li>
                    @endforeach
                @endif
            </ul>
        </div>
    </div>
    <!--第三部分开始-->
    <div class="user-about">
        <div id="fuc" class="clearfix">
            <a class="question" data-text="{{$personalCenterInfo['userId']}}" href="javascript:void(0)" onclick="maohash('question')">提问</a>
            <a class="answer" data-text="{{$personalCenterInfo['userId']}}" href="javascript:void(0)" onclick="maohash('answer')">回答</a>
            <a class="article" data-text="{{$personalCenterInfo['userId']}}" href="javascript:void(0)" onclick="maohash('article')">文章</a>
            <a class="collect" data-text="{{$personalCenterInfo['userId']}}" href="javascript:void(0)" onclick="maohash('collect')">收藏</a>
            <a class="follow" data-text="{{$personalCenterInfo['userId']}}" href="javascript:void(0)" onclick="maohash('follow')">关注</a>
        </div>

        <div  id="show">
            <div id="contentShowquestion" class="question mc hide">
                <ul></ul>
            </div>
            <div id="contentShowanswer" class="answer mc hide">
                <ul></ul>
            </div>
            <div id="contentShowarticle" class="article mc hide">
                <ul></ul>
            </div>
            <div id="contentShowcollect" class="collect mc hide">
                <ul></ul>
            </div>
            <div id="contentShowfollow" class="follow mc hide">
                <ul></ul>
            </div>

        </div>
    <!--个人中心结束-->
    </div>
    <!--私信遮罩层开始-->
    <div id="maskLayer" class="display">
        <!--写私信开始-->
        <div id="writeMessage_1">
            <form action="">
                <div>
                    <h3>写私信</h3></div>
                <div>
                    <input id="privateMsgId" type="search" value="{{$personalCenterInfo['display_name']}}" disabled />
                </div>
                <div>
                    <textarea id="privateLetterMsg" name=""></textarea>
                </div>
                <div>
                    <a id="adddialog" href="javascript:void(0)" onclick="addDialog()">发送</a>
                    <a id="call_oof" href="javascript:void(0)">取消</a>
                </div>
                {{ csrf_field() }}
            </form>
        </div>
    </div>
    <!--私信遮罩层结束-->
    <!--提问遮罩层开始-->
    <div id="maskLayer_1" class="dispaly">
        <div id="questions">
            <form action="">
                <div>
                    <h3>向 <a href="javascript:void(0)">{{$personalCenterInfo['display_name']}}</a> 提问</h3>
                </div>
                <div>
                    <input type="search" id="questioonTitle" placeholder="标题：一句话描述问题，用问号结尾" />
                    <div></div>
                </div>
                <div>
                    <textarea id="questionContent"></textarea>
                </div>
                <div style="display: none"></div>
                <div class="clearfix demo">
                    <input type="text" id="search_tag" placeholder="添加标签（回车键创建新标签）">
                </div>
                <div>
                    <a id="askquestion" href="javascript:void(0)" onclick="askQuestion({{$personalCenterInfo['userId']}})">发送</a>
                    <a id="call_oof_1" href="javascript:void(0)">取消</a>
                </div>
            </form>
        </div>
    </div>
    <!--提问遮罩层结束-->
@endsection


@section('javascripts')
    <script src="{{ asset('js/anchor.js') }}" type="text/javascript" charset="utf-8"></script>
    <script src="{{ asset('js/personalCenter.js') }}" type="text/javascript" charset="utf-8"></script>
    <script src="{{ asset('js/public.js') }}" type="text/javascript" charset="utf-8"></script>
    <script src="{{ asset('js/selectize.js') }}"></script>
    <script src="{{ asset('js/searchtags.js') }}"></script>
    <script src="{{ asset('js/view/paged_list.js') }}"></script>
    <script src="{{ asset('js/shared/util.js') }}"></script>
@endsection
