@extends('layouts.layout')

@section('head')
    <title>我的粉丝-工作网</title>
@endsection

@section('stylesheets')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/fans.css') }}"/>
@endsection
@section('content')
    <form action="">
        <!--我的粉丝开始-->
        <div>
            <div>
                <p><span>
                        @if($loginId && $userInfo && $loginId === $userInfo->id)
                            我
                        @else
                            {{ $userInfo->display_name }}
                        @endif
                    </span>的粉丝</p>
            </div>
            @if($fansInfo)
                <div class="link-others-content clearfix">
                    @foreach($fansInfo as $fan)
                        <dl class="fl" id="attention{{$fan->id}}">
                            <dt class="fl">
                                <a href="{{url('profile/'.$fan->id)}}">
                                    @if(!empty($fan->avatar))
                                        <img src="{{url('/avatars/60/'.$fan->avatar)}}"/>
                                    @else
                                        <img src="{{url('/avatars/60/head.png')}}"/>
                                    @endif
                                </a>
                            </dt>
                            <dd class="fl">
                                <h3>{{$fan->display_name}}</h3>
                                <div class="position clearfix">
                                    <div class="compony fl">{{$fan->firstDescribe or ''}}</div>
                                    <div class="job fl">{{$fan->secondDescribe or ''}}</div>
                                </div>
                                <div class="numbs clearfix">
                                    <div class="numbs-li fl">
                                        <p><b id="fansNums{{$fan->id}}">{{$fan->follower}}</b></p>
                                        <p>粉丝</p>
                                    </div>
                                    <div class="numbs-li fl">
                                        <p><b>{{$fan->reputation}}</b></p>
                                        <p>点赞</p>
                                    </div>
                                    <div class="numbs-li fl">
                                        <p><b>{{$fan->question}}</b></p>
                                        <p>提问</p>
                                    </div>
                                    <div class="numbs-li fl">
                                        <p><b>{{$fan->answer}}</b></p>
                                        <p>回答</p>
                                    </div>
                                </div>
                                <div class="btns">
                                    @if($loginId)
                                        @if($loginId != $fan->id)
                                            @if($fan->isAttention == 1)
                                                <a class="btn btn-attion-cancel attention{{$fan->id}}" href="javascript:void(0)" onclick="isLogin('deleteAttention({{$fan->id}})',2)">取消关注</a>

                                            @else
                                                <a class="btn btn-attion attention{{$fan->id}}" href="javascript:void(0)" onclick="isLogin('addAttention({{$fan->id}})',2)">加关注</a>
                                            @endif
                                        @endif
                                    @else
                                        <a class="btn btn-attion attention{{$fan->id}}" href="javascript:void(0)" onclick="isLogin('addAttention({{$fan->id}})',2)">加关注</a>
                                    @endif
                                </div>
                            </dd>
                        </dl>
                    @endforeach
                </div>
            @else
                <div class="link-none-data"></div>
            @endif
        <!--我的粉丝结束-->
            {{ csrf_field() }}
        </div>
    </form>
    <a href="javascript:;" id="btn-back-top"></a>
@endsection

@section('javascripts')
    <script src="{{ asset('js/personalCenter.js') }}" type="text/javascript" charset="utf-8"></script>
@endsection
