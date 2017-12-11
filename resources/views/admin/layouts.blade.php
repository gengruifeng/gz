
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta charset="utf-8" />
	<title>工作网 - Admin</title>

	<meta name="description" content="" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

	<!-- bootstrap & fontawesome -->
	<link rel="stylesheet" href="{{ asset('admin/assets/css/bootstrap.min.css') }}" />
	<link rel="stylesheet" href="{{ asset('admin/assets/css/font-awesome.min.css') }}" />

	<!-- page specific plugin styles -->

	<!-- text fonts -->
	<link rel="stylesheet" href="{{ asset('admin/assets/css/ace-fonts.css') }}" />

	<!-- ace styles -->
	<link rel="stylesheet" href="{{ asset('admin/assets/css/ace.min.css') }}" />

	<!--[if lte IE 9]>
	<link rel="stylesheet" href="{{ asset('admin/assets/css/ace-part2.min.css') }}" />
	<![endif]-->
	<link rel="stylesheet" href="{{ asset('admin/assets/css/ace-skins.min.css') }}" />
	<link rel="stylesheet" href="{{ asset('admin/assets/css/ace-rtl.min.css') }}" />

	<!--[if lte IE 9]>
	<link rel="stylesheet" href="{{ asset('admin/assets/css/ace-ie.min.css') }}" />

	<![endif]-->


	<!-- inline scripts related to this page -->
	<link rel="stylesheet" href="{{ asset('admin/assets/css/ace.onpage-help.css') }}" />
	<link rel="stylesheet" href="{{ asset('admin/docs/assets/js/themes/sunburst.css') }}" />
	<link rel="stylesheet" href="{{ asset('admin/assets/css/jquery.gritter.css') }}" />
	<link rel="stylesheet" href="{{ asset('admin/css/common.css') }}" />
	@yield('stylesheets')


</head>
<body style="overflow-x: hidden" class="no-skin">


<!-- 顶栏导航 -->
<div id="navbar" class="navbar navbar-default">
	<div class="navbar-container" id="navbar-container">
		<!-- #section:basics/sidebar.mobile.toggle -->
		<button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler">
			<span class="sr-only">Toggle sidebar</span>

			<span class="icon-bar"></span>

			<span class="icon-bar"></span>

			<span class="icon-bar"></span>
		</button>

		<!-- /section:basics/sidebar.mobile.toggle -->
		<div class="navbar-header pull-left">
			<!-- #section:basics/navbar.layout.brand -->
			<a href="#" class="navbar-brand">
				<small>
					<i class="fa fa-leaf"></i>
					工作网 Admin
				</small>
			</a>

			<!-- /section:basics/navbar.layout.brand -->

			<!-- #section:basics/navbar.toggle -->

			<!-- /section:basics/navbar.toggle -->
		</div>

		<!-- #section:basics/navbar.dropdown -->
		<div class="navbar-buttons navbar-header pull-right" role="navigation">
			<ul class="nav ace-nav">
				<!-- #section:basics/navbar.user_menu -->
				<li class="light-blue">
					<a data-toggle="dropdown" href="#" class="dropdown-toggle">
						<img class="nav-user-photo" src="{{ !empty($userarr['avatar']) ? url('/avatars/30/'.$userarr['avatar']):url('/avatars/30/head.png' )}}" alt="Jason's Photo" />
                                <span class="user-info">
                                    <small>Welcome</small>
										{{ $userarr['display_name'] }}
                                </span>

						<i class="ace-icon fa fa-caret-down"></i>
					</a>

					<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
						{{--<li>--}}
							{{--<a href="#">--}}
								{{--<i class="ace-icon fa fa-cog"></i>--}}
								{{--Settings--}}
							{{--</a>--}}
						{{--</li>--}}

						{{--<li>--}}
							{{--<a href="profile.html">--}}
								{{--<i class="ace-icon fa fa-user"></i>--}}
								{{--Profile--}}
							{{--</a>--}}
						{{--</li>--}}

						{{--<li class="divider"></li>--}}

						<li>
							<a href="{{ url('logout') }}">
								<i class="ace-icon fa fa-power-off"></i>
								Logout
							</a>
						</li>
					</ul>
				</li>

				<!-- /section:basics/navbar.user_menu -->
			</ul>
		</div>

		<!-- /section:basics/navbar.dropdown -->
	</div><!-- /.navbar-container -->
