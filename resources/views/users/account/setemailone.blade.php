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
                <!--设置邮箱>输入邮箱开始-->
                <div class="intEmail">
                    <div><h3>设置邮箱</h3></div>
                    <div>
                        <a href="javascript:void(0)">
                            <span></span>
                            <span>输入邮箱</span>
                            <span></span>
                        </a>
                        <a href="javascript:void(0)">
                            <span>2</span>
                            <span>前往邮箱验证</span>
                            <span></span>
                        </a>
                        <a href="javascript:void(0)">
                            <span>3</span>
                            <span>完成绑定</span>
                        </a>
                    </div>
                    <div>
                        <form id = "setMailForm" action="{{ url('ajax/sendmail') }}" onsubmit="return false" autocomplete="off">
                            <div>
                                <input type="text" name="mail" placeholder="请输入邮箱" />
                            </div>
                            <div>
                                <a onclick="setEmail()" href="javascript:void(0)">发送邮件验证</a>
                            </div>
                            {{ csrf_field() }}
                        </form>
                    </div>
                </div>
                <!--设置邮箱>输入邮箱结束-->
            </div>
        </div>
    </div>
    <!--个人信息结束-->
@endsection
<script src="{{ asset('js/safety.js') }}"></script>

