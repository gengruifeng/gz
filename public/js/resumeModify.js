$(document).ready(function() {
	$('.input-startday').datepicker({
		autoclose: true,
		todayHighlight: true,
		format: 'yyyy-mm-dd',
		language: 'zh-CN',
	})



	if($('#imgUpload').length > 0){
		var _token =  $("input[name = '_token']").val();
		resumeUpload(_token);
	}

	// 自定义方法 手机或邮箱
	jQuery.validator.addMethod("checkUsername", function (value, element) {
		return this.optional(element) || (/^1[3|4|5|7|8]\d{9}$/.test(value) || /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(value));
	}, "用户名格式不正确。");

	// 自定义方法 只邮箱
	jQuery.validator.addMethod("emailUsername", function (value, element) {
		return this.optional(element) || /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(value);
	}, "邮箱格式不正确。");

	// 自定义方法 只手机
	jQuery.validator.addMethod("mobUsername", function (value, element) {
		return this.optional(element) || /^1[3|4|5|7|8]\d{9}$/.test(value);
	}, "手机号格式不正确。");

	// 自定义方法 密码
	jQuery.validator.addMethod("passcode", function (value, element) {
		return this.optional(element) || /^[\w_]{6,16}$/.test(value);
	}, "密码为6-16个字符，只能输入英文、数字、‘_’。");

	//自定义方法 名号
	jQuery.validator.addMethod("checkDisplayName", function (value, element) {
		return this.optional(element) || /^[a-zA-Z0-9_\u4e00-\u9fa5]{4,24}$/.test(value);
	}, "名号为4-24位字符：支持中文、英文、数字、‘_’。");

	// 个人信息校检
});

