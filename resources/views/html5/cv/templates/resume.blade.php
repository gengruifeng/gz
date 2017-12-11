
 <!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title></title>
    	<meta name="description" content="帮你解决简历的基本问题、难题！ 工作网欢迎您来分享您的职场经验。"/>
    	<meta name="keywords" content="简历"/>
    	<meta name="viewport" content="width=device-width,initial-scale=1, minimum-scale=1.0, maximum-scale=1, user-scalable=no">
		<link rel="stylesheet" href="{{ asset('css/css3/public.css') }}"/>
		<link rel="stylesheet" href="{{ asset('css/css3/resume.css') }}"/>
	</head>
	<body>
		<!--头部开始-->
		<div class="common-head clearfix">
			<div class="logo"><img class="logoimg" src="{{ url('images/logos/logosecd.png') }}" alt=""></div>
		</div>
		<!--内容开始-->
			<section>
				<div class="resume-wrap">
					<div class="resume-search clearfix">
						<input type="text" class="fl inp" id="search-inp-one" placeholder="输入行业或职位名，找专属你的个人简历模板">
						<a href="javascript:;" class="fl inpbtn" id="search-inp-two">找模板</a>
					</div>
					<div id="resumelist">
						<ul></ul>
					</div>
					{{--<div class="addmore"><a href="javascript:;">loading...</a></div>--}}

				</div>
			</section>
		<!-- 弹出 -->
		<div class="search-help hide">
			<!--头部开始-->
			<div class="common-head clearfix">
				<a href="javascript:;" class="fl btn-close-bac btn-close"></a>
				<div class="logo">简历模板搜索</div>
			</div>
			<!--内容开始-->
			<section>
				<div class="resume-wrap-search">
					<form action="/cv/templates/search" method="get" class="resume-search clearfix">
						<div class="search clearfix">
							<input type="search" id="search-template" name="q" class="fl inp" autocomplete="off" placeholder="输入行业或职位名，找专属你的个人简历模板">
							<input type="submit" class="inpbtn fr" value="找模板">
						</div>
					</form>
				</div>
			</section>
		</div>
		<a href="javascript:;" id="btn-back-top"></a>
		<script src="{{ asset('js/jquery-2.1.0.js') }}"></script>
		<script src="{{ asset('jswrap/public.js') }}"></script>
		<script src="{{ asset('js/view/paged_list.js') }}"></script>
		<script src="{{ asset('js/shared/util.js') }}"></script>
		<script>
			$('#resumelist').pagedList({
				serverCall: '/cv/templates/list',
				kwargs: QueryString,
				hiddenClass: 'hidden'
			});
		</script>
		<script>
			$(function(){
				var key=false;
				$('#search-inp-one,#search-inp-two').on('click',function () {

					$('.search-help').animate({'top':0,opacity: 'show'},400);
//					$('#search-inp-one').blur();
					$('#search-template').focus();
					document.body.scrollTop =0;
					key =true;
				});
				$('.btn-close').on('click',function(){
					$('.search-help').animate({'top':'30%',opacity: 'hide'},400);
					$('.search-help input').find('input[type="search"]').focus();
					key=false;
				});

				document.addEventListener('touchmove', function(event) {
					//判断条件,条件成立才阻止背景页面滚动,其他情况不会再影响到页面滚动
					if(key == true){
						event.preventDefault();
					}
				})

			})
		</script>
	</body>
</html>
