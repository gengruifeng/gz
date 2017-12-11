<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		@yield('head')
		<meta name="user-login" content="{{ '' === $userarr['display_name'] ? '' : $userarr['display_name'] }}">
		<link rel="stylesheet" href="{{ asset('css/common.css') }}"/>
		<link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}"/>
		<link rel="stylesheet" href="{{ asset('css/headercommon.css') }}"/>
		<link rel="stylesheet" href="{{ asset('css/normalize.css') }}"/>

        @yield('stylesheets')
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
						@if($userarr['uid'])
							<a id="release" href="javascript:void(0)"><span></span><span>发布</span><span></span>
							</a>
							<ul id="releaseUl" class="dispaly">
								<li><a href="{{url('questions/ask')}}">发布问题</a></li>
								<li><a href="{{url('articles/compose')}}">发布文章</a></li>
							</ul>
						@endif
					</div>
					<input type="hidden"  value={{$userarr['uid']}} id='checklogin'/>
					@if(!$userarr['uid'])
						<div class="login">
							<a id="clickLogin" href="{{ url('/login') }}">登录</a>
							<a id="clickRegister" href="{{ url('/registermobile') }}">注册</a>
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
								<img id="logindlyc" src="{{ empty($userarr['avatar'])?url('/images/head30X30.png') :url('/avatars/30/'.$userarr['avatar']) }}"/><i></i>

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
									<a href="{{ url('messages') }}">私信&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
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
            @yield('content')
        </section>
		<!--发送成功开始-->

		<div id="dialog">
			<p>你确定删除吗？</p>
		</div>
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
		<!-- 提示信息 结束-->
		<div id="dialog_3" class="display">
			<div id="confirm_3">
				<form action="">
					<p id="dialog_3_p">发送成功</p>
					<input class="_token" type="text" name="" id="" value="" />
				</form>
			</div>
		</div>
		@yield('askdateil')



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
		<script src="http://www.sobot.com/chat/pc/pc.min.js?sysNum=5dddc69a2b8b4fea9a73ce671182809d" id="zhichiload" ></script>
		<script src="{{ asset('js/jquery-2.1.0.js') }}"></script>
		<script src="{{ asset('js/jquery.form.js') }}"></script>
		<script src="{{ asset('js/jquery-ui.min.js') }}"></script>
		<script src="{{ asset('js/common.js') }}"></script>
		<script src="{{ asset('js/jquery.validate.js') }}"></script>
		@yield('javascripts')
		<!--脚步开始-->
		<footer id="footer">
			<div id="foot">
				<div class="foot_top">
					<a href="javascript:void(0)">关于工作网</a>
					<span>|</span>
					<a href="javascript:void(0)">免责声明</a>
					<span>|</span>
					<a href="javascript:void(0)">知识产权</a>
					<span>|</span>
					<a href="javascript:void(0)">合作机会</a>
					<span>|</span>
					<a href="javascript:void(0)">人才招聘</a>
					<span>|</span>
					<a href="javascript:void(0)">联系我们</a>
				</div>
				<p class="foot_btm">Copyright &copy; 2016 工作网 All rights reserved</p>
			</div>
		</footer>

	</body>
</html>
