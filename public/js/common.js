var url = window.location.protocol+'//'+window.location.host;
//注册页加载页面底部
//$('#header').load("header.html#head");
$(function(){
	//头部发布开始
	$('#release').hover(function(){
		$('#releaseUl').removeClass('dispaly');
	},function(){
		var timer=setTimeout(function(){
			$('#releaseUl').addClass('dispaly');
		},200);
		$('#releaseUl').hover(function(){
			clearTimeout(timer);
			$('#releaseUl').removeClass('dispaly');
		},function(){
			$('#releaseUl').addClass('dispaly');
		});
	});
	//头部发布结束
});
$(function(){
	//个人中心tab切换开始
	$('#fuc>a').click(function(){
		var index=$(this).index();
		$(this).addClass('onClick').siblings('a').removeClass('onClick');
		$('.mc').eq(index).removeClass('dispaly').siblings().addClass('dispaly');
	});
	//个人中心tab切换结束
});
//登录状态开始
$(window).load(function() {
	var checklogin = $('#checklogin').val();
	// Animate loader off screen
	if(checklogin == undefined){
		$('#loginyc').show();
		$('#logindlyc').hide();
	}else{
		$('#logindlyc').show();
		$('#loginyc').hide();
	}
});
$(function(){
	$('.loginStatus_1').hover(function(){
		var index=$(this).index();
		
		$('.loginStatus_2').eq(index).removeClass('dispaly').siblings().addClass('dispaly');
	},function(){
		var index=$(this).index();
		var timer=setTimeout(function(){
			$('.loginStatus_2').eq(index).addClass('dispaly');
		},200);
		$('.loginStatus_2').hover(function(){
			clearTimeout(timer);
		},function(){
			$('.loginStatus_2').addClass('dispaly');
		});
	});
});
//登录状态结束
//问答页面最新问题切换开始
$(function(){
	$('.qa').click(function(){
		var index=$(this).index();
		$('.qa_ul').eq(index).removeClass('dispaly').siblings().addClass('dispaly');
	});
});
//问答页面最新问题切换结束

//发送验证码时添加cookie
function addCookie(name,value,expiresHours){
	var cookieString=name+"="+escape(value);
	//判断是否设置过期时间,0代表关闭浏览器时失效
	if(expiresHours>0){
		var date=new Date();
		date.setTime(date.getTime()+expiresHours*1000);
		cookieString=cookieString+";expires=" + date.toUTCString();
	}
	document.cookie=cookieString;
}
//修改cookie的值
function editCookie(name,value,expiresHours){
	var cookieString=name+"="+escape(value);
	if(expiresHours>0){
		var date=new Date();
		date.setTime(date.getTime()+expiresHours*1000); //单位是毫秒
		cookieString=cookieString+";expires=" + date.toGMTString();
	}
	document.cookie=cookieString;
}
//根据名字获取cookie的值
function getCookieValue(name){
	var strCookie=document.cookie;
	var arrCookie=strCookie.split("; ");
	for(var i=0;i<arrCookie.length;i++){
		var arr=arrCookie[i].split("=");
		if(arr[0]==name){
			return unescape(arr[1]);
			break;
		}
	}
}
// $(function(){
// 	v = getCookieValue("secondsremained")?getCookieValue("secondsremained"):0//获取cookie值
// 	if(v>0){
// 		settime($("#sendsmscodews"));//开始倒计时
// 	}
// })

//开始倒计时
var countdown;
function settime(obj) {
	countdown=getCookieValue("secondsremained");
	if (countdown == 0 || countdown ==undefined) {
		obj.removeAttr("disabled");
		obj.html("获取验证码");
		return ;
	} else {
		obj.attr("disabled", true);
		obj.html("重新发送(" + countdown + ")");
		countdown--;
		editCookie("secondsremained",countdown,countdown+1);
	}
	setTimeout(function() { settime(obj) },1000) //每1000毫秒执行一次
}

// 根据邮箱域名跳转相应登录页面
function goMail(txt) {
	var hash={
		'qq.com': 'http://mail.qq.com',
		'gmail.com': 'http://mail.google.com',
		'sina.com': 'http://mail.sina.com.cn',
		'163.com': 'http://mail.163.com',
		'126.com': 'http://mail.126.com',
		'yeah.net': 'http://www.yeah.net/',
		'sohu.com': 'http://mail.sohu.com/',
		'tom.com': 'http://mail.tom.com/',
		'sogou.com': 'http://mail.sogou.com/',
		'139.com': 'http://mail.10086.cn/',
		'hotmail.com': 'http://www.hotmail.com',
		'live.com': 'http://login.live.com/',
		'live.cn': 'http://login.live.cn/',
		'live.com.cn': 'http://login.live.com.cn',
		'189.com': 'http://webmail16.189.cn/webmail/',
		'yahoo.com.cn': 'http://mail.cn.yahoo.com/',
		'yahoo.cn': 'http://mail.cn.yahoo.com/',
		'eyou.com': 'http://www.eyou.com/',
		'21cn.com': 'http://mail.21cn.com/',
		'188.com': 'http://www.188.com/',
		'foxmail.com': 'http://www.foxmail.com',
		'wyzc.com':'http://mail.mxhichina.com'
	};
	var url = txt.split('@')[1];
	if(hash[url] == ""){
		dialogcom_warn("无法跳转，请检查邮箱地址是否存在");
	}else{
		window.open(hash[url]);
	}
}
//删除js
showOverlay("#dialog_2");
function showOverlay(id) {
	$(id).height($(window).height());
	$(id).width($(window).width());
};