</div>
<!-- /顶栏导航 -->

<!--主体 -->
<div class="main-container" id="main-container">

	<!-- 左侧导航 -->
	@include('admin.sidebar')
	<!-- /左侧导航 -->

	<!--主体网页 -->
	<div class="main-content">

		<!-- 面包线 -->
		@if(!empty($currentData['m']) && !empty($currentData['g']) && !empty($currentData['l']))
		<div class="breadcrumbs" id="breadcrumbs">
			<ul class="breadcrumb">
				<li>
					<i class="ace-icon fa fa-home home-icon"></i>
					<a href="javascript:void(0)">{{ !empty($currentData['m'])?$currentData['m']:'' }}</a>
				</li>

				<li>
					<a href="javascript:void(0)">{{ !empty($currentData['g'])?$currentData['g']:'' }}</a>
				</li>
				<li class="active">{{ !empty($currentData['l'])?$currentData['l']:'' }}</li>
			</ul><!-- /.breadcrumb -->

			<!-- /section:basics/content.searchbox -->
		</div>
		@endif
		<div class="page-content">
			<!-- /section:settings.box -->
			<div class="row">
				<div class="col-xs-12">
					<!-- PAGE CONTENT BEGINS -->

					<!-- #section:pages/error -->
					<div class="error-container">
						<div style="position: fixed;top: 50%;left: 50%;z-index: 1000;" id="firstDiv">
						</div>
						@yield('content')

					</div>

					<!-- /section:pages/error -->

					<!-- PAGE CONTENT ENDS -->
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div>
		<!-- /面包线 -->
	</div>
	<!--/主体网页 -->

	<!-- 底部 -->
	<div class="footer">
		<div class="footer-inner">
			<!-- #section:basics/footer -->
			<div class="footer-content">
						<span class="bigger-120">
							<span class="blue bolder">工作网</span>
							Application &copy; 2016-2017
						</span>

				&nbsp; &nbsp;
						<span class="action-buttons">
							<a href="#">
								<i class="ace-icon fa fa-twitter-square light-blue bigger-150"></i>
							</a>

							<a href="#">
								<i class="ace-icon fa fa-facebook-square text-primary bigger-150"></i>
							</a>

							<a href="#">
								<i class="ace-icon fa fa-rss-square orange bigger-150"></i>
							</a>
						</span>
			</div>

			<!-- /section:basics/footer -->
		</div>
	</div>
	<!-- /底部 -->
	<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
		<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
	</a>
</div><!-- /.main-container -->

<!-- 主体 -->
<script src="{{ asset('js/jquery-1.10.2.min.js') }}"></script>

<script type="text/javascript">
	if('ontouchstart' in document.documentElement) document.write("<script src='admin/assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
</script>


<script src="{{ asset('admin/assets/js/bootstrap.min.js') }}"></script>

<!-- page specific plugin scripts -->

<!-- ace scripts -->
{{--<script src="{{ asset('admin/assets/js/ace-elements.min.js') }}"></script>--}}
<script src="{{ asset('admin/assets/js/ace.min.js') }}"></script>

<!-- inline styles related to this page -->

<!-- ace settings handler -->
<script src="{{ asset('admin/assets/js/ace-extra.min.js') }}"></script>

<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

<!--[if lte IE 8]>
<script src="{{ asset('admin/assets/js/html5shiv.js') }}"></script>
<script src="{{ asset('admin/assets/js/respond.min.js') }}"></script>

<![endif]-->


<script src="{{ asset('admin/assets/js/ace/ace.onpage-help.js') }}"></script>
<script src="{{ asset('admin/docs/assets/js/rainbow.js') }}"></script>
<script src="{{ asset('admin/assets/js/jquery.gritter.min.js') }}"></script>
<script src="{{ asset('admin/assets/js/spin.min.js') }}"></script>
<script src="{{ asset('admin/js/common.js') }}"></script>
@yield('javascripts')

</body>
</html>
