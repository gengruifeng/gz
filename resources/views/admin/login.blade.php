<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta charset="utf-8" />
	<title>Login Page - Ace Admin</title>

	<meta name="description" content="User login page" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

	<!-- bootstrap & fontawesome -->
	<link rel="stylesheet" href="{{ asset('admin/assets/css/bootstrap.min.css') }}" />
	<link rel="stylesheet" href="{{ asset('admin/assets/css/font-awesome.min.css') }}" />

	<!-- text fonts -->
	<link rel="stylesheet" href="{{ asset('admin/assets/css/ace-fonts.css') }}" />

	<!-- ace styles -->
	<link rel="stylesheet" href="{{ asset('admin/assets/css/ace.min.css') }}" />
	<link rel="stylesheet" href="{{ asset('admin/assets/css/ace.onpage-help.css') }}" />

	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

	<!--[if lt IE 9]>
	<script src="{{ asset('admin/assets/js/html5shiv.js') }}"></script>
	<script src="{{ asset('admin/assets/js/respond.min.js') }}"></script>
	<![endif]-->
</head>
<body class="login-layout light-login">
<div class="main-container">
	<div class="main-content">
		<div class="row">
			<div class="col-sm-10 col-sm-offset-1">
				<div class="login-container">
					<div class="center">
						<h4 class="blue" id="id-company-text">&nbsp;</h4>
						<h4 class="blue" id="id-company-text">&nbsp;</h4>
						<h1>
							<i class="ace-icon fa fa-leaf green"></i>
							<span class="red">工作网</span>
							<span class="white" id="id-text2"></span>
						</h1>
					</div>
					<div class="space-6"></div>

					<div class="position-relative">
						<div id="login-box" class="login-box visible widget-box no-border">
							<div class="widget-body">
								<div class="widget-main">
									<h4 class="header blue lighter bigger">
										<i class="ace-icon fa fa-coffee green"></i>
										Welcome
									</h4>

									<div class="space-6"></div>

									<form>
										<fieldset>
											<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="text" class="form-control" placeholder="Username" />
															<i class="ace-icon fa fa-user"></i>
														</span>
											</label>

											<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="password" class="form-control" placeholder="Password" />
															<i class="ace-icon fa fa-lock"></i>
														</span>
											</label>

											<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input style="width: 130px;" type="text" class="form-control" placeholder="Codes" />
															<i style="left: -60px;" class="ace-icon fa fa-pencil-square-o "></i>
														</span>
												<img onclick="this.src='{{url('users/code')}}/'+Math.random()" alt="" src="{{url('admin/code')}}">
											</label>

											<div class="space"></div>

											<div class="clearfix">
												<a href="{{ asset('/admin/account/list') }}" type="button" class="width-35 pull-right btn btn-sm btn-primary">
													<i class="ace-icon fa fa-key"></i>
													<span class="bigger-110">Login</span>
												</a>
											</div>

											<div class="space-4"></div>
										</fieldset>
									</form>
								</div><!-- /.widget-main -->
							</div><!-- /.widget-body -->
						</div><!-- /.login-box -->
					</div><!-- /.position-relative -->

					<div class="navbar-fixed-top align-right">
						<br />
						&nbsp;
						<a id="btn-login-dark" href="#">Dark</a>
						&nbsp;
						<span class="blue">/</span>
						&nbsp;
						<a id="btn-login-blur" href="#">Blur</a>
						&nbsp;
						<span class="blue">/</span>
						&nbsp;
						<a id="btn-login-light" href="#">Light</a>
						&nbsp; &nbsp; &nbsp;
					</div>
				</div>
			</div><!-- /.col -->
		</div><!-- /.row -->
	</div><!-- /.main-content -->
</div><!-- /.main-container -->

<!-- basic scripts -->

<!--[if !IE]> -->
<script type="text/javascript">
	window.jQuery || document.write("<script src='/admin/assets/js/jquery.min.js'>"+"<"+"/script>");
</script>

<!-- <![endif]-->

<script type="text/javascript">
	if('ontouchstart' in document.documentElement) document.write("<script src='admin/assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
</script>

<!-- inline scripts related to this page -->
<script type="text/javascript">
	jQuery(function($) {
		$(document).on('click', '.toolbar a[data-target]', function(e) {
			e.preventDefault();
			var target = $(this).data('target');
			$('.widget-box.visible').removeClass('visible');//hide others
			$(target).addClass('visible');//show target
		});
	});



	//you don't need this, just used for changing background
	jQuery(function($) {
		$('#btn-login-dark').on('click', function(e) {
			$('body').attr('class', 'login-layout');
			$('#id-text2').attr('class', 'white');
			$('#id-company-text').attr('class', 'blue');

			e.preventDefault();
		});
		$('#btn-login-light').on('click', function(e) {
			$('body').attr('class', 'login-layout light-login');
			$('#id-text2').attr('class', 'grey');
			$('#id-company-text').attr('class', 'blue');

			e.preventDefault();
		});
		$('#btn-login-blur').on('click', function(e) {
			$('body').attr('class', 'login-layout blur-login');
			$('#id-text2').attr('class', 'white');
			$('#id-company-text').attr('class', 'light-blue');

			e.preventDefault();
		});

	});
</script>
</body>
</html>
