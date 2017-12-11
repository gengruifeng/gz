@extends('layouts.layout')

@section('head')
	<title>更换号码-工作网</title>
@endsection

@section('stylesheets')
<link rel="stylesheet" type="text/css" href="{{ asset('css/personnel.css') }}"/>
@endsection
@section('content')
	<!--内容开始-->
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
					<!--更换号码>身份验证开始-->
					<div class="changePhone">
						<div><h3>更换号码</h3></div>
						<div>
							<a href="javascript:void(0)">
								<span></span>
								<span>身份验证</span>
								<span></span>
							</a>
							<a href="javascript:void(0)">
								<span>2</span>
								<span>修改绑定手机</span>
								<span></span>
							</a>
							<a href="javascript:void(0)">
								<span>3</span>
								<span>完成修改</span>
							</a>
						</div>
						<div>
							<div>
								<form id = "submobileForm" action="{{ url('ajax/account/bindingmobile') }}" onsubmit="return false" autocomplete="off">
									<input type="hidden" name="mobile" value="{{ !empty($userData['mobile'])?$userData['mobile']:'' }}" />
									<input class="changePhone_input" type="text" name="code" placeholder="请输入验证码" />
									{{ csrf_field() }}
								</form>
								<form id = "mobileForm" action="{{ url('ajax/sendsms') }}" onsubmit="return false" autocomplete="off">
									<button class="changePhone_btn" onclick="grfsendSms(this,1)" id = "sendcode">获取验证码</button>
									<input type="hidden" name="mobile" value="{{ !empty($userData['mobile'])?$userData['mobile']:'' }}" />
									{{ csrf_field() }}
								</form>
							</div>
							<div id="changePhone_subbtn_p">
								<a class="changePhone_subbtn" onclick="subbindingMobile('changemobiletwo')" href="javascript:void(0)">提交</a>
							</div>
						</div>
					</div>
					<!--更换号码>身份验证结束-->
				</div>
			</div>
		</div>
		<!--个人信息结束-->
@endsection
@section('javascripts')
<script>

	window.onload = function () {
		$(function () {
			mobile ="{{ !empty($userData['mobile'])?$userData['mobile']:'' }}";
			email ="{{ !empty($userData['email'])?$userData['email']:'' }}";
			is = getCookieValue("secondsremained");
			if(is != undefined){
				document.cookie = "secondsremained" + "="+is;
			}
			settime($('#sendcode'));
		});
	}
</script>
<script src="{{ asset('js/safety.js') }}"></script>
@endsection
