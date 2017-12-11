@extends('layouts.layout')

@section('head')
    <title>私信对话-工作网</title>
@endsection

@section('stylesheets')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/inform.css') }}"/>
@endsection
@section('content')
    <!--私信对话页面开始-->
    <form class="privateMessage" action="">
        <div>
            <a href="javascript:void(0)">与{{$user->display_name}}的私信对话</a>
            <a href="{{url('messages')}}" >返回私信列表 &gt;&gt;</a>
        </div>
        <div>
            <textarea name="" id="privatemsg" placeholder="想对ta说点什么?" ></textarea>
            <a href="javascript:void(0)" onclick="sendPrivateLetter('{{$user->dialogId}}','{{$user->id}}')">发送</a>
        </div>
        <div>
            <ul>
                @foreach($contents as $content)
                    @if($content->sender == $loginId)
                        <li class="self">
                            <div>
                                <a href="{{url('profile')}}">
                                    @if(!empty($content->avatar))
                                        <img style="width: 60px;height: 60px;" src="{{ url('/avatars/60/'.$content->avatar)}}"/>
                                    @else
                                        <img style="width: 60px;height: 60px;" src="{{url('/avatars/60/head.png')}}"/>
                                    @endif
                                </a>
                            </div>
                            <div>
                            <div>
                            <p>{{$content->content}}</p>
                                <div class="border"></div>
                            </div>
                        <p>{{$content->created_at}}</p>
                    </div>
                </li>
                    @else
                        <li class="other">
                            <div>
                                <a href="{{url('profile/'.$user->id)}}">
                                    @if(!empty($content->avatar))
                                        <img style="width: 60px;height: 60px;" src="{{ url('/avatars/60/'.$content->avatar)}}"/>
                                    @else
                                        <img style="width: 60px;height: 60px;" src="{{url('/avatars/60/head.png')}}"/>
                                    @endif
                                </a>
                            </div>
                            <div>
                                <div>
                                    <p>{{$content->content}}</p>
                                    <div class="border"></div>
                                </div>
                                <p>{{$content->created_at}}</p>
                            </div>
                        </li>
                    @endif
                    @endforeach
            </ul>
        </div>
        {{ csrf_field() }}
    </form>
    <a href="javascript:;" id="btn-back-top"></a>
    <!--私信对话页面结束-->
@endsection

@section('javascripts')
    <script src="{{ asset('js/notice.js') }}" type="text/javascript" charset="utf-8"></script>
    <script src="{{ asset('js/public.js') }}" type="text/javascript" charset="utf-8"></script>
@endsection