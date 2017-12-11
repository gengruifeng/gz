@extends('layouts.layout')

@section('head')
    <title>我的关注-工作网</title>
@endsection

@section('stylesheets')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/fans.css') }}"/>
@endsection
@section('content')
    <form action="">
        <!--我的粉丝开始-->

        <div class="link-others">
            <div class="link-others-title">
                <p><span>
                        @if($loginId && $userInfo && $loginId === $userInfo->id)
                            我
                        @else
                            {{ $userInfo->display_name }}
                        @endif
                    </span>关注的人</p>
            </div>
            @if($followingInfo)
                <div class="link-others-content clearfix">
                    @foreach($followingInfo as $following)
                        <dl class="fl" id="attention{{$following->id}}">
                                <dt class="fl">
                                    <a href="{{url('profile/'.$following->id)}}">
                                        @if(!empty($following->avatar))
                                            <img src="{{url('/avatars/60/'.$following->avatar)}}"/>
                                        @else
                                            <img src="{{url('/avatars/60/head.png')}}"/>
                                        @endif
                                    </a>
                                </dt>
                                <dd class="fl">
                                    <h3>{{$following->display_name}}</h3>
                                    <div class="position clearfix">
                                        <div class="compony fl">{{$following->firstDescribe or ''}}</div>
                                        <div class="job fl">{{$following->secondDescribe or ''}}</div>
                                    </div>
                                    <div class="numbs clearfix">
                                        <div class="numbs-li fl">
                                            <p><b id="fansNums{{$following->id}}">{{$following->follower}}</b></p>
                                            <p>粉丝</p>
                                        </div>
                                        <div class="numbs-li fl">
                                            <p><b>{{$following->reputation}}</b></p>
                                            <p>点赞</p>
                                        </div>
                                        <div class="numbs-li fl">
                                            <p><b>{{$following->question}}</b></p>
                                            <p>提问</p>
                                        </div>
                                        <div class="numbs-li fl">
                                            <p><b>{{$following->answer}}</b></p>
                                            <p>回答</p>
                                        </div>
                                    </div>
                                    <div class="btns">
                                        @if($loginId)
                                            @if($loginId != $following->id)
                                                @if($following->isAttention == 1)
                                                    @if(!empty($flag) && $flag == 1)
                                        <a href="javascript:;" class=" btn btn-attion-cancel  attention{{$following->id}}" onclick="isLogin('delOtherAttention({{$following->id}})',2)">取消关注</a>
                                                    @else
                                                        <a class="btn btn-attion-cancel attention{{$following->id}}" href="javascript:void(0)" onclick="isLogin('delAttention({{$following->id}})',2)">取消关注</a>
                                                    @endif
                                                @else
                                                    <a class="btn btn-attion attention{{$following->id}}" href="javascript:void(0)" onclick="isLogin('addAttention({{$following->id}})',2)">加关注</a>
                                                @endif
                                            @endif
                                        @else
                                            <a class="btn btn-attion attention{{$following->id}}" href="javascript:void(0)" onclick="isLogin('addAttention({{$following->id}})',2)">加关注</a>
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