//定义用户可以输入的最多字数
function checkMaxInput(obj,inputnumber,maxLen) {
	if (obj.value.length > maxLen){	//如果输入的字数超过了限制
		obj.value = obj.value.substring(0, maxLen);	//就去掉多余的字
		document.getElementById(inputnumber).innerText = '0'
	}
	else{
		document.getElementById(inputnumber).innerText =(maxLen - obj.value.length);//计算并显示剩余字数
	}
}
var exp = /^[a-zA-Z0-9_\u4e00-\u9fa5]{2,11}$/;

//成功提示
function dialog_3(message){
	$('#dialog_3').removeClass('display');
	$('#dialog_3_p').text(message);
	setTimeout(function(){
		$('#dialog_3').addClass('display');
	},2000);
}

//成功提示
function dialogcom_yes(message){
	$('.dialogcom_yes').removeClass('hide');
	$('.dialogcom_yes form span').text(message);
	setTimeout(function(){
		$('.dialogcom_yes').addClass('hide');
	},2000);
}
//成功提示跳转
function dialogcom_yes_go(message,loca,way){
	$('.dialogcom_yes').removeClass('hide');
	$('.dialogcom_yes form span').text(message);
	var type = false;
	$('.dialogcom_yes').click(function(){
		//dialogcom_yes
		type = true;
		$(this).addClass('hide');
		if(way == 1){
			location.reload();
		}else{
			location.href=loca;
		}

	});
	if(type == false ){
		setTimeout(function(){
			$('.dialogcom_yes').addClass('hide');
			if(way == 1){
				location.reload();
			}else{
				location.href=loca;
			}
		},2000);
	}
}
//失败提示
function dialogcom_wrong(message){
	$('.dialogcom_wrong').removeClass('hide');
	$('.dialogcom_wrong form span').text(message);
	setTimeout(function(){
		$('.dialogcom_wrong').addClass('hide');
	},2000);
}
//警告提示
function dialogcom_warn(message){
	$('.dialogcom_warn').removeClass('hide');
	$('.dialogcom_warn form span').text(message);
	setTimeout(function(){
		$('.dialogcom_warn').addClass('hide');
	},2000);
}
//点击遮罩层消失
$('.dialogcom').click(function(){
	$('.dialogcom').addClass('hide');
});
//添加标签封装函数
function addTags(id) {
	var exp = /[\u4e00-\u9fa5_a-zA-Z0-9]{2,11}/;
	$(id).on('keydown', function(event) {
		$(this).css({
			'borderColor': '#dfdfdf'
		});
		var status = null;
		var _e = event || window.event;
		var keycode = _e.which;
		var text = $(this).val();
		if(keycode == 13 && $('#addTag a').length < 5 && exp.test(text) && !/\s/.test(text) && text.length < 11) {

			for(var i = 0; i < $('#addTag a').length; i++) {
				if($('#addTag a').eq(i).text() == text) {
					status = true;
				}
			}
			if(status) {
				dialogcom_warn('重复了！')
				$(this).val('');
			} else {
				var str = "<a href='javascript:void(0)'>" + text +
					"<span class='offTag'></span>" +
					"</a>";
				$('#addTag').append(str);

			}
			$(this).val('');
		} else if(keycode == 13) {
			$(this).css({
				'borderColor': '#f87e6a'
			});
			var text = $(this).val('');
		}

	});
};


function isLogin(data,type) {

	var islogin =  $("meta[name=user-login]").attr("content");
	if(islogin == ''){
		window.dialog_data = data;
		window.type = type;
		dialoglogin();
	}else{
		if(type == 1){
			window.location.href=data;
		}else{
			eval(data);
		}
	}
}

function dialoglogin() {
	$( "#dialog-login" ).dialog({
		width:490,
		height:503,
		modal: true,
	});
	validate_login()

	$('.btnclose').click(function () {
		$('#dialog-login').dialog('close');
	})

}

