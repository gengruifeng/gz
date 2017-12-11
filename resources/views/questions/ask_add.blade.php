<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>提出问题-工作网</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/simditor.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/inform.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/simditor-mention.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/common.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/selectize/selectize.default.css') }}"/>
</head>
<body>
<!--头部开始-->
<header id="header">
    <div id="head">
        <!--头部列表开始-->
        <div class="nav">
            <div class="logo"><a href="{{url('/')}}">&nbsp;</a></div>
            <div class="nav_list">
                <a href="{{url('/')}}">首页</a>
                <!-- <a href="javascript:void(0)">宣讲会</a> -->
                <a href="{{url('/resume/my')}}">我的简历</a>
                <a href="{{url('/questions')}}">问答</a>
                <a href="{{url('/articles')}}">文章</a>
                <a href="{{ url('/cv/templates') }}">简历模板</a>
                <!-- <a href="{{url('/articles')}}">热文</a> -->
                <a id="release" href="javascript:void(0)"><span></span><span>发布</span><span></span>
                </a>
                <ul id="releaseUl" class="dispaly">
                    <li><a href="{{url('questions/ask')}}">发布问题</a></li>
                    <li><a href="{{url('articles/compose')}}">发布文章</a></li>
                </ul>
            </div>
            <input type="hidden"  value={{$userarr['uid']}} id='checklogin'/>
            @if(!$userarr['uid'])
                <div class="login">
                    <a id="clickLogin" href="/login">登录</a>
                    <a id="clickRegister" href="/registermobile">注册</a>
                </div>
            @else
                <div class="loginStatus">
                    <a class="loginStatus_1" href="javascript:void(0)">通知
                        @if($noticeNum['totalNum']>0)
                            <span>
									{{$noticeNum['totalNum']}}
								</span>
                        @endif
                    </a>
                    <a class="loginStatus_1" href="javascript:void(0)">
                        <img src="{{ url('/avatars/30/'.$userarr['avatar']) }}"/><i></i>
                    </a>
                    <ul class="loginStatus_2 dispaly">
                        <li>
                            <a href="{{ url('notifications/answers') }}">问答消息</a>
                            @if($noticeNum['answerNum']>0)
                                <span>
										{{$noticeNum['answerNum']}}
									</span>
                            @endif
                        </li>
                        <li>
                            <a href="{{ url('notifications/articles') }}">文章消息</a>
                            @if($noticeNum['articleNum']>0)
                                <span>
										{{$noticeNum['articleNum']}}
									</span>
                            @endif
                        </li>
                        <li>
                            <a href="{{ url('messages') }}">私信</a>
                            @if($noticeNum['privateMsgNum']>0)
                                <span>
										{{$noticeNum['privateMsgNum']}}
									</span>
                            @endif
                        </li>
                        <li>
                            <a href="{{ url('notifications/others') }}">系统通知</a>
                            @if($noticeNum['systemNum']>0)
                                <span>
										{{$noticeNum['systemNum']}}
									</span>
                            @endif
                        </li>
                    </ul>
                    <ul class="loginStatus_2 dispaly">
                        <li>
                            <a href="{{url('profile')}}">个人中心</a>
                        </li>
                        <li>
                            <a href="{{url('account/settings')}}">个人设置</a>
                        </li>
                        <li>
                            <a href="{{url('/resume/list')}}">简历管理</a>
                        </li>
                        <li>
                            <a href="{{url('profile/articles')}}">我的文章</a>
                        </li>
                        <li>
                            <a href="{{url('profile/follower')}}">我的粉丝</a>
                        </li>
                        <li>
                            <a href="{{url('profile/following')}}">我的关注</a>
                        </li>
                        <li>
                            <a href="{{url('logout')}}">退出登录</a>
                        </li>
                    </ul>
                </div>
            @endif
        </div>
        <!--头部列表结束-->
    </div>
</header>
<!--头部结束-->
<!--内容开始-->
<section id="section">
    <!--头部结束-->
    <!--内容开始-->
    <!--发布问题开始-->
    <form class="release" action="">
        <div>
            <input type="text" placeholder="标题 : 一句话描述问题，用问号结尾" id="subject" onkeyup="checkMaxInput(this,'inputnumber',50)" onkeydown="checkMaxInput(this,'inputnumber',50)"/>
            <p>还可以输入
                <a href="javascript:void(0)" id="inputnumber">50</a>字</p>
        </div>
        <div>
            <div>
                {{ csrf_field() }}
                <textarea id="editor" placeholder="请输入..."   ></textarea>
            </div>
            <div>
                <p>
                <a href="javascript:void(0)" id = 'plinputnumber'></a></p>
            </div>
        </div>
        <div>
            <div>
                {{--添加后--}}
                <div style="display: none"></div>

                {{--内容--}}
                <div class="clearfix demo">
                    <input type="text" id="search_tag" placeholder="添加标签（回车键创建新标签）">
                </div>
                <div>
                    <button type="button" id="askadd" >发布</button>
                </div>
            </div>

        </div>
        <input class="_token" type="hidden" value="{{$uid}}" id="is_help" />
        <input class="_token" type="text" />
    </form>
