@extends('layouts.layout')

@section('head')
    <title>绑定设置-工作网</title>
@endsection

@section('stylesheets')
<link rel="stylesheet" type="text/css" href="{{asset('css/personnel.css')}}"/>
@endsection

@section('content')
<!--内容开始-->
    <!--个人信息开始-->
    <div id="message">
        <div class="message">
            <div class="message_left">
                <a href="{{ url('account/settings') }}"><span></span><span>基本资料</span></a>
                <a href="{{ url('account/avatar') }}"><span></span><span>我的头像</span></a>
                <a class="onClick" href="javascript:void(0)"><span></span><span>绑定设置</span></a>
                <a href="{{ url('account/safety') }}"><span></span><span>账户安全</span></a>
                <a href="{{ url('account/proficiency') }}"><span></span><span>擅长领域</span></a>
            </div>
            <input type="hidden" value={{ $name['uid'] }} id='hidden' >
            {{ csrf_field() }}
            <div class="message_right">
                <!--绑定设置开始-->
                <div class="settings">
                    <div class="bind-setting">
                        <h3>绑定设置</h3>
                        <p>好东西就要与朋友们分享</p>
                    </div>
                    <div class="bind-info">
                        <div class="third-binding">
                            <div class="third-sina">
                                <div class="third-img"></div>
                                <div class="third-button">
                                    
                                        @if ($name['sina'] === "")
                                        <p>我的新浪微博账户<span class="tips">未绑定</span></p>
                                        <a class="unactive" href={{url("auth/weibo")}}>
                                            <span>现在绑定</span>
                                        </a>
                                        @else
                                        <p>我的新浪微博账户<span>{{ $name['sina'] }}</span></p>
                                        <a href= "javascript:void(0)"id="wbjb">
                                            解绑
                                        </a>
                                        @endif
                                </div>
                            </div>
                            <div class="third-weixin">
                                <div class="third-img"></div>
                                <div class="third-button">
                                        @if ($name['weixin'] === "")
                                            <p>我的微信账户<span class="tips">未绑定</span></p>
                                            <a class="unactive" href={{url("auth/weixinweb")}}>
                                                <span>现在绑定</span>
                                            </a>
                                        @else
                                            <p>我的微信账户<span>{{ $name['weixin'] }}</span></p>
                                            <a href="javascript:void(0)" id="wxjb">
                                                解绑
                                            </a>
                                        @endif
                                </div>
                            </div>
                            <div class="third-qq">
                                <div class="third-img"></div>
                                <div class="third-button">
                                        @if ($name['qq'] === "")
                                            <p>我的QQ账户<span class="tips">未绑定</span></p>
                                            <a class="unactive" href={{url("auth/qq")}}>
                                                <span>现在绑定</span>
                                            </a>
                                        @else
                                        <p>我的QQ账户<span>{{ $name['qq'] }}</span></p>
                                            <a href="javascript:void(0)" id="qqjb">
                                                解绑
                                            </a>
                                        @endif
                                </div>
                            </div>
                        </div>
                        <div class="qrcode">
                            <div>
                                <div></div>
                                <p>加工作网为微信好友</p>
                                <p>或使用微信搜索<br />工作网微信号</p>
                                <p>gzwenba</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!--绑定设置结束-->
            </div>
        </div>
    </div>
    <!--个人信息结束-->
<!--内容结束-->
@endsection
@section('javascripts')
    <script src="{{asset('js/oauth.js')}}" type="text/javascript" charset="utf-8"></script>
@endsection
