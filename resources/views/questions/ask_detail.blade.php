@extends('layouts.layout')

@section('head')
    <title>{{$askdetail['subject']}}-问答-工作网</title>
    <meta name="keywords" content="{{$askdetail['tagsToString']}}">
    <meta name="description" content="{{$askdetail['subject']}} 工作网欢迎您来分享您的职场经验。">
@endsection

@section('stylesheets')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/simditor.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/answersDetail.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/vcommon.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/simditor-mention.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/selectize/selectize.default.css') }}"/>
@endsection
@section('content')
    <!--内容开始-->
    <!--问答详情开始-->
    <!--第一部分-->
    <div>


        <div class="search-wrap">
            <form action="/search" class="search-det">
                <p class="fl search-inner"><input type="search" name="q" id="search" placeholder="提问前请先搜索，看看你的问题其他用户是否已经问过..." /></p>
                <button></button>
                <div class="dispaly">
                    <ul>
                        <li>
                            <a href="javascript:void(0)">如何做一份好的角色设计？</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">如何做一份好的角色设计？</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">如何做一份好的角色设计？</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">如何做一份好的角色设计？</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">如何做一份好的角色设计？</a>
                        </li>
                    </ul>
                    <div>
                        <a href="javascript:void(0)">点击加载更多</a>
                    </div>
                </div>
                <input class="_token" type="text" />
            </form>
            <p>共
                <a href="javascript:void(0)"> {{$askdetail['questioncount']}} </a> 问题
                <a href="javascript:void(0)"> {{$askdetail['usercount']}} </a>人参与</p>
        </div>
        <div class="questions-header">
            {{--<div class="questions-title clearfix">--}}
                {{--<a class="fl" href="/questions">工作网问答</a>--}}
                {{--<span class="fl">&nbsp;>&nbsp;{{$askdetail['subject']}}</span>--}}
            {{--</div>--}}

            <!-- 标题 -->
            <div class="question-text">
                <span class="rendered-qtext">
                    <strong>{{$askdetail['subject']}}</strong>
                </span>


            </div>
            <!-- 问题内容-->
            <div class="question-detail" >
                {!! $askdetail['askdetail'] !!}
            </div>
            <!-- 问题标签 -->
            <div class="clearfix">
                <div class="question-tags fl">
                    @foreach ($askdetail['tags'] as $tags)
                        <a href="{{url('questions/tagged/'.$tags->name)}}">{{$tags->name}}</a>
                    @endforeach

                </div>
                <span class="question-cz fr">
                    @if ($askdetail['askuserstatus'] === 1)
                        <a href="/questions/update/{{$askdetail['askid']}}">编辑</a>
                        <a href="javascript:void(0)" onclick="askdel({{$askdetail['askid']}})">删除</a>
                    @endif
                </span>

            </div>

            <div class="question-info">
                <div class="question-userimg">
                    <a href="javascript:void(0)"><img class="rich-avatar" data-card-url="/users/card/{{ $askdetail['askuid'] }}" src="{{ empty($askdetail['avatar'])?url('/images/head30X30.png') :url('/avatars/60/'.$askdetail['avatar']) }}" /></a>
                </div>
                <div class="question-operation">
                        <span class="question-username fl">
                            <a class="rich-avatar" data-card-url="/users/card/{{ $askdetail['askuid'] }}" data-text="{{$askdetail['askuid']}}" href="/profile/{{$askdetail['askuid']}}">{{$askdetail['username']}}</a>
                        </span>
                    <span class="question-time  fl">
                            {{$askdetail['askcreated_at']}}
                    </span>
                    <div class="fr other-by">
                        <a href="javascript:void(0)" class="inviteAt">
                            <span class="question-invitations hover">
                                邀请回答
                            </span>
                        </a>
                        <span>&nbsp;·&nbsp;{{$askdetail['viewed']}}阅读&nbsp;·&nbsp;</span>

                        <span id="staredNumber">{{$askdetail['stared']}}人关注</span>
                        @if ($askdetail['staredstatus'] === 1)
                            <button type="button" class="btn btn-attion"  id="stared">关注</button>
                        @else
                            <button type="button" class="btn-attion btn-attion-cancel"  id="stared">取消关注</button>
                        @endif
                        <button type="button" class="btn-answer onClick" onclick="isLogin('click_scroll()',2)">回答</button>
                    </div>
                </div>
            </div>
            <div class="answers-num">
                <strong><span id="answerednum">{{$askdetail['answered']}}个回答</span></strong>
        </div>
        </div>
        @if($askdetail['answered']<1 && $userarr['uid'] == "")
            <div>
        @else
             <div>
        @endif
            <ul  id = 'answerslist'>
                {{ csrf_field() }}
                <input type="hidden" class="_token" value="1" id="answereid">
                <input type="hidden" class="_token" value="{{$askdetail['askid']}}" id="askid">
                <input type="hidden" class="_token" value="{{$askdetail['askuid']}}" id="askuid">
                <input type="hidden"  class="_token" id="answerpage" value="1">
                <input type="hidden"  class="_token" id="userpage" value="1">
            </ul>
        </div>
        <div id="btm" class="fl" style="height:auto;">
            <div id="">
                {{--<p id="jiazai">加载中...</p>--}}
            </div>
            @if($userarr['uid'] == "" )
                <div class="loginTips">要回答问题请先&nbsp;&nbsp;<a href="javascript:;" onclick="isLogin('/questions/{{$askdetail['askid']}}',1)">登录</a>&nbsp;或&nbsp;<a href="{{url('/registermobile')}}">注册</a></div>
            @endif
            @if($askdetail['askuserstatus'] == 1)
                <div class="btmTips">不能回答自己发布的问题, 你可以修改问题内容</div>
            @elseif($askdetail['answeruserstatus'] == 1)
                <div class="btmTips">一个问题只能回答一次，但是你可以编辑自己回答的内容。</div>
            @endif


            @if($userarr['uid'] == "" || $askdetail['askuserstatus'] == 1 || $askdetail['answeruserstatus'] == 1)
                <div style="display: none" id="discuss_11">
                    @else
                        <div id="discuss_11">

                            @endif
                            <ul>
                                <li class="clearfix">
                                    <div>
                                        <a href="javascript:void(0)" ><img id="userimg" src="{{ empty($userarr['avatar'])?url('/images/head60X60.png') :url('/avatars/60/'.$userarr['avatar']) }}" /></a>
                                    </div>
                                    <div>
                                        <div><a href="javascript:void(0)">{{$userarr['display_name']}}</a></div>
                                        <div>
                                            <textarea id="editor" placeholder="请输入..." autofocus></textarea>
                                        </div>
                                        <div>
                                            <a href="javascript:void(0)"><span></span></a>
                                            <a href="javascript:void(0)" id="tijiao">提交回答</a>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                </div>
        </div>
        <!--第二部分-->
        <div class="questions-side">
            <div class="side-ask">
                <a href="javascript:void(0)" onclick="isLogin('/questions/ask',1)">我要提问</a>
            </div>
            <div class="side-img">
                <a href="http://campus.jd.com/web/static/forward?to=video_list&t=8" target="_blank"><img src="{{ asset('images/advertisement/jdstory.png') }}" /></a>
            </div>

            <div class="side-questions">
                <strong>相关问题</strong>
                <ul>
                    @foreach ($askdetail['relatedask'] as $tags)
                        <li>
                            <a href="/questions/{{$tags->id}}">
                                {{$tags->subject}}
                                <span>{{$tags->answered}}个回答</span>
                            </a>
                            {{--<a href="/questions/{{$tags->id}}">--}}

                            {{--</a>--}}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <!--问答详情结束-->
    @endsection

    @section('askdateil')
        <!--脚部结束-->
            <!--名片展示开始-->
            <div id="callingCard" class="dispaly">

            </div>
            <!--名片展示结束-->
            <!--私信遮罩层开始-->
            <div id="maskLayer" class="dispaly">

            </div>
            <!--私信遮罩层结束-->
            <!--提问遮罩层开始-->
            <div id="maskLayer_1" class="dispaly">

            </div>
            <!--提问遮罩层结束-->
            <script>
                window._bd_share_config = {
                    "common": {
                        "bdSnsKey": {},
                        "bdText": "",
                        "bdMini": "1",
                        "bdMiniList": [],
                        "bdPic": "",
                        "bdStyle": "0",
                        "bdSize": "16"
                    },
                    "share": {}
                };
                with(document) 0[(getElementsByTagName('head')[0] || body).appendChild(createElement('script')).src = 'http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion=' + ~(-new Date() / 36e5)];
            </script>
            <!--百度分享结束-->
            <!--邀请好友回答开始-->
            <div id="invite" class="dispaly">
                <form action="">
                    <div class="invite-hd clearfix">
                        <div class="fl invite-search">
                            <input class="fl" type="search" name="" id="search-users" value="" placeholder="搜索你要邀请的用户" />
                            <a href="javascript:;" class="btn fr"></a>
                        </div>
                        <div class="fl invite-person">
                            <dl class="fl">
                                <dt class="fl"><span class="fl">已邀请：</span></dt>
                                @foreach($askdetail['invited'] as $val)
                                    <dt class="fl"><a href="/profile/{{$val->id}}"><img src="{{url('/avatars/60/'.$val->avatar)}}" alt="head.jpg"></a></dt>
                                @endforeach
                            </dl>
                        </div>

                    </div>
                    <ul id="userinvitations">
                        {{--已请类 onclick--}}

                    </ul>
                    <div class="invite-more">
                        <a href="javascript:void(0)" onclick="userjiazai()">邀请更多回答</a>
                    </div>
                    <input class="_token" type="text" />
                </form>
                <span></span>
            </div>
            <!--邀请好友回答结束-->
            <div id="bdopacit"></div>
        @endsection
        @section('javascripts')
                <script src="{{ asset('js/typeahead.bundle.js')}}"></script>
                <script src="{{ asset('js/handlebars.js')}}"></script>
                <script src="{{ asset('js/module.js') }}"></script>
                <script src="{{ asset('js/hotkeys.js') }}"></script>
                <script src="{{ asset('js/uploader.js') }}"></script>
                <script src="{{ asset('js/simditor.js') }}"></script>
                <script src="{{ asset('js/simditor-mention.js') }}"></script>
                <script src="{{ asset('js/askdetail.js') }}"></script>
                <script src="{{ asset('js/search.js') }}"></script>
                <script src="{{ asset('js/selectize.js') }}"></script>
                <script src="{{ asset('js/shared/util.js') }}"></script>
                <script src="{{ asset('js/view/hover_menu.js') }}"></script>
                <script src="{{ asset('js/card.js') }}"></script>
                <script>

                    $(document).on('mouseenter', '.rich-avatar', function (event) {
                        $(this).hoverCard({
                            cardUrl: 'card-url',
                            hiddenClass: 'hidden',
                            cardOffset: 11
                        });
                    });
                </script>
        @endsection
