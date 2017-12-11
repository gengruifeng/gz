<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		@yield('head')
		{{--<meta name="user-login" content="{{ '' === $userarr['display_name'] ? '' : $userarr['display_name'] }}">--}}
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
					<div class="logo"></div>
				</div>
				<!--头部列表结束-->
			</div>
		</header>
		<!--头部结束-->
		<!--内容开始-->
		<section id="section">
            @yield('content')
        </section>
		<!--脚步开始-->
		<footer id="footer">
			<div id="foot">

				<p class="foot_btm">Copyright &copy; 2016 工作网 All rights reserved</p>
			</div>
		</footer>

	</body>
</html>