function validate_login() {
	jQuery.validator.addMethod("checkUsername", function(value, element) {
		return this.optional(element) || (/^1[3|4|5|7|8]\d{9}$/.test(value) || /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(value));
	}, "用户名格式不正确。");
	$('#dialog-login-from').validate({
		errorElement: 'p',
		errorClass: 'red-text',
		focusInvalid: true,
		rules: {
			auth_name: {
				required: true,
				checkUsername: true,
			},
			passcode: {
				required: true,
				rangelength:[6,16]
			},
		},

		messages: {
			auth_name:{
				required:"请输入用户名。",
			},
			passcode: {
				required: "请输入密码。",
				rangelength: "密码长度为6-16位。",
			},
		},

		highlight: function (e) {
			$(e).parent().addClass('err');
			$(e).parent().removeClass('correct');

		},

		success: function (e) {
			$(e).prev().addClass('correct');
			$(e).prev().removeClass('err');
			$(e).remove();

		},

		errorPlacement: function (error, element) {
			error.insertAfter(element.parent());
		},

		submitHandler: function (form) {
			var options = {
				success: function() {
                    changeElent();
					if (1 !== window.type) {
						eval(window.dialog_data);
					}

					$('#dialog-login').dialog('close');
				},
				error: dialogerrorHandle
			};
			$(form).ajaxSubmit(options);
		}

	});
}

function dialogerrorHandle(response, status, xhr, form) {
	switch (response.status) {
		case 400:
			dialogerrorPush(response.responseJSON.errors);
			break;

		default:
			dialogerrorTip(response.responseJSON);
			break;
	}
}


function dialogerrorPush(errors) {
	var l = errors.length;

	for (var i = 0; i < l; i++) {
		$("input[name=" + errors[i].input + "]").parent().addClass('err');
		$("input[name=" + errors[i].input + "]").parent().after('<p id="'+errors[i].input+'-error" class="red-text">'+errors[i].message+'</p>')
		$('.correct').removeClass('correct')

	}
}

function dialogerrorTip(error) {
	$("input[name=auth_name]").parent().addClass('err');
	$("input[name=auth_name]").parent().after('<p id="auth_name-error" class="red-text"">'+error.description+'</p>')
	$('.correct').removeClass('correct')

}

function changeElent() {
	$.ajax({
		url: '/ajax/getinfo',
		type: 'POST',
		dataType: 'json',
		data: {_token:$('input[name = _token]').val()},
	})
		.done(function (r) {
			$("meta[name=user-login]").attr("content",r.display_name);
			var html ='<div class="loginStatus"><a href="javascript:void(0)" class="loginStatus_1 dispaly">通知</a><a href="javascript:void(0)" class="loginStatus_1 dispaly"><img src="/avatars/30/'+r.avatar+'" id="logindlyc"><i></i></a><ul class="loginStatus_2 dispaly"><li><a href="/askmsg/'+r.uid+'">问答消息</a></li><li><a href="/articlemsg/'+r.uid+'">文章消息</a></li><li><a href="/privatemsg/'+r.uid+'">私信</a></li><li><a href="/systemmsg/'+r.uid+'">系统通知</a></li></ul><ul class="loginStatus_2 dispaly"><li><a href="/profile">个人中心</a></li><li><a href="/account/settings">个人设置</a></li><li><a href="/resume/list">简历管理</a></li><li><a href="/profile/'+r.uid+'/articles">我的文章</a></li><li><a href="/follower">我的粉丝</a></li><li><a href="/following">我的关注</a></li><li><a href="/logout">退出登录</a></li></ul></div>';
			if($('.loginStatus').length > 0){
				$('.loginStatus').remove();
			}
			$('.login').remove();
			$('.nav_list').after(html);

			$('.loginStatus_1').hover(function() {
				var index = $(this).index();

				$('.loginStatus_2').eq(index).removeClass('dispaly').siblings().addClass('dispaly');
			}, function() {
				var index = $(this).index();
				var timer = setTimeout(function() {
					$('.loginStatus_2').eq(index).addClass('dispaly');
				}, 200);
				$('.loginStatus_2').hover(function() {
					clearTimeout(timer);
				}, function() {
					$('.loginStatus_2').addClass('dispaly');
				});
			});

			if (1 === window.type) {
				window.location.href=window.dialog_data;
			}
		})

		.fail(function(XMLHttpRequest, textStatus, errorThrown) {

		})
}

function dialogupload() {
	$( ".imgup" ).dialog({
		width:550,
		height:500,
		modal: true,
	});
	$('.btn-close').click(function () {
		$('.imgup').dialog('close');
	})

}

//回到顶部
$(window).on('resize scroll',function(){
	if($(window).scrollTop()>100){
		$('a#btn-back-top').show();
	}else{
		$('a#btn-back-top').hide();
	}
	// console.log(1)
});
$('a#btn-back-top').click(function () {
	var speed=200;//滑动的速度
	$('body,html').animate({ scrollTop: 0 }, speed);
	return false;
});