//个人信息
if($('#form-personnal').length > 0){
	$('#form-personnal').validate({
		errorElement: 'p',
		errorClass: 'red-text',
		focusInvalid: true,

		rules: {
			display_name:{
				required:true,
			},
			mobUsername: {//只可手机
				required:true,
				mobUsername :"手机号格式不正确",
			},
			email: {//邮箱
				required: true,
				emailUsername :"邮箱格式不正确",
			},
			province: {//省份
				required: true,

			},
			city: {// 城市
				required: true,
			},
			sex: {// 性别
				required: true,
			},
			birthday: {// 性别
				required: true,
			},

		},
		messages: {
			display_name:{
				required:"请输入用户名。",
			},
			mobUsername:{//用户名 可邮箱、可手机
				required:"请输入手机号。",
			},
			email: {
				required: "请输入邮箱。",
			},
			province: {
				required: "请选择省份。",
			},
			city:{
				required: "请选择城市。",
			},
			sex:{
				required: "请选择性别。",
			},
			birthday:{
				required: "出生日期。",
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
			// var uid = $('#uid').val();
			var data =$(form).serialize();
			$.ajax({
				type:"post",
				url:"/ajax/resume/persons",
				async : false,
				data:data,
				dataType: "json",
				success:function(data) {
					dialogcom_yes_go('保存成功!','/resume/persons',1);
				},error: function(xhr, status, error) {
					if(xhr.status == 401){
						dialoglogin();
						type = false;
					}
					if(xhr.status == 403){
						dialogcom_wrong('页面停留时间过长请刷新页面后再操作!');
						type = false;
					}
				}
			})
		}
	});
}

//求职信息
if($('#form-advices').length > 0){
	$('#form-advices').validate({
		errorElement: 'p',
		errorClass: 'red-text',
		focusInvalid: true,

		rules: {
			word_period:{
				required:true,
			},
			employment_type: {
				required: true,
			},
			city: {
				required: true,
			},
			job_type: {
				required: true,
			},
			salary: {
				required: true,
			},
		},
		messages: {
			word_period:{
				required:"请选择工作年限",
			},
			employment_type: {
				required: "请选择职位类型",
			},
			city: {
				required: "请选择城市",
			},
			job_type: {
				required: "请选择求职状态",
			},
			salary: {
				required: "请选择期望月薪",
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
			// var uid = $('#uid').val();
			var str = $('.inphide').val();
			if(str==''){
				dialogcom_warn('请选择职业!');
			}else{
				var data =$(form).serialize();
				$.ajax({
					type:"post",
					url:"/ajax/resume/advices",
					async : false,
					data:data+"&position="+str,
					dataType: "json",
					success:function(data) {
						dialogcom_yes_go('保存成功!','/resume/advices',1);
					},error: function(xhr, status, error) {
						if(xhr.status == 401){
							dialoglogin();
							type = false;
						}
						if(xhr.status == 403){
							dialogcom_wrong('页面停留时间过长请刷新页面后再操作!');
							type = false;
						}
					}
				})
			}

		}
	});
}

if($('#form-interests').length > 0){
	$('#form-interests').validate({
		errorElement: 'p',
		errorClass: 'red-text',
		focusInvalid: true,

		rules: {
			interests:{
				required:true,
			},
		},
		messages: {
			interests:{
				required:"请填写兴趣爱好。",
			},
		},
		highlight: function (e) {//错误的显示
			$(e).removeClass('correct');
			$(e).addClass('err');

		},
		success: function (e) {//成功
			$(e).prev().removeClass('err');
			$(e).prev().addClass('correct');
			$(e).remove();
		},
		errorPlacement: function (error, element) {//错误显示位置
			if(element.next().attr('class') == 'red-text'){
				element.next().remove();
			}
			error.insertAfter(element);
		},
		submitHandler: function (form) {//可提交后操作
			// var uid = $('#uid').val();
			var data =$(form).serialize();
			$.ajax({
				type:"post",
				url:"/ajax/resume/interests",
				async : false,
				data:data,
				dataType: "json",
				success:function(data) {
					dialogcom_yes_go('保存成功!','/resume/interests',1);
				},error: function(xhr, status, error) {
					if(xhr.status == 401){
						dialoglogin();
						type = false;
					}
					if(xhr.status == 403){
						dialogcom_wrong('页面停留时间过长请刷新页面后再操作!');
						type = false;
					}
				}
			})
		}
	});
}
//教育背景
function education(id) {
	$("#from-education"+id).validate({
		errorElement: 'p',
		errorClass: 'red-text',
		focusInvalid: true,

		rules: {
			school_name:{
				required:true,
			},
			expert_name:{
				required:true,
			},
			time_start:{
				required:true,
			},
			time_end:{
				required:true,
			},
			level:{
				required:true,
			},
		},
		messages: {
			school_name:{
				required:"请填写学校名称。",
			},
			expert_name:{
				required:"请填写专业名称。",
			},
			time_start:{
				required:"请选择时间开始。",
			},
			time_end:{
				required:"请选择毕业时间。",
			},
			level:{
				required:"请选择学历。",
			},
		},
		highlight: function (e) {//错误的显示
			$(e).removeClass('correct');
			$(e).addClass('err');

		},
		success: function (e) {//成功
			$(e).prev().removeClass('err');
			$(e).prev().addClass('correct');
			$(e).remove();
		},
		errorPlacement: function (error, element) {//错误显示位置
			if(element.next().attr('class') == 'red-text'){
				element.next().remove();
			}
			error.insertAfter(element);
		},
		submitHandler: function (form) {//可提交后操作
			// var uid = $('#uid').val();
			var data =$(form).serialize();
			$.ajax({
				type:"post",
				url:"/ajax/resume/educations",
				async : false,
				data:data,
				dataType: "json",
				success:function(data) {
					dialogcom_yes_go('保存成功!','/resume/educations',1);
				},error: function(xhr, status, error) {
					if(xhr.status == 401){
						dialoglogin();
						type = false;
					}
					if(xhr.status == 403){
						dialogcom_wrong('页面停留时间过长请刷新页面后再操作!');
						type = false;
					}
				}
			})
		}
	});
}

if($('#from-educationadd').length > 0){
	$('#from-educationadd').validate({
		errorElement: 'p',
		errorClass: 'red-text',
		focusInvalid: true,

		rules: {
			school_name:{
				required:true,
			},
			expert_name:{
				required:true,
			},
			time_start:{
				required:true,
			},
			time_end:{
				required:true,
			},
			level:{
				required:true,
			},
		},
		messages: {
			school_name:{
				required:"请填写学校名称。",
			},
			expert_name:{
				required:"请填写专业名称。",
			},
			time_start:{
				required:"请选择时间开始。",
			},
			time_end:{
				required:"请选择毕业时间。",
			},
			level:{
				required:"请选择学历。",
			},
		},
		highlight: function (e) {//错误的显示
			$(e).removeClass('correct');
			$(e).addClass('err');

		},
		success: function (e) {//成功
			$(e).prev().removeClass('err');
			$(e).prev().addClass('correct');
			$(e).remove();
		},
		errorPlacement: function (error, element) {//错误显示位置
			if(element.next().attr('class') == 'red-text'){
				element.next().remove();
			}
			error.insertAfter(element);
		},
		submitHandler: function (form) {//可提交后操作
			// var uid = $('#uid').val();
			var data =$(form).serialize();
			$.ajax({
				type:"post",
				url:"/ajax/resume/educations",
				async : false,
				data:data,
				dataType: "json",
				success:function(data) {
					dialogcom_yes_go('新增教育背景成功!','/resume/educations',1);
				},error: function(xhr, status, error) {
					if(xhr.status == 401){
						dialoglogin();
						type = false;
					}
					if(xhr.status == 403){
						dialogcom_wrong('页面停留时间过长请刷新页面后再操作!');
						type = false;
					}
				}
			})
		}
	});
}
//个人经历
function experience(id) {
	$("#from-experience"+id).validate({
		errorElement: 'p',
		errorClass: 'red-text',
		focusInvalid: true,

		rules: {
			company:{
				required:true,
			},
			position:{
				required:true,
			},
			time_start:{
				required:true,
			},
			time_end:{
				required:true,
			},
		},
		messages: {
			company:{
				required:"请填写公司名称。",
			},
			position:{
				required:"请填写职业名称。",
			},
			time_start:{
				required:"请选择。",
			},
			time_end:{
				required:"请选择。",
			},
		},
		highlight: function (e) {//错误的显示
			$(e).removeClass('correct');
			$(e).addClass('err');

		},
		success: function (e) {//成功
			$(e).prev().removeClass('err');
			$(e).prev().addClass('correct');
			$(e).remove();
		},
		errorPlacement: function (error, element) {//错误显示位置
			if(element.next().attr('class') == 'red-text'){
				element.next().remove();
			}
			error.insertAfter(element);
		},
		submitHandler: function (form) {//可提交后操作
			// var uid = $('#uid').val();
			var data =$(form).serialize();
			$.ajax({
				type:"post",
				url:"/ajax/resume/experiences",
				async : false,
				data:data,
				dataType: "json",
				success:function(data) {
					dialogcom_yes_go('保存成功!','/resume/experiences',1);
				},error: function(xhr, status, error) {
					if(xhr.status == 401){
						dialoglogin();
						type = false;
					}
					if(xhr.status == 403){
						dialogcom_wrong('页面停留时间过长请刷新页面后再操作!');
						type = false;
					}
				}
			})
		}
	});
}

if($('#from-experienceadd').length > 0){
	$('#from-experienceadd').validate({
		errorElement: 'p',
		errorClass: 'red-text',
		focusInvalid: true,

		rules: {
			company:{
				required:true,
			},
			position:{
				required:true,
			},
			time_start:{
				required:true,
			},
			time_end:{
				required:true,
			},
		},
		messages: {
			company:{
				required:"请填写公司名称。",
			},
			position:{
				required:"请填写职业名称。",
			},
			time_start:{
				required:"请选择。",
			},
			time_end:{
				required:"请选择。",
			},
		},
		highlight: function (e) {//错误的显示
			$(e).removeClass('correct');
			$(e).addClass('err');

		},
		success: function (e) {//成功
			$(e).prev().removeClass('err');
			$(e).prev().addClass('correct');
			$(e).remove();
		},
		errorPlacement: function (error, element) {//错误显示位置
			if(element.next().attr('class') == 'red-text'){
				element.next().remove();
			}
			error.insertAfter(element);
		},
		submitHandler: function (form) {//可提交后操作
			// var uid = $('#uid').val();
			var data =$(form).serialize();
			$.ajax({
				type:"post",
				url:"/ajax/resume/experiences",
				async : false,
				data:data,
				dataType: "json",
				success:function(data) {
					dialogcom_yes_go('新增个人经历成功!','/resume/experiences',1);
				}
			})
		}
	});
}

//技能证书
function diploma(id) {
	$("#from-diploma"+id).validate({
		errorElement: 'p',
		errorClass: 'red-text',
		focusInvalid: true,

		rules: {
			certificate:{
				required:true,
			},
			achivement:{
				required:true,
			},

		},
		messages: {
			certificate:{
				required:"请填写证书名称。",
			},
			achivement:{
				required:"请填写成绩。",
			},
		},
		highlight: function (e) {//错误的显示
			$(e).removeClass('correct');
			$(e).addClass('err');

		},
		success: function (e) {//成功
			$(e).prev().removeClass('err');
			$(e).prev().addClass('correct');
			$(e).remove();
		},
		errorPlacement: function (error, element) {//错误显示位置
			if(element.next().attr('class') == 'red-text'){
				element.next().remove();
			}
			error.insertAfter(element);
		},
		submitHandler: function (form) {//可提交后操作
			var certificateadd = $("#certificate-edit"+id).val();
			var certificatecope = $("#certificate-editcope"+id).val();
			var certificate = '';
			if(certificateadd != ''){
				certificate = certificateadd;
			}else if(certificatecope != ''){
				certificate = certificatecope;
			}
			if(certificate != ''){
				var data =$(form).serialize();
				$.ajax({
					type:"post",
					url:"/ajax/resume/diplomas",
					async : false,
					data:data+"&certificate="+certificate,
					dataType: "json",
					success:function(data) {
						dialogcom_yes_go('保存成功!', '/resume/diplomas',1);
					}
				})
			}else{
				dialogcom_warn('请选择证书!');
			}
		}
	});
}

if($('#from-diplomaadd').length > 0){
	$('#from-diplomaadd').validate({
		errorElement: 'p',
		errorClass: 'red-text',
		focusInvalid: true,
		rules: {
			achivement:{
				required:true,
			},

		},
		messages: {
			achivement:{
				required:"请填写成绩。",
			},
		},
		highlight: function (e) {//错误的显示
			$(e).removeClass('correct');
			$(e).addClass('err');

		},
		success: function (e) {//成功
			$(e).prev().removeClass('err');
			$(e).prev().addClass('correct');
			$(e).remove();
		},
		errorPlacement: function (error, element) {//错误显示位置
			if(element.next().attr('class') == 'red-text'){
				element.next().remove();
			}
			error.insertAfter(element);
		},
		submitHandler: function (form) {//可提交后操作
			// var uid = $('#uid').val();
			var certificateadd = $("#certificate-add").val();
			var certificatecope = $("#certificate-cope").val();
			var certificate = '';
			if(certificateadd != ''){
				certificate = certificateadd;
			}else if(certificatecope != ''){
				certificate = certificatecope;
			}
			if(certificate != ''){
				var data =$(form).serialize();
				$.ajax({
					type:"post",
					url:"/ajax/resume/diplomas",
					async : false,
					data:data,
					dataType: "json",
					success:function(data) {
						dialogcom_yes_go('新增技能证书成功!','/resume/diplomas',1);
					}
				})
			}else{
				dialogcom_warn('请选择证书!');
			}

		}
	});
}

//奖项荣誉
function honor(id) {
	$("#from-honor"+id).validate({
		errorElement: 'p',
		errorClass: 'red-text',
		focusInvalid: true,
		rules: {
			received_at:{
				required:true,
			},
			award:{
				required:true,
			},

		},
		messages: {
			received_at:{
				required:"请填写获得时间。",
			},
			award:{
				required:"请填写取得成就。",
			},
		},
		highlight: function (e) {//错误的显示
			$(e).removeClass('correct');
			$(e).addClass('err');

		},
		success: function (e) {//成功
			$(e).prev().removeClass('err');
			$(e).prev().addClass('correct');
			$(e).remove();
		},
		errorPlacement: function (error, element) {//错误显示位置
			if(element.next().attr('class') == 'red-text'){
				element.next().remove();
			}
			error.insertAfter(element);
		},
		submitHandler: function (form) {//可提交后操作
			// var uid = $('#uid').val();
			var data =$(form).serialize();
			$.ajax({
				type:"post",
				url:"/ajax/resume/honors",
				async : false,
				data:data,
				dataType: "json",
				success:function(data) {
					dialogcom_yes_go('保存成功!','/resume/honors',1);
				},error: function(xhr, status, error) {
					if(xhr.status == 401){
						dialoglogin();
						type = false;
					}
					if(xhr.status == 403){
						dialogcom_wrong('页面停留时间过长请刷新页面后再操作!');
						type = false;
					}
				}
			})
		}
	});
}

if($('#from-honoradd').length > 0){
	$('#from-honoradd').validate({
		errorElement: 'p',
		errorClass: 'red-text',
		focusInvalid: true,
		rules: {
			received_at:{
				required:true,
			},
			award:{
				required:true,
			},

		},
		messages: {
			received_at:{
				required:"请填写获得时间。",
			},
			award:{
				required:"请填写取得成就。",
			},
		},
		highlight: function (e) {//错误的显示
			if($(e).attr('name') != 'received_at'){
				$(e).addClass('err');
			}
			$(e).removeClass('correct');

		},
		success: function (e) {//成功
			$(e).prev().removeClass('err');
			$(e).prev().addClass('correct');
			$(e).remove();
		},
		errorPlacement: function (error, element) {//错误显示位置
			if(element.next().attr('class') == 'red-text'){
				element.next().remove();
			}
			error.insertAfter(element);
		},
		submitHandler: function (form) {//可提交后操作
			// var uid = $('#uid').val();
			var data =$(form).serialize();
			$.ajax({
				type:"post",
				url:"/ajax/resume/honors",
				async : false,
				data:data,
				dataType: "json",
				success:function(data) {
					dialogcom_yes_go('新增奖项荣誉成功!','/resume/honors',1);
				},error: function(xhr, status, error) {
					if(xhr.status == 401){
						dialoglogin();
						type = false;
					}
					if(xhr.status == 403){
						dialogcom_wrong('页面停留时间过长请刷新页面后再操作!');
						type = false;
					}
				}
			})
		}
	});
}

//个人作品
function project(id) {
	$("#form-project"+id).validate({
		errorElement: 'p',
		errorClass: 'red-text',
		focusInvalid: true,
		rules: {
			worked_at:{
				required:true,
			},
			title:{
				required:true,
			},
			description:{
				required:true,
			},

		},
		messages: {
			worked_at:{
				required:"请填写完成时间"
			},
			title:{
				required:"请填写作品名称"
			},
			description:{
				required:"请填写作品描述"
			}
		},
		highlight: function (e) {//错误的显示
			$(e).removeClass('correct');
			$(e).addClass('err');

		},
		success: function (e) {//成功
			$(e).prev().removeClass('err');
			$(e).prev().addClass('correct');
			$(e).remove();
		},
		errorPlacement: function (error, element) {//错误显示位置
			if(element.next().attr('class') == 'red-text'){
				element.next().remove();
			}
			error.insertAfter(element);
		},
		submitHandler: function (form) {//可提交后操作
			// var uid = $('#uid').val();
			var data =$(form).serialize();
			$.ajax({
				type:"post",
				url:"/ajax/resume/projects",
				async : false,
				data:data,
				dataType: "json",
				success:function(data) {
					dialogcom_yes_go('保存成功!','/resume/projects',1);
				},error: function(xhr, status, error) {
					if(xhr.status == 401){
						dialoglogin();
						type = false;
					}
					if(xhr.status == 403){
						dialogcom_wrong('页面停留时间过长请刷新页面后再操作!');
						type = false;
					}
				}
			})
		}
	});
}
if($('#form-projectadd').length > 0){
	$('#form-projectadd').validate({
		errorElement: 'p',
		errorClass: 'red-text',
		focusInvalid: true,
		rules: {
			worked_at:{
				required:true,
			},
			title:{
				required:true,
			},
			description:{
				required:true,
			},

		},
		messages: {
			worked_at:{
				required:"请填写完成时间。",
			},
			title:{
				required:"请填写作品名称。",
			},
			description:{
				required:"请填写作品描述。",
			},
		},
		highlight: function (e) {//错误的显示
			$(e).removeClass('correct');
			$(e).addClass('err');

		},
		success: function (e) {//成功
			$(e).prev().removeClass('err');
			$(e).prev().addClass('correct');
			$(e).remove();
		},
		errorPlacement: function (error, element) {//错误显示位置
			if(element.next().attr('class') == 'red-text'){
				element.next().remove();
			}
			error.insertAfter(element);
		},
		submitHandler: function (form) {//可提交后操作
			// var uid = $('#uid').val();
			var data =$(form).serialize();
			$.ajax({
				type:"post",
				url:"/ajax/resume/projects",
				async : false,
				data:data,
				dataType: "json",
				success:function(data) {
					dialogcom_yes_go('新增个人作品成功!','/resume/projects',1);
				},error: function(xhr, status, error) {
					if(xhr.status == 401){
						dialoglogin();
						type = false;
					}
					if(xhr.status == 403){
						dialogcom_wrong('页面停留时间过长请刷新页面后再操作!');
						type = false;
					}
				}
			})
		}
	});
}
// 修改简历
$(function(){
	// 编辑 切换状态
	$('.btn-modify').on('click',function(){
		$(this).closest('.cnt').find('.cnt-inner').eq(0).hide();
		$(this).closest('.cnt').find('.cnt-inner').eq(1).show();
		$(this).closest('.pane').find('.addmore').hide();
		$('.btn-modify').hide();
	});
	$('.btn-cancel').on('click',function(){
		// $(this).closest('.cnt').find('.cnt-inner').eq(0).show();
		// $(this).closest('.cnt').find('.cnt-inner').eq(1).hide();
		// $(this).closest('.pane').find('.addmore').show();
		// $('.btn-modify').show();
		// return false;
		window.location.reload();
	});
	$('.addmore').on('click',function(){
		$('.resume-modify .modify-mainlist .cnt').show();
		$(this).closest('.pane').find('.addmore').hide();
	});


// 个人信息 头像
	$('.modify-personnal .person-img').on({
		'mouseenter': function () {
			$(this).find('.hover').show();
		},
		'mouseleave': function () {
			$(this).find('.hover').hide();
		}
	});


	// // 学校选择
	// $('.academy-city a').on('click',function(){
	// 	$('.academy-city').hide();
	// 	$('.academy-name').show();
	// })
	// $('.academy-name h2').on('click',function(){
	// 	$('.academy-city').show();
	// 	$('.academy-name').hide();
	// })
	// $('.academy-name .btm a').on('click',function(){
	// 	_value=$(this).html();
	// 	$(this).closest('dd').find('.inpbtn ').val(_value).removeClass('active');
	// 	$("#academy").hide();
	//
	// })
});
//添加更多
$('.addmore').on('click',function(){
	$(this).hide();
	$(this).closest('.pane').find('.cnt-new').show();

});
$('.cnt-new .btn-cancel').on('click',function(){
	$(this).closest('.cnt-new').hide();
	$(this).closest('.pane').find('.addmore').show();
	return false;
});

// 教育背景
function showschool(th,event) {
	$('.slidmsg').addClass('hide');
	$('.academy-name').addClass('hide');
	$('.academy-city').removeClass('hide');
	$('.school_name').removeClass('active');
	if($(th).hasClass('active')){
		$(th).removeClass('active');
		$(th).parent().next().addClass('hide');
	}else{
		$(th).addClass('active');
		$(th).parent().next().removeClass('hide');
	}
	event = event || window.event;
	if (event.stopPropagation) {
		event.stopPropagation();
	} else {
		event.cancelBubble = true;
	}

	$('.academy-name h2').on('click',function(event){
		$('.academy-city').removeClass('hide');
		$('.academy-name').addClass('hide');
		event = event || window.event;
		if (event.stopPropagation) {
			event.stopPropagation();
		} else {
			event.cancelBubble = true;
		}
	});
	$('.academy-name .btm a').on('click',function(event){

		_value=$(this).html();

		$(this).parent().parent().parent().prev().find('input').eq(0).val(_value).removeClass('active');
		$('.slidmsg').addClass('hide');
		event = event || window.event;
		if (event.stopPropagation) {
			event.stopPropagation();
		} else {
			event.cancelBubble = true;
		}
		if($(th).val() != '' && $(th).next().attr('id') == 'school_name-error'){
			$(th).next().remove();
		}
	})
}
// 技能证书
function showdiplomas(th,event) {
	$('.slidmsg').addClass('hide');
	$('.academy-name').addClass('hide');
	$('.academy-city').removeClass('hide');
	$('.school_name').removeClass('active');
	if($(th).hasClass('active')){
		$(th).removeClass('active');
		$(th).parent().next().addClass('hide');
	}else{
		$(th).addClass('active');
		$(th).parent().next().removeClass('hide');
	}
	event = event || window.event;
	if (event.stopPropagation) {
		event.stopPropagation();
	} else {
		event.cancelBubble = true;
	}

	$('.academy-name h2').on('click',function(event){
		$('.academy-city').removeClass('hide');
		$('.academy-name').addClass('hide');
		event = event || window.event;
		if (event.stopPropagation) {
			event.stopPropagation();
		} else {
			event.cancelBubble = true;
		}
	});
	$('.academy-name .btm a').on('click',function(event){
		_value=$(this).html();
		$(this).parent().parent().parent().prev().find('input').eq(0).val(_value).removeClass('active');
		$("input[name='certificate-cope']").val('');
		$(th).parent().parent().next().addClass('hide');
		$('.slidmsg').addClass('hide');
		event = event || window.event;
		if (event.stopPropagation) {
			event.stopPropagation();
		} else {
			event.cancelBubble = true;
		}
	})
}
// 点其它地方消失
$(document).on('click',function(){
	$('.inpbtn').removeClass('active');
	if($(this).find('.slidmsg').length>0){
		$('.slidmsg').addClass('hide');
	}
});

function city(th,cityid,event) {
	$(th).parent().addClass('hide');
	$(th).parent().parent().find('.city' + cityid).eq(0).removeClass('hide');
	ccccccid = $(th).attr('data-city');
	event = event || window.event;
	if (event.stopPropagation) {
		event.stopPropagation();
	} else {
		event.cancelBubble = true;
	}
}

//个人经历删除
function experiencedel(id) {
	$(function () {
		$("#dialog").dialog({
			modal:true,
			width:440,
			height:180,
			dialogClass: "no-close",
			buttons: [{
				text: "取消",
				click: function() {
					$(this).dialog("close");
				}
			},
				{
					text: "确定",
					click: function() {
						$(this).dialog("close");
						$.ajax({
							type:"post",
							url:"/ajax/resume/experiencedel",
							async : false,
							data:{id:id,_token:$("input[name = '_token']").val()},
							dataType: "json",
							success:function(data) {
								location.href = '/resume/experiences';
							},error: function(xhr, status, error) {
								if(xhr.status == 401){
									dialoglogin();
									type = false;
								}
								if(xhr.status == 403){
									dialogcom_wrong('页面停留时间过长请刷新页面后再操作!');
									type = false;
								}
							}
						})
					}
				}
			]
		});
	});
}
//技能证书删除
function diplomadel(id) {
	$(function () {
		$("#dialog").dialog({
			modal:true,
			width:440,
			height:180,
			dialogClass: "no-close",
			buttons: [{
				text: "取消",
				click: function() {
					$(this).dialog("close");
				}
			},
				{
					text: "确定",
					click: function() {
						$(this).dialog("close");
						$.ajax({
							type:"post",
							url:"/ajax/resume/diplomadel",
							async : false,
							data:{id:id,_token:$("input[name = '_token']").val()},
							dataType: "json",
							success:function(data) {
								location.href = '/resume/diplomas';
							},error: function(xhr, status, error) {
								if(xhr.status == 401){
									dialoglogin();
									type = false;
								}
								if(xhr.status == 403){
									dialogcom_wrong('页面停留时间过长请刷新页面后再操作!');
									type = false;
								}
							}
						})
					}
				}
			]
		});
	});
}
//奖项荣誉删除
function honordel(id) {
	$(function () {
		$("#dialog").dialog({
			modal:true,
			width:440,
			height:180,
			dialogClass: "no-close",
			buttons: [{
				text: "取消",
				click: function() {
					$(this).dialog("close");
				}
			},
				{
					text: "确定",
					click: function() {
						$(this).dialog("close");
						$.ajax({
							type:"post",
							url:"/ajax/resume/honordel",
							async : false,
							data:{id:id,_token:$("input[name = '_token']").val()},
							dataType: "json",
							success:function(data) {
								location.href = '/resume/honors';
							},error: function(xhr, status, error) {
								if(xhr.status == 401){
									dialoglogin();
									type = false;
								}
								if(xhr.status == 403){
									dialogcom_wrong('页面停留时间过长请刷新页面后再操作!');
									type = false;
								}
							}
						})
					}
				}
			]
		});
	});
}
//教育背景删除
function educationdel(id) {
	$(function () {
		$("#dialog").dialog({
			modal:true,
			width:440,
			height:180,
			dialogClass: "no-close",
			buttons: [{
				text: "取消",
				click: function() {
					$(this).dialog("close");
				}
			},
				{
					text: "确定",
					click: function() {
						$(this).dialog("close");
						$.ajax({
							type:"post",
							url:"/ajax/resume/educationdel",
							async : false,
							data:{id:id,_token:$("input[name = '_token']").val()},
							dataType: "json",
							success:function(data) {
								location.href = '/resume/educations';
							},error: function(xhr, status, error) {
								if(xhr.status == 401){
									dialoglogin();
									type = false;
								}
								if(xhr.status == 403){
									dialogcom_wrong('页面停留时间过长请刷新页面后再操作!');
									type = false;
								}
							}
						})
					}
				}
			]
		});
	});
}
//个人作品删除
function projectdel(id) {
	$(function () {
		$("#dialog").dialog({
			modal:true,
			width:440,
			height:180,
			dialogClass: "no-close",
			buttons: [{
				text: "取消",
				click: function() {
					$(this).dialog("close");
				}
			},
				{
					text: "确定",
					click: function() {
						$(this).dialog("close");
						$.ajax({
							type:"post",
							url:"/ajax/resume/projectdel",
							async : false,
							data:{id:id,_token:$("input[name = '_token']").val()},
							dataType: "json",
							success:function(data) {
								location.href = '/resume/projects';
							},error: function(xhr, status, error) {
								if(xhr.status == 401){
									dialoglogin();
									type = false;
								}
								if(xhr.status == 403){
									dialogcom_wrong('页面停留时间过长请刷新页面后再操作!');
									type = false;
								}
							}
						})
					}
				}
			]
		});
	});
}
function resumeselect(){
	$.ajax({
		type:"post",
		url:"/ajax/resume/checkselect",
		async : false,
		data:{_token:$("input[name = '_token']").val()},
		dataType: "json",
		success:function(data) {
			location.href = '/resume/select';
		},error: function(xhr, status, error) {
				if(xhr.status == 401){
					dialoglogin();
					type = false;
				}
				if(xhr.status == 403){
					dialogcom_wrong('页面停留时间过长请刷新页面后再操作!');
					type = false;
				}
			if (400 === xhr.status) {
				dialogcom_warn(xhr.responseJSON.errors[0].message);
			} else {
				dialogcom_warn('简历不完善，请把个人信息、教育背景、个人经历的必填项完善哦~');
			}
		}
	})

}


// 自动搜查
$(function () {
	var cache = {};

	$("input[name='expert_name']").autocomplete({
		minLength: 2,
		source: function (request, response) {
			var q = request.term;

			for (q in cache) {
				response(cache[q]);
			}

			$.getJSON("/majors/search", {q: request.term}, function (data, status, xhr) {
				var i,
					l = data.length,
					tmp = null,
					map = [];

				for (i = 0; i < l; i++) {
					tmp = {
						value: data[i].name,
						label: data[i].name,
						source_id: data[i].source_id,
						id: data[i].id
					};

					map.push(tmp);
				}

				cache[q] = map;
				response(map);
			});
		},
		select: function (event, ui) {
			// window.location.href = "/questions/" + ui.item.id;
		}
	});
});

// 自动搜查
$(function () {
	var cache = {};

	$("input[name='seachschool']").on('click',function(event){
		$(this).autocomplete({
			minLength: 2,
			source: function (request, response) {
				var q = request.term;
				for (q in cache) {
					response(cache[q]);
				}

				$.getJSON("/school/search", {q: request.term,cityid:ccccccid}, function (data, status, xhr) {
					var i,
						l = data.length,
						tmp = null,
						map = [];

					for (i = 0; i < l; i++) {
						tmp = {
							value: data[i].name,
							label: data[i].name,
							source_id: data[i].source_id,
							id: data[i].id
						};

						map.push(tmp);
					}

					cache[q] = map;
					response(map);
				});
			},
			select: function (event, ui) {
				$('.slidmsg').addClass('hide');
				$("input[name='school_name']").val(ui.item.label);
			}
		});
		event = event || window.event;
		if (event.stopPropagation) {
			event.stopPropagation();
		} else {
			event.cancelBubble = true;
		}
	})
	if($("input[name='received_at']").length > 0){
		$("input[name='received_at']").on('change',function(event){
			if($(this).val() != '' && $(this).next().attr('id') == 'received_at-error'){
				$(this).next().remove();
			}
		})
	}

	if($("input[name='worked_at']").length > 0){
		$("input[name='worked_at']").on('change',function(event){
			if($(this).val() != '' && $(this).next().attr('id') == 'worked_at-error'){
				$(this).next().remove();
			}
		})
	}
	if($("input[name='time_start']").length > 0){
		$("input[name='time_start']").on('change',function(event){
			if($(this).val() != '' && $(this).next().attr('id') == 'time_start-error'){
				$(this).next().remove();
			}
		})
	}
	if($("input[name='time_end']").length > 0){
		$("input[name='time_end']").on('change',function(event){
			if($(this).val() != '' && $(this).next().attr('id') == 'time_end-error'){
				$(this).next().remove();
			}
		})
	}


});
