$(document).ready(function(){
	// 自定义方法 手机或邮箱
	jQuery.validator.addMethod("checkUsername", function(value, element) {
		return this.optional(element) || (/^1[3|4|5|7|8]\d{9}$/.test(value) || /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(value));
	}, "用户名格式不正确");

	// 自定义方法 只邮箱
	jQuery.validator.addMethod("emailUsername", function(value, element) {
		return this.optional(element) || /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(value);
	}, "邮箱格式不正确");

	// 自定义方法 只手机
	jQuery.validator.addMethod("mobUsername", function(value, element) {
		return this.optional(element) || /^1[3|4|5|7|8]\d{9}$/.test(value);
	}, "手机号格式不正确");

	// 自定义方法 密码
	jQuery.validator.addMethod("passcode", function(value, element) {
		return this.optional(element) || /^[\w_]{6,16}$/.test(value);
	}, "密码为6-16位字符，只能输入英文、数字、‘_’");

	//自定义方法 名号
	jQuery.validator.addMethod("checkDisplayName", function(value, element) {
		return this.optional(element) || /^[a-zA-Z0-9_\u4e00-\u9fa5]{2,24}$/.test(value);
	}, "名号为2-24位字符：支持中文、英文、数字、‘_’");

	$("#school").click(function () {
		$(".stepone dt").html('<b class="importantip">*</b>学校：');
		$(".steptwo dt").html('<b class="importantip">*</b>专业：');
		$(".stepone dd input").attr('placeholder','您的学校');
		$(".steptwo dd input").attr('placeholder','您的专业');
	});

	$("#work").click(function () {
		$(".stepone dt").html('<b class="importantip">*</b>公司：');
		$(".steptwo dt").html('<b class="importantip">*</b>职位：');
		$(".stepone dd input").attr('placeholder','您的公司');
		$(".steptwo dd input").attr('placeholder','您的职位');
	});


	// 第三方登录账号注册 mobile-form =>loginMobile
	if($('#mobile-form').length > 0){
		$('#mobile-form').validate({
			errorElement: 'p',
			errorClass: 'red-text',
			focusInvalid: true,
			rules: {
				mobile: {//只可手机
					required:true,
					mobUsername :"手机号格式不正确",
				},
				referral_code: {
					required:true,
					maxlength:8,
					minlength:8
				},
				password: {//密码 输入长度必须介于 6 和 16之间的字符串（汉字算一个字符）
					required: true,
					rangelength:[6,16]
				},
				confirm_password: {//确认密码
					required: true,
					minlength: 6,
					equalTo: "#password"
				},
				mobMsg: {// 短信验证码 6位数字
					// required: "#newsletter:checked",
					required: true,
					digits:true,
					maxlength:6,
					minlength:6
				},
			},
			messages: {
				referral_code:{
					required:"请输入邀请码",
					minlength: "邀请码长度为8位",
					maxlength:"邀请码长度为8位"
				},
				mobile:{//用户名 可邮箱、可手机
					required:"请输入手机号",
				},
				password: {
					required: "请输入密码",
					rangelength:"长度介于6和16之间的字符串"
				},
				confirm_password: {
					required: "请输入密码",
					minlength: "最少为6位",
					equalTo: "两次密码不一致"
				},
				mobMsg:{
					required: "短信验证码",
					minlength: "短信验证码为6位",
					maxlength:"短信验证码为6位",
					digits:"只能为数字"
				},
			},
			highlight: function (e) {//错误的显示
				$(e).parent().removeClass('correct');
				$(e).parent().addClass('err');

			},
			success: function (e) {//成功
				$(e).prev().removeClass('err');
				$(e).prev().addClass('correct');
				$(e).remove();
			},
			errorPlacement: function (error, element) {//错误显示位置
				if(element.parent().next().attr('class') == 'red-text'){
					element.parent().next().remove();
				}
				error.insertAfter(element.parent());
			},
			submitHandler: function (form) {//可提交后操作
				var uid = $('#uid').val();
				var data =$(form).serialize();
				$.ajax({
					type:"post",
					url:"ajax/users/information",
					async : false,
					data:data+'&registerType=5',
					dataType: "json",
					success:function(data) {
						location.href = '/personalinfoadd';
					}
				}).fail(function(XMLHttpRequest, textStatus) {
					$('.red-text').remove();
					if(textStatus == 'error'){
						var errors = XMLHttpRequest.responseJSON.errors;
						$.each(errors,function (name,vale) {
							$("input[name=" + vale.input + "]").parent().after('<p id="'+name+'-error" class="red-text">'+vale.message+'</p>');
							$("input[name=" + vale.input + "]").parent().removeClass('correct');
							$("input[name=" + vale.input + "]").parent().addClass('err');
						})
					}
				});
			}
		});
	}


	// 注册后 填写个人信息
	if($('#personal-form').length > 0){
		var token = $("input[name = '_token']").val();
		$('#personal-form').validate({
			errorElement: 'p',
			errorClass: 'red-text',
			focusInvalid: true,
			rules: {
				display_name: {//用户名 可邮箱、可手机
					required: true,
					checkDisplayName :true,
					remote: {
						type: "post",
						url: "/ajax/users/checkdisplayname",
						data: {
							display_name: function() {
								return $("#display_name").val();
							},_token:token
						},
						dataType: "json",
						dataFilter: function(data,type) {
							var check = JSON.parse(data);
							if (1 === check.isExist) {
								return false;
							}
							return true;
						}
					}
				},
				stepone: {//公司 学校
					required: true
				},
				steptwo: {//专业 职位
					required: true
				}
			},
			messages: {
				display_name:{//用户名 可邮箱、可手机
					required:"请输入用户名",
					remote:"名号已经被注册"
				},
				stepone: {//公司 学校
					required: "请输入你的公司或学校"
				},
				steptwo: {//性别
					required: "请输入你的职位或专业",
				},
			},
			highlight: function (e) {//错误的显示
				$(e).parent().removeClass('correct');
				$(e).parent().addClass('err');

			},
			success: function (e) {//成功
				$(e).prev().removeClass('err');
				$(e).prev().addClass('correct');
				$(e).remove();
			},
			errorPlacement: function (error, element) {//错误显示位置
				if(element.parent().next().attr('class') == 'red-text'){
					element.parent().next().remove();
				}
				error.insertAfter(element.parent());
			},
			submitHandler: function (form) {//可提交后操作
				//ajax
				var data =$(form).serialize();
				var userid = $("#userRegisterId").val();
				$.ajax({
					url: '/ajax/users/register',
					type: 'post',
					dataType: 'json',
					data: data+'&userid='+userid+'&registerType=6',
				}).fail(function(XMLHttpRequest, textStatus) {
					$('.red-text').remove();
					if(textStatus == 'error'){
						var errors = XMLHttpRequest.responseJSON.errors;
						$.each(errors,function (name,vale) {
							$("input[name=" + vale.input + "]").parent().after('<p id="'+name+'-error" class="red-text">'+vale.message+'</p>');
							$("input[name=" + vale.input + "]").parent().removeClass('correct');
							$("input[name=" + vale.input + "]").parent().addClass('err');
						})
					}else{
						location.href = '/begoodat';
					}
				});
			}
		});
	}

	// 密码设置成功重新登录 resetSucess-form
	if($('#resetSucess-form').length > 0){
		$('#resetSucess-form').validate({
			errorElement: 'p',
			errorClass: 'red-text',
			focusInvalid: true,
			rules: {
				display_name: {//用户名 可邮箱、可手机
					required: true,
					checkUsername :true,
				},
				password: {//密码 输入长度必须介于 6 和 16之间的字符串（汉字算一个字符）
					required: true,
					rangelength:[6,16]
				},
			},
			messages: {
				display_name:{//用户名 可邮箱、可手机
					required:"请输入用户名",
				},
				password: {
					required: "请输入密码",
					rangelength:"长度介于6和16之间的字符串"

				},
			},
			highlight: function (e) {//错误的显示
				$(e).parent().removeClass('correct');
				$(e).parent().addClass('err');

			},
			success: function (e) {//成功
				$(e).prev().removeClass('err');
				$(e).prev().addClass('correct');
				$(e).remove();
			},
			errorPlacement: function (error, element) {//错误显示位置
				error.insertAfter(element.parent());
			},
			submitHandler: function (form) {//可提交后操作
				//ajax
				window.location.href="擅长领域.html";
			}
		});
	}

	// 手机号账号注册 MobileRegistration-form
	if($('#MobileRegistration-form').length > 0){
		$('#MobileRegistration-form').validate({
			errorElement: 'p',
			errorClass: 'red-text',
			focusInvalid: true,
			rules: {
				referral_code: {//邀请码
					required: true,
					maxlength:8,
					minlength:8
				},
				passcode: {//密码 输入长度必须介于 6 和 16之间的字符串
					required: true,
					passcode:true
				},
				passcode_confirmation: {//确认密码
					required: true,
					passcode:true,
					equalTo: "#passcode"
				},
				verifycode: {// 短信验证码 6位数字
					required: true,
					digits:true,
					maxlength:6,
					minlength:6
				},
			},
			messages: {
				referral_code: {
					required: "请输入邀请码",
					minlength: "邀请码长度为8位",
					maxlength:"邀请码长度为8位"
				},
				passcode: {
					required: "请输入密码",
					passcode:"密码为6-16位字符，只能输入英文、数字、‘_’"
				},
				passcode_confirmation: {
					required: "请输入密码",
					passcode:"确认密码为6-16位字符，只能输入英文、数字、‘_’",
					equalTo: "两次密码不一致"
				},
				verifycode:{
					required: "请输入短信验证码",
					minlength: "短信验证码为6位",
					maxlength:"短信验证码为6位",
					digits:"只能为数字"
				},
			},
			highlight: function (e) {//错误的显示
				$(e).parent().removeClass('correct');
				$(e).parent().addClass('err');

			},
			success: function (e) {//成功
				$(e).prev().removeClass('err');
				$(e).prev().addClass('correct');
				$(e).remove();
			},
			errorPlacement: function (error, element) {//错误显示位置
				if(element.parent().next().attr('class') == 'red-text'){
					element.parent().next().remove();
				}
				error.insertAfter(element.parent());
			},
			submitHandler: function (form) {//可提交后操作
				//ajax
				var data =$(form).serialize();
				var mobile = $("#mobile").val();
				$.ajax({
					url: '/ajax/users/register',
					type: 'post',
					dataType: 'json',
					data: data+'&mobile='+mobile+'&registerType=2'
				}).fail(function(XMLHttpRequest, textStatus) {
					$('.red-text').remove();
					if(textStatus == 'error'){
						var errors = XMLHttpRequest.responseJSON.errors;
						$.each(errors,function (name,vale) {
							$("input[name=" + vale.input + "]").parent().after('<p id="'+name+'-error" class="red-text">'+vale.message+'</p>');
							$("input[name=" + vale.input + "]").parent().removeClass('correct');
							$("input[name=" + vale.input + "]").parent().addClass('err');
						})
					}else{
						location.href = '/personalinfoadd';
					}
				});
			}
		});
	}

	// 手机号注册 2 loginMobileNum-form
	if($('#loginMobileNum-form').length > 0){
		$('#loginMobileNum-form').validate({
			errorElement: 'p',
			errorClass: 'red-text',
			focusInvalid: true,
			rules: {
				mobile:{//只手机号
					required: true,
					mobUsername:true

				},
			},
			messages: {
				mobile:{
					required:"请输入手机号",
				},
			},
			highlight: function (e) {//错误的显示
				$(e).parent().removeClass('correct');
				$(e).parent().addClass('err');

			},
			success: function (e) {//成功
				$(e).prev().removeClass('err');
				$(e).prev().addClass('correct');
				$(e).remove();
			},
			errorPlacement: function (error, element) {//错误显示位置
				$('.red-text').remove();
				error.insertAfter(element.parent());
			},
			submitHandler: function (form) {//可提交后操作
				var data =$(form).serialize();
				$.ajax({
					type: "post",
					url: "/ajax/users/register",
					data: data+'&registerType=1',
					dataType: "json"
				}).fail(function(XMLHttpRequest, textStatus) {
					$('.red-text').remove();
					if(textStatus == 'error'){
						var errors = XMLHttpRequest.responseJSON.errors;
						$.each(errors,function (name,vale) {
							$("input[name=" + vale.input + "]").parent().after('<p id="'+name+'-error" class="red-text">'+vale.message+'</p>');
							$("input[name=" + vale.input + "]").parent().removeClass('correct');
							$("input[name=" + vale.input + "]").parent().addClass('err');
						})
					}else{
						location.href = 'mobileinfoadd';
					}
				});
			}
		});
	}

	// 邮箱认证 2 loginSureEmail
	if($('#loginSureEmail-form').length > 0){
		$('#loginSureEmail-form').validate({
			errorElement: 'p',
			errorClass: 'red-text',
			focusInvalid: true,
			rules: {
				email: {
					required: true,
					emailUsername:true
				},
			},
			messages: {
				email:{
					required:"请输入邮箱"
				},
			},
			highlight: function (e) {//错误的显示
				$(e).parent().removeClass('correct');
				$(e).parent().addClass('err');

			},
			success: function (e) {//成功
				$(e).prev().removeClass('err');
				$(e).prev().addClass('correct');
				$(e).remove();
			},
			errorPlacement: function (error, element) {//错误显示位置
				$('.red-text').remove();
				error.insertAfter(element.parent());
			},
			submitHandler: function (form) {//可提交后操作
				var data =$(form).serialize();
				$.ajax({
					type:"post",
					url:"/ajax/users/register",
					async:false,
					data:data+'&registerType=3',
					dataType: "json"
				}).fail(function(XMLHttpRequest, textStatus) {
					$('.red-text').remove();
					if(textStatus == 'error'){
						var errors = XMLHttpRequest.responseJSON.errors;
						$.each(errors,function (name,vale) {
							$("input[name=" + vale.input + "]").parent().after('<p id="' + name + '-error" class="red-text">' + vale.message + '</p>');
							$("input[name=" + vale.input + "]").parent().removeClass('correct');
							$("input[name=" + vale.input + "]").parent().addClass('err');
						})
					}else{
						location.href = 'mailconfirm';
					}
				})
			}
		});
	}

	// 邮箱设置新密码 loginPssswordResetEmail
	if($('#loginPssswordResetEmail-form').length > 0){
		$('#loginPssswordResetEmail-form').validate({
			errorElement: 'p',
			errorClass: 'red-text',
			focusInvalid: true,
			rules: {
				password: {
					required: true,
					rangelength:[6,16],
					passcode:true
				},
				password_confirmation: {//确认密码
					required: true,
					minlength: 6,
					equalTo: "#password"
				},
			},
			messages: {
				password: {
					required: "请输入密码",
					rangelength:"长度介于6和16之间的字符串",
					passcode:"密码为6-16位字符，只能输入英文、数字、‘_’"
				},
				password_confirmation: {
					required: "请输入密码",
					minlength: "最少为6位",
					equalTo: "两次密码不一致"
				},
			},
			highlight: function (e) {//错误的显示
				$(e).parent().removeClass('correct');
				$(e).parent().addClass('err');

			},
			success: function (e) {//成功
				$(e).prev().removeClass('err');
				$(e).prev().addClass('correct');
				$(e).remove();
			},
			errorPlacement: function (error, element) {//错误显示位置
				if(element.parent().next().attr('class') == 'red-text'){
					element.parent().next().remove();
				}
				error.insertAfter(element.parent());

			},
			submitHandler: function (form) {//可提交后操作
				var data =$(form).serialize();
				$.ajax({
					url: $(form).attr('action'),
					type: 'POST',
					dataType: 'json',
					data: data+'&template=retrieve&type=1&isTable=1',
				})
					.done(function() {
						window.location.href="/login";
					})
					.fail(function(XMLHttpRequest, textStatus, errorThrown) {
						if(textStatus == 'error'){
							var obj = JSON.parse(XMLHttpRequest.responseText);
							var errors = obj.errors;
							$.each(errors,function (name,vale) {
								$("input[name=" + name + "]").parent().after('<p id="' + name + '-error" class="red-text">' + vale + '</p>');
								$("input[name=" + name + "]").parent().removeClass('correct');
								$("input[name=" + name + "]").parent().addClass('err');
							})
						}else{
							window.location.href="/login";
						}

					})

			}
		});
	}

	// 邮箱找回密码 loginFindEmail
	if($('#loginFindEmail-form').length > 0){
		$('#loginFindEmail-form').validate({
			errorElement: 'p',
			errorClass: 'red-text',
			focusInvalid: true,
			rules: {
				mail: {
					required: true,
					emailUsername:true
				},
				code: {
					required: true,
				},
			},
			messages: {
				mail:{
					required:"请输入邮箱"
				},
				code:{
					required: "请输入验证码",
				},
			},
			highlight: function (e) {//错误的显示
				$(e).parent().removeClass('correct');
				$(e).parent().addClass('err');
			},
			success: function (e) {//成功
				$(e).prev().removeClass('err');
				if($(e).attr('id') != 'code-error'){
					$(e).prev().addClass('correct');
				}
				$(e).remove();

			},
			errorPlacement: function (error, element) {//错误显示位置
				if(element.parent().next().attr('class') == 'red-text'){
					element.parent().next().remove();
				}
				error.insertAfter(element.parent());
			},
			submitHandler: function (form) {//可提交后操作
				var data =$(form).serialize();
				$.ajax({
					url: $(form).attr('action'),
					type: 'POST',
					dataType: 'json',
					data: data+'&template=updatepassword&type=1&isTable=1',
				})

					.fail(function(XMLHttpRequest, textStatus, errorThrown) {
						if(textStatus == 'error'){
							$('.red-text').remove();
							var obj = JSON.parse(XMLHttpRequest.responseText);
							var errors = obj.errors;
							$.each(errors,function (name,vale) {
								$("input[name="+name+"]").parent().after('<p class="red-text">' + vale + '</p>');
								$("input[name="+name+"]").parent().removeClass('correct');
								$("input[name="+name+"]").parent().addClass('err');
							});
							$('#img').attr('src','/users/code/'+Math.random()*10000+1);

						}else{
							$('.emialdress').text($("input[name=mail]").val());
							$('.sub').attr('onclick','goMail("'+$("input[name=mail]").val()+'")');
							$('.retrieveMobile').addClass('hide');
							$('.Emailmodify').removeClass('hide');
							location.hash='mailConfirm';
						}

					})
			}
		});
	}

	// 邮箱注册 loginRegistrationEmail
	if($('#loginRegistrationEmail-form').length > 0){
		$('#loginRegistrationEmail-form').validate({
			errorElement: 'p',
			errorClass: 'red-text',
			focusInvalid: true,
			rules: {
				email: {
					required: true,
					emailUsername:true
				},
			},
			messages: {
				email:{
					required:"请输入邮箱"
				},
			},
			highlight: function (e) {//错误的显示
				$(e).parent().removeClass('correct');
				$(e).parent().addClass('err');

			},
			success: function (e) {//成功
				$(e).prev().removeClass('err');
				$(e).prev().addClass('correct');
				$(e).remove();
			},
			errorPlacement: function (error, element) {//错误显示位置
				if(element.parent().next().attr('class') == 'red-text'){
					element.parent().next().remove();
				}
				error.insertAfter(element.parent());
			},
			submitHandler: function (form) {//可提交后操作
				//ajax
				var data =$(form).serialize();
				$.ajax({
					type:"post",
					url:"/ajax/users/register",
					data:data+'&registerType=3',
					dataType: "json"
				}).fail(function(XMLHttpRequest, textStatus) {
					$('.red-text').remove();
					if(textStatus == 'error'){
						var errors = XMLHttpRequest.responseJSON.errors;
						$.each(errors,function (name,vale) {
							$("input[name=" + vale.input + "]").parent().after('<p id="' + name + '-error" class="red-text">' + vale.message + '</p>');
							$("input[name=" + vale.input + "]").parent().removeClass('correct');
							$("input[name=" + vale.input + "]").parent().addClass('err');

						})
					}else{
						location.href = 'mailconfirm';
					}
				})
			}
		});
	}

	// 账号注册 loginRegistrationEpassword
	if($('#loginRegistrationEpassword-form').length > 0){
		$('#loginRegistrationEpassword-form').validate({
			errorElement: 'p',
			errorClass: 'red-text',
			focusInvalid: true,
			rules: {
				referral_code: {
					required: true,
					maxlength:8,
					minlength:8
				},
				passcode: {//密码 输入长度必须介于 6 和 16之间的字符串（汉字算一个字符）
					required: true,
					rangelength:[6,16],
					passcode:true
				},
				passcode_confirmation: {//确认密码
					required: true,
					minlength: 6,
					equalTo: "#passcode"
				},
				verifycode: {
					required: true,
				},
			},
			messages: {
				passcode: {
					required: "请输入密码",
					rangelength:"长度介于6和16之间的字符串",
					passcode:"密码为6-16位字符，只能输入英文、数字、‘_’"
				},
				referral_code: {
					required: "请输入邀请码",
					minlength: "邀请码长度为8位",
					maxlength:"邀请码长度为8位"
				},
				passcode_confirmation: {
					required: "请输入密码",
					minlength: "最少为6位",
					equalTo: "两次密码不一致"
				},
				verifycode:{
					required: "请输入短信验证码",
				},
			},
			highlight: function (e) {//错误的显示
				$(e).parent().removeClass('correct');
				$(e).parent().addClass('err');

			},
			success: function (e) {//成功
				$(e).prev().removeClass('err');
				$(e).prev().addClass('correct');
				$(e).remove();
			},
			errorPlacement: function (error, element) {//错误显示位置
				if(element.parent().next().attr('class') == 'red-text'){
					element.parent().next().remove();
				}
				error.insertAfter(element.parent());
			},
			submitHandler: function (form) {//可提交后操作
				var data =$(form).serialize();
				var id = $("#id").val();
				$.ajax({
					url: '/ajax/users/register',
					type: 'post',
					dataType: 'json',
					data: data+'&id='+id+'&registerType=4'
				}).fail(function(XMLHttpRequest, textStatus) {
					$('.red-text').remove();
					if(textStatus == 'error'){
						var errors = XMLHttpRequest.responseJSON.errors;
						$.each(errors,function (name,vale) {
							$("input[name=" + vale.input + "]").parent().after('<p id="' + name + '-error" class="red-text">' + vale.message + '</p>');
							$("input[name=" + vale.input + "]").parent().removeClass('correct');
							$("input[name=" + vale.input + "]").parent().addClass('err');
						});
						$("#verifyCodeReplace img").attr("src","/users/code/"+Math.random());
					}else{
						location.href = '/personalinfoadd';
					}
				});
			}
		});
	}
});