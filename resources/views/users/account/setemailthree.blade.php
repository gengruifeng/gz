@extends('layouts.layout')

@section('head')
    <title>设置邮箱-工作网</title>
@endsection

@section('stylesheets')
<link rel="stylesheet" type="text/css" href="{{ asset('css/personnel.css') }}"/>
@endsection
@section('content')
    <!--个人信息开始-->
    <div id="message">
        <div class="message">
            <div class="message_left">
                <a href="{{ url('account/settings') }}"><span></span><span>基本资料</span></a>
                <a href="{{ url('account/avatar') }}"><span></span><span>我的头像</span></a>
                <a href="{{ url('account/oauth') }}"><span></span><span>绑定设置</span></a>
                <a class="onClick" href="{{ url('account/safety') }}"><span></span><span>账户安全</span></a>
                <a href="{{ url('account/proficiency') }}"><span></span><span>擅长领域</span></a>
            </div>
            <div class="message_right">
                <!--设置邮箱>完成绑定开始-->
                <div class="setEmailFinish">
                    <div><h3>设置邮箱</h3></div>
                    <div>
                        <a href="javascript:void(0)">
                            <span></span>
                            <span>输入邮箱</span>
                            <span></span>
                        </a>
                        <a href="javascript:void(0)">
                            <span></span>
                            <span>前往邮箱验证</span>
                            <span></span>
                        </a>
                        <a href="javascript:void(0)">
                            <span></span>
                            <span>完成绑定</span>
                        </a>
                    </div>
                    <div>
                        @if(!empty($returnData['msg']))
                           <span class="live">{{ $returnData['msg'] }}</span>
                        @else
                            <p>完成绑定邮箱！</p>
                        @endif
                        @if(!empty($returnData['email'])&& empty($returnData['msg']))
                            <p>绑定的邮箱为 : <span>{{ $returnData['email'] }}</span></p>
                            <p>今后你可以使用该邮箱进行登录</p>
                        @endif
                    </div>
                </div>
                <!--设置邮箱>完成绑定结束-->
            </div>
        </div>
    </div>
    <!--个人信息结束-->
@endsection
