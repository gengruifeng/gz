@extends('layouts.layout')

@section('head')
    <title>设置新密码-工作网</title>
@endsection

@section('content')
	<!--内容开始-->
    <div id="login">
        <!--手机设置密码开始-->
        <div class="setNewPwd">
            <form id="form" action="{{ url('ajax/mobilepass') }}" method="post" onsubmit="return false"  autocomplete="off">
                <div><h3>设置新密码</h3></div>
                <div>
                    <p>短信验证码已下发，如果未收到，请<a href="{{ asset('forgot/mobile') }}">点击重新发送</a></p>
                </div>
                <div>
                    <input type="password" name="password"  placeholder="新密码" />
                </div>
                <div>
                    <input type="password" name="password_confirmation"  placeholder="确认密码" />
                </div>
                <div>
                    <input name="code" type="text" placeholder="短信验证码" />
                </div>
                <div>
                    <button onclick="setMobilePass()">完成</button>
                </div>
                <input type="hidden" name="mobile" value="{{ $mobile }}">
                {{ csrf_field() }}
            </form>

        </div>
        <!--手机设置密码结束-->
    </div>
@endsection
<script src="{{ asset('js/forgot.js') }}" type="text/javascript" charset="utf-8"></script>