</section>

<!-- 提示信息 开始-->
<div class="dialogcom dialogcom_yes hide">
    <form action="">
        <span>提问成功</span>
        <input class="_token" type="hidden" name="" id="" value="">
    </form>
</div>
<div class="dialogcom dialogcom_warn hide ">
    <form action="">
        <span>操作过于频繁，请明天再来</span>
        <input class="_token" type="hidden" name="" id="" value="">
    </form>
</div>
<div class="dialogcom dialogcom_wrong hide">
    <form action="">
        <span>操作过于频繁，请明天再来</span>
        <input class="_token" type="hidden" name="" id="" value="">
    </form>
</div>

<!--提示层 开始-->
<div class="shadows-tips shadows-question hide">
    <div class="inner">
        <a href="javascript:;" class="btn-close"></a>
        <a href="javascript:;" class="btn-not"></a>
        <div class="position-a1 position-a"></div>
        <div class="position-a2 position-a"></div>
        <div class="position-a3 position-a"></div>
        <div class="position-a4 position-a"></div>
    </div>
</div>

<div id="dialog-login" class="vlogin loginReset hide">
    <a href="javascript:;" class="btnclose"></a>
    <div class="inner">
        <a href="{{url('/')}}"><h1></h1></a>
        <!-- <h3>密码设置成功，请重新登录</h3> -->
        <form id="dialog-login-from" method="post" action="{{ url('/ajax/login') }}" autocomplete="off">
            <!-- 信息相关 开始 -->
            <div class="tp">
                <div class="testwap">
                    <div class="inp inpUser">
                        <input type="text" id="auth_name" name="auth_name" placeholder="您的邮箱/手机号" >
                    </div>
                </div>
                <div class="testwap">
                    <div class="inp inpPassword">
                        <input type="password" id="passcode" name="passcode" placeholder="您的密码">
                    </div>
                </div>
                <div class="clearfix passwordRm">
                    <div class="fl checkbox">
                        <p class="fl"><input name="remember_me" value="1" type="checkbox" id="checkbox"><span><b></b></span></p><label class="fl" for="checkbox">记住我</label>
                    </div>
                    <a href="{{ url('forgot/mobile') }}" target="_blank" class="fr">忘记密码？</a>
                </div>
                {{ csrf_field() }}
                <input type="submit" value="登录">
                <p class="tips">还没有账号？ <a href="{{ url('registermobile') }}" target="_blank">立即注册&nbsp;<span>>></span></a></p>
            </div>
            <!-- 信息相关 结束 -->

            <!-- 第三方账号登录 开始 -->
            <div class="btm">
                <p><span>第三方账号登录</span></p>
                <ul class="clearfix">
                    <li class="fl"><a href="{{ url("auth/weixinweb") }}" target="_blank"></a></li>
                    <li class="fl"><a href="{{ url("auth/qq") }}" target="_blank"></a></li>
                    <li class="fl"><a href="{{ url("auth/weibo") }}" target="_blank"></a></li>
                </ul>
            </div>
            <!-- 第三方账号登录 结束 -->
        </form>
    </div>
</div>
<!--内容结束-->
<script src="{{ asset('js/jquery-2.1.0.js') }}"></script>
<script src="{{ asset('js/jquery.form.js') }}"></script>
<script src="{{ asset('js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('js/common.js') }}"></script>
<script src="{{ asset('js/module.js') }}"></script>
<script src="{{ asset('js/hotkeys.js') }}"></script>
<script src="{{ asset('js/uploader.js') }}"></script>
<script src="{{ asset('js/simditor.js') }}"></script>
<script src="{{ asset('js/simditor-mention.js') }}"></script>
<script src="{{ asset('js/askadd.js') }}"></script>
<script src="{{ asset('js/selectize/selectize.js') }}"></script>
<script src="{{ asset('js/jquery.validate.js') }}"></script>
</body>
</html>
